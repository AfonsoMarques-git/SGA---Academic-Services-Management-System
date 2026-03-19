<?php
/**
 * Manager Reports Controller
 */

class ManagerReportsController {
    protected $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Show reports dashboard
     */
    public function index() {
        requireAnyRole('gestor', 'funcionario');
        
        // Get summary statistics
        $stats = [];
        
        // Total users per role
        $stmt = $this->pdo->query("
            SELECT r.name, COUNT(u.id) as count
            FROM roles r
            LEFT JOIN users u ON u.role_id = r.id AND u.is_active = 1
            GROUP BY r.id, r.name
        ");
        $stats['usersByRole'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Total courses
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM courses WHERE is_active = 1");
        $stats['totalCourses'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Total units
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM course_units WHERE is_active = 1");
        $stats['totalUnits'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Total active academic years
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM academic_years WHERE is_active = 1");
        $stats['totalAcademicYears'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Total active semesters configured in study plans
        $stmt = $this->pdo->query("SELECT COUNT(DISTINCT semester) as count FROM study_plans WHERE is_active = 1");
        $stats['totalSemesters'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Total active users
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM users WHERE is_active = 1");
        $stats['totalUsers'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Recent student records
        $stmt = $this->pdo->query("
            SELECT 
                sr.id, sr.user_id, sr.status, sr.submitted_at,
                u.full_name as student_name
            FROM student_records sr
            LEFT JOIN users u ON sr.user_id = u.id
            ORDER BY sr.submitted_at DESC
            LIMIT 10
        ");
        $stats['recentRecords'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Pending validations
        $stmt = $this->pdo->query("
            SELECT COUNT(*) as count FROM student_records
            WHERE status = 'submetida'
        ");
        $stats['pendingValidations'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        return [
            'view' => '../views/manager/reports.php',
            'data' => ['stats' => $stats]
        ];
    }
    
    /**
     * Export data to CSV
     */
    public function export($type) {
        requireRole('gestor');
        
        $filename = '';
        $data = [];
        $headers = [];
        
        switch ($type) {
            case 'users':
                $filename = 'utilizadores_' . date('Ymd_His') . '.csv';
                $stmt = $this->pdo->query("
                    SELECT u.username, u.email, u.full_name, r.name as role_name, u.created_at
                    FROM users u
                    LEFT JOIN roles r ON u.role_id = r.id
                    WHERE u.is_active = 1
                    ORDER BY u.created_at DESC
                ");
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $headers = ['Utilizador', 'Email', 'Nome Completo', 'Perfil', 'Data Criação'];
                break;
                
            case 'courses':
                $filename = 'cursos_' . date('Ymd_His') . '.csv';
                $stmt = $this->pdo->query("
                    SELECT code, name, description, created_at
                    FROM courses
                    WHERE is_active = 1
                    ORDER BY name
                ");
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $headers = ['Código', 'Nome', 'Descrição', 'Data Criação'];
                break;
                
            case 'units':
                $filename = 'ucs_' . date('Ymd_His') . '.csv';
                $stmt = $this->pdo->query("
                    SELECT code, name, description, ects, created_at
                    FROM course_units
                    WHERE is_active = 1
                    ORDER BY name
                ");
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $headers = ['Código', 'Nome', 'Descrição', 'ECTS', 'Data Criação'];
                break;
                
            default:
                setFlash('error', 'Tipo de exportação inválido');
                header('Location: reports.php');
                exit;
        }
        
        // Output CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, $headers);
        
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        
        fclose($output);
        exit;
    }
}
