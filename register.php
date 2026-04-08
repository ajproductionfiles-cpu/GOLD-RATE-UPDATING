<?php

declare(strict_types=1);

require_once __DIR__ . '/layout.php';

if (setting('allow_registration', '1') !== '1') {
    exit('Registration disabled by admin.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $name = trim($_POST['name'] ?? '');
        $email = trim(strtolower($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
            throw new RuntimeException('Please provide valid name/email and minimum 6 character password.');
        }

        $photoPath = null;
        if (!empty($_FILES['photo']['name'])) {
            $photoPath = saveUpload($_FILES['photo']);
        }

        $stmt = db()->prepare('INSERT INTO users(name, email, password_hash, role, photo_path) VALUES(?, ?, ?, "user", ?)');
        $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), $photoPath]);

        flash('success', 'Account created. Please login.');
        header('Location: index.php');
        exit;
    } catch (Throwable $e) {
        flash('error', $e->getMessage());
    }
}

renderHeader('Register', null);
?>
<div class="card" style="max-width:520px;margin:auto;">
    <h2>Create User</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="field"><label>Name</label><input type="text" name="name" required></div>
        <div class="field"><label>Email</label><input type="email" name="email" required></div>
        <div class="field"><label>Password</label><input type="password" name="password" minlength="6" required></div>
        <div class="field"><label>Profile photo (camera/gallery)</label><input type="file" name="photo" accept="image/*" capture="environment"></div>
        <button type="submit">Register</button>
    </form>
</div>
<?php renderFooter(); ?>
