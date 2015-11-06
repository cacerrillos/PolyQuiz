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

include_once('PolyQuizAdminAPI.php');

include_once('PolyHouseAPI.php');

$app->run();

?>
