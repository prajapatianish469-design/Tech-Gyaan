<?php
require_once __DIR__ . '/../app/config/db.php';
require_once __DIR__ . '/../app/lib/helpers.php';
if (session_status() === PHP_SESSION_NONE) session_start();
require_student();

$pdo = db();
$materials = $pdo->query("SELECT * FROM materials ORDER BY uploaded_at DESC")->fetchAll();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Materials</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="container mt-4">
  <h2>Available Materials</h2>
  <ul class="list-group">
    <?php foreach($materials as $m): ?>
    <li class="list-group-item">
      <?= h($m['title']) ?> — <?= h(ucfirst($m['type'])) ?>
      <a href="<?= APP_URL ?>/uploads/<?= h($m['file_name']) ?>" target="_blank" class="btn btn-sm btn-outline-primary float-end">Download</a>
    </li>
    <?php endforeach; ?>
  </ul>
</body>
</html>
