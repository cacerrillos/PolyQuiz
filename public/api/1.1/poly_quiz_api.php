<?php
$app->get('/quizzes', function() {
  global $mysqli;
  if(isset($_GET['id'])) {
    echo json_encode(PolyQuiz::from_mysql($mysqli, $_GET['id'], $_SESSION['dbext']), JSON_PRETTY_PRINT);
  } else {
    echo json_encode(PolyQuiz::all_from_mysql($mysqli, $_SESSION['dbext']), JSON_PRETTY_PRINT);
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
