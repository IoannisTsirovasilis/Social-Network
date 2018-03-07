<?php
	header("Content-Type: application/json; charset=UTF-8");
	$obj = json_decode($_POST["x"], false);
	include($_SERVER['DOCUMENT_ROOT'] . '/socialNetwork/includes/db_connect.php');
	$sql = 'SELECT * FROM `reactions` WHERE `post_id`=? AND `user_id`=?;';
	$stmt = $con->prepare($sql);
	$stmt->bind_param('ii', $obj->post_id, $obj->user_id);
	$stmt->execute();
	$result = $stmt->get_result();
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();		
		if ($row['reaction'] == $obj->reaction) {
			$sql = 'DELETE FROM `reactions` WHERE `post_id`=? AND `user_id`=?;';
			$stmt = $con->prepare($sql);
			$stmt->bind_param('ss', $obj->post_id, $obj->user_id);
			$stmt->execute();
			$reaction->reaction = 0;
		} else {
			$sql = 'UPDATE `reactions` SET `reaction`=? WHERE `post_id`=? AND `user_id`=?;';
			$stmt = $con->prepare($sql);
			$stmt->bind_param('sss', $obj->reaction, $obj->post_id, $obj->user_id);
			$stmt->execute();
		}			
	} else {
		$sql = 'INSERT INTO `reactions` VALUES (?,?,?);';
		$stmt = $con->prepare($sql);
		$stmt->bind_param('sss', $obj->post_id, $obj->user_id, $obj->reaction);
		$stmt->execute();
	}
	$sql = 'SELECT SUM(`reaction`) FROM `reactions` WHERE `post_id`=? AND `reaction`=1;';
	$stmt = $con->prepare($sql);
	$stmt->bind_param('s', $obj->post_id);
	$stmt->execute();
	$stmt->bind_result($likes);
	$stmt->fetch();
	$stmt->close();
	$sql = 'SELECT SUM(`reaction`) FROM `reactions` WHERE `post_id`=? AND `reaction`=-1;';
	$stmt = $con->prepare($sql);
	$stmt->bind_param('s', $obj->post_id);
	$stmt->execute();
	$stmt->bind_result($dislikes);
	$stmt->fetch();
	$stmt->close();
	$dislikes = -$dislikes;
	$sql = 'UPDATE `posts` SET `likes`=?, `dislikes`=? WHERE `post_id`=?;';
	$stmt = $con->prepare($sql);
	$stmt->bind_param('sss', $likes, $dislikes, $obj->post_id);
	$stmt->execute();
	$stmt->close();
	$con->close();	
?>