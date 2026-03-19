<?php
/**
 * Staff Grades Page
 */

require_once __DIR__ . '/../../core/bootstrap.php';
require_once __DIR__ . '/../../controllers/StaffGradesController.php';

$controller = new StaffGradesController($pdo);

// Handle actions
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

if ($action === 'view' && $id) {
    $result = $controller->view($id);
} elseif ($action === 'evaluate' && $id) {
    $result = $controller->evaluateRecord($id);
} else {
    $result = $controller->index();
}

// Extract data for view
extract($result['data']);
$error = getFlash('error');
$success = getFlash('success');

// Include view
include __DIR__ . '/../../views/' . $result['view'];
