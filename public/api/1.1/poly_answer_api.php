<?php

$app->get('/answers', function() {
  global $mysqli;
  if(isset($_GET['answer_id'])) {
    echo json_encode(PolyAnswerFactory::get($mysqli, $_GET['answer_id'], $_SESSION['dbext']), JSON_PRETTY_PRINT);
  }
});

$app->get('/answers/:answerid', function($answerid) {
  global $mysqli;
  //$answer = PolyAnswer
  echo json_encode(PolyAnswerFactory::get($mysqli, $answerid, $_SESSION['dbext']), JSON_PRETTY_PRINT);
});

$app->post('/answers', function() {
  global $_POST_JSON, $mysqli;
  $answer = false;
  $res = false;
  $question = PolyQuestionFactory::get($mysqli, $_POST_JSON['question_id'], $_SESSION['dbext']);
  if($question) {
    if($_POST_JSON['type'] == "STANDARD") {
      $answer = PolyAnswerFactory::create($mysqli, $_POST_JSON['question_id'], "STANDARD", $_SESSION['dbext']);
      var_dump($answer);
      $answer->result->points = $_POST_JSON['points'];
      $answer->result->text = $_POST_JSON['text'];
      var_dump($answer);
      $res = $answer->result->save($mysqli, $_SESSION['dbext']);
      var_dump($answer);
    }
  }
  

  echo json_encode($res, JSON_PRETTY_PRINT);
});

$app->delete('/answers', function() {
  global $mysqli;
  $result = false;
  if(isset($_GET['answer_id'])) {
    $result = PolyAnswerFactory::delete($mysqli, $_GET['answer_id'], $_SESSION['dbext']);
  }
  echo json_encode($result, JSON_PRETTY_PRINT);
});

$app->delete('/answers/:answerid', function($answerid) {
  global $mysqli;
  $result = PolyAnswerFactory::delete($mysqli, $answerid, $_SESSION['dbext']);
  echo json_encode($result, JSON_PRETTY_PRINT);
});

?>
