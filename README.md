# Vehicle Rental Management System (V.R.M)

A modern, secure PHP + MySQL vehicle rental management system with full CRUD operations, booking system, advanced search capabilities, and Ajax features. Built with security-first approach and responsive design.

## 🚀 Features Overview

### ✅ Core Functionality
- **CRUD Operations** - Complete Create, Read, Update, Delete for vehicles
- **Booking System** - Real-time availability checking with date conflict detection
- **Advanced Search** - Multi-criteria search (type, price range, keyword)
- **Ajax Features** - Live autocomplete, date availability checker, real-time cost calculator
- **User Authentication** - Secure login/signup system with session management
- **Responsive Design** - Modern UI that works on all devices

### 🔒 Security Features (All 5 Required)
1. **Input Filtering** - PHP filter_var(), filter_input(), custom validation functions
2. **Output Escaping** - htmlspecialchars() with ENT_QUOTES on all outputs
3. **Session Protection** - Secure session configuration, session regeneration, protected pages
4. **CAPTCHA** - Custom CAPTCHA on registration to prevent bots
5. **Password Encryption** - bcrypt hashing (PASSWORD_DEFAULT)

**Additional Security:**
- CSRF token protection on all forms
- SQL injection prevention (prepared statements)
- XSS prevention (input/output sanitization)
- Secure session configuration (httponly, use_only_cookies)

## 📋 Requirements Met

| Criteria | Status | Implementation |
|----------|--------|----------------|
| **CRUD Operations** | ✅ Complete | Add, View, Edit, Delete vehicles |
| **Security (5 features)** | ✅ Complete | All 5 + additional measures |
| **Multi-criteria Search** | ✅ Complete | Type + Price Range + Keyword |
| **Ajax Functionality** | ✅ Complete | Autocomplete + Date Checker + Calculator |
| **Template Engine** | 🔄 Ready | Structure ready for Twig/Smarty |
| **Version Control** | ✅ Git Ready | Complete project structure |

## 🌐 Live Demo

**Website URL:** https://student.bicnepal.edu.np/~np02cs4s250016/public/

**Admin Login:**
- Email: `admin@vrm.com`
- Password: `admin@2061`


## 🛠️ Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/XAMPP/LAMP
- GD Library (for CAPTCHA)

### Step 1: Database Setup
```sql
-- Create database
CREATE DATABASE vehicle_rental_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Import schema
mysql -u root -p vehicle_rental_db < database.sql
```

### Step 2: Configuration
Edit `config/db.php`:
```php
$servername = "localhost";
$username = "root";          // Your MySQL username
$password = "";              // Your MySQL password
$dbname = "vehicle_rental_db";
```

### Step 3: File Permissions
```bash
chmod 755 -R public/
chmod 755 -R assets/
mkdir logs
chmod 777 logs/
```

### Step 4: Access
**Local Development:** `http://localhost/VR.M/public/index.php`

**Live Website:** https://student.bicnepal.edu.np/~np02cs4s250016/public/

## 📁 Project Structure

```
V.R.M/
├── assets/
│   ├── css/
│   │   ├── style.css              # Main application styles
│   │   └── landing.css            # Landing page styles
│   └── js/
│       ├── booking-calculator.js  # Real-time cost calculation
│       ├── search-autocomplete.js # Ajax autocomplete
│       ├── date-availability.js   # Ajax date checker
│       └── landing.js             # Landing page auth
├── config/
│   └── db.php                     # Database configuration
├── includes/
│   ├── functions.php              # Security & helper functions
│   ├── header.php                 # Common header
│   └── footer.php                 # Common footer
├── public/
│   ├── landing.php                # Login/Signup landing page
│   ├── login_handler.php          # Ajax login handler
│   ├── register_handler.php       # Ajax registration handler
│   ├── index.php                  # Main dashboard
│   ├── add.php                    # Create vehicle (C)
│   ├── edit.php                   # Update vehicle (U)
│   ├── delete.php                 # Delete vehicle (D)
│   ├── book.php                   # Booking form
│   ├── process_booking.php        # Process booking
│   ├── my_bookings.php            # User bookings
│   ├── search.php                 # Advanced search
│   ├── check_availability.php     # Ajax availability
│   ├── ajax_search_handler.php    # Ajax autocomplete
│   ├── captcha.php                # CAPTCHA generation
│   └── logout.php                 # Logout handler
├── logs/                          # Security logs (auto-created)
├── database.sql                   # Database schema
├── README.md                      # This file
└── SECURITY.md                    # Security documentation

```

## 🎯 Key Features Explained

### 1. CRUD Operations
- **Create**: [add.php](public/add.php) - Add new vehicles with validation
- **Read**: [index.php](public/index.php) - Display all vehicles with status
- **Update**: [edit.php](public/edit.php) - Modify vehicle details
- **Delete**: [delete.php](public/delete.php) - Remove vehicles (with confirmation)

### 2. Booking System
- Real-time availability checking via Ajax
- Date conflict detection
- Automatic cost calculation
- Booking history tracking

### 3. Advanced Search
- Search by **vehicle type** (Car, Bike, Scooter)
- Search by **price range** (min/max daily rate)
- Search by **keyword** (model, plate number)
- All criteria can be combined simultaneously

### 4. Ajax Features
- **Autocomplete Search** - Suggests vehicles as you type
- **Date Availability Checker** - Real-time booking conflict detection
- **Cost Calculator** - Live total cost calculation
- **Login/Signup** - Ajax form submission without page reload

