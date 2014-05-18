<?
include_once("genquiz.func.php");
include_once("polysession.func.php");
include_once("config.func.php");
session_start();
$quiz = $_SESSION['quiz'];
$thisquestion = $quiz->getQuestion($_POST['num']);
if($thisquestion->type==0){
	if($_POST['submit']=="Next" && (!isset($_POST['answer'])) ){
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	
	if($_POST['submit']=="Next" && isset($_POST['answer'])){
		$num = $_POST['num'];
		$next = $num+1;
		$_SESSION["answers"][$num] = $_POST['answer'];
		$quiz = $_SESSION['quiz'];
		$quiz -> setResponse($num, $_POST['answer']);
		$ps = new PolySession($quiz->uuidid);
		$ps->push();
		$_SESSION['quiz'] = $quiz;
		header("Location: ../?p=takequiz&UUID=".$_POST['uuid']."&num=".$next);
	}
	if($_POST['submit']=="Previous"){
		$num = $_POST['num'];
		$prev = $num-1;
		if(isset($_POST['answer'])){
			$_SESSION["answers"][$num] = $_POST['answer'];
			$quiz = $_SESSION['quiz'];
			$quiz -> setResponse($num, $_POST['answer']);
			$_SESSION['quiz'] = $quiz;
		}
		$ps = new PolySession($quiz->uuidid);
		$ps->push();
		header("Location: ../?p=takequiz&UUID=".$_POST['uuid']."&num=".$prev);
	}
}
if($thisquestion->type==1){
	if($_POST['submit']=="Next" && (!isset($_POST['answer'])) ){
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	
	if($_POST['submit']=="Next" && isset($_POST['answer'])){
		$num = $_POST['num'];
		$next = $num+1;
		$_SESSION["answers"][$num] = $_POST['answer'];
		$quiz = $_SESSION['quiz'];
		$quiz -> setResponse($num, $_POST['answer']);
		$ps = new PolySession($quiz->uuidid);
		$ps->push();
		$_SESSION['quiz'] = $quiz;
		header("Location: ../?p=takequiz&UUID=".$_POST['uuid']."&num=".$next);
	}
	if($_POST['submit']=="Previous"){
		$num = $_POST['num'];
		$prev = $num-1;
		if(isset($_POST['answer'])){
			$_SESSION["answers"][$num] = $_POST['answer'];
			$quiz = $_SESSION['quiz'];
		$quiz -> setResponse($num, $_POST['answer']);
		$_SESSION['quiz'] = $quiz;
		}
		$ps = new PolySession($quiz->uuidid);
		$ps->push();
		header("Location: ../?p=takequiz&UUID=".$_POST['uuid']."&num=".$prev);
	}
}
if($thisquestion->type==2){
	if($_POST['submit']=="Next"){
		$num = $_POST['num'];
		$next = $num+1;
		for($x = 0; $x < count($thisquestion->left); $x++){
			$responsArray[$x] = $_POST['ans'.$x];
		}
		$_SESSION["answers"][$num] = $responsArray;
		$quiz = $_SESSION['quiz'];
		$quiz -> setResponse($num, $responsArray);
		$ps = new PolySession($quiz->uuidid);
		$ps->push();
		$_SESSION['quiz'] = $quiz;
		header("Location: ../?p=takequiz&UUID=".$_POST['uuid']."&num=".$next);
	}
	if($_POST['submit']=="Previous"){
		$num = $_POST['num'];
		$prev = $num-1;
		for($x = 0; $x < count($thisquestion->left); $x++){
			$responsArray[$x] = $_POST['ans'.$x];
		}
		$_SESSION["answers"][$num] = $responsArray;
		$quiz -> setResponse($num, $responsArray);
		$_SESSION['quiz'] = $quiz;
		
		$ps = new PolySession($quiz->uuidid);
		$ps->push();
		header("Location: ../?p=takequiz&UUID=".$_POST['uuid']."&num=".$prev);
	}
}
if($thisquestion->type==4){
	if($_POST['submit']=="Next" && (!isset($_POST['answer'])) ){
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	
	if($_POST['submit']=="Next" && isset($_POST['answer'])){
		$num = $_POST['num'];
		$next = $num+1;
		$_SESSION["answers"][$num] = $_POST['answer'];
		$quiz = $_SESSION['quiz'];
		$quiz -> setResponse($num, $_POST['answer']);
		$ps = new PolySession($quiz->uuidid);
		$ps->push();
		$_SESSION['quiz'] = $quiz;
		header("Location: ../?p=takequiz&UUID=".$_POST['uuid']."&num=".$next);
	}
	if($_POST['submit']=="Previous"){
		$num = $_POST['num'];
		$prev = $num-1;
		if(isset($_POST['answer'])){
			$_SESSION["answers"][$num] = $_POST['answer'];
			$quiz = $_SESSION['quiz'];
			$quiz -> setResponse($num, $_POST['answer']);
			$_SESSION['quiz'] = $quiz;
		}
		$ps = new PolySession($quiz->uuidid);
		$ps->push();
		header("Location: ../?p=takequiz&UUID=".$_POST['uuid']."&num=".$prev);
	}
}
if($_POST['submit']=="Finish"){
	$uuid = $_POST['uuid'];
	$quiz = $_SESSION['quiz'];
	$quiz ->calculateScore();
	$rawscore = $quiz -> totalscore;
	$possibleScore = $quiz->possiblepoints;
	$percentage = round((($rawscore/$possibleScore)*100), 2);
	$fn = $_SESSION['firstname'];
	$ln = $_SESSION['lastname'];
	$ts = time();
	$ds = date("Y-m-d");
	$ip = $_SERVER['REMOTE_ADDR'];
	$house = $_SESSION['house'];
	$mysqli = new mysqli($db_host, $db_user, $db_password);
	$mysqli -> select_db($_SESSION['dbext']);
	if(mysqli_connect_errno()) {
		echo "Connection Failed: " . mysqli_connect_errno();
		exit();
	}
	$sessionn = $_SESSION['session'];
	$sessionuuid = $sessionn -> getUUID();
	$quiz->setStudentName($fn, $ln);
	$frp = $quiz->freeresponsepossible;
	$frt = $quiz->totalfreeresponse;
	if($quiz->freeGraded || $frp == 0){
		$frgraded = 1;
	} else {
		$frgraded = 0;
	}
	if($stmt = $mysqli -> prepare("INSERT INTO results
	(id,firstname,lastname,quizuuid,rawscore,possiblescore,percentage,datestamp,timestamp,ip,house,session,object, frscore, frpossible,frgraded)
	VALUES ('',?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")){
		$stmt -> bind_param("sssiisssssssiii", $fn, $ln, $uuid, $rawscore, $possibleScore, $percentage, $ds, $ts, $ip, $house, $sessionuuid , serialize($quiz), $frt, $frp,$frgraded);
		$stmt -> execute();
		$stmt -> close();
	} else {
		echo $mysqli->error;
	}
	$_SESSION['raw'] = $rawscore;
	$_SESSION['total'] = $possibleScore;
	$_SESSION['perc'] = $percentage;
	$_SESSION['frt'] = $frt;
	$_SESSION['frp'] = $frp;
	$_SESSION['frgraded'] = $frgraded;
	$ps = new PolySession($quiz->uuidid);
	$ps->remove();
	header("Location: ../?p=done");
}

?>