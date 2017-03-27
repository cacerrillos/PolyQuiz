<?php

$app->get('/questions', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;
  $query_params = $request->getQueryParams();

  if(isset($query_params['question_id']) && isset($query_params['quiz_id'])) {
    //give error
    // echo json_encode(false;
  } else if(isset($query_params['question_id'])) {
    $result = PolyQuestionFactory::get($this->db, intval($query_params['question_id']), $_SESSION['dbext']);
  } else if(isset($query_params['quiz_id'])) {
    $result = PolyQuestionFactory::get_by_quiz($this->db, intval($query_params['quiz_id']), $_SESSION['dbext']);
  }

  return $response->withJson($result, $http_status);
});

$app->get('/questions/{question_id}', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;
  $query_params = $request->getQueryParams();
  $parsed_body = $request->getParsedBody();

  $result = PolyQuestionFactory::get($this->db, intval($args['question_id']), $_SESSION['dbext']);

  return $response->withJson($result, $http_status);
});

$app->post('/questions', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;
  $parsed_body = $request->getParsedBody();

  $question = false;
  $quiz = PolyQuiz::from_mysql($this->db, $parsed_body['quiz_id'], $_SESSION['dbext']);
  if($parsed_body['type'] == "STANDARD") {
    $question = PolyQuestionFactory::create($this->db, $parsed_body['quiz_id'], "STANDARD", $_SESSION['dbext']);
    $question->text = $parsed_body['text'];
    $question->extra_credit = $parsed_body['extra_credit'];
    $question->canvas = $parsed_body['canvas'];
    $result = $question->save($this->db, $_SESSION['dbext']);
  }
  
  return $response->withJson($result, $http_status);
});

$app->put('/questions', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;
  $query_params = $request->getQueryParams();
  $parsed_body = $request->getParsedBody();

  if(isset($query_params['question_id'])) {
    $question = PolyQuestionFactory::get($this->db, $query_params['question_id'], $_SESSION['dbext']);
    if($question) {
      if(isset($parsed_body['text'])) {
        $question->text = $parsed_body['text'];
      }
      if(isset($parsed_body['extra_credit'])) {
        $question->extra_credit = $parsed_body['extra_credit'];
      }
      if(isset($parsed_body['canvas'])) {
        $question->canvas = $parsed_body['canvas'];
      }
      $result = $question->save($this->db, $_SESSION['dbext']);
    }
  }

  return $response->withJson($result, $http_status);
});

$app->put('/questions/{question_id}', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;
  $query_params = $request->getQueryParams();
  $parsed_body = $request->getParsedBody();

  if($parsed_body['type'] == "STANDARD") {
    $question = PolyQuestionFactory::get($this->db, $args['question_id'], $_SESSION['dbext']);
    if($question) {
      if(isset($parsed_body['text'])) {
        $question->text = $parsed_body['text'];
      }
      if(isset($parsed_body['extra_credit'])) {
        $question->extra_credit = $parsed_body['extra_credit'];
      }
      if(isset($parsed_body['canvas'])) {
        $question->canvas = $parsed_body['canvas'];
      }
      $result = $question->save($this->db, $_SESSION['dbext']);
    }
  }

  return $response->withJson($result, $http_status);
});


$app->delete('/questions', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;
  $query_params = $request->getQueryParams();

  if(isset($query_params['question_id'])) {
    $result = PolyQuestionFactory::delete($this->db, $query_params['question_id'], $_SESSION['dbext']);
  }

  return $response->withJson($result, $http_status);
});

$app->delete('/questions/{question_id}', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;
  $query_params = $request->getQueryParams();
  $parsed_body = $request->getParsedBody();

  $result = PolyQuestionFactory::delete($this->db, $args['question_id'], $_SESSION['dbext']);

  return $response->withJson($result, $http_status);
});

