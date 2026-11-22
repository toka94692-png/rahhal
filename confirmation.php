<?php
// Booking confirmation page, reached after a successful booking.
require __DIR__ . '/includes/init.php';
require_login();

$user = current_user();
$code = $_GET['code'] ?? '';

$stmt = db()->prepare('SELECT * FROM bookings WHERE confirmation_code = ? AND user_id = ?');
$stmt->execute([$code, $user['id']]);
$booking = $stmt->fetch();

$PAGE_TITLE = 'Booking confirmed';
$BASE_PATH = '';
include __DIR__ . '/includes/header.php';

if (!$booking): ?>
    <div class="alert alert-danger">Booking not found.</div>
<?php else:
    $item = get_item($booking['item_type'], (int)$booking['item_id']);
    $cfg = type_config($booking['item_type']);
    ?>
    <div class="card shadow-sm text-center py-5">
        <div class="card-body">
            <div class="display-4 mb-2">✅</div>
            <h1 class="h3">Your booking is confirmed!</h1>
            <p class="text-muted">Show this confirmation code when you arrive:</p>
            <div class="confirmation-code text-primary"><?= e($booking['confirmation_code']) ?></div>
            <hr class="my-4">
            <p class="mb-1">
                <strong><?= $cfg['icon'] ?> <?= e($item ? item_title($booking['item_type'], $item) : 'Item') ?></strong>
            </p>
            <p class="mb-1">Date: <?= e($booking['booking_date']) ?> · <?= e($cfg['qty_label']) ?>: <?= (int)$booking['quantity'] ?></p>
            <p class="mb-4">Total paid at arrival: <strong><?= money($booking['total_price']) ?></strong></p>
            <a href="my_bookings.php" class="btn btn-primary">Go to My Bookings</a>
            <a href="index.php" class="btn btn-outline-secondary">Back home</a>
        </div>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
