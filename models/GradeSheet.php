<?php
/**
 * GradeSheet Model
 * Pautas de Avaliação
 */
class GradeSheet {
    protected $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Create grade sheet
     */
    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO grade_sheets 
            (unit_id, academic_year_id, season, created_by, status)
            VALUES (:unit_id, :academic_year_id, :season, :created_by, 'em_preparacao')
        ");
        
        $result = $stmt->execute([
            ':unit_id' => $data['unit_id'],
            ':academic_year_id' => $data['academic_year_id'],
            ':season' => $data['season'],
            ':created_by' => $data['created_by'],
        ]);
        
        if ($result) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }
    
    /**
     * Get grade sheet by ID
     */
    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT gs.*, cu.name AS unit_name, ay.label AS academic_year,
                   u.full_name AS created_by_name
            FROM grade_sheets gs
            JOIN course_units cu ON gs.unit_id = cu.id
            JOIN academic_years ay ON gs.academic_year_id = ay.id
            LEFT JOIN users u ON gs.created_by = u.id
            WHERE gs.id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Get all grade sheets with filters
     */
    public function getAll($filters = []) {
        $query = "
            SELECT gs.*, cu.name AS unit_name, ay.label AS academic_year,
                   u.full_name AS created_by_name
            FROM grade_sheets gs
            JOIN course_units cu ON gs.unit_id = cu.id
            JOIN academic_years ay ON gs.academic_year_id = ay.id
            LEFT JOIN users u ON gs.created_by = u.id
            WHERE 1=1
        ";
        
        $params = [];
        if (!empty($filters['unit_id'])) {
            $query .= " AND gs.unit_id = :unit_id";
            $params[':unit_id'] = $filters['unit_id'];
        }
        if (!empty($filters['academic_year_id'])) {
            $query .= " AND gs.academic_year_id = :academic_year_id";
            $params[':academic_year_id'] = $filters['academic_year_id'];
        }
        if (!empty($filters['season'])) {
            $query .= " AND gs.season = :season";
            $params[':season'] = $filters['season'];
        }
        if (!empty($filters['status'])) {
            $query .= " AND gs.status = :status";
            $params[':status'] = $filters['status'];
        }
        
        $query .= " ORDER BY gs.created_at DESC";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Add student to grade sheet
     */
    public function addStudent($gradeSheetId, $userId) {
        $stmt = $this->pdo->prepare("
            INSERT IGNORE INTO grade_sheet_students 
            (grade_sheet_id, user_id)
            VALUES (:grade_sheet_id, :user_id)
        ");
        
        return $stmt->execute([
            ':grade_sheet_id' => $gradeSheetId,
            ':user_id' => $userId,
        ]);
    }
    
    /**
     * Get students in grade sheet
     */
    public function getStudents($gradeSheetId) {
        $stmt = $this->pdo->prepare("
            SELECT gss.*, u.full_name, u.email
            FROM grade_sheet_students gss
            JOIN users u ON gss.user_id = u.id
            WHERE gss.grade_sheet_id = :grade_sheet_id
            ORDER BY u.full_name
        ");
        $stmt->execute([':grade_sheet_id' => $gradeSheetId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Update grade
     */
    public function updateGrade($gradeSheetId, $userId, $finalGrade, $notes = null) {
        // Validate grade
        if ($finalGrade !== null && ($finalGrade < 0 || $finalGrade > 20)) {
            throw new Exception("Nota deve estar entre 0 e 20");
        }
        
        $gradeStatus = null;
        if ($finalGrade !== null) {
            $gradeStatus = $finalGrade >= 9.5 ? 'aprovado' : 'rejeitado';
        }
        
        $stmt = $this->pdo->prepare("
            UPDATE grade_sheet_students
            SET final_grade = :final_grade,
                grade_status = :grade_status,
                notes = :notes,
                updated_at = NOW()
            WHERE grade_sheet_id = :grade_sheet_id AND user_id = :user_id
        ");
        
        return $stmt->execute([
            ':grade_sheet_id' => $gradeSheetId,
            ':user_id' => $userId,
            ':final_grade' => $finalGrade,
            ':grade_status' => $gradeStatus,
            ':notes' => $notes,
        ]);
    }
    
    /**
     * Publish grade sheet
     */
    public function publish($gradeSheetId) {
        $stmt = $this->pdo->prepare("
            UPDATE grade_sheets
            SET status = 'publicada'
            WHERE id = :id
        ");
        return $stmt->execute([':id' => $gradeSheetId]);
    }
    
    /**
     * Close grade sheet
     */
    public function close($gradeSheetId) {
        $stmt = $this->pdo->prepare("
            UPDATE grade_sheets
            SET status = 'fechada'
            WHERE id = :id
        ");
        return $stmt->execute([':id' => $gradeSheetId]);
    }
    
    /**
     * Check if grade sheet already exists
     */
    public function exists($unitId, $academicYearId, $season) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as count
            FROM grade_sheets
            WHERE unit_id = :unit_id 
            AND academic_year_id = :academic_year_id 
            AND season = :season
        ");
        $stmt->execute([
            ':unit_id' => $unitId,
            ':academic_year_id' => $academicYearId,
            ':season' => $season,
        ]);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
}
