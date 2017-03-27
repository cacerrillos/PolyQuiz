<?php

function get_quiz($mysqli, $quiz_id, $user_id, $all_data) {
  return PolyQuiz::from_mysql($mysqli, $quiz_id, $user_id, $all_data);
}

$app->get('/quizzes', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;

  $query_params = $request->getQueryParams();

  if(isset($query_params['quiz_id'])) {
    $result = get_quiz($this->db, $query_params['quiz_id'], $_SESSION['dbext'], isset($query_params['all_data']));
  } else {
    $result = PolyQuiz::all_from_mysql($this->db, $_SESSION['dbext']);
  }

  return $response->withJson($result, $http_status);
});


$app->get('/quizzes/{quiz_id}', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;
  $query_params = $request->getQueryParams();

  $result = get_quiz($this->db, $args['quiz_id'], $_SESSION['dbext'], isset($query_params['all_data']));

  return $response->withJson($result, $http_status);
});

$app->get('/quizzes/{quiz_id}/questions', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;

  $result = PolyQuestionFactory::get_by_quiz($this->db, intval($args['quiz_id']), $_SESSION['dbext']);

  return $response->withJson($result, $http_status);
});


$app->post('/quizzes', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;
  $query_params = $request->getQueryParams();
  $parsed_body = $request->getParsedBody();

  if($this->is_admin) {
    if(isset($query_params['quiz_name'])) {
      $result = PolyQuiz::create($this->db, $query_params['quiz_name'], $_SESSION['dbext']);
    } else if(isset($parsed_body['quiz_name'])) {
      $result = PolyQuiz::create($this->db, $parsed_body['quiz_name'], $_SESSION['dbext']);
    }
  }

  return $response->withJson($result, $http_status);
});

$app->put('/quizzes', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;
  $query_params = $request->getQueryParams();
  $parsed_body = $request->getParsedBody();

  if($this->is_admin) {
    if(isset($query_params['quiz_id'])) {
      if(isset($parsed_body['quiz_name'])) {
        $quiz = PolyQuiz::from_mysql($this->db, $query_params['quiz_id'], $_SESSION['dbext'], false);
        if($quiz['status']) {
          $quiz['result']->quiz_name = $parsed_body['quiz_name'];
          $result = $quiz['result']->save($this->db, $_SESSION['dbext']);
        }
      }
    }
  }  

  return $response->withJson($result, $http_status);
});

$app->delete('/quizzes/{quiz_id}', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;

  if($this->is_admin) {
    $status = PolyQuiz::delete($this->db, $args['quiz_id'], $_SESSION['dbext']);
  }

  return $response->withJson($result, $http_status);
});

$app->delete('/quizzes', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;

  $query_params = $request->getQueryParams();
  if(isset($query_params['quiz_id'])) {
    if($this->is_admin) {
      $result = PolyQuiz::delete($this->db, $query_params['quiz_id'], $_SESSION['dbext']);
    }
  }
  
  return $response->withJson($result, $http_status);
});
