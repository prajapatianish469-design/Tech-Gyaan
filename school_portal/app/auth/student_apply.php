<?php
require_once __DIR__ . '/../../app/config/db.php';
require_once __DIR__ . '/../../app/lib/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = db();
    $courses = isset($_POST['courses']) ? implode(', ', $_POST['courses']) : '';
    $sql = "INSERT INTO students
      (full_name, father_name, mother_name, dob, gender, student_phone, father_phone, mother_phone,
       email, address, courses, batch_time, mode_learning, qualification, message, status)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['full_name'] ?? '',
        $_POST['father_name'] ?? '',
        $_POST['mother_name'] ?? '',
        $_POST['dob'] ?? null,
        $_POST['gender'] ?? null,
        $_POST['student_phone'] ?? '',
        $_POST['father_phone'] ?? '',
        $_POST['mother_phone'] ?? '',
        $_POST['email'] ?? '',
        $_POST['address'] ?? '',
        $courses,
        $_POST['batch_time'] ?? '',
        $_POST['mode_learning'] ?? '',
        $_POST['qualification'] ?? '',
        $_POST['message'] ?? ''
    ]);
    flash('ok', 'Application submitted! Wait for admin approval.');
    header('Location: ' . APP_URL . '/app/auth/student_apply.php');
    exit;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Student Application</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <h2>Student Application Form</h2>
  <?php if($m=flash('ok')): ?><div class="alert alert-success"><?= h($m) ?></div><?php endif; ?>
  <form method="post" class="row g-3">
    <h5>Personal Details</h5>
    <div class="col-md-6"><label class="form-label">Full Name</label><input name="full_name" class="form-control" required></div>
    <div class="col-md-6"><label class="form-label">Date of Birth</label><input type="date" name="dob" class="form-control" required></div>
    <div class="col-md-6"><label class="form-label">Father's Name</label><input name="father_name" class="form-control" required></div>
    <div class="col-md-6"><label class="form-label">Mother's Name</label><input name="mother_name" class="form-control" required></div>
    <div class="col-md-3"><label class="form-label">Gender</label><select name="gender" class="form-control" required><option value="">Select</option><option>Male</option><option>Female</option><option>Other</option></select></div>

    <div class="col-md-4"><label class="form-label">Student Contact</label><input name="student_phone" class="form-control" required></div>
    <div class="col-md-4"><label class="form-label">Father Contact</label><input name="father_phone" class="form-control" required></div>
    <div class="col-md-4"><label class="form-label">Mother Contact</label><input name="mother_phone" class="form-control" required></div>
    <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
    <div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" required></textarea></div>

    <h5>Course Details</h5>
    <div class="col-12">
      <label class="form-label">Which course do you want to Enroll in?</label>
      <div class="form-check"><input class="form-check-input" type="checkbox" name="courses[]" value="Basic Computer"><label class="form-check-label">Basic Computer</label></div>
      <div class="form-check"><input class="form-check-input" type="checkbox" name="courses[]" value="Tally ERP-9"><label class="form-check-label">Tally ERP-9</label></div>
      <div class="form-check"><input class="form-check-input" type="checkbox" name="courses[]" value="A.D.C.A"><label class="form-check-label">A.D.C.A</label></div>
      <div class="form-check"><input class="form-check-input" type="checkbox" name="courses[]" value="Internet"><label class="form-check-label">Internet</label></div>
      <div class="form-check"><input class="form-check-input" type="checkbox" name="courses[]" value="Hardware"><label class="form-check-label">Hardware</label></div>
      <div class="form-check"><input class="form-check-input" type="checkbox" name="courses[]" value="All of the Above"><label class="form-check-label">All of the Above</label></div>
    </div>

    <div class="col-md-6"><label class="form-label">Preferred Batch Timing</label><select name="batch_time" class="form-control" required><option value="">Select</option><option>Morning</option><option>Evening</option></select></div>
    <div class="col-md-6"><label class="form-label">Mode of Learning</label><select name="mode_learning" class="form-control" required><option value="">Select</option><option>Online</option><option>Offline (In Institute)</option></select></div>

    <h5>Education Background</h5>
    <div class="col-md-6"><label class="form-label">Highest Qualification</label><select name="qualification" class="form-control" required>
      <option value="">Select</option>
      <option>Class 5</option><option>Class 6</option><option>Class 7</option><option>Class 8</option>
      <option>Class 9</option><option>Class 10</option><option>Class 11</option><option>Class 12</option>
      <option>Above 12</option><option>None of these</option>
    </select></div>

    <div class="col-12"><label class="form-label">Your Message For Us</label><textarea name="message" class="form-control"></textarea></div>

    <div class="col-12"><button class="btn btn-primary">Submit Application</button></div>
  </form>
</body>
</html>
