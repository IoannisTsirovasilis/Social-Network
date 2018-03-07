<?php
	session_start();
	session_regenerate_id();
	include($_SERVER['DOCUMENT_ROOT'] . '/socialNetwork/includes/db_connect.php');
	$sql = 'UPDATE `users` SET `status`=0 WHERE `id`=?;';
	$stmt = $con->prepare($sql);
	$stmt->bind_param('s', $_SESSION['id']);
	$stmt->execute();
	$stmt->close();
	$con->close();
	$_SESSION = array();
	session_destroy();
	header('Location: http://192.168.1.2/socialNetwork/');
?>