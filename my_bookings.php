<?php
// The logged-in user's bookings.
require __DIR__ . '/includes/init.php';
require_login();

$user = current_user();
$stmt = db()->prepare('SELECT * FROM bookings WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$user['id']]);
$bookings = $stmt->fetchAll();

$PAGE_TITLE = 'My Bookings';
$BASE_PATH = '';
include __DIR__ . '/includes/header.php';
?>

<h1 class="h3 mb-4">My Bookings</h1>

<?php if (!$bookings): ?>
    <div class="alert alert-info">You have no bookings yet. Start exploring!</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle bg-white shadow-sm">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Service</th>
                    <th>Item</th>
                    <th>Date</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($bookings as $b):
                $cfg = type_config($b['item_type']);
                $item = get_item($b['item_type'], (int)$b['item_id']);
                ?>
                <tr>
                    <td><code><?= e($b['confirmation_code']) ?></code></td>
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
                    <td>
                        <?php if ($b['status'] === 'confirmed'): ?>
                            <form method="post" action="cancel_booking.php" class="confirm-delete m-0">
                                <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
                                <button class="btn btn-outline-danger btn-sm" type="submit">Cancel</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
