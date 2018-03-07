<?php
	header("Content-Type: application/json; charset=UTF-8");
	$obj = json_decode($_POST["x"], false);
	include($_SERVER['DOCUMENT_ROOT'] . '/socialNetwork/includes/db_connect.php');
	$sql = 'SELECT * FROM `posts` WHERE `user_id`=? ORDER BY `post_id` DESC;';
	$stmt = $con->prepare($sql);
	$stmt->bind_param('s', $obj->owners_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$posts = array();
	while ($row = $result->fetch_assoc()) {
		$stmt->close();
		$row['post'] = htmlspecialchars($row['post']);
		$sql = 'SELECT `reaction` FROM `reactions` WHERE `post_id`=? AND `user_id`=?;';
		$stmt = $con->prepare($sql);
		$stmt->bind_param('ss', $row['post_id'], $obj->visitors_id);
		$stmt->execute();
		$stmt->bind_result($reaction);
		$stmt->fetch();
		$row['reaction'] = $reaction;
		$posts[] = $row;
	}
	$stmt->close();
	$con->close();	
	echo json_encode($posts);
?>