<?

$app->get('/questions', function() {
  global $mysqli;
  if(isset($_GET['question_id']) && isset($_GET['quiz_id'])) {
    //give error
    echo json_encode(false, JSON_PRETTY_PRINT);
  } else if(isset($_GET['question_id'])) {
    echo json_encode(PolyQuestionFactory::get($mysqli, intval($_GET['question_id']), $_SESSION['dbext']), JSON_PRETTY_PRINT);
  } else if(isset($_GET['quiz_id'])) {
    echo json_encode(PolyQuestionFactory::get_by_quiz($mysqli, intval($_GET['quiz_id']), $_SESSION['dbext']), JSON_PRETTY_PRINT);
  }
});

$app->get('/questions/:questionid', function($questionid) {
  global $mysqli;
  echo json_encode(PolyQuestionFactory::get($mysqli, intval($questionid), $_SESSION['dbext']), JSON_PRETTY_PRINT);
});

$app->post('/questions', function() {
  global $_POST_JSON, $mysqli;
  $question = false;
  $quiz = PolyQuiz::from_mysql($mysqli, $_POST_JSON['quiz_id'], $_SESSION['dbext']);
  if($_POST_JSON['type'] == "STANDARD") {
    $question = PolyQuestionFactory::create($mysqli, $_POST_JSON['quiz_id'], "STANDARD", $_SESSION['dbext']);
    $question->text = $_POST_JSON['text'];
    $question->extra_credit = $_POST_JSON['extra_credit'];
    $question->canvas = $_POST_JSON['canvas'];
    $res = $question->save($mysqli, $_SESSION['dbext']);
  }
  echo json_encode($res, JSON_PRETTY_PRINT);
});

$app->put('/questions/:questionid', function($questionid) {
  global $_POST_JSON, $mysqli;

  $res = false;
  if($_POST_JSON['type'] == "STANDARD") {
    $question = PolyQuestionFactory::get($mysqli, $questionid, $_SESSION['dbext']);
    if($question) {
      if(isset($_POST_JSON['text'])) {
        $question->text = $_POST_JSON['text'];
      }
      if(isset($_POST_JSON['extra_credit'])) {
        $question->extra_credit = $_POST_JSON['extra_credit'];
      }
      if(isset($_POST_JSON['canvas'])) {
        $question->canvas = $_POST_JSON['canvas'];
      }
      $res = $question->save($mysqli, $_SESSION['dbext']);
    }
  }
  echo json_encode($res, JSON_PRETTY_PRINT);
});

$app->delete('/questions', function($questionid) {
  global $mysqli;
  $result = false;
  if(isset($_GET['question_id'])) {
    $result = PolyQuestionFactory::delete($mysqli, $_GET['question_id'], $_SESSION['dbext']);
  }
  echo json_encode($result, JSON_PRETTY_PRINT);
});

$app->delete('/questions/:questionid', function($questionid) {
  global $mysqli;
  $result = PolyQuestionFactory::delete($mysqli, $questionid, $_SESSION['dbext']);
  echo json_encode($result, JSON_PRETTY_PRINT);
});

?>
