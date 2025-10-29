# Total Amount Source Trace - From Ticket Prices to Stripe

## Complete Flow: Where `totalAmount` Comes From

### 🎯 Destination: Stripe Payment Intent
**File:** `app/Http/Controllers/Payment/StripeController.php` (Line 60, 67)

```php
// Line 60: Convert order total to cents
$totalAmount = $order->total_amount * 100;

// Line 67: Send to Stripe
'amount' => $totalAmount,  // In cents (e.g., 225000 for RM2,250.00)
```

---

## 📊 Source Chain

### Step 1: Order Creation - Store `total_amount` in Database
**File:** `app/Http/Controllers/TicketController.php` (Line 289)

```php
Order::create([
    // ... other fields
    'total_amount' => $totalAmount,  // ← STORED IN DATABASE
]);
```

### Step 2: Calculate `totalAmount` Before Order Creation
**File:** `app/Http/Controllers/TicketController.php` (Line 236)

```php
// Line 236: Final calculation
$totalAmount = $subtotal + $serviceFee + $taxAmount;
```

### Step 3: Calculate Service Fee
**File:** `app/Http/Controllers/TicketController.php` (Line 234, 1170-1173)

```php
// Line 234: Calculate service fee
$serviceFee = $this->calculateServiceFee($subtotal);

// Lines 1170-1173: Implementation
private function calculateServiceFee(float $subtotal): float
{
    $serviceFeePercentage = Setting::get('service_fee_percentage', 0.0); // Default: 0.0
    return round($subtotal * ($serviceFeePercentage / 100), 2);
}
```

### Step 4: Calculate Tax
**File:** `app/Http/Controllers/TicketController.php` (Line 235, 1179-1182)

```php
// Line 235: Calculate tax
$taxAmount = $this->calculateTax($subtotal + $serviceFee);

// Lines 1179-1182: Implementation
private function calculateTax(float $subtotal): float
{
    $taxPercentage = Setting::get('tax_percentage', 0.0); // Default: 0.0
    return round($subtotal * ($taxPercentage / 100), 2);
}
```

### Step 5: Calculate Subtotal (After Discount)
**File:** `app/Http/Controllers/TicketController.php` (Lines 165-231)

#### For Single-Day Purchase:
```php
// Lines 171-176: Single-day calculation
$ticketType = \App\Models\Ticket::findOrFail($request->ticket_type_id);
$quantity = $request->quantity;
$basePrice = $ticketType->price;
$subtotal = $basePrice * $quantity;  // No discount for single-day

// Line 178-179
$originalSubtotal = $subtotal;
$discountAmount = 0;
```

#### For Multi-Day Purchase:
```php
// Lines 204-214: Multi-day calculation
$day1Ticket = \App\Models\Ticket::findOrFail($request->day1_ticket_type);
$day1Price = $day1Ticket->price;
$day1Quantity = $request->day1_quantity ?? 1;
$subtotal += $day1Price * $day1Quantity;

$day2Ticket = \App\Models\Ticket::findOrFail($request->day2_ticket_type);
$day2Price = $day2Ticket->price;
$day2Quantity = $request->day2_quantity ?? 1;
$subtotal += $day2Price * $day2Quantity;

// Lines 224-230: Apply combo discount
if ($event->combo_discount_enabled) {
    $discountAmount = $event->calculateComboDiscount($subtotal);
    $subtotal = $subtotal - $discountAmount;  // Subtotal AFTER discount
} else {
    $discountAmount = 0;
}
```

### Step 6: Ticket Price Source
**File:** `app/Models/Ticket.php`

```php
// Ticket model has 'price' field
$ticketType->price  // Retrieved from database 'tickets' table
```

---

## 🔄 Complete Formula Chain

