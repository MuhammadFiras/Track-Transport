# Track Transport - Electrical Testing & Quality Assurance System

> **Status:** 🏗️ In Active Development (US 3.x Implementation Phase)

A dedicated platform for automated electrical and safety systems testing of vehicles. Track Transport provides production operators with a streamlined workflow for VIN scanning, automated electrical component testing, anomaly detection, and digital QC report generation.

## 🎯 Project Overview

Track Transport is currently focused on **Journey 3: Electrical & Safety Systems Testing (US 3.x)**, delivering automated testing capabilities for electrical components, lights, sensors, and safety features in vehicles. The system automates the testing process from vehicle identification through QC reporting.

### Core Workflows:
1. **Vehicle Identification (US 3.1)**: Scan vehicle VIN barcode to automatically load test profiles
2. **Automated Testing (US 3.2)**: Hardware simulator for testing electrical components
3. **Real-time Monitoring (US 3.3)**: Log sensor readings and detect anomalies with automatic alerting
4. **QC Report Generation (US 3.4)**: Generate digital quality assurance reports
5. **Test Finalization (US 3.5)**: Complete testing workflow and update production status

## 🛠️ Tech Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Blade Templates with JavaScript (MVC Architecture)
- **Styling**: Tailwind CSS 4.x with Vite
- **Build Tool**: Vite 8.x
- **Database**: MySQL/PostgreSQL (via Laravel migrations)
- **External APIs**: NHTSA VPIC (Vehicle Product Information Catalog)
- **JavaScript Modules**: Modular MVC pattern (VINModel, VINView, VINController)

## ✨ Implemented Features

### US 3.1: Vehicle Identification
- ✅ VIN barcode scanning interface
- ✅ NHTSA VPIC API integration for vehicle data lookup
- ✅ Real-time vehicle information display (model name, color, specifications)
- ✅ Form validation (11-17 character VIN format)
- ✅ Error handling for invalid VINs

### US 3.2: Automated Electrical Testing
- ✅ Hardware simulator interface for testing components
- ✅ Support for multiple electrical components testing
- ✅ Voltage recording and validation
- ✅ Automatic test profile loading by VIN

### US 3.3: Real-time Anomaly Detection & Logging
- ✅ Sensor data logging with server-side validation
- ✅ Automatic anomaly detection (voltage bounds checking)
- ✅ PASS/FAIL status determination
- ✅ Database persistence of test results
- ✅ CSRF protection for data submissions

### US 3.4: Digital QC Report Generation
- ✅ QC report view with test results
- ✅ Report generation from test data
- ✅ Digital documentation of test outcomes

### US 3.5: Test Finalization
- ✅ Finalize test workflow
- ✅ Update production status
- ✅ Test completion tracking

## 📁 Project Structure

```
Track-Transport/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Controller.php (Base controller)
│   │       └── SimulatorController.php (US 3.2-3.5 implementations)
│   ├── Models/
│   │   ├── User.php
│   │   ├── Vehicle.php (VIN and vehicle data)
│   │   ├── TestProfile.php (Component bounds/specifications)
│   │   ├── SensorLog.php (Test results and anomaly detection)
│   │   └── QcReport.php (Quality assurance reports)
│   └── Providers/
│       └── AppServiceProvider.php
├── resources/
│   ├── views/
│   │   ├── vin-scanner.blade.php (US 3.1 - Vehicle identification)
│   │   ├── simulator.blade.php (US 3.2-3.3 - Testing interface)
│   │   ├── report.blade.php (US 3.4 - QC report generation)
│   │   └── welcome.blade.php
│   ├── js/
│   │   ├── app.js (Application bootstrap)
│   │   ├── bootstrap.js
│   │   ├── Controller/ (MVC Controller layer)
│   │   │   └── VINController.js
│   │   ├── Model/ (MVC Model layer)
│   │   │   └── VINModel.js (NHTSA API integration)
│   │   └── View/ (MVC View layer)
│   │       └── VINView.js (DOM manipulation & rendering)
│   └── css/
│       └── app.css
├── routes/
│   └── web.php (Application routes)
├── database/
│   ├── migrations/ (Schema definitions)
│   │   ├── create_users_table.php
│   │   ├── create_vehicles_table.php
│   │   ├── create_test_profiles_table.php
│   │   ├── create_sensor_logs_table.php
│   │   └── create_qc_reports_table.php
│   ├── factories/
│   │   └── UserFactory.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── cache.php
│   ├── database.php
│   ├── filesystems.php
│   ├── logging.php
│   ├── mail.php
│   ├── queue.php
│   ├── services.php
│   └── session.php
├── public/
│   ├── index.php (Application entry point)
│   └── js/ (Frontend assets)
├── tests/
│   ├── Feature/ (Feature tests)
│   ├── Integration/ (Integration tests)
│   └── Unit/ (Unit tests)
├── composer.json (PHP dependencies)
├── package.json (Node.js dependencies)
├── vite.config.js (Build configuration)
└── README.md
```

