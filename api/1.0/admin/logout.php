<?
	session_start();
	session_destroy();
	$status = array();
	$status['status'] = true;
	echo json_encode($status);
?>