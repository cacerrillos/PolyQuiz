<?
session_start();
include_once("../../../func/config.func.php");
include_once("../objects/polyquiz.php");
$debug = false;
if(isset($_SESSION["is_admin"])){
	if(isset($_GET['uuid'])){
		$uuid = $_GET['uuid'];
	} else {
		exit();
	}
	$mysqli = new mysqli($db_host, $db_user, $db_password);
	$mysqli -> select_db($db_name);
	
	echo json_encode(PolyStats::getStats($mysqli, $uuid, $_SESSION['dbext'], false));
	exit();
	
	if($debug) {
		echo "<pre>".json_encode($resultObject, JSON_PRETTY_PRINT)."</pre>";
	} else {
		echo json_encode($resultObject);
	}
	exit();
}

?>