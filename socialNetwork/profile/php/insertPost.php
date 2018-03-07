<?php
	header("Content-Type: application/json; charset=UTF-8");
	$obj = json_decode($_POST["x"], false);
	include($_SERVER['DOCUMENT_ROOT'] . '/socialNetwork/includes/db_connect.php');
	$sql = 'INSERT INTO `posts` (`user_id`,`post`) VALUES (?,?);';
	$stmt = $con->prepare($sql);
	$stmt->bind_param('ss', $obj->id, $obj->post);
	$stmt->execute();
	$stmt->close();
	$con->close();	
?>