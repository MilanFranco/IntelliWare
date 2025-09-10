<?php
session_start();

$step = isset($_GET['step']) && $_GET['step'] === '2' ? '2' : '1';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['__step']) && $_POST['__step'] === '1') {
	$_SESSION['reg_username'] = trim($_POST['username'] ?? '');
	$_SESSION['reg_password'] = trim($_POST['password'] ?? '');
	$_SESSION['reg_confirm'] = trim($_POST['confirm_password'] ?? '');
	header('Location: register.php?step=2');
	exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Register | IntelliWare</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<link href="styles.css?v=1.0" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Onest&display=swap" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
	<style>
		.auth-wrapper { min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%; }
		.auth-wrapper .header { margin-top: 24px; margin-bottom: 12px; }
		.signin-logo { width: clamp(280px, 26vw, 360px); height: auto; display: block; margin-left: auto; margin-right: auto; }
		.container { max-width: 560px; width: 92vw; margin-left: auto; margin-right: auto; }
		.signinbox { background:#1D387B; border:1px solid #2A4484; border-radius:10px; padding:24px; color:#E3E3E3; box-shadow: 0 0 20px 2px #304374; }
		.tfieldname { color:#E3E3E3; font-size:12px; margin-bottom:6px; }
		.tf input, .tf .input { background:#13275B; color:#fff; border:1px solid #304374; padding:10px 12px; border-radius:6px; width:100%; box-sizing:border-box; }
		.btnlogin, .btn { background:#51D55A; color:#fff; border-radius:6px; padding:10px 12px; font-size:14px; border:1px solid transparent; cursor:pointer; width:100%; }
		.btn-secondary { background:#13275B; color:#E3E3E3; border:1px solid #304374; text-align:center; text-decoration:none; display:inline-block; }
		.fieldrow { display:flex; align-items:center; justify-content:space-between; margin-top:10px; }
		.new-user-line { display:flex; align-items:center; justify-content:center; gap:6px; }
		.newt { color:#E3E3E3; font-family:'Onest', sans-serif; }
		.reglink a { color:#9CC4FF; text-decoration:none; }
		.error { background:#ff6b6b33; border:1px solid #ff6b6b; color:#ffdede; padding:8px; border-radius:6px; margin-bottom:10px; }
	</style>
</head>
<body>
	<div class="auth-wrapper">
		<div class="header">
			<img src="img/logo.svg" alt="IntelliWare" class="fade-in signin-logo" />
		</div>
		<div class="container">
			<div class="signinbox">
				<?php if ($step === '1'): ?>
					<h3>Registration</h3>
					<p class="subtext">Create your username and password.</p>
					<hr>
					<?php if (isset($_GET['error'])): ?>
						<div class="error"><?php echo htmlspecialchars($_GET['error']); ?></div>
					<?php endif; ?>
					<form method="POST" action="register.php">
						<input type="hidden" name="__step" value="1" />
						<div class="tfieldname">Create your username</div>
						<div class="tf">
							<input type="text" name="username" placeholder="e.g. jdoe" required>
						</div>
						<div class="fieldrow">
							<div class="tfieldname">Password</div>
						</div>
						<div class="tf">
							<input type="password" name="password" required>
						</div>
						<div class="fieldrow">
							<div class="tfieldname">Confirm Password</div>
						</div>
						<div class="tf">
							<input type="password" name="confirm_password" required>
						</div>
						<br>
						<button class="btnlogin" type="submit">Proceed to Personal Information →</button>
					</form>
				<?php else: ?>
					<h3>Personal Information</h3>
					<p class="subtext">Create a profile and put information.</p>
					<hr>
					<?php if (isset($_GET['error'])): ?>
						<div class="error"><?php echo htmlspecialchars($_GET['error']); ?></div>
					<?php endif; ?>
					<form method="POST" action="src/scripts/register_user.php">
						<div style="display:flex; gap:12px;">
							<div style="flex:1;">
								<div class="tfieldname">First Name</div>
								<div class="tf"><input class="input" type="text" name="first_name" required></div>
							</div>
							<div style="flex:1;">
								<div class="tfieldname">Surname</div>
								<div class="tf"><input class="input" type="text" name="last_name" required></div>
							</div>
						</div>
						<div class="tfieldname" style="margin-top:12px;">Email Address</div>
						<div class="tf"><input class="input" type="email" name="email" required></div>
						<div class="tfieldname" style="margin-top:12px;">Full Name (auto or edit)</div>
						<div class="tf"><input class="input" type="text" name="full_name" placeholder="e.g. Juan Dela Cruz"></div>
						<input type="hidden" name="username" value="<?php echo htmlspecialchars($_SESSION['reg_username'] ?? ''); ?>">
						<input type="hidden" name="password" value="<?php echo htmlspecialchars($_SESSION['reg_password'] ?? ''); ?>">
						<input type="hidden" name="confirm_password" value="<?php echo htmlspecialchars($_SESSION['reg_confirm'] ?? ''); ?>">
						<br>
						<div style="display:flex; gap:12px;">
							<a href="register.php?step=1" class="btn-secondary">← Back</a>
							<button class="btnlogin" type="submit">Submit</button>
						</div>
					</form>
				<?php endif; ?>
			</div>
			<br>
			<div class="signinbox">
				<div class="new-user-line">
					<span class="newt">Already have an account?</span>
					<span class="reglink"><a href="signin.php">Log In here.</a></span>
				</div>
			</div>
			<div class="footer">
				<hr><br>
				© 2025 IntelliWare. All rights reserved.
				<a href="#">About IntelliWare</a>
				<a href="#">Contact our Support</a>
			</div>
		</div>
	</div>
</body>
</html>


