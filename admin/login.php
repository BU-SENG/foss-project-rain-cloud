<?php
require_once '../config.php';

if (isAdmin()) {
    redirect('dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['username'];
        logActivity($pdo, $admin['id'], 'Login', 'Admin logged in');
        redirect('dashboard.php');
    } else {
        $error = 'Invalid username or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - School News Board</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        /* Animated background */
        .bg-shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            animation: float 20s infinite ease-in-out;
        }
        
        .shape1 { width: 300px; height: 300px; top: 10%; left: 10%; background: white; animation-delay: 0s; }
        .shape2 { width: 200px; height: 200px; bottom: 20%; right: 15%; background: white; animation-delay: 2s; }
        .shape3 { width: 150px; height: 150px; top: 60%; left: 70%; background: white; animation-delay: 4s; }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
        }
        
        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 480px;
            padding: 1rem;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 3rem;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: slideUp 0.6s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        
        .lock-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #6366f1, #ec4899);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .login-title {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }
        
        .login-subtitle {
            color: #64748b;
            font-size: 1rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            padding: 0.9rem 1.2rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            outline: none;
        }
        
        .input-icon {
            position: absolute;
            right: 1.2rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1rem;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            border: none;
            border-radius: 15px;
            padding: 1rem;
            font-size: 1.1rem;
            font-weight: 700;
            color: white;
            width: 100%;
            margin-top: 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(99, 102, 241, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(-1px);
        }
        
        .alert-custom {
            background: rgba(239, 68, 68, 0.1);
            border: 2px solid #ef4444;
            border-radius: 15px;
            padding: 1rem 1.2rem;
            color: #dc2626;
            margin-bottom: 1.5rem;
            animation: shake 0.5s;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0;
            color: #94a3b8;
            font-size: 0.9rem;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .divider span {
            padding: 0 1rem;
        }
        
        .link-custom {
            color: #6366f1;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .link-custom:hover {
            color: #4f46e5;
            text-decoration: underline;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        
        .back-link:hover {
            color: #6366f1;
            transform: translateX(-5px);
        }
        
        .demo-info {
            background: rgba(99, 102, 241, 0.1);
            border: 2px dashed #6366f1;
            border-radius: 15px;
            padding: 1rem;
            margin-top: 1rem;
            text-align: center;
        }
        
        .demo-info small {
            color: #4f46e5;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="bg-shape shape1"></div>
    <div class="bg-shape shape2"></div>
    <div class="bg-shape shape3"></div>
    
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="lock-icon">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <h1 class="login-title">Welcome Back!</h1>
                <p class="login-subtitle">Sign in to manage school news</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert-custom">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-user me-2"></i>Username
                    </label>
                    <div style="position: relative;">
                        <input type="text" name="username" class="form-control" placeholder="Enter your username" required autofocus>
                        <i class="fas fa-user-circle input-icon"></i>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-lock me-2"></i>Password
                    </label>
                    <div style="position: relative;">
                        <input type="password" name="password" class="form-control" placeholder="Enter your password" required id="password">
                        <i class="fas fa-eye-slash input-icon" style="cursor: pointer;" onclick="togglePassword()"></i>
                    </div>
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                </button>
            </form>

            <div class="divider">
                <span>OR</span>
            </div>

            <div class="text-center">
                <p class="mb-2" style="color: #64748b;">Don't have an account?</p>
                <a href="register.php" class="link-custom">
                    <i class="fas fa-user-plus me-2"></i>Create Admin Account
                </a>
            </div>

            <div class="demo-info">
                <small>
                    <i class="fas fa-info-circle me-1"></i>
                    Demo: admin / admin123
                </small>
            </div>

            <div class="text-center mt-4">
                <a href="../index.php" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                    Back to News
                </a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = event.target;
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }
    </script>
</body>
</html>