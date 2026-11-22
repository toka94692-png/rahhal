<?php
// Global search across all services, from the home page hero.
require __DIR__ . '/includes/init.php';
$q = trim($_GET['q'] ?? '');
$PAGE_TITLE = 'Search';
$BASE_PATH = '';
include __DIR__ . '/includes/header.php';
?>

<h1 class="h3 mb-4">Results for "<?= e($q) ?>"</h1>

<?php
$foundAny = false;
foreach (type_config() as $type => $cfg):
    $results = list_items($type, ['q' => $q]);
    if (!$results) continue;
    $foundAny = true;
    ?>
    <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
        <h2 class="h5 mb-0"><?= $cfg['icon'] ?> <?= e($cfg['plural']) ?></h2>
        <a class="btn btn-outline-primary btn-sm" href="<?= $type ?>s.php?q=<?= urlencode($q) ?>">Filter these</a>
    </div>
    <div class="row">
        <?php foreach (array_slice($results, 0, 3) as $row): ?>
            <?php render_card($type, $row); ?>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>

<?php if (!$foundAny): ?>
    <div class="alert alert-info">Nothing matches your search. Try another city or place.</div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
