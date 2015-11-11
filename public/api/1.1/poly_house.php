<?php
class PolyHouse {
  public $house_id;
  public $house_name;
  public function __construct($house_id, $house_name){
    $this->house_id = $house_id;
    $this->house_name = $house_name;
  }
  public static function get($mysqli, $house_id, $owner){
    $toReturn = false;
    if($stmt = $mysqli->prepare("SELECT `house_id`, `house_name` FROM `house` WHERE `house_id` = ? AND `user_id` = ? LIMIT 1;")){
      $stmt->bind_param("ii", $house_id, $owner);
      $stmt->execute();
      $stmt->bind_result($house_id, $house_name);
      $stmt->store_result();
      while($stmt->fetch()){
        $toReturn = new self($house_id, $house_name);
      }
      $stmt->close();
    } else {
      die($mysqli->error);
    }
    if(!$toReturn){
      return false;
    } else {
      return $toReturn;
    } 
  }
  public static function getAll($mysqli, $owner) {
    $resultObject = array();
    if($stmt = $mysqli->prepare("SELECT `house_id`, `house_name` FROM `house` WHERE `user_id` = ?;")){
      $stmt->bind_param("i", $owner);
      $stmt->execute();
      $stmt->bind_result($house_id, $house_name);
      $stmt->store_result();
      while($stmt->fetch()){
        $resultObject[$house_id] = new self($house_id, $house_name);
      }
      $stmt->close();
    } else {
      die($mysqli->error);
    }
    return $resultObject;
  }
  public static function post($mysqli, $house_name, $owner) {
    $resultObject = array();
    $resultObject['status'] = false;
    if($stmt = $mysqli->prepare("INSERT INTO `house` (`house_name`, `user_id`) VALUES (?, ?);")) {
      $stmt->bind_param("si", $house_name, $owner);
      if($stmt->execute()) {
        $resultObject['status'] = true;
      } else {
        $resultObject['status_details'] = $stmt->errno;
      }
      $stmt->close();
      
    } else {
      $resultObject['error'] = $mysqli->error;
    }
    return $resultObject;
  }
  public static function put($mysqli, $house_id, $house_name, $owner) {
    $resultObject = array();
    if($stmt = $mysqli->prepare("UPDATE `house` SET `house_name` = ? WHERE `house`.`house_id` = ? AND `house`.`user_id` = ? LIMIT 1;")) {
      $stmt->bind_param("sii", $house_name, $house_id, $owner);
      $stmt->execute();
      $stmt->close();
      $resultObject['status'] = true;
    } else {
      $resultObject['error'] = $mysqli->error;
    }
    return $resultObject;
  }
  public static function delete($mysqli, $house_id, $owner) {
    $resultObject = array();
    if($stmt = $mysqli->prepare("DELETE FROM `house` WHERE `house`.`house_id` = ? AND `house`.`user_id` = ? LIMIT 1;")) {
      $stmt->bind_param("ii", $house_id, $owner);
      if($stmt->execute()) {
        if($stmt->affected_rows == 0) {
          $resultObject['status'] = false;
          $resultObject['status_details'] = 'Not Found';
        } else {
          $resultObject['status'] = true;
        }
      } else {
        $resultObject['status'] = false;
        $resultObject['status_details'] = $stmt->errno;
      }
      
      $stmt->close();
    } else {
      $resultObject['error'] = $mysqli->error;
    }
    return $resultObject;
  }
}
?>
