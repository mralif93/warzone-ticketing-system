<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Event;
use App\Models\PurchaseTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Customer;
use Stripe\Exception\CardException;
use Stripe\Exception\RateLimitException;
use Stripe\Exception\InvalidRequestException;
use Stripe\Exception\AuthenticationException;
use Stripe\Exception\ApiConnectionException;
use Stripe\Exception\ApiErrorException;

class StripeController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a payment intent for an order
     */
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        try {
            $order = Order::with(['purchaseTickets.ticketType.event'])
                ->where('id', $request->order_id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$order) {
                return response()->json([
                    'error' => 'Order not found or access denied'
                ], 404);
            }

            if ($order->status !== 'pending') {
                return response()->json([
                    'error' => 'Order is not in pending status'
                ], 400);
            }

            // Calculate total amount (in cents)
            $totalAmount = $order->total_amount * 100;

            // Create or retrieve Stripe customer
            $customer = $this->getOrCreateStripeCustomer(Auth::user());

            // Create payment intent with Malaysian payment methods
            // Note: Cannot use both automatic_payment_methods and payment_method_types together
            // We specify payment_method_types explicitly to control which methods are available
            $paymentIntent = PaymentIntent::create([
                'amount' => $totalAmount,
                'currency' => 'myr', // Malaysian Ringgit
                'customer' => $customer->id,
                'metadata' => [
                    'order_id' => $order->id,
                    'customer_email' => Auth::user()->email,
                ],
                'payment_method_types' => [
                    'card',           // Visa, Mastercard
                    'fpx',            // Malaysian bank transfers
                ],
            ]);

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
            ]);

        } catch (ApiErrorException $e) {
            Log::error('Stripe API Error in createPaymentIntent: ' . $e->getMessage());
            Log::error('Stripe API Error Details: ' . json_encode([
                'type' => get_class($e),
                'code' => $e->getStripeCode(),
                'stripe_code' => $e->getStripeCode(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]));
            return response()->json([
                'error' => 'Payment processing error. Please try again.',
                'message' => $e->getMessage(), // Include in response for debugging
            ], 500);
        } catch (\Exception $e) {
            Log::error('Payment Intent Creation Error: ' . $e->getMessage());
            Log::error('Exception Type: ' . get_class($e));
            Log::error('Stack Trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'An unexpected error occurred. Please try again.',
                'message' => $e->getMessage(), // Include in response for debugging
            ], 500);
        }
    }

    /**
     * Handle successful payment
     */
    public function handlePaymentSuccess(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
            'order_id' => 'required|exists:orders,id',
        ]);

        try {
            DB::beginTransaction();

            // Retrieve payment intent from Stripe
            $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);

            if ($paymentIntent->status !== 'succeeded') {
                return response()->json([
                    'error' => 'Payment was not successful'
                ], 400);
            }

            $order = Order::with(['purchaseTickets.ticketType.event'])
                ->where('id', $request->order_id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$order) {
                return response()->json([
                    'error' => 'Order not found'
                ], 404);
            }

            // Create payment record (store order payment_method if available, otherwise stripe)
            $payment = Payment::create([
                'order_id' => $order->id,
                'method' => $order->payment_method ?? 'stripe',
                'transaction_id' => $paymentIntent->id,
                'amount' => $paymentIntent->amount / 100, // Convert back from cents
                'currency' => $paymentIntent->currency,
                'status' => 'succeeded',
                'payment_details' => json_encode([
                    'stripe_payment_intent_id' => $paymentIntent->id,
                    'payment_method_id' => $paymentIntent->payment_method,
                    'customer_id' => $paymentIntent->customer,
                    'charges' => $paymentIntent->charges->data,
                ]),
            ]);

            // Update order status
            $order->update([
                'status' => 'paid',
                'payment_id' => $payment->id,
                'paid_at' => now(),
            ]);

            // Update all purchase ticket statuses to 'active'
            $order->purchaseTickets()->update(['status' => 'active']);
            
            // Log the synchronization
            Log::info('Payment success - Status synced', [
                'payment_id' => $payment->id,
                'order_id' => $order->id,
                'payment_status' => 'succeeded',
                'order_status' => 'paid',
                'tickets_updated' => $order->purchaseTickets()->count()
            ]);

            // Note: Inventory and availability are tracked via PurchaseTicket records and
            // Ticket model helper methods elsewhere. Avoid mutating quantities here to
            // prevent inconsistencies or undefined relations during payment callback.

            // Log payment to audit trail
            \App\Models\AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'CREATE',
                'table_name' => 'payments',
                'record_id' => $payment->id,
                'old_values' => null,
                'new_values' => $payment->toArray(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'description' => "Payment created: Stripe payment #{$payment->transaction_id} for Order #{$order->order_number} - RM{$payment->amount}"
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment completed successfully',
                'payment_id' => $payment->id,
                'order_id' => $order->id,
            ]);

        } catch (ApiErrorException $e) {
            DB::rollBack();
            Log::error('Stripe Payment Success Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Payment verification failed. Please contact support.'
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment Success Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'An unexpected error occurred. Please contact support.'
            ], 500);
        }
    }

    /**
     * Handle payment failure
     */
    public function handlePaymentFailure(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
            'order_id' => 'required|exists:orders,id',
        ]);

        try {
            DB::beginTransaction();

            $order = Order::where('id', $request->order_id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$order) {
                return response()->json([
                    'error' => 'Order not found'
                ], 404);
            }

            // Create failed payment record
            Payment::create([
                'order_id' => $order->id,
                'method' => $order->payment_method ?? 'stripe',
                'transaction_id' => $request->payment_intent_id,
                'amount' => $order->total_amount,
                'currency' => 'myr',
                'status' => 'failed',
                'payment_details' => json_encode([
                    'stripe_payment_intent_id' => $request->payment_intent_id,
                    'failure_reason' => 'Payment failed or was cancelled',
                ]),
            ]);

            // Update order status to pending (keep order active for retry)
            $order->update(['status' => 'pending']);

            // Update purchase ticket statuses back to pending
            $order->purchaseTickets()->update(['status' => 'pending']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment failure recorded',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment Failure Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }

    /**
     * Get payment status
     */
    public function getPaymentStatus(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
        ]);

        try {
            $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);

            return response()->json([
                'status' => $paymentIntent->status,
                'amount' => $paymentIntent->amount / 100,
                'currency' => $paymentIntent->currency,
                'created' => $paymentIntent->created,
            ]);

        } catch (ApiErrorException $e) {
            Log::error('Stripe Status Check Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Unable to check payment status'
            ], 500);
        }
    }

    /**
     * Get or create Stripe customer
     */
    private function getOrCreateStripeCustomer($user)
    {
        try {
            // Note: Customer::all() doesn't support filtering by email directly
            // Instead, we'll search for customers by email using list method
            // For simplicity, we'll create a new customer each time (Stripe handles duplicates)
            // Or we can store Stripe customer ID in users table for better performance
            
            // Try to search for existing customer by email
            try {
                $customers = Customer::all([
                    'limit' => 100, // Get up to 100 customers
                ]);
                
                // Manually filter by email
                foreach ($customers->data as $customer) {
                    if ($customer->email === $user->email) {
                        Log::info('Found existing Stripe customer: ' . $customer->id);
                        return $customer;
                    }
                }
            } catch (\Exception $e) {
                // If listing fails, just create a new customer
                Log::warning('Could not search for existing Stripe customer: ' . $e->getMessage());
            }

            // Create new customer
            $customer = Customer::create([
                'email' => $user->email,
                'name' => $user->name ?? 'Customer',
                'metadata' => [
                    'user_id' => $user->id,
                ],
            ]);
            
            Log::info('Created new Stripe customer: ' . $customer->id);
            return $customer;

        } catch (ApiErrorException $e) {
            Log::error('Stripe Customer Creation Error: ' . $e->getMessage());
            Log::error('Stripe Customer Error Details: ' . json_encode([
                'type' => get_class($e),
                'code' => method_exists($e, 'getStripeCode') ? $e->getStripeCode() : null,
                'message' => $e->getMessage(),
            ]));
            throw $e;
        } catch (\Exception $e) {
            Log::error('Unexpected error in getOrCreateStripeCustomer: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get payment methods for customer
     */
    public function getPaymentMethods()
    {
        try {
            $customer = $this->getOrCreateStripeCustomer(Auth::user());
            
            $paymentMethods = PaymentMethod::all([
                'customer' => $customer->id,
                'type' => 'card',
            ]);

            return response()->json([
                'payment_methods' => $paymentMethods->data,
            ]);

        } catch (ApiErrorException $e) {
            Log::error('Stripe Payment Methods Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Unable to retrieve payment methods'
            ], 500);
        }
    }

    /**
     * Create payment method
     */
    public function createPaymentMethod(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required|string',
        ]);

        try {
            $customer = $this->getOrCreateStripeCustomer(Auth::user());
            
            $paymentMethod = PaymentMethod::retrieve($request->payment_method_id);
            $paymentMethod->attach(['customer' => $customer->id]);

            return response()->json([
                'success' => true,
                'message' => 'Payment method saved successfully',
            ]);

        } catch (ApiErrorException $e) {
            Log::error('Stripe Payment Method Creation Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Unable to save payment method'
            ], 500);
        }
    }

    /**
     * Webhook handler for Stripe events
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                $endpointSecret
            );
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid payload: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Invalid signature: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->handlePaymentIntentSucceeded($event->data->object);
                break;
            case 'payment_intent.payment_failed':
                $this->handlePaymentIntentFailed($event->data->object);
                break;
            default:
                Log::info('Unhandled event type: ' . $event->type);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle successful payment intent from webhook
     */
    private function handlePaymentIntentSucceeded($paymentIntent)
    {
        try {
            $orderId = $paymentIntent->metadata->order_id ?? null;
            
            if (!$orderId) {
                Log::error('No order_id in payment intent metadata');
                return;
            }

            $order = Order::find($orderId);
            if (!$order) {
                Log::error('Order not found: ' . $orderId);
                return;
            }

            // Update order and create payment record if not already done
            if ($order->status === 'pending') {
                DB::beginTransaction();

                $payment = Payment::create([
                    'order_id' => $order->id,
                    'method' => $order->payment_method ?? 'stripe',
                    'transaction_id' => $paymentIntent->id,
                    'amount' => $paymentIntent->amount / 100,
                    'currency' => $paymentIntent->currency,
                    'status' => 'succeeded',
                    'payment_details' => json_encode($paymentIntent),
                ]);

                $order->update([
                    'status' => 'paid',
                    'payment_id' => $payment->id,
                    'paid_at' => now(),
                ]);

                // Update all purchase ticket statuses to 'active'
                $order->purchaseTickets()->update(['status' => 'active']);

                DB::commit();
                
                // Log the synchronization
                Log::info('Webhook payment success - Status synced', [
                    'payment_id' => $payment->id,
                    'order_id' => $order->id,
                    'payment_status' => 'succeeded',
                    'order_status' => 'paid',
                    'tickets_updated' => $order->purchaseTickets()->count()
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Webhook Payment Success Error: ' . $e->getMessage());
        }
    }

    /**
     * Handle failed payment intent from webhook
     */
    private function handlePaymentIntentFailed($paymentIntent)
    {
        try {
            $orderId = $paymentIntent->metadata->order_id ?? null;
            
            if (!$orderId) {
                Log::error('No order_id in payment intent metadata');
                return;
            }

            $order = Order::find($orderId);
            if (!$order) {
                Log::error('Order not found: ' . $orderId);
                return;
            }

            // Update order status to pending (keep order active for retry)
            $order->update(['status' => 'pending']);

            // Update purchase ticket statuses back to pending
            $order->purchaseTickets()->update(['status' => 'pending']);
            
            // Log the synchronization
            Log::info('Payment failure - Status synced', [
                'order_id' => $orderId,
                'payment_status' => 'failed',
                'order_status' => 'pending',
                'tickets_updated' => $order->purchaseTickets()->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Webhook Payment Failure Error: ' . $e->getMessage());
        }
    }
}