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

    // Processar candidatura a curso
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['action'] ?? '') === 'apply_course') {
        $courseId = $_POST['course_id'] ?? '';
        if (!$courseId) {
            setFlash('error', 'Selecione um curso para se candidatar.');
            header('Location: dashboard.php');
            exit;
        }
        // Verificar se já existe candidatura pendente para este curso
        $jaCandidatado = false;
        foreach ($enrollmentRequests as $req) {
            if ($req['course_id'] == $courseId && $req['status'] === 'pendente') {
                $jaCandidatado = true;
                break;
            }
        }
        if ($jaCandidatado) {
            setFlash('error', 'Já existe uma candidatura pendente para este curso.');
            header('Location: dashboard.php');
            exit;
        }
        // Criar candidatura
        $enrollmentRequestModel->create([
            'user_id' => $userId,
            'course_id' => $courseId,
            'student_record_id' => $studentRecord['id'] ?? null,
            'request_type' => 'candidatura',
            'notes_by_student' => null
        ]);
        setFlash('success', 'Candidatura submetida com sucesso!');
        header('Location: dashboard.php');
        exit;
    }

    include __DIR__ . '/../views/student/dashboard.php';
    
} elseif ($role === 'funcionario' || $role === 'professor') {
    // Staff/Professor Dashboard
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

    // Matrículas pendentes (completo)
    $sql = "
        SELECT er.*, u.full_name, u.email, u.username, sr.birth_date, c.name AS course_name
        FROM enrollment_requests er
        LEFT JOIN users u ON er.user_id = u.id
        LEFT JOIN student_records sr ON er.student_record_id = sr.id
        LEFT JOIN courses c ON er.course_id = c.id
        WHERE er.status = 'pendente'
        ORDER BY er.created_at ASC
    ";
    $matriculasPendentes = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    include __DIR__ . '/../views/manager/dashboard.php';
    
} else {
    die('Perfil desconhecido: ' . h($role));
}


