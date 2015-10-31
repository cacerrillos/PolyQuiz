<?
session_start();
include_once("config.func.php");
$suc = false;
if(isset($_POST['oldpass']) && isset($_POST['pass1']) && isset($_POST['pass2'])){
	if(strlen($_POST['oldpass'])>0 && strlen($_POST['pass1'])>0 && strlen($_POST['pass2'])>0){
		$mysqli = new mysqli($db_host, $db_user, $db_password);
		$mysqli -> select_db($db_name);
		if(mysqli_connect_errno()) {
			echo "Connection Failed: " . mysqli_connect_errno();
			exit();
		}
		if($_POST['pass1']==$_POST['pass2']){
			$permid = substr($_SESSION['dbext'], 1);
			if($stmt = $mysqli -> prepare("SELECT id FROM users WHERE permissionsid = ? AND password = ?")){
				$stmt -> bind_param("ss", $permid, md5($_POST['oldpass']));
				$stmt -> execute();
				$stmt -> bind_result($data['id']);
				$stmt -> store_result();
				$stmt -> fetch();
				$num = $stmt -> num_rows;
				$stmt -> close();
			} else {
				echo $mysqli->error;
			}
			if($num==1){
				$newpass = md5($_POST['pass1']);
				if($stmt = $mysqli -> prepare("UPDATE `users` SET password = ? WHERE permissionsid = ?")){
					$stmt -> bind_param("ss", $newpass, $permid);
					$stmt -> execute();
					$stmt -> close();
					$suc = true;
				} else {
					echo $mysqli->error;
				}
			}
		}
	}
}
if($suc){
	session_start();
	session_destroy();
	header("Location: ../?p=admin");
} else {
	header('Location: ' . $_SERVER['HTTP_REFERER']);
}
?>