<?php
$app->get('/answers/:answerid', function($answerid) {
  global $mysqli;
  //$answer = PolyAnswer
  echo json_encode(PolyAnswerFactory::get($mysqli, $answerid, $_SESSION['dbext']), JSON_PRETTY_PRINT);
});
?>
