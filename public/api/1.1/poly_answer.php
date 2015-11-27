<?php
class PolyAnswerFactory {
  public static function get($mysqli, $answer_id, $user_id) {
    $to_return = false;
    if($stmt = $mysqli->prepare("SELECT `answer_type` FROM `answer` WHERE `answer_id` = ? AND `user_id` = ? LIMIT 1;")) {
      $stmt->bind_param("ii", $answer_id, $user_id);
      if($stmt->execute()) {
        $stmt->bind_result($answer_type_r);
        $stmt->fetch();
        $type = $answer_type_r;
        $stmt->close();
        switch ($type) {
          case "STANDARD":
            $to_return = new PolyAnswer_Standard($answer_id);
            $to_return->fetch($mysqli, $user_id);
            break;
          case "STANDARD_SMART":
            $to_return = new PolyAnswer_Standard_Smart($answer_id);
            $to_return->fetch($mysqli, $user_id);
            break;
          default:
            break;
        }
      } else {
        echo $mysqli->error;
      }
    }
    return $to_return;
  }
  public static function get_by_question($mysqli, $question_id, $user_id) {
    $to_return = array();
    $to_fetch = array();
    if($stmt = $mysqli->prepare("SELECT `answer_id` FROM `answer` WHERE `question_id` = ? AND `user_id` = ?;")) {
      $stmt->bind_param("ii", $question_id, $user_id);
      if($stmt->execute()) {
        $stmt->bind_result($answer_id_r);
        while($stmt->fetch()) {
          array_push($to_fetch, $answer_id_r);
        }
        $stmt->close();
        foreach($to_fetch as &$x) {
          $to_return[$x] = PolyAnswerFactory::get($mysqli, $x, $user_id);
        }
      }
    }
    return $to_return;
  }
}
class PolyAnswer {
  public $answer_id;
  public $type; //STANDARD, STANDARD_SMART
  public $sort_id = 0;
  public $question_id;
  public function __construct($answer_id){
    $this->answer_id = $answer_id;
  }
  public function toJSON(){
    return json_encode($this, JSON_PRETTY_PRINT);
  }
  public function fetch($mysqli, $user_id) {
    if($stmt = $mysqli->prepare("SELECT `answer_type`, `sort_id` FROM `answer` WHERE `answer_id` = ? AND `user_id` = ? LIMIT 1;")) {
      $stmt->bind_param("ii", $this->answer_id, $user_id);
      if($stmt->execute()) {
        $stmt->bind_result($answer_type_r, $sort_id_r);
        $stmt->fetch();
        $this->answer_type = $answer_type_r;
        $this->sort_id = $sort_id_r;
      }
      $stmt->close();
    }
  }
  public function from_mysql($mysqli, $answer_id, $user_id) {
    if($stmt = $mysqli->prepare("SELECT `answer_type`, `sort_id` FROM `answer` WHERE `answer_id` = ? AND `user_id` = ? LIMIT 1;")) {
      $stmt->bind_param("ii", $answer_id, $user_id);
      if($stmt->execute()) {
        $stmt->bind_result($answer_type_r, $sort_id_r);
        $stmt->fetch();
        $this->answer_type = $answer_type_r;
        $this->sort_id = $sort_id_r;
      }
      $stmt->close();
    }
  }
  public function save($mysqli, $user_id) {
    $response = array();
    $response['status'] = false;
    if($stmt = $mysqli->prepare("UPDATE `answer` SET `answer_type` = ?, `sort_id` = ? WHERE `answer`.`answer_id` = ? AND `user_id` = ? LIMIT 1;")) {
      $stmt->bind_param("siii", $this->type, $this->sort_id, $this->answer_id, $user_id);
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
  public function __construct($answer_id) {
    $this->answer_id = $answer_id;
    $this->type = "STANDARD";
  }
  public function getText() {
    return $this->text;
  }
  public function fetch($mysqli, $user_id) {
    PolyAnswer::fetch($mysqli, $user_id);

    if($stmt = $mysqli->prepare(
      "SELECT `answer_standard`.`points`, `answer_standard_text`.`text` FROM `answer_standard`
        LEFT JOIN `answer_standard_text` ON `answer_standard`.`answer_id` = `answer_standard_text`.`answer_id`
        WHERE `answer_standard`.`answer_id` = ? AND `answer_standard`.`user_id` = ? LIMIT 1;")) {
      $stmt->bind_param("ii", $this->answer_id, $user_id);
      if($stmt->execute()) {
        $stmt->bind_result($points_r, $text_r);
        $stmt->fetch();
        $this->points = $points_r;
        $this->text = $text_r;
      }
      $stmt->close();
    }
  }
  public function from_mysql($mysqli, $answer_id, $user_id) {
    PolyAnswer::from_mysql($mysqli, $answer_id, $user_id);
    if($stmt = $mysqli->prepare(
      "SELECT `answer_standard`.`points` FROM `answer_standard` "
      . " LEFT JOIN `answer_standard_text` ON `answer_standard`.`answer_id` = `answer_standard_text`.`answer_id` "
      . " WHERE `answer_standard`.`answer_id` = ? LIMIT 1;")) {
      $stmt->bind_param("i", $answer_id);
      if($stmt->execute()) {
        $stmt->bind_result($points_r, $text_r);
        $stmt->fetch();
        $this->points = $points_r;
        $this->text = $text_r;
      }
      $stmt->close();
    }
  }
}

class PolyAnswer_Standard_Smart extends PolyAnswer_Standard {
  public $include = array();
  public $exclude = array();
  public function __construct($answer_id) {
    $this->answer_id = $answer_id;
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
  function fetch_include_exclude_from_mysql($mysqli, $user_id) {
    $this->fetch_include_from_mysql($mysqli, $user_id);
    $this->fetch_exclude_from_mysql($mysqli, $user_id);
  }
  function fetch_include_from_mysql($mysqli, $user_id) {
    $query = "SELECT `answer_standard_smart_include`.`selected_answer_id` FROM `answer_standard_smart_include`"
      . " WHERE `answer_standard_smart_include`.`answer_id` = ? AND `user_id` = ?;";
    if($stmt = $mysqli->prepare($query)) {
      $stmt->bind_param("ii", $this->answer_id, $user_id);
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
    return $result;
  }
  function fetch_exclude_from_mysql($mysqli, $user_id) {
    $query = "SELECT `answer_standard_smart_exclude`.`selected_answer_id` FROM `answer_standard_smart_exclude`"
      . " WHERE `answer_standard_smart_exclude`.`answer_id` = ? AND `user_id` = ?;";
    if($stmt = $mysqli->prepare($query)) {
      $stmt->bind_param("ii", $this->answer_id, $user_id);
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
    return $result;
  }
  public function fetch($mysqli, $user_id) {
    PolyAnswer::fetch($mysqli, $user_id);
    $this->fetch_include_exclude_from_mysql($mysqli, $user_id);
  }
  public function from_mysql($mysqli, $answer_id, $user_id) {
    PolyAnswer::from_mysql($mysqli, $answer_id, $user_id);
    $this->fetch_include_exclude_from_mysql($mysqli, $user_id);
  }
}
?>
