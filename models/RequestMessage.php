<?php
/**
 * Internal Request Messages (chat) model
 */
class RequestMessage {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->ensureSchema();
    }

    /**
     * Resolve role name for a given user
     */
    protected function getRoleNameByUserId($userId) {
        $stmt = $this->pdo->prepare("SELECT LOWER(r.name) AS role_name
            FROM users u
            LEFT JOIN roles r ON r.id = u.role_id
            WHERE u.id = :user_id
            LIMIT 1");
        $stmt->execute([':user_id' => $userId]);
        return (string) ($stmt->fetchColumn() ?: '');
    }

    /**
     * Messaging is disabled for gestor role
     */
    protected function canUseMessages($userId) {
        return $this->getRoleNameByUserId($userId) !== 'gestor';
    }

    /**
     * Detect if thread has any participant with a specific role
     */
    protected function threadHasRole($threadId, $roleName) {
        $stmt = $this->pdo->prepare("SELECT 1
            FROM request_thread_participants p
            INNER JOIN users u ON u.id = p.user_id
            LEFT JOIN roles r ON r.id = u.role_id
            WHERE p.thread_id = :thread_id AND LOWER(COALESCE(r.name, '')) = :role_name
            LIMIT 1");
        $stmt->execute([
            ':thread_id' => $threadId,
            ':role_name' => strtolower((string) $roleName),
        ]);
        return (bool) $stmt->fetchColumn();
    }

    /**
     * Ensure chat tables exist
     */
    protected function ensureSchema() {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS request_threads (
            id INT PRIMARY KEY AUTO_INCREMENT,
            created_by INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (created_by) REFERENCES users(id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $this->pdo->exec("CREATE TABLE IF NOT EXISTS request_thread_participants (
            thread_id INT NOT NULL,
            user_id INT NOT NULL,
            joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            last_read_at DATETIME,
            PRIMARY KEY (thread_id, user_id),
            FOREIGN KEY (thread_id) REFERENCES request_threads(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id),
            INDEX idx_user (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $this->pdo->exec("CREATE TABLE IF NOT EXISTS request_messages (
            id INT PRIMARY KEY AUTO_INCREMENT,
            thread_id INT NOT NULL,
            sender_id INT NOT NULL,
            message_text TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (thread_id) REFERENCES request_threads(id) ON DELETE CASCADE,
            FOREIGN KEY (sender_id) REFERENCES users(id),
            INDEX idx_thread (thread_id),
            INDEX idx_created_at (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }

    /**
     * List users available for conversation
     */
    public function getAvailableUsers($currentUserId) {
        if (!$this->canUseMessages($currentUserId)) {
            return [];
        }

        $stmt = $this->pdo->prepare("SELECT u.id, u.full_name, u.username, r.name AS role_name
            FROM users u
            LEFT JOIN roles r ON r.id = u.role_id
            WHERE u.is_active = 1
              AND u.id <> :user_id
              AND LOWER(COALESCE(r.name, '')) <> 'gestor'
            ORDER BY u.full_name ASC");
        $stmt->execute([':user_id' => $currentUserId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all threads for current user
     */
    public function getThreadsForUser($userId) {
        if (!$this->canUseMessages($userId)) {
            return [];
        }

        $stmt = $this->pdo->prepare("SELECT
                t.id,
                u.id AS other_user_id,
                u.full_name AS other_full_name,
                u.username AS other_username,
                r.name AS other_role_name,
                lm.message_text AS last_message,
                lm.created_at AS last_message_at
            FROM request_threads t
            INNER JOIN request_thread_participants me
                ON me.thread_id = t.id AND me.user_id = :current_user_id
            INNER JOIN request_thread_participants op
                ON op.thread_id = t.id AND op.user_id <> :other_user_exclude_id
            INNER JOIN users u
                ON u.id = op.user_id
            LEFT JOIN roles r
                ON r.id = u.role_id
            LEFT JOIN request_messages lm
                ON lm.id = (
                    SELECT m2.id
                    FROM request_messages m2
                    WHERE m2.thread_id = t.id
                    ORDER BY m2.created_at DESC, m2.id DESC
                    LIMIT 1
                )
            WHERE LOWER(COALESCE(r.name, '')) <> 'gestor'
            ORDER BY COALESCE(lm.created_at, t.updated_at) DESC, t.id DESC");

        $stmt->execute([
            ':current_user_id' => $userId,
            ':other_user_exclude_id' => $userId,
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ensure a user belongs to a thread
     */
    public function userInThread($threadId, $userId) {
        $stmt = $this->pdo->prepare("SELECT 1
            FROM request_thread_participants
            WHERE thread_id = :thread_id AND user_id = :user_id
            LIMIT 1");
        $stmt->execute([
            ':thread_id' => $threadId,
            ':user_id' => $userId,
        ]);
        return (bool) $stmt->fetchColumn();
    }

    /**
     * Get a thread by id for a user
     */
    public function getThreadById($threadId, $userId) {
        if (!$this->canUseMessages($userId)) {
            return null;
        }

        if (!$this->userInThread($threadId, $userId)) {
            return null;
        }

        $stmt = $this->pdo->prepare("SELECT
                t.id,
                u.id AS other_user_id,
                u.full_name AS other_full_name,
                u.username AS other_username,
                r.name AS other_role_name
            FROM request_threads t
            INNER JOIN request_thread_participants me
                ON me.thread_id = t.id AND me.user_id = :current_user_id
            INNER JOIN request_thread_participants op
                ON op.thread_id = t.id AND op.user_id <> :other_user_exclude_id
            INNER JOIN users u
                ON u.id = op.user_id
            LEFT JOIN roles r
                ON r.id = u.role_id
                        WHERE t.id = :thread_id
                            AND LOWER(COALESCE(r.name, '')) <> 'gestor'
            LIMIT 1");

        $stmt->execute([
            ':thread_id' => $threadId,
            ':current_user_id' => $userId,
            ':other_user_exclude_id' => $userId,
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Get messages in a thread
     */
    public function getMessages($threadId, $userId) {
        if (!$this->canUseMessages($userId)) {
            return [];
        }

        if (!$this->userInThread($threadId, $userId)) {
            return [];
        }

        if ($this->threadHasRole($threadId, 'gestor')) {
            return [];
        }

        $stmt = $this->pdo->prepare("SELECT
                m.id,
                m.thread_id,
                m.sender_id,
                m.message_text,
                m.created_at,
                u.full_name AS sender_name,
                u.username AS sender_username
            FROM request_messages m
            INNER JOIN users u ON u.id = m.sender_id
            WHERE m.thread_id = :thread_id
            ORDER BY m.created_at ASC, m.id ASC");

        $stmt->execute([':thread_id' => $threadId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get message by id
     */
    public function getMessageById($messageId) {
        $stmt = $this->pdo->prepare("SELECT id, thread_id, sender_id, message_text, created_at
            FROM request_messages
            WHERE id = :id
            LIMIT 1");
        $stmt->execute([':id' => $messageId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Find existing direct thread between two users
     */
    protected function findDirectThread($userA, $userB) {
        $stmt = $this->pdo->prepare("SELECT t.id
            FROM request_threads t
            INNER JOIN request_thread_participants p1
                ON p1.thread_id = t.id AND p1.user_id = :user_a
            INNER JOIN request_thread_participants p2
                ON p2.thread_id = t.id AND p2.user_id = :user_b
            LIMIT 1");

        $stmt->execute([
            ':user_a' => $userA,
            ':user_b' => $userB,
        ]);

        return $stmt->fetchColumn() ?: null;
    }

    /**
     * Find or create direct thread
     */
    public function findOrCreateDirectThread($currentUserId, $otherUserId) {
        $existingId = $this->findDirectThread($currentUserId, $otherUserId);
        if ($existingId) {
            return (int) $existingId;
        }

        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("INSERT INTO request_threads (created_by) VALUES (:created_by)");
            $stmt->execute([':created_by' => $currentUserId]);
            $threadId = (int) $this->pdo->lastInsertId();

            $stmt = $this->pdo->prepare("INSERT INTO request_thread_participants (thread_id, user_id)
                VALUES (:thread_id, :user_id)");
            $stmt->execute([':thread_id' => $threadId, ':user_id' => $currentUserId]);
            $stmt->execute([':thread_id' => $threadId, ':user_id' => $otherUserId]);

            $this->pdo->commit();
            return $threadId;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Send message to thread
     */
    public function sendMessage($threadId, $senderId, $messageText) {
        if (!$this->canUseMessages($senderId)) {
            return false;
        }

        $cleanText = trim((string) $messageText);
        if ($cleanText === '') {
            return false;
        }

        if (!$this->userInThread($threadId, $senderId)) {
            return false;
        }

        if ($this->threadHasRole($threadId, 'gestor')) {
            return false;
        }

        $stmt = $this->pdo->prepare("INSERT INTO request_messages (thread_id, sender_id, message_text)
            VALUES (:thread_id, :sender_id, :message_text)");

        $ok = $stmt->execute([
            ':thread_id' => $threadId,
            ':sender_id' => $senderId,
            ':message_text' => $cleanText,
        ]);

        if ($ok) {
            $touch = $this->pdo->prepare("UPDATE request_threads SET updated_at = NOW() WHERE id = :thread_id");
            $touch->execute([':thread_id' => $threadId]);
        }

        return $ok;
    }

    /**
     * Edit an existing message (only sender can edit)
     */
    public function editMessage($messageId, $editorId, $newText) {
        if (!$this->canUseMessages($editorId)) {
            return false;
        }

        $message = $this->getMessageById($messageId);
        if (!$message) {
            return false;
        }

        $threadId = (int) $message['thread_id'];
        if ((int) $message['sender_id'] !== (int) $editorId) {
            return false;
        }

        if (!$this->userInThread($threadId, $editorId) || $this->threadHasRole($threadId, 'gestor')) {
            return false;
        }

        $cleanText = trim((string) $newText);
        if ($cleanText === '') {
            return false;
        }

        $stmt = $this->pdo->prepare("UPDATE request_messages
            SET message_text = :message_text
            WHERE id = :id AND sender_id = :sender_id");

        return $stmt->execute([
            ':message_text' => $cleanText,
            ':id' => $messageId,
            ':sender_id' => $editorId,
        ]);
    }

    /**
     * Delete an existing message (only sender can delete)
     */
    public function deleteMessage($messageId, $requestUserId) {
        if (!$this->canUseMessages($requestUserId)) {
            return false;
        }

        $message = $this->getMessageById($messageId);
        if (!$message) {
            return false;
        }

        $threadId = (int) $message['thread_id'];
        if ((int) $message['sender_id'] !== (int) $requestUserId) {
            return false;
        }

        if (!$this->userInThread($threadId, $requestUserId) || $this->threadHasRole($threadId, 'gestor')) {
            return false;
        }

        $stmt = $this->pdo->prepare("DELETE FROM request_messages
            WHERE id = :id AND sender_id = :sender_id");

        $ok = $stmt->execute([
            ':id' => $messageId,
            ':sender_id' => $requestUserId,
        ]);

        if ($ok) {
            $touch = $this->pdo->prepare("UPDATE request_threads SET updated_at = NOW() WHERE id = :thread_id");
            $touch->execute([':thread_id' => $threadId]);
        }

        return $ok;
    }

    /**
     * Start direct conversation with first message
     */
    public function startConversation($currentUserId, $otherUserId, $messageText) {
        if ($currentUserId === $otherUserId) {
            return null;
        }

        if (!$this->canUseMessages($currentUserId) || !$this->canUseMessages($otherUserId)) {
            return null;
        }

        $threadId = $this->findOrCreateDirectThread($currentUserId, $otherUserId);
        $sent = $this->sendMessage($threadId, $currentUserId, $messageText);

        return $sent ? $threadId : null;
    }
}
