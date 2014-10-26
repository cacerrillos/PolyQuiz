<?
session_start();
include_once("config.func.php");
function resetnumbers($uuidr){
	global $db_host, $db_user, $db_password, $db_name;
	if(hasPermissions($uuidr)){
		mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
		mysql_select_db($db_name) or die(mysql_error());
		$query = mysql_query("SELECT * FROM `".$uuidr."` ORDER BY id ASC;");
		$number = mysql_num_rows($query);
		$counter = 1;
		while($info = mysql_fetch_array($query)){
			$queryy = mysql_query("UPDATE `".$uuidr."` SET id='$counter' WHERE id='$info[id]'");
			$counter++;
		}
		mysql_close();
	}
}
if(isset($_SESSION["is_admin"])){
	if(isset($_POST['submit'])){
		if(isset($_POST['fromuuid']) && isset($_POST['touuid'])){
			$mysqli = new mysqli($db_host, $db_user, $db_password);
			$mysqli -> select_db($db_name);
			$status = intval($_POST['status']);
			$ids = array();
			$objects = array();
			//$images = array();
			if($stmt = $mysqli->prepare("SELECT * FROM `". mysqli_real_escape_string($mysqli, $_POST['fromuuid']) ."`;")){
				//$stmt->bind_param("iss", $status, $_POST['uuid'], $_SESSION['dbext']);
				$stmt->execute();
				$stmt->bind_result($id, $object, $images);
				$counter = 0;
				while($stmt->fetch()){
					$ids[$counter] = $id;
					$objects[$id] = $object;
					//$images[$id] = $images;
					$counter++;
				}
				$stmt->close();
				for($x = 0; $x < count($ids); $x++) {
					if($stmtt = $mysqli -> prepare("INSERT INTO `".mysqli_real_escape_string($mysqli, $_POST['touuid'])."` (`id`, `object`) VALUES (?,?);")){
						$stmtt -> bind_param("is",$y = 0, $objects[$ids[$x]]);
						$stmtt -> execute();
						$stmtt -> close();
					} else {
						echo $mysqli->error;
					}
				}
				resetnumbers(mysqli_real_escape_string($mysqli, $_POST['touuid']));
			} else {
				echo $mysqli->error;
			}
			$mysqli->close();
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		} else {
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
	}
}

?>