<?php
// Adjust if your MySQL user/pass differ
const DB_HOST = 'localhost';
const DB_NAME = 'school_portal';
const DB_USER = 'root';
const DB_PASS = '';
const APP_URL = 'http://localhost:8080/school_portal';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

