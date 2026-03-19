<?php
/**
 * Staff Grades Controller
 */

class StaffGradesController {
    protected $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * List grade sheets
     */
    public function index() {
        requireRole('funcionario');
        
        $staffId = getUserId();
        
        // Get grade sheets for this staff member
        $stmt = $this->pdo->prepare("
            SELECT gs.*, uc.course_name, cu.name as unit_name, ay.label as academic_year_label
            FROM grade_sheets gs
            LEFT JOIN course_units cu ON gs.unit_id = cu.id
            LEFT JOIN academic_years ay ON gs.academic_year_id = ay.id
            LEFT JOIN (
                SELECT sp.unit_id, MIN(c.name) AS course_name
                FROM study_plans sp
                INNER JOIN courses c ON c.id = sp.course_id
                WHERE sp.is_active = 1
                GROUP BY sp.unit_id
            ) uc ON uc.unit_id = gs.unit_id
            WHERE gs.created_by = ? OR gs.created_by IS NULL
            ORDER BY gs.created_at DESC
        ");
        $stmt->execute([$staffId]);
        $gradeSheets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'view' => 'staff/grades.php',
            'data' => [
                'gradeSheets' => $gradeSheets
            ]
        ];
    }
    
    /**
     * View/edit grade sheet
     */
    public function view($id) {
        requireRole('funcionario');
        
        $stmt = $this->pdo->prepare("
            SELECT gs.*, uc.course_name, cu.name as unit_name, ay.label as academic_year_label
            FROM grade_sheets gs
            LEFT JOIN course_units cu ON gs.unit_id = cu.id
            LEFT JOIN academic_years ay ON gs.academic_year_id = ay.id
            LEFT JOIN (
                SELECT sp.unit_id, MIN(c.name) AS course_name
                FROM study_plans sp
                INNER JOIN courses c ON c.id = sp.course_id
                WHERE sp.is_active = 1
                GROUP BY sp.unit_id
            ) uc ON uc.unit_id = gs.unit_id
            WHERE gs.id = ?
        ");
        $stmt->execute([$id]);
        $gradeSheet = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$gradeSheet) {
            http_response_code(404);
            die('Pauta não encontrada');
        }
        
        // Get grades for this sheet
        $stmt = $this->pdo->prepare("
            SELECT gss.*, u.full_name as student_name
            FROM grade_sheet_students gss
            LEFT JOIN users u ON gss.user_id = u.id
            WHERE gss.grade_sheet_id = ?
            ORDER BY u.full_name
        ");
        $stmt->execute([$id]);
        $grades = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle grade updates
            foreach ($_POST['grades'] ?? [] as $gradeId => $value) {
                $stmt = $this->pdo->prepare(
                    "UPDATE grade_sheet_students
                     SET final_grade = ?, updated_by = ?, updated_at = NOW()
                     WHERE id = ?"
                );
                $stmt->execute([$value !== '' ? $value : null, getUserId(), $gradeId]);
            }
            
            setFlash('success', 'Notas atualizadas com sucesso');
            header('Location: ' . url('staff/grades.php?action=view&id=' . $id));
            exit;
        }
        
        return [
            'view' => 'staff/grades-view.php',
            'data' => [
                'gradeSheet' => $gradeSheet,
                'grades' => $grades
            ]
        ];
    }

    /**
     * Evaluate submitted student record
     */
    public function evaluateRecord($id) {
        requireAnyRole('funcionario', 'gestor');

        $stmt = $this->pdo->prepare(
            "SELECT sr.*, c.name as course_name, u.full_name as student_name, u.email as student_email
             FROM student_records sr
             LEFT JOIN courses c ON sr.course_id = c.id
             LEFT JOIN users u ON sr.user_id = u.id
             WHERE sr.id = ?"
        );
        $stmt->execute([$id]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$record) {
            setFlash('error', t('staff.record_not_found'));
            header('Location: ' . url('dashboard.php'));
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status'] ?? '';
            $reviewNotes = trim($_POST['review_notes'] ?? '');

            if (!in_array($status, ['aprovada', 'rejeitada'], true)) {
                setFlash('error', t('staff.invalid_status'));
                header('Location: ' . url('staff/grades.php?action=evaluate&id=' . (int) $id));
                exit;
            }

            $updateStmt = $this->pdo->prepare(
                "UPDATE student_records
                 SET status = :status,
                     review_notes = :review_notes,
                     reviewed_by = :reviewed_by,
                     reviewed_at = NOW(),
                     updated_at = NOW()
                 WHERE id = :id"
            );

            $updated = $updateStmt->execute([
                ':status' => $status,
                ':review_notes' => $reviewNotes !== '' ? $reviewNotes : null,
                ':reviewed_by' => getUserId(),
                ':id' => $id,
            ]);

            if ($updated) {
                setFlash('success', t('staff.record_review_saved'));
                header('Location: ' . url('dashboard.php'));
                exit;
            }

            setFlash('error', t('staff.record_review_failed'));
            header('Location: ' . url('staff/grades.php?action=evaluate&id=' . (int) $id));
            exit;
        }

        return [
            'view' => 'staff/record-evaluate.php',
            'data' => [
                'record' => $record,
            ],
        ];
    }
}
