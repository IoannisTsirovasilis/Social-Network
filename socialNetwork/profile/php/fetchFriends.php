<?php
	header("Content-Type: application/json; charset=UTF-8");
	$obj = json_decode($_POST["x"], false);
	include($_SERVER['DOCUMENT_ROOT'] . '/socialNetwork/includes/db_connect.php');
	$sql = 'SELECT * FROM `users` WHERE `id` IN (SELECT `id_to` FROM `requests` WHERE `id_from`=? AND `status`=1);';
	$stmt = $con->prepare($sql);
	$stmt->bind_param('s', $obj->id);
	$stmt->execute();
	$result = $stmt->get_result();
	$friends = array();
	while ($row = $result->fetch_assoc()) {
		$friends[] = $row;
	}
	$stmt->close();
	$con->close();	
	echo json_encode($friends);
?>