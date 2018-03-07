<?php
	header("Content-Type: application/json; charset=UTF-8");
	$obj = json_decode($_POST["x"], false);
	include($_SERVER['DOCUMENT_ROOT'] . '/socialNetwork/includes/db_connect.php');
	$sql = 'UPDATE `users` SET `avatar`=? WHERE `id`=?;';
	$stmt = $con->prepare($sql);
	$stmt->bind_param('ss', $obj->avatar, $obj->id);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	$con->close();	
?>