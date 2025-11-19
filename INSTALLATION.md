# Installation Guide

Complete step-by-step instructions for installing the School News Board System.

## 📋 Table of Contents

- [System Requirements](#system-requirements)
- [Local Development Setup](#local-development-setup)
- [Production Deployment](#production-deployment)
- [Cloud Deployment (InfinityFree)](#cloud-deployment-InfinityFree)
- [Troubleshooting](#troubleshooting)

---

## System Requirements

### Minimum Requirements
- **PHP:** 7.4 or higher
- **MySQL:** 5.7 or higher (or MariaDB 10.3+)
- **Web Server:** Apache 2.4+ or Nginx 1.18+
- **Memory:** 512 MB RAM
- **Disk Space:** 100 MB (excluding media uploads)

### Required PHP Extensions
- `pdo_mysql` - MySQL PDO driver
- `mysqli` - MySQL improved extension
- `gd` or `imagick` - Image processing
- `mbstring` - Multibyte string support
- `fileinfo` - File type detection

### Optional But Recommended
- `openssl` - For HTTPS
- `zip` - For backups
- `curl` - For API integrations

---

## Local Development Setup

### Step 1: Install Prerequisites

#### Windows (Using XAMPP/WAMP)
1. Download and install [XAMPP](https://www.apachefriends.org/) or [WAMP](https://www.wampserver.com/)
2. Start Apache and MySQL services
3. XAMPP/WAMP includes PHP, MySQL, and web server

#### Windows (Manual)
1. Install [MySQL](https://dev.mysql.com/downloads/installer/)
2. Install [PHP](https://windows.php.net/download/)
3. Enable MySQL extension in `php.ini`:
   ```ini
   extension=pdo_mysql
   extension=mysqli
   ```

#### Linux (Ubuntu/Debian)
```bash
# Install MySQL
sudo apt update
sudo apt install mysql-server

# Install PHP and extensions
sudo apt install php php-mysql php-cli php-gd php-mbstring php-xml

# Start MySQL
sudo systemctl start mysql
sudo systemctl enable mysql
```

#### macOS
```bash
# Install Homebrew if not installed
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Install MySQL
brew install mysql
brew services start mysql

# Install PHP
brew install php
```

### Step 2: Clone the Repository

```bash
# Clone the project
git clone https://github.com/your-team/school-news-board.git
cd school-news-board

# Or download ZIP and extract
```

### Step 3: Database Setup

#### Create Database
```bash
# Login to MySQL
mysql -u root -p

# Or on Windows with XAMPP
# Open phpMyAdmin at http://localhost/phpmyadmin
```

```sql
-- Create database
CREATE DATABASE if0_40453990_school_news;

-- Use database
USE if0_40453990_school_news;

-- Run the schema (or import via phpMyAdmin)
SOURCE database.sql;
```

#### Verify Installation
```sql
-- Check tables
SHOW TABLES;

-- Should show:
-- admins
-- categories
-- news
-- activity_logs

-- Check default admin
SELECT * FROM admins;
```

### Step 4: Configure Database Connection

Edit `config.php`:

**For Local MySQL:**
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'your_password');
define('DB_NAME', 'if0_40453990_school_news');
```

**For InfinityFree or Remote MySQL:**
```php
define('DB_HOST', 'sql109.infinityfree.com');
define('DB_USER', 'if0_40453990');
define('DB_PASS', 'your_password');
define('DB_NAME', 'if0_40453990_school_news');
```

### Step 5: Create Upload Directory

```bash
# Create uploads folder
mkdir uploads
chmod 755 uploads  # Linux/Mac

# On Windows, right-click > Properties > Security
# Give IUSR or IIS_IUSRS write permissions
```

### Step 6: Start the Server

#### Using PHP Built-in Server
```bash
# From project root
php -S localhost:8000

# Access at: http://localhost:8000
```

#### Using Apache/Nginx
```bash
# Copy to web root
sudo cp -r school-news-board /var/www/html/

# Access at: http://localhost/school-news-board
```

### Step 7: First Login

1. Go to `http://localhost:8000/admin/login.php`
2. Login with default credentials:
   - **Username:** admin
   - **Password:** admin123
3. **IMPORTANT:** Change password immediately after first login!

---

## Production Deployment

### Using Apache

1. **Install Apache & mod_rewrite**
   ```bash
   sudo apt install apache2
   sudo a2enmod rewrite
   ```

2. **Configure Virtual Host**
   ```apache
   <VirtualHost *:80>
       ServerName school-news.example.com
       DocumentRoot /var/www/school-news-board
       
       <Directory /var/www/school-news-board>
           Options -Indexes +FollowSymLinks
           AllowOverride All
           Require all granted
       </Directory>
       
       ErrorLog ${APACHE_LOG_DIR}/school-news-error.log
       CustomLog ${APACHE_LOG_DIR}/school-news-access.log combined
   </VirtualHost>
   ```

3. **Enable site and restart**
   ```bash
   sudo a2ensite school-news.conf
   sudo systemctl restart apache2
   ```

### Using Nginx

```nginx
server {
    listen 80;
    server_name school-news.example.com;
    root /var/www/school-news-board;
    
    index index.php;
    
    location / {
        try_files $uri $uri/ =404;
    }
    
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
    }
    
    location ~ /\.ht {
        deny all;
    }
}
```

### SSL Certificate (Let's Encrypt)

```bash
sudo apt install certbot python3-certbot-apache
sudo certbot --apache -d school-news.example.com
```

---

## Cloud Deployment (InfinityFree)

### Step 1: Create Account
1. Go to [InfinityFree](https://infinityfree.net)
2. Create free hosting account
3. Wait for activation

### Step 2: Create Database
1. Go to Control Panel → MySQL Databases
2. Create new database
3. Note down:
   - Database name
   - Username
   - Password
   - Hostname

### Step 3: Import Schema
1. Go to phpMyAdmin
2. Select your database
3. Click Import tab
4. Upload `database.sql`
5. Click Go

### Step 4: Upload Files
1. Use File Manager or FTP
2. Upload all PHP files to `htdocs` folder
3. Create `uploads` folder with write permissions

### Step 5: Update config.php
Use the credentials from Step 2

---

## Troubleshooting

### Database Connection Failed

**Problem:** `could not find driver`
```bash
# Check if pdo_mysql is installed
php -m | grep mysql

# If not, install it
sudo apt install php-mysql  # Linux
```

**Problem:** `Access denied for user`
- Check username and password in config.php
- Verify MySQL user has permissions
- Reset password if needed:
  ```sql
  ALTER USER 'root'@'localhost' IDENTIFIED BY 'newpassword';
  ```

### Upload Errors

**Problem:** Files not uploading
```bash
# Check directory permissions
ls -la uploads/

# Fix permissions
chmod 755 uploads/
```

**Problem:** File size limit
Edit `php.ini`:
```ini
upload_max_filesize = 10M
post_max_size = 10M
```

### Permission Denied

```bash
# Give web server ownership
sudo chown -R www-data:www-data /var/www/school-news-board

# Or for your user
sudo chown -R $USER:$USER .
```

### MySQL Authentication Failed

```bash
# Reset root password
sudo mysql

# Run these commands
ALTER USER 'root'@'localhost' IDENTIFIED BY 'newpassword';
FLUSH PRIVILEGES;
EXIT;
```

### Port Already in Use

```bash
# Find process using port 8000
lsof -ti:8000

# Kill it
kill -9 <PID>

# Or use different port
php -S localhost:8080
```

---

## Post-Installation

### Create Additional Admin Accounts
1. Go to `/admin/register.php`
2. Create new admin account
3. Or use SQL:
   ```sql
   INSERT INTO admins (username, password, email) 
   VALUES ('newadmin', '$2y$10$...', 'admin@example.com');
   ```

### Add Custom Categories
```sql
INSERT INTO categories (name, slug) VALUES ('Announcements', 'announcements');
```

### Backup Database
```bash
mysqldump -u root -p if0_40453990_school_news > backup.sql
```

---

## Need Help?

- Check [README.md](README.md) for overview
- Read [USER_GUIDE.md](USER_GUIDE.md) for usage
- Open an issue on GitHub
- Contact project maintainers

**Happy hosting! 🚀**