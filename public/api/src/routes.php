<?php
// Routes

require __DIR__ . '/../1.1/poly_quiz.php';

require __DIR__ . '/../1.1/poly_quiz_api.php';

require __DIR__ . '/../1.1/poly_question.php';

require __DIR__ . '/../1.1/poly_question_api.php';

require __DIR__ . '/../1.1/poly_answer.php';

require __DIR__ . '/../1.1/poly_answer_api.php';

require __DIR__ . '/../1.1/poly_quiz_admin_api.php';

require __DIR__ . '/../1.1/poly_house.php';

require __DIR__ . '/../1.1/poly_house_api.php';

require __DIR__ . '/../1.1/poly_session_api.php';


$app->get('/', function($request, $response, $args) {
  $result = array();
  $http_status =  200;

  $result['is_admin'] = $this->is_admin;

  return $response->withJson($result, $http_status);
});