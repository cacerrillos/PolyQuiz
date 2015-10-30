<?
include_once("types/matching.php");
include_once("types/standard.php");
include_once("polyhouse.php");
include_once("polysession.php");
include_once("polystats.php");
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
				$toReturn[$quizuuid] = $thisQuiz;
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