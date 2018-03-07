<?php
	header("Content-Type: application/json; charset=UTF-8");
	$obj = json_decode($_POST["x"], false);
	include($_SERVER['DOCUMENT_ROOT'] . '/socialNetwork/includes/db_connect.php');
	$sql = 'SELECT * FROM `requests` WHERE `id_to`=? AND `status`=0;';
	$stmt = $con->prepare($sql);
	$stmt->bind_param('s', $obj->id);
	$stmt->execute();
	$result = $stmt->get_result();
	$requests = array();
	while ($row = $result->fetch_assoc()) {
		$requests[] = $row;
	}
	$stmt->close();
	$con->close();
	echo json_encode($requests);	
?>