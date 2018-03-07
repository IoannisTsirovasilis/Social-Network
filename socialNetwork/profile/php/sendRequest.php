<?php
	header("Content-Type: application/json; charset=UTF-8");
	$obj = json_decode($_POST["x"], false);
	include($_SERVER['DOCUMENT_ROOT'] . '/socialNetwork/includes/db_connect.php');
	$sql = 'INSERT INTO `requests` (`id_from`,`username_from`,`id_to`) VALUES(?,?,?);';
	$stmt = $con->prepare($sql);
	$stmt->bind_param('sss', $obj->id_from, $obj->username, $obj->id_to);
	$stmt->execute();
	$stmt->close();
	$con->close();
	$obj->message = 'Friend request has been sent!';
	echo json_encode($obj);	
?>