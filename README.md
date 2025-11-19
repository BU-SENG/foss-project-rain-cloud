[![Review Assignment Due Date](https://classroom.github.com/assets/deadline-readme-button-22041afd0340ce965d47ae6ef1cefeee28c7c493a6346c4f15d667ab976d596c.svg)](https://classroom.github.com/a/tfYzIujW)

# School News Board System

A complete FOSS news management system for schools built with PHP and MySQL.

## Features ✨

- ✅ Admin dashboard with statistics
- ✅ Create, edit, delete news articles
- ✅ Image upload support (max 5MB)
- ✅ Category-based filtering
- ✅ Full-text search functionality
- ✅ Mobile responsive design
- ✅ Activity logging for admin actions
- ✅ Secure authentication with password hashing
- ✅ SQL injection & XSS protection
- ✅ "NEW" badge for recent posts (24 hours)

## Requirements 🔧

- PHP 7.4 or higher with PDO PostgreSQL extension
- PostgreSQL 12 or higher
- Apache/Nginx web server
- mod_rewrite enabled

## Installation 📦

### 1. Clone or Download
```bash
git clone <your-repo-url>
cd school-news-board
```

### 2. Database Setup
```bash
# Create database and import schema
psql -U postgres
CREATE DATABASE school_news;
\c school_news
\i database.sql
```

Or use pgAdmin:
- Create a database named `school_news`
- Run the SQL from `database.sql`

### 3. Configure Database
Edit `config.php` and update your PostgreSQL credentials:
```php
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_USER', 'postgres');
define('DB_PASS', 'your_password');
define('DB_NAME', 'school_news');
```

### 4. Set Permissions
```bash
# Create uploads directory
mkdir uploads
chmod 777 uploads
```

### 5. Access the Application
- **Public Site**: `http://localhost/school-news-board/`
- **Admin Panel**: `http://localhost/school-news-board/admin/login.php`

**Default Admin Credentials:**
- Username: `admin`
- Password: `admin123`

⚠️ **Change the default password immediately after first login!**

## Project Structure 📁

```
school-news-board/
├── config.php              # Database config & helper functions
├── index.php               # Public homepage (news listing)
├── news.php                # Individual news article view
├── database.sql            # Database schema
├── uploads/                # Image upload directory
├── admin/
│   ├── login.php          # Admin login
│   ├── logout.php         # Logout handler
│   ├── dashboard.php      # Admin dashboard
│   ├── nav.php            # Admin navigation
│   ├── news_list.php      # Manage all news
│   ├── news_create.php    # Create new news
│   └── news_edit.php      # Edit existing news
└── README.md              # This file
```

## Usage 📝

### For Administrators:

1. **Login**: Go to `/admin/login.php`
2. **Create News**: Click "Create News" from navigation
3. **Manage News**: View, edit, or delete from "Manage News"
4. **Monitor**: Check dashboard for statistics and activity logs

### For Students/Public:

1. **Browse News**: Visit homepage to see all news
2. **Filter by Category**: Use dropdown to filter by Academic, Sports, Events, etc.
3. **Search**: Use search bar to find specific news
4. **Read Full Article**: Click "Read More" on any news card

## Security Features 🔒

- Password hashing with `password_hash()`
- Prepared statements to prevent SQL injection
- Input sanitization for XSS prevention
- File upload validation (type & size)
- Admin authentication for all admin pages
- Activity logging for audit trail

## Customization 🎨

### Adding Categories:
```sql
INSERT INTO categories (name, slug) VALUES ('New Category', 'new-category');
```

### Changing Upload Limits:
Edit in `config.php`:
```php
define('MAX_UPLOAD_SIZE', 5242880); // 5MB in bytes
define('ALLOWED_TYPES', ['image/jpeg', 'image/png', 'image/gif']);
```

## Requirements Checklist ✅

| Requirement | Status |
|------------|--------|
| FR-001: Admin post news | ✅ |
| FR-002: Students view news | ✅ |
| FR-003: Categorized news | ✅ |
| FR-004: Image uploads | ✅ |
| FR-005: Search functionality | ✅ |
| FR-006: Admin login | ✅ |
| FR-007: Chronological order | ✅ |
| FR-008: Edit/delete posts | ✅ |
| FR-009: New notifications | ✅ |
| FR-010: Mobile responsive | ✅ |
| FR-011: Activity logs | ✅ |
| FR-012: Admin dashboard | ✅ |
| NFR-001: Security (SQL/XSS) | ✅ |
| NFR-002: 50 concurrent users | ✅ |
| NFR-003: 99% uptime | Depends on hosting |

## Troubleshooting 🔧

**Images not uploading:**
- Check `uploads/` directory exists and has write permissions
- Verify `upload_max_filesize` in `php.ini`

**Search not working:**
- Ensure GIN index exists on news table for full-text search
- PostgreSQL version must support ts_vector

**Login fails:**
- Verify database connection in `config.php`
- Check if admin user exists in database

## Contributing 🤝

This is a FOSS project. Contributions are welcome!

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Open a Pull Request

## License 📄

Open source - Free to use and modify for educational purposes.

## Support 💬

For issues and questions, please open an issue on the repository.

---

**Built with ❤️ by SENG 411 Rain Cloud for schools everywhere!**