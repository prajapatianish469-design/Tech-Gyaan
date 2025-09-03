<?php
require_once __DIR__ . '/../app/config/db.php';
require_once __DIR__ . '/../app/lib/helpers.php';
require_admin();
$pdo = db();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $type  = $_POST['type'] ?? '';
    if (!$title || !in_array($type, ['syllabus','notes','question_bank'])) {
        $msg = 'Invalid input.';
    } elseif (empty($_FILES['file']['name'])) {
        $msg = 'Please choose a file.';
    } else {
        $dir = __DIR__ . '/../uploads/';
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        $fname = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/','_', basename($_FILES['file']['name']));
        $target = $dir . $fname;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
            $stmt = $pdo->prepare("INSERT INTO materials (title, type, file_name) VALUES (?,?,?)");
            $stmt->execute([$title, $type, $fname]);
            $msg = 'Uploaded successfully.';
        } else $msg = 'Upload failed.';
    }
}
$materials = $pdo->query("SELECT * FROM materials ORDER BY uploaded_at DESC")->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Upload Materials</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <a href="dashboard.php" class="btn btn-secondary mb-3">⬅ Back</a>
  <?php if ($msg): ?><div class="alert alert-info"><?= h($msg) ?></div><?php endif; ?>

  <form method="post" enctype="multipart/form-data" class="row g-3 mb-4">
    <div class="col-md-5">
      <label class="form-label">Title</label>
      <input name="title" class="form-control" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">Type</label>
      <select name="type" class="form-control" required>
        <option value="">Select</option>
        <option value="syllabus">Syllabus</option>
        <option value="notes">Notes</option>
        <option value="question_bank">Question Bank</option>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">File</label>
      <input type="file" name="file" class="form-control" required>
    </div>
    <div class="col-12"><button class="btn btn-success">Upload</button></div>
  </form>

  <h4>Uploaded Files</h4>
  <table class="table table-bordered">
    <thead><tr><th>Title</th><th>Type</th><th>File</th><th>Uploaded</th></tr></thead>
    <tbody>
      <?php foreach($materials as $m): ?>
      <tr>
        <td><?= h($m['title']) ?></td>
        <td><?= h(ucfirst($m['type'])) ?></td>
        <td><a href="../uploads/<?= h($m['file_name']) ?>" target="_blank">Download</a></td>
        <td><?= dmy($m['uploaded_at']) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
