<?php
/**
 * Manager Course Units Form View
 */
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $unit ? t('form.edit_unit') : t('form.new_unit'); ?> - <?php echo t('dashboard.manager.title'); ?></title>
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
                    <i class="fas fa-clipboard"></i> 
                    <?php echo $unit ? t('form.edit_unit') : t('form.new_unit'); ?>
                </h2>
                
                <?php if ($error): ?>
                    <?php echo alertError($error); ?>
                <?php endif; ?>
                
                <div class="card mt-4">
                    <div class="card-body">
                        <form method="POST" novalidate>
                            <div class="mb-3">
                                <label for="code" class="form-label"><?php echo t('form.unit_code'); ?> *</label>
                                <input type="text" class="form-control" id="code" name="code" 
                                       value="<?php echo h($unit['code'] ?? ''); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label"><?php echo t('form.unit_name'); ?> *</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?php echo h($unit['name'] ?? ''); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="ects" class="form-label"><?php echo t('form.ects'); ?></label>
                                <input type="number" class="form-control" id="ects" name="ects" step="0.5"
                                       value="<?php echo h($unit['ects'] ?? ''); ?>">
                            </div>

                            <div class="mb-3">
                                <label for="hours" class="form-label">Horas *</label>
                                <input type="number" class="form-control" id="hours" name="hours" min="0" value="<?php echo h($unit['hours'] ?? 0); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label"><?php echo t('form.description'); ?></label>
                                <textarea class="form-control" id="description" name="description" rows="4"><?php echo h($unit['description'] ?? ''); ?></textarea>
                            </div>

                            <hr>
                            <h5>Associação a Curso, Ano Letivo e Semestre</h5>
                            <div class="mb-3">
                                <label for="course_id" class="form-label">Curso *</label>
                                <select class="form-select" id="course_id" name="course_id" required>
                                    <option value="">Selecione o curso</option>
                                    <?php foreach (($courses ?? []) as $course): ?>
                                        <option value="<?php echo h($course['id']); ?>"
                                            <?php if (($selected_course_id ?? '') == $course['id']) echo 'selected'; ?>>
                                            <?php echo h($course['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="academic_year_number" class="form-label">Ano Letivo *</label>
                                <select class="form-select" id="academic_year_number" name="academic_year_number" required>
                                    <option value="">Selecione o ano</option>
                                    <?php for ($y = 1; $y <= 5; $y++): ?>
                                        <option value="<?php echo $y; ?>" <?php if (($selected_year ?? '') == $y) echo 'selected'; ?>><?php echo $y; ?>º Ano</option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="semester" class="form-label">Semestre *</label>
                                <select class="form-select" id="semester" name="semester" required>
                                    <option value="">Selecione o semestre</option>
                                    <option value="1" <?php if (($selected_semester ?? '') == 1) echo 'selected'; ?>>1º Semestre</option>
                                    <option value="2" <?php if (($selected_semester ?? '') == 2) echo 'selected'; ?>>2º Semestre</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2 d-sm-flex justify-content-sm-end">
                                <a href="<?php echo url('manager/units.php'); ?>" class="btn btn-secondary">
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
