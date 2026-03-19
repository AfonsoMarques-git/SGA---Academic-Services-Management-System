<?php
/**
 * Manager Courses List View
 */
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('courses.title'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo url('assets/css/app.css'); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    
    <div class="container-fluid mt-5">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2><i class="fas fa-book"></i> <?php echo t('courses.manage'); ?></h2>
            </div>
            <div class="col-md-6 text-end">
                <a href="<?php echo url('manager/courses.php?action=create'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> <?php echo t('courses.create'); ?>
                </a>
            </div>
        </div>
        
        <?php if ($error): ?>
            <?php echo alertError($error); ?>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <?php echo alertSuccess($success); ?>
        <?php endif; ?>
        
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th><?php echo t('courses.code'); ?></th>
                            <th><?php echo t('courses.name'); ?></th>
                            <th><?php echo t('courses.description'); ?></th>
                            <th><?php echo t('courses.created_date'); ?></th>
                            <th><?php echo t('common.actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($courses)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <?php echo t('courses.no_found'); ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td><strong><?php echo h($course['code']); ?></strong></td>
                                    <td><?php echo h($course['name']); ?></td>
                                    <td><?php echo h(substr($course['description'] ?? '', 0, 50)); ?></td>
                                    <td><?php echo formatDate($course['created_at'] ?? ''); ?></td>
                                    <td>
                                        <a href="<?php echo url('manager/courses.php?action=edit&id=' . $course['id']); ?>" 
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> <?php echo t('common.edit'); ?>
                                        </a>
                                        <form method="POST" action="<?php echo url('manager/courses.php?action=delete&id=' . $course['id']); ?>" 
                                              style="display:inline;" onsubmit="return confirm('<?php echo t('common.confirm'); ?>');">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> <?php echo t('common.delete'); ?>
                                            </button>
                                        </form>
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
