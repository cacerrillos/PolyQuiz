<?
session_start();
include_once("config.func.php");
if($_POST['submit']=="Login"){
	$mysqli = new mysqli($db_host, $db_user, $db_password);
	$mysqli -> select_db($db_name);
	if(mysqli_connect_errno()) {
		echo "Connection Failed: " . mysqli_connect_errno();
		exit();
	}
	if($stmt = $mysqli -> prepare("SELECT * FROM users WHERE username = ? AND password = ?")){
		$stmt -> bind_param("ss", $_POST['user'], md5($_POST['pass']));
		$stmt -> execute();
		$stmt -> bind_result($data['id'], $data['username'], $data['passwordHash'], $data['permsid']);
		$stmt -> store_result();
		$stmt -> fetch();
		$num = $stmt -> num_rows;
		$stmt -> close();
	} else {
		echo $mysqli->error;
	}
	$mysqli -> close();
	if($num==1){
		$_SESSION['is_admin'] = "set";
		$_SESSION['username'] = $_POST['user'];
		$_SESSION['dbext'] = "_".$data['permsid'];
		$_SESSION['admin_id_num'] = $data['id'];
		$_SESSION['permsid'] = $data['permsid'];
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
}
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>