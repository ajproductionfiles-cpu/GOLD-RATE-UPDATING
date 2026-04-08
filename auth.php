<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

function currentUser(): ?array
{
    if (empty($_SESSION['user_id'])) {
        return null;
    }

    $stmt = db()->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([(int) $_SESSION['user_id']]);

    return $stmt->fetch() ?: null;
}

function requireLogin(): array
{
    $user = currentUser();
    if ($user === null) {
        header('Location: index.php');
        exit;
    }

    return $user;
}

function requireAdmin(): array
{
    $user = requireLogin();
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        exit('Forbidden: Admin only');
    }

    return $user;
}

function login(string $email, string $password): bool
{
    $stmt = db()->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([trim(strtolower($email))]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        return false;
    }

    $_SESSION['user_id'] = (int) $user['id'];
    session_regenerate_id(true);

    return true;
}

function logout(): void
{
    $_SESSION = [];
    session_destroy();
}

function setting(string $key, string $default = ''): string
{
    $stmt = db()->prepare('SELECT setting_value FROM settings WHERE setting_key = ?');
    $stmt->execute([$key]);
    $val = $stmt->fetchColumn();

    return $val === false ? $default : (string) $val;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function pullFlash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function createImageHash(string $filePath): ?string
{
    if (!extension_loaded('gd')) {
        return null;
    }

    $img = @imagecreatefromstring((string) file_get_contents($filePath));
    if (!$img) {
        return null;
    }

    $resized = imagecreatetruecolor(8, 8);
    imagecopyresampled($resized, $img, 0, 0, 0, 0, 8, 8, imagesx($img), imagesy($img));
    imagefilter($resized, IMG_FILTER_GRAYSCALE);

    $pixels = [];
    $sum = 0;
    for ($y = 0; $y < 8; $y++) {
        for ($x = 0; $x < 8; $x++) {
            $rgb = imagecolorat($resized, $x, $y) & 0xFF;
            $pixels[] = $rgb;
            $sum += $rgb;
        }
    }

    $avg = $sum / 64;
    $hash = '';
    foreach ($pixels as $pix) {
        $hash .= $pix >= $avg ? '1' : '0';
    }

    imagedestroy($img);
    imagedestroy($resized);

    return $hash;
}

function hammingDistance(string $a, string $b): int
{
    $len = min(strlen($a), strlen($b));
    $dist = abs(strlen($a) - strlen($b));

    for ($i = 0; $i < $len; $i++) {
        if ($a[$i] !== $b[$i]) {
            $dist++;
        }
    }

    return $dist;
}

function saveUpload(array $upload): string
{
    if (($upload['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Image upload failed.');
    }

    if (($upload['size'] ?? 0) > MAX_UPLOAD_BYTES) {
        throw new RuntimeException('Image too large. Maximum 5MB allowed.');
    }

    $tmp = $upload['tmp_name'];
    $mime = mime_content_type($tmp);
    $ext = match ($mime) {
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        default => throw new RuntimeException('Only JPG/PNG/WEBP files are allowed.'),
    };

    $name = bin2hex(random_bytes(16)) . '.' . $ext;
    $target = UPLOAD_DIR . '/' . $name;

    if (!move_uploaded_file($tmp, $target)) {
        throw new RuntimeException('Could not store uploaded image.');
    }

    return 'public/uploads/' . $name;
}
