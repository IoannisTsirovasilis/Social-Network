<?php
	header("Content-Type: application/json; charset=UTF-8");
	$obj = json_decode($_POST["x"], false);
	include($_SERVER['DOCUMENT_ROOT'] . '/socialNetwork/includes/db_connect.php');
	$sql = 'UPDATE `requests` SET `status`=1 WHERE `id_from`=? AND `id_to`=?';
	$stmt = $con->prepare($sql);
	$stmt->bind_param('ss', $obj->id_from, $obj->id_to);
	$stmt->execute();
	$sql = 'INSERT INTO `requests` VALUES (?,?,?,1);';
	$stmt = $con->prepare($sql);
	$stmt->bind_param('sss', $obj->id_to, $obj->username, $obj->id_from);
	$stmt->execute();
	$stmt->close();
	$con->close();	
?>