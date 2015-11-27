<?
$app->post('/questions', function() {
  global $_POST_JSON, $mysqli;
  $quiz = PolyQuiz::from_mysql($mysqli, $_POST_JSON['quiz_id'], $_SESSION['dbext']);
  if($_POST_JSON['type'] == "STANDARD") {
    $question = new PolyQuestion_Standard($quiz['result']);
    $question->text = $_POST_JSON['text'];
    $question->extra_credit = $_POST_JSON['extra_credit'];
    $question->canvas = $_POST_JSON['canvas'];
    $res = $question->save($mysqli, $_SESSION['dbext']);
  }
  echo json_encode($res, JSON_PRETTY_PRINT);
});
?>