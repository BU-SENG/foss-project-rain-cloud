<?php
require_once '../config.php';

if (!isAdmin()) {
    redirect('login.php');
}

// Get statistics
$total = $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn();
$today = $pdo->query("SELECT COUNT(*) FROM news WHERE DATE(published_at) = CURDATE()")->fetchColumn();
$categories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();

// Recent news
$recent = $pdo->query("SELECT n.*, c.name as category_name 
                       FROM news n 
                       LEFT JOIN categories c ON n.category_id = c.id 
                       ORDER BY n.published_at DESC LIMIT 5")->fetchAll();

// Recent activity
$logs = $pdo->prepare("SELECT l.*, a.username 
                       FROM activity_logs l 
                       LEFT JOIN admins a ON l.admin_id = a.id 
                       ORDER BY l.created_at DESC LIMIT 10");
$logs->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container my-4">
        <h2>Dashboard</h2>
        <p class="text-muted">Welcome back, <?= $_SESSION['admin_name'] ?>!</p>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5>Total News</h5>
                        <h2><?= $total ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5>Posted Today</h5>
                        <h2><?= $today ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5>Categories</h5>
                        <h2><?= $categories ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Recent News</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <?php foreach ($recent as $item): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <strong><?= htmlspecialchars($item['title']) ?></strong>
                                        <span class="badge bg-info"><?= $item['category_name'] ?></span>
                                    </div>
                                    <small class="text-muted">
                                        <?= date('M d, Y H:i', strtotime($item['published_at'])) ?>
                                    </small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Activity Log</h5>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        <div class="list-group">
                            <?php while ($log = $logs->fetch()): ?>
                                <div class="list-group-item">
                                    <strong><?= htmlspecialchars($log['username']) ?></strong>: 
                                    <?= htmlspecialchars($log['action']) ?>
                                    <br>
                                    <small class="text-muted">
                                        <?= date('M d, Y H:i', strtotime($log['created_at'])) ?>
                                    </small>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>