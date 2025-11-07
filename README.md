# Mini Employee Management System

A modern employee management system built with Laravel 12 and Vite, featuring a responsive dashboard for managing employees and departments.

## Features

- 👥 **User Authentication**
  - Secure login system
  - Admin role management
  - Password reset functionality

- 👔 **Employee Management**
  - Create, view, and delete employee records
  - Employee information includes name, email, position, and salary
  - Soft delete support for employee records
  - Automatic welcome email for new employees

- 🏢 **Department Management**
  - Pre-configured departments (HR, Finance, Engineering, Marketing, Sales)
  - Department-wise employee organization
  - Department slug-based routing

- 📊 **Dashboard**
  - Dynamic data tables with server-side processing
  - Real-time status indicators
  - Salary formatting
  - Responsive design

- 🔒 **API Security**
  - Token-based authentication using Laravel Sanctum
  - Protected admin routes
  - CSRF protection

## Tech Stack

- **Backend**: Laravel 12
- **Frontend**: Vite
- **Database**: MySQL
- **Authentication**: Laravel Breeze + Sanctum
- **Data Tables**: Yajra Laravel DataTables
- **Queue System**: Laravel Jobs for email processing

## Prerequisites

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL

## Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd mini_employee_management
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install Node.js dependencies:
   ```bash
   npm install
   ```

4. Create environment file:
   ```bash
   cp .env.example .env
   ```

5. Generate application key:
   ```bash
   php artisan key:generate
   ```

6. Configure your database in `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=mini_employee_management
   DB_USERNAME=root
   DB_PASSWORD=
   ```

7. Run database migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```

8. Build assets:
   ```bash
   npm run build
   ```

## Quick Start

1. Start the development server:
   ```bash
   php artisan serve
   ```

2. Start the Vite development server:
   ```bash
   npm run dev
   ```

3. Start the queue worker for processing jobs:
   ```bash
   php artisan queue:work
   ```

4. Access the application:
   - URL: `http://localhost:8000`
   - Default admin credentials:
     - Email: soumya@example.com
     - Password: password123

## API Usage

When making API requests, always include the following header:
```http
Accept: application/json
```
For Admin
```http
X-ROLE: admin
```

## Development Commands

- Fresh migration with seeding:
  ```bash
  php artisan migrate:fresh --seed
  ```

## Directory Structure

- `app/`
  - `Http/Controllers/` - Application controllers
  - `Models/` - Eloquent models
  - `Jobs/` - Queue jobs (e.g., welcome emails)
  
- `database/`
  - `migrations/` - Database migrations
  - `seeders/` - Database seeders
  - `factories/` - Model factories

- `resources/`
  - `views/` - Blade templates
  - `js/` - JavaScript files
  - `css/` - Stylesheet files

- `routes/`
  - `web.php` - Web routes
  - `api.php` - API routes
  - `auth.php` - Authentication routes

## Author

- **Soumya Manna**

## Contributing

Please read our contributing guidelines before submitting pull requests.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
