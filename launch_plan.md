# Launch Plan: CoinNest (coinnest.cloud)

To launch **CoinNest** online using your domain `coinnest.cloud`, we need to address a key technical requirement: **GitHub Pages only supports static sites (HTML/CSS/JS).** 

Because CoinNest uses **PHP** and a **MySQL Database**, you need a hosting provider that supports these technologies.

---

## 🏗️ 1. Choose a PHP/MySQL Hosting Provider
Here are your best options for your specific tech stack:

| Provider | Type | Good For... | GitHub Support? |
| :--- | :--- | :--- | :--- |
| **Render.com** | Cloud | Free tier available, high performance. | **Yes** (Auto-deploys from GitHub) |
| **InfinityFree.com** | Free | 100% Free, supports MySQL. | Limited (Manual upload mostly) |
| **Hostinger / Namecheap** | Paid | Recommended for production. Includes Email & SSL. | **Yes** (Easy Setup) |

---

## 🚀 2. Step-by-Step Deployment Guide

### Step A: Push to GitHub (For Version Control)
Since you want it on GitHub, you should create a **Private Repository** and upload your files.
1. Create a new repo on [GitHub](https://github.com/new).
2. Upload the files (excluding `coinnest_backup.zip`).
3. *Note: If you have Git installed, I can help you push via command line. Otherwise, use the "Upload Files" button on GitHub.*

### Step B: Upload Files to Host
I have already created a backup of your current project for you:
- **Backup File:** `coinnest_backup.zip`
- **Location:** `C:\xampp\htdocs\New folder\coinnest_backup.zip`

1. Log in to your hosting control panel (cPanel, Render Dashboard, etc.).
2. Use the **File Manager** to upload and extract `coinnest_backup.zip` into the `public_html` (or equivalent) directory.

### Step C: Set Up the Database
Your site needs its database to function.
1. In your hosting panel, look for **MySQL Databases**.
2. Create a new database named `coinnest_db`.
3. Create a user, assign a password, and add the user to the database.
4. Open **phpMyAdmin**, select your new database, and click **Import**.
5. Choose the `database.sql` file from your project.

### Step D: Update Database connection
Open `includes/db.php` on your server and update it with the credentials from Step C:
```php
$host = 'localhost'; // Usually localhost
$db_name = 'your_database_name';
$username = 'your_database_user';
$password = 'your_database_password';
```

---

## 🌐 3. Connect coinnest.cloud
Once your files are hosted, you need to tell your domain where to look.

1. **Find your Host's Nameservers:** (e.g., `ns1.hostinger.com`, `ns2.hostinger.com`).
2. **Update Domain DNS:** Go to where you bought `coinnest.cloud` (e.g., Namecheap, Cloudflare, GoDaddy).
3. Change the **Nameservers** to the ones provided by your host.
4. Wait 1-24 hours for "Propagation" (the time it takes for the internet to update your address).

---

## 🛠️ Next Steps
1. **Do you have a hosting account already?** (If so, tell me which one and I'll give specific steps).
2. **Do you want help pushing to GitHub?** I can guide you through the manual upload if you don't have Git installed.
