<?php
require_once '../config.php';

if (!isAdmin()) {
    redirect('login.php');
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
    $stmt->execute([$id]);
    logActivity($pdo, $_SESSION['admin_id'], 'Delete News', "Deleted news ID: $id");
    redirect('news_list.php');
}

// Get all news
$news = $pdo->query("SELECT n.*, c.name as category_name 
                     FROM news n 
                     LEFT JOIN categories c ON n.category_id = c.id 
                     ORDER BY n.published_at DESC")->fetchAll();

$page_title = 'Manage News';
include '../includes/header.php';
?>

    <?php include 'nav.php'; ?>

    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Manage News</h2>
            <a href="news_create.php" class="btn btn-primary">+ Create New</a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Published</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($news as $item): ?>
                                <tr>
                                    <td><?= $item['id'] ?></td>
                                    <td><?= htmlspecialchars($item['title']) ?></td>
                                    <td><span class="badge bg-info"><?= $item['category_name'] ?></span></td>
                                    <td><?= date('M d, Y', strtotime($item['published_at'])) ?></td>
                                    <td>
                                        <a href="news_edit.php?id=<?= $item['id'] ?>" 
                                           class="btn btn-sm btn-warning">Edit</a>
                                        <a href="?delete=<?= $item['id'] ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Delete this news?')">Delete</a>
                                        <a href="../news.php?id=<?= $item['id'] ?>" 
                                           class="btn btn-sm btn-info" target="_blank">View</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php include '../includes/footer.php'; ?>