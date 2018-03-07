<?php
	header("Content-Type: application/json; charset=UTF-8");
	$obj = json_decode($_POST["x"], false);
	include($_SERVER['DOCUMENT_ROOT'] . '/socialNetwork/includes/db_connect.php');
	$sql = 'DELETE FROM `posts` WHERE `post_id`=?;';
	$stmt = $con->prepare($sql);
	$stmt->bind_param('s', $obj->id);
	$stmt->execute();
	$stmt->close();
	$con->close();	
?>