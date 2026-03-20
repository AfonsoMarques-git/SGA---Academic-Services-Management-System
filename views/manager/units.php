<?php
/**
 * Manager Course Units List View
 */
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('units.title'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo url('assets/css/app.css'); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    
    <div class="container-fluid mt-5">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2><i class="fas fa-clipboard"></i> <?php echo t('units.manage'); ?></h2>
            </div>
            <div class="col-md-6 text-end">
                <a href="<?php echo url('manager/units.php?action=create'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> <?php echo t('units.create'); ?>
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
                            <th><?php echo t('units.code'); ?></th>
                            <th><?php echo t('units.name'); ?></th>
                            <th><?php echo t('units.ects'); ?></th>
                            <th>Horas</th>
                            <th>Curso</th>
                            <th>Ano</th>
                            <th>Semestre</th>
                            <th><?php echo t('units.description'); ?></th>
                            <th><?php echo t('units.created_date'); ?></th>
                            <th><?php echo t('common.actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($units)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <?php echo t('units.no_found'); ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php 
                            require_once __DIR__ . '/../../models/StudyPlan.php';
                            require_once __DIR__ . '/../../models/Curso.php';
                            $studyPlanModel = new StudyPlan($GLOBALS['pdo']);
                            $cursoModel = new Curso($GLOBALS['pdo']);
                            foreach ($units as $unit): 
                                // Busca associação (pega só a primeira, se houver)
                                $plan = null;
                                $plans = $studyPlanModel->getByCourse(0); // hack para evitar erro se não houver
                                $stmt = $GLOBALS['pdo']->prepare("SELECT * FROM study_plans WHERE unit_id = ? LIMIT 1");
                                $stmt->execute([$unit['id']]);
                                $plan = $stmt->fetch();
                                $cursoNome = '-';
                                if ($plan && !empty($plan['course_id'])) {
                                    $curso = $cursoModel->getById($plan['course_id']);
                                    $cursoNome = $curso['name'] ?? '-';
                                }
                            ?>
                                <tr>
                                    <td><strong><?php echo h($unit['code']); ?></strong></td>
                                    <td><?php echo h($unit['name']); ?></td>
                                    <td><span class="badge bg-primary"><?php echo h($unit['ects']); ?></span></td>
                                    <td><?php echo h($unit['hours'] ?? 0); ?></td>
                                    <td><?php echo h($cursoNome); ?></td>
                                    <td><?php echo h($plan['academic_year_number'] ?? '-'); ?></td>
                                    <td><?php echo h($plan['semester'] ?? '-'); ?></td>
                                    <td><?php echo h(substr($unit['description'] ?? '', 0, 50)); ?></td>
                                    <td><?php echo formatDate($unit['created_at'] ?? ''); ?></td>
                                    <td>
                                        <a href="<?php echo url('manager/units.php?action=edit&id=' . $unit['id']); ?>" 
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> <?php echo t('common.edit'); ?>
                                        </a>
                                        <form method="POST" action="<?php echo url('manager/units.php?action=delete&id=' . $unit['id']); ?>" 
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
