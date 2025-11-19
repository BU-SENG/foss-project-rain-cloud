<?php
require_once 'config.php';

// Get filter parameters
$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

// Build query
$sql = "SELECT n.*, c.name as category_name, c.slug as category_slug 
        FROM news n 
        LEFT JOIN categories c ON n.category_id = c.id 
        WHERE n.is_published = TRUE";

$params = [];

if ($category) {
    $sql .= " AND c.slug = ?";
    $params[] = $category;
}

if ($search) {
    $sql .= " AND to_tsvector('english', n.title || ' ' || n.content) @@ plainto_tsquery('english', ?)";
    $params[] = $search;
}

$sql .= " ORDER BY n.published_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$news = $stmt->fetchAll();

// Get categories for filter
$cats = $pdo->query("SELECT * FROM categories")->fetchAll();

// Get stats
$totalNews = $pdo->query("SELECT COUNT(*) FROM news WHERE is_published = TRUE")->fetchColumn();
$todayNews = $pdo->query("SELECT COUNT(*) FROM news WHERE is_published = TRUE AND DATE(published_at) = CURRENT_DATE")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School News Board - Stay Connected</title>
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
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --card-shadow-hover: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            background-attachment: fixed;
            color: var(--dark);
            overflow-x: hidden;
        }
        
        /* Animated background particles */
        .bg-particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            opacity: 0.3;
        }
        
        .particle {
            position: absolute;
            background: white;
            border-radius: 50%;
            animation: float 20s infinite ease-in-out;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-100px) rotate(180deg); }
        }
        
        /* Navbar */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            animation: slideDown 0.5s ease-out;
        }
        
        @keyframes slideDown {
            from { transform: translateY(-100%); }
            to { transform: translateY(0); }
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .nav-icon {
            font-size: 1.8rem;
            margin-right: 0.5rem;
        }
        
        /* Hero Section */
        .hero-section {
            position: relative;
            padding: 4rem 0 3rem;
            z-index: 1;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: white;
            text-shadow: 0 2px 20px rgba(0, 0, 0, 0.2);
            margin-bottom: 1rem;
            animation: fadeInUp 0.8s ease-out;
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 2rem;
            animation: fadeInUp 0.8s ease-out 0.2s backwards;
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
        
        /* Stats Cards */
        .stats-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            animation: fadeInUp 0.8s ease-out 0.4s backwards;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-shadow-hover);
        }
        
        .stats-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        /* Search Section */
        .search-section {
            position: relative;
            z-index: 1;
            margin: 2rem 0;
            animation: fadeInUp 0.8s ease-out 0.6s backwards;
        }
        
        .search-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .search-input {
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            padding: 1rem 1.5rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            outline: none;
        }
        
        .btn-search {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: 15px;
            padding: 1rem 2rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }
        
        .category-select {
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            padding: 1rem 1.5rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .category-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            outline: none;
        }
        
        /* Category Pills */
        .category-pills {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 1.5rem;
        }
        
        .category-pill {
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.2);
            color: rgba(1, 1, 1, 0.2);;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .category-pill:hover, .category-pill.active {
            background: white;
            color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        /* News Section */
        .news-section {
            position: relative;
            z-index: 1;
            padding: 2rem 0 4rem;
        }
        
        .news-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%;
            border: 1px solid rgba(0, 0, 0, 0.05);
            animation: fadeInUp 0.6s ease-out backwards;
        }
        
        .news-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: var(--card-shadow-hover);
        }
        
        .news-image-container {
            position: relative;
            height: 250px;
            overflow: hidden;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }
        
        .news-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .news-card:hover .news-image {
            transform: scale(1.1);
        }
        
        .news-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, transparent, rgba(0, 0, 0, 0.5));
        }
        
        .news-badges {
            position: absolute;
            top: 1rem;
            left: 1rem;
            display: flex;
            gap: 0.5rem;
            z-index: 2;
        }
        
        .badge-custom {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.8rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .badge-category {
            background: rgba(99, 102, 241, 0.9);
            color: white;
        }
        
        .badge-new {
            background: rgba(236, 72, 153, 0.9);
            color: white;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .news-body {
            padding: 1.5rem;
        }
        
        .news-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 1rem;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .news-excerpt {
            color: var(--gray);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .news-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }
        
        .news-date {
            color: var(--gray);
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-read-more {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-read-more:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(99, 102, 241, 0.3);
            color: white;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            animation: fadeInUp 0.8s ease-out;
        }
        
        .empty-icon {
            font-size: 5rem;
            color: var(--gray);
            margin-bottom: 1rem;
        }
        
        .empty-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        .empty-text {
            color: var(--gray);
            font-size: 1.1rem;
        }
        
        /* Admin Button */
        .btn-admin {
            background: white;
            color: var(--primary);
            border: 2px solid white;
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-admin:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .stats-card {
                margin-bottom: 1rem;
            }
            
            .category-pills {
                justify-content: center;
            }
        }
        
        /* Loading Animation */
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
        
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite;
        }
    </style>
</head>
<body>
    <!-- Background Particles -->
    <div class="bg-particles">
        <div class="particle" style="width: 10px; height: 10px; left: 10%; top: 20%; animation-delay: 0s;"></div>
        <div class="particle" style="width: 15px; height: 15px; left: 80%; top: 40%; animation-delay: 2s;"></div>
        <div class="particle" style="width: 8px; height: 8px; left: 50%; top: 60%; animation-delay: 4s;"></div>
        <div class="particle" style="width: 12px; height: 12px; left: 30%; top: 80%; animation-delay: 1s;"></div>
        <div class="particle" style="width: 6px; height: 6px; left: 70%; top: 10%; animation-delay: 3s;"></div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-custom">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <i class="fas fa-graduation-cap nav-icon"></i>
                School News Board
            </a>
            <a href="admin/login.php" class="btn btn-admin">
                <i class="fas fa-lock me-2"></i>Admin
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container text-center">
            <h1 class="hero-title">Stay Connected, Stay Informed</h1>
            <p class="hero-subtitle">Your hub for all school news, events, and announcements</p>
            
            <!-- Stats -->
            <div class="row g-4 mt-4">
                <div class="col-md-6">
                    <div class="stats-card">
                        <div class="stats-icon">📰</div>
                        <div class="stats-number"><?= $totalNews ?></div>
                        <div class="text-muted">Total Articles</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stats-card">
                        <div class="stats-icon">✨</div>
                        <div class="stats-number"><?= $todayNews ?></div>
                        <div class="text-muted">Posted Today</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="search-section">
        <div class="container">
            <div class="search-container">
                <form method="get" class="row g-3">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0" style="border-radius: 15px 0 0 15px; border: 2px solid #e2e8f0; border-right: none;">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" name="search" class="form-control search-input border-start-0 ps-0" 
                                   placeholder="Search for news, events, announcements..." 
                                   value="<?= htmlspecialchars($search) ?>"
                                   style="border-radius: 0 15px 15px 0;">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-search w-100">
                            <i class="fas fa-search me-2"></i>Search
                        </button>
                    </div>
                </form>
                
                <!-- Category Pills -->
                <div class="category-pills">
                    <a href="index.php" class="category-pill <?= !$category ? 'active' : '' ?>">
                        <i class="fas fa-globe me-2"></i>All News
                    </a>
                    <?php foreach ($cats as $cat): ?>
                        <a href="?category=<?= $cat['slug'] ?>" 
                           class="category-pill <?= $category == $cat['slug'] ? 'active' : '' ?>">
                            <?php 
                                $icons = [
                                    'academic' => 'fa-book',
                                    'sports' => 'fa-futbol',
                                    'events' => 'fa-calendar-alt',
                                    'general' => 'fa-info-circle'
                                ];
                                $icon = $icons[$cat['slug']] ?? 'fa-tag';
                            ?>
                            <i class="fas <?= $icon ?> me-2"></i><?= $cat['name'] ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- News Section -->
    <div class="news-section">
        <div class="container">
            <?php if (empty($news)): ?>
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <h3 class="empty-title">No News Found</h3>
                    <p class="empty-text">There are no articles matching your search. Try different keywords or browse all categories.</p>
                    <a href="index.php" class="btn btn-search mt-3">
                        <i class="fas fa-arrow-left me-2"></i>Back to All News
                    </a>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($news as $index => $item): 
                        $isNew = strtotime($item['published_at']) > strtotime('-24 hours');
                        $animationDelay = ($index % 6) * 0.1;
                    ?>
                        <div class="col-lg-4 col-md-6" style="animation-delay: <?= $animationDelay ?>s;">
                            <div class="news-card">
                                <div class="news-image-container">
                                    <?php if ($item['image_path']): ?>
                                        <img src="<?= $item['image_path'] ?>" class="news-image" alt="<?= htmlspecialchars($item['title']) ?>">
                                    <?php else: ?>
                                        <div class="news-image" style="display: flex; align-items: center; justify-content: center; font-size: 4rem; color: white;">
                                            📰
                                        </div>
                                    <?php endif; ?>
                                    <div class="news-overlay"></div>
                                    <div class="news-badges">
                                        <span class="badge-custom badge-category">
                                            <?= htmlspecialchars($item['category_name']) ?>
                                        </span>
                                        <?php if ($isNew): ?>
                                            <span class="badge-custom badge-new">
                                                <i class="fas fa-star me-1"></i>NEW
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="news-body">
                                    <h3 class="news-title"><?= htmlspecialchars($item['title']) ?></h3>
                                    <p class="news-excerpt"><?= htmlspecialchars(substr($item['content'], 0, 150)) ?>...</p>
                                    <div class="news-meta">
                                        <span class="news-date">
                                            <i class="far fa-calendar"></i>
                                            <?= date('M d, Y', strtotime($item['published_at'])) ?>
                                        </span>
                                        <a href="news.php?id=<?= $item['id'] ?>" class="btn-read-more">
                                            Read More
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
        
        // Add stagger animation to cards on load
        window.addEventListener('load', () => {
            const cards = document.querySelectorAll('.news-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                }, index * 100);
            });
        });
    </script>
</body>
</html>