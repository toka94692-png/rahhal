<?php
// Page header + navbar. Pages set $PAGE_TITLE and $BASE_PATH ('' or '../') first.
$user = current_user();
$BASE = $BASE_PATH ?? '';
$types = type_config();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($PAGE_TITLE ?? SITE_NAME) ?> | <?= SITE_NAME ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?= $BASE ?>assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= $BASE ?>index.php">🌍 <?= SITE_NAME ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="<?= $BASE ?>flights.php">Flights</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $BASE ?>hotels.php">Hotels</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $BASE ?>homes.php">Hosted Homes</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $BASE ?>restaurants.php">Restaurants</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $BASE ?>attractions.php">Attractions</a></li>
            </ul>
            <ul class="navbar-nav">
                <?php if ($user): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= $BASE ?>my_bookings.php">My Bookings</a></li>
                    <?php if ($user['is_admin']): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= $BASE ?>admin/index.php">Admin</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><span class="nav-link disabled">Hi, <?= e($user['name']) ?></span></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $BASE ?>logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= $BASE ?>login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $BASE ?>register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<main class="container py-4">
<?php if ($flash = flash_get()): ?>
    <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show">
        <?= e($flash['msg']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
