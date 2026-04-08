<?php

declare(strict_types=1);

require_once __DIR__ . '/layout.php';

$user = requireAdmin();
$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $portalName = trim($_POST['portal_name'] ?? 'Jewellery Ads Portal');
    $allow = isset($_POST['allow_registration']) ? '1' : '0';

    $stmt = $pdo->prepare('REPLACE INTO settings(setting_key, setting_value) VALUES(?, ?)');
    $stmt->execute(['portal_name', $portalName]);
    $stmt->execute(['allow_registration', $allow]);

    flash('success', 'Settings updated.');
    header('Location: settings.php');
    exit;
}

renderHeader('Settings', $user);
?>
<div class="card">
    <h2>Portal Settings</h2>
    <form method="post">
        <div class="field"><label>Portal name</label><input name="portal_name" value="<?= htmlspecialchars(setting('portal_name')) ?>"></div>
        <div class="field"><label><input type="checkbox" name="allow_registration" value="1" <?= setting('allow_registration', '1') === '1' ? 'checked' : '' ?>> Allow new user registration</label></div>
        <button type="submit">Save</button>
    </form>
</div>
<?php renderFooter(); ?>
