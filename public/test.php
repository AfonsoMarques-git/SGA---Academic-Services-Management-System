<?php
/**
 * TEST - Verify system is working
 */

echo "<h1>🔍 TESTE DO SISTEMA</h1>";

// Test 1: Check database connection
echo "<h2>1. Ligação à Base de Dados</h2>";
try {
    require_once __DIR__ . '/../config/database.php';
    $result = $pdo->query("SELECT 1");
    echo "<p style='color: green;'>✅ BD conectada com sucesso!</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro: " . $e->getMessage() . "</p>";
}

// Test 2: Check usuarios table
echo "<h2>2. Tabelas da BD</h2>";
try {
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "<p>Tabelas encontradas: " . implode(", ", $tables) . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro ao listar tabelas</p>";
}

// Test 3: List users
echo "<h2>3. Utilizadores Registados</h2>";
try {
    $users = $pdo->query("SELECT id, username, group_id FROM users")->fetchAll();
    if ($users) {
        echo "<ul>";
        foreach ($users as $u) {
            echo "<li>" . htmlspecialchars($u['username']) . " (Grupo: " . $u['group_id'] . ")</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Nenhum utilizador encontrado</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro: " . $e->getMessage() . "</p>";
}

// Test 4: List students
echo "<h2>4. Alunos Registados</h2>";
try {
    $alunos = $pdo->query("SELECT id, nome, curso_id FROM alunos")->fetchAll();
    if ($alunos) {
        echo "<ul>";
        foreach ($alunos as $a) {
            echo "<li>" . htmlspecialchars($a['nome']) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Nenhum aluno encontrado</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro: " . $e->getMessage() . "</p>";
}

// Test 5: Test login
echo "<h2>5. Teste de Login</h2>";
try {
    require_once __DIR__ . '/../models/User.php';
    $userModel = new User($pdo);
    
    // Try to find a user
    $user = $userModel->findByUsername('admin');
    if ($user) {
        echo "<p>✅ Utilizador 'admin' encontrado!</p>";
        echo "<p>Dados: " . print_r($user, true) . "</p>";
    } else {
        echo "<p>❌ Utilizador 'admin' não encontrado</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro no teste de login: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p>Teste concluído!</p>";
echo "<a href='login.php'>Voltar ao Login</a>";
?>
