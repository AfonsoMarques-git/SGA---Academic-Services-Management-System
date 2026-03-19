# Guia de Desenvolvimento - SGA

## Quickstart para Desenvolvedores

### Estrutura de um Controlador

```php
<?php
class MeuController {
    protected $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Listar todos os registos
     */
    public function index() {
        // Validar permissões
        requireAnyRole('gestor', 'funcionario');
        
        // Carregar modelo
        require_once __DIR__ . '/../models/MeuModelo.php';
        $model = new MeuModelo($this->pdo);
        
        // Obter dados
        $dados = $model->getAll();
        
        // Retornar resultado
        return [
            'view' => 'minha/pagina.php',
            'data' => ['items' => $dados]
        ];
    }
    
    /**
     * Criar novo registo
     */
    public function create() {
        requireRole('gestor');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'campo1' => $_POST['campo1'] ?? '',
                'campo2' => $_POST['campo2'] ?? '',
            ];
            
            if (empty($data['campo1'])) {
                setFlash('error', 'Campo obrigatório');
                // operação falhou, retornar form
            } else {
                // Inserir dados
                $stmt = $this->pdo->prepare("INSERT INTO tabela SET ...");
                $stmt->execute($data);
                
                setFlash('success', 'Registo criado com sucesso');
                header('Location: ' . url('pagina.php'));
                exit;
            }
        }
        
        return [
            'view' => 'minha/form.php',
            'data' => ['item' => null]
        ];
    }
}
```

### Estrutura de uma Vista

```php
<?php
/**
 * Descrição da vista
 */
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../layouts/navbar.php'; ?>
    
    <div class="container mt-5">
        <!-- Mensagens de erro/sucesso -->
        <?php if ($error ?? false): ?>
            <?php echo alertError($error); ?>
        <?php endif; ?>
        
        <?php if ($success ?? false): ?>
            <?php echo alertSuccess($success); ?>
        <?php endif; ?>
        
        <!-- Conteúdo -->
        <h1>Título</h1>
        
        <!-- Loop sobre dados -->
        <?php foreach ($items as $item): ?>
            <div class="card">
                <h5><?php echo h($item['name']); ?></h5>
            </div>
        <?php endforeach; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

### Funções Helper Essenciais

```php
// Escapar output (SEMPRE use isto!)
h($string) → htmlspecialchars($string, ENT_QUOTES, 'UTF-8')

// Autenticação
isAuthenticated() → bool
requireAuth() → void or redirect
requireRole('gestor') → void or 403 error
requireAnyRole('gestor', 'funcionario') → void or 403 error

// URLs
url('pagina.php') → /public/pagina.php
url('manager/courses.php?action=edit&id=5') → /public/manager/courses.php?...

// Mensagens
setFlash('tipo', 'mensagem') → void
getFlash('tipo') → string or null
alertSuccess('mensagem') → HTML alert
alertError('mensagem') → HTML alert

// Formatação
formatDate('2026-03-17') → "17/03/2026"
formatDateTime('2026-03-17 14:30:00') → "17/03/2026 14:30"
getStateLabel('rascunho') → "Rascunho"
getStateBadgeClass('aprovada') → "success"
```

## Adicionando uma Nova Feature

### Exemplo: Criar Gestão de Disciplinas

#### 1. Criar o Controlador

```bash
# File: controllers/DisciplineController.php

<?php
class DisciplineController {
    // ... suas classes aqui
}
```

#### 2. Criar as Vistas

```bash
# Files:
views/manager/disciplines.php           (lista)
views/manager/disciplines-form.php      (form)
```

#### 3. Criar a Página Pública

```bash
# File: public/manager/disciplines.php

<?php
require_once __DIR__ . '/../core/bootstrap.php';
require_once __DIR__ . '/../controllers/DisciplineController.php';

$controller = new DisciplineController($pdo);
// ... controller logic
```

#### 4. Adicionar Navegação

```bash
# No navbar ou no dashboard, adicionar link:
<a href="<?php echo url('manager/disciplines.php'); ?>">
    Gerir Disciplinas
