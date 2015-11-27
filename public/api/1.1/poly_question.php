<?php
class PolyQuestion {
  public $question_id = null;
  public $question_type;
  public $data = array();
  public $answers = array();
  public $sort_id = 0;
  protected $parent_quiz;
  public function __construct($parent_quiz) {
    $this->parent_quiz = $parent_quiz;
  }
  public function addAnswer($answer) {
    $this->answers[$answer->answer_id] = $answer;
  }
  public function get_answer($answer_id) {
    if(isset($this->answers[$answer_id])) {
      return $this->answers[$answer_id];
    }
  }
  public function toJSON(){
    return json_encode($this, JSON_PRETTY_PRINT);
  }
  public function get_parent_quiz() {
    return $this->parent_quiz;
  }
  function count_answers_by_type($answer_type) {
    $counter = 0;
    foreach($this->answers as &$answer) {
      if($answer->type == $answer_type) {
        $counter++;
      }
    }
    return $counter;
  }
  public function fetch_answers_from_mysql($mysqli, $user_id) {
    $fetch_answer_standard = PolyAnswer_Standard::all_from_mysql($mysqli, $this, $user_id);
    if($fetch_answer_standard['status']) {
      //$this->answers = array_merge($this->answers, $fetch_answer_standard['result']);
      $this->answers = $this->answers + $fetch_answer_standard['result'];
    }
    $fetch_answer_standard_smart = PolyAnswer_Standard_Smart::all_from_mysql($mysqli, $this, $user_id);
    if($fetch_answer_standard_smart['status']) {
      //$this->answers = array_merge($this->answers, $fetch_answer_standard_smart['result']);
      $this->answers = $this->answers + $fetch_answer_standard_smart['result'];
    }
    $do_sort = false;
    foreach($this->answers as &$answer) {
      if($answer->sort_id == 0) {
        $do_sort = true;
        break;
      }
    }

    if($do_sort) {
      $num_of_standard = $this->count_answers_by_type("STANDARD");
      $num_of_standard_smart = $this->count_answers_by_type("STANDARD_SMART");
      $total_num = $num_of_standard + $num_of_standard_smart;
      
      $standard_arr = range(1, $num_of_standard);
      $standard_counter = 0;
      $standard_smart_arr = range($num_of_standard, $total_num);
      $standard_smart_counter = 0;

      foreach($this->answers as &$answer) {
        if($answer->type == "STANDARD") {
          $answer->sort_id = $standard_arr[$standard_counter];
          $standard_counter++;
        } else if($answer->type == "STANDARD_SMART") {
          $answer->sort_id = $standard_smart_arr[$standard_smart_counter];
          $standard_smart_counter++;
        }
      }
      $this->save($mysqli, $user_id);
    }
  }
  public function save($mysqli, $user_id) {
    $response = array();
    $response['status'] = false;
    if($stmt = $mysqli->prepare("UPDATE `question` SET `question_type` = ?, `sort_id` = ? WHERE `question`.`question_id` = ? AND `question`.`quiz_id` = ? AND `question`.`user_id` = ? LIMIT 1;")) {
      $stmt->bind_param("siiii", $this->question_type, $this->parent_quiz->get_question_sort_id($this->question_id), $this->question_id, $this->parent_quiz->quiz_id, $user_id);
      if($stmt->execute()) {
        $response['status'] = true;
      } else {
        $response['error'] = $mysqli->error;
      }
      $stmt->close();
    } else {
      $response['error'] = $mysqli->error;
    }
    //save all answers
    foreach($this->answers as &$answer) {
      echo gettype($answer);
      $answer->save($mysqli, $user_id);
    }
    return $response;
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
  public static function all_from_mysql($mysqli, $parent_quiz, $user_id) {
    $result = array();
    $result['status'] = false;
    $result['result'] = array();
    $query = "SELECT `question`.`question_id`, `question`.`sort_id`, `question_standard`.`extra_credit`, `question_standard`.`canvas` , `question_standard_text`.`text`"
    . " FROM `question` LEFT JOIN `question_standard` ON `question`.`question_id` = `question_standard`.`question_id`"
    . " LEFT JOIN `question_standard_text` ON `question_standard`.`question_id` = `question_standard_text`.`question_id`"
    . " WHERE `question`.`quiz_id` = ? AND `question`.`question_type` = 'STANDARD' AND `question`.`user_id` = ?;";
    if($stmt = $mysqli->prepare($query)) {
      $stmt->bind_param("ii", $parent_quiz->quiz_id, $user_id);
      if($stmt->execute()) {
        $stmt->bind_result($question_id, $sort_id, $extra_credit, $canvas, $text);
        while($stmt->fetch()) {
          $result['status'] = true;
          $new_question = new self($parent_quiz);
          $new_question->question_id = $question_id;
          $new_question->extra_credit = $extra_credit ? true : false;
          $new_question->canvas = $canvas ? true : false;
          $new_question->text = $text;
          //array_push($result['result'], $new_question);
          $result['result'][$question_id] = $new_question;
        }
        $stmt->close();
        foreach($result['result'] as &$question) {
          $question->fetch_answers_from_mysql($mysqli, $user_id);
        }
      } else {
        $result['error'] = $mysqli->error;
        $stmt->close();
      }
    } else {
      $result['stmt_error'] = $mysqli->error;
    }
    return $result;
  }
  public function save($mysqli, $user_id) {
    PolyQuestion::save($mysqli, $user_id);

  }
}
?>
