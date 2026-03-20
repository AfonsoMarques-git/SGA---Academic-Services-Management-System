<?php
/**
 * Página de Matrícula de Novo Aluno
 */

require_once __DIR__ . '/../core/bootstrap.php';

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $birth_date = trim($_POST['birth_date'] ?? '');
    $national_id = trim($_POST['national_id'] ?? '');
    $tax_number = trim($_POST['tax_number'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $postal_code = trim($_POST['postal_code'] ?? '');
    $course_id = trim($_POST['course_id'] ?? '');

    if (!$full_name || !$email || !$username || !$birth_date || !$course_id) {
        $error = 'Preencha todos os campos obrigatórios.';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email inválido.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO enrollment_requests (full_name, email, username, birth_date, national_id, tax_number, phone, address, city, postal_code, course_id, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pendente', NOW())");
            $stmt->execute([$full_name, $email, $username, $birth_date, $national_id, $tax_number, $phone, $address, $city, $postal_code, $course_id]);
            $success = 'Matrícula submetida com sucesso! Aguarde o contato por email.';
        } catch (Exception $e) {
            $error = 'Erro ao submeter matrícula: ' . $e->getMessage();
        }
    }
}

// Buscar cursos ativos
require_once __DIR__ . '/../models/Curso.php';
$cursoModel = new Curso($pdo);
$courses = $cursoModel->getAll();
?>
<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matrícula de Novo Aluno</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2>Matrícula de Novo Aluno</h2>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo h($error); ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo h($success); ?></div>
                <?php endif; ?>
                <form method="POST" class="card p-4 mt-4">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Nome Completo *</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Nome de Utilizador *</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="birth_date" class="form-label">Data de Nascimento *</label>
                        <input type="date" class="form-control" id="birth_date" name="birth_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="national_id" class="form-label">Nº Documento de Identificação</label>
                        <input type="text" class="form-control" id="national_id" name="national_id">
                    </div>
                    <div class="mb-3">
                        <label for="tax_number" class="form-label">NIF</label>
                        <input type="text" class="form-control" id="tax_number" name="tax_number">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Morada</label>
                        <input type="text" class="form-control" id="address" name="address">
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">Cidade</label>
                        <input type="text" class="form-control" id="city" name="city">
                    </div>
                    <div class="mb-3">
                        <label for="postal_code" class="form-label">Código Postal</label>
                        <input type="text" class="form-control" id="postal_code" name="postal_code">
                    </div>
                    <div class="mb-3">
                        <label for="course_id" class="form-label">Curso *</label>
                        <select class="form-select" id="course_id" name="course_id" required>
                            <option value="">Selecione o curso</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?php echo h($course['id']); ?>"><?php echo h($course['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submeter Matrícula</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>