<?
session_start();
session_destroy();
session_start();
include_once("../../../func/config.func.php");

$_POST = json_decode(file_get_contents("php://input"), true);

$result = array();
$result['status'] = false;
$result['user'] = $_POST['user'];
if(count($_POST) > 0){
	$mysqli = new mysqli($db_host, $db_user, $db_password);
	$mysqli -> select_db($db_name);
	if(mysqli_connect_errno()) {
		$result['status_details'] = "Connection Failed: " . mysqli_connect_errno() . " " . $mysqli->error;
		$result['dbinfo'] = $db_array;
		$result['env'] = $_SERVER;
	} else {
		if($stmt = $mysqli -> prepare("SELECT `id`, `email`, `name` FROM users WHERE email = ? AND password = ?")){
			$stmt -> bind_param("ss", $_POST['user'], md5($_POST['pass']));
			$stmt -> execute();
			$stmt -> bind_result($data['id'], $data['email'], $data['name']);
			$stmt -> store_result();
			$stmt -> fetch();
			$num = $stmt -> num_rows;
			$stmt -> close();
		} else {
			$result['status_details'] = $mysqli->error;
		}
		$mysqli -> close();
		if($num==1){
			$_SESSION['is_admin'] = "set";
			$_SESSION['email'] = $data['email'];
			$_SESSION['name'] = $data['name'];
			$_SESSION['dbext'] = $data['id'];
			$_SESSION['admin_id_num'] = $data['id'];
			$_SESSION['permsid'] = $data['permsid'];
			$result['status'] = true;
		}
	}
}
echo json_encode($result);
?>