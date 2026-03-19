<?php
/**
 * Bootstrap - Initialize application
 */

// Load configuration
require_once __DIR__ . '/../config/app.php';

// Load database
$pdo = require_once __DIR__ . '/../config/database.php';

// Load helpers
require_once __DIR__ . '/helpers.php';

// Load session
require_once __DIR__ . '/session.php';

// Load internationalization
require_once __DIR__ . '/i18n.php';
initializeLanguage();
