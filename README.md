# comment3  

A lightweight, PHP‑based comment filtering and moderation system. It provides separate interfaces for **administrators**, **moderators**, and **regular users**, allowing you to manage keywords, ban users, and keep discussions clean with minimal effort.

---

## Overview  

`comment3` is a self‑contained web application that:

* Stores comments in a MySQL database (`DB/commentfiltering.sql`).  
* Filters posts in real‑time using a configurable keyword list.  
* Offers a full admin dashboard for managing keywords, moderators, and users.  
* Provides a moderator panel for banning users and updating profiles.  
* Includes a simple front‑end for posting, editing, and viewing comments.

The project is organized into three main areas:

| Area | Entry point | Purpose |
|------|-------------|---------|
| **Admin** | `admin/admin_dashboard.php` | Manage keywords, moderators, and users. |
| **Moderator** | `moderator/moderator_dashboard.php` | Ban users, edit profiles, and review flagged comments. |
| **Public** | `index.php` | View and add comments (with filtering). |

---

## Features  

- **Keyword‑based filtering** – Add, edit, or delete prohibited words via the admin UI.  
- **User roles** – Distinct admin, moderator, and regular‑user permissions.  
- **Moderation tools** – Moderators can ban users, view flagged comments, and update their own profile.  
- **Dashboard analytics** – Quick overview of total comments, banned users, and active keywords.  
- **Responsive UI** – Simple CSS (`css/style.css`, `admin/css/style.css`, `moderator/css/style.css`).  
- **Secure authentication** – Session‑based login for all roles (`login.php`, `admin_login.php`).  
- **Exportable database schema** – `DB/commentfiltering.sql` contains the full table structure and sample data.  

---

## Tech Stack  

| Layer | Technology |
|-------|------------|
| Backend | PHP 7.4+ |
| Database | MySQL / MariaDB |
| Front‑end | HTML5, CSS3 (no external frameworks) |
| Server | Apache / Nginx (any server with PHP support) |
| Version control | Git (GitHub) |

---

## Installation  

1. **Clone the repository**  

   ```bash
   git clone https://github.com/yourusername/comment3.git
   cd comment3
   ```

2. **Create a MySQL database**  

   ```sql
   CREATE DATABASE comment3;
   ```

3. **Import the schema**  

   ```bash
   mysql -u YOUR_DB_USER -p comment3 < DB/commentfiltering.sql
   ```

4. **Configure the application**  

   - Copy `config.php.example` (if provided) to `config.php` and edit the following constants:

     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'comment3');
     define('DB_USER', 'YOUR_OWN_DB_USER');
     define('DB_PASS', 'YOUR_OWN_DB_PASSWORD');
     ```

   - Do the same for `admin/config.php` and `moderator/config.php` if they exist.

5. **Set file permissions** (optional, depending on your server setup)

   ```bash
   chmod -R 755 .
   ```

6. **Deploy**  

   - Place the project in your web root (e.g., `/var/www/html/comment3`).  
   -