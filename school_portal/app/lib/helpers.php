<?php
// helpers.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function flash(string $key, ?string $message = null) {
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return;
    }
    $msg = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $msg;
}

function redirect(string $url) {
    header("Location: $url");
    exit;
}

function require_admin() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['admin_id'])) {
        header('Location: ' . APP_URL . '/admin/login.php');
        exit;
    }
}

function require_student() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['student_id'])) {
        header('Location: ' . APP_URL . '/student/login.php');
        exit;
    }
}

function random_password(int $len = 10): string {
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789@#%!';
    $out = '';
    for ($i = 0; $i < $len; $i++) {
        $out .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $out;
}

function generate_student_uid(PDO $pdo): string {
    $yy = date('y');
    $row = $pdo->query("SELECT AUTO_INCREMENT AS next_id FROM information_schema.TABLES WHERE TABLE_SCHEMA='".DB_NAME."' AND TABLE_NAME='students'")->fetch();
    $next = (int)($row['next_id'] ?? 1);
    return sprintf('STU%s-%04d', $yy, $next);
}

function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function dmy(?string $mysqlDate): string {
    if (empty($mysqlDate)) return '—';
    return date('d/m/Y', strtotime($mysqlDate));
}
