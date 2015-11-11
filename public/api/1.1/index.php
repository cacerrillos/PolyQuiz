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

include_once('poly_quiz.php');

include_once('poly_quiz_api.php');

include_once('poly_quiz_admin_api.php');

include_once('poly_house_api.php');

include_once('poly_session_api.php');

$app->run();

?>
