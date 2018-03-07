<?php
	header("Content-Type: application/json; charset=UTF-8");
	$obj = json_decode($_POST["x"], false);
	include($_SERVER['DOCUMENT_ROOT'] . '/socialNetwork/includes/db_connect.php');
	$sql = 'SELECT * FROM `users` WHERE LOWER(`username`) LIKE ?;';
	$hint = strtolower($obj->hint . '%');
	$stmt = $con->prepare($sql);
	$stmt->bind_param('s', $hint);
	$stmt->execute();
	$result = $stmt->get_result();
	$hints = array();
	while ($row = $result->fetch_assoc()) {
		$hints[] = $row;
	}
	$stmt->close();
	$con->close();	
	echo json_encode($hints);
?>