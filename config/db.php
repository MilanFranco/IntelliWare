<?php
// Database connection for IntelliWare
// Update credentials if needed
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'warehouse_system';

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
	die('Database connection failed: ' . $conn->connect_error);
}

// Ensure proper charset
$conn->set_charset('utf8mb4');
?>


