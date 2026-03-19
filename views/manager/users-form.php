<?php
/**
 * Manager Users Form View
 */
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $user ? t('form.edit_user') : t('form.new_user'); ?> - <?php echo t('dashboard.manager.title'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo url('assets/css/app.css'); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2>
                    <i class="fas fa-user"></i> 
                    <?php echo $user ? t('form.edit_user') : t('form.new_user'); ?>
                </h2>
                
                <?php if ($error): ?>
                    <?php echo alertError($error); ?>
                <?php endif; ?>
                
                <div class="card mt-4">
                    <div class="card-body">
                        <form method="POST" novalidate>
                            <?php if (!$user): ?>
                                <div class="mb-3">
                                    <label for="username" class="form-label"><?php echo t('form.username'); ?> *</label>
                                    <input type="text" class="form-control" id="username" name="username" 
                                           value="<?php echo h($user['username'] ?? ''); ?>" required>
                                </div>
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <label for="full_name" class="form-label"><?php echo t('form.full_name'); ?> *</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" 
                                       value="<?php echo h($user['full_name'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label"><?php echo t('form.email'); ?> *</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo h($user['email'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="role_id" class="form-label"><?php echo t('form.profile'); ?> *</label>
                                <select class="form-select" id="role_id" name="role_id" required>
                                    <option value=""><?php echo t('form.select_profile'); ?></option>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?php echo $role['id']; ?>" 
                                                <?php echo ($user['role_id'] ?? null) == $role['id'] ? 'selected' : ''; ?>>
                                            <?php echo h($role['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <hr>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <?php echo t('form.password'); ?> <?php echo $user ? '(' . t('form.password_leave_blank') . ')' : ''; ?> *
                                </label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       <?php echo !$user ? 'required' : ''; ?>>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password_confirm" class="form-label">
                                    <?php echo t('form.password_confirm'); ?> *
                                </label>
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" 
                                       <?php echo !$user ? 'required' : ''; ?>>
                            </div>
                            
                            <div class="d-grid gap-2 d-sm-flex justify-content-sm-end">
                                <a href="<?php echo url('manager/users.php'); ?>" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> <?php echo t('form.cancel'); ?>
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> <?php echo t('form.save'); ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo url('assets/js/app.js'); ?>"></script>
</body>
</html>
