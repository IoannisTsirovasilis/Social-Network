<?php 
	session_start();
	session_regenerate_id();
	header('Location: http://192.168.1.2/socialNetwork/profile/logout.php');
?>