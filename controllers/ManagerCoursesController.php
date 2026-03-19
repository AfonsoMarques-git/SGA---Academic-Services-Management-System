<?php
/**
 * Manager Courses Controller
 */

class ManagerCoursesController {
    protected $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * List all courses
     */
    public function index() {
        requireAnyRole('gestor', 'funcionario');
        
        require_once __DIR__ . '/../models/Curso.php';
        $cursoModel = new Curso($this->pdo);
        
        $courses = $cursoModel->getAll();
        
        return [
            'view' => '../views/manager/courses.php',
            'data' => ['courses' => $courses]
        ];
    }
    
    /**
     * Create new course
     */
    public function create() {
        requireRole('gestor');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $_POST['code'] ?? '';
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            
            if (empty($code) || empty($name)) {
                setFlash('error', 'Código e nome são obrigatórios');
            } else {
                try {
                    $stmt = $this->pdo->prepare("
                        INSERT INTO courses (code, name, description, is_active, created_at)
                        VALUES (?, ?, ?, 1, NOW())
                    ");
                    $stmt->execute([$code, $name, $description]);
                    
                    setFlash('success', 'Curso criado com sucesso');
                    header('Location: courses.php');
                    exit;
                } catch (Exception $e) {
                    setFlash('error', 'Erro ao criar curso: ' . $e->getMessage());
                }
            }
        }
        
        return [
            'view' => '../views/manager/courses-form.php',
            'data' => ['course' => null]
        ];
    }
    
    /**
     * Edit course
     */
    public function edit($id) {
        requireRole('gestor');
        
        require_once __DIR__ . '/../models/Curso.php';
        $cursoModel = new Curso($this->pdo);
        
        $course = $cursoModel->getById($id);
        
        if (!$course) {
            http_response_code(404);
            die('Curso não encontrado');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $_POST['code'] ?? '';
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            
            if (empty($code) || empty($name)) {
                setFlash('error', 'Código e nome são obrigatórios');
            } else {
                try {
                    $stmt = $this->pdo->prepare("
                        UPDATE courses SET code = ?, name = ?, description = ?, updated_at = NOW()
                        WHERE id = ?
                    ");
                    $stmt->execute([$code, $name, $description, $id]);
                    
                    setFlash('success', 'Curso atualizado com sucesso');
                    header('Location: courses.php');
                    exit;
                } catch (Exception $e) {
                    setFlash('error', 'Erro ao atualizar curso: ' . $e->getMessage());
                }
            }
        }
        
        return [
            'view' => '../views/manager/courses-form.php',
            'data' => ['course' => $course]
        ];
    }
    
    /**
     * Delete course
     */
    public function delete($id) {
        requireRole('gestor');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $stmt = $this->pdo->prepare("UPDATE courses SET is_active = 0 WHERE id = ?");
                $stmt->execute([$id]);
                
                setFlash('success', 'Curso eliminado com sucesso');
                header('Location: courses.php');
                exit;
            } catch (Exception $e) {
                setFlash('error', 'Erro ao eliminar curso: ' . $e->getMessage());
            }
        }
        
        header('Location: courses.php');
        exit;
    }
}
