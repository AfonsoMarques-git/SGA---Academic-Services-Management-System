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
        $cursoModel = new Curso($this->pdo);
        $courses = $cursoModel->getAll();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $_POST['code'] ?? '';
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $ects = $_POST['ects'] ?? 0;
            
            if (empty($code) || empty($name)) {
                setFlash('error', 'Código e nome são obrigatórios');
            } else {
                try {
                    $stmt = $this->pdo->prepare("
                        INSERT INTO course_units (code, name, description, ects, is_active, created_at)
                        VALUES (?, ?, ?, ?, 1, NOW())
                    ");
                    $stmt->execute([$code, $name, $description, $ects]);
                    
                    setFlash('success', 'UC criada com sucesso');
                    header('Location: units.php');
                    exit;
                } catch (Exception $e) {
                    setFlash('error', 'Erro ao criar UC: ' . $e->getMessage());
                }
            }
        }
        
        return [
            'view' => '../views/manager/units-form.php',
            'data' => ['unit' => null, 'courses' => $courses]
        ];
    }
    
    /**
     * Edit unit
     */
    public function edit($id) {
        requireRole('gestor');
        
        require_once __DIR__ . '/../models/CourseUnit.php';
        require_once __DIR__ . '/../models/Curso.php';
        
        $unitModel = new CourseUnit($this->pdo);
        $cursoModel = new Curso($this->pdo);
        
        $unit = $unitModel->getById($id);
        $courses = $cursoModel->getAll();
        
        if (!$unit) {
            http_response_code(404);
            die('UC não encontrada');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $_POST['code'] ?? '';
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $ects = $_POST['ects'] ?? 0;
            
            if (empty($code) || empty($name)) {
                setFlash('error', 'Código e nome são obrigatórios');
            } else {
                try {
                    $stmt = $this->pdo->prepare("
                        UPDATE course_units SET code = ?, name = ?, description = ?, ects = ?, updated_at = NOW()
                        WHERE id = ?
                    ");
                    $stmt->execute([$code, $name, $description, $ects, $id]);
                    
                    setFlash('success', 'UC atualizada com sucesso');
                    header('Location: units.php');
                    exit;
                } catch (Exception $e) {
                    setFlash('error', 'Erro ao atualizar UC: ' . $e->getMessage());
                }
            }
        }
        
        return [
            'view' => '../views/manager/units-form.php',
            'data' => ['unit' => $unit, 'courses' => $courses]
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
