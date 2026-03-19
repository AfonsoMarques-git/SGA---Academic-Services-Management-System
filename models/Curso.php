<?php
/**
 * Course Model - For professional database
 */
class Curso {
    protected $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Get all courses
     */
    public function getAll() {
        $stmt = $this->pdo->query("
            SELECT id, code, name FROM courses 
            WHERE is_active = 1
            ORDER BY name
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get course by ID
     */
    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM courses WHERE id = ? AND is_active = 1
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get course with units
     */
    public function getWithUnits($courseId) {
        // Get course
        $course = $this->getById($courseId);
        
        if (!$course) return null;
        
        // Get course units via study_plans
        $stmt = $this->pdo->prepare("
            SELECT DISTINCT cu.* FROM course_units cu
            INNER JOIN study_plans sp ON cu.id = sp.unit_id
            WHERE sp.course_id = ? AND cu.is_active = 1
            ORDER BY cu.name
        ");
        $stmt->execute([$courseId]);
        $course['units'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $course;
    }
    
    /**
     * Create course
     */
    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO courses (code, name, description, is_active)
            VALUES (:code, :name, :description, :is_active)
        ");
        
        return $stmt->execute([
            ':code' => $data['code'],
            ':name' => $data['name'],
            ':description' => $data['description'] ?? null,
            ':is_active' => $data['is_active'] ?? 1,
        ]);
    }
}
