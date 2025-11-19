<?php
require_once 'config.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT n.*, c.name as category_name, a.username as author 
                       FROM news n 
                       LEFT JOIN categories c ON n.category_id = c.id
                       LEFT JOIN admins a ON n.author_id = a.id
                       WHERE n.id = ? AND n.is_published = TRUE");
$stmt->execute([$id]);
$news = $stmt->fetch();

if (!$news) {
    redirect('index.php');
}

// Get related news
$related = $pdo->prepare("SELECT n.*, c.name as category_name 
                          FROM news n 
                          LEFT JOIN categories c ON n.category_id = c.id 
                          WHERE n.category_id = ? AND n.id != ? AND n.is_published = TRUE 
                          ORDER BY n.published_at DESC LIMIT 3");
$related->execute([$news['category_id'], $id]);
$relatedNews = $related->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($news['title']) ?> - School News</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #ec4899;
            --accent: #14b8a6;
            --dark: #0f172a;
            --gray: #64748b;
            --light: #f8fafc;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            background-attachment: fixed;
            min-height: 100vh;
            padding-bottom: 4rem;
        }
        
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .btn-back {
            background: white;
            color: var(--primary);
            border: 2px solid white;
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .btn-back:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .article-container {
            background: white;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            margin: 2rem 0;
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .article-header {
            position: relative;
            height: 500px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            overflow: hidden;
        }
        
        .article-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.9;
        }
        
        .article-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 3rem;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
        }
        
        .article-badge {
            display: inline-block;
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            background: rgba(99, 102, 241, 0.9);
            color: white;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            margin-bottom: 1rem;
        }
        
        .article-title {
            font-size: 3rem;
            font-weight: 800;
            color: white;
            margin: 0;
            text-shadow: 0 2px 20px rgba(0, 0, 0, 0.3);
            line-height: 1.2;
        }
        
        .article-body {
            padding: 3rem;
        }
        
        .article-meta {
            display: flex;
            gap: 2rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 2rem;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--gray);
            font-size: 0.95rem;
        }
        
        .meta-icon {
            color: var(--primary);
            font-size: 1.1rem;
        }
        
        .article-content {
            font-size: 1.15rem;
            line-height: 1.8;
            color: var(--dark);
        }
        
        .article-content p {
            margin-bottom: 1.5rem;
        }
        
        .share-section {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 2px solid #e2e8f0;
        }
        
        .share-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--dark);
        }
        
        .share-buttons {
            display: flex;
            gap: 1rem;
        }
        
        .btn-share {
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-facebook {
            background: #1877f2;
            color: white;
        }
        
        .btn-twitter {
            background: #1da1f2;
            color: white;
        }
        
        .btn-whatsapp {
            background: #25d366;
            color: white;
        }
        
        .btn-share:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .related-section {
            margin-top: 3rem;
        }
        
        .section-title {
            font-size: 2rem;
            font-weight: 800;
            color: white;
            text-align: center;
            margin-bottom: 2rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
        
        .related-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .related-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .related-image {
            height: 200px;
            overflow: hidden;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }
        
        .related-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .related-card:hover .related-image img {
            transform: scale(1.1);
        }
        
        .related-body {
            padding: 1.5rem;
        }
        
        .related-category {
            display: inline-block;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 0.8rem;
        }
        
        .related-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .related-date {
            color: var(--gray);
            font-size: 0.85rem;
        }
        
        @media (max-width: 768px) {
            .article-title {
                font-size: 2rem;
            }
            
            .article-body {
                padding: 2rem 1.5rem;
            }
            
            .article-meta {
                flex-direction: column;
                gap: 1rem;
            }
            
            .share-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-custom">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <i class="fas fa-graduation-cap me-2" style="font-size: 1.8rem;"></i>
                School News Board
            </a>
            <a href="index.php" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Back to News
            </a>
        </div>
    </nav>

    <div class="container my-5">
        <!-- Article -->
        <article class="article-container">
            <!-- Header with Image -->
            <div class="article-header">
                <?php if ($news['image_path']): ?>
                    <img src="<?= $news['image_path'] ?>" class="article-image" alt="<?= htmlspecialchars($news['title']) ?>">
                <?php else: ?>
                    <div class="article-image d-flex align-items-center justify-content-center" style="font-size: 8rem; color: white;">
                        📰
                    </div>
                <?php endif; ?>
                <div class="article-overlay">
                    <span class="article-badge">
                        <i class="fas fa-tag me-2"></i><?= htmlspecialchars($news['category_name']) ?>
                    </span>
                    <h1 class="article-title"><?= htmlspecialchars($news['title']) ?></h1>
                </div>
            </div>

            <!-- Article Body -->
            <div class="article-body">
                <!-- Meta Information -->
                <div class="article-meta">
                    <div class="meta-item">
                        <i class="fas fa-calendar meta-icon"></i>
                        <span><?= date('F d, Y', strtotime($news['published_at'])) ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-clock meta-icon"></i>
                        <span><?= date('h:i A', strtotime($news['published_at'])) ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-user meta-icon"></i>
                        <span>By <?= htmlspecialchars($news['author']) ?></span>
                    </div>
                </div>

                <!-- Content -->
                <div class="article-content">
                    <?= nl2br(htmlspecialchars($news['content'])) ?>
                </div>

                <!-- Share Section -->
                <div class="share-section">
                    <h3 class="share-title">
                        <i class="fas fa-share-alt me-2"></i>Share this article
                    </h3>
                    <div class="share-buttons">
                        <button class="btn btn-share btn-facebook" onclick="shareOnFacebook()">
                            <i class="fab fa-facebook-f"></i>
                            Facebook
                        </button>
                        <button class="btn btn-share btn-twitter" onclick="shareOnTwitter()">
                            <i class="fab fa-twitter"></i>
                            Twitter
                        </button>
                        <button class="btn btn-share btn-whatsapp" onclick="shareOnWhatsApp()">
                            <i class="fab fa-whatsapp"></i>
                            WhatsApp
                        </button>
                    </div>
                </div>
            </div>
        </article>

        <!-- Related Articles -->
        <?php if (!empty($relatedNews)): ?>
        <div class="related-section">
            <h2 class="section-title">📚 Related Articles</h2>
            <div class="row g-4">
                <?php foreach ($relatedNews as $item): ?>
                    <div class="col-md-4">
                        <a href="news.php?id=<?= $item['id'] ?>" class="text-decoration-none">
                            <div class="related-card">
                                <div class="related-image">
                                    <?php if ($item['image_path']): ?>
                                        <img src="<?= $item['image_path'] ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                                    <?php else: ?>
                                        <div style="display: flex; align-items: center; justify-content: center; height: 100%; font-size: 3rem; color: white;">
                                            📰
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="related-body">
                                    <span class="related-category"><?= htmlspecialchars($item['category_name']) ?></span>
                                    <h3 class="related-title"><?= htmlspecialchars($item['title']) ?></h3>
                                    <p class="related-date">
                                        <i class="far fa-calendar me-1"></i>
                                        <?= date('M d, Y', strtotime($item['published_at'])) ?>
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        const pageUrl = encodeURIComponent(window.location.href);
        const pageTitle = encodeURIComponent(document.title);

        function shareOnFacebook() {
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${pageUrl}`, '_blank', 'width=600,height=400');
        }

        function shareOnTwitter() {
            window.open(`https://twitter.com/intent/tweet?url=${pageUrl}&text=${pageTitle}`, '_blank', 'width=600,height=400');
        }

        function shareOnWhatsApp() {
            window.open(`https://wa.me/?text=${pageTitle}%20${pageUrl}`, '_blank');
        }
    </script>
</body>
</html>