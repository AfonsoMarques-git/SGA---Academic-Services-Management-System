<?php
/**
 * Manager Users Controller
 */

class ManagerUsersController {
    protected $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * List all users
     */
    public function index() {
        requireRole('gestor');
        
        $stmt = $this->pdo->query("
            SELECT u.id, u.username, u.full_name, u.email, r.name as role_name, u.is_active, u.created_at
            FROM users u
            LEFT JOIN roles r ON u.role_id = r.id
            ORDER BY u.created_at DESC
        ");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'view' => '../views/manager/users.php',
            'data' => ['users' => $users]
        ];
    }
    
    /**
     * Create new user
     */
    public function create() {
        requireRole('gestor');
        
        // Get available roles
        $stmt = $this->pdo->query("SELECT * FROM roles ORDER BY name");
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $full_name = $_POST['full_name'] ?? '';
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';
            $role_id = $_POST['role_id'] ?? null;
            
            $errors = [];
            
            if (empty($username)) $errors[] = 'Utilizador obrigatório';
            if (empty($email)) $errors[] = 'Email obrigatório';
            if (empty($full_name)) $errors[] = 'Nome completo obrigatório';
            if (empty($password)) $errors[] = 'Palavra-passe obrigatória';
            if ($password !== $password_confirm) $errors[] = 'Palavras-passe não correspondem';
            if (empty($role_id)) $errors[] = 'Perfil obrigatório';
            
            if (!empty($errors)) {
                setFlash('error', implode(', ', $errors));
            } else {
                try {
                    // Check if username already exists
                    $check = $this->pdo->prepare("SELECT id FROM users WHERE username = ?");
                    $check->execute([$username]);
                    
                    if ($check->fetch()) {
                        setFlash('error', 'Utilizador já existe');
                    } else {
                        $password_hash = password_hash($password, PASSWORD_BCRYPT);
                        
                        $stmt = $this->pdo->prepare("
                            INSERT INTO users (username, email, full_name, password_hash, role_id, is_active, created_at)
                            VALUES (?, ?, ?, ?, ?, 1, NOW())
                        ");
                        $stmt->execute([$username, $email, $full_name, $password_hash, $role_id]);
                        
                        setFlash('success', 'Utilizador criado com sucesso');
                        header('Location: users.php');
                        exit;
                    }
                } catch (Exception $e) {
                    setFlash('error', 'Erro ao criar utilizador: ' . $e->getMessage());
                }
            }
        }
        
        return [
            'view' => '../views/manager/users-form.php',
            'data' => ['user' => null, 'roles' => $roles]
        ];
    }
    
    /**
     * Edit user
     */
    public function edit($id) {
        requireRole('gestor');
        
        $stmt = $this->pdo->prepare("
            SELECT u.*, r.id as role_id FROM users u
            LEFT JOIN roles r ON u.role_id = r.id
            WHERE u.id = ?
        ");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            http_response_code(404);
            die('Utilizador não encontrado');
        }
        
        $stmt = $this->pdo->query("SELECT * FROM roles ORDER BY name");
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $full_name = $_POST['full_name'] ?? '';
            $role_id = $_POST['role_id'] ?? null;
            $password = $_POST['password'] ?? '';
            
            $errors = [];
            
            if (empty($email)) $errors[] = 'Email obrigatório';
            if (empty($full_name)) $errors[] = 'Nome completo obrigatório';
            if (empty($role_id)) $errors[] = 'Perfil obrigatório';
            
            if (!empty($errors)) {
                setFlash('error', implode(', ', $errors));
            } else {
                try {
                    if (!empty($password)) {
                        $password_hash = password_hash($password, PASSWORD_BCRYPT);
                        $stmt = $this->pdo->prepare("
                            UPDATE users SET email = ?, full_name = ?, role_id = ?, password_hash = ?, updated_at = NOW()
                            WHERE id = ?
                        ");
                        $stmt->execute([$email, $full_name, $role_id, $password_hash, $id]);
                    } else {
                        $stmt = $this->pdo->prepare("
                            UPDATE users SET email = ?, full_name = ?, role_id = ?, updated_at = NOW()
                            WHERE id = ?
                        ");
                        $stmt->execute([$email, $full_name, $role_id, $id]);
                    }
                    
                    setFlash('success', 'Utilizador atualizado com sucesso');
                    header('Location: users.php');
                    exit;
                } catch (Exception $e) {
                    setFlash('error', 'Erro ao atualizar utilizador: ' . $e->getMessage());
                }
            }
        }
        
        return [
            'view' => '../views/manager/users-form.php',
            'data' => ['user' => $user, 'roles' => $roles]
        ];
    }
    
    /**
     * Deactivate user
     */
    public function deactivate($id) {
        requireRole('gestor');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $stmt = $this->pdo->prepare("UPDATE users SET is_active = 0 WHERE id = ?");
                $stmt->execute([$id]);
                
                setFlash('success', 'Utilizador desativado com sucesso');
                header('Location: users.php');
                exit;
            } catch (Exception $e) {
                setFlash('error', 'Erro ao desativar utilizador: ' . $e->getMessage());
            }
        }
        
        header('Location: users.php');
        exit;
    }
}
