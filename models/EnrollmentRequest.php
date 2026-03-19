<?php
/**
 * EnrollmentRequest Model
 */
class EnrollmentRequest {
    protected $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Create enrollment request
     */
    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO enrollment_requests 
            (user_id, course_id, student_record_id, request_type, status, notes_by_student)
            VALUES (:user_id, :course_id, :student_record_id, :request_type, :status, :notes_by_student)
        ");
        
        return $stmt->execute([
            ':user_id' => $data['user_id'],
            ':course_id' => $data['course_id'],
            ':student_record_id' => $data['student_record_id'] ?? null,
            ':request_type' => $data['request_type'] ?? 'inscricao',
            ':status' => 'pendente',
            ':notes_by_student' => $data['notes_by_student'] ?? null,
        ]);
    }
    
    /**
     * Get request by ID
     */
    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT er.*, c.name AS course_name, u.full_name AS student_name, 
                   reviewer.full_name AS reviewer_name
            FROM enrollment_requests er
            LEFT JOIN courses c ON er.course_id = c.id
            LEFT JOIN users u ON er.user_id = u.id
            LEFT JOIN users reviewer ON er.reviewed_by = reviewer.id
            WHERE er.id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Get pending requests
     */
    public function getPendingRequests() {
        $stmt = $this->pdo->prepare("
            SELECT er.*, c.name AS course_name, u.full_name AS student_name,
                   sr.status AS record_status
            FROM enrollment_requests er
            JOIN courses c ON er.course_id = c.id
            JOIN users u ON er.user_id = u.id
            LEFT JOIN student_records sr ON er.student_record_id = sr.id
            WHERE er.status = 'pendente'
            ORDER BY er.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get recent requests (all statuses)
     */
    public function getRecentRequests($limit = 30) {
        $limit = max(1, min(200, (int) $limit));

        $stmt = $this->pdo->prepare(
            "SELECT er.*, c.name AS course_name, u.full_name AS student_name,
                    reviewer.full_name AS reviewer_name, sr.status AS record_status
             FROM enrollment_requests er
             JOIN courses c ON er.course_id = c.id
             JOIN users u ON er.user_id = u.id
             LEFT JOIN users reviewer ON er.reviewed_by = reviewer.id
             LEFT JOIN student_records sr ON er.student_record_id = sr.id
             ORDER BY er.created_at DESC
             LIMIT {$limit}"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get student's requests
     */
    public function getByStudentId($studentId) {
        $stmt = $this->pdo->prepare("
            SELECT er.*, c.name AS course_name
            FROM enrollment_requests er
            JOIN courses c ON er.course_id = c.id
            WHERE er.user_id = :user_id
            ORDER BY er.created_at DESC
        ");
        $stmt->execute([':user_id' => $studentId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Approve request
     */
    public function approve($requestId, $reviewedBy, $notes = null) {
        $stmt = $this->pdo->prepare("
            UPDATE enrollment_requests
            SET status = 'aprovado',
                reviewed_by = :reviewed_by,
                reviewed_at = NOW(),
                review_notes = :review_notes
            WHERE id = :id
        ");
        
        return $stmt->execute([
            ':id' => $requestId,
            ':reviewed_by' => $reviewedBy,
            ':review_notes' => $notes,
        ]);
    }
    
    /**
     * Reject request
     */
    public function reject($requestId, $reviewedBy, $notes = null) {
        $stmt = $this->pdo->prepare("
            UPDATE enrollment_requests
            SET status = 'rejeitado',
                reviewed_by = :reviewed_by,
                reviewed_at = NOW(),
                review_notes = :review_notes
            WHERE id = :id
        ");
        
        return $stmt->execute([
            ':id' => $requestId,
            ':reviewed_by' => $reviewedBy,
            ':review_notes' => $notes,
        ]);
    }
}
