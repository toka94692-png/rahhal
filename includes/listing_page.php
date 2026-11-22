<?php
// Shared listing page. flights.php / hotels.php / ... set $TYPE then include this.
require_once __DIR__ . '/init.php';

$cfg = type_config($TYPE);
$filters = [
    'q' => trim($_GET['q'] ?? ''),
    'max_price' => trim($_GET['max_price'] ?? ''),
];
$items = list_items($TYPE, $filters);

$PAGE_TITLE = $cfg['plural'];
$BASE_PATH = '';
include __DIR__ . '/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><?= $cfg['icon'] ?> <?= e($cfg['plural']) ?></h1>
</div>

<?php if ($TYPE === 'home'): ?>
    <p class="text-muted">Stay with local families and live the country's customs and traditions first-hand.</p>
<?php endif; ?>

<form class="row g-2 mb-4" method="get">
    <div class="col-sm-6 col-md-5">
        <input class="form-control" type="search" name="q" value="<?= e($filters['q']) ?>"
               placeholder="Search by city or name">
    </div>
    <div class="col-sm-4 col-md-3">
        <input class="form-control" type="number" min="0" name="max_price" value="<?= e($filters['max_price']) ?>"
               placeholder="Max price (EGP)">
    </div>
    <div class="col-sm-2">
        <button class="btn btn-primary w-100" type="submit">Filter</button>
    </div>
</form>

<?php if (!$items): ?>
    <div class="alert alert-info">No results found. Try a different search.</div>
<?php endif; ?>

<div class="row">
    <?php foreach ($items as $row): ?>
        <?php render_card($TYPE, $row); ?>
    <?php endforeach; ?>
</div>

<?php include __DIR__ . '/footer.php'; ?>
