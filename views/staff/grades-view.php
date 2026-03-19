<?php
/**
 * Staff Grade Sheet View/Edit
 */
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pauta - Funcionário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo url('assets/css/app.css'); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    
    <div class="container-fluid mt-5">
        <h2>
            <i class="fas fa-file-alt"></i> 
            Pauta - <?php echo h($gradeSheet['course_name'] ?? 'Sem título'); ?>
        </h2>
        
        <?php if ($error ?? false): ?>
            <?php echo alertError($error); ?>
        <?php endif; ?>
        
        <?php if ($success ?? false): ?>
            <?php echo alertSuccess($success); ?>
        <?php endif; ?>
        
        <div class="card mt-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>UC:</strong> <?php echo h($gradeSheet['unit_name'] ?? '-'); ?></p>
                        <p><strong>Ano Letivo:</strong> <?php echo h($gradeSheet['academic_year_label'] ?? '-'); ?></p>
                        <p><strong>Época:</strong> <?php echo h($gradeSheet['season'] ?? 'Normal'); ?></p>
                    </div>
                    <div class="col-md-6 text-end">
                        <p><strong>Estado:</strong> 
                            <span class="badge bg-<?php echo getStateBadgeClass($gradeSheet['status'] ?? 'em_preparacao'); ?>">
                                <?php echo getStateLabel($gradeSheet['status'] ?? 'em_preparacao'); ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <form method="POST" novalidate>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Aluno</th>
                                    <th width="150">Nota</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($grades ?? [])): ?>
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-4">
                                            Nenhum aluno nesta pauta
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($grades as $grade): ?>
                                        <tr>
                                            <td><?php echo h($grade['student_name']); ?></td>
                                            <td>
                                                <input type="number" class="form-control" name="grades[<?php echo $grade['id']; ?>]" 
                                                       value="<?php echo h($grade['final_grade'] ?? ''); ?>" 
                                                       min="0" max="20" step="0.5" placeholder="0-20">
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Notas
                        </button>
                        <a href="<?php echo url('staff/grades.php'); ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo url('assets/js/app.js'); ?>"></script>
</body>
</html>
