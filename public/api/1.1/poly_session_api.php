<?php

$app->get('/sessions', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;
  $query_params = $request->getQueryParams();
  $parsed_body = $request->getParsedBody();

  if(isset($query_params['id'])) {
    $result = PolySession::fromMySQL($this->db, $query_params['id'], $_SESSION['dbext']);
  } else {
    $result = PolySession::ownedBy($this->db, $_SESSION['dbext']);
  }

  return $response->withJson($result, $http_status);
});

$app->get('/sessions/{session_id}', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;

  $result = PolySession::fromMySQL($this->db, $args['session_id'], $_SESSION['dbext']);

  return $response->withJson($result, $http_status);
});

$app->post('/sessions', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;
  $parsed_body = $request->getParsedBody();

  $result['seg'] = $parsed_body;
  if($parsed_body) {
    if(isset($parsed_body['name']) && isset($parsed_body['quiz']) && isset($parsed_body['house']) && isset($parsed_body['active']) && isset($parsed_body['show'])) {
      $obj = PolySession::createCheckMysql($this->db);
      $obj->owner = $_SESSION['dbext'];
      
      $obj->name = $parsed_body['name'];
      $obj->quiz = $parsed_body['quiz'];
      $obj->house = $parsed_body['house'];

      if($parsed_body['active']) {
        $obj->setStatus(true);
      } else {
        $obj->setStatus(false);
      }

      if($parsed_body['show']){
        $obj->setShowScores(true);

      } else {
        $obj->setShowScores(false);
      }
      $subStatus = $obj->saveToMysql($this->db, $_SESSION['dbext']);
      if($subStatus['status']){
        $result['status'] = true;
      } else {
        $result['sub'] = $subStatus;
      }
    }
  }

  return $response->withJson($result, $http_status);
});

$app->put('/sessions', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;
  $query_params = $request->getQueryParams();
  $parsed_body = $request->getParsedBody();

  if(isset($query_params['id'])) {
    $obj = PolySession::fromMySQL($this->db, $query_params['id'], $_SESSION['dbext']);
    if(isset($parsed_body['name'])) {
      $obj->name = $parsed_body['name'];
    }
    if(isset($parsed_body['quiz'])) {
      $obj->quiz = $parsed_body['quiz'];
    }
    if(isset($parsed_body['house'])) {
      $obj->house = $parsed_body['house'];
    }
    if(isset($parsed_body['active'])) {
      $obj->setStatus($parsed_body['active']);
    }
    if(isset($parsed_body['show'])) {
      $obj->setShowScores($parsed_body['show']);
    }
    $result['sex'] = $obj->inDB;
    $subStatus = $obj->saveToMysql($this->db, $_SESSION['dbext']);
    $result['status'] = $subStatus['status'];
    if($subStatus['status']){
      $result['status'] = true;
    } else {
      $result['sub'] = $subStatus;
    }
    
  } else {
    
  }

  return $response->withJson($result, $http_status);
});

$app->put('/sessions/{session_id}', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;
  $query_params = $request->getQueryParams();
  $parsed_body = $request->getParsedBody();

  $obj = PolySession::fromMySQL($this->db, $args['session_id'], $_SESSION['dbext']);
  if(isset($parsed_body['name'])) {
    $obj->name = $parsed_body['name'];
  }
  if(isset($parsed_body['quiz'])) {
    $obj->quiz = $parsed_body['quiz'];
  }
  if(isset($parsed_body['house'])) {
    $obj->house = $parsed_body['house'];
  }
  if(isset($parsed_body['active'])) {
    $obj->setStatus($parsed_body['active']);
  }
  if(isset($parsed_body['show'])) {
    $obj->setShowScores($parsed_body['show']);
  }
  $subStatus = $obj->saveToMysql($this->db, $_SESSION['dbext']);
  if($subStatus['status']){
    $result['status'] = true;
  } else {
    $result['sub'] = $subStatus;
  }

  return $response->withJson($result, $http_status);
});

$app->delete('/sessions', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;
  $query_params = $request->getQueryParams();
  $parsed_body = $request->getParsedBody();

  if(isset($query_params['id'])) {
    $obj = PolySession::fromMySQL($this->db, $query_params['id'], $_SESSION['dbext']);
    
    $result['status'] = $obj->delete($this->db, $_SESSION['dbext']);

  } else {
    //DELETE ALL
  }

  return $response->withJson($result, $http_status);
});

$app->delete('/sessions/{session_id}', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;
  $query_params = $request->getQueryParams();
  $parsed_body = $request->getParsedBody();

  $obj = PolySession::fromMySQL($this->db, $args['session_id'], $_SESSION['dbext']);
    
  $result['status'] = $obj->delete($this->db, $_SESSION['dbext']);

  return $response->withJson($result, $http_status);
});

