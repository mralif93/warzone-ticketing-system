# Stripe Checkout Amount Flow

## Key Function: `createPaymentIntent()`

**File:** `app/Http/Controllers/Payment/StripeController.php`

### Step-by-Step Flow:

1. **Input: Order ID**
   - Validates: `order_id` must exist in `orders` table
   - Retrieves: Order with user authentication check

2. **Amount Calculation (Line 60)**
   ```php
   $totalAmount = $order->total_amount * 100;
   ```
   - **Source**: `$order->total_amount` (from database)
   - **Conversion**: Multiplies by 100 to convert from RM to cents (Stripe requirement)
   - **Example**: If `total_amount = 2250.00` → `$totalAmount = 225000` cents

3. **Stripe Payment Intent Creation (Line 66-83)**
   ```php
   $paymentIntent = PaymentIntent::create([
       'amount' => $totalAmount,      // ← THIS IS SENT TO STRIPE (in cents)
       'currency' => 'myr',
       'customer' => $customer->id,
       'metadata' => [
           'order_id' => $order->id,
           'user_id' => Auth::id(),
       ],
       // ... payment method types
   ]);
   ```

## Order Total Amount Calculation

**File:** `app/Http/Controllers/TicketController.php` (Line 233-236)

The `order->total_amount` is calculated as:
```php
$subtotal = (ticket prices * quantities) - discount;
$serviceFee = $subtotal * (serviceFeePercentage / 100);
$taxAmount = ($subtotal + $serviceFee) * (taxPercentage / 100);
$totalAmount = $subtotal + $serviceFee + $taxAmount;
```

### Formula:
```
Total Amount = Subtotal + Service Fee + Tax
            = (Original Subtotal - Discount) + Service Fee + Tax
```

## What Goes to Stripe:

**Input to Stripe:**
- `amount`: `$order->total_amount * 100` (in cents, e.g., 225000 for RM2,250.00)
- `currency`: `'myr'` (Malaysian Ringgit)
- `customer`: Stripe customer ID
- `metadata`: Contains `order_id` and `user_id`

**Important:**
- Stripe receives the amount in **cents** (smallest currency unit)
- Amount is calculated from `order.total_amount` field in database
- This amount includes: Subtotal - Discount + Service Fee + Tax

