# API Documentation

Technical documentation for the School News Board System database schema, functions, and architecture.

## 📋 Table of Contents

- [Database Schema](#database-schema)
- [Core Functions](#core-functions)
- [File Structure](#file-structure)
- [Security Implementation](#security-implementation)
- [Configuration](#configuration)

---

## Database Schema

### MySQL Database: `if0_40453990_school_news`

#### Table: `admins`
Stores administrator account information.

```sql
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | INT | AUTO_INCREMENT, PRIMARY KEY | Auto-incrementing admin ID |
| `username` | VARCHAR(50) | UNIQUE, NOT NULL | Unique username for login |
| `password` | VARCHAR(255) | NOT NULL | Hashed password (bcrypt) |
| `email` | VARCHAR(100) | | Admin email address |
| `created_at` | TIMESTAMP | DEFAULT NOW | Account creation timestamp |

**Indexes:**
- Primary key on `id`
- Unique constraint on `username`

---

#### Table: `categories`
Stores news categories.

```sql
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    slug VARCHAR(50) UNIQUE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | INT | AUTO_INCREMENT, PRIMARY KEY | Category ID |
| `name` | VARCHAR(50) | NOT NULL | Display name |
| `slug` | VARCHAR(50) | UNIQUE, NOT NULL | URL-friendly identifier |

**Default Data:**
```sql
INSERT INTO categories (name, slug) VALUES 
('Academic', 'academic'),
('Sports', 'sports'),
('Events', 'events'),
('General', 'general');
```

---

#### Table: `news`
Stores news articles.

```sql
CREATE TABLE news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    category_id INT,
    image_path VARCHAR(255),
    author_id INT,
    published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_published TINYINT(1) DEFAULT 1,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (author_id) REFERENCES admins(id),
    INDEX idx_published (published_at),
    INDEX idx_category (category_id),
    FULLTEXT INDEX idx_search (title, content)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | INT | AUTO_INCREMENT, PRIMARY KEY | Article ID |
| `title` | VARCHAR(200) | NOT NULL | Article headline |
| `content` | TEXT | NOT NULL | Full article content |
| `category_id` | INT | FOREIGN KEY | Links to categories table |
| `image_path` | VARCHAR(255) | | Path to uploaded image |
| `author_id` | INT | FOREIGN KEY | Links to admins table |
| `published_at` | TIMESTAMP | DEFAULT NOW | Publication timestamp |
| `updated_at` | TIMESTAMP | AUTO UPDATE | Last update timestamp |
| `is_published` | TINYINT(1) | DEFAULT 1 | Publication status (1=true, 0=false) |

**Indexes:**
- Primary key on `id`
- Index on `published_at` for sorting
- Index on `category_id` for filtering
- FULLTEXT index on `title` and `content` for search

**Full-Text Search Index:**
```sql
-- Already included in table creation
FULLTEXT INDEX idx_search (title, content)
```

---

#### Table: `activity_logs`
Tracks all admin actions for auditing.

```sql
CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT,
    action VARCHAR(100) NOT NULL,
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES admins(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | INT | AUTO_INCREMENT, PRIMARY KEY | Log entry ID |
| `admin_id` | INT | FOREIGN KEY | Admin who performed action |
| `action` | VARCHAR(100) | NOT NULL | Action type (Login, Create News, etc) |
| `details` | TEXT | | Additional information |
| `created_at` | TIMESTAMP | DEFAULT NOW | When action occurred |

**Common Actions:**
- `Login` - Admin logged in
- `Logout` - Admin logged out
- `Create News` - New article created
- `Edit News` - Article modified
- `Delete News` - Article removed

---

## Core Functions

### `config.php`
Central configuration file with database connection and helper functions.

#### Database Connection
```php
$pdo = new PDO(
    "mysql:host=HOST;dbname=NAME;charset=utf8mb4",
    DB_USER,
    DB_PASS,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]
);
```

**PDO Options:**
- `ERRMODE_EXCEPTION` - Throw exceptions on errors
- `FETCH_ASSOC` - Return associative arrays
- `EMULATE_PREPARES = false` - Use real prepared statements

#### Helper Functions

**`sanitize($data)`**
```php
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}
```
- Removes HTML tags
- Trims whitespace
- Converts special characters to HTML entities
- **Use for:** Display output, not database storage

**`isAdmin()`**
```php
function isAdmin() {
    return isset($_SESSION['admin_id']);
}
```
- Checks if user is logged in as admin
- **Returns:** Boolean

**`redirect($url)`**
```php
function redirect($url) {
    header("Location: $url");
    exit();
}
```
- Redirects to specified URL
- Exits script execution
- **Use for:** Page redirections after actions

**`logActivity($pdo, $admin_id, $action, $details = '')`**
```php
function logActivity($pdo, $admin_id, $action, $details = '') {
    $stmt = $pdo->prepare("INSERT INTO activity_logs (admin_id, action, details) VALUES (?, ?, ?)");
    $stmt->execute([$admin_id, $action, $details]);
}
```
- Records admin actions to database
- **Parameters:**
  - `$pdo` - Database connection
  - `$admin_id` - Admin performing action
  - `$action` - Action type
  - `$details` - Optional additional info

---

## File Structure

```
school-news-board/
├── config.php                 # Configuration & database connection
├── index.php                  # Public homepage (news listing)
├── news.php                   # Individual news article view
├── database.sql               # Database schema
├── uploads/                   # User-uploaded images
├── admin/
│   ├── login.php             # Admin login page
│   ├── register.php          # Admin registration
│   ├── logout.php            # Logout handler
│   ├── dashboard.php         # Admin dashboard
│   ├── nav.php               # Admin navigation component
│   ├── news_list.php         # Manage all news
│   ├── news_create.php       # Create new article
│   └── news_edit.php         # Edit existing article
├── README.md                  # Project overview
├── CONTRIBUTING.md            # Contribution guidelines
├── CODE_OF_CONDUCT.md         # Community standards
├── INSTALLATION.md            # Setup instructions
├── USER_GUIDE.md              # User documentation
├── API_DOCUMENTATION.md       # This file
└── LICENSE                    # MIT License
```

---

## Security Implementation

### Password Security

**Hashing:**
```php
// Creating password hash
$hashed = password_hash($password, PASSWORD_DEFAULT);

// Verifying password
if (password_verify($password, $hashed)) {
    // Password correct
}
```
- Uses bcrypt algorithm
- Automatically salted
- Cost factor: 10 (default)

### SQL Injection Prevention

**Always use prepared statements:**
```php
// ✅ CORRECT - Using prepared statements
$stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
$stmt->execute([$id]);

// ❌ WRONG - String concatenation
$sql = "SELECT * FROM news WHERE id = " . $id;  // NEVER DO THIS!
```

### XSS (Cross-Site Scripting) Prevention

**When displaying user input:**
```php
// ✅ CORRECT - Escape output
echo htmlspecialchars($user_input, ENT_QUOTES, 'UTF-8');

// ❌ WRONG - Direct output
echo $user_input;  // NEVER DO THIS!
```

### File Upload Validation

```php
// Type validation
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($_FILES['image']['type'], $allowed_types)) {
    // Reject upload
}

// Size validation
$max_size = 5242880; // 5MB
if ($_FILES['image']['size'] > $max_size) {
    // Reject upload
}

// Unique filename
$filename = uniqid() . '.' . $extension;
```

### Session Security

```php
// Start session securely
session_start();

// Store minimal data
$_SESSION['admin_id'] = $admin_id;
$_SESSION['admin_name'] = $username;

// Destroy on logout
session_destroy();
```

---

## Configuration

### Application Settings

```php
// File uploads
define('UPLOAD_DIR', 'uploads/');
define('MAX_UPLOAD_SIZE', 5242880);  // 5MB in bytes
define('ALLOWED_TYPES', ['image/jpeg', 'image/png', 'image/gif']);
```

### Database Configuration

**Local Development:**
```php
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_USER', 'postgres');
define('DB_PASS', 'password');
define('DB_NAME', 'school_news');
```

**Production (Supabase):**
```php
define('DB_HOST', 'db.xxxxx.supabase.co');
define('DB_PORT', '5432');
define('DB_USER', 'postgres');
define('DB_PASS', 'secure_password');
define('DB_NAME', 'postgres');
```

### PHP Settings (php.ini)

```ini
; Upload limits
upload_max_filesize = 10M
post_max_size = 10M

; Session settings
session.cookie_httponly = 1
session.use_strict_mode = 1

; Error reporting (development)
error_reporting = E_ALL
display_errors = On

; Error reporting (production)
error_reporting = E_ALL
display_errors = Off
log_errors = On
```

---

## Query Examples

### Search News (Full-Text)
```php
$sql = "SELECT n.*, c.name as category_name 
        FROM news n 
        LEFT JOIN categories c ON n.category_id = c.id 
        WHERE MATCH(n.title, n.content) AGAINST (? IN NATURAL LANGUAGE MODE)
        ORDER BY n.published_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$search_query]);
```

### Get News by Category
```php
$sql = "SELECT n.*, c.name as category_name 
        FROM news n 
        LEFT JOIN categories c ON n.category_id = c.id 
        WHERE c.slug = ? AND n.is_published = 1
        ORDER BY n.published_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$category_slug]);
```

### Recent Activity Log
```php
$sql = "SELECT l.*, a.username 
        FROM activity_logs l 
        LEFT JOIN admins a ON l.admin_id = a.id 
        ORDER BY l.created_at DESC 
        LIMIT 10";
$logs = $pdo->query($sql)->fetchAll();
```

---

## Performance Considerations

### Database Indexes
- **Primary keys** - All tables (automatic with AUTO_INCREMENT)
- **Foreign keys** - For JOIN operations
- **published_at** - For date sorting
- **category_id** - For filtering
- **FULLTEXT** - For search functionality (title, content)

### Optimization Tips
1. Use `LIMIT` for pagination
2. Index frequently queried columns
3. Use prepared statements (cached)
4. Minimize JOIN operations
5. Compress uploaded images
6. Enable MySQL query cache
7. Use InnoDB engine for transactions

### Caching Strategies
```php
// Basic query result caching
$cache_file = 'cache/news_' . md5($sql) . '.json';
if (file_exists($cache_file) && time() - filemtime($cache_file) < 300) {
    $news = json_decode(file_get_contents($cache_file), true);
} else {
    $news = $pdo->query($sql)->fetchAll();
    file_put_contents($cache_file, json_encode($news));
}
```

---

## API Endpoints (Future Enhancement)

Consider adding REST API for mobile app integration:

### Proposed Endpoints
```
GET    /api/news              # List all news
GET    /api/news/{id}         # Get single article
GET    /api/news?category=X   # Filter by category
GET    /api/news?search=X     # Search articles
POST   /api/news              # Create article (admin)
PUT    /api/news/{id}         # Update article (admin)
DELETE /api/news/{id}         # Delete article (admin)
POST   /api/auth/login        # Admin login
POST   /api/auth/logout       # Admin logout
```

---

## Database Migrations

When modifying schema, follow this pattern:

```sql
-- migration_001_add_views_column.sql
ALTER TABLE news ADD COLUMN views INTEGER DEFAULT 0;
CREATE INDEX idx_news_views ON news(views);

-- migration_002_add_tags.sql
CREATE TABLE tags (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE news_tags (
    news_id INTEGER REFERENCES news(id) ON DELETE CASCADE,
    tag_id INTEGER REFERENCES tags(id) ON DELETE CASCADE,
    PRIMARY KEY (news_id, tag_id)
);
```

---

## Testing

### Database Queries
```sql
-- Test full-text search
SELECT * FROM news 
WHERE MATCH(title, content) AGAINST ('basketball' IN NATURAL LANGUAGE MODE);

-- Test category filtering
SELECT n.*, c.name 
FROM news n 
JOIN categories c ON n.category_id = c.id 
WHERE c.slug = 'sports';
```

### PHP Functions
```php
// Test password hashing
$hash = password_hash('test123', PASSWORD_DEFAULT);
var_dump(password_verify('test123', $hash));  // Should be true

// Test sanitization
$dirty = '<script>alert("xss")</script>';
$clean = sanitize($dirty);
echo $clean;  // Should output escaped HTML
```

---

## Further Reading

- [MySQL Documentation](https://dev.mysql.com/doc/)
- [PHP PDO Documentation](https://www.php.net/manual/en/book.pdo.php)
- [OWASP Security Guide](https://owasp.org/)
- [Bootstrap 5 Documentation](https://getbootstrap.com/docs/5.3/)
- [MySQL Full-Text Search](https://dev.mysql.com/doc/refman/8.0/en/fulltext-search.html)

---

**For questions or clarifications, please open an issue on GitHub!**