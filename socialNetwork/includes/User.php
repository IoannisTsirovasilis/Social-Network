<?php
	class User {
		public function insertUser($username, $email, $password) {
			include($_SERVER['DOCUMENT_ROOT'] . '/socialNetwork/includes/db_connect.php');
			$password = password_hash($password, PASSWORD_DEFAULT);
			$sql = 'INSERT INTO `users` (`username`,`email`,`password`) VALUES(?,?,?);';
			$stmt = $con->prepare($sql);
			$stmt->bind_param('sss', $username, $email, $password);
			$stmt->execute();
			$stmt->close();
			$con->close();
		}
		
		public static function findUser($email) {
			include($_SERVER['DOCUMENT_ROOT'] . '/socialNetwork/includes/db_connect.php');
			$sql = 'SELECT `email` FROM `users` WHERE `email`=?;';
			$stmt = $con->prepare($sql);
			$stmt->bind_param('s', $email);
			$stmt->execute();
			$result = $stmt->get_result();		
			if ($result->num_rows === 1) {
				$stmt->close();
				$con->close();
				return TRUE;
			}
			$stmt->close();
			$con->close();
			return FALSE;		
		}
		
		public static function login($email, $password) {
			include($_SERVER['DOCUMENT_ROOT'] . '/socialNetwork/includes/db_connect.php');
			$user = null;
			$sql = 'SELECT * FROM `users` WHERE LOWER(`email`)=LOWER(?);';
			$stmt = $con->prepare($sql);
			$stmt->bind_param('s', $email);
			$stmt->execute();
			$result = $stmt->get_result();			
			if ($result->num_rows === 1) {
				$row = $result->fetch_assoc();
				if (password_verify($password, $row['password'])) {
					$user['id'] = $row['id'];
					$user['username'] = $row['username'];
					$user['email'] = $row['email'];
					$sql = 'UPDATE `users` SET `status` = 1 WHERE `id`=?;';
					$stmt = $con->prepare($sql);
					$stmt->bind_param('s', $user['id']);
					$stmt->execute();					
				}
			}
			$stmt->close();
			$con->close();
			return $user;
		}
		
		public static function changePassword($id, $oldPass, $newPass) {
			include($_SERVER['DOCUMENT_ROOT'] . '/socialNetwork/includes/db_connect.php');
			$sql = 'SELECT `password` FROM `users` WHERE `id`=?;';
			$stmt = $con->prepare($sql);
			$stmt->bind_param('s', $id);
			$stmt->execute();
			$result = $stmt->get_result();
			if ($row = $result->fetch_assoc()) {
				if (password_verify($oldPass, $row['password'])) {
					$newPass = password_hash($newPass, PASSWORD_DEFAULT);
					$sql = 'UPDATE `users` SET `password`=? WHERE `id`=?;';
					$stmt = $con->prepare($sql);
					$stmt->bind_param('ss', $newPass, $id);
					$stmt->execute();
					$stmt->close();
					$con->close();
					return TRUE;
				}
			}
			$stmt->close();
			$con->close();
			return FALSE;
		}
		
		public static function fetchUser($userId) {
			include($_SERVER['DOCUMENT_ROOT'] . '/socialNetwork/includes/db_connect.php');
			$user = null;
			$sql = 'SELECT * FROM `users` WHERE `id`=?;';
			$stmt = $con->prepare($sql);
			$stmt->bind_param('s', $userId);
			$stmt->execute();
			$result = $stmt->get_result();			
			if ($result->num_rows === 1) {
				$row = $result->fetch_assoc();				
				$user['id'] = $row['id'];
				$user['username'] = $row['username'];
				$user['email'] = $row['email']; // can be omitted
				$user['avatar'] = $row['avatar'];
			}
			$stmt->close();
			$con->close();
			return $user;
		}
		
		public static function friendRequestStatus($from, $to) {
			include($_SERVER['DOCUMENT_ROOT'] . '/socialNetwork/includes/db_connect.php');
			$res = null;
			$sql = 'SELECT * FROM `requests` WHERE `id_from`=? AND `id_to`=? AND `status`=0;';
			$stmt = $con->prepare($sql);
			$stmt->bind_param('ss', $from, $to);
			$stmt->execute();
			$result = $stmt->get_result();
			if ($result->num_rows > 0) {
				$res = new stdClass();
				$res->message = 'Friend request has been sent!';
				$res->status = 0;
			} else {
				$stmt->bind_param('ss', $to, $from);
				$stmt->execute();
				$result = $stmt->get_result();
				if ($result->num_rows > 0) {
					$res = new stdClass();
					$res->message = 'Awaiting for response...';
					$res->status = -1;
				} else {
					$sql = 'SELECT * FROM `requests` WHERE `id_from`=? AND `id_to`=? AND `status`=1;';
					$stmt = $con->prepare($sql);
					$stmt->bind_param('ss', $from, $to);
					$stmt->execute();
					$result = $stmt->get_result();
					if ($result->num_rows > 0) {
						$res = new stdClass();
						$res->message = '';
						$res->status = 1;
					}
				}
			}
			$stmt->close();
			$con->close();
			return $res;
		}
	}
?>