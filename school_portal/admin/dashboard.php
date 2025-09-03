<?php
require_once __DIR__ . '/../app/config/db.php';
require_once __DIR__ . '/../app/lib/helpers.php';
require_admin();

$pdo = db();
$approved = $pdo->query("SELECT id, full_name, email,  status FROM students WHERE status='approved' ORDER BY id DESC")->fetchAll();
$rejected = $pdo->query("SELECT id, full_name, email, status FROM students WHERE status='rejected' ORDER BY id DESC")->fetchAll();
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <div class="d-flex justify-content-between align-items-center">
    <h2>Admin Dashboard</h2>
    <div>
      <strong><?= h($_SESSION['admin_username'] ?? 'Admin') ?></strong>
      <a href="logout.php" class="btn btn-sm btn-outline-danger ms-2">Logout</a>
    </div>
  </div>

  <div class="mt-3 mb-3">
    <a href="manage_students.php" class="btn btn-primary">Manage Students</a>
    <a href="upload_material.php" class="btn btn-secondary">Upload Materials</a>
  </div>

  <?php if ($m = flash('ok')): ?><div class="alert alert-success"><?= h($m) ?></div><?php endif; ?>
  <?php if ($m = flash('err')): ?><div class="alert alert-danger"><?= h($m) ?></div><?php endif; ?>

  <h4 class="mt-4">✔ Approved Students</h4>
  <?php if ($approved): ?>
  <table class="table table-bordered">
    <thead><tr><th>ID</th><th>UID</th><th>Name</th><th>Email</th><th>Approved On</th><th>View</th></tr></thead>
    <tbody>
    <?php foreach ($approved as $s): ?>
      <tr>
        <td><?= $s['id'] ?></td>
        
        <td><?= h($s['full_name']) ?></td>
        <td><?= h($s['email']) ?></td>
        <td><?= dmy($s['approved_at']) ?></td>
        <td><a class="btn btn-sm btn-outline-primary" href="student_view.php?id=<?= $s['id'] ?>">Open</a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
    <p class="text-muted">No approved students yet.</p>
  <?php endif; ?>

  <h4 class="mt-4">⛔ Rejected Students</h4>
  <?php if ($rejected): ?>
  <table class="table table-bordered">
    <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Rejected On</th><th>View</th></tr></thead>
    <tbody>
      <?php foreach ($rejected as $s): ?>
        <tr>
          <td><?= $s['id'] ?></td>
          <td><?= h($s['full_name']) ?></td>
          <td><?= h($s['email']) ?></td>
          <td><?= dmy($s['rejected_at']) ?></td>
          <td><a class="btn btn-sm btn-outline-primary" href="student_view.php?id=<?= $s['id'] ?>">Open</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
    <p class="text-muted">No rejected students yet.</p>
  <?php endif; ?>

</body>
</html>
