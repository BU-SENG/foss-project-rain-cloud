<?php
// Shared footer include
?>
</main>

<footer class="bg-light py-5 mt-auto border-top">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <strong>School News Board</strong>
                <div class="text-muted small">&copy; <?= date('Y') ?> — SENG 411 Rain Cloud FOSS Project</div>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="/" class="text-muted me-3">Home</a>
                <a href="/news.php" class="text-muted me-3">All News</a>
                <a href="/admin/login.php" class="text-muted">Admin</a>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap bundle + small app scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/admin.js"></script>
</body>
</html>