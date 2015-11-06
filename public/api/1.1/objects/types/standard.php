<?
class PolyQuestionStandard extends PolyQuestion {
	public function __construct($question){
		parent::__construct();
		$this->type = "Standard";
		$this->data['question'] = $question;
	}
}
class PolyAnswerStandard extends PolyAnswer {
	public function __construct($answerText, $points){
		parent::__construct();
		$this->version = 1;
		$this->type = "Standard";
		$this->data['answerText'] = $answerText;
		$this->data['points'] = $points;
	}
	public static function fromPolyAnswer($obj){
		if($obj->version == 1 && $obj->type == "Standard"){
			$instance = new self("", 0);
			$instance->uuid = $obj->uuid;
			$instance->data = $obj->data;
			return $instance;
		} else {
			throw new Exception("Invalid Version! Received " . $obj->version . " expected 1!");
		}
	}
}
?>