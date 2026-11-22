<?php
// Admin: add or edit one item (any type).
require __DIR__ . '/../includes/init.php';
require_admin('../');

$type = $_GET['type'] ?? $_POST['type'] ?? '';
$cfg = type_config($type);
if (!$cfg) {
    flash_set('Unknown type.', 'danger');
    header('Location: index.php');
    exit;
}

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$item = $id ? get_item($type, $id) : null;
if ($id && !$item) {
    flash_set('Item not found.', 'danger');
    header('Location: items.php?type=' . urlencode($type));
    exit;
}

$numericCols = ['price', 'price_per_night', 'avg_price', 'ticket_price', 'rating', 'seats'];
$textareaCols = ['description', 'experience_description'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values = [];
    foreach ($cfg['fields'] as $col => $label) {
        $value = trim($_POST[$col] ?? '');
        if (in_array($col, $numericCols)) {
            $value = $value === '' ? 0 : (float)$value;
            if ($col === 'seats') $value = (int)$value;
        }
        $values[$col] = $value;
    }

    if ($item) {
        $set = implode(', ', array_map(fn($col) => "$col = ?", array_keys($values)));
        $stmt = db()->prepare('UPDATE ' . $cfg['table'] . " SET $set WHERE id = ?");
        $stmt->execute([...array_values($values), $id]);
        flash_set($cfg['label'] . ' updated.');
    } else {
        $cols = implode(', ', array_keys($values));
        $marks = implode(', ', array_fill(0, count($values), '?'));
        $stmt = db()->prepare('INSERT INTO ' . $cfg['table'] . " ($cols) VALUES ($marks)");
        $stmt->execute(array_values($values));
        flash_set($cfg['label'] . ' added.');
    }
    header('Location: items.php?type=' . urlencode($type));
    exit;
}

$PAGE_TITLE = ($item ? 'Edit ' : 'Add ') . $cfg['label'];
$BASE_PATH = '../';
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><?= ($item ? 'Edit' : 'Add new') ?> <?= e($cfg['label']) ?></h1>
    <a href="items.php?type=<?= e($type) ?>" class="btn btn-outline-secondary btn-sm">Back to list</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="post">
            <input type="hidden" name="type" value="<?= e($type) ?>">
            <input type="hidden" name="id" value="<?= (int)$id ?>">

            <?php foreach ($cfg['fields'] as $col => $label):
                $value = $item[$col] ?? '';
                ?>
                <div class="mb-3">
                    <label class="form-label"><?= e($label) ?></label>
                    <?php if (in_array($col, $textareaCols)): ?>
                        <textarea class="form-control" name="<?= e($col) ?>" rows="4"><?= e($value) ?></textarea>
                    <?php elseif ($col === 'flight_date'): ?>
                        <input class="form-control" type="date" name="<?= e($col) ?>" value="<?= e($value) ?>" required>
                    <?php elseif (in_array($col, $numericCols)): ?>
                        <input class="form-control" type="number" step="any" min="0" name="<?= e($col) ?>" value="<?= e($value) ?>" required>
                    <?php else: ?>
                        <input class="form-control" name="<?= e($col) ?>" value="<?= e($value) ?>" required>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <button class="btn btn-primary" type="submit"><?= $item ? 'Save changes' : 'Add' ?></button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
