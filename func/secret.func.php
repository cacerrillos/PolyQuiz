<?
session_start();
include_once("config.func.php");
if(isset($_SESSION['admin_id_num'])){
	if($_SESSION['admin_id_num']==$theid && isset($_SESSION["is_admin"]) && isset($_POST['submit'])){
		if($_POST['submit']=="Browse"){
			$_SESSION['dbext'] = $_POST['uuid'];
		} else if($_POST['submit']=="Overlord"){
			$_SESSION['dbext'] = "Overlord";
		}
		header('Location: ../?p=admin');
	}
}
header('Location: ../?p=admin');
?>