<?php
/**
 * Student Dashboard View
 */

$studentName = $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Aluno';
$studentStatus = $studentRecord['status'] ?? null;
$requestCount = is_array($enrollmentRequests ?? null) ? count($enrollmentRequests) : 0;
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('student_dashboard.title'); ?> - SGA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo url('assets/css/app.css'); ?>" rel="stylesheet">
    <style>
        .student-hero {
            background: linear-gradient(135deg, var(--surface-strong) 0%, var(--surface) 100%);
            border: 1px solid var(--border);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .summary-card {
            height: 100%;
            border: 1px solid var(--border);
            border-radius: 0.9rem;
            box-shadow: var(--shadow-sm);
        }

        .summary-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
            line-height: 1;
        }

        .quick-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            color: inherit;
        }

        .quick-link.disabled {
            color: var(--text-muted);
            cursor: not-allowed;
            pointer-events: none;
        }

        .data-label {
            display: block;
            margin-bottom: 0.25rem;
            font-size: 0.85rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .data-value {
            font-size: 1rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>

    <div class="container mt-4 mb-5">
        <!-- Candidaturas a Cursos -->
        <section class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Candidaturas a Cursos</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCandidatarCurso">
                    <i class="fas fa-plus"></i> Candidatar-me a curso
                </button>
            </div>
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-3">Minhas candidaturas pendentes</h6>
                    <?php 
                    $pendingRequests = array_filter($enrollmentRequests ?? [], function($req) {
                        return $req['status'] === 'pendente';
                    });
                    ?>
                    <?php if (!empty($pendingRequests)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Curso</th>
                                        <th>Data</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pendingRequests as $req): ?>
                                        <tr>
                                            <td><?= h($req['course_name']) ?></td>
                                            <td><?= formatDate($req['created_at']) ?></td>
                                            <td><span class="badge bg-warning text-dark">Pendente</span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">Ainda não submeteu nenhuma candidatura pendente a cursos.</p>
                    <?php endif; ?>

                    <!-- Histórico de candidaturas aceites/recusadas -->
                    <?php 
                    $finalizedRequests = array_filter($enrollmentRequests ?? [], function($req) {
                        return in_array($req['status'], ['aprovado', 'recusado']);
                    });
                    ?>
                    <?php if (!empty($finalizedRequests)): ?>
                        <hr class="my-4">
                        <h6 class="mb-3">Histórico de candidaturas</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Curso</th>
                                        <th>Data</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($finalizedRequests as $req): ?>
                                        <tr>
                                            <td><?= h($req['course_name']) ?></td>
                                            <td><?= formatDate($req['created_at']) ?></td>
                                            <td>
                                                <?php if ($req['status'] === 'aprovado'): ?>
                                                    <span class="badge bg-success">Aceite</span>
                                                <?php elseif ($req['status'] === 'recusado'): ?>
                                                    <span class="badge bg-danger">Recusada</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Modal Candidatar a Curso -->
        <div class="modal fade" id="modalCandidatarCurso" tabindex="-1" aria-labelledby="modalCandidatarCursoLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="<?= url('dashboard.php?action=apply_course') ?>">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalCandidatarCursoLabel">Candidatar-me a Curso</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="course_id" class="form-label">Curso</label>
                                <select class="form-select" id="course_id" name="course_id" required>
                                    <option value="">Selecione o curso</option>
                                    <?php foreach (($cursos ?? []) as $course): ?>
                                        <option value="<?= h($course['id']) ?>"><?= h($course['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Submeter candidatura</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <section class="student-hero">
            <div class="row align-items-center g-3">
                <div class="col-lg-8">
                    <h1 class="h2 mb-2"><?php echo t('student_dashboard.welcome'); ?>, <?php echo h($studentName); ?></h1>
                    <p class="text-muted mb-0">
                        <?php echo t('student_dashboard.subtitle'); ?>
                    </p>
                </div>
                <div class="col-lg-4">
                    <div class="d-flex flex-column gap-2">
                        <div class="summary-card card">
                            <div class="card-body py-3">
                                <span class="text-muted d-block mb-1"><?php echo t('student_dashboard.record_status'); ?></span>
                                <?php if ($studentStatus): ?>
                                    <span class="badge bg-<?php echo getStateBadgeClass($studentStatus); ?>">
                                        <?php echo getStateLabel($studentStatus); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted"><?php echo t('student_dashboard.record_not_created'); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card summary-card">
                    <div class="card-body">
                        <div class="text-muted mb-2"><?php echo t('student_dashboard.enrollment_requests'); ?></div>
                        <div class="summary-value"><?php echo $requestCount; ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card summary-card">
                    <div class="card-body">
                        <div class="text-muted mb-2"><?php echo t('student_dashboard.desired_course'); ?></div>
                        <div class="fw-semibold"><?php echo h($studentRecord['course_name'] ?? t('student_dashboard.not_defined')); ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card summary-card">
                    <div class="card-body">
                        <div class="text-muted mb-2"><?php echo t('student_dashboard.latest_update'); ?></div>
                        <div class="fw-semibold"><?php echo $requestCount > 0 ? formatDate($enrollmentRequests[0]['created_at']) : t('student_dashboard.no_requests'); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><?php echo t('student_dashboard.record_data'); ?></h5>
                    </div>
                    <div class="card-body">
                        <?php if ($studentRecord): ?>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div>
                                        <span class="data-label"><?php echo t('record.full_name'); ?></span>
                                        <div class="data-value"><?php echo h($studentRecord['full_name']); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div>
                                        <span class="data-label"><?php echo t('student_dashboard.desired_course'); ?></span>
                                        <div class="data-value"><?php echo h($studentRecord['course_name'] ?? t('student_dashboard.not_defined')); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div>
                                        <span class="data-label"><?php echo t('record.email_contact'); ?></span>
                                        <div class="data-value"><?php echo h($studentRecord['email_contact'] ?? '-'); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div>
                                        <span class="data-label"><?php echo t('record.birth_date'); ?></span>
                                        <div class="data-value"><?php echo $studentRecord['birth_date'] ? formatDate($studentRecord['birth_date']) : '-'; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div>
                                        <span class="data-label"><?php echo t('record.national_id'); ?></span>
                                        <div class="data-value"><?php echo h($studentRecord['national_id'] ?? '-'); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div>
                                        <span class="data-label"><?php echo t('dashboard.status'); ?></span>
                                        <div class="data-value">
                                            <span class="badge bg-<?php echo getStateBadgeClass($studentRecord['status']); ?>">
                                                <?php echo getStateLabel($studentRecord['status']); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div>
                                        <span class="data-label"><?php echo t('student_dashboard.address'); ?></span>
                                        <div class="data-value"><?php echo h($studentRecord['address'] ?? t('student_dashboard.not_defined')); ?></div>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($studentRecord['review_notes'])): ?>
                                <div class="alert alert-info mt-4 mb-0">
                                    <strong><?php echo t('student_dashboard.observations'); ?></strong><br>
                                    <?php echo nl2br(h($studentRecord['review_notes'])); ?>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="alert alert-warning mb-0">
                                <?php echo t('student_dashboard.no_record_created'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><?php echo t('student_dashboard.enrollment_title'); ?></h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($enrollmentRequests)): ?>
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th><?php echo t('student_dashboard.course'); ?></th>
                                            <th><?php echo t('student_dashboard.type'); ?></th>
                                            <th><?php echo t('student_dashboard.status'); ?></th>
                                            <th><?php echo t('student_dashboard.date'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($enrollmentRequests as $request): ?>
                                            <tr>
                                                <td><?php echo h($request['course_name'] ?? '-'); ?></td>
                                                <td><?php echo h(ucfirst($request['request_type'] ?? 'inscricao')); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php echo getStateBadgeClass($request['status']); ?>">
                                                        <?php echo getStateLabel($request['status']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo formatDate($request['created_at'] ?? null); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted mb-0"><?php echo t('student_dashboard.no_enrollments'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><?php echo t('student_dashboard.quick_actions'); ?></h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="<?php echo url('student/record.php'); ?>" class="list-group-item list-group-item-action quick-link">
                            <span><?php echo t('student_dashboard.my_record'); ?></span>
                            <span class="text-muted"><?php echo t('student_dashboard.open'); ?></span>
                        </a>
                        <a href="<?php echo url('student/change-password.php'); ?>" class="list-group-item list-group-item-action quick-link">
                            <span><?php echo t('student_dashboard.change_password'); ?></span>
                            <span class="text-muted">Alterar</span>
                        </a>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><?php echo t('student_dashboard.summary'); ?></h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">
                            <strong><?php echo t('student_dashboard.record_created'); ?></strong>
                            <?php echo $studentRecord ? t('student_dashboard.yes') : t('student_dashboard.no'); ?>
                        </p>
                        <p class="mb-3">
                            <strong><?php echo t('student_dashboard.total_available_courses'); ?></strong>
                            <?php echo count($cursos ?? []); ?>
                        </p>
                        <p class="mb-0 text-muted">
                            <?php echo t('student_dashboard.update_info'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo url('assets/js/app.js'); ?>"></script>
</body>
</html>
