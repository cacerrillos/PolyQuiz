<?php
$app->get('/quizzes', function() {
  global $mysqli;
  if(isset($_GET['id'])) {
    echo json_encode(PolyQuiz::from_mysql($mysqli, $_GET['id'], $_SESSION['dbext']), JSON_PRETTY_PRINT);
  } else {
    echo json_encode(PolyQuiz::all_from_mysql($mysqli, $_SESSION['dbext']), JSON_PRETTY_PRINT);
  }
  
});
?>
