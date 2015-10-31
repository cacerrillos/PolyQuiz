<?
include_once("genquiz.func.php");
include_once("polysession.func.php");
include_once("config.func.php");
session_start();
$quiz = $_SESSION['quiz'];
$thisquestion = $quiz->getQuestion($_POST['num']);
if($thisquestion!="null"){
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
			if(isset($_POST['canvasValue'])){
				$quiz->setCanvasValue($num, $_POST['canvasValue']);
			}
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
				if(isset($_POST['canvasValue'])){
					$quiz->setCanvasValue($num, $_POST['canvasValue']);
				}
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
			if(isset($_POST['canvasValue'])){
				$quiz->setCanvasValue($num, $_POST['canvasValue']);
			}
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
				if(isset($_POST['canvasValue'])){
					$quiz->setCanvasValue($num, $_POST['canvasValue']);
				}
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
				$responsArray[$x] = intval($_POST['ans'.$x]);
			}
			$_SESSION["answers"][$num] = $responsArray;
			$quiz = $_SESSION['quiz'];
			$quiz -> setResponse($num, $responsArray);
			if(isset($_POST['canvasValue'])){
				$quiz->setCanvasValue($num, $_POST['canvasValue']);
			}
			$ps = new PolySession($quiz->uuidid);
			$ps->push();
			$_SESSION['quiz'] = $quiz;
			header("Location: ../?p=takequiz&UUID=".$_POST['uuid']."&num=".$next);
		}
		if($_POST['submit']=="Previous"){
			$num = $_POST['num'];
			$prev = $num-1;
			for($x = 0; $x < count($thisquestion->left); $x++){
				$responsArray[$x] = intval($_POST['ans'.$x]);
			}
			$_SESSION["answers"][$num] = $responsArray;
			$_SESSION['quiz'] = $quiz;
			$quiz -> setResponse($num, $responsArray);
			if(isset($_POST['canvasValue'])){
				$quiz->setCanvasValue($num, $_POST['canvasValue']);
			}
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
			if(isset($_POST['canvasValue'])){
				$quiz->setCanvasValue($num, $_POST['canvasValue']);
			}
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
				if(isset($_POST['canvasValue'])){
					$quiz->setCanvasValue($num, $_POST['canvasValue']);
				}
				$_SESSION['quiz'] = $quiz;
			}
			$ps = new PolySession($quiz->uuidid);
			$ps->push();
			header("Location: ../?p=takequiz&UUID=".$_POST['uuid']."&num=".$prev);
		}
	}
} else {
	if($_POST['submit']=="Previous"){
		$num = $_POST['num'];
		$prev = $num-1;
		$ps = new PolySession($quiz->uuidid);
		$ps->push();
		header("Location: ../?p=takequiz&UUID=".$_POST['uuid']."&num=".$prev);
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
		$mysqli -> select_db($db_name);
		if(mysqli_connect_errno()) {
			echo "Connection Failed: " . mysqli_connect_errno();
			exit();
		}
		$sessionn = $_SESSION['session'];
		$sessionuuid = $sessionn -> getUUID();
		if($stmt = $mysqli -> prepare("SELECT `score` FROM `sessions` WHERE `uuid`=?;")) {
			$stmt->bind_param("s", $sessionuuid);
			$stmt->execute();
			$stmt->bind_result($show);
			$stmt->store_result();
			$stmt->fetch();
			if(!isset($show)){
				$show = "1";
			}
			$stmt->close();
		} else {
			echo $mysqli-error;
		}
		$quiz->setStudentName($fn, $ln);
		$frp = $quiz->freeresponsepossible;
		$frt = $quiz->totalfreeresponse;
		if($quiz->freeGraded || $frp == 0){
			$frgraded = 1;
		} else {
			$frgraded = 0;
		}
		if($stmt = $mysqli -> prepare("INSERT INTO results
		(firstname,lastname,quizuuid,rawscore,possiblescore,percentage,datestamp,timestamp,ip,house,session, frscore, frpossible,frgraded,owner)
		VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);")){
			$stmt -> bind_param("sssiissssssiiis", $fn, $ln, $uuid, $rawscore, $possibleScore, $percentage, $ds, $ts, $ip, $house, $sessionuuid, $frt, $frp,$frgraded, $_SESSION['dbext']);
			$stmt -> execute();
			$idrow = $stmt->insert_id;
			$stmt -> close();
			if($stmt = $mysqli->prepare("INSERT INTO `quizobjects` (`id`, `object`, `owner`) VALUES (?, ?, ?);")){
				$stmt->bind_param("iss", $idrow, gzcompress(serialize($quiz)), $_SESSION['dbext']);
				$stmt -> execute();
				$stmt -> close();
			} else {
				echo $mysqli->error;
			}
		} else {
			echo $mysqli->error;
		}
		$mysqli -> select_db($db_name);
		if($stmt = $mysqli->prepare("SELECT value FROM stats WHERE id='totalresults';")){
			$stmt -> execute();
			$stmt -> bind_result($count);
			$stmt -> store_result();
			$stmt -> fetch();
			$stmt -> close();
			$count = intval($count) + 1;
			if($stmt = $mysqli->prepare("UPDATE stats SET value = ? WHERE id='totalresults';")){
				$stmt -> bind_param("s", $count);
				$stmt -> execute();
				$stmt -> close();
			} else {
				echo $mysqli->error;
			}
		} else {
			echo $mysqli->error;
		}
		if($stmt = $mysqli->prepare("SELECT value FROM stats WHERE id=?;")){
			$stmt -> bind_param("s", $_SESSION['dbext']);
			$stmt -> execute();
			$stmt -> bind_result($count);
			$stmt -> store_result();
			$stmt -> fetch();
			$stmt -> close();
			$count = intval($count) + 1;
			if($stmt = $mysqli->prepare("UPDATE stats SET value = ? WHERE id=?;")){
				$stmt -> bind_param("ss", $count, $_SESSION['dbext']);
				$stmt -> execute();
				$stmt -> close();
			} else {
				echo $mysqli->error;
			}
		} else {
			echo $mysqli->error;
		}
		$_SESSION['raw'] = $rawscore;
		$_SESSION['total'] = $possibleScore;
		$_SESSION['perc'] = $percentage;
		$_SESSION['frt'] = $frt;
		$_SESSION['frp'] = $frp;
		$_SESSION['frgraded'] = $frgraded;
		$_SESSION['show'] = $show;
		$ps = new PolySession($quiz->uuidid);
		$ps->remove();
		header("Location: ../?p=done");
	}
}
?>