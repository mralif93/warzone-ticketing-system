# Warzone Ticketing System

A professional ticketing system built with Laravel 10, featuring custom authentication, modern UI with TailwindCSS, and SweetAlert2 for enhanced user experience.

## Features

- **Custom Authentication System**
  - User registration with role-based access
  - Secure login with remember me functionality
  - Password reset via email
  - Professional UI with form validation

- **Modern Design**
  - Responsive design using TailwindCSS CDN
  - Professional gradient backgrounds
  - Interactive elements with hover effects
  - SweetAlert2 for beautiful notifications

- **User Roles**
  - Admin: Full system access
  - Agent: Support agent capabilities
  - Customer: Basic ticket management

- **Dashboard**
  - Statistics overview
  - Quick action buttons
  - User profile display
  - Role-based navigation

## Requirements

- PHP 8.1 or higher
- Composer
- MySQL 5.7+ or MariaDB 10.2+
- Web server (Apache/Nginx)

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd warzone-ticketing-system
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database configuration**
   - Create a MySQL database named `warzone_ticketing`
   - Update the `.env` file with your database credentials:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=warzone_ticketing
     DB_USERNAME=your_username
     DB_PASSWORD=your_password
     ```

5. **Run migrations**
   ```bash
   php artisan migrate
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   ```

7. **Access the application**
   - Open your browser and go to `http://localhost:8000`
   - You'll be redirected to the login page

## Usage

### Registration
1. Click "Create new account" on the login page
2. Fill in your details (name, email, phone, department)
3. Choose a strong password
4. Click "Create Account"

### Login
1. Enter your email and password
2. Optionally check "Remember me" for persistent login
3. Click "Sign in"

### Password Reset
1. Click "Forgot your password?" on the login page
2. Enter your email address
3. Check your email for the reset link
4. Follow the link to reset your password

### Dashboard
After logging in, you'll see:
- Welcome message with your role
- Statistics cards (currently showing 0 - ready for ticket integration)
- Quick action buttons for future features

## Project Structure

```
warzone-ticketing-system/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── AuthController.php
│   │   │   └── DashboardController.php
│   │   └── Middleware/
│   ├── Models/
│   │   └── User.php
│   └── Providers/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── auth/
│       │   ├── login.blade.php
│       │   ├── register.blade.php
│       │   ├── forgot-password.blade.php
│       │   └── reset-password.blade.php
│       ├── layouts/
│       │   └── app.blade.php
│       └── dashboard.blade.php
├── routes/
│   ├── web.php
│   └── api.php
└── composer.json
```

## Technologies Used

- **Backend**: Laravel 10
- **Frontend**: TailwindCSS (CDN)
- **JavaScript**: SweetAlert2, jQuery
- **Database**: MySQL
- **Authentication**: Custom Laravel authentication

## Customization

### Styling
The application uses TailwindCSS classes with custom CSS for enhanced styling. You can modify the styles in:
- `resources/views/layouts/app.blade.php` - Main layout and global styles
- Individual view files for specific page styles

### User Roles
User roles are defined in the User model and can be extended:
- `admin`: Full system access
- `agent`: Support agent capabilities  
- `customer`: Basic user access

### Database
The database schema can be extended by creating new migrations:
```bash
php artisan make:migration create_tickets_table
```

## Security Features

- CSRF protection on all forms
- Password hashing using Laravel's built-in hashing
- Input validation and sanitization
- SQL injection protection through Eloquent ORM
- XSS protection through Blade templating

## Future Enhancements

This is a foundation that can be extended with:
- Ticket creation and management
- File uploads for ticket attachments
- Email notifications
- Real-time updates
- Advanced reporting
- API endpoints for mobile apps

## Support

For support or questions, please contact the development team.

## License

This project is licensed under the MIT License.
