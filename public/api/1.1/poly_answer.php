<?php
class PolyAnswerFactory {
  public static function get($mysqli, $answer_id, $user_id) {
    $to_return = false;
    //$to_return = new PolyQueryResult();
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
          //$to_return[$x] = PolyAnswerFactory::get($mysqli, $x, $user_id);
          $this_answer = PolyAnswerFactory::get($mysqli, $x, $user_id);
          $this_answer->question_id = $question_id;
          array_push($to_return, $this_answer);
        }
      }
    }
    return $to_return;
  }
  static function _create_base_answer($mysqli, $question_id, $answer_type, $user_id) {
    $result = new PolyQueryResult();
    if($stmt = $mysqli->prepare("INSERT INTO `answer` (`question_id`, `answer_type`, `sort_id`, `user_id`) VALUES (?, ?, ?, ?);")) {
      $zero = 0;
      $stmt->bind_param("isii", $question_id, $answer_type, $zero, $user_id);
      if($stmt->execute()) {
        $result->result = $stmt->insert_id;
        $result->status = true;
      } else {
        $result->details = $mysqli->error;
      }
      $stmt->close();
    } else {
      $result->details = $mysqli->error;
    }
    return $result;
  }
  public static function create($mysqli, $question_id, $answer_type, $user_id) {
    $result = new PolyQueryResult();

    switch ($answer_type) {
      case "STANDARD":
        $base_answer_id = PolyAnswerFactory::_create_base_answer($mysqli, $question_id, $answer_type, $user_id);
        $result->AddSubResult($base_answer_id);
        if($base_answer_id->Status()) {

          if($stmt = $mysqli->prepare("INSERT INTO `answer_standard` (`answer_id`, `points`, `user_id`) VALUES (?, ?, ?);")) {
            $zero = 0;
            $stmt->bind_param("iii", $base_answer_id->result, $zero, $user_id);
            if($stmt->execute()) {
              $stmt->close();
              if($stmt = $mysqli->prepare("INSERT INTO `answer_standard_text` (`answer_id`, `text`, `user_id`) VALUES (?, ?, ?);")) {
                $empty_string = "";
                $stmt->bind_param("isi", $base_answer_id->result, $empty_string, $user_id);
                if($stmt->execute()) {
                  $stmt->close();
                  $result->result = PolyAnswerFactory::get($mysqli, $base_answer_id->result, $user_id);
                } else {
                  $stmt->close();
                  $result->details = "Failed to create STANDARD answer_text entry!" . "\n" . $stmt->error;
                }
              }
            } else {
              $result->details = "Failed to create STANDARD answer entry!" . "\n" . $mysqli->error;
              $stmt->close();
              
            }
          }
        } else {
          $result->details = "Failed to create base answer entry!";
        }
        break;
      case "STANDARD_SMART":
        $base_answer = PolyQuestionFactory::_create_base_question($mysqli, $quiz_id, $question_type, $user_id);
        if($base_answer) {
          
        }
        break;
      default:
        # code...
        break;
    }
    return $result;
  }
  public static function delete($mysqli, $answer_id, $user_id) {
    $result = -1;
    if($stmt = $mysqli->prepare("DELETE FROM `answer` WHERE `answer_id` = ? AND `user_id` = ? LIMIT 1;")) {
      $stmt->bind_param("ii", $answer_id, $user_id);
      if($stmt->execute()) {
        $result = $mysqli->affected_rows;
      }
      $stmt->close();
    }
    return $result;
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
  public function save($mysqli, $user_id) {
    PolyAnswer::save($mysqli, $user_id);
    if($stmt = $mysqli->prepare("UPDATE `answer_standard` SET `points` = ? WHERE `answer_id` = ? AND `user_id` = ? LIMIT 1;")) {
      $stmt->bind_param("iii", $this->points, $this->answer_id, $user_id);
      if($stmt->execute()){

      }
      //update points and text
      $stmt->close();
    }
    if($stmt = $mysqli->prepare("UPDATE `answer_standard_text` SET `text` = ? WHERE `answer_id` = ? AND `user_id` = ? LIMIT 1;")) {
      $stmt->bind_param("sii", $this->text, $this->answer_id, $user_id);
      if($stmt->execute()){

      }
      //update points and text
      $stmt->close();
    }
  }
}

class PolyAnswer_Standard_Smart extends PolyAnswer {
  public $points = 0;
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
    if($stmt = $mysqli->prepare(
      "SELECT `answer_standard_smart`.`points` FROM `answer_standard_smart`
        WHERE `answer_standard_smart`.`answer_id` = ? AND `answer_standard_smart`.`user_id` = ? LIMIT 1;")) {
      $stmt->bind_param("ii", $this->answer_id, $user_id);
      if($stmt->execute()) {
        $stmt->bind_result($points_r);
        $stmt->fetch();
        $this->points = $points_r;
      }
      $stmt->close();
    }
    $this->fetch_include_exclude_from_mysql($mysqli, $user_id);
  }
}
?>
