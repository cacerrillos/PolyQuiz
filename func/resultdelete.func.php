<?
session_start();
include_once("config.func.php");
include_once("../securimage/securimage.php");
$securimage = new Securimage();
if(!isset($_SESSION["is_admin"])){
	header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
	mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
	mysql_select_db($db_name) or die(mysql_error()); 
	if(isset($_GET['uuid'])){
		$uuid = mysql_real_escape_string($_GET['uuid']);
		if($uuid=="all"){
			$query = mysql_query("DELETE FROM results WHERE owner = '".$_SESSION['dbext']."'");
		} else if(!is_int($uuid) && isset($_GET['house'])){
			$house = $_GET['house'];
			$query = mysql_query("DELETE FROM results WHERE quizuuid='$uuid' AND house='$house' AND owner='".$_SESSION['dbext']."';");
		} else {
			$query = mysql_query("DELETE FROM results WHERE id='$uuid' AND owner='".$_SESSION['dbext']."'");
		}
		mysql_close();
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	} else if(isset($_POST['uuid'])) {
		$tempString = preg_replace('/\s+/', '', $_POST['uuid'].$_POST['house']);
		if ($securimage->check($_POST['captcha_code'.$tempString]) == false) {
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		} else {
			$uuid = mysql_real_escape_string($_POST['uuid']);
			$house = $_POST['house'];
			$query = mysql_query("DELETE FROM results WHERE quizuuid='$uuid' AND house='$house' AND owner='".$_SESSION['dbext']."';");
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
	}
header('Location: ' . $_SERVER['HTTP_REFERER']);
}
?>