<?
include_once("types/matching.php");
include_once("types/standard.php");
class PolyQuiz {
	public $uuid;
	public $version = 0;
	public $data = array();
	public function __construct(){
		$this->version = 0;
		$this->uuid = uniqid("PolyQuiz_".$this->version."_");
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
		$this->uuid = uniqid("PolyQuestion_".$this->version."_");
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
		$this->uuid = uniqid("PolyAnswer_".$this->version."_");
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