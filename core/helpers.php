<?php
/**
 * Helper Functions
 */

/**
 * Escape HTML output
 */
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * Check if user is authenticated
 */
function isAuthenticated() {
    return isset($_SESSION['user_id']) && isset($_SESSION['role']);
}

/**
 * Get current user role
 */
function getUserRole() {
    return $_SESSION['role'] ?? null;
}

/**
 * Normalize role identifiers from DB/session to translation keys
 */
function normalizeRoleKey($role) {
    $role = trim(strtolower((string) $role));

    $roleMap = [
        'aluno' => 'aluno',
        'student' => 'aluno',
        'funcionario' => 'funcionario',
        'funcionário' => 'funcionario',
        'staff' => 'funcionario',
        'gestor' => 'gestor',
        'manager' => 'gestor',
        'gestor pedagogico' => 'gestor',
        'gestor pedagógico' => 'gestor',
    ];

    return $roleMap[$role] ?? $role;
}

/**
 * Get translated role label for UI
 */
function getRoleLabel($role = null) {
    $sourceRole = $role ?? getUserRole();
    $normalizedRole = normalizeRoleKey($sourceRole);
    return t('role.' . $normalizedRole, (string) $sourceRole);
}

/**
 * Get current user ID
 */
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Check if user has a specific role
 */
function hasRole($role) {
    return isAuthenticated() && getUserRole() === $role;
}

/**
 * Check if user has one of multiple roles
 */
function hasAnyRole(...$roles) {
    $userRole = getUserRole();
    return in_array($userRole, $roles);
}

/**
 * Redirect to login if not authenticated
 */
function requireAuth() {
    if (!isAuthenticated()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Require specific role
 */
function requireRole($role) {
    requireAuth();
    if (getUserRole() !== $role) {
        http_response_code(403);
        die('Acesso negado');
    }
}

/**
 * Require one of multiple roles
 */
function requireAnyRole(...$roles) {
    requireAuth();
    if (!hasAnyRole(...$roles)) {
        http_response_code(403);
        die('Acesso negado');
    }
}

/**
 * Format date for display
 */
function formatDate($date) {
    if (!$date) return '';
    $timestamp = strtotime($date);
    return date('d/m/Y', $timestamp);
}

/**
 * Format datetime for display
 */
function formatDateTime($datetime) {
    if (!$datetime) return '';
    $timestamp = strtotime($datetime);
    return date('d/m/Y H:i', $timestamp);
}

/**
 * Convert state to badge class
 */
function getStateBadgeClass($state) {
    $badges = [
        'rascunho' => 'secondary',
        'submetida' => 'info',
        'aprovada' => 'success',
        'rejeitada' => 'danger',
        'pendente' => 'warning',
        'aprovado' => 'success',
        'rejeitado' => 'danger',
        'em_preparacao' => 'secondary',
        'publicada' => 'success',
        'fechada' => 'dark',
    ];
    return $badges[$state] ?? 'secondary';
}

/**
 * Get state label in current language using translations
 */
function getStateLabel($state) {
    $translationKey = 'state.' . $state;
    return t($translationKey, $state);
}

/**
 * Generate success alert HTML
 */
function alertSuccess($message) {
    return '<div class="alert alert-success alert-dismissible fade show" role="alert">
        ' . h($message) . '
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>';
}

/**
 * Generate error alert HTML
 */
function alertError($message) {
    return '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        ' . h($message) . '
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>';
}

/**
 * Generate warning alert HTML
 */
function alertWarning($message) {
    return '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        ' . h($message) . '
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>';
}

/**
 * Get flash message from session
 */
function getFlash($key) {
    $value = $_SESSION[$key] ?? null;
    if ($value) {
        unset($_SESSION[$key]);
    }
    return $value;
}

/**
 * Set flash message in session
 */
function setFlash($key, $value) {
    $_SESSION[$key] = $value;
}

/**
 * Get base URL for the application (works locally on XAMPP)
 */
function baseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];

    // Resolve project base path robustly for both /public/*.php and /public/*/*.php.
    $scriptPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? ($_SERVER['PHP_SELF'] ?? ''));
    $publicPos = strpos($scriptPath, '/public');

    if ($publicPos !== false) {
        $basePath = substr($scriptPath, 0, $publicPos);
    } else {
        $basePath = dirname($scriptPath);
    }

    if ($basePath === '/' || $basePath === '\\' || $basePath === '.') {
        $basePath = '';
    }

    return $protocol . '://' . $host . rtrim($basePath, '/');
}

/**
 * Get URL for public assets
 */
function publicUrl($path = '') {
    $base = baseUrl() . '/public';
    if ($path) {
        $base .= '/' . ltrim($path, '/');
    }
    return $base;
}

/**
 * Generate URL path (relative to public folder)
 */
function url($path = '') {
    return publicUrl($path);
}
