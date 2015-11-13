<?php
class PolyQuestion {
  public $question_id = null;
  public $question_type;
  public $data = array();
  public $answers = array();
  protected $parent_quiz;
  public function __construct($parent_quiz) {
    $this->parent_quiz = $parent_quiz;
  }
  public function addAnswer($answer) {
    $arr = array();
    $arr['answer'] = $answer;
    $arr['sort_id'] = 0;
    $this->answers[$answer->answer_id] = $arr;
  }
  public function get_answer($answer_id) {
    if(isset($this->answers[$answer_id])) {
      return $this->answers[$answer_id]['answer'];
    }
  }
  public function get_answer_sort_id($answer_id) {
    if(isset($this->answers[$answer_id])) {
      return $this->answers[$answer_id]['sort_id'];
    }
  }
  public function toJSON(){
    return json_encode($this, JSON_PRETTY_PRINT);
  }
  public function get_parent_quiz() {
    return $this->parent_quiz;
  }
  public function fetch_answers_from_mysql($mysqli, $transaction) {
    $fetch_answer_standard = PolyAnswer_Standard::from_mysql($mysqli, $this, $transaction);
    if($fetch_answer_standard['status']) {
      $this->answers = array_merge($this->answers, $fetch_answer_standard['result']);
    }
    $fetch_answer_standard_smart = PolyAnswer_Standard_Smart::from_mysql($mysqli, $this, $transaction);
    if($fetch_answer_standard_smart['status']) {
      $this->answers = array_merge($this->answers, $fetch_answer_standard_smart['result']);
    }
  }
}
class PolyQuestion_Standard extends PolyQuestion {
  public $text = "";
  public $extra_credit = false;
  public $canvas = false;
  public function __construct($parent_quiz) {
    $this->parent_quiz = $parent_quiz;
    $this->question_type = "STANDARD";
  }
  public static function from_mysql($mysqli, $parent_quiz, $transaction = true) {
    $result = array();
    $result['status'] = false;
    $result['result'] = array();
    $transaction ? $mysqli->begin_transaction(MYSQLI_TRANS_START_READ_ONLY) : null;
    $query = "SELECT `question`.`question_id`, `question`.`sort_id`, `question_standard`.`extra_credit`, `question_standard`.`canvas` , `question_standard_text`.`text`"
    . " FROM `question` LEFT JOIN `question_standard` ON `question`.`question_id` = `question_standard`.`question_id`"
    . " LEFT JOIN `question_standard_text` ON `question_standard`.`question_id` = `question_standard_text`.`question_id`"
    . " WHERE `question`.`quiz_id` = ? AND `question`.`question_type` = 'STANDARD';";
    if($stmt = $mysqli->prepare($query)) {
      $stmt->bind_param("i", $parent_quiz->quiz_id);
      if($stmt->execute()) {
        $stmt->bind_result($question_id, $sort_id, $extra_credit, $canvas, $text);
        while($stmt->fetch()) {
          $result['status'] = true;
          $res = array();
          $res['sort_id'] = $sort_id;
          $res['question'] = new self($parent_quiz);
          $res['question']->question_id = $question_id;
          $res['question']->extra_credit = $extra_credit ? true : false;
          $res['question']->canvas = $canvas ? true : false;
          $res['question']->text = $text;
          array_push($result['result'], $res);          
        }
        $stmt->close();
        foreach($result['result'] as &$question) {
          $question['question']->fetch_answers_from_mysql($mysqli, $transaction);
        }
      } else {
        $result['error'] = $mysqli->error;
        $stmt->close();
      }
    } else {
      $result['stmt_error'] = $mysqli->error;
    }
    if($transaction) {
      if(!$mysqli->commit()) {
        $result['commit_error'] = $mysqli->error;
      }
    }
    return $result;
  }
}
?>
