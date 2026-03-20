<?php
/**
 * Manager Users List View
 */
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('users.title'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo url('assets/css/app.css'); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    
    <div class="container-fluid mt-5">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2><i class="fas fa-users"></i> <?php echo t('users.manage'); ?></h2>
            </div>
            <div class="col-md-6 text-end">
                <a href="<?php echo url('manager/users.php?action=create'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> <?php echo t('users.create'); ?>
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
                            <th><?php echo t('users.username'); ?></th>
                            <th><?php echo t('users.full_name'); ?></th>
                            <th><?php echo t('users.email'); ?></th>
                            <th><?php echo t('users.role'); ?></th>
                            <th><?php echo t('users.status'); ?></th>
                            <th><?php echo t('users.created_date'); ?></th>
                            <th><?php echo t('common.actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <?php echo t('users.no_found'); ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><strong><?php echo h($user['username']); ?></strong></td>
                                    <td><?php echo h($user['full_name']); ?></td>
                                    <td><?php echo h($user['email']); ?></td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php echo h($user['role_name'] ?? t('users.no_role')); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($user['is_active']): ?>
                                            <span class="badge bg-success"><?php echo t('users.active'); ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?php echo t('users.inactive'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo formatDate($user['created_at'] ?? ''); ?></td>
                                    <td>
                                        <a href="<?php echo url('manager/users.php?action=edit&id=' . $user['id']); ?>" 
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> <?php echo t('common.edit'); ?>
                                        </a>
                                        <?php if ($user['is_active']): ?>
                                            <form method="POST" action="<?php echo url('manager/users.php?action=deactivate&id=' . $user['id']); ?>" 
                                                  style="display:inline;" onsubmit="return confirm('<?php echo t('common.confirm'); ?>');">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-ban"></i> <?php echo t('common.deactivate'); ?>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <form method="POST" action="<?php echo url('manager/users.php?action=activate&id=' . $user['id']); ?>" 
                                                  style="display:inline;" onsubmit="return confirm('<?php echo t('common.confirm'); ?>');">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i> <?php echo t('common.activate'); ?>
                                                </button>
                                            </form>
                                            <form method="POST" action="<?php echo url('manager/users.php?action=delete&id=' . $user['id']); ?>" 
                                                  style="display:inline;" onsubmit="return confirm('<?php echo t('common.confirm_delete'); ?>');">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i> <?php echo t('common.delete'); ?>
                                                </button>
                                            </form>
                                        <?php endif; ?>
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
