<?php
// /Labbooking/includes/db.php
// XAMPP / Localhost database connection

declare(strict_types=1);

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = ''; // XAMPP default password empty
$DB_NAME = 'edencampus'; // phpMyAdmin database name
$DB_PORT = 3306;

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

if ($mysqli->connect_errno) {
    die('Database connection failed: ' . $mysqli->connect_error);
}

$mysqli->set_charset('utf8mb4');

// Some old files may use $conn instead of $mysqli
$conn = $mysqli;
