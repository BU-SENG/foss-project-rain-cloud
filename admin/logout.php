<?php
require_once '../config.php';

if (isAdmin()) {
    logActivity($pdo, $_SESSION['admin_id'], 'Logout', 'Admin logged out');
}

session_destroy();
redirect('login.php');
?>
