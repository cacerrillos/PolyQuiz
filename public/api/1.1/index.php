<?
session_start();
//error_reporting(E_ALL);
require 'Slim/Slim.php';
include_once("../../func/config.func.php");

//include_once('objects/polyquiz.php');
//include_once("objects/polyhouse.php");
include_once("objects/polysession.php");
include_once("objects/polystats.php");
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

include_once('poly_house.php');

include_once('poly_house_api.php');

include_once('poly_session_api.php');

$app->get('/', function() {
  $result = array();
  $result['is_admin'] = isAdmin();
  echo json_encode($result, JSON_PRETTY_PRINT);
});

$app->run();

?>
