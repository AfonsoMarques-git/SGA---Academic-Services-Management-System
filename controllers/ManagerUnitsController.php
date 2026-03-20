<?php
/**
 * Manager Units (CourseUnits) Controller
 */

class ManagerUnitsController {
    protected $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * List all course units
     */
    public function index() {
        requireAnyRole('gestor', 'funcionario');
        
        require_once __DIR__ . '/../models/CourseUnit.php';
        $unitModel = new CourseUnit($this->pdo);
        
        $units = $unitModel->getAll();
        
        return [
            'view' => '../views/manager/units.php',
            'data' => ['units' => $units]
        ];
    }
    
    /**
     * Create new unit
     */
    public function create() {
        requireRole('gestor');
        
        // Get available courses
        require_once __DIR__ . '/../models/Curso.php';
        require_once __DIR__ . '/../models/StudyPlan.php';
        require_once __DIR__ . '/../models/User.php';
        $cursoModel = new Curso($this->pdo);
        $courses = $cursoModel->getAll();
        $userModel = new User($this->pdo);
        $professors = $userModel->getByRole('professor');

        $selected_course_id = $_POST['course_id'] ?? '';
        $selected_year = $_POST['academic_year_number'] ?? '';
        $selected_semester = $_POST['semester'] ?? '';
        $selected_professor_id = $_POST['professor_id'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $_POST['code'] ?? '';
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $ects = $_POST['ects'] ?? 0;
            $hours = $_POST['hours'] ?? 0;
            $professor_id = $_POST['professor_id'] ?? null;

            if (empty($code) || empty($name) || empty($selected_course_id) || empty($selected_year) || empty($selected_semester) || empty($professor_id)) {
                setFlash('error', 'Todos os campos são obrigatórios');
            } else {
                try {
                    $stmt = $this->pdo->prepare("
                        INSERT INTO course_units (code, name, description, ects, hours, is_active, created_at, professor_id)
                        VALUES (?, ?, ?, ?, ?, 1, NOW(), ?)
                    ");
                    $stmt->execute([$code, $name, $description, $ects, $hours, $professor_id]);
                    $unit_id = $this->pdo->lastInsertId();

                    // Associa à tabela study_plans
                    $studyPlanModel = new StudyPlan($this->pdo);
                    $studyPlanModel->create([
                        'course_id' => $selected_course_id,
                        'unit_id' => $unit_id,
                        'academic_year_number' => $selected_year,
                        'semester' => $selected_semester,
                        'is_active' => 1
                    ]);

                    setFlash('success', 'UC criada e associada ao curso/ano/semestre/professor com sucesso');
                    header('Location: units.php');
                    exit;
                } catch (Exception $e) {
                    setFlash('error', 'Erro ao criar UC: ' . $e->getMessage());
                }
            }
        }

        return [
            'view' => '../views/manager/units-form.php',
            'data' => [
                'unit' => null,
                'courses' => $courses,
                'professors' => $professors,
                'selected_course_id' => $selected_course_id,
                'selected_year' => $selected_year,
                'selected_semester' => $selected_semester,
                'selected_professor_id' => $selected_professor_id
            ]
        ];
    }
    
    /**
     * Edit unit
     */
    public function edit($id) {
        requireRole('gestor');
        
        require_once __DIR__ . '/../models/CourseUnit.php';
        require_once __DIR__ . '/../models/Curso.php';
        require_once __DIR__ . '/../models/StudyPlan.php';
        require_once __DIR__ . '/../models/User.php';

        $unitModel = new CourseUnit($this->pdo);
        $cursoModel = new Curso($this->pdo);
        $studyPlanModel = new StudyPlan($this->pdo);
        $userModel = new User($this->pdo);

        $unit = $unitModel->getById($id);
        $courses = $cursoModel->getAll();
        $professors = $userModel->getByRole('professor');

        // Buscar associação existente (se houver)
        $studyPlan = null;
        if ($unit) {
            $plans = $this->pdo->prepare("SELECT * FROM study_plans WHERE unit_id = ? LIMIT 1");
            $plans->execute([$id]);
            $studyPlan = $plans->fetch();
        }

        $selected_course_id = $_POST['course_id'] ?? ($studyPlan['course_id'] ?? '');
        $selected_year = $_POST['academic_year_number'] ?? ($studyPlan['academic_year_number'] ?? '');
        $selected_semester = $_POST['semester'] ?? ($studyPlan['semester'] ?? '');
        $selected_professor_id = $_POST['professor_id'] ?? ($unit['professor_id'] ?? '');

        if (!$unit) {
            http_response_code(404);
            die('UC não encontrada');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $_POST['code'] ?? '';
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $ects = $_POST['ects'] ?? 0;
            $hours = $_POST['hours'] ?? 0;
            $professor_id = $_POST['professor_id'] ?? null;

            if (empty($code) || empty($name) || empty($selected_course_id) || empty($selected_year) || empty($selected_semester) || empty($professor_id)) {
                setFlash('error', 'Todos os campos são obrigatórios');
            } else {
                try {
                    $stmt = $this->pdo->prepare("
                        UPDATE course_units SET code = ?, name = ?, description = ?, ects = ?, hours = ?, professor_id = ?, updated_at = NOW()
                        WHERE id = ?
                    ");
                    $stmt->execute([$code, $name, $description, $ects, $hours, $professor_id, $id]);

                    // Atualiza ou cria associação na study_plans
                    if ($studyPlan) {
                        $updatePlan = $this->pdo->prepare("UPDATE study_plans SET course_id = ?, academic_year_number = ?, semester = ? WHERE id = ?");
                        $updatePlan->execute([$selected_course_id, $selected_year, $selected_semester, $studyPlan['id']]);
                    } else {
                        $studyPlanModel->create([
                            'course_id' => $selected_course_id,
                            'unit_id' => $id,
                            'academic_year_number' => $selected_year,
                            'semester' => $selected_semester,
                            'is_active' => 1
                        ]);
                    }

                    setFlash('success', 'UC e associação atualizadas com sucesso');
                    header('Location: units.php');
                    exit;
                } catch (Exception $e) {
                    setFlash('error', 'Erro ao atualizar UC: ' . $e->getMessage());
                }
            }
        }

        return [
            'view' => '../views/manager/units-form.php',
            'data' => [
                'unit' => $unit,
                'courses' => $courses,
                'professors' => $professors,
                'selected_course_id' => $selected_course_id,
                'selected_year' => $selected_year,
                'selected_semester' => $selected_semester,
                'selected_professor_id' => $selected_professor_id
            ]
        ];
    }
    
    /**
     * Delete unit
     */
    public function delete($id) {
        requireRole('gestor');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $stmt = $this->pdo->prepare("UPDATE course_units SET is_active = 0 WHERE id = ?");
                $stmt->execute([$id]);
                
                setFlash('success', 'UC eliminada com sucesso');
                header('Location: units.php');
                exit;
            } catch (Exception $e) {
                setFlash('error', 'Erro ao eliminar UC: ' . $e->getMessage());
            }
        }
        
        header('Location: units.php');
        exit;
    }
}
