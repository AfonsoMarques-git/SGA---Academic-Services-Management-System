<?php
/**
 * User Model - For Professional Database Structure
 * Works with: users (role_id), roles, student_records
 */
class User {
    protected $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Find user by username
     */
    public function findByUsername($username) {
        $stmt = $this->pdo->prepare("
            SELECT u.id, u.username, u.password_hash, u.role_id,
                   r.name as role_name,
                   u.full_name
            FROM users u
            LEFT JOIN roles r ON u.role_id = r.id
            WHERE u.username = ? AND u.is_active = 1
            LIMIT 1
        ");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Find user by ID
     */
    public function findById($id) {
        $stmt = $this->pdo->prepare("
            SELECT u.id, u.username, u.password_hash, u.role_id,
                   r.name as role_name,
                   u.full_name, u.email
            FROM users u
            LEFT JOIN roles r ON u.role_id = r.id
            WHERE u.id = ? AND u.is_active = 1
            LIMIT 1
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Verify password using bcrypt
     */
    public function verifyPassword($user, $password) {
        return password_verify($password, $user['password_hash']);
    }
    
    /**
     * Update last login
     */
    public function updateLastLogin($userId) {
        $stmt = $this->pdo->prepare("
            UPDATE users
            SET last_login = NOW()
            WHERE id = ?
        ");
        return $stmt->execute([$userId]);
    }

    /**
     * Create user
     */
    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO users (role_id, full_name, email, username, password_hash, is_active)
            VALUES (:role_id, :full_name, :email, :username, :password_hash, :is_active)
        ");
        
        return $stmt->execute([
            ':role_id' => $data['role_id'] ?? 1, // Default to aluno
            ':full_name' => $data['full_name'],
            ':email' => $data['email'],
            ':username' => $data['username'],
            ':password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':is_active' => $data['is_active'] ?? 1,
        ]);
    }
    
    /**
     * Get users by role
     */
    public function getByRole($roleName) {
        $stmt = $this->pdo->prepare("
            SELECT u.id, u.username, u.role_id,
                   r.name as role_name,
                   u.full_name, u.email
            FROM users u
            LEFT JOIN roles r ON u.role_id = r.id
            WHERE r.name = ? AND u.is_active = 1
            ORDER BY u.full_name
        ");
        $stmt->execute([$roleName]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get all active users
     */
    public function getAll() {
        $stmt = $this->pdo->prepare("
            SELECT u.id, u.username, u.role_id,
                   r.name as role_name,
                   u.full_name, u.email
            FROM users u
            LEFT JOIN roles r ON u.role_id = r.id
            WHERE u.is_active = 1
            ORDER BY u.full_name
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Atualiza a senha do usuário
     */
    public function updatePassword($userId, $newPassword) {
        $stmt = $this->pdo->prepare("
            UPDATE users
            SET password_hash = :password_hash
            WHERE id = :id
        ");
        return $stmt->execute([
            ':password_hash' => password_hash($newPassword, PASSWORD_DEFAULT),
            ':id' => $userId
        ]);
    }
}
