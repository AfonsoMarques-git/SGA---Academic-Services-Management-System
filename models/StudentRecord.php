<?php
/**
 * StudentRecord Model
 * Ficha de Aluno
 */
class StudentRecord {
    protected $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Create or update student record
     */
    public function save($data) {
        if (isset($data['id']) && $data['id']) {
            return $this->update($data);
        }
        return $this->create($data);
    }
    
    /**
     * Create student record
     */
    public function create($data) {
        // First, mark any existing draft as inactive if needed
        $stmt = $this->pdo->prepare("
            SELECT id FROM student_records
            WHERE user_id = :user_id AND status = 'rascunho'
        ");
        $stmt->execute([':user_id' => $data['user_id']]);
        
        $stmt = $this->pdo->prepare("
            INSERT INTO student_records 
            (user_id, course_id, full_name, birth_date, national_id, tax_number, 
             phone, email_contact, address, city, postal_code, photo_path, status)
            VALUES 
            (:user_id, :course_id, :full_name, :birth_date, :national_id, :tax_number,
             :phone, :email_contact, :address, :city, :postal_code, :photo_path, :status)
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
            ':photo_path' => $data['photo_path'] ?? null,
            ':status' => $data['status'] ?? 'rascunho',
        ]);
    }
    
    /**
     * Update student record
     */
    public function update($data) {
        $stmt = $this->pdo->prepare("
            UPDATE student_records
            SET full_name = :full_name,
                birth_date = :birth_date,
                national_id = :national_id,
                tax_number = :tax_number,
                phone = :phone,
                email_contact = :email_contact,
                address = :address,
                city = :city,
                postal_code = :postal_code,
                course_id = :course_id
                " . (isset($data['photo_path']) ? ", photo_path = :photo_path" : "") . "
            WHERE id = :id
        ");
        
        $params = [
            ':id' => $data['id'],
            ':full_name' => $data['full_name'],
            ':birth_date' => $data['birth_date'] ?? null,
            ':national_id' => $data['national_id'] ?? null,
            ':tax_number' => $data['tax_number'] ?? null,
            ':phone' => $data['phone'] ?? null,
            ':email_contact' => $data['email_contact'] ?? null,
            ':address' => $data['address'] ?? null,
            ':city' => $data['city'] ?? null,
            ':postal_code' => $data['postal_code'] ?? null,
            ':course_id' => $data['course_id'],
        ];
        
        if (isset($data['photo_path'])) {
            $params[':photo_path'] = $data['photo_path'];
        }
        
        return $stmt->execute($params);
    }
    
    /**
     * Get record by ID
     */
    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT sr.*, c.name AS course_name, u.full_name AS reviewer_name
            FROM student_records sr
            LEFT JOIN courses c ON sr.course_id = c.id
            LEFT JOIN users u ON sr.reviewed_by = u.id
            WHERE sr.id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Get student's active record
     */
    public function getByUserId($userId) {
        $stmt = $this->pdo->prepare("
            SELECT sr.*, c.name AS course_name
            FROM student_records sr
            LEFT JOIN courses c ON sr.course_id = c.id
            WHERE sr.user_id = :user_id
            ORDER BY sr.created_at DESC
            LIMIT 1
        ");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetch();
    }
    
    /**
     * Get all submitted records for review
     */
    public function getSubmittedRecords() {
        $stmt = $this->pdo->prepare("
            SELECT sr.*, c.name AS course_name, u.full_name AS student_name
            FROM student_records sr
            JOIN courses c ON sr.course_id = c.id
            JOIN users u ON sr.user_id = u.id
            WHERE sr.status = 'submetida'
            ORDER BY sr.submitted_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Update status
     */
    public function updateStatus($recordId, $status, $reviewedBy, $notes = null) {
        $stmt = $this->pdo->prepare("
            UPDATE student_records
            SET status = :status,
                reviewed_by = :reviewed_by,
                reviewed_at = NOW(),
                review_notes = :review_notes
            WHERE id = :id
        ");
        
        return $stmt->execute([
            ':id' => $recordId,
            ':status' => $status,
            ':reviewed_by' => $reviewedBy,
            ':review_notes' => $notes,
        ]);
    }
    
    /**
     * Submit record
     */
    public function submit($recordId) {
        $stmt = $this->pdo->prepare("
            UPDATE student_records
            SET status = 'submetida',
                submitted_at = NOW()
            WHERE id = :id AND status = 'rascunho'
        ");
        return $stmt->execute([':id' => $recordId]);
    }
}
