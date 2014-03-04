<?
session_start();
/*
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
				if($stmt = $mysqli -> prepare("INSERT INTO users (id,username,password,permissionsid) VALUES ('',?,?,?)")){
					$stmt -> bind_param("sss", $_POST['username'], md5($_POST['password']), md5($_POST['username'].md5($_POST['password'])));
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
*/
header('Location: ../?p=register');
?>