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
<head><meta charset="utf-8"><title>Student Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="container mt-4">
  <div class="d-flex justify-content-between align-items-center">
    <h2>Welcome, <?= h($_SESSION['student_name'] ?? 'Student') ?></h2>
    <a href="login.php?logout=1" class="btn btn-danger">Logout</a>
  </div>

  <h4 class="mt-3">Study Materials</h4>
  <ul class="list-group">
    <?php foreach($materials as $m): ?>
      <li class="list-group-item">
        <?= h($m['title']) ?> (<?= h(ucfirst($m['type'])) ?>)
        <a href="<?= APP_URL ?>/uploads/<?= h($m['file_name']) ?>" target="_blank" class="btn btn-sm btn-outline-primary float-end">Download</a>
      </li>
    <?php endforeach; ?>
  </ul>
</body>
</html>
