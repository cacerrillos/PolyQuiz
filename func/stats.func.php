<?
include_once("config.func.php");
mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
mysql_select_db($db_name) or die(mysql_error()); 
$dataquiz = mysql_query("SELECT id FROM results;");
$num = mysql_num_rows($dataquiz);
echo "<font size='48'>$num</font>";
$size = mysql_query('SELECT table_schema $db_name, sum( data_length + index_length ) / 1024 / 1024 "Size" FROM information_schema.TABLES GROUP BY table_schema ;');
while($info = mysql_fetch_assoc($size)){
	if($info[$db_name]==$db_name){
		echo "<br>Size: ".$info['Size']."<br>";
	}
}
?>