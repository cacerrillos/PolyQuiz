<?
session_start();
include_once("config.func.php");
include_once("question.obj.php");
if(isset($_SESSION["is_admin"])){
	if(isset($_GET['delete'])){
		$mysqli = new mysqli($db_host, $db_user, $db_password);
		$mysqli -> select_db($_SESSION['dbext']);
		if(mysqli_connect_errno()) {
			error_log(mysqli_connect_errno());
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		} else {
			$uuid = $mysqli -> real_escape_string($_GET['delete']);
			if($stmt = $mysqli->prepare("SELECT * FROM `quizes` WHERE uuid = ?;")){
				$stmt->bind_param("s", $uuid);
				$stmt->execute();
				$stmt->store_result();
				$num = $stmt->num_rows;
				$stmt->close();
				if($num==1){
					if($result = $mysqli->query("DROP TABLE IF EXISTS `".$uuid."`")){
					} else {
						error_log($mysqli->error);
					}
					if($stmt = $mysqli->prepare("DELETE FROM `quizes` WHERE uuid=?;")){
						$stmt->bind_param("s", $uuid);
						$stmt->execute();
					} else {
						error_log($mysqli->error);
					}
				} else {
					header('Location: ' . $_SERVER['HTTP_REFERER']);
				}
			} else {
				error_log($mysqli->error);
			}
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
	}
} else {
	header('Location: ' . $_SERVER['HTTP_REFERER']);
}
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>