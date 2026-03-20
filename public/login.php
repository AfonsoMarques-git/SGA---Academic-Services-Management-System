<?php
/**
 * Login Page - Secure Authentication
 */

require_once __DIR__ . '/../core/bootstrap.php';

// If already logged in, redirect to dashboard
if (isAuthenticated()) {
    header('Location: dashboard.php');
    exit;
}

$error = getFlash('login_error');
$success = getFlash('login_success');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = t('auth.username_password_required');
    } else {
        // Load User model
        require_once __DIR__ . '/../models/User.php';
        $userModel = new User($pdo);
        
        // Find user
        $user = $userModel->findByUsername($username);
        
        if ($user && $userModel->verifyPassword($user, $password)) {
            // Valid credentials
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['role'] = strtolower($user['role_name']);
            $_SESSION['full_name'] = $user['full_name'];
            
            // Update last login
            $userModel->updateLastLogin($user['id']);
            
            // Redirect to dashboard
            header('Location: dashboard.php');
            exit;
        } else {
            $error = t('auth.invalid_credentials');
        }
    }
}

?>
<!DOCTYPE html>
<html lang="<?php echo getCurrentLanguage() === 'en' ? 'en' : 'pt-PT'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Gestão Académica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo url('assets/css/app.css'); ?>" rel="stylesheet">
</head>
<body class="login-page">
    <main class="login-shell" role="main">
        <div class="row g-0">
            <aside class="col-lg-5 login-side">
                <h1 class="display-6 fw-bold mb-3">SGA</h1>
                <p class="mb-4"><?php echo t('auth.setup_title'); ?> <?php echo t('auth.setup_subtitle'); ?></p>
                <h6 class="text-uppercase fw-bold small mb-2"><?php echo t('auth.test_accounts'); ?></h6>
                <ul class="user-demo-list" aria-label="Test users">
                    <li><strong>joao_silva</strong> <span><?php echo t('auth.student'); ?></span></li>
                    <li><strong>maria_santos</strong> <span><?php echo t('auth.student'); ?></span></li>
                    <li><strong>carlos_funcionario</strong> <span><?php echo t('auth.staff'); ?></span></li>
                    <li><strong>ana_gestor</strong> <span><?php echo t('auth.manager'); ?></span></li>
                </ul>
                <p class="mb-0 mt-3"><small><?php echo t('auth.password_for_all'); ?> <strong>password123</strong></small></p>
            </aside>

            <section class="col-lg-7 login-main" aria-label="Authentication">
                <div class="login-header">
                    <h2 class="h3 mb-1"><?php echo t('auth.login_title'); ?></h2>
                    <div class="d-flex align-items-center gap-2">
                        <div class="dropdown">
                            <button class="btn btn-link nav-link dropdown-toggle p-0" id="loginLanguageDropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="<?php echo t('nav.language'); ?>">
                                <?php echo strtoupper(getCurrentLanguage()); ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="loginLanguageDropdown">
                                <li><a class="dropdown-item <?php echo getCurrentLanguage() === 'pt' ? 'active' : ''; ?>" href="<?php echo url('change-language.php?lang=pt'); ?>"><?php echo t('language.portuguese'); ?></a></li>
                                <li><a class="dropdown-item <?php echo getCurrentLanguage() === 'en' ? 'active' : ''; ?>" href="<?php echo url('change-language.php?lang=en'); ?>"><?php echo t('language.english'); ?></a></li>
                            </ul>
                        </div>
                    <button
                        type="button"
                        class="theme-toggle js-theme-toggle"
                        aria-label="<?php echo t('nav.toggle_theme') . ' ' . t('nav.dark'); ?>"
                        aria-pressed="false"
                        data-label-light="<?php echo t('nav.theme_light'); ?>"
                        data-label-dark="<?php echo t('nav.theme_dark'); ?>"
                        data-next-light="<?php echo t('nav.light'); ?>"
                        data-next-dark="<?php echo t('nav.dark'); ?>"
                        data-toggle-prefix="<?php echo t('nav.toggle_theme'); ?>"
                    ><?php echo t('nav.theme'); ?></button>
                    </div>
                </div>
                <p class="login-subtitle"><?php echo t('auth.login_subtitle'); ?></p>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo h($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?php echo t('auth.close_alert'); ?>"></button>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo h($success); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?php echo t('auth.close_alert'); ?>"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" novalidate>
                    <div class="mb-3">
                        <label for="username" class="form-label"><?php echo t('auth.username_label'); ?></label>
                        <input type="text" class="form-control" id="username" name="username"
                               required autofocus autocomplete="username" placeholder="<?php echo t('auth.username_label'); ?>">
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label"><?php echo t('auth.password_label'); ?></label>
                        <input type="password" class="form-control" id="password" name="password"
                               required autocomplete="current-password" placeholder="<?php echo t('auth.password_placeholder'); ?>">
                    </div>

                    <button type="submit" class="btn btn-primary w-100"><?php echo t('auth.login_button'); ?></button>
                    <a href="matricula.php" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="fas fa-user-plus"></i> Fazer Matrícula
                    </a>
                </form>
            </section>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo url('assets/js/app.js'); ?>"></script>
</body>
</html>
