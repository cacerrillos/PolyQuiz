<?
session_start();
include_once("config.func.php");
if(!isset($_SESSION["is_admin"])){
	header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
if($_POST['submit']=="Create"){
	if(!strlen($_POST['quizname'])>0){
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	$quizname = $_POST['quizname'];
	$status = intval($_POST['status']);
	$ts = time();
	$uuid = md5($quizname.$ts);
	$mysqli = new mysqli($db_host, $db_user, $db_password);
	$mysqli -> select_db($db_name);
	if(mysqli_connect_errno()) {
		echo "Connection Failed: " . mysqli_connect_errno();
		exit();
	}
	if($stmt = $mysqli -> prepare("INSERT INTO quizes (uuid, quizname, quizsubject, status) VALUES (?,?,'',?)")){
		$stmt -> bind_param("ssi", $uuid, $quizname, $status);
		$stmt -> execute();
		$stmt -> close();
		//echo var_dump($quiz);
	} else {
		echo $mysqli->error;
	}
		mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
		mysql_select_db($db_name) or die(mysql_error());
		$data = mysql_query("DROP TABLE IF EXISTS `".mysql_real_escape_string($uuid)."`;");
		$query2 = mysql_query(
		"CREATE TABLE `".mysql_real_escape_string($uuid)."` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` text,
  `images` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;"
);
header('Location: ' . $_SERVER['HTTP_REFERER']);
}
}

?>