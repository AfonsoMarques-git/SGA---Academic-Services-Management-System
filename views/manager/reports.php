<?php
/**
 * Manager Reports View
 */
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('reports.title'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo url('assets/css/app.css'); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    
    <div class="container-fluid mt-5">
        <h2><i class="fas fa-chart-bar"></i> <?php echo t('reports.system_reports'); ?></h2>
        
        <?php if ($error): ?>
            <?php echo alertError($error); ?>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <?php echo alertSuccess($success); ?>
        <?php endif; ?>
        
        <!-- Statistics Cards -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-6 mt-4 g-3">
            <div class="col">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo t('reports.total_users'); ?></h5>
                        <p class="display-6 text-primary"><?php echo $stats['totalUsers']; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo t('reports.courses'); ?></h5>
                        <p class="display-6 text-success"><?php echo $stats['totalCourses']; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo t('reports.units'); ?></h5>
                        <p class="display-6 text-info"><?php echo $stats['totalUnits']; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo t('reports.academic_years'); ?></h5>
                        <p class="display-6 text-secondary"><?php echo $stats['totalAcademicYears']; ?></p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo t('reports.semesters'); ?></h5>
                        <p class="display-6 text-secondary"><?php echo $stats['totalSemesters']; ?></p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo t('reports.pending_validations'); ?></h5>
                        <p class="display-6 text-warning"><?php echo $stats['pendingValidations']; ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Users by Role -->
        <div class="card mt-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><?php echo t('reports.users_by_role'); ?></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th><?php echo t('reports.role'); ?></th>
                                <th><?php echo t('reports.quantity'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['usersByRole'] as $role): ?>
                                <tr>
                                    <td><?php echo h($role['name']); ?></td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?php echo $role['count']; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Recent Records -->
        <div class="card mt-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><?php echo t('reports.recent_submissions'); ?></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th><?php echo t('record.my_record_open'); ?></th>
                                <th><?php echo t('dashboard.status'); ?></th>
                                <th><?php echo t('record.submission_date'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['recentRecords'] as $record): ?>
                                <tr>
                                    <td><?php echo h($record['student_name']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo getStateBadgeClass($record['status'] ?? 'pending'); ?>">
                                            <?php echo t('state.' . ($record['status'] ?? 'pending')); ?>
                                        </span>
                                    </td>
                                    <td><?php echo formatDateTime($record['submitted_at'] ?? ''); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Export Options -->
        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><?php echo t('reports.export_data'); ?></h5>
            </div>
            <div class="card-body">
                <div class="d-flex gap-2">
                    <a href="<?php echo url('manager/reports.php?action=export&type=users'); ?>" 
                       class="btn btn-outline-primary">
                        <i class="fas fa-download"></i> <?php echo t('reports.export_users'); ?>
                    </a>
                    <a href="<?php echo url('manager/reports.php?action=export&type=courses'); ?>" 
                       class="btn btn-outline-success">
                        <i class="fas fa-download"></i> <?php echo t('reports.export_courses'); ?>
                    </a>
                    <a href="<?php echo url('manager/reports.php?action=export&type=units'); ?>" 
                       class="btn btn-outline-info">
                        <i class="fas fa-download"></i> <?php echo t('reports.export_units'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo url('assets/js/app.js'); ?>"></script>
</body>
</html>
