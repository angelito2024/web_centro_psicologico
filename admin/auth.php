<?php
require_once __DIR__ . '/../inc/blog.php';

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    ]);
    session_start();
}

const LOGIN_MAX_ATTEMPTS = 5;
const LOGIN_LOCKOUT_SECONDS = 300; // 5 minutos

function admin_config(): array
{
    $file = __DIR__ . '/admin-config.php';
    if (!is_file($file)) {
        return [];
    }
    $config = require $file;
    return is_array($config) ? $config : [];
}

function is_logged_in(): bool
{
    return !empty($_SESSION['admin_logged_in']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function login_locked_out(): bool
{
    $attempts = $_SESSION['login_attempts'] ?? 0;
    $lastAttempt = $_SESSION['login_last_attempt'] ?? 0;
    if ($attempts >= LOGIN_MAX_ATTEMPTS && (time() - $lastAttempt) < LOGIN_LOCKOUT_SECONDS) {
        return true;
    }
    if ($attempts >= LOGIN_MAX_ATTEMPTS && (time() - $lastAttempt) >= LOGIN_LOCKOUT_SECONDS) {
        $_SESSION['login_attempts'] = 0;
    }
    return false;
}

function register_failed_login(): void
{
    $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
    $_SESSION['login_last_attempt'] = time();
}

function register_successful_login(): void
{
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['login_attempts'] = 0;
    session_regenerate_id(true);
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_check(?string $token): bool
{
    return is_string($token) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function set_flash_old(array $data, string $error): void
{
    $_SESSION['flash_old'] = $data;
    $_SESSION['flash_error'] = $error;
}

function get_and_clear_flash(): array
{
    $old = $_SESSION['flash_old'] ?? null;
    $error = $_SESSION['flash_error'] ?? '';
    unset($_SESSION['flash_old'], $_SESSION['flash_error']);
    return ['old' => $old, 'error' => $error];
}
