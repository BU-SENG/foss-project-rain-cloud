<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    
    body {
        font-family: 'Inter', sans-serif;
        background: #f8fafc;
    }
    
    .admin-navbar {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        padding: 0;
    }
    
    .navbar-brand {
        font-size: 1.3rem;
        font-weight: 800;
        color: white !important;
        padding: 1.2rem 0;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }
    
    .brand-icon {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #6366f1, #ec4899);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .nav-link {
        color: rgba(255, 255, 255, 0.8) !important;
        font-weight: 600;
        padding: 1.2rem 1.2rem !important;
        transition: all 0.3s ease;
        position: relative;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .nav-link:hover {
        color: white !important;
        background: rgba(255, 255, 255, 0.1);
    }
    
    .nav-link.active {
        color: white !important;
        background: rgba(99, 102, 241, 0.3);
    }
    
    .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(135deg, #6366f1, #ec4899);
    }
    
    .navbar-nav-right .nav-link {
        border-radius: 50px;
        margin-left: 0.5rem;
    }
    
    .btn-logout {
        background: rgba(239, 68, 68, 0.2);
        color: #fca5a5 !important;
        border-radius: 50px;
        padding: 0.6rem 1.5rem !important;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-logout:hover {
        background: #ef4444;
        color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(239, 68, 68, 0.3);
    }
    
    .btn-view-site {
        background: rgba(34, 197, 94, 0.2);
        color: #86efac !important;
    }
    
    .btn-view-site:hover {
        background: #22c55e;
        color: white !important;
    }
</style>

<nav class="navbar navbar-expand-lg admin-navbar">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="dashboard.php">
            <div class="brand-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <span>Admin Panel</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="background: rgba(255, 255, 255, 0.1); border: none;">
            <span class="navbar-toggler-icon" style="filter: brightness(0) invert(1);"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">
                        <i class="fas fa-home"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'news_list.php' ? 'active' : '' ?>" href="news_list.php">
                        <i class="fas fa-newspaper"></i>
                        Manage News
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'news_create.php' ? 'active' : '' ?>" href="news_create.php">
                        <i class="fas fa-plus-circle"></i>
                        Create News
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav navbar-nav-right">
                <li class="nav-item">
                    <a class="nav-link btn-view-site" href="../index.php" target="_blank">
                        <i class="fas fa-external-link-alt"></i>
                        View Site
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn-logout" href="logout.php">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>