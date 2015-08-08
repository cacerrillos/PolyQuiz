<?
session_start();
include_once("../../../func/config.func.php");
include_once("../objects/polyquiz.php");
if(isset($_SESSION["is_admin"]) && isset($_GET['uuid'])){
	$mysqli = new mysqli($db_host, $db_user, $db_password);
	$mysqli -> select_db($db_name);
	echo json_encode(PolyHouse::getHouse($mysqli, $_GET['uuid'], $_SESSION['dbext']));
}
?>