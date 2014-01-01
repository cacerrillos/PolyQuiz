<?
session_start();
include_once("config.func.php");
if(isset($_SESSION["is_admin"])){
	if(!isset($_GET['uuid'])){
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	} else {
		mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
		mysql_select_db($db_name) or die(mysql_error());
		$uuid = mysql_real_escape_string($_GET['uuid']);
		$data = mysql_query("SELECT * FROM ".$uuid." ORDER BY id ASC;");
		$numrows = mysql_num_rows($data);
		$counter = 1;
		while($info = mysql_fetch_array($data)){
			$query = mysql_query("UPDATE ".$uuid." SET id='$counter' WHERE id='".$info['id']."';");
			$counter += 1;
		}
	}
	header('Location: ' . $_SERVER['HTTP_REFERER']);
}
?>