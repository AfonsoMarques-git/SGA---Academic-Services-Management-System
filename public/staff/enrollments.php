<?php
/**
 * Enrollment Requests Management Page
 */

require_once __DIR__ . '/../../core/bootstrap.php';
require_once __DIR__ . '/../../controllers/EnrollmentRequestsController.php';

$controller = new EnrollmentRequestsController($pdo);
$action = $_GET['action'] ?? 'index';
$requestId = (int) ($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $requestId > 0) {
    if ($action === 'approve') {
        $controller->approve($requestId);
    }
    if ($action === 'reject') {
        $controller->reject($requestId);
    }
}

$result = $controller->index();
extract($result['data']);
$error = getFlash('error');
$success = getFlash('success');

include __DIR__ . '/../../views/' . $result['view'];
