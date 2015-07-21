<?
$time_start = microtime(true);
function convert($size)
{
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}
include_once("func/genquiz.func.php");
include_once("func/config.func.php");
session_start();
$use = true;
$sat = true;
$page = "";

if(isset($_GET['p'])){
	$page = $_GET['p'];
	if($page == "managequiz" && !isset($_GET['UUID'])){
		header("Location: ?p=quizadmin");
	}
	if($page=="addquestion" && !isset($_GET['uuid'])){
	//	header("Location: ?p=managequiz");
	}
	if($page=="exit"){
		session_destroy();
		header("Location: ?p=home");
	}
} else {
	header("Location: ?p=home");
}
if(!isset($_SESSION["total"]) && $page=="done"){
	header("Location: ?p=home");
}
$_SESSION['page'] = $page;
if($page=="home"){
	$pagetype = "home";
	$pagetype2 = "home";
} else if($page=="admin"){
	$pagetype = "admin";
} else if($page=="quizadmin"){
	$pagetype = "admin";
} else if($page=="takequiz"){
	$pagetype = "takequiz";
} else if($page=="managequiz"){
	$pagetype = "admin";
} else if($page=="results"){
	$pagetype = "admin";
} else if($page=="done"){
	$pagetype = "takequiz";
} else if($page=="stats"){
	
} else if($page=="postquizadmin"){
	$pagetype = "admin";
} else if($page=="addquestion"){
	$pagetype = "admin";
} else if($page=="pendingsessions"){
	$pagetype = "admin";
} else if($page=="survey"){
} else if($page=="sessions"){
	$pagetype = "admin";
} else if($page=="register"){
} else if($page=="math"){
} else if($page=="gradefr"){
	$pagetype = "admin";
} else {
	$pagetype = "home";
	$pagetype2 = "home";
}
$_SESSION['pagetype'] = $pagetype;
include("inc/header.inc.php");
?>
<?
if($page=="home"){
	$pagetype = "home";
	$pagetype2 = "home";
	include("inc/home.inc.php");
} else if($page=="admin"){
	$pagetype = "admin";
	include("inc/admin.inc.php");
} else if($page=="quizadmin"){
	$pagetype = "admin";
	?>
	<admin-quiz-management-page _get='<? echo json_encode($_GET); ?>'></admin-quiz-management-page>
	<?
} else if($page=="takequiz"){
	$pagetype = "takequiz";
	include("inc/takequiz.inc.php");
} else if($page=="managequiz"){
	$pagetype = "admin";
	include("inc/managequiz.inc.php");
} else if($page=="results"){
	$pagetype = "admin";
	?>
	<admin-quiz-results-page _get='<? echo json_encode($_GET); ?>'></admin-quiz-results-page>
	<?
} else if($page=="done"){
	$pagetype = "take";
	include("inc/fin.inc.php");
} else if($page=="stats"){
	include("inc/stats.inc.php");
} else if($page=="postquizadmin"){
	$pagetype = "admin";
	include("inc/postquiz.inc.php");
} else if($page=="addquestion"){
	$pagetype = "admin";
	include("inc/addquestion.inc.php");
} else if($page=="pendingsessions"){
	$pagetype = "admin";
	include("inc/pending.inc.php");
} else if($page=="survey"){
	include("inc/survey.inc.php");
} else if($page=="sessions"){
	include("inc/sessions.inc.php");
} else if($page=="register"){
	include("inc/register.inc.php");
} else if($page=="math"){
	include("inc/matrix.inc.php");
} else if($page=="gradefr"){
	$pagetype = "admin";
	include("inc/freeresponsegrading.inc.php");
} else {
	$pagetype = "home";
	$pagetype2 = "home";
	include("inc/home.inc.php");
}

?>
<?
include("inc/footer.inc.php");
$time_end = microtime(true);
$execution_time = ($time_end - $time_start)/60;
if(isset($_SESSION['username'])){
	if($_SESSION['username']=="Caboolean" || $_SESSION['username'] == "caboolean"){
		echo '<h3><center>Total Execution Time: '.$execution_time.' Mins '.convert(memory_get_usage(true)).'</center></h3>';
	} else {
		echo '<!-- Total Execution Time: '.$execution_time.' Mins '.convert(memory_get_usage(true)).' -->';
	}
}
?>
