<?php
/**
 * Student Records Model - For professional database
 */
class Aluno {
    protected $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Get student record by user ID
     */
    public function getByUserId($userId) {
        $stmt = $this->pdo->prepare("
            SELECT sr.*, c.name as course_name, u.full_name
            FROM student_records sr
            LEFT JOIN courses c ON sr.course_id = c.id
            LEFT JOIN users u ON sr.user_id = u.id
            WHERE sr.user_id = ?
            ORDER BY sr.created_at DESC
            LIMIT 1
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get all student records
     */
    public function getAll() {
        $stmt = $this->pdo->query("
            SELECT sr.*, c.name as course_name, u.full_name, u.email, u.username
            FROM student_records sr
            LEFT JOIN courses c ON sr.course_id = c.id
            LEFT JOIN users u ON sr.user_id = u.id
            ORDER BY u.full_name
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get by course
     */
    public function getByCourse($courseId) {
        $stmt = $this->pdo->prepare("
            SELECT sr.*, c.name as course_name, u.full_name
            FROM student_records sr
            LEFT JOIN courses c ON sr.course_id = c.id
            LEFT JOIN users u ON sr.user_id = u.id
            WHERE sr.course_id = ?
            ORDER BY u.full_name
        ");
        $stmt->execute([$courseId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Create student record
     */
    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO student_records 
            (user_id, course_id, full_name, birth_date, national_id, tax_number, 
             phone, email_contact, address, city, postal_code, status)
            VALUES (:user_id, :course_id, :full_name, :birth_date, :national_id, :tax_number,
                    :phone, :email_contact, :address, :city, :postal_code, 'rascunho')
        ");
        
        return $stmt->execute([
            ':user_id' => $data['user_id'],
            ':course_id' => $data['course_id'],
            ':full_name' => $data['full_name'],
            ':birth_date' => $data['birth_date'] ?? null,
            ':national_id' => $data['national_id'] ?? null,
            ':tax_number' => $data['tax_number'] ?? null,
            ':phone' => $data['phone'] ?? null,
            ':email_contact' => $data['email_contact'] ?? null,
            ':address' => $data['address'] ?? null,
            ':city' => $data['city'] ?? null,
            ':postal_code' => $data['postal_code'] ?? null,
        ]);
    }
    
    /**
     * Update student record
     */
    public function update($recordId, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE student_records
            SET course_id = :course_id, full_name = :full_name, birth_date = :birth_date,
                national_id = :national_id, tax_number = :tax_number, phone = :phone,
                email_contact = :email_contact, address = :address, city = :city,
                postal_code = :postal_code, updated_at = NOW()
            WHERE id = :record_id
        ");
        
        return $stmt->execute([
            ':course_id' => $data['course_id'],
            ':full_name' => $data['full_name'],
            ':birth_date' => $data['birth_date'] ?? null,
            ':national_id' => $data['national_id'] ?? null,
            ':tax_number' => $data['tax_number'] ?? null,
            ':phone' => $data['phone'] ?? null,
            ':email_contact' => $data['email_contact'] ?? null,
            ':address' => $data['address'] ?? null,
            ':city' => $data['city'] ?? null,
            ':postal_code' => $data['postal_code'] ?? null,
            ':record_id' => $recordId,
        ]);
    }
    
    /**
     * Submit record
     */
    public function submit($recordId) {
        $stmt = $this->pdo->prepare("
            UPDATE student_records
            SET status = 'submetida', submitted_at = NOW(), updated_at = NOW()
            WHERE id = ?
        ");
        return $stmt->execute([$recordId]);
    }
}
