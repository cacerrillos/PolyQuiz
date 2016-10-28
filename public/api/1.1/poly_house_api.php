<?
$app->get('/houses', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;
  $query_params = $request->getQueryParams();

  if(isset($query_params['house_id'])) {
    $result = PolyHouse::get($this->db, $query_params['house_id'], $_SESSION['dbext']);
  } else {
    $result = PolyHouse::getAll($this->db, $_SESSION['dbext']);
  }

  return $response->withJson($result, $http_status);
});

$app->get('/houses/{house_id}', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;

  $result = PolyHouse::get($this->db, $args['house_id'], $_SESSION['dbext']);

  return $response->withJson($result, $http_status);
});

$app->post('/houses', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;
  $parsed_body = $request->getParsedBody();

  if(isset($parsed_body['house_name'])) {
    $result = PolyHouse::post($this->db, $parsed_body['house_name'], $_SESSION['dbext']);
  } else {
    $error = array();
    $error['code'] = 50;
    $error['message'] = "Invalid Arguments";
    $error['errors'] = array();
    $error['errors'][0]['code'] = 10;
    $error['errors'][0]['field'] = 'house_name';
    $error['errors'][0]['message'] = 'Body should contain the field name!';
    $result = $error;
  }
  

  return $response->withJson($result, $http_status);
});

$app->put('/houses/{house_id}', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;
  $parsed_body = $request->getParsedBody();

  if($parsed_body && isset($parsed_body['house_name'])) {
    $result = PolyHouse::put($this->db, $args['house_id'], $parsed_body['house_name'], $_SESSION['dbext']);
  }

  return $response->withJson($result, $http_status);
});

$app->put('/houses', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;
  $query_params = $request->getQueryParams();
  $parsed_body = $request->getParsedBody();

  if(isset($query_params['house_id'])) {
    if($parsed_body && isset($parsed_body['house_name'])) {
      $result = PolyHouse::put($this->db, $query_params['house_id'], $parsed_body['house_name'], $_SESSION['dbext']);
    }
  }

  return $response->withJson($result, $http_status);
});

$app->delete('/houses/{house_id}', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;

  if($this->is_admin) {
    $result = PolyHouse::delete($this->db, $args['house_id'], $_SESSION['dbext']);
  }

  return $response->withJson($result, $http_status);
});

$app->delete('/houses', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;
  $query_params = $request->getQueryParams();
  
  if(isset($query_params['house_id'])) {
    if($this->is_admin) {
      $result = PolyHouse::delete($mysqli, $query_params['house_id'], $_SESSION['dbext']);
    }
  }

  return $response->withJson($result, $http_status);
});

