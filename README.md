# WIDAS - Web-Based Intrusion Detection and Alert System

A comprehensive security monitoring platform built with Laravel 13, React 19, Inertia.js, and Tailwind CSS. WIDAS provides real-time intrusion detection, security monitoring, alert management, and threat analysis capabilities.

## Features

### Intrusion Detection Engine
- **Brute Force Detection** - Detects multiple failed login attempts and locks accounts
- **SQL Injection Detection** - Identifies SQL injection patterns (UNION SELECT, OR 1=1, DROP TABLE, etc.)
- **XSS Detection** - Detects cross-site scripting attempts
- **Session Abuse Detection** - Identifies abnormal session behavior
- **Unauthorized Access Detection** - Monitors for privilege escalation attempts
- **API Abuse Detection** - Rate limiting and malformed payload detection

### Security Dashboard
- Real-time threat monitoring with interactive charts
- Severity distribution analysis
- Threat type breakdown
- Login activity monitoring
- Overall threat score calculation

### Alert System
- Multi-channel notifications (dashboard + email)
- Severity-based alerting (Low, Medium, High, Critical)
- Alert acknowledgment and resolution workflows
- Real-time alert count indicators

### User Management
- Role-Based Access Control (Admin, Analyst, User)
- User CRUD operations
- Account status management
- Login attempt tracking

### Security Features
- Rate limiting on authentication and API
- IP blocking with automatic and manual modes
- Comprehensive audit trail
- Activity logging
- Session management

### Reporting
- Daily, weekly, and monthly security reports
- PDF export capabilities
- Executive summary with key metrics

## System Architecture

```
┌─────────────────────────────────────────────────────────────────────────┐
│                           WIDAS Architecture                            │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  ┌──────────────┐    ┌──────────────┐    ┌────────────────────────┐    │
│  │   React 19    │    │  Inertia.js  │    │    Laravel 13 API      │    │
│  │   Frontend    │◄──►│   Bridge     │◄──►│   (Controllers,        │    │
│  │  (TypeScript) │    │              │    │    Services, Repos)     │    │
│  └──────────────┘    └──────────────┘    └───────────┬────────────┘    │
│                                                       │                 │
│                        ┌──────────────────────────────┼──────┐          │
│                        │           Detection Engine   │      │          │
│                        │  ┌───────────────────────────▼──┐   │          │
│                        │  │  Middleware (CheckBlockedIp,  │   │          │
│                        │  │  IntrusionDetection, LogAct)  │   │          │
│                        │  └───────────────────┬───────────┘   │          │
│                        │                      │              │          │
│                        │  ┌───────────────────▼───────────┐   │          │
│                        │  │  Services Layer                │   │          │
│                        │  │  - DetectionEngine             │   │          │
│                        │  │  - AlertService                │   │          │
│                        │  │  - ThreatAnalysisService       │   │          │
│                        │  │  - AuditService                │   │          │
│                        │  │  - ReportingService            │   │          │
│                        │  └───────────────────┬───────────┘   │          │
│                        │                      │              │          │
│                        │  ┌───────────────────▼───────────┐   │          │
│                        │  │  Queue (Jobs)                  │   │          │
│                        │  │  - ProcessIntrusionEvent       │   │          │
│                        │  │  - CleanupOldLogs              │   │          │
│                        │  └───────────────────────────────┘   │          │
│                        └──────────────────────────────────────┘          │
│                                                                         │
│  ┌──────────────────────────────────────────────────────────────────┐   │
│  │                        Database (MySQL/SQLite)                   │   │
│  │  Users │ Roles │ Permissions │ Intrusion Events │ Alerts        │   │
│  │  Security Logs │ Audit Logs │ Blocked IPs │ Threat Rules        │   │
│  │  Login Attempts │ Activity Logs │ Threat Scores │ Settings      │   │
│  └──────────────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────────┘
```

## Tech Stack

### Backend
- **Laravel 13** - PHP framework
- **PHP 8.4+** - Runtime
- **MySQL 8 / SQLite** - Database
- **Laravel Sanctum** - API authentication
- **Laravel Queues** - Async job processing
- **Laravel Notifications** - Multi-channel notifications
- **Laravel Scheduler** - Cron task management

