<?
session_start();
include_once("../../../func/config.func.php");
//include_once("../../../func/genquiz.func.php");
include_once("../objects/polyquiz.php");
$status = array();
$status['status'] = false;
if(isset($_SESSION["is_admin"]) && isset($_GET['sessionname']) && isset($_GET['quiz']) && isset($_GET['house']) && isset($_GET['status']) && isset($_GET['show'])){
	$mysqli = new mysqli($db_host, $db_user, $db_password);
	$mysqli -> select_db($db_name);
	
	$obj = PolySession::createCheckMysql($mysqli);
	$obj->owner = $_SESSION['dbext'];
	
	$obj->name = $_GET['sessionname'];
	$obj->quiz = $_GET['quiz'];
	$obj->data['house'] = $_GET['house'];
	if($_GET['status']=="open"){
		$obj->setStatus(true);
	} else if($_GET['status']=="closed"){
		$obj->setStatus(false);
	} else {
		//false
	}
	if($_GET['show']=="show"){
		$obj->setShowScores(true);

	} else if($_GET['show']=="dontshow"){
		$obj->setShowScores(false);
	} else {
		//false
	}
	
	if($obj->saveToMysql($mysqli, $_SESSION['dbext'])){
		$status['status'] = true;
	}
}
echo json_encode($status);
?>