<?php
/**
 * Internal Messages (Meus Pedidos)
 */

require_once __DIR__ . '/../core/bootstrap.php';
requireAuth();

if (!hasAnyRole('aluno', 'funcionario')) {
    setFlash('error', t('messages.access_denied'));
    header('Location: ' . url('dashboard.php'));
    exit;
}

require_once __DIR__ . '/../models/RequestMessage.php';

$messageModel = new RequestMessage($pdo);
$currentUserId = (int) getUserId();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'start') {
        $recipientId = (int) ($_POST['recipient_id'] ?? 0);
        $messageText = $_POST['message_text'] ?? '';

        if ($recipientId <= 0) {
            setFlash('error', t('messages.select_user'));
            header('Location: ' . url('messages.php'));
            exit;
        }

        $threadId = $messageModel->startConversation($currentUserId, $recipientId, $messageText);

        if ($threadId) {
            setFlash('success', t('messages.conversation_started'));
            header('Location: ' . url('messages.php?thread=' . (int) $threadId));
            exit;
        }

        setFlash('error', t('messages.send_failed'));
        header('Location: ' . url('messages.php'));
        exit;
    }

    if ($action === 'send') {
        $threadId = (int) ($_POST['thread_id'] ?? 0);
        $messageText = $_POST['message_text'] ?? '';

        if ($threadId > 0 && $messageModel->sendMessage($threadId, $currentUserId, $messageText)) {
            header('Location: ' . url('messages.php?thread=' . (int) $threadId));
            exit;
        }

        setFlash('error', t('messages.send_failed'));
        header('Location: ' . url('messages.php?thread=' . (int) $threadId));
        exit;
    }

    if ($action === 'edit') {
        $messageId = (int) ($_POST['message_id'] ?? 0);
        $threadId = (int) ($_POST['thread_id'] ?? 0);
        $messageText = $_POST['message_text'] ?? '';

        if ($messageId > 0 && $threadId > 0 && $messageModel->editMessage($messageId, $currentUserId, $messageText)) {
            setFlash('success', t('messages.edit_success'));
            header('Location: ' . url('messages.php?thread=' . (int) $threadId));
            exit;
        }

        setFlash('error', t('messages.edit_failed'));
        header('Location: ' . url('messages.php?thread=' . (int) $threadId));
        exit;
    }

    if ($action === 'delete') {
        $messageId = (int) ($_POST['message_id'] ?? 0);
        $threadId = (int) ($_POST['thread_id'] ?? 0);

        if ($messageId > 0 && $threadId > 0 && $messageModel->deleteMessage($messageId, $currentUserId)) {
            setFlash('success', t('messages.delete_success'));
            header('Location: ' . url('messages.php?thread=' . (int) $threadId));
            exit;
        }

        setFlash('error', t('messages.delete_failed'));
        header('Location: ' . url('messages.php?thread=' . (int) $threadId));
        exit;
    }
}

$selectedThreadId = (int) ($_GET['thread'] ?? 0);
$threads = $messageModel->getThreadsForUser($currentUserId);
$users = $messageModel->getAvailableUsers($currentUserId);
$selectedThread = null;
$messages = [];

if ($selectedThreadId > 0) {
    $selectedThread = $messageModel->getThreadById($selectedThreadId, $currentUserId);
    if ($selectedThread) {
        $messages = $messageModel->getMessages($selectedThreadId, $currentUserId);
    }
}

$error = getFlash('error');
$success = getFlash('success');

include __DIR__ . '/../views/messages/index.php';
