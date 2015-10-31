<?
session_start();
include_once("../../../func/config.func.php");
//include_once("../../../func/genquiz.func.php");
include_once("../objects/polyquiz.php");
if(isset($_SESSION["is_admin"])){
	$mysqli = new mysqli($db_host, $db_user, $db_password);
	$mysqli -> select_db($db_name);
	echo json_encode(PolyHouse::getHouses($mysqli, $_SESSION['dbext']));
}


?>