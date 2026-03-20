<?php
/**
 * Helper Functions
 */

function h($str) {
	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function isAuthenticated() {
	return isset($_SESSION['user_id']) && isset($_SESSION['role']);
}

function getUserRole() {
	return $_SESSION['role'] ?? null;
}

function normalizeRoleKey($role) {
	$role = trim(strtolower((string) $role));
	$roleMap = [
		'aluno' => 'aluno',
		'student' => 'aluno',
		'funcionario' => 'funcionario',
		'funcionário' => 'funcionario',
		'staff' => 'funcionario',
		'gestor' => 'gestor',
		'manager' => 'gestor',
		'gestor pedagogico' => 'gestor',
		'gestor pedagógico' => 'gestor',
	];
	return $roleMap[$role] ?? $role;
}

function getRoleLabel($role = null) {
	$sourceRole = $role ?? getUserRole();
	$normalizedRole = normalizeRoleKey($sourceRole);
	return t('role.' . $normalizedRole, (string) $sourceRole);
}

function getUserId() {
	return $_SESSION['user_id'] ?? null;
}

function hasRole($role) {
	return isAuthenticated() && getUserRole() === $role;
}

function hasAnyRole(...$roles) {
	$userRole = getUserRole();
	return in_array($userRole, $roles);
}

function requireAuth() {
	if (!isAuthenticated()) {
		header('Location: login.php');
		exit;
	}
}

function requireRole($role) {
	requireAuth();
	if (getUserRole() !== $role) {
		http_response_code(403);
		die('Acesso negado');
	}
}

function requireAnyRole(...$roles) {
	requireAuth();
	if (!hasAnyRole(...$roles)) {
		http_response_code(403);
		die('Acesso negado');
	}
}

function formatDate($date) {
	if (!$date) return '';
	$timestamp = strtotime($date);
	return date('d/m/Y', $timestamp);
}

function formatDateTime($datetime) {
	if (!$datetime) return '';
	$timestamp = strtotime($datetime);
	return date('d/m/Y H:i', $timestamp);
}

function getStateBadgeClass($state) {
	$badges = [
		'rascunho' => 'secondary',
		'submetida' => 'info',
		'aprovada' => 'success',
		'rejeitada' => 'danger',
		'pendente' => 'warning',
		'aprovado' => 'success',
		'rejeitado' => 'danger',
		'em_preparacao' => 'secondary',
		'publicada' => 'success',
		'fechada' => 'dark',
	];
	return $badges[$state] ?? 'secondary';
}

function getStateLabel($state) {
	$translationKey = 'state.' . $state;
	return t($translationKey, $state);
}

function alertSuccess($message) {
	return '<div class="alert alert-success alert-dismissible fade show" role="alert">'
		. h($message) .
		'<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}

function alertError($message) {
	return '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
		. h($message) .
		'<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}

function alertWarning($message) {
	return '<div class="alert alert-warning alert-dismissible fade show" role="alert">'
		. h($message) .
		'<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}

function getFlash($key) {
	$value = $_SESSION[$key] ?? null;
	if ($value) {
		unset($_SESSION[$key]);
	}
	return $value;
}

function setFlash($key, $value) {
	$_SESSION[$key] = $value;
}

function baseUrl() {
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
	$host = $_SERVER['HTTP_HOST'];
	$scriptPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? ($_SERVER['PHP_SELF'] ?? ''));
	$publicPos = strpos($scriptPath, '/public');
	if ($publicPos !== false) {
		$basePath = substr($scriptPath, 0, $publicPos);
	} else {
		$basePath = dirname($scriptPath);
	}
	if ($basePath === '/' || $basePath === '\\' || $basePath === '.') {
		$basePath = '';
	}
	return $protocol . '://' . $host . rtrim($basePath, '/');
}

function publicUrl($path = '') {
	$base = baseUrl() . '/public';
	if ($path) {
		$base .= '/' . ltrim($path, '/');
	}
	return $base;
}
