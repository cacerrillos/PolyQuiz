<?
session_start();
include_once("config.func.php");
if(isset($_SESSION["is_admin"])){
	include("img.class.php");
	$mysqli = new mysqli($db_host, $db_user, $db_password);
	$mysqli -> select_db($db_name);
	$quizuuid = $_GET['uuid'];
	$question = $_GET['num'];
	$imguuid = $_GET['img'];
	$quizuuid = mysqli_real_escape_string($mysqli, $quizuuid);
	$question = mysqli_real_escape_string($mysqli, $question);
	$imguuid = mysqli_real_escape_string($mysqli, $imguuid);
	
	if(mysqli_connect_errno()) {
		echo "Connection Failed: " . mysqli_connect_errno();
		exit();
	}
	if(hasPermissions($quizuuid)){
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
			if($stmt = $mysqli->prepare("DELETE FROM images WHERE uuid=?")){
				$stmt->bind_param("s", $imguuid);
				$stmt->execute();
				$stmt->close();
			} else {
				echo $mysqli->error;
			}
		} else {
			echo $mysqli -> error;
		}
		$mysqli->close();
	}
}
header('Location: ' . $_SERVER['HTTP_REFERER']);

?>