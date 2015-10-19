<?
	session_start();
	$result = array();
	$result['admin'] = false;
	if(isset($_SESSION['dbext'])){
		$result['admin'] = true;
		if(isset($_SESSION['email'])){
			$result['email'] = $_SESSION['email'];
		}
		if(isset($_SESSION['name'])){
			$result['name'] = $_SESSION['name'];
		}
	}
	echo json_encode($result);
?>