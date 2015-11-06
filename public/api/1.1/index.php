<?
session_start();
error_reporting(E_ALL);
require 'Slim/Slim.php';
include_once("../../func/config.func.php");

include_once('objects/polyquiz.php');
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
if(isset($_SESSION['is_admin'])) {
	$mysqli = new mysqli($db_host, $db_user, $db_password);
	$mysqli -> select_db($db_name);
} else {

}

if(isset($_SESSION["is_admin"]) && isset($_GET['uuid'])){
	
	
}

$app->get('/houses', function() {
	global $mysqli;
	echo json_encode(PolyHouse::getHouses($mysqli, $_SESSION['dbext']));
});

$app->get('/houses/:houseid', function ($houseid) {
	global $mysqli;
	echo json_encode(PolyHouse::getHouse($mysqli, $houseid, $_SESSION['dbext']));
});

$app->post('/houses', function() {
	var_dump($_POST);
});


$app->run();
?>