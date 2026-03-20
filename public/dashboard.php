<?php
/**
 * Dashboard - Route by Role/Profile
 */

require_once __DIR__ . '/../core/bootstrap.php';
requireAuth();

$role = strtolower(getUserRole());
$userId = getUserId();

// Load models
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Aluno.php';
require_once __DIR__ . '/../models/Curso.php';
require_once __DIR__ . '/../models/EnrollmentRequest.php';

$userModel = new User($pdo);
$alunoModel = new Aluno($pdo);
$cursoModel = new Curso($pdo);
$enrollmentRequestModel = new EnrollmentRequest($pdo);

// Get data based on role
if ($role === 'aluno') {
    // Student Dashboard
    $studentRecord = $alunoModel->getByUserId($userId);
    $cursos = $cursoModel->getAll();
    $enrollmentRequests = $enrollmentRequestModel->getByStudentId($userId);
    
    include __DIR__ . '/../views/student/dashboard.php';
    
} elseif ($role === 'funcionario') {
    // Staff Dashboard
    $cursos = $cursoModel->getAll();
    $alunos = $alunoModel->getAll();
    
    include __DIR__ . '/../views/staff/dashboard.php';
    
} elseif ($role === 'gestor') {
    // Manager Dashboard
    $cursos = $cursoModel->getAll();
    $alunos = $alunoModel->getAll();

    $managerStats = [
        'totalUnits' => 0,
        'totalAcademicYears' => 0,
        'totalSemesters' => 0,
    ];

    // Load academic configuration indicators for manager widgets.
    try {
        $stmt = $pdo->query("SELECT COUNT(*) AS count FROM course_units WHERE is_active = 1");
        $managerStats['totalUnits'] = (int) ($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);

        $stmt = $pdo->query("SELECT COUNT(*) AS count FROM academic_years WHERE is_active = 1");
        $managerStats['totalAcademicYears'] = (int) ($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);

        $stmt = $pdo->query("SELECT COUNT(DISTINCT semester) AS count FROM study_plans WHERE is_active = 1");
        $managerStats['totalSemesters'] = (int) ($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);
    } catch (Exception $e) {
        // Keep default zero values if optional academic tables are unavailable.
    }
    
    // Matrículas pendentes (públicas)
    $matriculasPendentes = $pdo->query("SELECT * FROM enrollment_requests WHERE status = 'pendente' ORDER BY created_at ASC")->fetchAll(PDO::FETCH_ASSOC);
    include __DIR__ . '/../views/manager/dashboard.php';
    
} else {
    die('Perfil desconhecido: ' . h($role));
}