```
1. Ticket Prices (from database 'tickets' table)
   ↓
2. Subtotal = Σ(ticket_price × quantity) [for each selected ticket]
   ↓
3. Discount = calculateComboDiscount(subtotal) [if multi-day + combo enabled]
   ↓
4. Subtotal (after discount) = Subtotal - Discount
   ↓
5. Service Fee = Subtotal × (service_fee_percentage / 100)
   ↓
6. Tax = (Subtotal + Service Fee) × (tax_percentage / 100)
   ↓
7. Total Amount = Subtotal + Service Fee + Tax
   ↓
8. Store in Database: order.total_amount = Total Amount
   ↓
9. Retrieve from Database: $order->total_amount
   ↓
10. Convert to Cents: $totalAmount = $order->total_amount × 100
   ↓
11. Send to Stripe: 'amount' => $totalAmount (in cents)
```

---

## 📍 Key Locations Summary

| Step | File | Line(s) | Description |
|------|------|---------|-------------|
| **Stripe Input** | `StripeController.php` | 60, 67 | Convert and send to Stripe |
| **Database Storage** | `TicketController.php` | 289 | Save `total_amount` to `orders` table |
| **Total Calculation** | `TicketController.php` | 236 | `$totalAmount = $subtotal + $serviceFee + $taxAmount` |
| **Tax Calculation** | `TicketController.php` | 235, 1179 | Based on `subtotal + serviceFee` |
| **Service Fee** | `TicketController.php` | 234, 1170 | Based on `subtotal` |
| **Subtotal Calculation** | `TicketController.php` | 165-231 | `Σ(price × quantity) - discount` |
| **Discount** | `TicketController.php` | 224-230 | Combo discount if enabled |
| **Ticket Price** | `Ticket Model` | - | From `tickets.price` column |

---

## 💾 Database Fields Involved

1. **`tickets.price`** - Base ticket price per unit
2. **`orders.subtotal`** - Sum of ticket prices (before discount)
3. **`orders.discount_amount`** - Discount applied (if combo)
4. **`orders.service_fee`** - Service fee amount
5. **`orders.tax_amount`** - Tax amount
6. **`orders.total_amount`** - **FINAL AMOUNT** sent to Stripe

---

## ⚙️ Settings Involved

From `settings` table (via `Setting::get()`):

1. **`service_fee_percentage`** - Default: `0.0` (can be changed by admin)
2. **`tax_percentage`** - Default: `0.0` (can be changed by admin)
3. **Event `combo_discount_percentage`** - Default: `10.0` (if combo enabled)

---

## 🔍 Example Calculation Flow

**Scenario:** Multi-day purchase, 5 tickets Day 1 (RM250 each), 5 tickets Day 2 (RM250 each)

```
1. Ticket Prices:
   - Day 1: RM250 × 5 = RM1,250
   - Day 2: RM250 × 5 = RM1,250

2. Original Subtotal: RM1,250 + RM1,250 = RM2,500

3. Discount (10% combo):
   - Discount = RM2,500 × 0.10 = RM250
   - Subtotal (after discount) = RM2,500 - RM250 = RM2,250

4. Service Fee (0%):
   - Service Fee = RM2,250 × 0.00 = RM0

5. Tax (0%):
   - Tax = (RM2,250 + RM0) × 0.00 = RM0

6. Total Amount:
   - Total = RM2,250 + RM0 + RM0 = RM2,250.00

7. Store in Database:
   - orders.total_amount = 2250.00

8. Send to Stripe:
   - $totalAmount = 2250.00 × 100 = 225000 cents
   - Stripe receives: 225000 (representing RM2,250.00)
```

---

## ✅ Key Points

1. **Source**: `totalAmount` comes from **`orders.total_amount`** field in database
2. **Calculation**: Done in `TicketController::checkout()` before order creation
3. **Formula**: `Subtotal (after discount) + Service Fee + Tax`
4. **Conversion**: Database value (RM) × 100 = Stripe amount (cents)
5. **Settings**: Service fee and tax percentages come from `settings` table
6. **Discount**: Only applied for multi-day purchases when combo discount is enabled

