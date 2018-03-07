<?php
	session_start();
	session_regenerate_id();
	if (!isset($_SESSION['id'])) :
		header('Location: http://192.168.1.2/socialNetwork/');
		exit;
	else :
		if (isset($_GET['id']) && gettype($_GET['id']) !== 'string') {
			header('Location: http://192.168.1.2/socialNetwork/profile/logout.php');
			exit;
		} else {
			$id = $_GET['id'];
			include($_SERVER['DOCUMENT_ROOT'] . '/socialNetwork/includes/User.php');
			$profile = User::fetchUser($id);
			if (!$profile) {
				header('Location: http://192.168.1.2/socialNetwork/profile/profile.php?id=' . $_SESSION['id']);
				exit;
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
			.wrap{word-wrap: break-word;};
		</style>
		<script src="js/jquery-3.3.1.min.js"></script>
		<script src="js/bootstrap.bundle.min.js"></script>
		<script src="ajax/search.js"></script>
		<script src="ajax/friends.js"></script>
		<script src="ajax/posts.js"></script>
		<script src="ajax/messages.js"></script>
		<script>
			fetchPosts(<?php echo $_SESSION['id']; ?>, <?php echo $profile['id']; ?>);
			setInterval(function(){fetchPosts(<?php echo $_SESSION['id']; ?>, <?php echo $profile['id']; ?>);},30000);
			fetchFriends(<?php echo $_SESSION['id']; ?>);
			friendRequests(<?php echo $_SESSION['id']; ?>, <?php echo '"' . $_SESSION['username'] . '"'; ?>);
			setInterval(function(){friendRequests(<?php echo $_SESSION['id']; ?>, <?php echo '"' . $_SESSION['username'] . '"'; ?>);}, 30000);	
			
		</script>		
	</head>
	<body>
		<?php 
			if ($profile['id'] !== $_SESSION['id']) {
				$response = User::friendRequestStatus($_SESSION['id'],$profile['id']);
			}			
		?>
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
		<div class="row mt-5">
			<div class="col-12 col-lg-3 col-xl-4">
				<div class = "container-fluid" id="requests"></div>
			</div>
			<div class="col-12 col-lg-6 col-xl-4">
				<div class = "container-fluid">
					<div class = "row">						
						<div class = "border border-success rounded bg-light col-12 py-3">
							<div class="media">
								<img class="align-self-start mr-3" width="64" height="64" src="<?php echo $profile['avatar']; ?>" alt="Error 404">
								<div class="media-body">
									<h5 class="mt-0"><?php echo $profile['username']; ?></h5>
									<div id="friendRequestStatus">
										<?php if ($_SESSION['id'] === $profile['id']) : ?>
											<textarea class="form-control" placeholder="Post what you are thinking..." id="post"></textarea>
											<button onclick="publishPost(<?php echo $_SESSION['id']; ?>)" class="btn btn-outline-success my-2">Post</button>
										<?php else : ?>
											<?php if (isset($response) && !empty($response) && $response->status === 0) : ?>
												<div class="d-flex justify-content-start align-items-center">
													<div class="text-success p-1"><?php echo $response->message; ?></div>
													<button onclick="cancelRequest(<?php echo $_SESSION["id"] . ",'" . $_SESSION['username'] . "'," . $profile["id"]; ?>)" class="btn btn-outline-danger ml-2">Cancel</button>
												</div>
											<?php elseif (isset($response) && $response && $response->status === -1) : ?>
												<div class="d-flex justify-content-start align-items-center">
													<div class="text-success p-1"><?php echo $response->message; ?></div>
												</div>
											<?php elseif (!isset($response)) : ?>
												<button onclick="sendRequest(<?php echo $_SESSION["id"] . ",'" . $_SESSION['username'] . "'," . $profile["id"]; ?>)" class="btn btn-outline-success my-2">Add Friend</button>
											<?php endif; ?>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php if ($_SESSION['id'] === $profile['id']  || (isset($response) && $response->status === 1)) : ?>
					<div class = "container-fluid mt-2" id="postArea"></div>						
				<?php else : ?>
					<div class = "container-fluid mt-2">
						<div class = "row mt-2">
							<div class = "border border-success rounded bg-light col-12 py-1">
								You have to be friends with <b><?php echo $profile['username']; ?></b> in order to see his/her posts
							</div>
						</div>	
					</div>
				<?php endif; ?>				
			</div>
			<div class="col-12 col-lg-3 col-xl-4" id="friends_list"></div>
		</div>
	</body>
</html>
<?php endif; ?>