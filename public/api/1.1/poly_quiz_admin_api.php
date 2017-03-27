<?

$app->get('/status', function($request, $response, $args) {
  $result = array();
  $http_status =  200;

  if(isset($_SESSION["is_admin"])) {
    $result['admin'] = true;
  } else {
    $result['admin'] = false;
  }

  return $response->withJson($result, $http_status);
});

$app->post('/login', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;
  $parsed_body = $request->getParsedBody();

  session_destroy();
  session_start();

  $result['user'] = $parsed_body['user'];

  if(count($parsed_body) > 0){
    if(mysqli_connect_errno()) {
      $result['status_details'] = "Connection Failed: " . mysqli_connect_errno();
    } else {
      if($stmt = $this->db -> prepare("SELECT `user_id`, `email`, `name` FROM `user` WHERE email = ? AND password = ?")){
        $stmt -> bind_param("ss", $parsed_body['user'], md5($parsed_body['pass']));
        $stmt -> execute();
        $stmt -> bind_result($data['id'], $data['email'], $data['name']);
        $stmt -> store_result();
        $stmt -> fetch();
        $num = $stmt -> num_rows;
        $stmt -> close();
      } else {
        $result['status_details'] = $this->db->error;
      }
      if($num==1){
        $_SESSION['is_admin'] = "set";
        $_SESSION['email'] = $data['email'];
        $_SESSION['name'] = $data['name'];
        $_SESSION['dbext'] = $data['id'];
        $_SESSION['admin_id_num'] = $data['id'];
        $_SESSION['permsid'] = $data['permsid'];
        $result['status'] = true;
      }
    }
  }

  return $response->withJson($result, $http_status);
});

$app->post('/logout', function($request, $response, $args) {
  $result = array("status" => false);
  $http_status =  200;
  $result['status'] = session_destroy();
  

  return $response->withJson($result, $http_status);
});

