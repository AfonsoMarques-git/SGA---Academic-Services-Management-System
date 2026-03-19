<?php
/**
 * Staff Dashboard - Funcionário
 */
?>
<!DOCTYPE html>
<html lang="<?php echo getCurrentLanguage() === 'en' ? 'en' : 'pt-PT'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('dashboard.staff.title'); ?> - SGA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo url('assets/css/app.css'); ?>" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    
    <div class="container">
        <section class="card mb-4">
            <div class="card-body">
                <div class="page-title-block">
                    <div>
                        <h1 class="h2 mb-2"><?php echo t('dashboard.staff.title'); ?></h1>
                        <p class="text-muted mb-0"><?php echo t('dashboard.welcome'); ?>, <?php echo h($_SESSION['full_name'] ?? $_SESSION['username']); ?>. <?php echo t('dashboard.staff.subtitle'); ?></p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="<?php echo url('staff/enrollments.php'); ?>" class="btn btn-outline-primary"><?php echo t('enrollment.manage_short'); ?></a>
                        <a href="<?php echo url('staff/grades.php'); ?>" class="btn btn-primary"><?php echo t('dashboard.manager.open_grades'); ?></a>
                    </div>
                </div>
            </div>
        </section>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <?php echo t('dashboard.total_students'); ?>
                    </div>
                    <div class="card-body">
                        <p class="display-4 mb-2"><?php echo count($alunos); ?></p>
                        <p class="text-muted mb-0"><?php echo t('dashboard.staff.records_summary'); ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <?php echo t('dashboard.staff.available_courses'); ?>
                    </div>
                    <div class="card-body">
                        <p class="display-4 mb-2"><?php echo count($cursos); ?></p>
                        <p class="text-muted mb-0"><?php echo t('dashboard.staff.active_offer'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><?php echo t('dashboard.submitted_records_staff'); ?></h5>
                <small class="text-muted"><?php echo t('dashboard.prioritize_pending'); ?></small>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th><?php echo t('dashboard.student_name'); ?></th>
                                <th><?php echo t('dashboard.email'); ?></th>
                                <th><?php echo t('dashboard.course'); ?></th>
                                <th><?php echo t('dashboard.status'); ?></th>
                                <th><?php echo t('dashboard.actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alunos as $a): ?>
                                <tr>
                                    <td><?php echo h($a['full_name']); ?></td>
                                    <td><?php echo h($a['email']); ?></td>
                                    <td><?php echo h($a['course_name'] ?? '-'); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo getStateBadgeClass($a['status'] ?? 'rascunho'); ?>">
                                            <?php echo getStateLabel($a['status'] ?? 'rascunho'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?php echo url('staff/grades.php?action=evaluate&id=' . (int) $a['id']); ?>" class="btn btn-sm btn-outline-primary"><?php echo t('dashboard.evaluate'); ?></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo url('assets/js/app.js'); ?>"></script>
</body>
</html>
