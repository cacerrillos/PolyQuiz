<?
session_start();
include("img.class.php");
include_once("config.func.php");
if(isset($_SESSION["is_admin"])){
	$allowedExts = array("gif", "jpeg", "jpg", "png");
	$extension = end(explode(".", $_FILES["file"]["name"]));
	if ((($_FILES["file"]["type"] == "image/gif")
		|| ($_FILES["file"]["type"] == "image/jpeg")
		|| ($_FILES["file"]["type"] == "image/jpg")
		|| ($_FILES["file"]["type"] == "image/pjpeg")
		|| ($_FILES["file"]["type"] == "image/x-png")
		|| ($_FILES["file"]["type"] == "image/png"))
		&& ($_FILES["file"]["size"] < 1024000000)
		&& in_array($extension, $allowedExts)){
		if ($_FILES["file"]["error"] > 0) {
			//echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
		} else {
			//echo "Upload: " . $_FILES["file"]["name"] . "<br>";
			//echo "Type: " . $_FILES["file"]["type"] . "<br>";
			//echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
			//echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
			$hash = time().md5(time().$_FILES["file"]["name"]);
    		if (file_exists("../images/" . $hash.".".$extension)){
				//echo $hash.".".$extension." already exists. ";
			} else {
				move_uploaded_file($_FILES["file"]["tmp_name"],
				"../images/" . $hash.".".$extension);
				$quizuuid = $_POST['uuid'];
				$question = $_POST['num'];
				$mysqli = new mysqli($db_host, $db_user, $db_password);
				$mysqli -> select_db($db_name);
				if(mysqli_connect_errno()) {
					echo "Connection Failed: " . mysqli_connect_errno();
					exit();
				}
				if($stmt = $mysqli -> prepare("INSERT INTO images (uuid, url) VALUES (?,?)")){
					$url = $hash.".".$extension;
					$stmt -> bind_param("ss", $hash, $url);
					$stmt -> execute();
					$stmt -> close();
				} else {
					echo $mysqli->error;
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
						$temppp -> addImage($hash);
						mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
						mysql_select_db($db_name) or die(mysql_error());
						$ser = serialize($temppp);
						$query = mysql_query("UPDATE ".$quizuuid." SET images='$ser' WHERE id='$nummy'");
						mysql_close();
					} else {
						echo $mysqli -> error;
					}
				}
			}
		}
	} else {
		//echo "Invalid file";
	}
}
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>