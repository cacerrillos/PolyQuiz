<?
	session_start();
	$result = array();
	$result['admin'] = false;
	if(isset($_SESSION['dbext'])){
		$result['admin'] = true;
	}
	echo json_encode($result);
?>