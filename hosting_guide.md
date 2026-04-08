# Hosting Guide for CoinNest (PHP/MySQL)

To host this website, follow these steps:

## 1. Database Setup
1. Log in to your hosting control panel (e.g., cPanel).
2. Create a new MySQL Database named `coinnest_db`.
3. Create a MySQL User and assign it to the database with all privileges.
4. Open **phpMyAdmin**, select your database, and import the `database.sql` file provided in the project root.

## 2. Configuration
1. Open `includes/db.php`.
2. Update the following variables with your actual database credentials:
   ```php
   $host = 'localhost';
   $db_name = 'coinnest_db';
   $username = 'YOUR_DB_USERNAME';
   $password = 'YOUR_DB_PASSWORD';
   ```

## 3. Upload Files
1. Upload all files from this directory to your web server (usually `public_html`).
2. Ensure the `includes/` and `api/` folders are uploaded correctly.

## 4. Admin Access
- **Admin Panel URL:** `yourdomain.com/admin.php`
- **Default Admin Email:** `admin@coinnest.com`
- **Default Admin Password:** `admin123`
- *Note: Log in as the admin user to manage platform settings, users, and KYC.*

## 5. Security Notes
- Change the admin password immediately after logging in.
- Ensure `includes/db.php` is protected and not accessible via browser directly (standard on most PHP hosts).
- Consider using an SSL certificate (HTTPS) for secure logins.
