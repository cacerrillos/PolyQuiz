<?
session_start();

include_once("config.func.php");
if(isset($_POST['username'])){
	if(strlen($_POST['username'])>0 && strlen($_POST['password'])>0){
		$mysqli = new mysqli($db_host, $db_user, $db_password);
		$mysqli -> select_db($db_name);
		if(mysqli_connect_errno()) {
			echo "Connection Failed: " . mysqli_connect_errno();
			exit();
		}
		if($stmt = $mysqli -> prepare("SELECT * FROM users WHERE username = ?")){
			$stmt -> bind_param("s", $_POST['username']);
			$stmt -> execute();
			$stmt -> store_result();
			$num = $stmt -> num_rows;
			$stmt -> close();
			if($num==0){
				$permsid = md5($_POST['username'].md5($_POST['password']));
				if($stmt = $mysqli -> prepare("INSERT INTO stats VALUES (?, ?);")){
					$zero = "0";
					$ext = "_".$permsid;
					$stmt -> bind_param("ss", $ext, $zero);
					$stmt -> execute();
					$stmt -> close();
				} else {
					error_log($mysqli->error);
				}
				if($stmt = $mysqli -> prepare("INSERT INTO users (id,username,password,permissionsid) VALUES ('',?,?,?)")){
					$stmt -> bind_param("sss", $_POST['username'], md5($_POST['password']), $permsid);
					$stmt -> execute();
					$stmt -> close();
					header('Location: ../?p=admin');
					exit();
				} else {
					echo $mysqli->error;
				}
			} else {
				//return with error
				header('Location: ../?p=register&error=1');
				exit();
			}
		} else {
			echo $mysqli->error;
		}
	}
}

header('Location: ../?p=register');
?>