<?php
require __DIR__ . '/includes/init.php';
$PAGE_TITLE = 'Home';
$BASE_PATH = '';
$featuredHomes = array_slice(list_items('home'), 0, 3);
include __DIR__ . '/includes/header.php';
?>

<section class="hero mb-5">
    <h1 class="display-5">Travel the world, live the culture</h1>
    <p class="lead mb-0">Flights, hotels, hosted homes, restaurants and attractions - all in one place.</p>
    <form class="hero-search d-flex" method="get" action="search.php">
        <input class="form-control form-control-lg me-2" type="search" name="q"
               placeholder="Where do you want to go? (e.g. Aswan, Istanbul)" required>
        <button class="btn btn-warning btn-lg" type="submit">Search</button>
    </form>
</section>

<h2 class="h4 mb-3">What are you looking for?</h2>
<div class="row g-3 mb-5">
    <?php foreach (type_config() as $key => $cfg): ?>
        <div class="col-6 col-md">
            <a class="text-decoration-none text-dark" href="<?= $key ?>s.php">
                <div class="card category-card text-center p-3 h-100">
                    <div class="emoji"><?= $cfg['icon'] ?></div>
                    <div class="fw-semibold mt-2"><?= e($cfg['plural']) ?></div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h4 mb-0">Live the local life: hosted homes</h2>
    <a href="homes.php" class="btn btn-outline-primary btn-sm">See all</a>
</div>
<div class="row">
    <?php foreach ($featuredHomes as $home): ?>
        <?php render_card('home', $home); ?>
    <?php endforeach; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
