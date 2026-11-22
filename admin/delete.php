<?php
// Admin: delete one item.
require __DIR__ . '/../includes/init.php';
require_admin('../');

$type = $_POST['type'] ?? '';
$cfg = type_config($type);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $cfg) {
    $stmt = db()->prepare('DELETE FROM ' . $cfg['table'] . ' WHERE id = ?');
    $stmt->execute([(int)($_POST['id'] ?? 0)]);
    flash_set($cfg['label'] . ' deleted.');
    header('Location: items.php?type=' . urlencode($type));
    exit;
}

header('Location: index.php');
exit;
