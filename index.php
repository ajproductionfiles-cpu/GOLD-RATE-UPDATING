<?php

declare(strict_types=1);

require_once __DIR__ . '/layout.php';

if (currentUser()) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (login($_POST['email'] ?? '', $_POST['password'] ?? '')) {
        flash('success', 'Welcome back!');
        header('Location: dashboard.php');
        exit;
    }
    flash('error', 'Invalid credentials.');
}

renderHeader('Login', null);
?>
<div class="card" style="max-width:420px;margin:auto;">
    <h2>Login</h2>
    <form method="post">
        <div class="field"><label>Email</label><input type="email" name="email" required></div>
        <div class="field"><label>Password</label><input type="password" name="password" required></div>
        <button type="submit">Login</button>
    </form>
    <?php if (setting('allow_registration', '1') === '1'): ?>
    <p><a href="register.php">Create account</a></p>
    <?php endif; ?>
    <p><a href="forgot_password.php">Forgot password?</a></p>
    <p><small>Default admin: admin@portal.local / Admin@123</small></p>
</div>
<?php renderFooter(); ?>
