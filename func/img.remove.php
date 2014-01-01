<?
session_start();
include_once("config.func.php");
if(isset($_SESSION["is_admin"])){
	include("img.class.php");
	mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
	mysql_select_db($db_name) or die(mysql_error());
	$quizuuid = $_GET['uuid'];
	$question = $_GET['num'];
	$imguuid = $_GET['img'];
	$quizuuid = mysql_real_escape_string($quizuuid);
	$question = mysql_real_escape_string($question);
	$imguuid = mysql_real_escape_string($imguuid);
	$mysqli = new mysqli($db_host, $db_user, $db_password);
	$mysqli -> select_db($db_name);
	if(mysqli_connect_errno()) {
		echo "Connection Failed: " . mysqli_connect_errno();
		exit();
	}
	if($stmt = $mysqli -> prepare("SELECT images FROM ".$quizuuid." WHERE id = ?")){
		$nummy = intval($question);
		$stmt -> bind_param("i", $nummy);
		$stmt -> execute();
		$stmt -> bind_result($result);
		$stmt -> store_result();
		$stmt -> fetch();
		$stmt -> close();
		if($result!=null && $result!=""){
			$temppp = unserialize($result);
		} else {
			$temppp = new imageGroup();
		}
		$temppp -> removeImage($imguuid);

		$ser = serialize($temppp);
		$query = mysql_query("UPDATE ".$quizuuid." SET images='$ser' WHERE id='$nummy'");
		mysql_close();
	} else {
		echo $mysqli -> error;
	}
}
header('Location: ' . $_SERVER['HTTP_REFERER']);

?>