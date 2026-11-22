<?php
// Details page for any item: details.php?type=flight&id=1
require __DIR__ . '/includes/init.php';

$type = $_GET['type'] ?? '';
$id = (int)($_GET['id'] ?? 0);
$cfg = type_config($type);
$item = $cfg ? get_item($type, $id) : null;

if (!$item) {
    http_response_code(404);
    $PAGE_TITLE = 'Not found';
    $BASE_PATH = '';
    include __DIR__ . '/includes/header.php';
    echo '<div class="alert alert-danger">Item not found.</div>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

$PAGE_TITLE = item_title($type, $item);
$BASE_PATH = '';
$unitLabel = $cfg['unit'] === 'night' ? 'per night' : 'per person';
include __DIR__ . '/includes/header.php';
?>

<div class="details-hero mb-4">
    <div class="fs-1"><?= $cfg['icon'] ?></div>
    <h1 class="h2 mb-1"><?= e(item_title($type, $item)) ?></h1>
    <div><?= e(item_location($type, $item)) ?></div>
</div>

<div class="row">
    <div class="col-lg-8">
        <?php if ($type === 'home'): ?>
            <div class="experience-box mb-4">
                <h2 class="h5">🏡 The experience with your host <?= e($item['host_name']) ?></h2>
                <p class="mb-0"><?= nl2br(e($item['experience_description'])) ?></p>
            </div>
        <?php elseif (!empty($item['description'])): ?>
            <p><?= nl2br(e($item['description'])) ?></p>
        <?php endif; ?>

        <table class="table">
            <tbody>
            <?php foreach ($cfg['fields'] as $col => $label): ?>
                <?php if (isset($item[$col]) && $item[$col] !== '' && $item[$col] !== null): ?>
                    <tr>
                        <th class="w-25"><?= e($label) ?></th>
                        <td>
                            <?php if (in_array($col, ['price', 'price_per_night', 'avg_price', 'ticket_price'])): ?>
                                <?= money($item[$col]) ?>
                            <?php else: ?>
                                <?= e($item[$col]) ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-6 text-primary"><?= money(item_price($type, $item)) ?></div>
                <div class="text-muted mb-3"><?= $unitLabel ?></div>
                <a href="book.php?type=<?= e($type) ?>&id=<?= (int)$item['id'] ?>" class="btn btn-primary btn-lg w-100">Book now</a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
