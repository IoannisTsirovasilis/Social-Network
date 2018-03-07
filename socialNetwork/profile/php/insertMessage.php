<?php
	header("Content-Type: application/json; charset=UTF-8");
	$obj = json_decode($_POST["x"], false);
	include($_SERVER['DOCUMENT_ROOT'] . '/socialNetwork/includes/db_connect.php');
	$sql = 'INSERT INTO `messages` (`id_from`,`id_to`,`message`) VALUES (?,?,?);';
	$stmt = $con->prepare($sql);
	$stmt->bind_param('sss', $obj->id_from, $obj->id_to, $obj->msg);
	$stmt->execute();
	$stmt->close();
	$con->close();	
?>