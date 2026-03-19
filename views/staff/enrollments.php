<?php
/**
 * Enrollment Requests Management View
 */
?>
<!DOCTYPE html>
<html lang="<?php echo getCurrentLanguage() === 'en' ? 'en' : 'pt-PT'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('enrollment.manage_title'); ?> - SGA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo url('assets/css/app.css'); ?>" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>

    <div class="container mt-4 mb-5">
        <section class="card mb-4">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h1 class="h3 mb-1"><?php echo t('enrollment.manage_title'); ?></h1>
                    <p class="text-muted mb-0"><?php echo t('enrollment.manage_subtitle'); ?></p>
                </div>
                <a href="<?php echo url('dashboard.php'); ?>" class="btn btn-outline-secondary"><?php echo t('record.back'); ?></a>
            </div>
        </section>

        <?php if ($error): ?>
            <?php echo alertError($error); ?>
        <?php endif; ?>

        <?php if ($success): ?>
            <?php echo alertSuccess($success); ?>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><?php echo t('enrollment.pending_title'); ?></h5>
                <span class="badge bg-warning text-dark"><?php echo count($pendingRequests ?? []); ?></span>
            </div>
            <div class="card-body">
                <?php if (empty($pendingRequests)): ?>
                    <p class="text-muted mb-0"><?php echo t('enrollment.no_pending'); ?></p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th><?php echo t('dashboard.student_name'); ?></th>
                                    <th><?php echo t('dashboard.course'); ?></th>
                                    <th><?php echo t('student_dashboard.type'); ?></th>
                                    <th><?php echo t('dashboard.status'); ?></th>
                                    <th><?php echo t('student_dashboard.date'); ?></th>
                                    <th><?php echo t('dashboard.actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pendingRequests as $request): ?>
                                    <tr>
                                        <td><?php echo h($request['student_name']); ?></td>
                                        <td><?php echo h($request['course_name']); ?></td>
                                        <td><?php echo h(ucfirst($request['request_type'] ?? 'inscricao')); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo getStateBadgeClass($request['status'] ?? 'pendente'); ?>">
                                                <?php echo getStateLabel($request['status'] ?? 'pendente'); ?>
                                            </span>
                                        </td>
                                        <td><?php echo formatDateTime($request['created_at'] ?? null); ?></td>
                                        <td>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <form method="POST" action="<?php echo url('staff/enrollments.php?action=approve&id=' . (int) $request['id']); ?>" class="d-flex gap-2">
                                                    <input type="hidden" name="review_notes" value="">
                                                    <button type="submit" class="btn btn-sm btn-success"><?php echo t('enrollment.approve'); ?></button>
                                                </form>

                                                <form method="POST" action="<?php echo url('staff/enrollments.php?action=reject&id=' . (int) $request['id']); ?>" class="d-flex gap-2">
                                                    <input type="text" name="review_notes" class="form-control form-control-sm" placeholder="<?php echo t('enrollment.notes_optional'); ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger"><?php echo t('enrollment.reject'); ?></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo t('enrollment.recent_title'); ?></h5>
            </div>
            <div class="card-body">
                <?php if (empty($recentRequests)): ?>
                    <p class="text-muted mb-0"><?php echo t('enrollment.no_recent'); ?></p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th><?php echo t('dashboard.student_name'); ?></th>
                                    <th><?php echo t('dashboard.course'); ?></th>
                                    <th><?php echo t('dashboard.status'); ?></th>
                                    <th><?php echo t('enrollment.reviewed_by'); ?></th>
                                    <th><?php echo t('student_dashboard.date'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentRequests as $request): ?>
                                    <tr>
                                        <td><?php echo h($request['student_name']); ?></td>
                                        <td><?php echo h($request['course_name']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo getStateBadgeClass($request['status'] ?? 'pendente'); ?>">
                                                <?php echo getStateLabel($request['status'] ?? 'pendente'); ?>
                                            </span>
                                        </td>
                                        <td><?php echo h($request['reviewer_name'] ?? '-'); ?></td>
                                        <td><?php echo formatDateTime($request['updated_at'] ?? null); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo url('assets/js/app.js'); ?>"></script>
</body>
</html>
