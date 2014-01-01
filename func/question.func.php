<?
class question {
	public $uuid, $question, $o_ans, $o_answers, $ans, $answers, $response = 0, $value, $imagegroup;
	public $set = false, $correct = null;
	function __construct($uuid, $value, $question, $ans, $a, $b, $c, $d){
		$this->value = $value;
		$this->uuid = $uuid;
		$this->question = $question;
		$this->o_ans = $ans;
		$this->o_answers = array(
		"a" => $a,
		"b" => $b,
		"c" => $c,
		"d" => $d
		);
		$this->generate();
		$this->imagegroup = new imageGroup();
	}
	function generate(){
		if(!$this->set){
			$temp = $this->o_answers;
			$tempnumbers = range(1,4);
			shuffle($tempnumbers);
			
			$temp[$tempnumbers[0]] = $temp['a'];
			$temp[$tempnumbers[1]] = $temp['b'];
			$temp[$tempnumbers[2]] = $temp['c'];
			$temp[$tempnumbers[3]] = $temp['d'];
			if($this->o_ans == "a"){
				$this->ans = $tempnumbers[0];
			}
			if($this->o_ans == "b"){
				$this->ans = $tempnumbers[1];
			}
			if($this->o_ans == "c"){
				$this->ans = $tempnumbers[2];
			}
			if($this->o_ans == "d"){
				$this->ans = $tempnumbers[3];
			}
			unset($temp['a']);
			unset($temp['b']);
			unset($temp['c']);
			unset($temp['d']);
			$this->answers = $temp;
			$this->set = true;
		}
	}
	function setResponse($response){
		$this->response = $response;
		$this->correct = $this->check();
	}
	function getResponse(){
		return $this->response;
	}
	function omitted(){
		if($this->response == "0"){
			return true;
		} else {
			return false;
		}
	}
	function correct(){
		return $this->correct;
	}
	function check(){
		if($this->response == $this->ans){
			return true;
		} else {
			return false;
		}
	}
	function getPoints(){
		return $this->value;
	}
}
?>