</a>
```

## Queries de Base de Dados Exemplo

### Selecionar com JOINs

```php
$stmt = $this->pdo->prepare("
    SELECT u.id, u.full_name, r.name as role_name
    FROM users u
    LEFT JOIN roles r ON u.role_id = r.id
    WHERE u.is_active = 1
    ORDER BY u.created_at DESC
");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
```

### Inserir com Prepared Statements

```php
$stmt = $this->pdo->prepare("
    INSERT INTO courses (code, name, description, is_active, created_at)
    VALUES (?, ?, ?, 1, NOW())
");
$stmt->execute([$code, $name, $description]);
$lastId = $this->pdo->lastInsertId();
```

### Atualizar Dados

```php
$stmt = $this->pdo->prepare("
    UPDATE courses 
    SET code = ?, name = ?, updated_at = NOW()
    WHERE id = ?
");
$stmt->execute([$code, $name, $id]);
```

### Soft Delete

```php
$stmt = $this->pdo->prepare("
    UPDATE courses SET is_active = 0 WHERE id = ?
");
$stmt->execute([$id]);
```

## Tratamento de Erros

### Try-Catch Pattern

```php
try {
    $stmt = $this->pdo->prepare("...");
    $stmt->execute($data);
    
    setFlash('success', 'Operação bem-sucedida');
    header('Location: pagina.php');
    exit;
    
} catch (PDOException $e) {
    setFlash('error', 'Erro: ' . $e->getMessage());
    // Retornar form com dados para retry
}
```

## Validação de Entrada

```php
$errors = [];

if (empty($username)) {
    $errors[] = 'Utilizador obrigatório';
}

if (strlen($password) < 6) {
    $errors[] = 'Palavra-passe deve ter mínimo 6 caracteres';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Email inválido';
}

if (!empty($errors)) {
    setFlash('error', implode(', ', $errors));
    // Retornar form
}
```

## Segurança - Checklist

Antes de fazer deploy:

- [ ] Todas as inputs escapadas com `h()`
- [ ] Todas as queries com prepared statements
- [ ] Todas as páginas com validação de autenticação
- [ ] Todas as operações sensíveis com validação de role
- [ ] Nenhuma palavra-passe em plaintext
- [ ] Nenhum erro SQL visível no frontend
- [ ] Logs de erro configurados
- [ ] CORS headers configurados (se aplicável)
- [ ] Rate limiting considerado
- [ ] Backups configurados

## Git Workflow Recomendado

```bash
# Trabalhar em feature branch
git checkout -b feature/nova-funcionalidade

# Fazer commits pequenos e significativos
git add .
git commit -m "Adicionar validação de email"

# Criar pull request para main
# Após review, merge em main
```

## Estrutura de Commits

```
feat: Adicionar gestão de disciplinas
fix: Corrigir bug em validação de email
refactor: Reorganizar controlador de cursos
docs: Atualizar guia de desenvolvimento
test: Adicionar testes para model User
chore: Atualizar dependências
```

## Performance Tips

```php
// BOM - Usar indexes na BD
SELECT * FROM users WHERE email = ? -- indexed

// EVITAR - N+1 queries
foreach ($users as $user) {
    $role = $db->query("SELECT * FROM roles WHERE id = $user['role_id']");
}

// BOM - Usar joins
SELECT u.*, r.name FROM users u
LEFT JOIN roles r ON u.role_id = r.id

// EVITAR - Queries em loops
// BOM - Caching de dados frequentes
```

## Debugging

### Log de Erros

```php
// Adicionar ao bootstrap.php
error_log("Debug: " . print_r($data, true));
error_log("Erro SQL: " . $e->getMessage());
```

### Verificar Logs

```bash
# XAMPP
C:\xampp\apache\logs\error.log
C:\xampp\mysql\data\aulasphp.log

# PHP
tail -f /var/log/apache2/error.log
```

## Testes Manuais

```bash
# Testar com curl
curl -X POST http://localhost/api/users \
  -H "Content-Type: application/json" \
  -d '{"name":"João"}'

# Testar autenticação
curl -b cookies.txt -c cookies.txt \
  http://localhost/public/login.php
```

---

**Tips Finais**:
- Leia o código existente antes de escrever novo código
- Mantenha consistência de style
- Documente mudanças significativas
- Teste antes de fazer push
- Review do próprio código antes de submeter PR

Boa sorte! 🚀
