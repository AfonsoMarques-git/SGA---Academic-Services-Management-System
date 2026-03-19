<?php
/**
 * Internal Messages View
 */
?>
<!DOCTYPE html>
<html lang="<?php echo getCurrentLanguage() === 'en' ? 'en' : 'pt-PT'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('messages.title'); ?> - SGA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo url('assets/css/app.css'); ?>" rel="stylesheet">
    <style>
        .messages-shell {
            display: grid;
            grid-template-columns: 330px 1fr;
            gap: 1rem;
        }

        .messages-list {
            max-height: 68vh;
            overflow-y: auto;
        }

        .chat-panel {
            min-height: 68vh;
            display: flex;
            flex-direction: column;
        }

        .chat-body {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            background: var(--surface-soft);
        }

        .bubble {
            max-width: 76%;
            border-radius: 12px;
            padding: 0.7rem 0.85rem;
            margin-bottom: 0.65rem;
            border: 1px solid var(--border);
            background: var(--surface);
        }

        .bubble.mine {
            margin-left: auto;
            background: var(--primary-soft);
        }

        .bubble-meta {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }

        .bubble-actions {
            margin-top: 0.45rem;
            display: flex;
            gap: 0.35rem;
            flex-wrap: wrap;
        }

        .thread-link {
            text-decoration: none;
            color: inherit;
        }

        .thread-link.active .list-group-item {
            border-color: var(--primary);
            background: var(--primary-soft);
        }

        @media (max-width: 992px) {
            .messages-shell {
                grid-template-columns: 1fr;
            }

            .messages-list,
            .chat-panel {
                max-height: none;
                min-height: auto;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>

    <div class="container mt-4 mb-5">
        <section class="card mb-4">
            <div class="card-body d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div>
                    <h1 class="h3 mb-1"><?php echo t('messages.title'); ?></h1>
                    <p class="text-muted mb-0"><?php echo t('messages.subtitle'); ?></p>
                </div>
                <a href="<?php echo url('dashboard.php'); ?>" class="btn btn-outline-secondary"><?php echo t('record.back'); ?></a>
            </div>
        </section>

        <?php if ($error): ?>
            <?php echo alertError($error); ?>
        <?php endif; ?>

        <?php if ($success): ?>
            <?php echo alertSuccess($success); ?>
        <?php endif; ?>

        <div class="messages-shell">
            <div>
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0"><?php echo t('messages.new_conversation'); ?></h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" novalidate>
                            <input type="hidden" name="action" value="start">
                            <div class="mb-2">
                                <label for="recipient_id" class="form-label"><?php echo t('messages.to'); ?></label>
                                <select id="recipient_id" name="recipient_id" class="form-select" required>
                                    <option value=""><?php echo t('common.select_option'); ?></option>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?php echo (int) $user['id']; ?>">
                                            <?php echo h($user['full_name']); ?> (<?php echo h(getRoleLabel($user['role_name'] ?? '')); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="new_message_text" class="form-label"><?php echo t('messages.message'); ?></label>
                                <textarea id="new_message_text" name="message_text" class="form-control" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm"><?php echo t('messages.start'); ?></button>
                        </form>
                    </div>
                </div>

                <div class="card messages-list">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><?php echo t('messages.conversations'); ?></h5>
                        <span class="badge bg-secondary"><?php echo count($threads); ?></span>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php if (empty($threads)): ?>
                            <div class="list-group-item text-muted"><?php echo t('messages.no_conversations'); ?></div>
                        <?php else: ?>
                            <?php foreach ($threads as $thread): ?>
                                <?php $isActive = ((int) $thread['id'] === (int) $selectedThreadId); ?>
                                <a class="thread-link <?php echo $isActive ? 'active' : ''; ?>" href="<?php echo url('messages.php?thread=' . (int) $thread['id']); ?>">
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                            <div>
                                                <div class="fw-semibold"><?php echo h($thread['other_full_name']); ?></div>
                                                <small class="text-muted"><?php echo h(getRoleLabel($thread['other_role_name'] ?? '')); ?></small>
                                            </div>
                                            <small class="text-muted"><?php echo !empty($thread['last_message_at']) ? formatDateTime($thread['last_message_at']) : '-'; ?></small>
                                        </div>
                                        <div class="text-muted small mt-1"><?php echo h(mb_strimwidth((string) ($thread['last_message'] ?? ''), 0, 72, '...')); ?></div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card chat-panel">
                <div class="card-header">
                    <?php if ($selectedThread): ?>
                        <h5 class="mb-0"><?php echo h($selectedThread['other_full_name']); ?></h5>
                        <small class="text-muted"><?php echo h(getRoleLabel($selectedThread['other_role_name'] ?? '')); ?></small>
                    <?php else: ?>
                        <h5 class="mb-0"><?php echo t('messages.select_conversation'); ?></h5>
                    <?php endif; ?>
                </div>
                <div class="card-body d-flex flex-column gap-3">
                    <div class="chat-body">
                        <?php if (!$selectedThread): ?>
                            <p class="text-muted mb-0"><?php echo t('messages.choose_left'); ?></p>
                        <?php elseif (empty($messages)): ?>
                            <p class="text-muted mb-0"><?php echo t('messages.no_messages'); ?></p>
                        <?php else: ?>
                            <?php foreach ($messages as $msg): ?>
                                <?php $isMine = ((int) $msg['sender_id'] === (int) getUserId()); ?>
                                <div class="bubble <?php echo $isMine ? 'mine' : ''; ?>">
                                    <div><?php echo nl2br(h($msg['message_text'])); ?></div>
                                    <div class="bubble-meta">
                                        <?php echo h($msg['sender_name']); ?> • <?php echo formatDateTime($msg['created_at']); ?>
                                    </div>
                                    <?php if ($isMine): ?>
                                        <div class="bubble-actions">
                                            <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#editMessage<?php echo (int) $msg['id']; ?>" aria-expanded="false" aria-controls="editMessage<?php echo (int) $msg['id']; ?>">
                                                <?php echo t('common.edit'); ?>
                                            </button>
                                            <form method="POST" onsubmit="return confirm('<?php echo h(t('messages.confirm_delete')); ?>');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="thread_id" value="<?php echo (int) $selectedThread['id']; ?>">
                                                <input type="hidden" name="message_id" value="<?php echo (int) $msg['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><?php echo t('common.delete'); ?></button>
                                            </form>
                                        </div>
                                        <div class="collapse mt-2" id="editMessage<?php echo (int) $msg['id']; ?>">
                                            <form method="POST" class="d-flex flex-column gap-2">
                                                <input type="hidden" name="action" value="edit">
                                                <input type="hidden" name="thread_id" value="<?php echo (int) $selectedThread['id']; ?>">
                                                <input type="hidden" name="message_id" value="<?php echo (int) $msg['id']; ?>">
                                                <textarea name="message_text" class="form-control form-control-sm" rows="3" required><?php echo h($msg['message_text']); ?></textarea>
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-sm btn-primary"><?php echo t('messages.save_changes'); ?></button>
                                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#editMessage<?php echo (int) $msg['id']; ?>">
                                                        <?php echo t('form.cancel'); ?>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <?php if ($selectedThread): ?>
                        <form method="POST" class="d-flex flex-column gap-2" novalidate>
                            <input type="hidden" name="action" value="send">
                            <input type="hidden" name="thread_id" value="<?php echo (int) $selectedThread['id']; ?>">
                            <label for="message_text" class="form-label mb-0"><?php echo t('messages.reply'); ?></label>
                            <textarea id="message_text" name="message_text" class="form-control" rows="3" required></textarea>
                            <div>
                                <button type="submit" class="btn btn-primary"><?php echo t('messages.send'); ?></button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo url('assets/js/app.js'); ?>"></script>
</body>
</html>
