<?
session_start();
include_once("config.func.php");
include_once("genquiz.func.php");
include_once("question.obj.php");
if(isset($_SESSION["is_admin"])){
	if(isset($_POST['submit'])){
		$uuid = $_POST['uuid'];	
		$mysqli = new mysqli($db_host, $db_user, $db_password);
		$mysqli -> select_db($db_name);
		if(mysqli_connect_errno()){
			echo "Connection Failed: " . mysqli_connect_errno();
			exit();
		}
		$numrows = 0;
		if($stmt = $mysqli -> prepare("SELECT * FROM results WHERE id=? AND owner = ?")){
			$stmt -> bind_param("ss", $uuid, $_SESSION['dbext']);
			$stmt -> execute();
			$stmt -> store_result();
			$numrows = $stmt->num_rows;
			$stmt -> bind_result($info['id'], $info['firstname'], $info['lastname'], $info['quizuuid'], $info['rawscore'], $info['possiblescore'], $info['percentage'], $info['datestamp'], $info['timestamp'], $info['ip'], $info['house'], $info['session'], $info['object'], $info['flag'], $info['frscore'], $info['frpossible'], $info['frgraded'], $info['owner']);
			$stmt -> fetch();
			$stmt -> close();
		}
		if($numrows>0){
			$quiz = unserialize($info['object']);
			$frt = 0;
			for($x = 0; $x < $quiz->numberofquestions; $x++){
				if($quiz->questions[$x]->type==1){
					$quiz->questions[$x]->pointsEarned = $_POST['score'.$x];
					$frt += $_POST['score'.$x];
				}
			}
			$quiz->setFRGraded();
			if($stmt = $mysqli -> prepare("UPDATE results SET object=?, frscore=?, frgraded='1' WHERE id=? AND owner = ?;")){
				$stmt -> bind_param("siis", serialize($quiz), $frt, $uuid, $_SESSION['dbext']);
				$stmt -> execute();
				$stmt -> close();
			} else {
				echo $mysqli->error;
			}
		}
		$mysqli -> close();
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
} else {
	header('Location: ' . $_SERVER['HTTP_REFERER']);
}
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>