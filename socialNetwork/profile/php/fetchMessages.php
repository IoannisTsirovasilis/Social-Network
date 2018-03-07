<?php
	header("Content-Type: application/json; charset=UTF-8");
	$obj = json_decode($_POST["x"], false);
	include($_SERVER['DOCUMENT_ROOT'] . '/socialNetwork/includes/db_connect.php');
	$sql = 'SELECT * FROM `messages` WHERE (`id_from`=? AND `id_to`=?) OR (`id_from`=? AND `id_to`=?);';
	$stmt = $con->prepare($sql);
	$stmt->bind_param('ssss', $obj->id_from, $obj->id_to, $obj->id_to, $obj->id_from);
	$stmt->execute();
	$result = $stmt->get_result();
	$messages = array();
	while ($row = $result->fetch_assoc()) {
		$row['message'] = htmlspecialchars($row['message']);
		$messages[] = $row;
	}
	$stmt->close();
	$con->close();	
	echo json_encode($messages);
?>