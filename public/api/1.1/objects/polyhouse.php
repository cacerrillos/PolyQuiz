<?
class PolyHouse {
	public $version = 0;
	public $id;
	public $name;
	public function __construct($houseid, $housename){
		$this->id = $houseid;
		$this->name = $housename;
	}
	public static function get($mysqli, $houseid, $owner){
		$toReturn = false;
		if($stmt = $mysqli->prepare("SELECT `house_id`, `house_name` FROM `house` WHERE `house_id` = ? AND `user_id` = ? LIMIT 1;")){
			$stmt->bind_param("ii", $houseid, $owner);
			$stmt->execute();
			$stmt->bind_result($houseid, $housename);
			$stmt->store_result();
			while($stmt->fetch()){
				$toReturn = new self($houseid, $housename);
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
			$stmt->bind_result($houseid, $housename);
			$stmt->store_result();
			while($stmt->fetch()){
				$resultObject[$houseid] = new self($houseid, $housename);
			}
			$stmt->close();
		} else {
			die($mysqli->error);
		}
		return $resultObject;
	}
	public static function post($mysqli, $housename, $owner) {
		$resultObject = array();
		$resultObject['status'] = false;
		if($stmt = $mysqli->prepare("INSERT INTO `house` (`house_name`, `user_id`) VALUES (?, ?);")) {
			$stmt->bind_param("si", $housename, $owner);
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
	public static function put($mysqli, $houseid, $housename, $owner) {
		$resultObject = array();
		if($stmt = $mysqli->prepare("UPDATE `house` SET `house_name` = ? WHERE `house`.`house_id` = ? AND `house`.`user_id` = ? LIMIT 1;")) {
			$stmt->bind_param("sii", $housename, $houseid, $owner);
			$stmt->execute();
			$stmt->close();
			$resultObject['status'] = true;
		} else {
			$resultObject['error'] = $mysqli->error;
		}
		return $resultObject;
	}
	public static function delete($mysqli, $houseid, $owner) {
		$resultObject = array();
		if($stmt = $mysqli->prepare("DELETE FROM `house` WHERE `house`.`house_id` = ? AND `house`.`user_id` = ? LIMIT 1;")) {
			$stmt->bind_param("ii", $houseid, $owner);
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