<?php
/**
 * Staff Grades List View
 */
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('grades.title'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo url('assets/css/app.css'); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    
    <div class="container-fluid mt-5">
        <h2><i class="fas fa-list"></i> <?php echo t('grades.my_grades'); ?></h2>
        
        <?php if ($error ?? false): ?>
            <?php echo alertError($error); ?>
        <?php endif; ?>
        
        <?php if ($success ?? false): ?>
            <?php echo alertSuccess($success); ?>
        <?php endif; ?>
        
        <div class="card mt-4">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th><?php echo t('grades.course'); ?></th>
                            <th><?php echo t('grades.unit'); ?></th>
                            <th><?php echo t('grades.season'); ?></th>
                            <th><?php echo t('grades.status'); ?></th>
                            <th><?php echo t('grades.created_date'); ?></th>
                            <th><?php echo t('grades.actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($gradeSheets ?? [])): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <?php echo t('grades.no_grades'); ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($gradeSheets as $sheet): ?>
                                <tr>
                                    <td><?php echo h($sheet['course_name'] ?? '-'); ?></td>
                                    <td><?php echo h($sheet['unit_name'] ?? '-'); ?></td>
                                    <td><?php echo h($sheet['season'] ?? t('grades.normal_season')); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo getStateBadgeClass($sheet['status'] ?? 'em_preparacao'); ?>">
                                            <?php echo getStateLabel($sheet['status'] ?? 'em_preparacao'); ?>
                                        </span>
                                    </td>
                                    <td><?php echo formatDate($sheet['created_at'] ?? ''); ?></td>
                                    <td>
                                        <a href="<?php echo url('staff/grades.php?action=view&id=' . $sheet['id']); ?>" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> <?php echo t('common.edit'); ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo url('assets/js/app.js'); ?>"></script>
</body>
</html>
