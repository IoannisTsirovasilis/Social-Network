<?php
	session_start();
	session_regenerate_id();
	$login = array('email' => '', 'password' => '');
	$err = '';
	$register = array('username' => '', 'email' => '', 'password' => '', 'rPassword' => '');
	$error = array('username' => '', 'email' => '', 'password' => '', 'rPassword' => '');
	$msg = '';
	if (isset($_GET['msg']) && $_GET['msg'] === '1') {
		$msg = 'Registration is completed successfully!';
	}
	if (isset($_POST['login'])) {
		$login = $_POST['login'];
		$validL = TRUE;
		if (!filter_var($login['email'], FILTER_VALIDATE_EMAIL)) {
			$validL = FALSE;
		}
		if (!(isset($login['password']) && $login['password'])) {
			$validL = FALSE;
		}
		if ($validL) {
			include('includes/User.php');
			$register['email'] = strip_tags(trim($register['email']));
			$userExistsL = User::login($login['email'], $login['password']);
			if (isset($userExistsL) && $userExistsL) {
				$_SESSION['username'] = $userExistsL['username'];
				$_SESSION['email'] = $userExistsL['email'];
				$_SESSION['id'] = $userExistsL['id'];
				header('Location: http://192.168.1.2/socialNetwork/profile/profile.php?id=' . $_SESSION['id']);
				exit;
			}
			else {
				$err = 'Wrong email or password';
			}			
		} else {
			$err = 'Wrong email or password';
		}
	}
	if (isset($_POST['register'])) {
		$register = $_POST['register'];
		$validR = TRUE;
		if (!preg_match('/^[a-zA-Z0-9_]+$/i', $register['username'])) {
			$error['username'] = 'Username can contain only letters, numbers and underscores';
			$validR = FALSE;
		}
		if (strlen($register['username']) < 4) {
			$error['username'] = 'Username must have at least 4 characters';
			$validR = FALSE;
		}
		if (strlen($register['username']) > 40) {
			$error['username'] = 'Username cannot exceed 40 characters';
			$validR = FALSE;
		}		
		if (!filter_var($register['email'], FILTER_VALIDATE_EMAIL)) {
			$error['email'] = 'Enter a valid email address';
			$validR = FALSE;
		}
		if (strlen($register['password']) < 4) {
			$error['password'] = 'Password must have at least 4 characters';
			$validR = FALSE;
		} else {
			if ($register['password'] !== $register['rPassword']) {
				$error['rPassword'] = 'Passwords do not match';
				$validR = FALSE;
			}
		}
		if ($validR) {
			include('includes/User.php');
			$register['username'] = strip_tags(trim($register['username']));
			$register['email'] = strip_tags(trim($register['email']));
			$userExistsR = User::findUser($register['email']);
			if ($userExistsR) {
				$error['rPassword'] = 'A user with this email address already exists';
			} else {
				User::insertUser($register['username'], $register['email'], $register['password']);
				header('Location: ?msg=1');
				exit;
			}			
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>ChatApp</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<script src="js/jquery-3.3.1.min.js"></script>
		<script src="js/bootstrap.bundle.min.js"></script>
	</head>
	<body>
		<nav class="navbar navbar-dark bg-dark justify-content-between">
			<a class="navbar-brand" href="#">ChatApp</a>
			<form class="form-inline mr-xl-5" method="POST">
				<small class="d-none d-md-block mr-2"><?php if ($err) echo '<b class="text-danger">' . $err . '</b>'; ?></small>
				<input name="login[email]" class="form-control mr-sm-2" type="email" placeholder="Email">
				<input name="login[password]" class="form-control mt-2 mt-sm-0 mr-sm-2" type="password" placeholder="Password">
				<button name="login[submit]" class="btn btn-outline-success my-2 my-sm-0" type="submit">Login</button>
				<small class="d-block d-md-none ml-2"><?php if ($err) echo '<b class="text-danger">' . $err . '</b>'; ?></small>
			</form>
		</nav>
		<form class="form-horizontal mt-5" method="POST">			
			<div class="form-group">
				<label class="control-label col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">Username</label>
				<div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
					<input name="register[username]" type="text" class="form-control" value="<?php echo htmlspecialchars($register['username']); ?>" placeholder="Enter username">
					<small><?php if ($error['username']) echo '<b class="text-danger">' . $error['username'] . '</b>'; ?></small>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">Email</label>
				<div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
					<input name="register[email]" type="email" class="form-control" value="<?php echo htmlspecialchars($register['email']); ?>" placeholder="Enter email">
					<small><?php if ($error['email']) echo '<b class="text-danger">' . $error['email'] . '</b>'; ?></small>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">Password:</label>
				<div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4"> 
					<input name="register[password]" type="password" class="form-control" placeholder="Enter password">
					<small><?php if ($error['password']) echo '<b class="text-danger">' . $error['password'] . '</b>'; ?></small>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">Repeat Password:</label>
				<div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4"> 
					<input name="register[rPassword]" type="password" class="form-control" placeholder="Enter password again">
					<small><?php if ($error['rPassword']) echo '<b class="text-danger">' . $error['rPassword'] . '</b>'; ?></small>
				</div>
			</div>
			<div class="form-group"> 
				<div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
					<button name="register[submit]" type="submit" class="btn btn-default">Register</button>
					<small><?php if ($msg) echo '<b class="text-success">' . $msg . '</b>'; ?></small>
				</div>					
			</div>
		</form>
	</body>
</html>