<?php
require_once 'config.php';

// Get filter parameters
$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

// Build query
$sql = "SELECT n.*, c.name as category_name, c.slug as category_slug 
        FROM news n 
        LEFT JOIN categories c ON n.category_id = c.id 
        WHERE n.is_published = 1";

$params = [];

if ($category) {
    $sql .= " AND c.slug = ?";
    $params[] = $category;
}

if ($search) {
    $sql .= " AND MATCH(n.title, n.content) AGAINST (? IN NATURAL LANGUAGE MODE)";
    $params[] = $search;
}

$sql .= " ORDER BY n.published_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$news = $stmt->fetchAll();

// Get categories for filter
$cats = $pdo->query("SELECT * FROM categories")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School News Board</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .news-card { transition: transform 0.2s; }
        .news-card:hover { transform: translateY(-5px); box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .badge-new { animation: pulse 2s infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
        .news-image { height: 200px; object-fit: cover; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">🏫 School News Board</a>
            <a href="admin/login.php" class="btn btn-light btn-sm">Admin Login</a>
        </div>
    </nav>

    <div class="container my-4">
        <div class="row mb-4">
            <div class="col-md-8">
                <form method="get" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" 
                           placeholder="Search news..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
            <div class="col-md-4">
                <select class="form-select" onchange="location.href='?category='+this.value">
                    <option value="">All Categories</option>
                    <?php foreach ($cats as $cat): ?>
                        <option value="<?= $cat['slug'] ?>" <?= $category == $cat['slug'] ? 'selected' : '' ?>>
                            <?= $cat['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="row">
            <?php if (empty($news)): ?>
                <div class="col-12 text-center py-5">
                    <h3>No news found</h3>
                </div>
            <?php endif; ?>

            <?php foreach ($news as $item): 
                $isNew = strtotime($item['published_at']) > strtotime('-24 hours');
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card news-card h-100">
                        <?php if ($item['image_path']): ?>
                            <img src="<?= $item['image_path'] ?>" class="card-img-top news-image" alt="News image">
                        <?php endif; ?>
                        <div class="card-body">
                            <div class="mb-2">
                                <span class="badge bg-info"><?= $item['category_name'] ?></span>
                                <?php if ($isNew): ?>
                                    <span class="badge bg-danger badge-new">NEW</span>
                                <?php endif; ?>
                            </div>
                            <h5 class="card-title"><?= htmlspecialchars($item['title']) ?></h5>
                            <p class="card-text"><?= substr(htmlspecialchars($item['content']), 0, 150) ?>...</p>
                            <small class="text-muted">
                                <?= date('M d, Y', strtotime($item['published_at'])) ?>
                            </small>
                        </div>
                        <div class="card-footer">
                            <a href="news.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
