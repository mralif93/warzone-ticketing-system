# Multi-Day Order Testing Scenarios

## Manual Testing Guide

### Prerequisites
1. Ensure the application is running: `php artisan serve`
2. Login as admin user
3. Navigate to `/admin/orders/create`
4. Open browser developer tools (F12) to see console logs

### Test Scenarios

## Scenario 1: Multi-Day Purchase - Day 1 Only
**Objective**: Test creating an order for Day 1 only in a multi-day event

**Steps**:
1. Select an event (multi-day event)
2. Choose "Multi-Day Purchase" radio button
3. Check only "Day 1" checkbox
4. Leave "Day 2" checkbox unchecked
5. Select a ticket type for Day 1
6. Set quantity for Day 1 (e.g., 2)
7. Fill in customer details
8. Submit the form

**Expected Result**: 
- ✅ Order created successfully
- ✅ Only Day 1 ticket purchased
- ✅ No validation errors
- ✅ Console shows: "Multi-day checkboxes - Day1: true, Day2: false"

## Scenario 2: Multi-Day Purchase - Day 2 Only
**Objective**: Test creating an order for Day 2 only in a multi-day event

**Steps**:
1. Select an event (multi-day event)
2. Choose "Multi-Day Purchase" radio button
3. Leave "Day 1" checkbox unchecked
4. Check only "Day 2" checkbox
5. Select a ticket type for Day 2
6. Set quantity for Day 2 (e.g., 3)
7. Fill in customer details
8. Submit the form

**Expected Result**: 
- ✅ Order created successfully
- ✅ Only Day 2 ticket purchased
- ✅ No validation errors
- ✅ Console shows: "Multi-day checkboxes - Day1: false, Day2: true"

## Scenario 3: Multi-Day Purchase - Both Days
**Objective**: Test creating an order for both days with combo discount

**Steps**:
1. Select an event (multi-day event)
2. Choose "Multi-Day Purchase" radio button
3. Check both "Day 1" and "Day 2" checkboxes
4. Select ticket types for both days
5. Set quantities for both days
6. Check "Combo Discount" checkbox (should appear)
7. Fill in customer details
8. Submit the form

**Expected Result**: 
- ✅ Order created successfully
- ✅ Both Day 1 and Day 2 tickets purchased
- ✅ Combo discount applied
- ✅ Console shows: "Multi-day checkboxes - Day1: true, Day2: true"

## Scenario 4: Multi-Day Purchase - No Days Selected
**Objective**: Test validation when no days are selected

**Steps**:
1. Select an event (multi-day event)
2. Choose "Multi-Day Purchase" radio button
3. Leave both checkboxes unchecked
4. Fill in customer details
5. Submit the form

**Expected Result**: 
- ❌ Validation error: "Please select at least one day to attend."
- ❌ Form not submitted

## Scenario 5: Single Day Purchase - Day 1 Selection
**Objective**: Test single day purchase with Day 1 selection

**Steps**:
1. Select an event (multi-day event)
2. Choose "Single Day Purchase" radio button
3. Select "Day 1" radio button
4. Select a ticket type
5. Set quantity
6. Fill in customer details
7. Submit the form

**Expected Result**: 
- ✅ Order created successfully
- ✅ Day 1 ticket purchased
- ✅ No validation errors

## Scenario 6: Single Day Purchase - Day 2 Selection
**Objective**: Test single day purchase with Day 2 selection

**Steps**:
1. Select an event (multi-day event)
2. Choose "Single Day Purchase" radio button
3. Select "Day 2" radio button
4. Select a ticket type
5. Set quantity
6. Fill in customer details
7. Submit the form

**Expected Result**: 
- ✅ Order created successfully
- ✅ Day 2 ticket purchased
- ✅ No validation errors

## Scenario 7: Day 1 Only Purchase
**Objective**: Test dedicated Day 1 only purchase option

**Steps**:
1. Select an event (multi-day event)
2. Choose "Day 1 Only" radio button
3. Select a ticket type for Day 1
4. Set quantity
5. Fill in customer details
6. Submit the form

**Expected Result**: 
- ✅ Order created successfully
- ✅ Day 1 ticket purchased
- ✅ No validation errors

## Scenario 8: Day 2 Only Purchase
**Objective**: Test dedicated Day 2 only purchase option

**Steps**:
1. Select an event (multi-day event)
2. Choose "Day 2 Only" radio button
3. Select a ticket type for Day 2
4. Set quantity
5. Fill in customer details
6. Submit the form

**Expected Result**: 
- ✅ Order created successfully
- ✅ Day 2 ticket purchased
- ✅ No validation errors

## Scenario 9: Ticket Loading Test
**Objective**: Test that ticket types load correctly for different events

**Steps**:
1. Select a single-day event
2. Choose any purchase type
3. Check console logs for ticket loading
4. Switch to a multi-day event
5. Check console logs for ticket loading

**Expected Result**: 
- ✅ Console shows: "=== FETCH TICKET TYPES START ==="
- ✅ Console shows: "Found X ticket types"
- ✅ Console shows: "Event is multi-day: true/false"
- ✅ All dropdowns populated correctly
- ✅ No "Error loading ticket types" messages

## Scenario 10: Pricing Preview Test
**Objective**: Test that pricing preview updates correctly

**Steps**:
1. Select an event and purchase type
2. Select ticket types and quantities
3. Watch the pricing preview section
4. Change quantities
5. Toggle combo discount (if applicable)

**Expected Result**: 
- ✅ Pricing preview updates in real-time
- ✅ Service fee and tax calculated correctly
- ✅ Combo discount applied when both days selected
- ✅ Total calculation is accurate

## Scenario 11: Form Validation Test
**Objective**: Test various validation scenarios

**Steps**:
1. Try submitting form without selecting event
2. Try submitting form without selecting ticket type
3. Try submitting form with invalid quantities
4. Try submitting form with insufficient tickets

**Expected Result**: 
- ❌ Appropriate validation errors shown
- ❌ Form not submitted
- ✅ Clear error messages displayed

## Scenario 12: Edge Cases
**Objective**: Test edge cases and error handling

**Steps**:
1. Test with maximum quantity (10)
2. Test with minimum quantity (1)
3. Test with sold-out tickets
4. Test with different payment methods
5. Test with different order statuses

**Expected Result**: 
- ✅ All edge cases handled gracefully
- ✅ Appropriate error messages for invalid inputs
- ✅ Success messages for valid inputs

## Console Debugging

When testing, check the browser console for these debug messages:

```
=== FETCH TICKET TYPES START ===
Event ID: 1
Event Select element: [object HTMLSelectElement]
Ticket Type Select element: [object HTMLSelectElement]
Day1 Ticket Select element: [object HTMLSelectElement]
Day2 Ticket Select element: [object HTMLSelectElement]
Day1 Only Ticket Select element: [object HTMLSelectElement]
Day2 Only Ticket Select element: [object HTMLSelectElement]
```

```
=== FORM SUBMISSION DEBUG ===
Selected purchase type: multi_day
Multi-day checkboxes - Day1: true, Day2: false
Day1 quantity value: 2
Day2 quantity value: 
```

## Expected Database Changes

After successful order creation, verify these database entries:

1. **orders table**: New order record with correct status and totals
2. **purchase_tickets table**: New purchase ticket records with correct event_day_name
3. **tickets table**: available_seats should be reduced by purchased quantity
4. **payments table**: New payment record (if applicable)

## Performance Testing

1. Test with large number of ticket types
2. Test with slow network connection
3. Test form submission with multiple rapid clicks
4. Test browser back/forward navigation

## Browser Compatibility

Test on:
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)
