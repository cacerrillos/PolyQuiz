<?
include_once("../../../func/config.func.php");
class QQ {
	function insert($name, $house, $quiz, $date, $status){
		global $db_host, $db_user, $db_password, $db_name;
			$preuuid = md5($date.$name.$house.$quiz.time());
			$uuid = substr($preuuid, 0, 6);
			$key = substr($preuuid, 6, 2);
			$mysqli = new mysqli($db_host, $db_user, $db_password);
			$mysqli -> select_db($db_name);
			if(mysqli_connect_errno()){
				echo "Connection Failed: " . mysqli_connect_errno();
				exit();
			}
			if($stmt = $mysqli -> prepare("SELECT * FROM sessions WHERE uuid=? AND owner=?")){
				$stmt -> bind_param("ss", $uuid, $_SESSION['dbext']);
				$stmt -> execute();
				$stmt -> store_result();
				$numrows = $stmt->num_rows;
				$stmt -> close();
				if($numrows!=0){
					$this->insert($name, $house, $quiz, $date, $status);
				} else {
					$statusint = intval($status);
					if($stmt2 = $mysqli -> prepare("INSERT INTO sessions VALUES (?, ?, ?, ?, ?, ?, ?, ?, '1')")){
						$stmt2 -> bind_param("sssissss", $uuid, $key, $house, $statusint, $quiz, $date, $name, $_SESSION['dbext']);
						$stmt2 -> execute();
						$stmt2 -> close();
						$mysqli->select_db($db_name);
						if($stmt3 = $mysqli->prepare("INSERT INTO globalsession VALUES (?, ?)")){
							$stmt3->bind_param("ss", $uuid, $_SESSION['dbext']);
							$stmt3->execute();
							$stmt3->close();
						}
					} else {
						echo $mysqli->error;
					}
				}
			} else {
				echo $mysqli->error;
			}
			$mysqli -> close();
	}
	function open($uuid){
		global $db_host, $db_user, $db_password, $db_name;
		$mysqli = new mysqli($db_host, $db_user, $db_password);
		$mysqli -> select_db($db_name);
		if(mysqli_connect_errno()){
			echo "Connection Failed: " . mysqli_connect_errno();
			exit();
		}
		if($stmt = $mysqli -> prepare("UPDATE sessions SET status='1' WHERE uuid=? AND owner=?")){
			$stmt -> bind_param("ss", $uuid, $_SESSION['dbext']);
			$stmt -> execute();
			$stmt -> close();
		} else {
			echo $mysqli->error;
		}
		$mysqli -> close();
	}
	function close($uuid){
		global $db_host, $db_user, $db_password, $db_name;
		$mysqli = new mysqli($db_host, $db_user, $db_password);
		$mysqli -> select_db($db_name);
		if(mysqli_connect_errno()){
			echo "Connection Failed: " . mysqli_connect_errno();
			exit();
		}
		if($stmt = $mysqli -> prepare("UPDATE sessions SET status='0' WHERE uuid=? AND owner=?")){
			$stmt -> bind_param("ss", $uuid, $_SESSION['dbext']);
			$stmt -> execute();
			$stmt -> close();
		} else {
			echo $mysqli->error;
		}
		$mysqli -> close();
	}
	function show($uuid){
		global $db_host, $db_user, $db_password, $db_name;
		$mysqli = new mysqli($db_host, $db_user, $db_password);
		$mysqli -> select_db($db_name);
		if(mysqli_connect_errno()){
			echo "Connection Failed: " . mysqli_connect_errno();
			exit();
		}
		if($stmt = $mysqli -> prepare("UPDATE sessions SET score='1' WHERE uuid=? AND owner=?")){
			$stmt -> bind_param("ss", $uuid, $_SESSION['dbext']);
			$stmt -> execute();
			$stmt -> close();
		} else {
			echo $mysqli->error;
		}
		$mysqli -> close();
	}
	function dontshow($uuid){
		global $db_host, $db_user, $db_password, $db_name;
		$mysqli = new mysqli($db_host, $db_user, $db_password);
		$mysqli -> select_db($db_name);
		if(mysqli_connect_errno()){
			echo "Connection Failed: " . mysqli_connect_errno();
			exit();
		}
		if($stmt = $mysqli -> prepare("UPDATE sessions SET score='0' WHERE uuid=? AND owner=?")){
			$stmt -> bind_param("ss", $uuid, $_SESSION['dbext']);
			$stmt -> execute();
			$stmt -> close();
		} else {
			echo $mysqli->error;
		}
		$mysqli -> close();
	}
}
session_start();
$good = false;
if(isset($_SESSION["is_admin"])){
	if(isset($_POST['submit'])){
		$submit = $_POST['submit'];
		if($submit=="Create"){
			if(strlen($_POST['sessionname'])>0){
				$date = $_POST['date'];
				$name = $_POST['sessionname'];
				$house = $_POST['house'];
				$quiz = $_POST['quiz'];
				$status = $_POST['status'];
				$QQ = new QQ();
				$QQ->insert($name, $house, $quiz, $date, $status);
				$good = true;
			}
		} else if($submit=="Open"){
			$uuid = $_POST['uuid'];
			$QQ = new QQ();
			$QQ -> open($uuid);
			$good = true;
		} else if($submit=="Close"){
			$uuid = $_POST['uuid'];
			$QQ = new QQ();
			$QQ -> close($uuid);
			$good = true;
		}
	} else if(isset($_GET['uuid']) && isset($_GET['action'])) {
		if($_GET['action']=="open"){
			$uuid = $_GET['uuid'];
			$QQ = new QQ();
			$QQ -> open($uuid);
			$good = true;
		} else if($_GET['action']=="close"){
			$uuid = $_GET['uuid'];
			$QQ = new QQ();
			$QQ -> close($uuid);
			$good = true;
		} else if($_GET['action']=="show"){
			$uuid = $_GET['uuid'];
			$QQ = new QQ();
			$QQ -> show($uuid);
			$good = true;
		} else if($_GET['action']=="dontshow"){
			$uuid = $_GET['uuid'];
			$QQ = new QQ();
			$QQ -> dontshow($uuid);
			$good = true;
		}
	}
}
if($good){
	echo '{ "status": true }';
} else {
	echo '{ "status": false }';
}

?>