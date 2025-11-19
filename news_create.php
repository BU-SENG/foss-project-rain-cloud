<?php
require_once '../config.php';

if (!isAdmin()) {
    redirect('login.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = $_POST['category_id'];
    $image_path = '';

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $file = $_FILES['image'];
        
        if ($file['size'] > MAX_UPLOAD_SIZE) {
            $error = 'File size exceeds 5MB limit';
        } elseif (!in_array($file['type'], ALLOWED_TYPES)) {
            $error = 'Invalid file type. Only JPG, PNG, GIF allowed';
        } else {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $upload_path = '../' . UPLOAD_DIR . $filename;
            
            if (!is_dir('../' . UPLOAD_DIR)) {
                mkdir('../' . UPLOAD_DIR, 0777, true);
            }
            
            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                $image_path = UPLOAD_DIR . $filename;
            } else {
                $error = 'Failed to upload file';
            }
        }
    }

    if (!$error) {
        $stmt = $pdo->prepare("INSERT INTO news (title, content, category_id, image_path, author_id) 
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $content, $category_id, $image_path, $_SESSION['admin_id']]);
        
        logActivity($pdo, $_SESSION['admin_id'], 'Create News', "Created: $title");
        $success = 'News created successfully!';
    }
}

// Get categories
 $categories = $pdo->query("SELECT * FROM categories")->fetchAll();

$page_title = 'Create News';
include '../includes/header.php';
?>

    <?php include 'nav.php'; ?>

    <div class="container my-4">
        <h2>Create News Article</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <?= $success ?>
                <a href="news_list.php">View all news</a>
            </div>
        <?php endif; ?>

        <div class="card form-card">
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Category *</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Content *</label>
                        <textarea name="content" class="form-control" rows="10" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Image (Max 5MB)</label>
                        <input type="file" name="image" class="form-control" accept="image/*" data-preview-target="#preview">
                        <small class="text-muted">Allowed: JPG, PNG, GIF</small>
                        <div class="mt-2">
                            <img id="preview" class="preview-img" style="display:none;" alt="Preview">
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Publish News</button>
                        <a href="news_list.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php include '../includes/footer.php'; ?>