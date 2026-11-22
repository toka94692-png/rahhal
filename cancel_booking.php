<?php
// Cancel one of the logged-in user's bookings.
require __DIR__ . '/includes/init.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = current_user();
    $stmt = db()->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ? AND user_id = ?");
    $stmt->execute([(int)($_POST['id'] ?? 0), $user['id']]);
    flash_set('Booking cancelled.');
}
header('Location: my_bookings.php');
exit;
