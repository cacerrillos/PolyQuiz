<?
session_start();
include_once("../../../func/config.func.php");
if(isset($_SESSION["is_admin"])){
	if(isset($_GET['uuid'])){
		$mysqli = new mysqli($db_host, $db_user, $db_password);
		$mysqli -> select_db($db_name);
		if($_GET['uuid']=="all"){
			if($stmt = $mysqli->prepare("DELETE FROM `sessions` WHERE `owner`=?;")){
				$stmt->bind_param("s", $_SESSION['dbext']);
				$stmt->execute();
				$stmt->close();
			} else {
				echo $mysqli->error;
			}
			echo '{ "status": true }';
			exit();
		} else {
			if($stmt = $mysqli->prepare("DELETE FROM `sessions` WHERE `owner` = ? AND `uuid` = ?;")){
				$stmt->bind_param("ss", $_SESSION['dbext'], $_GET['uuid']);
				$stmt->execute();
				$stmt->close();
			} else {
				echo $mysqli->error;
			}
			echo '{ "status": true }';
			exit();
		}
	}
}
echo '{ "status": false }';
?>