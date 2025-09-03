<?php
require_once __DIR__ . '/../app/lib/helpers.php';
if (session_status() === PHP_SESSION_NONE) session_start();
session_unset();
session_destroy();
header('Location: login.php');
exit;
