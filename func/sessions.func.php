<?
include_once("genquiz.func.php");
include_once("config.func.php");
session_start();
if(isset($_POST['uuid']) && isset($_POST['pass'])){
	$uuid = $_POST['uuid'];
	$pass = $_POST['pass'];
	$sessiongen = new quizSessionFromMysql();
	$session = $sessiongen->getSession($uuid, $pass);
	if($session!=null){
		$_SESSION['session'] = $session;
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	} else {
		unset($_SESSION["session"]);
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
}
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>