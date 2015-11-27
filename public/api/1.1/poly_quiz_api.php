<?php
$app->get('/quizzes', function() {
  global $mysqli;
  if(isset($_GET['quiz_id'])) {
    get_quiz($mysqli, $_GET['quiz_id'], $_SESSION['dbext'], isset($_GET['all_data']));
  } else {
    echo json_encode(PolyQuiz::all_from_mysql($mysqli, $_SESSION['dbext']), JSON_PRETTY_PRINT);
  }
});

$app->get('/quizzes/:quizid', function($quizid) {
  global $mysqli;
  get_quiz($mysqli, $quizid, $_SESSION['dbext'], isset($_GET['all_data']));
});

$app->get('/quizzes/:quizid/questions', function($quizid) {
  global $mysqli;
  echo json_encode(PolyQuestionFactory::get_by_quiz($mysqli, intval($quizid), $_SESSION['dbext']), JSON_PRETTY_PRINT);
});

function get_quiz($mysqli, $quiz_id, $user_id, $all_data) {
  echo json_encode(PolyQuiz::from_mysql($mysqli, $quiz_id, $user_id, $all_data), JSON_PRETTY_PRINT);
}

$app->post('/quizzes', function() {
  global $mysqli, $_POST_JSON;
  if(isAdmin()) {
    if(isset($_GET['quiz_name'])) {
      echo json_encode(PolyQuiz::create($mysqli, $_GET['quiz_name'], $_SESSION['dbext']), JSON_PRETTY_PRINT);
    } else if(isset($_POST_JSON['quiz_name'])) {
      echo json_encode(PolyQuiz::create($mysqli, $_POST_JSON['quiz_name'], $_SESSION['dbext']), JSON_PRETTY_PRINT);
    }
  }
});

$app->delete('/quizzes/:quizid', function($quizid) {
  global $mysqli;
  if(isAdmin()) {
    echo json_encode(PolyQuiz::delete($mysqli, $quizid, $_SESSION['dbext']), JSON_PRETTY_PRINT);
  }
});

$app->delete('/quizzes', function() {
  global $mysqli;
  if(isset($_GET['quiz_id'])) {
    if(isAdmin()) {
      echo json_encode(PolyQuiz::delete($mysqli, $_GET['quiz_id'], $_SESSION['dbext']), JSON_PRETTY_PRINT);
    }
  }
});
?>
