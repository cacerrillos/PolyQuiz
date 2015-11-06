<?
class PolyQuestionMatching extends PolyQuestion {
	public function __construct($question){
		parent::__construct();
		$this->type = "Matching";
		$this->data['question'] = $question;
	}
}
class PolyAnswerMatching extends PolyAnswer {
	function __construct($left, $right, $points){
		parent::__construct();
		$this->version = 1;
		$this->type = "Matching";
		$this->data['left'] = $left;
		$this->data['right'] = $right;
		$this->data['points'] = $points;
	}
}
?>