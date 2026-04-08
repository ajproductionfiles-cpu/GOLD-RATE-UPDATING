<?php

declare(strict_types=1);

require_once __DIR__ . '/layout.php';

$user = requireAdmin();
$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $name = trim($_POST['name'] ?? '');
        $fields = trim($_POST['custom_fields'] ?? '');
        if ($name === '') {
            throw new RuntimeException('Category name required.');
        }
        $stmt = $pdo->prepare('INSERT INTO categories(name, custom_fields) VALUES(?, ?)');
        $stmt->execute([$name, $fields]);
        flash('success', 'Category added.');
    } catch (Throwable $e) {
        flash('error', $e->getMessage());
    }
    header('Location: categories.php');
    exit;
}

$categories = $pdo->query('SELECT * FROM categories ORDER BY created_at DESC')->fetchAll();
renderHeader('Categories', $user);
?>
<div class="card">
    <h2>Add Jewellery Category</h2>
    <form method="post">
        <div class="grid">
            <div class="field"><label>Category name</label><input name="name" required></div>
            <div class="field"><label>Custom detail fields (comma separated)</label><input name="custom_fields" placeholder="metal,color,stone"></div>
        </div>
        <button type="submit">Add Category</button>
    </form>
</div>
<div class="card">
    <h3>Existing Categories</h3>
    <ul>
        <?php foreach ($categories as $cat): ?>
            <li><strong><?= htmlspecialchars($cat['name']) ?></strong> — <?= htmlspecialchars($cat['custom_fields']) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php renderFooter(); ?>
