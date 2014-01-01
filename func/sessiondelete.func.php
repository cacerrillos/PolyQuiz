<?
session_start();
include_once("config.func.php");
if(!isset($_SESSION["is_admin"])){
	header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
	if(isset($_GET['uuid'])){
	mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
	mysql_select_db($db_name) or die(mysql_error()); 
	
	$uuid = mysql_real_escape_string($_GET['uuid']);
	if($uuid=="all"){
		$query = mysql_query("TRUNCATE TABLE sessions");
	} else {
	$query = mysql_query("DELETE FROM sessions WHERE uuid='$uuid'");
	}
	mysql_close();
	header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
header('Location: ' . $_SERVER['HTTP_REFERER']);
}
?>