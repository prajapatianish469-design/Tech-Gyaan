<?php
require_once __DIR__ . '/../app/config/db.php';
require_once __DIR__ . '/../app/lib/helpers.php';
require_admin();

$pdo = db();
$pending = $pdo->query("SELECT id, full_name, email, status FROM students WHERE status='pending' ORDER BY id DESC")->fetchAll();
$all = $pdo->query("SELECT id, full_name, email, status FROM students ORDER BY id DESC")->fetchAll();
?>



<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Manage Students</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <a href="dashboard.php" class="btn btn-secondary mb-3">⬅ Back to Dashboard</a>
  <h3>Pending Applications</h3>
  <?php if ($pending): ?>
    <ul class="list-group mb-4">
      <?php foreach ($pending as $p): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <div><?= h($p['full_name']) ?> — <?= h($p['email']) ?> <small class="text-muted">(applied <?= dmy($p['applied_at']) ?>)</small></div>
          <a class="btn btn-sm btn-primary" href="student_view.php?id=<?= $p['id'] ?>">Open</a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p class="text-muted">No pending applications.</p>
  <?php endif; ?>

  <h3>All Students</h3>
  <table class="table table-bordered">
    <thead><tr><th>ID</th><th>UID</th><th>Name</th><th>Email</th><th>Status</th><th>View</th></tr></thead>
    <tbody>
      <?php foreach ($all as $s): ?>
      <tr>
        <td><?= $s['id'] ?></td>
        <td><?= h($s['student_uid'] ?? '—') ?></td>
        <td><?= h($s['full_name']) ?></td>
        <td><?= h($s['email']) ?></td>
        <td><?= h($s['status']) ?></td>
        <td><a class="btn btn-sm btn-outline-primary" href="student_view.php?id=<?= $s['id'] ?>">Open</a></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
