<! This is temporary would be filled in actual page infoe later,---Mark>
<?php
require_once 'config.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT n.*, c.name as category_name, a.username as author 
                       FROM news n 
                       LEFT JOIN categories c ON n.category_id = c.id
                       LEFT JOIN admins a ON n.author_id = a.id
                       WHERE n.id = ? AND n.is_published = 1");
$stmt->execute([$id]);
$news = $stmt->fetch();

if (!$news) {
    redirect('index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($news['title']) ?> - School News</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">🏫 School News Board</a>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <a href="index.php" class="btn btn-outline-primary mb-3">← Back to News</a>
                
                <article>
                    <div class="mb-3">
                        <span class="badge bg-info"><?= $news['category_name'] ?></span>
                        <small class="text-muted ms-2">
                            Posted on <?= date('F d, Y', strtotime($news['published_at'])) ?>
                        </small>
                    </div>

                    <h1 class="mb-4"><?= htmlspecialchars($news['title']) ?></h1>

                    <?php if ($news['image_path']): ?>
                        <img src="<?= $news['image_path'] ?>" class="img-fluid rounded mb-4" alt="News image">
                    <?php endif; ?>

                    <div class="lead mb-4">
                        <?= nl2br(htmlspecialchars($news['content'])) ?>
                    </div>

                    <hr>

                    <div class="text-muted">
                        <small>Author: <?= htmlspecialchars($news['author']) ?></small>
                    </div>
                </article>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
