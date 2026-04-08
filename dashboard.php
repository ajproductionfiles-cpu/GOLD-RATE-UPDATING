<?php

declare(strict_types=1);

require_once __DIR__ . '/layout.php';

$user = requireLogin();
$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $title = trim($_POST['title'] ?? '');
        $categoryId = (int) ($_POST['category_id'] ?? 0);
        $karat = trim($_POST['karat'] ?? '');
        $details = trim($_POST['details'] ?? '');
        $custom = [];
        foreach ($_POST as $k => $v) {
            if (str_starts_with((string) $k, 'cf_') && trim((string) $v) !== '') {
                $custom[substr((string) $k, 3)] = trim((string) $v);
            }
        }
        if ($custom) {
            $details .= ($details !== '' ? "\n" : '') . 'Custom: ' . json_encode($custom, JSON_UNESCAPED_UNICODE);
        }

        if ($title === '' || $karat === '' || $categoryId <= 0) {
            throw new RuntimeException('Title, category and karat are required.');
        }

        $photoPath = saveUpload($_FILES['jewellery_photo']);
        $hash = createImageHash(__DIR__ . '/' . $photoPath);

        $stmt = $pdo->prepare('INSERT INTO jewellery(user_id, category_id, title, karat, details, user_photo_path, image_hash) VALUES(?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([(int) $user['id'], $categoryId, $title, $karat, $details, $photoPath, $hash]);

        flash('success', 'Jewellery entry submitted.');
        header('Location: gallery.php');
        exit;
    } catch (Throwable $e) {
        flash('error', $e->getMessage());
    }
}

$categories = $pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();

renderHeader('Dashboard', $user);
?>
<div class="card">
    <h2>Add Jewellery for Ads</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="grid">
            <div class="field"><label>Jewellery title</label><input type="text" name="title" required></div>
            <div class="field"><label>Jewellery category</label>
                <select name="category_id" id="categorySelect" required>
                    <option value="">Select category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= (int) $cat['id'] ?>" data-fields="<?= htmlspecialchars($cat['custom_fields']) ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field"><label>Jewellery karat</label><input type="text" name="karat" placeholder="e.g. 18K" required></div>
            <div class="field"><label>User photo with jewellery (camera)</label><input type="file" name="jewellery_photo" accept="image/*" capture="environment" required></div>
        </div>
        <div class="field" id="dynamicFields"></div>
        <div class="field"><label>Other details</label><textarea name="details" rows="4" placeholder="Design, weight, occasion, etc."></textarea></div>
        <button type="submit">Submit</button>
    </form>
</div>
<script>
const categorySelect = document.getElementById('categorySelect');
const dynamicFields = document.getElementById('dynamicFields');
categorySelect.addEventListener('change', () => {
  const selected = categorySelect.options[categorySelect.selectedIndex];
  const fields = (selected?.dataset?.fields || '').split(',').map(v => v.trim()).filter(Boolean);
  dynamicFields.innerHTML = '';
  fields.forEach((f) => {
    const div = document.createElement('div');
    div.className = 'field';
    div.innerHTML = `<label>${f}</label><input type="text" name="cf_${f}">`;
    dynamicFields.appendChild(div);
  });
});
</script>
<?php renderFooter(); ?>
