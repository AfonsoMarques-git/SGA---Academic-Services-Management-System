<?php
/**
 * Internationalization (i18n) - Translation Management
 */

$TRANSLATIONS = [];
$CURRENT_LANG = 'pt';

/**
 * Load translations for a specific language
 */
function loadTranslations($lang = 'pt') {
    global $TRANSLATIONS, $CURRENT_LANG;
    
    // Validate language code
    if (!preg_match('/^[a-z]{2}$/', $lang)) {
        $lang = 'pt';
    }
    
    $filePath = __DIR__ . '/../translations/' . $lang . '.php';
    if (file_exists($filePath)) {
        $TRANSLATIONS = require $filePath;
        $CURRENT_LANG = $lang;
        return true;
    }
    
    return false;
}

/**
 * Get a translation string
 * @param string $key The translation key (e.g., 'nav.logout')
 * @param string $default Default value if key not found
 * @return string The translated string
 */
function trans($key, $default = '') {
    global $TRANSLATIONS;
    return $TRANSLATIONS[$key] ?? ($default ?: $key);
}

/**
 * Alias for trans()
 */
function t($key, $default = '') {
    return trans($key, $default);
}

/**
 * Alias for trans() - same as __() from Laravel
 */
function __($key, $default = '') {
    return trans($key, $default);
}

/**
 * Get current language
 */
function getCurrentLanguage() {
    global $CURRENT_LANG;
    return $CURRENT_LANG;
}

/**
 * Set language in session
 */
function setLanguage($lang) {
    if (preg_match('/^[a-z]{2}$/', $lang)) {
        $_SESSION['language'] = $lang;
        loadTranslations($lang);
        return true;
    }
    return false;
}

/**
 * Initialize language from session or default
 */
function initializeLanguage() {
    $lang = $_SESSION['language'] ?? 'pt';
    loadTranslations($lang);
}
