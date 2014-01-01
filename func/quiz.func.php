<?
include_once("genquiz.func.php");
include_once("polysession.func.php");
include_once("config.func.php");
session_start();
/*
var_dump($_GET);
echo "<br>";
var_dump($_POST);
echo "<br>";
var_dump($_SESSION);
echo "<br>"; */
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
if($_POST['submit']=="Finish"){
//	echo "Proccessing!";
	$uuid = $_POST['uuid'];
	$quiz = $_SESSION['quiz'];
	$quiz ->calculateScore();
	$rawscore = $quiz -> totalscore;
	$possibleScore = $quiz->possiblepoints;
	//$numberofquestions = $quiz -> numberofquestions;
	/*
	if($stmt = $mysqli -> prepare("SELECT * FROM ".$mysqli->real_escape_string($uuid))){
		$stmt -> execute();
		$stmt -> bind_result($quiz['id'], $quiz['question'], $quiz['a'], $quiz['b'], $quiz['c'], $quiz['d'], $quiz['answer']);
		$stmt -> store_result();
		$numberofquestions = $stmt ->num_rows;
		$stmt -> fetch();
		$stmt -> close();
		//echo var_dump($quiz);
	} else {
		echo $mysqli->error();
	}
	$rawscore = 0;
	for($x = 1; $x <= $numberofquestions; $x++){
		if($stmt = $mysqli -> prepare("SELECT * FROM ".$mysqli->real_escape_string($uuid)." WHERE id = ?")){
			$stmt -> bind_param("i", $x);
			$stmt -> execute();
			$stmt -> bind_result($currentcorrect['id'], $currentcorrect['question'], $currentcorrect['a'], $currentcorrect['b'], $currentcorrect['c'], $currentcorrect['d'], $currentcorrect['answer']);
			$stmt -> fetch();
			$stmt -> close();
		} else {
			echo $mysqli->error();
		}
	//	$currentcorrect = mysql_fetch_array(mysql_query("SELECT * FROM ". mysql_real_escape_string($_POST['uuid']) ." WHERE id='".$x."';"));
		if($currentcorrect['answer']==$_SESSION['answers'][$x]){
			$rawscore += 1;
		}
	}*/
	$percentage = round((($rawscore/$possibleScore)*100), 2);
//	echo " ".$rawscore;
//	echo "<br>".$percentage;
	$fn = $_SESSION['firstname'];
	$ln = $_SESSION['lastname'];
	$ts = time();
	$ds = date("Y-m-d");
	$ip = $_SERVER['REMOTE_ADDR'];
	$house = $_SESSION['house'];
	$mysqli = new mysqli($db_host, $db_user, $db_password);
	$mysqli -> select_db($db_name);
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
	//echo $mysqli->error;
//	$result = mysql_query($query) or die(mysql_error());
	$_SESSION['raw'] = $rawscore;
	$_SESSION['total'] = $possibleScore;
	$_SESSION['perc'] = $percentage;
	$_SESSION['frt'] = $frt;
	$_SESSION['frp'] = $frp;
	$_SESSION['frgraded'] = $frgraded;
	$ps = new PolySession($quiz->uuidid);
	$ps->remove();
	//echo $rawscore;
	header("Location: ../?p=done");
}

?>