<?php
/**
 * Root Index - Entry Point for XAMPP
 * Redirects to public/login.php or public/dashboard.php
 */

session_start();

if (isset($_SESSION['user_id'])) {
    // Already logged in - redirect to dashboard
    header('Location: public/dashboard.php');
} else {
    // Not logged in - redirect to login
    header('Location: public/login.php');
}
exit;
