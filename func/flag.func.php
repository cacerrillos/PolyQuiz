<?
session_start();
include_once("config.func.php");
if(true || isset($_SESSION['is_admin'])){
	mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
	mysql_select_db($db_name) or die(mysql_error());
	$data = mysql_query("SELECT * FROM results ORDER BY id ASC;");
	while($info = mysql_fetch_array($data)){
		$thisip = $info['ip'];
		$thissession = $info['session'];
		$datainception = mysql_query("SELECT * FROM results WHERE `ip` = '".$thisip."' AND `session` = '".$thissession."'");
		$numrows = mysql_num_rows($datainception);
		if($numrows > 1){
			$troublemakers = mysql_query("UPDATE results SET flag = 'yes' WHERE `ip` = '".$thisip."' AND `session` = '".$thissession."'");
		}
	}
}
//header('Location: ' . $_SERVER['HTTP_REFERER']);
?>