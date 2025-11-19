<?php
// Database configuration (Supabase PostgreSQL)
define('DB_HOST', 'aws-1-eu-west-1.pooler.supabase.com');
define('DB_PORT', '5432');
define('DB_USER', 'postgres.pwywceahdfurihzhjkdp');
define('DB_PASS', 'foss_news_board'); // Replace with your actual password
define('DB_NAME', 'postgres');

// Application settings
define('UPLOAD_DIR', 'uploads/');
define('MAX_UPLOAD_SIZE', 5242880); // 5MB
define('ALLOWED_TYPES', ['image/jpeg', 'image/png', 'image/gif']);

// Session configuration
session_start();

// Database connection (PostgreSQL with SSL for Supabase)
try {
    $pdo = new PDO(
        "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";sslmode=require",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Helper functions
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

function isAdmin() {
    return isset($_SESSION['admin_id']);
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function logActivity($pdo, $admin_id, $action, $details = '') {
    $stmt = $pdo->prepare("INSERT INTO activity_logs (admin_id, action, details) VALUES (?, ?, ?)");
    $stmt->execute([$admin_id, $action, $details]);
}
?>