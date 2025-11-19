<?php
// Shared header include
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) : 'School News Board' ?></title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Optional icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <link href="/assets/css/styles.css" rel="stylesheet">
</head>
<body>

<?php
// Fetch categories for the header dropdown when possible
if (isset($pdo)) {
    try {
        $headerCats = $pdo->query("SELECT slug, name FROM categories ORDER BY name")->fetchAll();
    } catch (Exception $e) {
        $headerCats = [];
    }
} else {
    $headerCats = [];
}
?>

<header class="site-header sticky-top bg-white shadow-sm">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <span class="fs-4 me-2">🏫</span>
                <span class="fw-bold">School News</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/news.php">All News</a></li>

                    <?php if (!empty($headerCats)): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="catDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Categories</a>
                            <ul class="dropdown-menu" aria-labelledby="catDropdown">
                                <li><a class="dropdown-item" href="/">All</a></li>
                                <?php foreach ($headerCats as $hc): ?>
                                    <li><a class="dropdown-item" href="/?category=<?= htmlspecialchars($hc['slug']) ?>"><?= htmlspecialchars($hc['name']) ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>

                <form class="d-flex me-3" role="search" method="get" action="/">
                    <input class="form-control form-control-sm me-2" name="search" type="search" placeholder="Search news" aria-label="Search">
                    <button class="btn btn-sm btn-primary" type="submit">Search</button>
                </form>

                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="/admin/login.php">Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<main class="container my-4">