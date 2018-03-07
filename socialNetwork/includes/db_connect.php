<?php 
	include_once($_SERVER['DOCUMENT_ROOT'] . '/socialNetwork/includes/init.php');
	$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);	
	if ($con->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
	}
?>	