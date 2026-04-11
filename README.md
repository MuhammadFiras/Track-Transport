# Track Transport 2

> **Status:** 🚀 Very Early Development (Pre-Alpha)

An integrated digital platform for vehicle testing, quality assurance, and safety certification management. Track Transport 2 consolidates fragmented testing data from multiple testing methodologies into a centralized system to streamline vehicle compliance workflows.

## 🎯 Project Overview

Track Transport 2 is a comprehensive platform designed to manage the complete lifecycle of vehicle testing and certification:

- **Emission Testing (US 1.x)**: Real-time monitoring and validation of exhaust emissions against regulatory standards
- **Aerodynamic Analysis (US 2.x)**: Wind tunnel experimentation data collection and analysis
- **Electrical & Safety Systems Testing (US 3.x)**: Automated in-system testing for electrical components, lights, sensors, and safety features
- **Crashworthiness Analysis (US 4.x)**: Biomechanical impact assessment using anthropomorphic test devices (dummy sensors)

## 🛠️ Tech Stack

- **Backend**: Laravel 11 (PHP)
- **Frontend**: Blade Templates, JavaScript (MVC Architecture)
- **Database**: TBD
- **Real-time Monitoring**: WebSocket-ready architecture
- **API Integration**: NHTSA VPIC (Vehicle Product Information Catalog)

## 📋 Key Features (Planned)

### Journey 1: Emission Certification
- Vehicle registration and emission standard configuration
- Real-time gas analyzer monitoring
- Automated validation against regulatory thresholds
- Digital certificate generation

### Journey 2: Wind Tunnel Testing
- Test object configuration and registration
- Sensor calibration management
- Real-time data visualization
- Export and analysis capabilities

### Journey 3: Electrical Testing & QC
- VIN barcode scanning for automatic profile loading
- Automated electrical test sequences
- Real-time anomaly detection
- Digital QC reporting

### Journey 4: Crash Testing Analysis
- Vehicle material configuration
- Sensor data extraction from crash test dummies
- Biomechanical injury criteria calculation (HIC)
- Crash box geometry comparison

## 📁 Project Structure

```
track-transport/
├── app/
│   ├── Http/Controllers/
│   ├── Models/
│   └── Providers/
├── resources/
│   ├── views/
│   │   └── vin-scanner.blade.php (US 3.1 Implementation)
│   ├── js/
│   │   ├── Model/
│   │   ├── View/
│   │   └── Controller/
│   └── css/
├── routes/
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
├── config/
├── tests/
└── public/
```

## 🚦 Development Status

| Journey | Feature | Status |
|---------|---------|--------|
| 1 | Emission Testing | 📝 In Documentation |
| 2 | Wind Tunnel Testing | 📝 In Documentation |
| 3 | VIN Scanner (US 3.1) | ✅ UI Complete, API Integration Complete |
| 4 | Crash Testing Analysis | 📝 In Documentation |

**Legend**: ✅ Complete | 🏗️ In Progress | 📝 In Documentation | ⏳ Planned

## 🔧 Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm
- MySQL/PostgreSQL

### Getting Started

```bash
# Clone repository
git clone <repository-url>
cd track-transport

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Build assets
npm run dev

# Start development server
php artisan serve
```

## 📚 Documentation

- [Vehicle Identification Documentation](./Vehicle_Identification.md) - US 3.1 implementation details
- [API Integration Guide](./docs/api-integration.md) - NHTSA VPIC API setup
- [MVC Architecture Pattern](./docs/mvc-architecture.md) - Frontend structure

## ⚠️ Early Development Notes

- **No Production Ready**: This project is in very early development stages
- **API Dependencies**: NHTSA VPIC API integration is functional but error handling needs enhancement
- **Database Schema**: Schema not finalized, subject to change
- **Authentication**: Security/auth system not yet implemented
- **Performance**: Real-time monitoring optimization pending

## 📋 Next Steps

1. Database schema design and migrations
2. User authentication & authorization system
3. API gateway setup for external integrations
4. Real-time WebSocket implementation
5. Comprehensive error handling across all journeys
6. Unit & integration test coverage
7. Production deployment pipeline

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
