<?php

declare(strict_types=1);

require_once __DIR__ . '/layout.php';

$resetLink = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim(strtolower($_POST['email'] ?? ''));
    $stmt = db()->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $uid = $stmt->fetchColumn();

    if ($uid) {
        $token = bin2hex(random_bytes(20));
        $expires = (new DateTime('+30 minutes'))->format('Y-m-d H:i:s');
        $ins = db()->prepare('INSERT INTO password_resets(user_id, token, expires_at) VALUES(?, ?, ?)');
        $ins->execute([(int) $uid, $token, $expires]);
        $resetLink = APP_URL . '/reset_password.php?token=' . urlencode($token);
    }
    flash('success', 'If account exists, reset link generated below (demo mode).');
}

renderHeader('Forgot Password', null);
?>
<div class="card" style="max-width:520px;margin:auto;">
    <h2>Forgot Password</h2>
    <form method="post">
        <div class="field"><label>Registered email</label><input type="email" name="email" required></div>
        <button type="submit">Generate Reset Link</button>
    </form>
    <?php if ($resetLink): ?>
        <p><small>Reset URL:</small><br><a href="<?= htmlspecialchars($resetLink) ?>"><?= htmlspecialchars($resetLink) ?></a></p>
    <?php endif; ?>
</div>
<?php renderFooter(); ?>
