<?
session_start();
include_once("config.func.php");
include_once("genquiz.func.php");
if(isset($_SESSION["is_admin"]) && isset($_GET['uuid'])){
	if(hasPermissions($_GET['uuid'])) {
		$mysqli = new mysqli($db_host, $db_user, $db_password);
		$mysqli -> select_db($db_name);
		$uuid = $_GET['uuid'];
		if($stmt = $mysqli->prepare("SELECT quizname FROM `quizzes` WHERE uuid=?;")){
			$stmt->bind_param("s", $uuid);
			$stmt->execute();
			$stmt->bind_result($name);
			while($stmt->fetch()) {
				$quizName = $name;
			}
			$stmt->close();
			if($quizName != null && $quizName != "") {
				$temp = new quizFromMysql();
				$generatedquiz = $temp -> createQuiz($uuid);
				header("Content-type: text/plain");
				header("Content-Disposition: attachment; filename=".$quizName.".polyquiz");
				echo base64_encode(serialize($generatedquiz));
			}
		} else {
			echo $mysqli->error;
		}
		$mysqli->close();
	}
}
?>