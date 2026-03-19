<?php
/**
 * Application Configuration
 */

// Roles (Perfis)
define('ROLE_ALUNO', 'aluno');
define('ROLE_FUNCIONARIO', 'funcionario');
define('ROLE_GESTOR', 'gestor');

// Student Record States
define('RECORD_DRAFT', 'rascunho');
define('RECORD_SUBMITTED', 'submetida');
define('RECORD_APPROVED', 'aprovada');
define('RECORD_REJECTED', 'rejeitada');

// Enrollment Request States
define('ENROLLMENT_PENDING', 'pendente');
define('ENROLLMENT_APPROVED', 'aprovado');
define('ENROLLMENT_REJECTED', 'rejeitado');

// Grade Sheet States
define('GRADE_DRAFT', 'em_preparacao');
define('GRADE_PUBLISHED', 'publicada');
define('GRADE_CLOSED', 'fechada');

// Grade Sheet Seasons
define('SEASON_NORMAL', 'normal');
define('SEASON_RECURSO', 'recurso');
define('SEASON_ESPECIAL', 'especial');

// File Upload
define('UPLOAD_DIR', __DIR__ . '/../public/uploads/photos/');
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png']);
define('MAX_FILE_SIZE', 2 * 1024 * 1024); // 2MB

// Session
define('SESSION_TIMEOUT', 3600); // 1 hour

// App Settings
define('APP_NAME', 'Sistema de Gestão de Serviços Académicos');
define('APP_VERSION', '1.0.0');

return [
    'app_name' => APP_NAME,
    'app_version' => APP_VERSION,
    'roles' => [ROLE_ALUNO, ROLE_FUNCIONARIO, ROLE_GESTOR],
];
