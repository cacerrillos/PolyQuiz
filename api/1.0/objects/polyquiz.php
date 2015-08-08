<?
include_once("types/matching.php");
include_once("types/standard.php");
class PolyStats {
	public $version = 0;
	public $data = array();
	public function __construct($quizUUID, $houseid, $count){
		$this->data['quizUUID'] = intval($quizUUID);
		$this->data['houseData'] = intval($houseid);
		$this->data['count'] = intval($count);
	}
	public static function getStats($mysqli, $quiz, $owner, $all = false){
		$resultObject = array();
		if($stmt = $mysqli->prepare("SELECT COUNT(`id`) FROM `results` WHERE `quiz` = ? AND `owner` = ? LIMIT 1;")){
			$stmt->bind_param("ii", $quiz, $owner);
			$stmt->execute();
			$stmt->bind_result($count);
			while($stmt->fetch()){
				array_push($resultObject, new self($quiz, -1, $count));
			}
			$stmt->close();
		} else {
			die($mysqli->error);
		}
		$houses = PolyHouse::getHouses($mysqli, $owner);
		for($x = 0; $x < count($houses); $x++){
			if($stmt = $mysqli->prepare("SELECT COUNT(`id`) FROM `results` WHERE `house` = ? AND `quiz` = ? AND `owner` = ? LIMIT 1;")){
				$stmt->bind_param("iii", $houses[$x]->data['houseId'], $quiz, $owner);
				$stmt->execute();
				$stmt->bind_result($count);
				while($stmt->fetch()){
					if($all || $count > 0){
						array_push($resultObject, new self($quiz, $houses[$x]->data['houseId'], $count));
					}
				}
				$stmt->close();
			} else {
				die($mysqli->error);
			}
		}
		for($x = 0; $x < count($resultObject); $x++){
			if($resultObject[$x]->data['houseData'] != -1){
				$resultObject[$x]->data['houseData'] = PolyHouse::getHouse($mysqli, $resultObject[$x]->data['houseData'], $owner);
			}
		}
		return $resultObject;
	}
}
class PolyHouse {
	public $version = 0;
	public $data = array();
	public function __construct($houseid, $housename){
		$this->data['houseId'] = $houseid;
		$this->data['houseName'] = $housename;
	}
	public static function getHouse($mysqli, $houseid, $owner){
		$toReturn = false;
		if($stmt = $mysqli->prepare("SELECT `houseid`, `housename` FROM `houses` WHERE `houseid` = ? AND `owner` = ?;")){
			$stmt->bind_param("ii", $houseid, $owner);
			$stmt->execute();
			$stmt->bind_result($houseid, $housename);
			$stmt->store_result();
			while($stmt->fetch()){
				$toReturn = new self($houseid, $housename);
			}
			$stmt->close();
		} else {
			die($mysqli->error);
		}
		if(!$toReturn){
			return false;
		} else {
			return $toReturn;
		}	
	}
	public static function getHouses($mysqli, $owner) {
		$resultObject = array();
		if($stmt = $mysqli->prepare("SELECT `houseid`, `housename` FROM `houses` WHERE `owner` = ?;")){
			$stmt->bind_param("i", $owner);
			$stmt->execute();
			$stmt->bind_result($houseid, $housename);
			$stmt->store_result();
			while($stmt->fetch()){
				array_push($resultObject, new self($houseid, $housename));
			}
			$stmt->close();
		} else {
			die($mysqli->error);
		}
		return $resultObject;
	}
}
class PolySession {
	public $sessionid;
	public $sessionkey;
	public $name;
	public $owner;
	public $quiz;
	public $date;
	public $data = array();
	public function __construct(){
		$this->data['status'] = true;
		$this->data['show'] = true;
		$this->data['house'] = 0;
		$date = new DateTime();
		$this->date = $date->getTimestamp();
	}
	
