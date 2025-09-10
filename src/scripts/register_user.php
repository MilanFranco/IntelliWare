<?php
session_start();

require_once __DIR__ . '/../../config/db.php';

function redirect_with_error($msg) {
	header('Location: ../../register.php?step=2&error=' . urlencode($msg));
	exit();
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
$confirm  = trim($_POST['confirm_password'] ?? '');
$first    = trim($_POST['first_name'] ?? '');
$last     = trim($_POST['last_name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$fullName = trim($_POST['full_name'] ?? ($first . ' ' . $last));

if ($username === '' || $password === '' || $confirm === '' || $first === '' || $last === '' || $email === '') {
	redirect_with_error('Please complete all fields.');
}

if ($password !== $confirm) {
	redirect_with_error('Passwords do not match.');
}

// Username uniqueness
$check = $conn->prepare('SELECT user_id FROM users WHERE username = ?');
$check->bind_param('s', $username);
$check->execute();
$res = $check->get_result();
if ($res && $res->num_rows > 0) {
	$check->close();
	redirect_with_error('Username is already taken.');
}
$check->close();

$passwordHash = password_hash($password, PASSWORD_BCRYPT);
$role = 'staff';

$stmt = $conn->prepare('INSERT INTO users (username, password, role, full_name) VALUES (?,?,?,?)');
if (!$stmt) {
	redirect_with_error('Failed to prepare statement.');
}
$stmt->bind_param('ssss', $username, $passwordHash, $role, $fullName);

if (!$stmt->execute()) {
	$err = $conn->error ?: 'Failed to create account.';
	$stmt->close();
	redirect_with_error($err);
}
$stmt->close();

unset($_SESSION['reg_username'], $_SESSION['reg_password'], $_SESSION['reg_confirm']);

$_SESSION['Username'] = $username;
$_SESSION['AccountID'] = $conn->insert_id;
$_SESSION['Role'] = $role;

header('Location: ../../dash_staff.php');
exit();


