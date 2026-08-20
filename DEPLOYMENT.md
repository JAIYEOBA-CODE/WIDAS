# WIDAS Deployment Guide

## System Requirements

### Minimum Requirements
- PHP 8.4+
- Composer 2.x
- Node.js 20+
- MySQL 8.0+ or SQLite 3.x
- Web Server (Nginx/Apache)
- 2 GB RAM
- 10 GB Storage

### Recommended
- PHP 8.4+
- Composer 2.x
- Node.js 22+
- MySQL 8.0+
- Nginx
- Redis (for caching/queues)
- 4 GB RAM
- 20 GB Storage

## Production Deployment

### 1. Server Preparation

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP and extensions
sudo apt install php8.4 php8.4-cli php8.4-common php8.4-mysql \
    php8.4-zip php8.4-gd php8.4-mbstring php8.4-curl php8.4-xml \
    php8.4-bcmath php8.4-sqlite3 -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -
sudo apt install nodejs -y

# Install Nginx
sudo apt install nginx -y

# Install MySQL
sudo apt install mysql-server -y
```

### 2. Application Setup

```bash
# Clone repository
git clone <repository-url> /var/www/widas
cd /var/www/widas

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install --production
npm run build

# Environment setup
cp .env.example .env
php artisan key:generate

# Configure database in .env
# Set APP_ENV=production
# Set APP_DEBUG=false
```

### 3. Database Setup

```bash
# Create MySQL database
mysql -u root -p
CREATE DATABASE widas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'widas'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON widas.* TO 'widas'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Run migrations and seeders
php artisan migrate --seed
```

### 4. Nginx Configuration

Create `/etc/nginx/sites-available/widas`:

```nginx
server {
    listen 80;
    server_name widas.example.com;
    root /var/www/widas/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location /build/ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

Enable the site:
```bash
sudo ln -s /etc/nginx/sites-available/widas /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 5. SSL with Let's Encrypt

```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d widas.example.com
```

### 6. Queue Worker

Create a systemd service `/etc/systemd/system/widas-queue.service`:

```ini
[Unit]
Description=WIDAS Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
WorkingDirectory=/var/www/widas
ExecStart=/usr/bin/php artisan queue:work --sleep=3 --tries=3 --max-time=3600
Restart=always

[Install]
WantedBy=multi-user.target
```

```bash
sudo systemctl enable widas-queue
sudo systemctl start widas-queue
```

### 7. Scheduler Cron Job

```bash
* * * * * cd /var/www/widas && php artisan schedule:run >> /dev/null 2>&1
```

### 8. Optimization

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 9. Security Hardening

```bash
# Set proper permissions
sudo chown -R www-data:www-data /var/www/widas
sudo chmod -R 755 /var/www/widas/storage
sudo chmod -R 755 /var/www/widas/bootstrap/cache

# Restrict access to sensitive files
sudo chmod 640 /var/www/widas/.env
```

## Docker Deployment (Alternative)

### Dockerfile
```dockerfile
FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
```

### docker-compose.yml
```yaml
version: '3.8'

services:
  app:
    build: .
    container_name: widas-app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./:/var/www
    depends_on:
      - db
    networks:
      - widas

  web:
    image: nginx:alpine
    container_name: widas-web
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./:/var/www
      - ./docker/nginx:/etc/nginx/conf.d
    depends_on:
      - app
    networks:
      - widas

  db:
    image: mysql:8.0
    container_name: widas-db
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: widas
      MYSQL_ROOT_PASSWORD: root_password
      MYSQL_USER: widas
      MYSQL_PASSWORD: secure_password
    volumes:
      - dbdata:/var/lib/mysql
    networks:
      - widas

  queue:
    build: .
    container_name: widas-queue
    restart: unless-stopped
    command: php artisan queue:work --sleep=3 --tries=3
    volumes:
      - ./:/var/www
    depends_on:
      - db
    networks:
      - widas

networks:
  widas:
    driver: bridge

volumes:
  dbdata:
```

## Monitoring

### Log Files
- `storage/logs/laravel.log` - Application logs
- `storage/logs/security.log` - Security-specific logs

### Health Check
```
GET /up
```

Returns 200 OK when the application is healthy.

## Backup

### Database Backup
```bash
# MySQL
mysqldump -u widas -p widas > backup_$(date +%Y%m%d).sql

# SQLite
cp database/database.sqlite backup_$(date +%Y%m%d).sqlite
```

### Restore
```bash
# MySQL
mysql -u widas -p widas < backup.sql

# SQLite
cp backup.sqlite database/database.sqlite
```
