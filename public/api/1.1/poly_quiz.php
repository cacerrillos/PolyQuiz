<?php
class PolyQuiz {
  public $quiz_id;
  public $quiz_name;
  public $questions = array();
  public function __construct($quiz_id, $quiz_name) {
    $this->quiz_id = $quiz_id;
    $this->quiz_name = $quiz_name;
  }
  public function addQuestion($question) {
    $this->questions[$question->question_id] = $question;
  }
  public function to_json_admin() {
    return json_encode($this, JSON_PRETTY_PRINT);
  }
  public function to_json_client() {

  }
  public function get_question_sort_id($question_id) {
    return $questions[$question_id]['sort_id'];
  }
  public function save($mysqli, $user_id) {
    $result = false;
    if($stmt = $mysqli->prepare("UPDATE `quiz` SET `quiz_name` = ? WHERE `quiz`.`quiz_id` = ? AND `quiz`.`user_id` = ?;")) {
      $stmt->bind_param("sii", $this->quiz_name, $this->quiz_id, $user_id);
      if($stmt->execute()) {
        if($stmt->affected_rows == 1) {
          $result = true;
        }
      }
      $stmt->close();
    }
    return $result;
  }
  public static function all_from_mysql($mysqli, $user_id, $fetch_all_data = false) {
    $result = array();
    $result['status'] = false;
    if($stmt = $mysqli->prepare("SELECT `quiz`.`quiz_id` FROM `quiz` WHERE `user_id` = ?;")) {
      $stmt->bind_param("i", $user_id);
      if($stmt->execute()) {
        $stmt->bind_result($quiz_id_r);
        $result['result'] = array();
        while($stmt->fetch()) {
          $result['status'] = true;
          array_push($result['result'], $quiz_id_r);
        }
        $stmt->close();
        $final_result = array();
        foreach($result['result'] as $quiz_id_to_fetch) {
          $quiz = PolyQuiz::from_mysql($mysqli, $quiz_id_to_fetch, $user_id, $fetch_all_data);
          if($quiz['status']) {
             $final_result[$quiz['result']->quiz_id] = $quiz['result'];
          }
        }
        $result['result'] = $final_result;
      } else {
        $result['error'] = $mysqli->error;
        $stmt->close();
      }
    } else {
      $result['stmt_error'] = $mysqli->error;
    }
    return $result['result'];
  }
  public static function from_mysql($mysqli, $quiz_id, $user_id, $fetch_all_data = false) {
    $result = array();
    $result['status'] = false;
    if($stmt = $mysqli->prepare("SELECT `quiz_name` FROM `quiz` WHERE `quiz_id` = ? AND `user_id` = ? LIMIT 1;")) {
      $stmt->bind_param("ii", $quiz_id, $user_id);
      if($stmt->execute()) {
        $stmt->bind_result($quiz_name_r);
        if($stmt->fetch()) {
          $result['status'] = true;
          $result['result'] = new PolyQuiz($quiz_id, $quiz_name_r);
          $stmt->close();
          $fetch_all_data ? $result['result']->fetch_questions_from_mysql($mysqli, $user_id) : null;
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
  public function fetch_questions_from_mysql($mysqli, $user_id) {
    $questions = PolyQuestionFactory::get_by_quiz($mysqli, $this->quiz_id, $user_id);
    if($questions) {
      //$this->questions = $this->questions + $questions;
      $this->questions = array_merge($this->questions, $questions);
      //array_push($this->questions, $questions);
    }
  }
  public static function create($mysqli, $quiz_name, $user_id) {
    $result = array();
    $result['status'] = false;
    if($stmt = $mysqli->prepare("INSERT INTO `quiz` (`quiz_id`, `quiz_name`, `user_id`) VALUES (NULL, ?, ?)")) {
      $stmt->bind_param("si", $quiz_name, $user_id);
      if($stmt->execute()) {
        $result['status'] = true;
        $result['result'] = new PolyQuiz($stmt->insert_id, $quiz_name);
      } else {
        $result['error'] = $mysqli->error;
      }
      $stmt->close();
    } else {
      $result['stmt_error'] = $mysqli->error;
    }
    return $result;
  }
  public static function delete($mysqli, $quiz_id, $user_id) {
    $result = array();
    $result['status'] = false;
    if($stmt = $mysqli->prepare("DELETE FROM `quiz` WHERE `quiz_id` = ? AND `user_id` = ? LIMIT 1;")) {
      $stmt->bind_param("ii", $quiz_id, $user_id);
      if($stmt->execute()) {
        $result['status'] = true;
      } else {
        $result['error'] = $mysqli->error;
      }
      $stmt->close();
    } else {
      $result['stmt_error'] = $mysqli->error;
    }
    return $result;
  }
}



if(isset($_GET['test'])) {
  include_once("../../func/config.func.php");
  $mysqli = new mysqli($db_host, $db_user, $db_password);
  $mysqli -> select_db($db_name);


  $polyQuiz = PolyQuiz::from_mysql($mysqli, 8, -1337, true);

  if($polyQuiz['status']) {
    $polyQuiz = $polyQuiz['result'];
    /*
    $polyQuestion = new PolyQuestion_Standard($polyQuiz);
    $polyQuestion->question_id = 1;
    $polyQuestion->text = "Which producer is mostly known for Trance music?";
    $polyQuiz->addQuestion($polyQuestion);

    $polyAnswer_Standard = new PolyAnswer_Standard($polyQuestion);
    $polyAnswer_Standard->answer_id = 1;
    $polyAnswer_Standard->text = "Tiesto";
    $polyQuestion->addAnswer($polyAnswer_Standard);

    $polyAnswer_Standard = new PolyAnswer_Standard($polyQuestion);
    $polyAnswer_Standard->answer_id = 2;
    $polyAnswer_Standard->points = 1;
    $polyAnswer_Standard->text = "Cosmic Gate";
    $polyQuestion->addAnswer($polyAnswer_Standard);

    $polyAnswer_Standard = new PolyAnswer_Standard($polyQuestion);
    $polyAnswer_Standard->answer_id = 3;
    $polyAnswer_Standard->points = 1;
    $polyAnswer_Standard->text = "Armin Van Burren";
    $polyQuestion->addAnswer($polyAnswer_Standard);

    $polyAnswer_Standard_Smart = new PolyAnswer_Standard_Smart($polyQuestion);
    $polyAnswer_Standard_Smart->answer_id = 4;
    $polyAnswer_Standard_Smart->points = 4;
    $polyAnswer_Standard_Smart->add_include(2);
    $polyAnswer_Standard_Smart->add_include(3);
    $polyQuestion->addAnswer($polyAnswer_Standard_Smart);
    */
    echo "<pre>" . $polyQuiz->to_json_admin() . "</pre>";
  }
  
}
?>
