<?
class PolyHouse {
	public $version = 0;
	public $data = array();
	public function __construct($houseid, $housename){
		$this->data['houseId'] = $houseid;
		$this->data['houseName'] = $housename;
	}
	public static function getHouse($mysqli, $houseid, $owner){
		$toReturn = false;
		if($stmt = $mysqli->prepare("SELECT `houseid`, `housename` FROM `houses` WHERE `houseid` = ? AND `owner` = ?;")){
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
	public static function getHouses($mysqli, $owner) {
		$resultObject = array();
		if($stmt = $mysqli->prepare("SELECT `houseid`, `housename` FROM `houses` WHERE `owner` = ?;")){
			$stmt->bind_param("i", $owner);
			$stmt->execute();
			$stmt->bind_result($houseid, $housename);
			$stmt->store_result();
			while($stmt->fetch()){
				array_push($resultObject, new self($houseid, $housename));
			}
			$stmt->close();
		} else {
			die($mysqli->error);
		}
		return $resultObject;
	}
}
?>