## 🚦 Development Status

| User Story | Feature | Status |
|---------|---------|--------|
| US 3.1 | Vehicle Identification (VIN Scanner) | ✅ Complete |
| US 3.2 | Automated Electrical Test (Hardware Simulator) | ✅ Complete |
| US 3.3 | Real-time Anomaly Detection & Logging | ✅ Complete |
| US 3.4 | Digital QC Report Generation | ✅ Complete |
| US 3.5 | Test Finalization & Status Update | ✅ Complete |
| Future | User Authentication & Authorization | ⏳ Planned |
| Future | Advanced Error Handling & Validation | 🏗️ In Progress |
| Future | Production Deployment Pipeline | ⏳ Planned |

**Legend**: ✅ Complete | 🏗️ In Progress | ⏳ Planned

## 🔧 Installation & Setup

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js 16+ and npm
- MySQL or PostgreSQL database
- Git

### Step 1: Clone & Install Dependencies

```bash
# Clone the repository
git clone <repository-url>
cd Track-Transport

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### Step 2: Environment Configuration

```bash
# Copy environment template
cp .env.example .env

# Generate application key
php artisan key:generate
```

Configure your database in the `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=track_transport
DB_USERNAME=root
DB_PASSWORD=
```

### Step 3: Database Setup

```bash
# Run migrations
php artisan migrate

# (Optional) Seed the database
php artisan db:seed
```

### Step 4: Build Frontend Assets

```bash
# Development build with watch mode
npm run dev

# Production build
npm run build
```

### Step 5: Run the Application

```bash
# Start Laravel development server
php artisan serve
```

The application will be available at `http://localhost:8000`

## 🎯 Quick Start

1. **Access the Application**: Navigate to `http://localhost:8000`
2. **Scan Vehicle VIN**: Use the VIN Scanner interface (US 3.1) to scan or enter a vehicle VIN
3. **Run Tests**: Access the simulator (US 3.2) to test electrical components
4. **Review Results**: Check the QC report (US 3.4) for test outcomes
5. **Finalize**: Complete the testing workflow (US 3.5)

## 📚 Documentation

- **[Vehicle Identification (US 3.1)](./Vehicle_Identification.md)** - VIN scanning and NHTSA API integration details
- **[US 3.3 Implementation](./US_3.3_IMPLEMENTATION.md)** - Anomaly detection and logging backend implementation
- **[Frontend Architecture](./resources/js/)** - MVC pattern with VINModel, VINView, VINController
- **[API Routes](./routes/web.php)** - Available endpoints for vehicle identification and testing

## ⚠️ Current Development Notes

### What's Working
- ✅ VIN scanning and vehicle data retrieval via NHTSA VPIC API
- ✅ Automated electrical component testing interface
- ✅ Real-time anomaly detection with server-side validation
- ✅ Digital QC report generation
- ✅ Database persistence for all test data
- ✅ CSRF protection for all POST requests

### Areas for Enhancement
- **User Authentication**: Security/auth system not yet implemented
- **Error Handling**: API error handling and user feedback needs refinement
- **Testing Coverage**: Unit and integration test coverage pending
- **Performance**: Real-time monitoring optimization in progress
- **Scalability**: Prepared for production deployment setup

## 📋 Next Steps

1. Implement user authentication and authorization system
2. Add comprehensive error handling and user feedback mechanisms
3. Enhance API error handling for NHTSA VPIC integration
4. Write unit and integration tests for all components
5. Performance optimization for real-time monitoring
6. Database backup and recovery procedures
7. Production deployment pipeline setup
8. Documentation expansion for API endpoints

## 🏗️ Future Enhancements

- **Journey 1**: Emission Testing workflow
- **Journey 2**: Wind Tunnel Testing capabilities
- **Journey 4**: Crash Testing Analysis features
- **Advanced Features**: WebSocket real-time monitoring, batch processing, reporting analytics

## 👥 Development Team

- Muhammad Firas (Emission Testing)
- Muhammad Nurwahyudi Adhitama (Wind Tunnel Testing)
- Naila Hanifah (Electrical Testing & VIN Scanner)
- Nur Hikmah (Crash Testing Analysis)

## 📄 References

Based on PRD: "Platform Terintegrasi Manajemen Kelayakan dan Sertifikasi Keamanan Kendaraan Berbasis Digital" (Integrated Vehicle Fitness & Security Certification Management Platform)

Year: 2026

## ⚖️ License

This project is developed as part of software testing and quality assurance coursework.

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
