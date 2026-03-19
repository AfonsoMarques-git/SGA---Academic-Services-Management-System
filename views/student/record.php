<?php
/**
 * Student Record View
 */
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Ficha - Aluno</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo url('assets/css/app.css'); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    
    <div class="container mt-5">
        <h2><i class="fas fa-file-alt"></i> Minha Ficha de Aluno</h2>
        
        <?php if ($error ?? false): ?>
            <?php echo alertError($error); ?>
        <?php endif; ?>
        
        <?php if ($success ?? false): ?>
            <?php echo alertSuccess($success); ?>
        <?php endif; ?>
        
        <div class="card mt-4">
            <div class="card-body">
                <?php if ($record): ?>
                    <div class="alert alert-info">
                        <strong>Estado:</strong> 
                        <span class="badge bg-<?php echo getStateBadgeClass($record['status'] ?? 'rascunho'); ?>">
                            <?php echo getStateLabel($record['status'] ?? 'rascunho'); ?>
                        </span>
                    </div>
                <?php endif; ?>
                
                <form method="POST" novalidate>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Nome Completo *</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" 
                                       value="<?php echo h($record['full_name'] ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="birth_date" class="form-label">Data de Nascimento</label>
                                <input type="date" class="form-control" id="birth_date" name="birth_date" 
                                       value="<?php echo h($record['birth_date'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="national_id" class="form-label">NIF</label>
                                <input type="text" class="form-control" id="national_id" name="national_id" 
                                       value="<?php echo h($record['national_id'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tax_number" class="form-label">Nº Contribuinte</label>
                                <input type="text" class="form-control" id="tax_number" name="tax_number" 
                                       value="<?php echo h($record['tax_number'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Telefone</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?php echo h($record['phone'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email_contact" class="form-label">Email de Contacto</label>
                                <input type="email" class="form-control" id="email_contact" name="email_contact" 
                                       value="<?php echo h($record['email_contact'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Morada</label>
                        <input type="text" class="form-control" id="address" name="address" 
                               value="<?php echo h($record['address'] ?? ''); ?>">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="city" class="form-label">Cidade</label>
                                <input type="text" class="form-control" id="city" name="city" 
                                       value="<?php echo h($record['city'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="postal_code" class="form-label">Código Postal</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code" 
                                       value="<?php echo h($record['postal_code'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="course_id" class="form-label">Curso Desejado *</label>
                        <select class="form-select" id="course_id" name="course_id" required>
                            <option value="">Selecione um curso</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?php echo $course['id']; ?>" 
                                        <?php echo ($record['course_id'] ?? null) == $course['id'] ? 'selected' : ''; ?>>
                                    <?php echo h($course['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="d-grid gap-2 d-sm-flex justify-content-sm-end mt-4">
                        <a href="<?php echo url('dashboard.php'); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Ficha
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo url('assets/js/app.js'); ?>"></script>
</body>
</html>
