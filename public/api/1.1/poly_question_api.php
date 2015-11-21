<?
$app->post('/questions', function() {
  global $_POST_JSON, $mysqli;
  echo json_encode($_POST_JSON, JSON_PRETTY_PRINT);
});
?>