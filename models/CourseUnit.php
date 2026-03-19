<?php
/**
 * CourseUnit Model
 */
class CourseUnit {
    protected $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Create course unit
     */
    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO course_units (code, name, ects, description, is_active)
            VALUES (:code, :name, :ects, :description, :is_active)
        ");
        
        return $stmt->execute([
            ':code' => $data['code'],
            ':name' => $data['name'],
            ':ects' => $data['ects'] ?? 6,
            ':description' => $data['description'] ?? null,
            ':is_active' => $data['is_active'] ?? true,
        ]);
    }
    
    /**
     * Get all active units
     */
    public function getAll() {
        $stmt = $this->pdo->prepare("
            SELECT * FROM course_units
            WHERE is_active = TRUE
            ORDER BY name
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get unit by ID
     */
    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM course_units WHERE id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Update unit
     */
    public function update($id, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE course_units
            SET name = :name, ects = :ects, description = :description
            WHERE id = :id
        ");
        
        return $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':ects' => $data['ects'],
            ':description' => $data['description'] ?? null,
        ]);
    }
}
