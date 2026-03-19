# SGA - Academic Services Management System

A comprehensive web-based academic management system built with PHP MVC architecture. Designed for higher education institutions to manage student records, courses, enrollment requests, grade sheets, and faculty operations.

## 📋 Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Quick Start](#quick-start)
- [User Roles](#user-roles)
- [Security Features](#security-features)
- [Contributing](#contributing)

## ✨ Features

### Core Functionality
- **Student Record Management** - Create, update, and validate student enrollment records
- **Course Management** - Organize courses and course units with hierarchical structure
- **Grade Management** - Record and manage student grades and performance metrics
- **Enrollment Requests** - Handle and process student enrollment applications
- **User Management** - Multi-role access control (Student, Staff, Manager)
- **Reporting** - Generate academic reports and data exports
- **Multi-language Support** - Interface available in Portuguese and English

### Dashboard Features
- **Students** - View personal records, course enrollment, and grades
- **Staff** - Access grade sheets, manage student evaluations
- **Managers** - System overview with statistics, full administrative control

## 🛠 Tech Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript
- **Architecture**: MVC (Model-View-Controller)
- **Server**: XAMPP (Apache, PHP, MySQL)
- **Database Access**: PDO with prepared statements

## 📁 Project Structure

```
SGA-Academic-Services-Management-System/
├── config/                          # Application configuration
│   ├── app.php                     # App constants
│   └── database.php                # Database connection (PDO)
├── core/                           # Core framework
│   ├── bootstrap.php               # Application initialization
│   ├── helpers.php                 # Global helper functions
│   ├── i18n.php                    # Internationalization
│   ├── Router.php                  # URL routing system
│   └── session.php                 # Session management
├── controllers/                    # Application logic
│   ├── ManagerCoursesController.php
│   ├── ManagerUnitsController.php
│   ├── ManagerUsersController.php
│   ├── ManagerReportsController.php
│   ├── StudentRecordController.php
│   ├── StaffGradesController.php
│   └── EnrollmentRequestsController.php
├── models/                         # Data layer
│   ├── User.php                    # User model
│   ├── Course.php                  # Course model
│   ├── CourseUnit.php              # Course unit model
│   ├── Aluno.php                   # Student/record model
│   ├── GradeSheet.php              # Grade sheet model
│   ├── StudentRecord.php           # Student record model
│   ├── EnrollmentRequest.php       # Enrollment request model
│   └── RequestMessage.php          # Message model
├── views/                          # Presentation layer
│   ├── auth/                       # Authentication views
│   ├── layouts/                    # Base layouts and navbar
│   ├── manager/                    # Manager dashboard & forms
│   ├── student/                    # Student views
│   ├── staff/                      # Staff views
│   ├── messages/                   # Message views
│   └── shared/                     # Shared components
├── public/                         # Web root (server-accessible)
│   ├── index.php                   # Entry point
│   ├── login.php                   # Login page
│   ├── logout.php                  # Logout handler
│   ├── dashboard.php               # Dashboard router
│   ├── assets/                     # CSS, JS, images
│   ├── manager/                    # Manager pages
│   ├── staff/                      # Staff pages
│   ├── student/                    # Student pages
│   └── uploads/                    # User uploads (photos)
├── routes/                         # Route definitions
│   └── web.php                     # Application routes
├── sql/                            # Database scripts
│   ├── schema.sql                  # Database schema
│   ├── seeds.sql                   # Initial test data
│   ├── criar-grupos.sql            # Group creation
│   └── reset-passwords.sql         # Password reset
├── translations/                   # Language files
│   ├── en.php                      # English translations
│   └── pt.php                      # Portuguese translations
└── index.php                       # Root entry point
```

## 🚀 Quick Start

### Prerequisites
- XAMPP (Apache, PHP 7.4+, MySQL)
- Git

### Installation

1. **Clone Repository**
```bash
git clone <repository-url>
cd SGA-Academic-Services-Management-System
```

2. **Configure Database**

Using phpMyAdmin:
1. Create a new database: `aulasphp`
2. Select the database
3. Go to the "SQL" tab
4. Paste contents from `/sql/schema.sql`
5. Execute
6. Paste contents from `/sql/seeds.sql` and execute

Or via MySQL terminal:
```bash
mysql -u root < sql/schema.sql
mysql -u root aulasphp < sql/seeds.sql
```

3. **Update Configuration**

Edit `config/database.php` if using different credentials:
```php
'host' => 'localhost',
'user' => 'root',
'password' => '',
'database' => 'aulasphp'
```

4. **Access Application**

Open your browser:
```
http://localhost/SGA---Academic-Services-Management-System/public/login.php
```

### Test Credentials

| Username | Password | Role |
|----------|----------|------|
| joao_silva | password123 | Student |
| maria_santos | password123 | Student |
| carlos_funcionario | password123 | Staff |
| ana_gestor | password123 | Manager |

## 👥 User Roles

### Student (Aluno)
- View and edit personal enrollment record
- Browse available courses
- Submit records for validation
- View grades and transcripts
- Check enrollment status

### Staff (Funcionário)
- Access grade sheets and manage evaluations
- Record student grades and scores
- View student records
- Generate simple reports
- Manage course evaluations

### Manager (Gestor Pedagógico)
- Full course and course unit management
- User account management and permissions
- Validate student records
- Create and modify study plans
- Generate comprehensive academic reports
- Export data to CSV format
- System overview and analytics

## 🔐 Security Features

- ✅ **Password Security** - bcrypt hashing via `password_hash()`
- ✅ **SQL Injection Prevention** - PDO prepared statements throughout
- ✅ **Session Security** - Timeout and secure session handling
- ✅ **Access Control** - Role-based middleware authorization
- ✅ **Input Validation** - Server-side validation for all inputs
- ✅ **Output Escaping** - HTML escaping for XSS prevention
- ✅ **CSRF Protection** - Token-based CSRF prevention

## 📝 Development Roadmap

### Completed Phases
- ✅ Project architecture and folder structure
- ✅ Centralized configuration setup
- ✅ Database schema with 11 tables and relationships
- ✅ Secure authentication system (bcrypt passwords)
- ✅ Session management and role-based middleware
- ✅ Multi-role dashboards (Student, Staff, Manager)

### Upcoming Phases
1. **Student Record Forms** - Creation, editing, photo upload
2. **Enrollment Requests** - Request processing and approval workflow
3. **Academic Management** - Full CRUD for courses and course units
4. **Grade Management** - Grade sheets and grade recording
5. **System Auditing** - Comprehensive logs and audit trails
6. **UI/UX Improvements** - Enhanced visual design and user experience

## 🗄️ Database

### Schema Overview
The system includes 11 tables:
- **users** - System user accounts with roles
- **alunos** - Student profiles and records
- **cursos** - Academic courses
- **course_units** - Course curriculum units
- **enrollments** - Student enrollment applications
- **grade_sheets** - Grade evaluation records
- **grades** - Individual grade entries
- **messages** - System notification messaging
- And supporting relationship tables

### Initial Setup
Default test data includes:
- 4 pre-configured user accounts with different roles
- Sample courses and course units
- Example student records

## 🧪 Configuration

Edit `config/database.php` to customize database connection:
```php
<?php
return [
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'database' => 'aulasphp',
    'port' => 3306
];
```

Edit `config/app.php` for application settings:
```php
<?php
return [
    'BASE_URL' => 'http://localhost/SGA---Academic-Services-Management-System/',
    'DEBUG' => true,
    'SESSION_TIMEOUT' => 3600
];
```

## 🌐 Multi-language Support

The system includes translation files for:
- **Portuguese** (`translations/pt.php`) - Default language
- **English** (`translations/en.php`) - English interface

Switch languages via the language switcher in the navigation bar.

## 📚 Key Files and Directories

| Path | Purpose |
|------|---------|
| `config/` | Application and database configuration |
| `core/` | Framework core files (routing, helpers, sessions) |
| `models/` | Data access layer classes |
| `controllers/` | Application logic and request handling |
| `views/` | HTML templates and presentation |
| `public/` | Web-accessible directory (entry point) |
| `sql/` | Database schema and seed scripts |
| `routes/web.php` | Application route definitions |

## 🤝 Contributing

Contributions are welcome! Please follow these guidelines:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Code Standards
- Follow Clean Code principles
- Use meaningful variable and function names
- Add comments for complex logic
- Test all changes thoroughly before submitting

## 📄 License

This project is an academic management system developed for educational purposes.

## 📞 Support

For issues, questions, or suggestions, please open an issue in the repository or contact the development team.

---

**Last Updated**: March 2024  
**Status**: Active Development
