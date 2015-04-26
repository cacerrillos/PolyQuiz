<?
//error_reporting(0);
error_reporting(E_ERROR);
date_default_timezone_set('America/Los_Angeles');
if(file_exists("func/count.func.php")){
	include_once("func/count.func.php");
} else if(file_exists("count.func.php")){
	include_once("count.func.php");
}
if(file_exists("func/permissions.obj.php")){
	include_once("func/permissions.obj.php");
} else if(file_exists("permissions.obj.php")){
	include_once("permissions.obj.php");
}
if(file_exists("func/secret.obj.php")){
	include_once("func/secret.obj.php");
} else if(file_exists("secret.obj.php")){
	include_once("secret.obj.php");
}
$db_host = "localhost";
$db_user = "polyquiz";
$db_password = "polyquiz";
$db_name = "polyquiz";
$db_array = array(
		"db_host" => $db_host,
		"db_user" => $db_user,
		"db_password" => $db_password,
		"db_name" => $db_name);
$alphabet = array(
		0 => "A",
		1 => "B",
		2 => "C",
		3 => "D",
		4 => "E",
		5 => "F",
		6 => "G",
		7 => "H",
		8 => "I",
		9 => "J",
		10 => "K",
		11 => "L",
		12 => "M",
		13 => "N",
		14 => "O",
		15 => "P",
		16 => "Q",
		17 => "R",
		18 => "S",
		19 => "T",
		20 => "U",
		21 => "V",
		22 => "W",
		23 => "X",
		24 => "Y",
		25 => "Z");
define("MULTIPLE", 0);
define("FREERESPONSE", 1);
define("MATCHING", 2);
define("SURVEY", 3);
function getDBExt($uuid){
	global $db_host, $db_user, $db_password, $db_name;
	$mysqli = new mysqli($db_host, $db_user, $db_password);
	$mysqli -> select_db($db_name);
	if(mysqli_connect_errno()) {
		echo "Connection Failed: " . mysqli_connect_errno();
		exit();
	}
	if($stmt = $mysqli->prepare("SELECT * FROM `globalsession` WHERE uuid = ?")){
		$stmt->bind_param("s", $uuid);
		$stmt->execute();
		$stmt->bind_result($notdb, $db);
		$stmt->store_result();
		$stmt->fetch();
		$stmt->close();
	} else {
		echo $mysqli->error;
	}
	$mysqli->close();
	return $db;
}
function hasPermissions($uuid){
	global $db_host, $db_user, $db_password, $db_name;
	$mysqli = new mysqli($db_host, $db_user, $db_password);
	$mysqli -> select_db($db_name);
	if(mysqli_connect_errno()) {
		echo "Connection Failed: " . mysqli_connect_errno();
		exit();
	}
	$flag = false;
	if($stmt = $mysqli->prepare("SELECT * FROM `quizzes` WHERE uuid = ? AND owner = ?")){
		$stmt->bind_param("ss", $uuid, $_SESSION['dbext']);
		$stmt->execute();
		$stmt->store_result();
		$numrows = $stmt->num_rows;
		if($numrows==1){
			$flag = true;
		}
		$stmt->close();
	} else {
		echo $mysqli->error;
	}
	$mysqli->close();
	return $flag;
}
?>