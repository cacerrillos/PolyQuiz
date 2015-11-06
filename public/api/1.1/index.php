<?
session_start();
//error_reporting(E_ALL);
require 'Slim/Slim.php';
include_once("../../func/config.func.php");

include_once('objects/polyquiz.php');
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$mysqli = new mysqli($db_host, $db_user, $db_password);
$mysqli -> select_db($db_name);

$_POST_JSON = json_decode(file_get_contents("php://input"), true);

function isAdmin() {
	return isset($_SESSION['is_admin']);
}
if(isset($_SESSION["is_admin"]) && isset($_GET['uuid'])){
	
	
}

$app->get('/houses', function() {
	global $mysqli;
	echo json_encode(PolyHouse::getAll($mysqli, $_SESSION['dbext']));
});

$app->get('/houses/:houseid', function ($houseid) {
	global $mysqli;
	echo json_encode(PolyHouse::get($mysqli, $houseid, $_SESSION['dbext']));
});

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
	echo json_encode($result);
});

$app->post('/logout', function() {
	session_destroy();
	$status = array();
	$status['status'] = true;
	echo json_encode($status);
});

$app->post('/houses', function() {
	global $mysqli;

	global $_POST_JSON;

	if(isset($_POST_JSON['name'])) {
		echo json_encode(PolyHouse::post($mysqli, $_POST_JSON['name'], $_SESSION['dbext']));
	} else {
		$error = array();
		$error['code'] = 50;
		$error['message'] = "Invalid Arguments";
		$error['errors'] = array();
		$error['errors'][0]['code'] = 10;
		$error['errors'][0]['field'] = 'name';
		$error['errors'][0]['message'] = 'Body should contain the field name!';
		echo json_encode($error);
	}
});

$app->put('/houses/:houseid', function($houseid) {
	global $mysqli;

	global $_POST_JSON;

	if($_POST_JSON && isset($_POST_JSON['name'])) {
		echo json_encode(PolyHouse::put($mysqli, $houseid, $_POST_JSON['name'], $_SESSION['dbext']));
	}

});

$app->delete('/houses/:houseid', function($houseid) {
	global $mysqli;
	if(isAdmin()) {
		echo json_encode(PolyHouse::delete($mysqli, $houseid, $_SESSION['dbext']));
	}
});

$app->run();
?>