### Frontend
- **React 19** - UI framework
- **TypeScript** - Type safety
- **Inertia.js** - Server-driven SPA
- **Tailwind CSS v4** - Styling
- **Framer Motion** - Animations
- **Recharts** - Charts and graphs
- **React Hook Form** - Form management

## Installation

### Prerequisites
- PHP 8.4+
- Composer 2.x
- Node.js 20+
- NPM 10+
- MySQL 8 or SQLite

### Quick Start

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd WIDAS
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure Database** (edit `.env`)

   For SQLite (default):
   ```
   DB_CONNECTION=sqlite
   DB_DATABASE=database/database.sqlite
   ```

   For MySQL:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=widas
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Create SQLite database** (if using SQLite)
   ```bash
   touch database/database.sqlite
   ```

7. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

8. **Build frontend assets**
   ```bash
   npm run build
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

   In a separate terminal:
   ```bash
   npm run dev
   ```

10. **Access the application**
    - URL: http://localhost:8000

## Default Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@widas.test | password |
| Security Analyst | analyst@widas.test | password |
| User | user@widas.test | password |

## Role-Based Access

### Admin
- Full system access
- User management (CRUD)
- Alert management and resolution
- Threat rule configuration
- System settings management
- Report generation
- Audit log review
- IP blocking management

### Security Analyst
- Threat monitoring dashboard
- Incident queue management
- Alert review and acknowledgment
- Incident investigation
- Report generation

### User
- Personal dashboard
- Security profile view
- Activity history
- Security notifications

## API Documentation

### Authentication
All API routes are protected with Laravel Sanctum.

### Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/alerts | List all alerts |
| GET | /api/alerts/unread-count | Get unread alert count |
| GET | /api/alerts/{id} | Get specific alert |
| POST | /api/alerts/{id}/mark-read | Mark alert as read |
| GET | /api/intrusions | List intrusion events |
| GET | /api/intrusions/stats | Get intrusion statistics |
| GET | /api/intrusions/{id} | Get specific intrusion |

## Security Features

### Brute Force Protection
- Tracks failed login attempts per IP
- Temporary account lockout after 5 failed attempts
- Rate limiting on login endpoint (5 attempts per minute)
- Automatic alert generation on threshold breach

### SQL Injection Detection
Patterns detected:
- UNION SELECT statements
- OR 1=1 / OR true conditions
- DROP TABLE statements
- INFORMATION_SCHEMA queries
- Comment injection (--, #)
- Time-based blind (WAITFOR DELAY, BENCHMARK)

### XSS Detection
Patterns detected:
- Script tags and event handlers
- javascript: URIs
- onerror, onclick, onload handlers
- iframe, embed, object injection
- alert(), prompt(), eval() calls
- document.cookie access attempts

### Session Security
- Session regeneration on login
- Session lifetime configuration
- Concurrent session monitoring

### IP Blocking
- Automatic blocking based on threat detection rules
- Manual blocking by administrators
- Permanent and temporary blocking options
- Automatic expiration of temporary blocks

## Database Schema

### Core Tables
- **users** - User accounts with role assignment
- **roles** - Role definitions (Admin, Analyst, User)
- **permissions** - Granular permissions
- **role_permission** - Role-permission pivot table

### Security Tables
- **intrusion_events** - Detected security threats
- **threat_rules** - Detection rule configurations
- **alerts** - Security alerts linked to events
- **security_logs** - Security event log
- **blocked_ips** - Blocked IP addresses

### Audit Tables
- **audit_logs** - Model change tracking
- **activity_logs** - User action history
- **login_attempts** - Authentication attempts

### Monitoring Tables
- **threat_scores** - IP/user threat scoring
- **system_settings** - Application configuration
- **notifications** - User notifications

## Testing

```bash
# Run all tests
php artisan test

# Run specific test suites
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

## Deployment

### Production Checklist

1. **Environment Configuration**
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   ```

2. **Database**
   - Use MySQL 8 for production
   - Configure proper connection pooling

3. **Queue Worker**
   ```bash
   php artisan queue:work --daemon
   ```

4. **Scheduler**
   ```bash
   * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
   ```

5. **Caching**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

6. **Optimization**
   ```bash
   php artisan optimize
   ```

7. **Web Server**
   - Configure Nginx/Apache to point to `public/` directory
   - Enable HTTPS with Let's Encrypt

## License

This project is developed for academic purposes as a final-year project in Cybersecurity.
