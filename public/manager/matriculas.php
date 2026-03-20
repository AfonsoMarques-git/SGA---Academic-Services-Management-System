<?php
// Corrigir require_once com verificação de realpath
$requirePath = realpath(__DIR__ . '/../../core/bootstrap.php');
if ($requirePath === false) {
    die('Erro crítico: Não foi possível localizar core/bootstrap.php. Caminho tentado: ' . __DIR__ . '/../core/bootstrap.php');
}
require_once $requirePath;
/**
 * Controller para gestão de matrículas pendentes pelo gestor
 */
requireRole('gestor');

// Aceitar matrícula
if (isset($_GET['action']) && $_GET['action'] === 'aceitar' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    // Buscar dados da matrícula e do utilizador
    $stmt = $pdo->prepare("SELECT er.*, u.full_name, u.email, u.username FROM enrollment_requests er LEFT JOIN users u ON er.user_id = u.id WHERE er.id = ? AND er.status = 'pendente'");
    $stmt->execute([$id]);
    $matricula = $stmt->fetch();
    if ($matricula && $matricula['full_name'] && $matricula['email'] && $matricula['username']) {
        // Gerar senha provisória
        $senha_prov = bin2hex(random_bytes(4));
        $hash = password_hash($senha_prov, PASSWORD_DEFAULT);
        // Verificar se já existe utilizador com este username/email
        $stmtCheck = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmtCheck->execute([$matricula['username'], $matricula['email']]);
        $userExists = $stmtCheck->fetch();
        if (!$userExists) {
            // Criar utilizador
            $stmtUser = $pdo->prepare("INSERT INTO users (role_id, full_name, email, username, password_hash, is_active, created_at) VALUES (1, ?, ?, ?, ?, 1, NOW())");
            $stmtUser->execute([
                $matricula['full_name'],
                $matricula['email'],
                $matricula['username'],
                $hash
            ]);
        }
        // Atualizar matrícula para aceite
        $pdo->prepare("UPDATE enrollment_requests SET status = 'aceite', reviewed_by = ?, reviewed_at = NOW() WHERE id = ?")
            ->execute([$_SESSION['user_id'], $id]);
        // Enviar email
        $to = $matricula['email'];
        $subject = 'Matrícula aceite - SGA';
        $message = "Olá, sua matrícula foi aceite!\n\nUsuário: {$matricula['username']}\nSenha provisória: $senha_prov\nAcesse o sistema e altere sua senha no dashboard.";
        @mail($to, $subject, $message);
        setFlash('success', 'Matrícula aceite, utilizador criado e email enviado.');
    } else {
        setFlash('error', 'Matrícula não encontrada, já processada ou dados incompletos.');
    }
    header('Location: matriculas.php');
    exit;
}
// Rejeitar matrícula
if (isset($_GET['action']) && $_GET['action'] === 'rejeitar' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $pdo->prepare("UPDATE enrollment_requests SET status = 'rejeitada', reviewed_by = ?, reviewed_at = NOW() WHERE id = ?")
        ->execute([$_SESSION['user_id'], $id]);
    setFlash('success', 'Matrícula rejeitada.');
    header('Location: matriculas.php');
    exit;
}
// Listar matrículas pendentes
$stmt = $pdo->query("SELECT * FROM enrollment_requests WHERE status = 'pendente' ORDER BY created_at ASC");
$matriculas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Matrículas Pendentes</title>
    <link href="../assets/css/app.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Matrículas Pendentes</h2>
        <?php if ($error = getFlash('error')): ?>
            <div class="alert alert-danger"><?php echo h($error); ?></div>
        <?php endif; ?>
        <?php if ($success = getFlash('success')): ?>
            <div class="alert alert-success"><?php echo h($success); ?></div>
        <?php endif; ?>
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Utilizador</th>
                    <th>Curso</th>
                    <th>Data Nasc.</th>
                    <th>Data Pedido</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($matriculas as $m): ?>
                    <tr>
                        <td><?php echo h($m['full_name']); ?></td>
                        <td><?php echo h($m['email']); ?></td>
                        <td><?php echo h($m['username']); ?></td>
                        <td><?php echo h($m['course_id']); ?></td>
                        <td><?php echo h($m['birth_date']); ?></td>
                        <td><?php echo h($m['created_at']); ?></td>
                        <td>
                            <a href="?action=aceitar&id=<?php echo $m['id']; ?>" class="btn btn-success btn-sm">Aceitar</a>
                            <a href="?action=rejeitar&id=<?php echo $m['id']; ?>" class="btn btn-danger btn-sm">Rejeitar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
