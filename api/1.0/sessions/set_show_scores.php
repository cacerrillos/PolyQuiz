<?
session_start();
include_once("../../../func/config.func.php");
//include_once("../../../func/genquiz.func.php");
include_once("../objects/polyquiz.php");
$status = array();
$status['status'] = false;
if(isset($_SESSION["is_admin"]) && isset($_GET['uuid']) && isset($_GET['show'])){
	$mysqli = new mysqli($db_host, $db_user, $db_password);
	$mysqli -> select_db($db_name);
	$obj = PolySession::fromMySQL($mysqli, $_GET['uuid'], $_SESSION['dbext']);
	if($obj){
		if($_GET['show']=="true"){
			$obj->setShowScores(true);
			if($obj->saveToMysql($mysqli, $_SESSION['dbext'])){
				$status['status'] = true;
			}
		} else if($_GET['show']=="false"){
			$obj->setShowScores(false);
			if($obj->saveToMysql($mysqli, $_SESSION['dbext'])){
				$status['status'] = true;
			}
		} else {
			//false
		}
	}
}
echo json_encode($status);
?>