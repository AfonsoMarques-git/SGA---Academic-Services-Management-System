<?php
/**
 * Enrollment Requests Management Controller
 */

class EnrollmentRequestsController {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * List pending/recent enrollment requests
     */
    public function index() {
        requireAnyRole('funcionario', 'gestor');

        require_once __DIR__ . '/../models/EnrollmentRequest.php';
        $requestModel = new EnrollmentRequest($this->pdo);

        $pendingRequests = $requestModel->getPendingRequests();
        $recentRequests = $requestModel->getRecentRequests(40);

        return [
            'view' => 'staff/enrollments.php',
            'data' => [
                'pendingRequests' => $pendingRequests,
                'recentRequests' => $recentRequests,
            ],
        ];
    }

    /**
     * Approve an enrollment request
     */
    public function approve($requestId) {
        requireAnyRole('funcionario', 'gestor');

        require_once __DIR__ . '/../models/EnrollmentRequest.php';
        $requestModel = new EnrollmentRequest($this->pdo);

        $notes = trim($_POST['review_notes'] ?? '');
        $ok = $requestModel->approve((int) $requestId, (int) getUserId(), $notes !== '' ? $notes : null);

        setFlash($ok ? 'success' : 'error', $ok ? t('enrollment.review_approved') : t('enrollment.review_failed'));
        header('Location: ' . url('staff/enrollments.php'));
        exit;
    }

    /**
     * Reject an enrollment request
     */
    public function reject($requestId) {
        requireAnyRole('funcionario', 'gestor');

        require_once __DIR__ . '/../models/EnrollmentRequest.php';
        $requestModel = new EnrollmentRequest($this->pdo);

        $notes = trim($_POST['review_notes'] ?? '');
        $ok = $requestModel->reject((int) $requestId, (int) getUserId(), $notes !== '' ? $notes : null);

        setFlash($ok ? 'success' : 'error', $ok ? t('enrollment.review_rejected') : t('enrollment.review_failed'));
        header('Location: ' . url('staff/enrollments.php'));
        exit;
    }
}
