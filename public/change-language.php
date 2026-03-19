<?php
/**
 * Language Switcher
 */

require_once __DIR__ . '/../core/bootstrap.php';

$lang = $_GET['lang'] ?? 'pt';

// Validate language
if (in_array($lang, ['pt', 'en'])) {
    setLanguage($lang);
}

// Redirect back to previous page, fallback based on auth state
$defaultRoute = isAuthenticated() ? url('dashboard.php') : url('login.php');
$referrer = $_SERVER['HTTP_REFERER'] ?? $defaultRoute;
header('Location: ' . $referrer);
exit;
