<?php
/**
 * Student Record Page
 */

require_once __DIR__ . '/../../core/bootstrap.php';
require_once __DIR__ . '/../../controllers/StudentRecordController.php';

$controller = new StudentRecordController($pdo);

$result = $controller->viewRecord();

// Extract data for view
extract($result['data']);
$error = getFlash('error');
$success = getFlash('success');

// Include view (supports both "student/record.php" and "views/student/record.php")
$view = $result['view'] ?? 'student/record.php';
if (strpos($view, 'views/') !== 0) {
	$view = 'views/' . ltrim($view, '/');
}

include __DIR__ . '/../../' . $view;
