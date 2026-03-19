<?php
/**
 * Application Diagnostic Test
 * Check for errors and configuration issues
 */

// Suppress errors temporarily to catch them
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    echo "ERROR: [$errno] $errstr in $errfile:$errline<br>";
    return true;
});

echo "<h2>Application Diagnostic Test</h2>";

// Test bootstrap
echo "<p><strong>Testing Bootstrap...</strong></p>";
try {
    require_once __DIR__ . '/../core/bootstrap.php';
    echo "✓ Bootstrap loaded successfully<br>";
} catch (Exception $e) {
    echo "✗ Bootstrap error: " . $e->getMessage() . "<br>";
}

// Test translation functions
echo "<p><strong>Testing Translation System...</strong></p>";

if (function_exists('t')) {
    echo "✓ t() function exists<br>";
    echo "  Sample: t('nav.dashboard') = '" . t('nav.dashboard') . "'<br>";
} else {
    echo "✗ t() function not found<br>";
}

if (function_exists('__')) {
    echo "✓ __() function exists<br>";
    echo "  Sample: __('nav.theme') = '" . __('nav.theme') . "'<br>";
} else {
    echo "✗ __() function not found<br>";
}

if (function_exists('getCurrentLanguage')) {
    echo "✓ getCurrentLanguage() function exists<br>";
    echo "  Current language: " . getCurrentLanguage() . "<br>";
} else {
    echo "✗ getCurrentLanguage() function not found<br>";
}

// Test helper functions
echo "<p><strong>Testing Helper Functions...</strong></p>";

if (function_exists('getStateLabel')) {
    echo "✓ getStateLabel() function exists<br>";
    echo "  Sample: getStateLabel('submetida') = '" . getStateLabel('submetida') . "'<br>";
    echo "  Sample: getStateLabel('rascunho') = '" . getStateLabel('rascunho') . "'<br>";
} else {
    echo "✗ getStateLabel() function not found<br>";
}

if (function_exists('getStateBadgeClass')) {
    echo "✓ getStateBadgeClass() function exists<br>";
    echo "  Sample: getStateBadgeClass('submetida') = '" . getStateBadgeClass('submetida') . "'<br>";
} else {
    echo "✗ getStateBadgeClass() function not found<br>";
}

// Test session
echo "<p><strong>Testing Session...</strong></p>";
if (isset($_SESSION)) {
    echo "✓ Session initialized<br>";
    echo "  Language: " . ($_SESSION['language'] ?? 'not set') . "<br>";
} else {
    echo "✗ Session not initialized<br>";
}

// Test theme CSS variables
echo "<p><strong>Testing CSS Theme Variables...</strong></p>";
echo "<div style='padding: 20px; background: var(--surface); color: var(--text); border: 1px solid var(--border); border-radius: 8px;'>";
echo "This section uses CSS theme variables (light/dark theme)";
echo "</div>";

echo "<p><strong>Testing Theme Toggle Button</strong></p>";
echo "<button type='button' class='theme-toggle js-theme-toggle' aria-label='Theme' aria-pressed='false' style='padding: 10px 20px; border: 1px solid #ddd; border-radius: 4px; background: #f5f5f5; cursor: pointer;'>Theme Toggle</button>";

echo "<h3>Diagnostic Complete!</h3>";
?>
