<?php
/**
 * Staff Record Evaluation View
 */
?>
<!DOCTYPE html>
<html lang="<?php echo getCurrentLanguage() === 'en' ? 'en' : 'pt-PT'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('staff.evaluate_record'); ?> - SGA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo url('assets/css/app.css'); ?>" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>

    <div class="container mt-4 mb-5">
        <section class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                    <div>
                        <h1 class="h3 mb-2"><?php echo t('staff.evaluate_record'); ?></h1>
                        <p class="text-muted mb-0"><?php echo h($record['student_name'] ?? '-'); ?> • <?php echo h($record['student_email'] ?? '-'); ?></p>
                    </div>
                    <a href="<?php echo url('dashboard.php'); ?>" class="btn btn-outline-secondary"><?php echo t('record.back'); ?></a>
                </div>
            </div>
        </section>

        <?php if ($error ?? false): ?>
            <?php echo alertError($error); ?>
        <?php endif; ?>

        <?php if ($success ?? false): ?>
            <?php echo alertSuccess($success); ?>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><?php echo t('student_dashboard.record_data'); ?></h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <strong><?php echo t('record.full_name'); ?>:</strong>
                        <div><?php echo h($record['full_name'] ?? '-'); ?></div>
                    </div>
                    <div class="col-md-6">
                        <strong><?php echo t('dashboard.course'); ?>:</strong>
                        <div><?php echo h($record['course_name'] ?? '-'); ?></div>
                    </div>
                    <div class="col-md-6">
                        <strong><?php echo t('record.email_contact'); ?>:</strong>
                        <div><?php echo h($record['email_contact'] ?? '-'); ?></div>
                    </div>
                    <div class="col-md-6">
                        <strong><?php echo t('record.phone'); ?>:</strong>
                        <div><?php echo h($record['phone'] ?? '-'); ?></div>
                    </div>
                    <div class="col-md-6">
                        <strong><?php echo t('dashboard.status'); ?>:</strong>
                        <div>
                            <span class="badge bg-<?php echo getStateBadgeClass($record['status'] ?? 'rascunho'); ?>">
                                <?php echo getStateLabel($record['status'] ?? 'rascunho'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <strong><?php echo t('record.submission_date'); ?>:</strong>
                        <div><?php echo !empty($record['submitted_at']) ? formatDateTime($record['submitted_at']) : '-'; ?></div>
                    </div>
                    <div class="col-12">
                        <strong><?php echo t('record.address'); ?>:</strong>
                        <div><?php echo h($record['address'] ?? '-'); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo t('staff.review_decision'); ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" novalidate>
                    <div class="mb-3">
                        <label for="status" class="form-label"><?php echo t('dashboard.status'); ?></label>
                        <select class="form-select" id="status" name="status" required>
                            <option value=""><?php echo t('common.select_option'); ?></option>
                            <option value="aprovada" <?php echo ($record['status'] ?? '') === 'aprovada' ? 'selected' : ''; ?>>
                                <?php echo t('state.aprovada'); ?>
                            </option>
                            <option value="rejeitada" <?php echo ($record['status'] ?? '') === 'rejeitada' ? 'selected' : ''; ?>>
                                <?php echo t('state.rejeitada'); ?>
                            </option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="review_notes" class="form-label"><?php echo t('record.observations'); ?></label>
                        <textarea class="form-control" id="review_notes" name="review_notes" rows="4" placeholder="<?php echo t('staff.review_notes_placeholder'); ?>"><?php echo h($record['review_notes'] ?? ''); ?></textarea>
                    </div>

                    <div class="d-flex gap-2 flex-wrap">
                        <button type="submit" class="btn btn-primary"><?php echo t('staff.save_review'); ?></button>
                        <a href="<?php echo url('dashboard.php'); ?>" class="btn btn-outline-secondary"><?php echo t('form.cancel'); ?></a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo url('assets/js/app.js'); ?>"></script>
</body>
</html>
