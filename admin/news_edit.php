<?php
require_once '../config.php';

if (!isAdmin()) {
    redirect('login.php');
}

$id = $_GET['id'] ?? 0;
$error = '';
$success = '';

// Get news item
$stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
$stmt->execute([$id]);
$news = $stmt->fetch();

if (!$news) {
    redirect('news_list.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = $_POST['category_id'];
    $image_path = $news['image_path'];

    // Handle new image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $file = $_FILES['image'];
        
        if ($file['size'] > MAX_UPLOAD_SIZE) {
            $error = 'File size exceeds 5MB limit';
        } elseif (!in_array($file['type'], ALLOWED_TYPES)) {
            $error = 'Invalid file type';
        } else {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $upload_path = '../' . UPLOAD_DIR . $filename;
            
            if (!is_dir('../' . UPLOAD_DIR)) {
                mkdir('../' . UPLOAD_DIR, 0777, true);
            }
            
            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                // Delete old image
                if ($image_path && file_exists('../' . $image_path)) {
                    unlink('../' . $image_path);
                }
                $image_path = UPLOAD_DIR . $filename;
            }
        }
    }

    if (!$error) {
        $stmt = $pdo->prepare("UPDATE news SET title = ?, content = ?, category_id = ?, image_path = ? 
                               WHERE id = ?");
        $stmt->execute([$title, $content, $category_id, $image_path, $id]);
        
        logActivity($pdo, $_SESSION['admin_id'], 'Edit News', "Edited: $title");
        $success = 'News updated successfully!';
        
        // Refresh data
        $news['title'] = $title;
        $news['content'] = $content;
        $news['category_id'] = $category_id;
        $news['image_path'] = $image_path;
    }
}

 $categories = $pdo->query("SELECT * FROM categories")->fetchAll();

$page_title = 'Edit News';
include '../includes/header.php';
?>

    <?php include 'nav.php'; ?>

    <div class="container my-4">
        <h2>Edit News Article</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <div class="card form-card">
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control" 
                               value="<?= htmlspecialchars($news['title']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Category *</label>
                        <select name="category_id" class="form-select" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" 
                                    <?= $cat['id'] == $news['category_id'] ? 'selected' : '' ?>>
                                    <?= $cat['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Content *</label>
                        <textarea name="content" class="form-control" rows="10" required><?= htmlspecialchars($news['content']) ?></textarea>
                    </div>

                    <?php if ($news['image_path']): ?>
                        <div class="mb-3">
                            <label class="form-label">Current Image</label>
                            <div>
                                <img src="../<?= $news['image_path'] ?>" style="max-width: 300px;" class="img-thumbnail">
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label">New Image (Optional)</label>
                        <input type="file" name="image" class="form-control" accept="image/*" data-preview-target="#preview">
                        <div class="mt-2">
                            <img id="preview" class="preview-img" style="display:none;" alt="Preview">
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update News</button>
                        <a href="news_list.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php include '../includes/footer.php'; ?>
