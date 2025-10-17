# Database Migration Structure by Module

This directory contains database migrations organized by functional modules for better maintainability and clarity.

## Module Organization

### 1. 👥 USER MODULE (`2025_10_15_070235_create_user_module_tables.php`)
**Purpose**: Handles user management and authentication

**Tables**:
- `users` - User accounts (customers, admins, gate staff)
- `password_reset_tokens` - Password reset functionality
- `personal_access_tokens` - API authentication

**Key Features**:
- Role-based access (Customer, Admin, Gate Staff)
- Complete address information
- Contact details
- Soft deletes for data retention

---

### 2. 🎪 EVENT MODULE (`2025_10_15_070239_create_event_module_tables.php`)
**Purpose**: Handles event information and management

**Tables**:
- `events` - Event details and configuration

**Key Features**:
- Multi-day event support (max 2 days)
- Status management (Draft, On Sale, Sold Out, Cancelled)
- Venue information
- Capacity management
- Combo discount settings for multi-day events
- Default event indicator (only one event can be default)
- Single default event constraint enforcement
- Soft deletes for data retention

---

### 3. 🎫 TICKET MODULE (`2025_10_15_070242_create_ticket_module_tables.php`)
**Purpose**: Handles ticket types/availability and individual purchased tickets

**Tables**:
- `tickets` - Ticket types/availability (VIP, General, Student, etc.)
- `purchase_tickets` - Individual customer tickets

**Key Features**:
- **Tickets Table**: Defines what ticket types are available for each event
- **Purchase Tickets Table**: Individual tickets purchased by customers
- Price management per ticket type
- Seat availability tracking
- QR code generation
- Status tracking (Sold, Held, Scanned, Invalid, Refunded)
- Soft deletes for data retention

---

### 4. 🛒 ORDER MODULE (`2025_10_15_070246_create_order_module_tables.php`)
**Purpose**: Handles customer orders and payments

**Tables**:
- `orders` - Customer orders
- `payments` - Payment processing

**Key Features**:
- Order management with status tracking
- Payment processing (Stripe integration)
- Service fees and tax calculation
- Order holding functionality
- QR code generation for orders
- Soft deletes for data retention

---

### 5. 🔧 SYSTEM MODULE (`2025_10_15_070250_create_system_module_tables.php`)
**Purpose**: Handles system-wide functionality

**Tables**:
- `admittance_logs` - Gate scanning and entry tracking
- `audit_logs` - System audit trail
- `failed_jobs` - Laravel queue system

**Key Features**:
- Gate scanning functionality
- Complete audit trail
- Staff activity tracking
- Device and IP logging
- Queue system support

---

## Data Flow

```
1. USER MODULE → User registration and authentication
2. EVENT MODULE → Event creation and management
3. TICKET MODULE → Define ticket types for events
4. ORDER MODULE → Customer places order for specific ticket types
5. TICKET MODULE → Generate individual purchase tickets
6. SYSTEM MODULE → Track scanning and audit activities
```

## Relationships

- **Events** → **Tickets** (1:many) - One event has many ticket types
- **Events** → **Purchase Tickets** (1:many) - One event has many individual tickets
- **Tickets** → **Purchase Tickets** (1:many) - One ticket type has many individual tickets
- **Orders** → **Purchase Tickets** (1:many) - One order has many tickets
- **Users** → **Orders** (1:many) - One user has many orders
- **Orders** → **Payments** (1:many) - One order can have multiple payments

## Indexes

Each table includes appropriate indexes for:
- Foreign key relationships
- Common query patterns
- Performance optimization
- Status-based filtering

## Soft Deletes

Most tables use soft deletes to maintain data integrity and audit trails while allowing logical deletion of records.
