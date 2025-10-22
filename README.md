# 🗳️ School Voting System

A comprehensive Laravel-based school election management system that allows students to participate in democratic elections while providing administrators with powerful tools to manage the entire electoral process.

## 📊 System Overview

The School Voting System is an election-based voting platform where:
- **Admins** create election sessions, manage political parties (max 2), and oversee candidates
- **Students** participate in secure, anonymous voting
- **Real-time results** tracking with historical data and winner declarations

## 🚀 Features

### 🔐 Authentication System
- USN (University Serial Number) + Password login
- Role-based access control (Admin/Student)
- Secure session management
- User registration with auto-assigned student role

### 👨‍💼 Admin Capabilities
- **Party Management**: Create up to 2 political parties with logos, slogans, and colors
- **Election Management**: Full lifecycle from creation to winner declaration
- **Position Management**: Add multiple positions (President, VP, Secretary, etc.)
- **Candidate Management**: Maximum 2 candidates per position
- **Real-time Analytics**: Live vote counting with interactive charts
- **Results & Reports**: Historical election data and winner reports

### 🗳️ Student Features
- **Interactive Voting Interface**: User-friendly candidate selection
- **One Vote Per Position**: Enforced voting rules with validation
- **Vote Confirmation**: Secure ballot submission with confirmation
- **Election Dashboard**: Real-time election status and participation rates
- **Historical View**: Past election results and personal voting history

## 🛠️ Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade Templates + Bootstrap 5
- **Database**: MySQL
- **Charts**: Chart.js for analytics
- **Icons**: Bootstrap Icons
- **Authentication**: Laravel built-in auth

## 📋 Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL/MariaDB
- Node.js & npm (for frontend assets)

## 🚀 Installation & Setup

### 1. Clone the Repository
```bash
git clone https://github.com/Katsura34/voting.git
cd voting
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup
Edit `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=voting_system
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Migrations & Seeders
```bash
# Create database tables
php artisan migrate

# Seed with initial data (admin user, parties, sample students)
php artisan db:seed
```

### 6. Build Assets
```bash
npm run build
```

### 7. Start Development Server
```bash
php artisan serve
```

Visit `http://localhost:8000` to access the application.

## 👤 Default Login Credentials

### Admin Access
- **USN**: `ADMIN001`
- **Password**: `admin123`
- **Role**: Administrator

### Student Access (Sample Users)
- **USN**: `1AB21CS001` to `1AB21CS005`
- **Password**: `student123`
- **Role**: Student

## 📊 Database Structure

```
1. users (Authentication)
   ↓
2. parties (Max 2 parties) 
   ↓
3. elections (Voting sessions)
   ↓
4. election_positions (President, VP, etc.)
   ↓
5. election_candidates (2 per position max)
   ↓  
6. votes (One vote per user per position per election)
   ↓
7. election_winners (Historical results)
```

## 🔄 Complete Workflow

### Admin Journey:
1. Login → Admin dashboard
2. Create Parties → Add 2 parties (e.g., Red vs Blue)
3. Create Election → "Student Council 2025"
4. Add Positions → President, Vice President, Secretary
5. Add Candidates → 2 candidates per position
6. Activate Election → Students can now vote
7. Monitor Results → Live bar charts
8. Close Election → Declare winners, save to history

### Student Journey:
1. Login → Student dashboard
2. Check Election → "Student Council 2025" is active
3. View Candidates → See 2 candidates per position
4. Cast Votes → One vote per position
5. Submit Ballot → Confirmation message
6. Wait for Results → Election closes, winners announced

## 🛡️ Security Features

- **CSRF Protection**: All forms protected against cross-site request forgery
- **Role-based Middleware**: Separate admin and student access controls
- **Vote Integrity**: One vote per user per position per election
- **Input Validation**: Comprehensive form validation with custom rules
- **Secure Sessions**: Laravel's built-in session security

## 🎨 UI/UX Features

- **Responsive Design**: Bootstrap 5 for mobile-friendly interface
- **Real-time Updates**: Live vote counting and progress tracking
- **Interactive Elements**: Click-to-select candidate cards
- **Visual Feedback**: Color-coded parties and status indicators
- **Accessibility**: Screen reader friendly with proper ARIA labels

## 📈 Analytics & Reporting

- **Live Vote Counting**: Real-time charts using Chart.js
- **Participation Rates**: Student engagement tracking
- **Historical Data**: Past election archives
- **Winner Reports**: Automated result generation
- **Export Functionality**: Results can be exported for records

## 🔧 Configuration

### Party Limits
To change the maximum number of parties, update the validation in:
- `app/Http/Requests/StorePartyRequest.php`
- Admin dashboard display logic

### Election Rules
Customize voting rules in:
- `app/Http/Controllers/Student/VotingController.php`
- Vote validation middleware

## 🚀 Production Deployment

1. Set `APP_ENV=production` in `.env`
2. Configure proper database credentials
3. Set `APP_DEBUG=false`
4. Configure web server (Apache/Nginx)
5. Set proper file permissions
6. Enable HTTPS for security

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🆘 Support

If you encounter any issues or need help with setup:
1. Check the troubleshooting section below
2. Open an issue on GitHub
3. Contact the development team

## 🔧 Troubleshooting

### Common Issues

**Database Connection Error**
- Verify database credentials in `.env`
- Ensure MySQL service is running
- Check if database exists

**Permission Denied**
```bash
sudo chmod -R 775 storage bootstrap/cache
```

**Middleware Not Found**
```bash
php artisan config:cache
php artisan route:cache
```

**Assets Not Loading**
```bash
npm run build
php artisan storage:link
```

---

**Built with ❤️ for democratic school governance**