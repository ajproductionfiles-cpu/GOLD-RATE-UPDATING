<?php

declare(strict_types=1);

require_once __DIR__ . '/layout.php';

$token = trim($_GET['token'] ?? $_POST['token'] ?? '');
if ($token === '') {
    exit('Missing token.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $password = $_POST['password'] ?? '';
        if (strlen($password) < 6) {
            throw new RuntimeException('Min 6 character password required.');
        }

        $stmt = db()->prepare('SELECT * FROM password_resets WHERE token = ? AND used = 0');
        $stmt->execute([$token]);
        $row = $stmt->fetch();

        if (!$row || strtotime($row['expires_at']) < time()) {
            throw new RuntimeException('Token invalid or expired.');
        }

        $up = db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
        $up->execute([password_hash($password, PASSWORD_DEFAULT), (int) $row['user_id']]);

        $mark = db()->prepare('UPDATE password_resets SET used = 1 WHERE id = ?');
        $mark->execute([(int) $row['id']]);

        flash('success', 'Password updated. Please login.');
        header('Location: index.php');
        exit;
    } catch (Throwable $e) {
        flash('error', $e->getMessage());
    }
}

renderHeader('Reset Password', null);
?>
<div class="card" style="max-width:520px;margin:auto;">
    <h2>Reset Password</h2>
    <form method="post">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <div class="field"><label>New password</label><input type="password" name="password" minlength="6" required></div>
        <button type="submit">Update Password</button>
    </form>
</div>
<?php renderFooter(); ?>
