<?php
class PolyAnswer {
  public $answer_id;
  public $type; //STANDARD, STANDARD_SMART
  protected $parent_question;
  public function __construct($parent_question){
    $this->parent_question = $parent_question;
  }
  public function toJSON(){
    return json_encode($this, JSON_PRETTY_PRINT);
  }
  public function get_sort_id() {
    return $this->parent_question->get_answer_sort_id($this->answer_id);
  }
  public function save($mysqli) {
    $response = array();
    $response['status'] = false;
    if($stmt = $mysqli->prepare("UPDATE `answer` SET `answer_type` = ?, `sort_id` = ? WHERE `answer`.`answer_id` = ? AND `answer`.`question_id` = ? LIMIT 1;")) {
      $stmt->bind_param("siii", $this->type, $this->get_sort_id(), $this->answer_id, $this->parent_question->question_id);
      if($stmt->execute()) {
        $response['status'] = true;
      } else {
        $response['error'] = $mysqli->error;
      }
      $stmt->close();
    } else {
      $response['error'] = $mysqli->error;
    }
    return $response;
  }
}

class PolyAnswer_Standard extends PolyAnswer {
  public $text = "";
  public $points = 0;
  public function __construct($parent_question) {
    $this->parent_question = $parent_question;
    $this->type = "STANDARD";
  }
  public function getText() {
    return $this->text;
  }
  public static function from_mysql($mysqli, $parent_question, $transaction) {
    $result = array();
    $result['status'] = false;
    $result['result'] = array();
    $transaction ? $mysqli->begin_transaction(MYSQLI_TRANS_START_READ_ONLY) : null;
    $query = "SELECT `answer`.`answer_id`, `answer`.`sort_id`, `answer_standard_text`.`text`"
    . " FROM `answer` LEFT JOIN `answer_standard` ON `answer`.`answer_id` = `answer_standard`.`answer_id`"
    . " LEFT JOIN `answer_standard_text` ON `answer_standard`.`answer_id` = `answer_standard_text`.`answer_id`"
    . " WHERE `answer`.`question_id` = ? AND `answer`.`answer_type` = 'STANDARD';";
    if($stmt = $mysqli->prepare($query)) {
      $stmt->bind_param("i", $parent_question->question_id);
      if($stmt->execute()) {
        $stmt->bind_result($answer_id, $sort_id, $text);
        while($stmt->fetch()) {
          $result['status'] = true;
          $res = array();
          $res['sort_id'] = $sort_id;
          $res['answer'] = new self($parent_question);
          $res['answer']->answer_id = $answer_id;
          $res['answer']->text = $text;
          $result['result'][$answer_id] = $res;
        }
        $stmt->close();
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

class PolyAnswer_Standard_Smart extends PolyAnswer_Standard {
  public $include = array();
  public $exclude = array();
  public function __construct($parent_question) {
    $this->parent_question = $parent_question;
    $this->type = "STANDARD_SMART";
  }
  public function getText() {
    return "swag";
  }
  public function add_include($answer_id) {
    $this->array_push_if_not_exists($this->include, $answer_id);
  }
  public function remove_include($answer_id) {
    $this->array_unset_if_exists($this->include, $answer_id);
  }
  public function add_exclude($answer_id) {
    $this->array_push_if_not_exists($this->exclude, $answer_id);
  }
  public function remove_exclude($answer_id) {
    $this->array_unset_if_exists($this->exclude, $answer_id);
  }
  function array_push_if_not_exists(&$array, $value) {
    if(!in_array($value, $array)) {
      array_push($array, $value);
    }
  }
  function array_unset_if_exists(&$array, $value) {
    if(($key = array_search($value, $array)) !== false) {
      unset($array[$key]);
    }
  }
  function fetch_include_exclude_from_mysql($mysqli, $transaction = true) {
    $this->fetch_include_from_mysql($mysqli, $transaction);
    $this->fetch_exclude_from_mysql($mysqli, $transaction);
  }
  function fetch_include_from_mysql($mysqli, $transaction = true) {
    $transaction ? $mysqli->begin_transaction(MYSQLI_TRANS_START_READ_ONLY) : null;
    $query = "SELECT `answer_standard_smart_include`.`selected_answer_id` FROM `answer_standard_smart_include`"
      . " WHERE `answer_standard_smart_include`.`answer_id` = ?;";
    if($stmt = $mysqli->prepare($query)) {
      $stmt->bind_param("i", $this->answer_id);
      if($stmt->execute()) {
        $stmt->bind_result($selected_answer_id);
        while($stmt->fetch()) {
          $result['status'] = true;
          $this->add_include($selected_answer_id);
        }
        $stmt->close();
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
  function fetch_exclude_from_mysql($mysqli, $transaction = true) {
    $transaction ? $mysqli->begin_transaction(MYSQLI_TRANS_START_READ_ONLY) : null;
    $query = "SELECT `answer_standard_smart_exclude`.`selected_answer_id` FROM `answer_standard_smart_exclude`"
      . " WHERE `answer_standard_smart_exclude`.`answer_id` = ?;";
    if($stmt = $mysqli->prepare($query)) {
      $stmt->bind_param("i", $this->answer_id);
      if($stmt->execute()) {
        $stmt->bind_result($selected_answer_id);
        while($stmt->fetch()) {
          $result['status'] = true;
          $this->add_exclude($selected_answer_id);
        }
        $stmt->close();
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
  public static function from_mysql($mysqli, $parent_question, $transaction = true) {
    $result = array();
    $result['status'] = false;
    $result['result'] = array();
    $transaction ? $mysqli->begin_transaction(MYSQLI_TRANS_START_READ_ONLY) : null;
    $query = "SELECT `answer`.`answer_id`, `answer`.`sort_id` FROM `answer`"
      . " WHERE `answer`.`question_id` = ? AND `answer`.`answer_type` = 'STANDARD_SMART'";
    if($stmt = $mysqli->prepare($query)) {
      $stmt->bind_param("i", $parent_question->question_id);
      if($stmt->execute()) {
        $stmt->bind_result($answer_id, $sort_id);
        while($stmt->fetch()) {
          $result['status'] = true;
          $res = array();
          $res['sort_id'] = $sort_id;
          $res['answer'] = new self($parent_question);
          $res['answer']->answer_id = $answer_id;
          $result['result'][$answer_id] = $res;
        }
        $stmt->close();
        foreach($result['result'] as &$ans) {
          $ans['answer']->fetch_include_exclude_from_mysql($mysqli, $transaction);
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
