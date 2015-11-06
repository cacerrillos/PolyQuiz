<?

$app->get('/status', function() {
	$result = array();
	if(isset($_SESSION["is_admin"])) {
		$result['admin'] = true;
	} else {
		$result['admin'] = false;
	}
	echo json_encode($result);
});

$app->post('/login', function() {
	global $mysqli;
	session_destroy();
	session_start();

	global $_POST_JSON;

	$result = array();
	$result['status'] = false;
	$result['user'] = $_POST_JSON['user'];
	if(count($_POST_JSON) > 0){
		if(mysqli_connect_errno()) {
			$result['status_details'] = "Connection Failed: " . mysqli_connect_errno();
		} else {
			if($stmt = $mysqli -> prepare("SELECT `id`, `email`, `name` FROM users WHERE email = ? AND password = ?")){
				$stmt -> bind_param("ss", $_POST_JSON['user'], md5($_POST_JSON['pass']));
				$stmt -> execute();
				$stmt -> bind_result($data['id'], $data['email'], $data['name']);
				$stmt -> store_result();
				$stmt -> fetch();
				$num = $stmt -> num_rows;
				$stmt -> close();
			} else {
				$result['status_details'] = $mysqli->error;
			}
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
	echo json_encode($result, JSON_PRETTY_PRINT);
});

$app->post('/logout', function() {
	session_destroy();
	$status = array();
	$status['status'] = true;
	echo json_encode($status, JSON_PRETTY_PRINT);
});

?>