	public static function createCheckMysql($mysqli){
		$toReturn = new self();
		$tempId = substr(uniqid(), 6, 6);
		$valid = false;
		while(!$valid){
			if($stmt = $mysqli->prepare(
				"SELECT `sessionid` FROM `sessions` WHERE `sessionid`=? LIMIT 1;"
			)){
				$stmt->bind_param("s", $tempId);
				$stmt->execute();
				$stmt->store_result();
				if($stmt->num_rows == 0){
					$valid = true;
				} else {
					$tempId = substr(uniqid(), 6, 6);
				}
				$stmt->close();
			} else {
				die($mysqli->error);
			}
		}
		if($valid){
			$toReturn->sessionid = $tempId;
			$toReturn->sessionkey = substr(uniqid(), 11, 2);
		}
		return $toReturn;
	}
	public static function ownedBy($mysqli, $owner){
		$toReturn = array();
		if($stmt = $mysqli->prepare("SELECT `sessionid`, `sessionkey`, `quiz`, `owner`, `name`, `data`, `date` FROM `sessions` WHERE `owner`=?;")){
			$stmt->bind_param("i", $owner);
			$stmt->execute();
			$stmt->bind_result($sessionid, $sessionkey, $quiz, $owner, $name, $data, $date);
			$stmt->execute();
			while($stmt->fetch()){
				$thisThang = new self();
				$thisThang->sessionid = $sessionid;
				$thisThang->sessionkey = $sessionkey;
				$thisThang->owner = $owner;
				$thisThang->name = $name;
				$thisThang->quiz = $quiz;
				$thisThang->date = $date;
				$thisThang->data = json_decode($data);
				
				array_push($toReturn, $thisThang);
			}
		}
		return $toReturn;
	}
	public static function fromMySQL($mysqli, $uuid){
		/*
		$toReturn = new self();
		if($stmt = $mysqli->prepare(
			"SELECT `uuid`, `sessionid`, `sessionkey`, `owner` FROM `quizzes` WHERE `uuid`=? LIMIT 1;"
		)){
			$stmt->bind_param("i", $uuid);
			$stmt->execute();
			$stmt->bind_result($quizuuid, $quizname, $version, $owner);
			$stmt->store_result();
			
			while($stmt->fetch()){
				$toReturn->uuid = $quizuuid;
				$toReturn->name = $quizname;
				$toReturn->version = $version;
			}
			if($stmt->num_rows != 1){
				$toReturn = false;
			}
			$stmt->close();
		} else {
			echo $mysqli->error;
		}
		if($toReturn){
			if($stmt = $mysqli->prepare("SELECT `data` FROM `quizzes_questions` WHERE `quiz` = ?;")){
				$stmt->bind_param("i", $uuid);
				$stmt->execute();
				$stmt->bind_result($data);
				$stmt->store_result();
				while($stmt->fetch()){
					$thisQ = PolyQuestion::fromJSON($data);
					if($thisQ){
						$toReturn->addQuestion($thisQ);
					}
				}
				
			} else {
				echo $mysqli->error;
			}
			return $toReturn;
		} else {
			return false;
		}
		*/
	}
}
class PolyQuiz {
	public $uuid;
	public $version = 0;
	public $data = array();
	public function __construct(){
		$this->version = 0;
		$this->uuid = uniqid("PolyQuiz_");
		$this->data['questions'] = array();
	}
	public static function fromJSON($jsonIn){
		$obj = json_decode($jsonIn);
		$instance = new self();
		$instance->uuid = $obj->uuid;
		$instance->version = $obj->version;
		$instance->data = $obj->data;
		return $instance;
	}
	public static function AllOwned($mysqli, $owner) {
		$toReturn = array();
		if($stmt = $mysqli->prepare(
			"SELECT `uuid`, `quizname`, `version`, `owner` FROM `quizzes` LIMIT 1;"
		)){
			$stmt->execute();
			$stmt->bind_result($quizuuid, $quizname, $version, $owner);
			$stmt->store_result();
			
			while($stmt->fetch()){
				$thisQuiz = new self();
				$thisQuiz->uuid = $quizuuid;
				$thisQuiz->name = $quizname;
				$thisQuiz->version = $version;
				array_push($toReturn, $thisQuiz);
			}
			$stmt->close();
		} else {
			echo $mysqli->error;
		}
		return $toReturn;
	}
	public static function fromMySQL($mysqli, $uuid, $includeQuestions = true){
		$toReturn = new self();
		if($stmt = $mysqli->prepare(
			"SELECT `uuid`, `quizname`, `version`, `owner` FROM `quizzes` WHERE `uuid`=? LIMIT 1;"
		)){
			$stmt->bind_param("i", $uuid);
			$stmt->execute();
			$stmt->bind_result($quizuuid, $quizname, $version, $owner);
			$stmt->store_result();
			
			while($stmt->fetch()){
				$toReturn->uuid = $quizuuid;
				$toReturn->name = $quizname;
				$toReturn->version = $version;
			}
			if($stmt->num_rows != 1){
				$toReturn = false;
			}
			$stmt->close();
		} else {
			echo $mysqli->error;
		}
		if($toReturn){
			if($includeQuestions){
				if($stmt = $mysqli->prepare("SELECT `data` FROM `quizzes_questions` WHERE `quiz` = ?;")){
					$stmt->bind_param("i", $uuid);
					$stmt->execute();
					$stmt->bind_result($data);
					$stmt->store_result();
					while($stmt->fetch()){
						$thisQ = PolyQuestion::fromJSON($data);
						if($thisQ){
							$toReturn->addQuestion($thisQ);
						}
					}
					
				} else {
					echo $mysqli->error;
				}
			}
			return $toReturn;
		} else {
			return false;
		}
	}
	public function addQuestion($obj){
		for($x = 0; $x < count($this->data['questions']); $x++){
			if($this->data['questions'][$x]->uuid == $obj->uuid){
				throw new Exception("Question with that UUID already exists under this quiz!");
			}
		}
		array_push($this->data['questions'], $obj);
	}
	public function toJSON(){
		return json_encode($this, JSON_PRETTY_PRINT);
	}
}
class PolyQuestion {
	public $uuid;
	public $type;
	public $version = 0;
	public $data = array();
	public function __construct(){
		$this->version = 0;
		$this->uuid = uniqid("PolyQuestion_");
		$this->data['answers'] = array();
	}
	public static function fromJSON($jsonIn){
		$obj = json_decode($jsonIn);
		$instance = new self();
		$instance->uuid = $obj->uuid;
		$instance->type = $obj->type;
		$instance->version = $obj->version;
		$instance->data = $obj->data;
		return $instance;
	}
	public function addAnswer($obj){
		for($x = 0; $x < count($this->data['answers']); $x++){
			if($this->data['answers'][$x]->uuid == $obj->uuid){
				throw new Exception("Answer with that UUID already exists under this question!");
			}
		}
		array_push($this->data['answers'], $obj);
	}
	public function toJSON(){
		return json_encode($this, JSON_PRETTY_PRINT);
	}
}
class PolyAnswer {
	public $uuid;
	public $type;
	public $version = 0;
	public $data = array();
	public function __construct(){
		$this->version = 0;
		$this->uuid = uniqid("PolyAnswer_");
	}
	public static function fromJSON($jsonIn){
		$obj = json_decode($jsonIn);
		$instance = new self();
		$instance->uuid = $obj->uuid;
		$instance->type = $obj->type;
		$instance->version = $obj->version;
		$instance->data = $obj->data;
		return $instance;
	}
	public function toJSON(){
		return json_encode($this, JSON_PRETTY_PRINT);
	}
}
?>