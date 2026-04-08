<?php

declare(strict_types=1);

require_once __DIR__ . '/layout.php';

$user = requireLogin();
$pdo = db();
$results = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $queryPath = saveUpload($_FILES['query_image']);
        $queryHash = createImageHash(__DIR__ . '/' . $queryPath);

        if ($queryHash === null) {
            throw new RuntimeException('GD extension not available, visual search cannot run.');
        }

        $items = $pdo->query('SELECT j.*, c.name AS category_name FROM jewellery j JOIN categories c ON c.id = j.category_id WHERE j.image_hash IS NOT NULL')->fetchAll();
        foreach ($items as $item) {
            $item['distance'] = hammingDistance($queryHash, (string) $item['image_hash']);
            $results[] = $item;
        }

        usort($results, fn($a, $b) => $a['distance'] <=> $b['distance']);
        $results = array_slice($results, 0, 12);
        flash('success', 'Showing closest matching jewellery items.');
    } catch (Throwable $e) {
        flash('error', $e->getMessage());
    }
}

renderHeader('Search Similar Jewellery', $user);
?>
<div class="card">
    <h2>Click & Search Jewellery Similarity</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="field"><label>Take/click photo to search</label><input type="file" name="query_image" accept="image/*" capture="environment" required></div>
        <button type="submit">Search Similar</button>
    </form>
</div>
<?php if ($results): ?>
<div class="card">
    <h3>Search Results</h3>
    <div class="grid">
        <?php foreach ($results as $item): ?>
            <div class="gallery-item card">
                <img src="<?= htmlspecialchars($item['user_photo_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                <p><strong><?= htmlspecialchars($item['title']) ?></strong></p>
                <p><span class="tag"><?= htmlspecialchars($item['category_name']) ?></span><span class="tag"><?= htmlspecialchars($item['karat']) ?></span></p>
                <p>Similarity score: <?= max(0, 100 - (int) $item['distance']) ?>%</p>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
<?php renderFooter(); ?>
