<?php
// Admin: list all items of one type, with add / edit / delete.
require __DIR__ . '/../includes/init.php';
require_admin('../');

$type = $_GET['type'] ?? '';
$cfg = type_config($type);
if (!$cfg) {
    flash_set('Unknown type.', 'danger');
    header('Location: index.php');
    exit;
}

$items = list_items($type);

$PAGE_TITLE = 'Admin: ' . $cfg['plural'];
$BASE_PATH = '../';
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><?= $cfg['icon'] ?> Manage <?= e($cfg['plural']) ?></h1>
    <div>
        <a href="index.php" class="btn btn-outline-secondary btn-sm">Dashboard</a>
        <a href="item_form.php?type=<?= e($type) ?>" class="btn btn-primary btn-sm">+ Add new</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover align-middle bg-white shadow-sm">
        <thead>
            <tr>
                <th>ID</th>
                <?php foreach ($cfg['fields'] as $col => $label): ?>
                    <th><?= e($label) ?></th>
                <?php endforeach; ?>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $row): ?>
            <tr>
                <td><?= (int)$row['id'] ?></td>
                <?php foreach ($cfg['fields'] as $col => $label): ?>
                    <td>
                        <?php if (in_array($col, ['price', 'price_per_night', 'avg_price', 'ticket_price'])): ?>
                            <?= money($row[$col]) ?>
                        <?php else: ?>
                            <?= e(mb_strimwidth((string)($row[$col] ?? ''), 0, 60, '...')) ?>
                        <?php endif; ?>
                    </td>
                <?php endforeach; ?>
                <td class="text-nowrap">
                    <a href="item_form.php?type=<?= e($type) ?>&id=<?= (int)$row['id'] ?>" class="btn btn-outline-primary btn-sm">Edit</a>
                    <form method="post" action="delete.php" class="confirm-delete d-inline">
                        <input type="hidden" name="type" value="<?= e($type) ?>">
                        <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                        <button class="btn btn-outline-danger btn-sm" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
