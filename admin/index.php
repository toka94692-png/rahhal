<?php
// Admin dashboard.
require __DIR__ . '/../includes/init.php';
require_admin('../');

$PAGE_TITLE = 'Admin';
$BASE_PATH = '../';
include __DIR__ . '/../includes/header.php';

$counts = [];
foreach (type_config() as $type => $cfg) {
    $counts[$type] = db()->query('SELECT COUNT(*) FROM ' . $cfg['table'])->fetchColumn();
}
$bookingCount = db()->query('SELECT COUNT(*) FROM bookings')->fetchColumn();
$userCount = db()->query('SELECT COUNT(*) FROM users')->fetchColumn();
?>

<h1 class="h3 mb-4">Admin dashboard</h1>

<div class="row g-3 mb-5">
    <?php foreach (type_config() as $type => $cfg): ?>
        <div class="col-6 col-md-4 col-lg-2">
            <a href="items.php?type=<?= e($type) ?>" class="text-decoration-none text-dark">
                <div class="card text-center p-3 h-100">
                    <div class="fs-2"><?= $cfg['icon'] ?></div>
                    <div class="display-6"><?= (int)$counts[$type] ?></div>
                    <div class="small text-muted"><?= e($cfg['plural']) ?></div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <a href="bookings.php" class="text-decoration-none text-dark">
            <div class="card p-3 text-center">
                <div class="display-6"><?= (int)$bookingCount ?></div>
                <div class="small text-muted">Bookings - click to manage</div>
            </div>
        </a>
    </div>
    <div class="col-md-6">
        <div class="card p-3 text-center">
            <div class="display-6"><?= (int)$userCount ?></div>
            <div class="small text-muted">Registered users</div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
