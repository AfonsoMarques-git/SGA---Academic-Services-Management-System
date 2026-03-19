<?php
/**
 * Manager Reports Page
 */

require_once __DIR__ . '/../../core/bootstrap.php';
require_once __DIR__ . '/../../controllers/ManagerReportsController.php';

$controller = new ManagerReportsController($pdo);

// Handle actions
$action = $_GET['action'] ?? 'index';
$type = $_GET['type'] ?? null;

if ($action === 'export' && $type) {
    $controller->export($type);
    exit;
} else {
    $result = $controller->index();
}

// Extract data for view
extract($result['data']);
$error = getFlash('error');
$success = getFlash('success');

// Include view from project root
$view = ltrim($result['view'] ?? '', " ./\\/");
include __DIR__ . '/../../' . $view;
