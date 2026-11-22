<?php
// Booking form + saving the booking. Login is required.
require __DIR__ . '/includes/init.php';
require_login();

$type = $_GET['type'] ?? $_POST['type'] ?? '';
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$cfg = type_config($type);
$item = $cfg ? get_item($type, $id) : null;

if (!$item) {
    flash_set('Item not found.', 'danger');
    header('Location: index.php');
    exit;
}

$user = current_user();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookingDate = trim($_POST['booking_date'] ?? '');
    $quantity = (int)($_POST['quantity'] ?? 1);

    if ($bookingDate === '') {
        $errors[] = 'Please choose a date.';
    }
    if ($quantity < 1) {
        $quantity = 1;
    }

    if (!$errors) {
        $total = item_price($type, $item) * $quantity;
        $code = 'RH-' . strtoupper(substr(md5(uniqid((string)mt_rand(), true)), 0, 8));

        $stmt = db()->prepare(
            'INSERT INTO bookings (user_id, item_type, item_id, booking_date, quantity, total_price, confirmation_code)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$user['id'], $type, $id, $bookingDate, $quantity, $total, $code]);

        header('Location: confirmation.php?code=' . urlencode($code));
        exit;
    }
}

$PAGE_TITLE = 'Book: ' . item_title($type, $item);
$BASE_PATH = '';
$unitLabel = $cfg['unit'] === 'night' ? 'nights' : 'people';
include __DIR__ . '/includes/header.php';
?>

<h1 class="h3 mb-4">Book: <?= $cfg['icon'] ?> <?= e(item_title($type, $item)) ?></h1>

<?php foreach ($errors as $error): ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
<?php endforeach; ?>

<div class="row">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="post">
                    <input type="hidden" name="type" value="<?= e($type) ?>">
                    <input type="hidden" name="id" value="<?= (int)$id ?>">

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input class="form-control" value="<?= e($user['name']) ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="booking_date">
                            <?= $cfg['unit'] === 'night' ? 'Check-in date' : 'Date' ?>
                        </label>
                        <input class="form-control" type="date" id="booking_date" name="booking_date"
                               min="<?= date('Y-m-d') ?>" required
                               value="<?= e($_POST['booking_date'] ?? ($type === 'flight' ? $item['flight_date'] : '')) ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="quantity"><?= e($cfg['qty_label']) ?></label>
                        <input class="form-control" type="number" id="quantity" name="quantity"
                               min="1" value="<?= max(1, (int)($_POST['quantity'] ?? 1)) ?>"
                               data-price="<?= e(item_price($type, $item)) ?>" required>
                        <div class="form-text">Price is <?= money(item_price($type, $item)) ?> per <?= $cfg['unit'] === 'night' ? 'night' : 'person' ?>.</div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <strong>Total</strong>
                        <strong class="text-primary fs-4" id="total-price"></strong>
                    </div>

                    <button class="btn btn-primary btn-lg w-100" type="submit">Confirm booking</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h2 class="h6 text-muted">Summary</h2>
                <p class="mb-1"><strong><?= e(item_title($type, $item)) ?></strong></p>
                <p class="mb-0 text-muted small"><?= e(item_location($type, $item)) ?></p>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
