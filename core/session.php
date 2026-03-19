<?php
/**
 * Session Management
 */

session_start();

// Check for session timeout
if (isAuthenticated()) {
    if (!isset($_SESSION['last_activity'])) {
        $_SESSION['last_activity'] = time();
    } else if (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT) {
        session_destroy();
        header('Location: login.php?error=sessao_expirada');
        exit;
    }
    $_SESSION['last_activity'] = time();
}

/**
 * Middleware: Check authentication
 */
function authMiddleware() {
    if (!isAuthenticated()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Middleware: Check role
 */
function roleMiddleware($allowedRoles) {
    authMiddleware();
    if (!in_array(getUserRole(), $allowedRoles)) {
        http_response_code(403);
        die('Acesso negado');
    }
}
