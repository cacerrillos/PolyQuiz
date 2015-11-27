<?php
class PolyQuestion {
  public $question_id = null;
  public $question_type;
  public $data = array();
  public $answers = array();
  public $sort_id = 0;
  public $quiz_id;
  public function __construct($quiz_id) {
    $this->quiz_id = $quiz_id;
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
    if($question_id) {
      if($stmt = $mysqli->prepare("UPDATE `question` SET `question_type` = ?, `sort_id` = ? WHERE `question`.`question_id` = ? AND `question`.`quiz_id` = ? AND `question`.`user_id` = ? LIMIT 1;")) {
        $stmt->bind_param("siiii", $this->question_type, $this->sort_id, $this->question_id, $this->quiz_id, $user_id);
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
      
    } else {
      //doinsert
      if($stmt = $mysqli->prepare("INSERT INTO `question` (`question_id`, `quiz_id`, `question_type`, `sort_id`, `user_id`) VALUES (NULL, ?, ?, ?, ?);")) {
        $stmt->bind_param("isii", $this->quiz_id, $this->question_type, $this->sort_id, $user_id);
        if($stmt->execute()) {
          $response['status'] = true;
          $this->question_id = $stmt->insert_id;
        } else {
          $response['error'] = $mysqli->error;
        }
      } else {
        $response['error'] = $mysqli->error;
      }
    }
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
  public function __construct($quiz_id) {
    $this->quiz_id = $quiz_id;
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
      $stmt->bind_param("ii", $quiz_id, $user_id);
      if($stmt->execute()) {
        $stmt->bind_result($question_id, $sort_id, $extra_credit, $canvas, $text);
        while($stmt->fetch()) {
          $result['status'] = true;
          $new_question = new self($quiz_id);
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
    $response = array();
    $response['status'] = false;
    $num_rows = 0;
    if($stmt = $mysqli->prepare("UPDATE `question_standard` SET `extra_credit` = ?, `canvas` = ? WHERE `question_id` = ? AND `user_id` = ? LIMIT 1;")) {
      $stmt->bind_param("iiii", $this->question_type, $this->canvas, $this->question_id, $user_id);
      if($stmt->execute()) {
        $response['status'] = true;
      } else {
        $response['error'] = $mysqli->error;
      }
      $stmt->close();
      if($response['status']) {
        if($stmt = $mysqli->prepare("UPDATE `question_standard_text` SET `text` = ? WHERE `question_id` = ? AND `user_id` = ? LIMIT 1;")) {
          $stmt->bind_param("sii", $this->text, $this->question_id, $user_id);
          if($stmt->execute()) {
            $num_rows = $stmt->affected_rows;
          } else {
            $response['status'] = false;
            $response['error'] = $mysqli->error;
          }
          $stmt->close();
        } else {
          $response['status'] = false;
        }
      }
    } else {
      $response['error'] = $mysqli->error;
    }
    if($num_rows == 0) {
      ///do insert
      if($stmt = $mysqli->prepare("INSERT INTO `question_standard` (`question_id`, `extra_credit`, `canvas`, `user_id`) VALUES (?, ?, ?, ?);")) {
        $stmt->bind_param("iiii", $this->question_id, $this->extra_credit, $this->canvas, $user_id);
        if($stmt->execute()) {
          $stmt->close();
          if($stmt = $mysqli->prepare("INSERT INTO `question_standard_text` (`question_id`, `text`, `user_id`) VALUES (?, ?, ?);")) {
            $stmt->bind_param("isi", $this->question_id, $this->text, $user_id);
            if($stmt->execute()) {
              $response['status'] = true;
            } else {
              $response['error'] = $mysqli->error;
            }
            $stmt->close();
          }
        } else {
          $stmt->close();
          $response['error'] = $mysqli->error;
        }
      }
    }
    return $response;
  }
}
?>
