<?php
/**
 * Student Record Controller
 */

class StudentRecordController {
    protected $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * View student record
     */
    public function viewRecord() {
        requireRole('aluno');
        
        $userId = getUserId();
        
        require_once __DIR__ . '/../models/Aluno.php';
        require_once __DIR__ . '/../models/Curso.php';
        
        $alunoModel = new Aluno($this->pdo);
        $cursoModel = new Curso($this->pdo);
        
        $record = $alunoModel->getByUserId($userId);
        $courses = $cursoModel->getAll();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle record update/submission
            
            $data = [
                'full_name' => $_POST['full_name'] ?? '',
                'birth_date' => $_POST['birth_date'] ?? '',
                'national_id' => $_POST['national_id'] ?? '',
                'tax_number' => $_POST['tax_number'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'email_contact' => $_POST['email_contact'] ?? '',
                'address' => $_POST['address'] ?? '',
                'city' => $_POST['city'] ?? '',
                'postal_code' => $_POST['postal_code'] ?? '',
                'course_id' => $_POST['course_id'] ?? '',
            ];

            // Normalize optional form fields to NULL for database compatibility.
            foreach (['birth_date', 'national_id', 'tax_number', 'phone', 'email_contact', 'address', 'city', 'postal_code'] as $optionalField) {
                if (($data[$optionalField] ?? '') === '') {
                    $data[$optionalField] = null;
                }
            }

            if (($data['course_id'] ?? '') === '') {
                setFlash('error', 'Selecione um curso antes de guardar a ficha.');
                header('Location: ' . url('student/record.php'));
                exit;
            }
            
            try {
                if (!$record) {
                    // Create new record
                    $data['user_id'] = $userId;
                    $saved = $alunoModel->create($data);
                    setFlash($saved ? 'success' : 'error', $saved ? 'Ficha criada com sucesso' : 'Não foi possível criar a ficha.');
                } else {
                    // Update existing record
                    $saved = $alunoModel->update($record['id'], $data);
                    setFlash($saved ? 'success' : 'error', $saved ? 'Ficha atualizada com sucesso' : 'Não foi possível atualizar a ficha.');
                }
            } catch (Throwable $e) {
                setFlash('error', 'Ocorreu um erro ao guardar a ficha.');
            }
            
            header('Location: ' . url('student/record.php'));
            exit;
        }
        
        return [
            'view' => 'student/record.php',
            'data' => [
                'record' => $record,
                'courses' => $courses
            ]
        ];
    }
}
