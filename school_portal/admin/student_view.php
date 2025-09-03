<?php
require_once __DIR__ . '/../app/config/db.php';
require_once __DIR__ . '/../app/lib/helpers.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
$pdo = db();
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();
if (!$student) {
    flash('err', 'Student not found.');
    header('Location: manage_students.php');
    exit;
}

// Show credentials from session if generated just now
$creds = $_SESSION['show_creds'] ?? null;
unset($_SESSION['show_creds']);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Student Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <a href="manage_students.php" class="btn btn-secondary mb-3">⬅ Back</a>

  <?php if ($m = flash('ok')): ?><div class="alert alert-success"><?= h($m) ?></div><?php endif; ?>
  <?php if ($m = flash('err')): ?><div class="alert alert-danger"><?= h($m) ?></div><?php endif; ?>

  <?php if ($creds): ?>
    <div class="alert alert-info">
      <strong>Credentials for student (share with student):</strong><br>
      Student ID: <code><?= h($creds['student_uid']) ?></code><br>
      Password: <code><?= h($creds['password']) ?></code>
    </div>
  <?php endif; ?>

  <h3><?= h($student['full_name']) ?> (<?= h($student['status']) ?>)</h3>
  <table class="table table-striped mt-3">
    <tbody>
      <tr><th>Student UID</th><td><?= h($student['student_uid'] ?? '—') ?></td></tr>
      <tr><th>Full Name</th><td><?= h($student['full_name']) ?></td></tr>
      <tr><th>Father's Name</th><td><?= h($student['father_name']) ?></td></tr>
      <tr><th>Mother's Name</th><td><?= h($student['mother_name']) ?></td></tr>
      <tr><th>DOB</th><td><?= dmy($student['dob']) ?></td></tr>
      <tr><th>Gender</th><td><?= h($student['gender']) ?></td></tr>
      <tr><th>Student Phone</th><td><?= h($student['student_phone']) ?></td></tr>
      <tr><th>Father Phone</th><td><?= h($student['father_phone']) ?></td></tr>
      <tr><th>Mother Phone</th><td><?= h($student['mother_phone']) ?></td></tr>
      <tr><th>Email</th><td><?= h($student['email']) ?></td></tr>
      <tr><th>Address</th><td><?= nl2br(h($student['address'])) ?></td></tr>
      <tr><th>Courses</th><td><?= h($student['courses']) ?></td></tr>
      <tr><th>Preferred Batch</th><td><?= h($student['batch_time']) ?></td></tr>
      <tr><th>Mode of Learning</th><td><?= h($student['mode_learning']) ?></td></tr>
      <tr><th>Qualification</th><td><?= h($student['qualification']) ?></td></tr>
      <tr><th>Message</th><td><?= nl2br(h($student['message'])) ?></td></tr>
      <tr><th>Applied At</th><td><?= dmy($student['applied_at']) ?></td></tr>
      <tr><th>Approved At</th><td><?= dmy($student['approved_at']) ?></td></tr>
      <tr><th>Rejected At</th><td><?= dmy($student['rejected_at']) ?></td></tr>
    </tbody>
  </table>

  <form method="post" action="toggle_status.php" class="d-flex gap-2">
    <input type="hidden" name="id" value="<?= $student['id'] ?>">
    <?php if ($student['status'] !== 'approved'): ?>
      <button name="action" value="approve" class="btn btn-success">Approve</button>
    <?php endif; ?>
    <?php if ($student['status'] !== 'rejected'): ?>
      <button name="action" value="reject" class="btn btn-danger">Reject</button>
    <?php endif; ?>
  </form>

</body>
</html>
