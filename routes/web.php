<?php
/**
 * Application Routes
 */

$router = new Router('/public');

// ============================================
// STUDENT ROUTES
// ============================================
$router->match(['GET', 'POST'], '/student/record', function() {
    require_once __DIR__ . '/../controllers/StudentRecordController.php';
    $controller = new StudentRecordController($GLOBALS['pdo']);
    $result = $controller->viewRecord();
    extract($result['data']);
    include __DIR__ . '/../views/' . $result['view'];
});

// ============================================
// STAFF (FUNCIONARIO) ROUTES
// ============================================
$router->match(['GET', 'POST'], '/staff/grades', function() {
    require_once __DIR__ . '/../controllers/StaffGradesController.php';
    $controller = new StaffGradesController($GLOBALS['pdo']);
    $result = $controller->index();
    extract($result['data']);
    include __DIR__ . '/../views/' . $result['view'];
});

// ============================================
// MANAGER (GESTOR) ROUTES
// ============================================

// Courses management
$router->match(['GET', 'POST'], '/manager/courses', function() {
    require_once __DIR__ . '/../controllers/ManagerCoursesController.php';
    $controller = new ManagerCoursesController($GLOBALS['pdo']);
    
    $action = $_GET['action'] ?? 'index';
    $id = $_GET['id'] ?? null;
    
    $result = match($action) {
        'create' => $controller->create(),
        'edit' => $id ? $controller->edit($id) : $controller->index(),
        'delete' => $id ? $controller->delete($id) : $controller->index(),
        default => $controller->index()
    };
    
    extract($result['data']);
    $error = getFlash('error');
    $success = getFlash('success');
    include __DIR__ . '/../views/' . $result['view'];
});

// Units management
$router->match(['GET', 'POST'], '/manager/units', function() {
    require_once __DIR__ . '/../controllers/ManagerUnitsController.php';
    $controller = new ManagerUnitsController($GLOBALS['pdo']);
    
    $action = $_GET['action'] ?? 'index';
    $id = $_GET['id'] ?? null;
    
    $result = match($action) {
        'create' => $controller->create(),
        'edit' => $id ? $controller->edit($id) : $controller->index(),
        'delete' => $id ? $controller->delete($id) : $controller->index(),
        default => $controller->index()
    };
    
    extract($result['data']);
    $error = getFlash('error');
    $success = getFlash('success');
    include __DIR__ . '/../views/' . $result['view'];
});

// Users management
$router->match(['GET', 'POST'], '/manager/users', function() {
    require_once __DIR__ . '/../controllers/ManagerUsersController.php';
    $controller = new ManagerUsersController($GLOBALS['pdo']);
    
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
    
    extract($result['data']);
    $error = getFlash('error');
    $success = getFlash('success');
    include __DIR__ . '/../views/' . $result['view'];
});

// Reports
$router->match(['GET', 'POST'], '/manager/reports', function() {
    require_once __DIR__ . '/../controllers/ManagerReportsController.php';
    $controller = new ManagerReportsController($GLOBALS['pdo']);
    
    $action = $_GET['action'] ?? 'index';
    $type = $_GET['type'] ?? null;
    
    if ($action === 'export' && $type) {
        $result = $controller->export($type);
    } else {
        $result = $controller->index();
    }
    
    extract($result['data']);
    $error = getFlash('error');
    $success = getFlash('success');
    include __DIR__ . '/../views/' . $result['view'];
});

return $router;
