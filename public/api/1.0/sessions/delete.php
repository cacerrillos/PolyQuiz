<?
session_start();
include_once("../../../func/config.func.php");
include_once("../objects/polyquiz.php");
$status = array();
$status['status'] = false;
if(isset($_SESSION["is_admin"]) && isset($_GET['uuid'])){
	$mysqli = new mysqli($db_host, $db_user, $db_password);
	$mysqli -> select_db($db_name);
	$session = PolySession::fromMySQL($mysqli, $_GET['uuid'], $_SESSION['dbext']);
	if($session){
		$res = $session->delete($mysqli, $_SESSION['dbext']);
		if($res === true){
			$status['status'] = true;
		} else {
			$status['status'] = false;
			$status['error'] = $res;
		}
	}
}
echo json_encode($status);
?>