<?php
// Session + login helpers.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// The logged-in user row, or null.
function current_user(): ?array {
    if (empty($_SESSION['user_id'])) return null;
    $stmt = db()->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    return $user ?: null;
}

// Pages that need a logged-in user call this.
// $base is '' on main pages and '../' inside admin/.
function require_login(string $base = ''): void {
    if (empty($_SESSION['user_id'])) {
        flash_set('Please log in first.', 'warning');
        header('Location: ' . $base . 'login.php');
        exit;
    }
}

// Admin pages call this.
function require_admin(string $base = '../'): void {
    $user = current_user();
    if (!$user || !$user['is_admin']) {
        flash_set('Admin access only.', 'danger');
        header('Location: ' . $base . 'index.php');
        exit;
    }
}
