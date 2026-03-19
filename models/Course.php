<?php
/**
 * Course Model
 */
class Course {
    protected $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
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
            ':is_active' => $data['is_active'] ?? true,
        ]);
    }
    
    /**
     * Get all active courses
     */
    public function getAll() {
        $stmt = $this->pdo->prepare("
            SELECT * FROM courses
            WHERE is_active = TRUE
            ORDER BY name
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get course by ID
     */
    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM courses WHERE id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Get course by code
     */
    public function getByCode($code) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM courses WHERE code = :code
        ");
        $stmt->execute([':code' => $code]);
        return $stmt->fetch();
    }
    
    /**
     * Update course
     */
    public function update($id, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE courses
            SET name = :name, description = :description
            WHERE id = :id
        ");
        
        return $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':description' => $data['description'] ?? null,
        ]);
    }
    
    /**
     * Get course with units
     */
    public function getWithUnits($courseId) {
        $course = $this->getById($courseId);
        if (!$course) return null;
        
        $stmt = $this->pdo->prepare("
            SELECT cu.*, sp.academic_year_number, sp.semester
            FROM study_plans sp
            JOIN course_units cu ON sp.unit_id = cu.id
            WHERE sp.course_id = :course_id
            ORDER BY sp.academic_year_number, sp.semester
        ");
        $stmt->execute([':course_id' => $courseId]);
        $course['units'] = $stmt->fetchAll();
        
        return $course;
    }
}
