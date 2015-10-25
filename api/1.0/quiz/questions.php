<?
session_start();
include_once("../../../func/config.func.php");
//include_once("../../../func/genquiz.func.php");
include_once("../objects/polyquiz.php");
$debug = false;
$resultObject = array();
$resultObject['status'] = false;

if(isset($_SESSION["is_admin"])){
	$mysqli = new mysqli($db_host, $db_user, $db_password);
	$mysqli -> select_db($db_name);
	if(isset($_GET['uuid'])){
		$uuid = $_GET['uuid'];
		$resultObject = PolyQuiz::fromMySQL($mysqli, $uuid);
	}
}
if($debug) {
	echo "<pre>".json_encode($resultObject, JSON_PRETTY_PRINT)."</pre>";
} else {
	echo json_encode($resultObject);
}

?>