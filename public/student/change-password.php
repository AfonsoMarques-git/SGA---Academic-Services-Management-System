<?php
/**
 * Aluno: Alteração de senha no dashboard
 */
require_once __DIR__ . '/../../core/bootstrap.php';
requireAuth();

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    if (!$current || !$new || !$confirm) {
        $error = 'Preencha todos os campos.';
    } else if ($new !== $confirm) {
        $error = 'A nova palavra-passe e a confirmação não coincidem.';
    } else {
        require_once __DIR__ . '/../../models/User.php';
        $userModel = new User($pdo);
        $user = $userModel->findById($_SESSION['user_id']);
        if (!$userModel->verifyPassword($user, $current)) {
            $error = 'Palavra-passe atual incorreta.';
        } else {
            $userModel->updatePassword($user['id'], $new);
            $success = 'Palavra-passe alterada com sucesso!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Alterar Palavra-passe</title>
    <link href="../../assets/css/app.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Alterar Palavra-passe</h2>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo h($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo h($success); ?></div>
        <?php endif; ?>
        <form method="POST" class="card p-4 mt-4" style="max-width: 400px;">
            <div class="mb-3">
                <label for="current_password" class="form-label">Palavra-passe atual</label>
                <input type="password" class="form-control" id="current_password" name="current_password" required>
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label">Nova palavra-passe</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmar nova palavra-passe</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Alterar</button>
        </form>
    </div>
</body>
</html>
