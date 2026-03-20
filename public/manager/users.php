<?php
/**
 * Manager Users Page
 */

require_once __DIR__ . '/../../core/bootstrap.php';
require_once __DIR__ . '/../../controllers/ManagerUsersController.php';

$controller = new ManagerUsersController($pdo);

// Handle actions
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

$result = match($action) {
    'create' => $controller->create(),
    'edit' => $id ? $controller->edit($id) : $controller->index(),
    'deactivate' => $id ? $controller->deactivate($id) : $controller->index(),
    'activate' => $id ? $controller->activate($id) : $controller->index(),
    'delete' => $id ? $controller->delete($id) : $controller->index(),
    default => $controller->index()
};

// Extract data for view
extract($result['data']);
$error = getFlash('error');
$success = getFlash('success');

// Include view from project root
$view = ltrim($result['view'] ?? '', " ./\\/");
include __DIR__ . '/../../' . $view;
