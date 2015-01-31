<?
include("img.class.php");
include_once("config.func.php");
class quiz {
	public $uuidid;
	public $uuid, $name, $subject, $numberofquestions, $totalscore = 0, $possiblepoints = 0, $freeresponsepossible = 0, $totalfreeresponse = 0, $freeGraded = false;
	public $questions, $randomized = false;
	public $fname, $lname;
	public $session;
	function __construct($uuid, $name, $subject){
		$this->name = $name;
		$this->subject = $subject;
		$this->uuid = $uuid;
		$this->questions["A"] = "";
		$this->uuidid = md5($uuid.$name.time());
	}
	function setStudentName($fname, $lname){
		$this->fname = $fname;
		$this->lname = $lname;
	}
	function uuid(){
		echo $this->uuid;
	}
	function getHouse(){
		$session = $this->session;
		$house = $session->getHouse();
		return $house;
	}
	function setSession($session){
		$this->session = $session;
	}
	function getQuestion($num){
		if($num<count($this->questions)){
			return $this->questions[$num];
		} else {
			return "null";
		}
	}
	function ifExists($uuid){
		$questions = $this->questions;
		foreach($questions as $quest){
			if($quest-> uuid == $uuid){
				return true;
			}
		}
		return false;
	}
	function addQuestion($questions){
		array_push($this->questions, $questions);
		if(isset($this->questions["A"])){
			unset($this->questions["A"]);
		}
	}
	function randomize(){
		if(isset($this->questions[0])){
			if($this->questions[0]==""){
				unset($this->questions[0]);
			}
		}
		shuffle($this->questions);
		for($x = 0; $x < count($this->questions); $x++){
			$this->questions[$x]->randomize();
		}
		$this->randomized = true;
	}
	function setFRGraded(){
		for($x = 0; $x <count($this->questions); $x++){
			if($this->questions[$x]->type==1){
				$this->questions[$x]->setFreeHasBeenGraded(true);
			}
		}
	}
	function calculateScore(){
		$questions = $this->questions;
		$totalscore = 0;
		$omittedq = 0;
		$correctq = 0;
		$incorrectq = 0;
		$size = sizeof($questions);
		$this->numberofquestions = $size;
		$possiblefree = 0;
		$freeresponse = 0;
		$possiblepoints = 0;
		for($x = 0; $x<$size; $x++){
			$question = $questions[$x];
			if($question->type==0){
				if($question->isExtraCredit()){
					//add no points to possible points
				} else {
					$possiblepoints += $question->getPoints();
				}
				if($question->correct()){
					$totalscore += $question->getPoints();
					$correctq++;
				} else if($question->omitted()){
					$omittedq++;
				}
			}
			if($question->type==1){
				$possiblefree += $question->getPoints();
				if($question->freeHasBeenGraded()){
					$freeresponse += $question->pointsEarned;
				}
			}
			if($question->type==2){
				$totalscore += $question->pointsEarned;
				if($question->isExtraCredit()){
					//add no points to possible points
				} else {
					$possiblepoints += count($question->left);
				}
			}
			if($question->type==4){
				if($question->isExtraCredit()){
					//do nothing
				} else {
					$possiblepoints += $question->getPoints();
				}
				$totalscore += $question->pointsEarned;
			}
		}
		$this->totalscore = $totalscore;
		$this->possiblepoints = $possiblepoints;
		$this->freeresponsepossible = $possiblefree;
		$this->totalfreeresponse = $freeresponse;
	}
	function setResponse($number, $response){
		$question = $this->questions[$number];
		$question ->setResponse($response);
	}
	function setCanvasValue($number, $canvasValue){
		$question = $this->questions[$number];
		$question -> setCanvasValue($canvasValue);
	}
}
include_once("question.obj.php");
class quizFromMysql{
	public $quiz;
	function getQuiz($studentuuid){
		global $db_host, $db_user, $db_password, $db_name;
		$mysqli = new mysqli($db_host, $db_user, $db_password);
		$mysqli -> select_db($db_name);
		if(mysqli_connect_errno()) {
			echo "Connection Failed: " . mysqli_connect_errno();
			exit();
		}
		if($stmt = $mysqli -> prepare("SELECT id FROM results WHERE id = ? AND owner = ?;")){
				$stmt -> bind_param("is", $studentuuid, $_SESSION['dbext']);
				$stmt -> execute();
				$stmt -> store_result();
				$num = $stmt -> num_rows;
				$stmt -> close();
		} else {
			echo $mysqli->error;
		}
		if($num==0){
			return false;
		} else {
			if($stmt = $mysqli->prepare("SELECT object FROM results WHERE id = ? AND owner = ?;")){
				$stmt->bind_param("is", $studentuuid, $_SESSION['dbext']);
				$stmt->execute();
				$stmt->store_result();
				$stmt->bind_result($object);
				while($stmt->fetch()){
					$quiz = unserialize($object);
				}
				$stmt->close();
			} else {
				echo $mysqli->error;
			}
			$mysqli->close();
			return $quiz;
		}
		$mysqli->close();
	}
	function createQuiz($uuid){
		global $db_host, $db_user, $db_password, $db_name;
		$mysqli = new mysqli($db_host, $db_user, $db_password);
		$mysqli -> select_db($db_name);
		if(mysqli_connect_errno()) {
			echo "Connection Failed: " . mysqli_connect_errno();
			exit();
		}
		if($stmt = $mysqli -> prepare("SELECT * FROM quizzes WHERE uuid = ? AND owner = ?")){
				$stmt -> bind_param("ss", $uuid, $_SESSION['dbext']);
				$stmt -> execute();
				$stmt -> store_result();
				$num = $stmt -> num_rows;
				$stmt -> close();
		} else {
			echo $mysqli->error;
		}
		if($num==0){
			return false;
		} else {
			if($stmt = $mysqli -> prepare("SELECT * FROM quizzes WHERE uuid = ? AND owner = ?")){
				$stmt -> bind_param("ss", $uuid, $_SESSION['dbext']);
				$stmt -> execute();
				$stmt -> bind_result($result['uuid'], $result['quizname'], $result['quizsubject'], $result['status'], $result['owner']);
				$stmt -> store_result();
				$stmt -> fetch();
				$stmt -> close();
				if($result['status']==0){
					return false;
				} else {
					$quiz = new quiz($result['uuid'], $result['quizname'], $result['quizsubject']);
					if(hasPermissions($result['uuid'])){
						if($stmt = $mysqli -> prepare("SELECT * FROM `".$result['uuid']."`")){
							$stmt -> execute();
							$stmt -> bind_result($resultq2['id'], $resultq2['object'], $resultq2['images']);
							$stmt -> store_result();
							while($stmt -> fetch()){
								$thisquestion = unserialize($resultq2['object']);
								if($resultq2['images']!=null && $resultq2['images']!=""){
									$thisquestion -> imagegroup = unserialize($resultq2['images']);
								}
								$quiz -> addQuestion($thisquestion);
							}
							$stmt -> close();
						} else {
							echo $mysqli->error;
						}
					}
				}
			} else {
				echo $mysqli->error;
			}
		}
		$mysqli -> close();
		return $quiz;
	}
}
class quizSessionFromMysql {
	function getSession($db, $uuid, $pass){
		global $db_host, $db_user, $db_password, $db_name;
		$mysqli = new mysqli($db_host, $db_user, $db_password);
		$mysqli -> select_db($db_name);
		if(mysqli_connect_errno()) {
			echo "Connection Failed: " . mysqli_connect_errno();
			exit();
		}
		$uuid = strtolower($uuid);
		$pass = strtolower($pass);
		if($stmt = $mysqli -> prepare("SELECT * FROM sessions WHERE uuid=?")){
				$stmt -> bind_param("s", $uuid);
				$stmt -> execute();
				$stmt -> store_result();
				$num = $stmt -> num_rows;
				$stmt -> close();
				if($num==1){
					if($stmt = $mysqli -> prepare("SELECT * FROM sessions WHERE uuid=?")){
						$stmt -> bind_param("s", $uuid);
						$stmt -> execute();
						$stmt -> bind_result($session['uuid'], $session['key'], $session['house'], $session['status'], $session['quiz'], $session['date'], $session['name'], $session['owner'], $session['show']);
						$stmt -> store_result();
						while($stmt -> fetch()){
							if($session['key']==$pass){
								if($session['status']==1){
									$tempsession = new quizSession($session['uuid'], $session['house'], $session['quiz']);
									$tempsession->db = $session['owner'];
									return $tempsession;
								}
							}
						}
						$stmt -> close();	
					} else {
						die($mysqli->error);
					}
				} else {
					return false;
				}
		} else {
			die($mysqli->error);
		}
	}
}
class quizSession {
	public $uuid, $house, $quiz;
	public $db;
	function __construct($uuid, $house, $quiz){
		$this->quiz = $quiz;
		$this->uuid = $uuid;
		$this->house = $house;
	}
	function addQuiz($uuid){
		//IDK WHY THIS IS EMPTY
	}
	function getUUID(){
		return $this->uuid;
	}
	function getQuizes(){
		return $this->quiz;
	}
	function getHouse(){
		return $this->house;
	}
}
?>