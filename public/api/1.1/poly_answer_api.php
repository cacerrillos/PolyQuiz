<?php

$app->get('/answers', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;
  $query_params = $request->getQueryParams();

  if(isset($query_params['answer_id'])) {
    $result = PolyAnswerFactory::get($this->db, $query_params['answer_id'], $_SESSION['dbext']);
  }

  return $response->withJson($result, $http_status);
});

$app->get('/answers/{answer_id}', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;

  $result = PolyAnswerFactory::get($this->db, $args['answer_id'], $_SESSION['dbext']);

  return $response->withJson($result, $http_status);
});

$app->post('/answers', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;
  $parsed_body = $request->getParsedBody();

  $answer = false;

  $question = PolyQuestionFactory::get($this->db, $parsed_body['question_id'], $_SESSION['dbext']);
  if($question) {
    if($parsed_body['type'] == "STANDARD") {
      $answer = PolyAnswerFactory::create($this->db, $parsed_body['question_id'], "STANDARD", $_SESSION['dbext']);
      $answer->result->points = $parsed_body['points'];
      $answer->result->text = $parsed_body['text'];
      $result = $answer->result->save($this->db, $_SESSION['dbext']);
    }
  }

  return $response->withJson($result, $http_status);
});

$app->delete('/answers', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;
  $query_params = $request->getQueryParams();

  if(isset($query_params['answer_id'])) {
    $result = PolyAnswerFactory::delete($this->db, $query_params['answer_id'], $_SESSION['dbext']);
  }

  return $response->withJson($result, $http_status);
});

$app->delete('/answers/{answer_id}', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;

  $result = PolyAnswerFactory::delete($this->db, $args['answer_id'], $_SESSION['dbext']);

  return $response->withJson($result, $http_status);
});

