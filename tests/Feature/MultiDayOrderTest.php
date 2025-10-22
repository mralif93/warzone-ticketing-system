<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Order;
use App\Models\Setting;

class MultiDayOrderTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;
    protected $customer;
    protected $multiDayEvent;
    protected $singleDayEvent;
    protected $ticket1;
    protected $ticket2;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'administrator',
            'status' => 'active'
        ]);

        // Create customer user
        $this->customer = User::create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'status' => 'active'
        ]);

        // Create multi-day event
        $this->multiDayEvent = Event::create([
            'name' => 'Multi-Day Test Event',
            'description' => 'A test event spanning multiple days',
            'venue' => 'Test Venue',
            'date_time' => '2025-12-01 10:00:00',
            'start_date' => '2025-12-01 10:00:00',
            'end_date' => '2025-12-02 18:00:00',
            'status' => 'on_sale',
            'combo_discount_enabled' => true,
            'combo_discount_percentage' => 10.0
        ]);

        // Create single-day event
        $this->singleDayEvent = Event::create([
            'name' => 'Single Day Test Event',
            'description' => 'A test event for single day',
            'venue' => 'Test Venue',
            'date_time' => '2025-12-15 10:00:00',
            'end_date_time' => '2025-12-15 18:00:00',
            'status' => 'on_sale',
            'combo_discount_enabled' => false,
            'combo_discount_percentage' => 0.0
        ]);

        // Create tickets for multi-day event
        $this->ticket1 = Ticket::create([
            'event_id' => $this->multiDayEvent->id,
            'name' => 'VIP Ticket',
            'description' => 'VIP access for both days',
            'price' => 100.00,
            'total_seats' => 50,
            'available_seats' => 50,
            'status' => 'active'
        ]);

        $this->ticket2 = Ticket::create([
            'event_id' => $this->multiDayEvent->id,
            'name' => 'Standard Ticket',
            'description' => 'Standard access for both days',
            'price' => 50.00,
            'total_seats' => 100,
            'available_seats' => 100,
            'status' => 'active'
        ]);

        // Create ticket for single-day event
        Ticket::create([
            'event_id' => $this->singleDayEvent->id,
            'name' => 'Single Day Ticket',
            'description' => 'Access for single day',
            'price' => 75.00,
            'total_seats' => 30,
            'available_seats' => 30,
            'status' => 'active'
        ]);

        // Set up settings
        Setting::create([
            'key' => 'service_fee_percentage',
            'value' => '5.0',
            'type' => 'string',
            'description' => 'Service fee percentage'
        ]);

        Setting::create([
            'key' => 'tax_percentage',
            'value' => '6.0',
            'type' => 'string',
            'description' => 'Tax percentage'
        ]);
    }

    /** @test */
    public function admin_can_create_multi_day_order_with_day1_only()
    {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/orders', [
            'user_id' => $this->customer->id,
            'event_id' => $this->multiDayEvent->id,
            'customer_email' => 'customer@test.com',
            'payment_method' => 'online_banking',
            'status' => 'pending',
            'purchase_type' => 'multi_day',
            'multi_day1_enabled' => '1',
            'multi_day2_enabled' => '0',
            'day1_ticket_type' => $this->ticket1->id,
            'day1_quantity' => 2,
            'notes' => 'Test order for Day 1 only'
        ]);

        $response->assertRedirect('/admin/orders');
        $response->assertSessionHas('success');

        // Verify order was created
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->customer->id,
            'event_id' => $this->multiDayEvent->id,
            'status' => 'pending'
        ]);

        // Verify purchase tickets were created
        $order = Order::where('user_id', $this->customer->id)->first();
        $this->assertCount(2, $order->purchaseTickets); // 2 tickets = 2 purchase ticket records
        $this->assertEquals('Day 1', $order->purchaseTickets->first()->event_day_name);
    }

    /** @test */
    public function admin_can_create_multi_day_order_with_day2_only()
    {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/orders', [
            'user_id' => $this->customer->id,
            'event_id' => $this->multiDayEvent->id,
            'customer_email' => 'customer@test.com',
            'payment_method' => 'online_banking',
            'status' => 'pending',
            'purchase_type' => 'multi_day',
            'multi_day1_enabled' => '0',
            'multi_day2_enabled' => '1',
            'day2_ticket_type' => $this->ticket2->id,
            'day2_quantity' => 3,
            'notes' => 'Test order for Day 2 only'
        ]);

        $response->assertRedirect('/admin/orders');
        $response->assertSessionHas('success');

        // Verify order was created
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->customer->id,
            'event_id' => $this->multiDayEvent->id,
            'status' => 'pending'
        ]);

        // Verify purchase tickets were created
        $order = Order::where('user_id', $this->customer->id)->first();
        $this->assertCount(3, $order->purchaseTickets); // 3 tickets = 3 purchase ticket records
        $this->assertEquals('Day 2', $order->purchaseTickets->first()->event_day_name);
    }

    /** @test */
    public function admin_can_create_multi_day_order_with_both_days()
    {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/orders', [
            'user_id' => $this->customer->id,
            'event_id' => $this->multiDayEvent->id,
            'customer_email' => 'customer@test.com',
            'payment_method' => 'online_banking',
            'status' => 'pending',
            'purchase_type' => 'multi_day',
            'multi_day1_enabled' => '1',
            'multi_day2_enabled' => '1',
            'day1_ticket_type' => $this->ticket1->id,
            'day1_quantity' => 2,
            'day2_ticket_type' => $this->ticket2->id,
            'day2_quantity' => 1,
            'is_combo_purchase' => '1',
            'notes' => 'Test order for both days with combo discount'
        ]);

        $response->assertRedirect('/admin/orders');
        $response->assertSessionHas('success');

        // Verify order was created
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->customer->id,
            'event_id' => $this->multiDayEvent->id,
            'status' => 'pending'
        ]);

        // Verify purchase tickets were created for both days
        $order = Order::where('user_id', $this->customer->id)->first();
        $this->assertCount(2, $order->purchaseTickets);
        
        $day1Ticket = $order->purchaseTickets->where('event_day_name', 'Day 1')->first();
        $day2Ticket = $order->purchaseTickets->where('event_day_name', 'Day 2')->first();
        
        $this->assertNotNull($day1Ticket);
        $this->assertNotNull($day2Ticket);
    }

    /** @test */
    public function admin_can_create_single_day_order_with_day1_selection()
    {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/orders', [
            'user_id' => $this->customer->id,
            'event_id' => $this->multiDayEvent->id,
            'customer_email' => 'customer@test.com',
            'payment_method' => 'online_banking',
            'status' => 'pending',
            'purchase_type' => 'single_day',
            'single_day_type' => 'day1',
            'ticket_type_id' => $this->ticket1->id,
            'quantity' => 1,
            'notes' => 'Test single day order for Day 1'
        ]);

        $response->assertRedirect('/admin/orders');
        $response->assertSessionHas('success');

        // Verify order was created
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->customer->id,
            'event_id' => $this->multiDayEvent->id,
            'status' => 'pending'
        ]);

        // Verify purchase ticket was created for Day 1
        $order = Order::where('user_id', $this->customer->id)->first();
        $this->assertCount(1, $order->purchaseTickets);
        $this->assertEquals('Day 1', $order->purchaseTickets->first()->event_day_name);
    }

    /** @test */
    public function admin_can_create_single_day_order_with_day2_selection()
    {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/orders', [
            'user_id' => $this->customer->id,
            'event_id' => $this->multiDayEvent->id,
            'customer_email' => 'customer@test.com',
            'payment_method' => 'online_banking',
            'status' => 'pending',
            'purchase_type' => 'single_day',
            'single_day_type' => 'day2',
            'ticket_type_id' => $this->ticket2->id,
            'quantity' => 2,
            'notes' => 'Test single day order for Day 2'
        ]);

        $response->assertRedirect('/admin/orders');
        $response->assertSessionHas('success');

        // Verify order was created
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->customer->id,
            'event_id' => $this->multiDayEvent->id,
            'status' => 'pending'
        ]);

        // Verify purchase ticket was created for Day 2
        $order = Order::where('user_id', $this->customer->id)->first();
        $this->assertCount(1, $order->purchaseTickets);
        $this->assertEquals('Day 2', $order->purchaseTickets->first()->event_day_name);
    }

    /** @test */
    public function admin_can_create_day1_only_order()
    {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/orders', [
            'user_id' => $this->customer->id,
            'event_id' => $this->multiDayEvent->id,
            'customer_email' => 'customer@test.com',
            'payment_method' => 'online_banking',
            'status' => 'pending',
            'purchase_type' => 'day1_only',
            'day1_only_ticket_type' => $this->ticket1->id,
            'day1_only_quantity' => 1,
            'notes' => 'Test Day 1 only order'
        ]);

        $response->assertRedirect('/admin/orders');
        $response->assertSessionHas('success');

        // Verify order was created
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->customer->id,
            'event_id' => $this->multiDayEvent->id,
            'status' => 'pending'
        ]);

        // Verify purchase ticket was created for Day 1
        $order = Order::where('user_id', $this->customer->id)->first();
        $this->assertCount(1, $order->purchaseTickets);
        $this->assertEquals('Day 1', $order->purchaseTickets->first()->event_day_name);
    }

    /** @test */
    public function admin_can_create_day2_only_order()
    {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/orders', [
            'user_id' => $this->customer->id,
            'event_id' => $this->multiDayEvent->id,
            'customer_email' => 'customer@test.com',
            'payment_method' => 'online_banking',
            'status' => 'pending',
            'purchase_type' => 'day2_only',
            'day2_only_ticket_type' => $this->ticket2->id,
            'day2_only_quantity' => 2,
            'notes' => 'Test Day 2 only order'
        ]);

        $response->assertRedirect('/admin/orders');
        $response->assertSessionHas('success');

        // Verify order was created
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->customer->id,
            'event_id' => $this->multiDayEvent->id,
            'status' => 'pending'
        ]);

        // Verify purchase ticket was created for Day 2
        $order = Order::where('user_id', $this->customer->id)->first();
        $this->assertCount(1, $order->purchaseTickets);
        $this->assertEquals('Day 2', $order->purchaseTickets->first()->event_day_name);
    }

    /** @test */
    public function multi_day_order_fails_when_no_days_selected()
    {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/orders', [
            'user_id' => $this->customer->id,
            'event_id' => $this->multiDayEvent->id,
            'customer_email' => 'customer@test.com',
            'payment_method' => 'online_banking',
            'status' => 'pending',
            'purchase_type' => 'multi_day',
            'multi_day1_enabled' => '0',
            'multi_day2_enabled' => '0',
            'notes' => 'Test order with no days selected'
        ]);

        $response->assertSessionHasErrors(['multi_day1_enabled']);
        $this->assertStringContainsString('Please select at least one day to attend', $response->getSession()->get('errors')->first('multi_day1_enabled'));
    }

    /** @test */
    public function multi_day_order_fails_when_day1_selected_but_no_ticket_type()
    {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/orders', [
            'user_id' => $this->customer->id,
            'event_id' => $this->multiDayEvent->id,
            'customer_email' => 'customer@test.com',
            'payment_method' => 'online_banking',
            'status' => 'pending',
            'purchase_type' => 'multi_day',
            'multi_day1_enabled' => '1',
            'multi_day2_enabled' => '0',
            'day1_quantity' => 1,
            'notes' => 'Test order with Day 1 selected but no ticket type'
        ]);

        $response->assertSessionHasErrors(['day1_ticket_type']);
    }

    /** @test */
    public function multi_day_order_fails_when_day2_selected_but_no_ticket_type()
    {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/orders', [
            'user_id' => $this->customer->id,
            'event_id' => $this->multiDayEvent->id,
            'customer_email' => 'customer@test.com',
            'payment_method' => 'online_banking',
            'status' => 'pending',
            'purchase_type' => 'multi_day',
            'multi_day1_enabled' => '0',
            'multi_day2_enabled' => '1',
            'day2_quantity' => 1,
            'notes' => 'Test order with Day 2 selected but no ticket type'
        ]);

        $response->assertSessionHasErrors(['day2_ticket_type']);
    }

    /** @test */
    public function multi_day_order_fails_when_insufficient_tickets_available()
    {
        $this->actingAs($this->admin);

        // Create an order that uses up all available tickets
        Order::create([
            'user_id' => $this->customer->id,
            'event_id' => $this->multiDayEvent->id,
            'order_number' => 'ORD-001',
            'customer_email' => 'customer@test.com',
            'payment_method' => 'online_banking',
            'status' => 'pending',
            'subtotal' => 100.00,
            'service_fee' => 5.00,
            'tax' => 6.00,
            'total' => 111.00,
            'notes' => 'Test order'
        ]);

        // Create purchase ticket that uses up all available seats
        \App\Models\PurchaseTicket::create([
            'order_id' => 1,
            'event_id' => $this->multiDayEvent->id,
            'ticket_type_id' => $this->ticket1->id,
            'quantity' => 50, // This uses up all available seats
            'price_paid' => 100.00,
            'event_day' => '2025-12-01',
            'event_day_name' => 'Day 1'
        ]);

        // Update ticket availability
        $this->ticket1->update(['available_seats' => 0]);

        $response = $this->post('/admin/orders', [
            'user_id' => $this->customer->id,
            'event_id' => $this->multiDayEvent->id,
            'customer_email' => 'customer@test.com',
            'payment_method' => 'online_banking',
            'status' => 'pending',
            'purchase_type' => 'multi_day',
            'multi_day1_enabled' => '1',
            'multi_day2_enabled' => '0',
            'day1_ticket_type' => $this->ticket1->id,
            'day1_quantity' => 1,
            'notes' => 'Test order with insufficient tickets'
        ]);

        $response->assertSessionHasErrors(['day1_ticket_type']);
        $this->assertStringContainsString('Insufficient tickets available', $response->getSession()->get('errors')->first('day1_ticket_type'));
    }

    /** @test */
    public function combo_discount_is_applied_when_both_days_selected()
    {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/orders', [
            'user_id' => $this->customer->id,
            'event_id' => $this->multiDayEvent->id,
            'customer_email' => 'customer@test.com',
            'payment_method' => 'online_banking',
            'status' => 'pending',
            'purchase_type' => 'multi_day',
            'multi_day1_enabled' => '1',
            'multi_day2_enabled' => '1',
            'day1_ticket_type' => $this->ticket1->id,
            'day1_quantity' => 1,
            'day2_ticket_type' => $this->ticket2->id,
            'day2_quantity' => 1,
            'is_combo_purchase' => '1',
            'notes' => 'Test order with combo discount'
        ]);

        $response->assertRedirect('/admin/orders');
        $response->assertSessionHas('success');

        // Verify order was created with combo discount
        $order = Order::where('user_id', $this->customer->id)->first();
        
        // Calculate expected values
        $subtotal = 100.00 + 50.00; // ticket1 + ticket2
        $comboDiscount = $subtotal * 0.10; // 10% discount
        $discountedSubtotal = $subtotal - $comboDiscount;
        $serviceFee = $discountedSubtotal * 0.05; // 5% service fee
        $tax = ($discountedSubtotal + $serviceFee) * 0.06; // 6% tax
        $total = $discountedSubtotal + $serviceFee + $tax;

        $this->assertEquals($discountedSubtotal, $order->subtotal);
        $this->assertEquals($comboDiscount, $order->combo_discount);
        $this->assertEquals($serviceFee, $order->service_fee);
        $this->assertEquals($tax, $order->tax);
        $this->assertEquals($total, $order->total);
    }

    /** @test */
    public function pricing_calculation_is_correct_for_different_scenarios()
    {
        $this->actingAs($this->admin);

        // Test Day 1 only pricing
        $response1 = $this->post('/admin/orders', [
            'user_id' => $this->customer->id,
            'event_id' => $this->multiDayEvent->id,
            'customer_email' => 'customer@test.com',
            'payment_method' => 'online_banking',
            'status' => 'pending',
            'purchase_type' => 'multi_day',
            'multi_day1_enabled' => '1',
            'multi_day2_enabled' => '0',
            'day1_ticket_type' => $this->ticket1->id,
            'day1_quantity' => 2,
            'notes' => 'Test Day 1 only pricing'
        ]);

        $response1->assertRedirect('/admin/orders');
        $order1 = Order::where('user_id', $this->customer->id)->first();
        
        // Expected: 2 * 100 = 200, service fee = 10, tax = 12.6, total = 222.6
        $this->assertEquals(200.00, $order1->subtotal);
        $this->assertEquals(10.00, $order1->service_fee);
        $this->assertEquals(12.60, $order1->tax);
        $this->assertEquals(222.60, $order1->total);

        // Test Day 2 only pricing
        $response2 = $this->post('/admin/orders', [
            'user_id' => $this->customer->id,
            'event_id' => $this->multiDayEvent->id,
            'customer_email' => 'customer@test.com',
            'payment_method' => 'online_banking',
            'status' => 'pending',
            'purchase_type' => 'multi_day',
            'multi_day1_enabled' => '0',
            'multi_day2_enabled' => '1',
            'day2_ticket_type' => $this->ticket2->id,
            'day2_quantity' => 3,
            'notes' => 'Test Day 2 only pricing'
        ]);

        $response2->assertRedirect('/admin/orders');
        $order2 = Order::where('user_id', $this->customer->id)->latest()->first();
        
        // Expected: 3 * 50 = 150, service fee = 7.5, tax = 9.45, total = 166.95
        $this->assertEquals(150.00, $order2->subtotal);
        $this->assertEquals(7.50, $order2->service_fee);
        $this->assertEquals(9.45, $order2->tax);
        $this->assertEquals(166.95, $order2->total);
    }
}