<?php
	session_start();
	session_regenerate_id();
	if (!isset($_SESSION['id'])) :
		header('Location: ../');
		exit;
	else :	
		include($_SERVER['DOCUMENT_ROOT'] . '/socialNetwork/includes/User.php');
		$profile = User::fetchUser($_SESSION['id']);
		$update = array('oldPassword' => '', 'newPassword' => '', 'rNewPassword' => '');
		$error = array('newPassword' => '', 'rNewPassword' => '');
		$msg = '';
		if (isset($_GET['msg']) && ($_GET['msg'] === '0' || $_GET['msg'] === '1')) {
			$msg = ($_GET['msg'] === '0') ? '<b class="text-danger">Something went wrong.</div>' : '<b class="text-success">Password has changed successfully</div>';
		}
		if (isset($_POST['update'])) {
			$update = $_POST['update'];
			$valid = TRUE;			
			if (strlen($update['newPassword']) < 4) {
				$error['newPassword'] = 'Password must have at least 4 characters';
				$valid = FALSE;
			} else {
				if ($update['newPassword'] !== $update['rNewPassword']) {
					$error['rNewPassword'] = 'Passwords do not match';
					$valid = FALSE;
				}
			}
			if ($valid) {				
				$bool = User::changePassword($_SESSION['id'],$update['oldPassword'],$update['newPassword']);
				if ($bool) {
					header('Location: ?msg=1');
					exit;
				} else {
					header('Location: ?msg=0');
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
		<style>
			.pointer{cursor:pointer};
		</style>
		<script src="js/jquery-3.3.1.min.js"></script>
		<script src="js/bootstrap.bundle.min.js"></script>
		<script src="ajax/search.js"></script>
		<script src="ajax/settings.js"></script>
	</head>
	<body>
		<nav class="navbar navbar-dark bg-dark navbar-expand-md">
			<a class="navbar-brand d-block d-md-none" href="profile.php<?php echo '?id=' . $_SESSION['id']; ?>">ChatApp</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse justify-content-between my-2 my-md-0" id="navbarText">
				<a class="navbar-brand d-none d-md-block" href="profile.php<?php echo '?id=' . $_SESSION['id']; ?>">ChatApp</a>
				<div class="navbar-nav mr-5 dropdown">
					<input onkeyup="searchUser(this.value)" type="text" class="form-control dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" placeholder="Search for a person...">
					<div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="hints"></div>
				</div>
				<div class="navbar-nav">
					<span class="navbar-text text-white">
						Welcome <?php if (isset($_SESSION['username'])) echo htmlspecialchars($_SESSION['username']); ?>
					</span>
					<a href="settings.php"><button class="btn btn-outline-success ml-md-2 my-2 my-md-0">Settings</button></a>
					<a href="logout.php"><button class="btn btn-outline-success ml-md-2 my-2 my-md-0">Logout</button></a>
				</div>				
			</div>
		</nav>	
		<form class="form-horizontal mt-5" method="POST">			
			<div class="form-group">
				<label class="control-label col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">Old Password</label>
				<div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
					<input name="update[oldPassword]" type="password" class="form-control" placeholder="Enter old password">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">New Password:</label>
				<div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4"> 
					<input name="update[newPassword]" type="password" class="form-control" placeholder="Enter new password">
					<small><?php if ($error['newPassword']) echo '<b class="text-danger">' . $error['newPassword'] . '</b>'; ?></small>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">Repeat New Password:</label>
				<div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4"> 
					<input name="update[rNewPassword]" type="password" class="form-control" placeholder="Enter new password again">
					<small><?php if ($error['rNewPassword']) echo '<b class="text-danger">' . $error['rNewPassword'] . '</b>'; ?></small>
				</div>
			</div>
			<div class="form-group"> 
				<div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
					<button name="update[submit]" type="submit" class="btn btn-success">Change Password</button>
					<small><?php if ($msg) echo $msg; ?></small>
				</div>					
			</div>
		</form>
		<div class="row">
			<div class="dropdown col-12 col-md-10 offset-md-2 col-lg-9 offset-lg-3 col-xl-8 offset-xl-4">
				<button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Change Avatar
				</button>
				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<button class="dropdown-item">
						<img onclick="updateAvatar(<?php echo $_SESSION['id']; ?>,'avatar/av00.jpg')" class="align-self-start mr-3" width="64" height="64" src="avatar/av00.jpg" alt="Error 404">
						<img onclick="updateAvatar(<?php echo $_SESSION['id']; ?>,'avatar/av01.png')" class="align-self-start mr-3" width="64" height="64" src="avatar/av01.png" alt="Error 404">
						<img onclick="updateAvatar(<?php echo $_SESSION['id']; ?>,'avatar/av02.png')" class="align-self-start mr-3" width="64" height="64" src="avatar/av02.png" alt="Error 404">
					</button>
					<button class="dropdown-item">
						<img onclick="updateAvatar(<?php echo $_SESSION['id']; ?>,'avatar/av03.png')" class="align-self-start mr-3" width="64" height="64" src="avatar/av03.png" alt="Error 404">
						<img onclick="updateAvatar(<?php echo $_SESSION['id']; ?>,'avatar/av04.png')" class="align-self-start mr-3" width="64" height="64" src="avatar/av04.png" alt="Error 404">
						<img onclick="updateAvatar(<?php echo $_SESSION['id']; ?>,'avatar/av05.png')" class="align-self-start mr-3" width="64" height="64" src="avatar/av05.png" alt="Error 404">
					</button>
					<button class="dropdown-item">
						<img onclick="updateAvatar(<?php echo $_SESSION['id']; ?>,'avatar/av06.png')" class="align-self-start mr-3" width="64" height="64" src="avatar/av06.png" alt="Error 404">
						<img onclick="updateAvatar(<?php echo $_SESSION['id']; ?>,'avatar/av07.png')" class="align-self-start mr-3" width="64" height="64" src="avatar/av07.png" alt="Error 404">
						<img onclick="updateAvatar(<?php echo $_SESSION['id']; ?>,'avatar/av08.png')" class="align-self-start mr-3" width="64" height="64" src="avatar/av08.png" alt="Error 404">
					</button>
					<button class="dropdown-item">
						<img onclick="updateAvatar(<?php echo $_SESSION['id']; ?>,'avatar/av09.png')" class="align-self-start mr-3" width="64" height="64" src="avatar/av09.png" alt="Error 404">
						<img onclick="updateAvatar(<?php echo $_SESSION['id']; ?>,'avatar/av10.png')" class="align-self-start mr-3" width="64" height="64" src="avatar/av10.png" alt="Error 404">
						<img onclick="updateAvatar(<?php echo $_SESSION['id']; ?>,'avatar/av11.png')" class="align-self-start mr-3" width="64" height="64" src="avatar/av11.png" alt="Error 404">
					</button>
					<button class="dropdown-item">
						<img onclick="updateAvatar(<?php echo $_SESSION['id']; ?>,'avatar/av12.png')" class="align-self-start mr-3" width="64" height="64" src="avatar/av12.png" alt="Error 404">
						<img onclick="updateAvatar(<?php echo $_SESSION['id']; ?>,'avatar/av13.png')" class="align-self-start mr-3" width="64" height="64" src="avatar/av13.png" alt="Error 404">
						<img onclick="updateAvatar(<?php echo $_SESSION['id']; ?>,'avatar/av14.png')" class="align-self-start mr-3" width="64" height="64" src="avatar/av14.png" alt="Error 404">
					</button>
					<button class="dropdown-item">
						<img onclick="updateAvatar(<?php echo $_SESSION['id']; ?>,'avatar/av15.png')" class="align-self-start mr-3" width="64" height="64" src="avatar/av15.png" alt="Error 404">
						<img onclick="updateAvatar(<?php echo $_SESSION['id']; ?>,'avatar/av16.png')" class="align-self-start mr-3" width="64" height="64" src="avatar/av16.png" alt="Error 404">
						<img onclick="updateAvatar(<?php echo $_SESSION['id']; ?>,'avatar/av17.png')" class="align-self-start mr-3" width="64" height="64" src="avatar/av17.png" alt="Error 404">
					</button>
				</div>
			</div>
			<div class="col-12 col-md-10 offset-md-2 col-lg-9 offset-lg-3 col-xl-8 offset-xl-4 mt-2">
				<img id="avatar" class="align-self-start" width="64" height="64" src="<?php echo $profile['avatar']; ?>" alt="Error 404">
			</div>
		</div>
	</body>
</html>
<?php endif; ?>