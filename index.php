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
	include("inc/quiz.admin.inc.php");
} else if($page=="takequiz"){
	$pagetype = "takequiz";
	include("inc/takequiz.inc.php");
} else if($page=="managequiz"){
	$pagetype = "admin";
	include("inc/managequiz.inc.php");
} else if($page=="results"){
	$pagetype = "admin";
	include("inc/results.inc.php");
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
echo '<!-- Total Execution Time: '.$execution_time.' Mins '.convert(memory_get_usage(true)).' -->';
?>
