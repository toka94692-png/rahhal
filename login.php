<?php
require __DIR__ . '/includes/init.php';

// Already logged in? Go home.
if (current_user()) {
    header('Location: index.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = db()->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        flash_set('Welcome back, ' . $user['name'] . '!');
        header('Location: index.php');
        exit;
    }
    $errors[] = 'Wrong email or password.';
}

$PAGE_TITLE = 'Login';
$BASE_PATH = '';
include __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-4 text-center">Login</h1>

                <?php foreach ($errors as $error): ?>
                    <div class="alert alert-danger"><?= e($error) ?></div>
                <?php endforeach; ?>

                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input class="form-control" type="email" name="email" required
                               value="<?= e($_POST['email'] ?? '') ?>">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <input class="form-control" type="password" name="password" required>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Login</button>
                </form>

                <p class="text-center text-muted small mt-3 mb-0">
                    No account yet? <a href="register.php">Register</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
