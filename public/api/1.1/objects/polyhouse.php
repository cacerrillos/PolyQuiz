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
		if($stmt = $mysqli->prepare("SELECT `houseid`, `housename` FROM `houses` WHERE `houseid` = ? AND `owner` = ? LIMIT 1;")){
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
		if($stmt = $mysqli->prepare("SELECT `houseid`, `housename` FROM `houses` WHERE `owner` = ?;")){
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
		if($stmt = $mysqli->prepare("INSERT INTO `houses` (`housename`, `owner`) VALUES (?, ?);")) {
			$stmt->bind_param("si", $housename, $owner);
			$stmt->execute();
			$stmt->close();
			$resultObject['status'] = true;
		} else {
			$resultObject['error'] = $mysqli->error;
		}
		return $resultObject;
	}
	public static function put($mysqli, $houseid, $housename, $owner) {
		$resultObject = array();
		if($stmt = $mysqli->prepare("UPDATE `houses` SET `housename` = ? WHERE `houses`.`houseid` = ? AND `houses`.`owner` = ? LIMIT 1;")) {
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
		if($stmt = $mysqli->prepare("DELETE FROM `houses` WHERE `houses`.`houseid` = ? AND `houses`.`owner` = ? LIMIT 1;")) {
			$stmt->bind_param("ii", $houseid, $owner);
			$stmt->execute();
			$stmt->close();
			$resultObject['status'] = true;
		} else {
			$resultObject['error'] = $mysqli->error;
		}
		return $resultObject;
	}
}
?>