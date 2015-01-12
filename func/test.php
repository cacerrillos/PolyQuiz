<? session_start();
	error_reporting(E_ALL);
	include_once("config.func.php");
	include_once("genquiz.func.php");
	echo  $_SESSION['dbext']."<br>";	
    function getQuiz($studentuuid){
		global $db_host, $db_user, $db_password, $db_name;
		$mysqli = new mysqli($db_host, $db_user, $db_password);
		$mysqli -> select_db($db_name);
		if(mysqli_connect_errno()) {
			echo "Connection Failed: " . mysqli_connect_errno();
			exit();
		}
		if($stmt = $mysqli -> prepare("SELECT * FROM `results` WHERE `id` = ? AND `owner` = ?;")){
				$stmt -> bind_param("is", intval($studentuuid), $_SESSION['dbext']);
				$stmt -> execute();
				$stmt -> store_result();
				$num = $stmt -> num_rows;
				$stmt -> close();
		} else {
			echo $mysqli->error;
		}
		var_dump($num);
		if($num==0){
			return false;
		} else {
			if($stmt = $mysqli->prepare("SELECT `object` FROM `results` WHERE `id` = ? AND `owner` = ?;")){
				$stmt->bind_param("is", intval($studentuuid), $_SESSION['dbext']);
				$stmt->execute();
				$stmt->store_result();
				$stmt->bind_result($object);
				while($stmt->fetch()){
					$quiz = unserialize($object);
				}
				$stmt->close();
			} else {
				echo $mysqli->error;
			}
			$mysqli->close();
			return $quiz;
		}
		$mysqli->close();
	}
	
	$quiz = getQuiz("3162");
	var_dump($quiz);
    ?>