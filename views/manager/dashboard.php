<?php
/**
 * Manager (Gestor) Dashboard View
 */
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('dashboard.manager.title'); ?> - SGA</title>
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
                        <h1 class="h2 mb-2"><?php echo t('dashboard.manager.title'); ?></h1>
                        <p class="text-muted mb-0"><?php echo t('dashboard.welcome'); ?>, <?php echo h($_SESSION['full_name'] ?? $_SESSION['username']); ?>. <?php echo t('dashboard.manager.subtitle'); ?></p>
                    </div>
                    <a href="<?php echo url('manager/reports.php'); ?>" class="btn btn-primary"><?php echo t('dashboard.manager.view_reports'); ?></a>
                </div>
            </div>
        </section>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-5 g-4 mb-4">
            <div class="col">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo t('dashboard.total_students'); ?></h5>
                        <p class="display-6 text-primary"><?php echo count($alunos); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo t('dashboard.courses'); ?></h5>
                        <p class="display-6 text-success"><?php echo count($cursos); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo t('dashboard.units'); ?></h5>
                        <p class="display-6 text-warning"><?php echo (int) ($managerStats['totalUnits'] ?? 0); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo t('dashboard.academic_years'); ?></h5>
                        <p class="display-6 text-info"><?php echo (int) ($managerStats['totalAcademicYears'] ?? 0); ?></p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo t('dashboard.semesters'); ?></h5>
                        <p class="display-6 text-secondary"><?php echo (int) ($managerStats['totalSemesters'] ?? 0); ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><?php echo t('dashboard.academic_config'); ?></h5>
            </div>
            <div class="list-group list-group-flush">
                <a href="<?php echo url('manager/courses.php'); ?>" class="list-group-item list-group-item-action">
                    <?php echo t('dashboard.manage_courses'); ?>
                </a>
                <a href="<?php echo url('manager/units.php'); ?>" class="list-group-item list-group-item-action">
                    <?php echo t('dashboard.manage_units'); ?>
                </a>
                <a href="<?php echo url('manager/users.php'); ?>" class="list-group-item list-group-item-action">
                    <?php echo t('dashboard.manage_users'); ?>
                </a>
                <a href="<?php echo url('manager/reports.php'); ?>" class="list-group-item list-group-item-action">
                    <?php echo t('dashboard.manage_reports'); ?>
                </a>
                <a href="<?php echo url('staff/enrollments.php'); ?>" class="list-group-item list-group-item-action">
                    <?php echo t('enrollment.manage_short'); ?>
                </a>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><?php echo t('dashboard.submitted_records'); ?></h5>
                <small class="text-muted"><?php echo t('dashboard.review_and_forward'); ?></small>
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
                                    <td><?php echo h($a['course_name'] ?? t('common.not_defined')); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo getStateBadgeClass($a['status'] ?? 'rascunho'); ?>">
                                            <?php echo t('state.' . ($a['status'] ?? 'rascunho')); ?>
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
