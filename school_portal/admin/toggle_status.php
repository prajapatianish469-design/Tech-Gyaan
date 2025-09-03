<?php
require_once __DIR__ . '/../app/config/db.php';
require_once __DIR__ . '/../app/lib/helpers.php';
require_admin();
$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';
} else {
    $id = (int)($_GET['id'] ?? 0);
    $action = $_GET['status'] ?? '';
}

if (!$id) {
    flash('err', 'Invalid student id');
    header('Location: manage_students.php');
    exit;
}

if ($action === 'approve' || $action === 'approved') {
    // create uid if not exists
    $stmt = $pdo->prepare("SELECT student_uid FROM students WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    $uid = $row['student_uid'] ?? null;
    if (empty($uid)) $uid = generate_student_uid($pdo);

    $plain = random_password(10);
    $hash = password_hash($plain, PASSWORD_BCRYPT);

    $q = $pdo->prepare("UPDATE students SET status='approved', student_uid=?, password_hash=?, approved_at=NOW(), rejected_at=NULL WHERE id=?");
    $q->execute([$uid, $hash, $id]);

    // show credentials once
    $_SESSION['show_creds'] = ['student_uid' => $uid, 'password' => $plain];

    flash('ok', 'Student approved and credentials generated.');
    header('Location: student_view.php?id=' . $id);
    exit;
}

if ($action === 'reject' || $action === 'rejected') {
    $q = $pdo->prepare("UPDATE students SET status='rejected', password_hash=NULL, rejected_at=NOW() WHERE id=?");
    $q->execute([$id]);
    flash('ok', 'Student rejected and login disabled.');
    header('Location: student_view.php?id=' . $id);
    exit;
}

// fallback
header('Location: manage_students.php');
exit;
