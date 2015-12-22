<?php
$app->get('/answers/:answerid', function($answerid) {
  global $mysqli;
  //$answer = PolyAnswer
  echo json_encode(PolyAnswerFactory::get($mysqli, $answerid, $_SESSION['dbext']), JSON_PRETTY_PRINT);
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
