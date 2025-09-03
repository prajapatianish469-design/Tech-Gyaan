<?php
require_once __DIR__ . '/../app/config/db.php';
require_once __DIR__ . '/../app/lib/helpers.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (isset($_SESSION['student_id'])) {
    header('Location: dashboard.php'); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = db();
    $stmt = $pdo->prepare("SELECT * FROM students WHERE email = ? AND status='approved'");
    $stmt->execute([$_POST['email']]);
    $st = $stmt->fetch();
    if ($st && password_verify($_POST['password'], $st['password_hash'])) {
        $_SESSION['student_id'] = $st['id'];
        $_SESSION['student_name'] = $st['full_name'];
        header('Location: dashboard.php'); exit;
    } else {
        flash('err', 'Invalid credentials or not approved.');
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Student Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="container mt-4">
  <h2>Student Login</h2>
  <?php if ($m = flash('err')): ?><div class="alert alert-danger"><?= h($m) ?></div><?php endif; ?>
  <form method="post" style="max-width:380px">
    <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
    <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
    <button class="btn btn-primary">Login</button>
  </form>
  <p class="mt-3"><a href="<?= APP_URL ?>/app/auth/student_apply.php">Apply as student</a></p>
</body>
</html>
