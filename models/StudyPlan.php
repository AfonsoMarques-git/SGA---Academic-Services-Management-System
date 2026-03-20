<?php
/**
 * StudyPlan Model
 * Responsável por associar UCs a Cursos, Ano Letivo e Semestre
 */
class StudyPlan {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Cria associação UC-Curso-Ano-Semestre
     */
    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO study_plans (course_id, unit_id, academic_year_number, semester, is_active)
            VALUES (:course_id, :unit_id, :academic_year_number, :semester, :is_active)
        ");
        return $stmt->execute([
            ':course_id' => $data['course_id'],
            ':unit_id' => $data['unit_id'],
            ':academic_year_number' => $data['academic_year_number'],
            ':semester' => $data['semester'],
            ':is_active' => $data['is_active'] ?? 1,
        ]);
    }

    /**
     * Lista todas as associações de um curso
     */
    public function getByCourse($courseId) {
        $stmt = $this->pdo->prepare("
            SELECT sp.*, cu.name as unit_name
            FROM study_plans sp
            JOIN course_units cu ON sp.unit_id = cu.id
            WHERE sp.course_id = :course_id
            ORDER BY sp.academic_year_number, sp.semester, cu.name
        ");
        $stmt->execute([':course_id' => $courseId]);
        return $stmt->fetchAll();
    }

    /**
     * Remove associação
     */
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM study_plans WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