## 🔐 Security Implementation

See [SECURITY.md](SECURITY.md) for detailed security documentation.

**Key Security Features:**
- All database queries use prepared statements
- CSRF tokens on all forms
- Session-based authentication
- Input validation and sanitization
- Output escaping (XSS prevention)
- Password hashing with bcrypt
- CAPTCHA on registration
- Secure session configuration

## 🌐 User Guide

### First Time Setup
1. Navigate to `landing.php`
2. Click "Sign Up"
3. Fill registration form (includes CAPTCHA)
4. Login with your credentials

### Adding a Vehicle
1. Login to system
2. Click "Add Vehicle" in navigation
3. Fill in vehicle details
4. Submit form

### Booking a Vehicle
1. Find available vehicle on homepage
2. Click "Book Now"
3. Select start and end dates
4. System checks availability in real-time
5. Confirm booking if available

### Searching Vehicles
1. Click "Search" in navigation
2. Enter search criteria:
   - Keyword (optional)
   - Vehicle type (optional)
   - Price range (optional)
3. Click "Search"

## 🧪 Testing

### Manual Testing Checklist
- [ ] User registration with CAPTCHA
- [ ] Login/Logout functionality
- [ ] Add new vehicle
- [ ] Edit existing vehicle
- [ ] Delete vehicle
- [ ] Search with multiple criteria
- [ ] Book available vehicle
- [ ] Ajax date availability check
- [ ] View booking history

### Security Testing
Recommended tools:
- OWASP ZAP - Automated vulnerability scanning
- Burp Suite - Manual security testing
- SQLMap - SQL injection testing

## 📊 Database Schema

### Tables
1. **users** - User accounts
   - id, username, email, password (bcrypt), is_admin, created_at

2. **vehicles** - Vehicle inventory
   - id, plate_number, model, type, daily_rate, status, created_at

3. **bookings** - Rental bookings
   - id, user_id, vehicle_id, start_date, end_date, total_cost, booking_status, created_at

## 🎨 UI/UX Features

- Modern gradient design
- Responsive layout (mobile-friendly)
- Smooth animations
- Interactive hover effects
- Real-time feedback
- Loading states
- Error handling with user-friendly messages

## 🚧 Future Enhancements (Optional)

- Template Engine integration (Twig/Smarty)
- Email notifications
- Payment integration
- Admin dashboard
- Vehicle images upload
- Review/rating system
- Advanced reporting
- Two-factor authentication

## 📝 Notes

- Default login for testing: Create account via signup
- All passwords are encrypted (cannot be retrieved)
- Session timeout: 24 minutes of inactivity
- CAPTCHA refreshes on each registration attempt


## 📄 License

Educational project @bic

---

**Project**: Vehicle Rental Management System
**Version**: 1.0
**Last Updated**: February 2026
│   ├── delete.php              # Delete vehicle
│   ├── book.php                # Booking form
│   ├── process_booking.php     # Process booking
│   ├── my_bookings.php         # User bookings
│   ├── search.php              # Advanced search
│   ├── ajax_search_handler.php # Ajax autocomplete
│   ├── login.php               # User login
│   ├── register.php            # User registration
│   ├── logout.php              # Logout
│   └── captcha.php             # CAPTCHA generator
├── assets/
│   ├── css/style.css           # Custom styles
│   └── js/
│       ├── booking-calculator.js   # Real-time cost calculation
│       └── search-autocomplete.js  # Ajax search suggestions
└── database.sql                # Database schema
```

## Features Details

### CRUD Operations
- Add new vehicles with validation
- View all vehicles with status
- Edit vehicle details
- Delete vehicles (with confirmation)

### Booking System
- Check vehicle availability in real-time
- Calculate booking cost automatically
- Prevent double-booking
- View booking history

### Search System
- Search by keyword (plate/model)
- Filter by vehicle type
- Filter by price range
- Ajax autocomplete suggestions

### Ajax Features
1. **Real-time Cost Calculator**
   - Calculates total cost as dates change
   - Validates date selections
   - Enables/disables submit button

2. **Search Autocomplete**
   - Live suggestions as you type
   - Fetches from database
   - Click to fill

## Security Implementation

### 1. SQL Injection Prevention
- All queries use prepared statements
- Input validation with filter_var()
- Type checking for IDs

### 2. XSS Prevention
- All output escaped with htmlspecialchars()
- No raw user input displayed

### 3. CSRF Protection
- Tokens on all forms
- Token validation before processing
- Session-based token generation

### 4. Password Security
- password_hash() with bcrypt
- password_verify() for login
- No plain text storage

### 5. Session Security
- session_regenerate_id() on login
- Secure session destruction
- Flash message system

## Usage

### For Users
1. Register account (with CAPTCHA)
2. Login
3. Browse available vehicles
4. Book vehicles (dates validated)
5. View booking history

### For Admins
1. Login
2. Add/Edit/Delete vehicles
3. View all bookings
4. Manage inventory

## Requirements
- PHP 7.4+
- MySQL 5.7+
- Web server (Apache/Nginx)

## Notes
- Dynamic availability checking
- Responsive Bootstrap design
- Clean, commented code
- Ready for deployment

## Security Best Practices Implemented
✅ Prepared statements
✅ CSRF tokens
✅ Password hashing
✅ Input validation
✅ Output escaping
✅ Session security
✅ CAPTCHA protection

---
