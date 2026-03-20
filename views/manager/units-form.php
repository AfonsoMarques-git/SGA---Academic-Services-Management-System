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
    <title><?php echo isset($unit) && $unit ? t('units.edit') : t('units.create'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo url('assets/css/app.css'); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <h4 class="mb-0 fw-bold">
                                <i class="fas fa-clipboard me-2"></i>
                                <?php echo isset($unit) && $unit ? t('units.edit') : t('units.create'); ?>
                            </h4>
                        </div>
                        <form method="POST" autocomplete="off">
                            <div class="mb-3">
                                <label for="code" class="form-label">Código *</label>
                                <input type="text" class="form-control" id="code" name="code" value="<?php echo h($_POST['code'] ?? $unit['code'] ?? ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome *</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo h($_POST['name'] ?? $unit['name'] ?? ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="ects" class="form-label">ECTS *</label>
                                <input type="number" class="form-control" id="ects" name="ects" min="0" value="<?php echo h($_POST['ects'] ?? $unit['ects'] ?? 0); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="hours" class="form-label">Horas *</label>
                                <input type="number" class="form-control" id="hours" name="hours" min="0" value="<?php echo h($_POST['hours'] ?? $unit['hours'] ?? 0); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label"><?php echo t('form.description'); ?></label>
                                <textarea class="form-control" id="description" name="description" rows="4"><?php echo h($_POST['description'] ?? $unit['description'] ?? ''); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="professor_id" class="form-label">Professor Responsável *</label>
                                <select class="form-select" id="professor_id" name="professor_id" required>
                                    <option value="">Selecione o professor</option>
                                    <?php foreach (($professors ?? []) as $prof): ?>
                                        <option value="<?php echo h($prof['id']); ?>" <?php if (($selected_professor_id ?? '') == $prof['id']) echo 'selected'; ?>>
                                            <?php echo h($prof['full_name']); ?> (<?php echo h($prof['email']); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <hr>
                            <h5>Associação a Curso, Ano Letivo e Semestre</h5>
                            <div class="mb-3">
                                <label for="course_id" class="form-label">Curso *</label>
                                <select class="form-select" id="course_id" name="course_id" required>
                                    <option value="">Selecione o curso</option>
                                    <?php foreach (($courses ?? []) as $course): ?>
                                        <option value="<?php echo h($course['id']); ?>" <?php if (($selected_course_id ?? '') == $course['id']) echo 'selected'; ?>><?php echo h($course['name']); ?></option>
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
