<?php

declare(strict_types=1);

require_once __DIR__ . '/layout.php';

$user = requireLogin();
$pdo = db();

$sql = 'SELECT j.*, c.name AS category_name, u.name AS uploader
        FROM jewellery j
        JOIN categories c ON c.id = j.category_id
        JOIN users u ON u.id = j.user_id
        ORDER BY j.created_at DESC';
$items = $pdo->query($sql)->fetchAll();

renderHeader('Gallery', $user);
?>
<div class="card">
    <h2>Uploaded Jewellery Gallery</h2>
    <div class="grid">
        <?php foreach ($items as $item): ?>
            <div class="gallery-item card">
                <img src="<?= htmlspecialchars($item['user_photo_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                <h3><?= htmlspecialchars($item['title']) ?></h3>
                <p><span class="tag"><?= htmlspecialchars($item['category_name']) ?></span><span class="tag"><?= htmlspecialchars($item['karat']) ?></span></p>
                <p><?= nl2br(htmlspecialchars($item['details'])) ?></p>
                <small>By <?= htmlspecialchars($item['uploader']) ?> at <?= htmlspecialchars($item['created_at']) ?></small>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php renderFooter(); ?>
