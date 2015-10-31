<?
session_start();
include_once("../../../func/config.func.php");
//include_once("../../../func/genquiz.func.php");
include_once("../objects/polyquiz.php");
$status = array();
$status['status'] = false;
if(isset($_SESSION["is_admin"]) && isset($_GET['uuid']) && isset($_GET['name'])){
	$mysqli = new mysqli($db_host, $db_user, $db_password);
	$mysqli -> select_db($db_name);
	$obj = PolySession::fromMySQL($mysqli, $_GET['uuid'], $_SESSION['dbext']);
	if($obj){
		$obj->name = $_GET['name'];
		if($obj->saveToMysql($mysqli, $_SESSION['dbext'])){
			$status['status'] = true;
		}
	}
}
echo json_encode($status);
?>