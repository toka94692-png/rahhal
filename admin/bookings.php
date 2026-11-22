<?php
// Admin: all bookings with the user who made each one.
require __DIR__ . '/../includes/init.php';
require_admin('../');

$stmt = db()->query(
    'SELECT bookings.*, users.name AS user_name, users.email AS user_email
     FROM bookings JOIN users ON users.id = bookings.user_id
     ORDER BY bookings.created_at DESC'
);
$bookings = $stmt->fetchAll();

$PAGE_TITLE = 'Admin: Bookings';
$BASE_PATH = '../';
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">All bookings</h1>
    <a href="index.php" class="btn btn-outline-secondary btn-sm">Dashboard</a>
</div>

<div class="table-responsive">
    <table class="table table-striped align-middle bg-white shadow-sm">
        <thead>
            <tr>
                <th>Code</th>
                <th>User</th>
                <th>Service</th>
                <th>Item</th>
                <th>Date</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($bookings as $b):
            $cfg = type_config($b['item_type']);
            $item = get_item($b['item_type'], (int)$b['item_id']);
            ?>
            <tr>
                <td><code><?= e($b['confirmation_code']) ?></code></td>
                <td><?= e($b['user_name']) ?><br><small class="text-muted"><?= e($b['user_email']) ?></small></td>
                <td><?= $cfg['icon'] ?> <?= e($cfg['label']) ?></td>
                <td><?= $item ? e(item_title($b['item_type'], $item)) : '<em class="text-muted">removed</em>' ?></td>
                <td><?= e($b['booking_date']) ?></td>
                <td><?= (int)$b['quantity'] ?></td>
                <td><?= money($b['total_price']) ?></td>
                <td>
                    <?php if ($b['status'] === 'confirmed'): ?>
                        <span class="badge bg-success">Confirmed</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Cancelled</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
