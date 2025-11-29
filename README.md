<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About This Project

A comprehensive Document Management and Letter System (DMLS) built with Laravel 12, designed to streamline document workflows, approval processes, and internal correspondence management for organizations.

## Key Features

### Document Management
- Upload, version, and track documents with metadata
- Document categorization and tagging
- Version control with restore capabilities
- Soft delete and document archiving
- OCR support via Tesseract for text extraction
- PDF generation and manipulation (FPDF, FPDI, DomPDF)
- Document collaboration with multiple users
- Comments and activity logging

### Workflow & Approvals
- Multi-level approval hierarchies (Employee → Manager → Director)
- Customizable workflow steps with role-based approvals
- Digital signature support for approvals
- Approval request tracking and notifications
- Automatic workflow creation on document upload

### Letter Management
- Internal letter composition and routing
- Letter templates with department-specific customization
- Inbox/Outbox for incoming and outgoing correspondence
- Letter threading (replies and conversations)
- PDF export for letters
- Email integration for external correspondence
- Read/unread status tracking

### Role-Based Access Control
- Spatie Laravel Permission integration
- Four primary roles: Admin, Director, Manager, Employee
- Custom role creation and permission assignment
- Department-based access restrictions

### Task Management
- Task creation and assignment
- Due date tracking with overdue alerts
- Task status updates and completion tracking
- Manager and Director task oversight

### Reporting & Analytics
- Weekly and monthly reports
- Department activity tracking
- Document approval statistics
- Export reports in multiple formats

### Additional Features
- Real-time notifications
- Health monitoring (Spatie Laravel Health)
- Activity and access logging
- Profile management
- System settings configuration

## Tech Stack

- Laravel 12.x
- PHP 8.2+
- Livewire 3.6 for reactive components
- Laravel Breeze for authentication
- Tailwind CSS 3.x with Alpine.js
- MySQL database
- Vite for asset bundling

### Key Packages
- `spatie/laravel-permission` - Role and permission management
- `spatie/laravel-health` - Application health monitoring
- `barryvdh/laravel-dompdf` - PDF generation
- `intervention/image` - Image processing
- `phpoffice/phpword` - Word document handling
- `thiagoalessio/tesseract_ocr` - OCR text extraction
- `laravel/sanctum` - API authentication

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL 5.7+ or MariaDB
- Tesseract OCR (optional, for OCR features)

### Setup Steps

1. Clone the repository
```bash
git clone <repository-url>
cd <project-directory>
```

2. Install PHP dependencies
```bash
composer install
```

3. Install JavaScript dependencies
```bash
npm install
```

4. Environment configuration
```bash
cp .env.example .env
```

5. Configure your `.env` file with database credentials
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dmls
DB_USERNAME=root
DB_PASSWORD=
```

6. Generate application key
```bash
php artisan key:generate
```

7. Run migrations
```bash
php artisan migrate
```

8. Seed the database (optional)
```bash
php artisan db:seed
```

9. Create storage symlink
```bash
php artisan storage:link
```

10. Build frontend assets
```bash
npm run build
```

## Running the Application

### Development Mode

Run all services concurrently (server, queue, and Vite):
```bash
composer dev
```

Or run services individually:
```bash
# Terminal 1 - Application server
php artisan serve

# Terminal 2 - Queue worker
php artisan queue:listen

# Terminal 3 - Vite dev server
npm run dev
```

### Production Mode
```bash
npm run build
php artisan serve
```

## User Roles & Access

### Admin
- Full system access
- User management
- Role and permission assignment
- System configuration

### Director
- Approve/reject documents requiring director-level approval
- View all department activities
- Create and assign tasks
- Generate reports
- Manage letters

### Manager
- Approve/reject documents from employees
- Create and manage letter templates
- Send internal and external letters
- Assign tasks to employees
- View department documents and reports

### Employee
- Upload and manage documents
- Request approvals
- View assigned tasks
- Send and receive letters
- Collaborate on documents

## Testing

Run the test suite using Pest:
```bash
php artisan test
```

Or with coverage:
```bash
php artisan test --coverage
```

## Code Quality

Format code with Laravel Pint:
```bash
./vendor/bin/pint
```

## Project Structure

```
app/
├── Console/         # Artisan commands
├── Events/          # Event classes
├── Http/
│   ├── Controllers/ # Application controllers
│   └── Middleware/  # Custom middleware
├── Jobs/            # Queue jobs
├── Mail/            # Mailable classes
├── Models/          # Eloquent models
├── Notifications/   # Notification classes
├── Policies/        # Authorization policies
└── Providers/       # Service providers

database/
├── migrations/      # Database migrations
├── seeders/         # Database seeders
└── factories/       # Model factories

resources/
├── views/           # Blade templates
├── js/              # JavaScript files
└── css/             # Stylesheets

routes/
├── web.php          # Web routes
├── api.php          # API routes
└── console.php      # Console routes
```

## Configuration

Key configuration files:
- `config/permission.php` - Role and permission settings
- `config/filesystems.php` - File storage configuration
- `config/queue.php` - Queue driver settings
- `config/mail.php` - Email configuration

## Contributing

Contributions are welcome! Please follow these guidelines:
1. Fork the repository
2. Create a feature branch
3. Commit your changes with clear messages
4. Write or update tests as needed
5. Submit a pull request

## Security

If you discover any security vulnerabilities, please report them immediately to the development team.

## License

This project is open-sourced software licensed under the MIT license.
