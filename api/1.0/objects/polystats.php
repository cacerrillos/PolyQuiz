<?
class PolyStats {
	public $version = 0;
	public $data = array();
	public function __construct($quizUUID, $houseid, $count){
		$this->data['quizUUID'] = intval($quizUUID);
		$this->data['houseData'] = intval($houseid);
		$this->data['count'] = intval($count);
	}
	public static function getStats($mysqli, $quiz, $owner, $all = false){
		$resultObject = array();
		if($stmt = $mysqli->prepare("SELECT COUNT(`id`) FROM `results` WHERE `quiz` = ? AND `owner` = ? LIMIT 1;")){
			$stmt->bind_param("ii", $quiz, $owner);
			$stmt->execute();
			$stmt->bind_result($count);
			while($stmt->fetch()){
				array_push($resultObject, new self($quiz, -1, $count));
			}
			$stmt->close();
		} else {
			die($mysqli->error);
		}
		$houses = PolyHouse::getHouses($mysqli, $owner);
		for($x = 0; $x < count($houses); $x++){
			if($stmt = $mysqli->prepare("SELECT COUNT(`id`) FROM `results` WHERE `house` = ? AND `quiz` = ? AND `owner` = ? LIMIT 1;")){
				$stmt->bind_param("iii", $houses[$x]->data['houseId'], $quiz, $owner);
				$stmt->execute();
				$stmt->bind_result($count);
				while($stmt->fetch()){
					if($all || $count > 0){
						array_push($resultObject, new self($quiz, $houses[$x]->data['houseId'], $count));
					}
				}
				$stmt->close();
			} else {
				die($mysqli->error);
			}
		}
		for($x = 0; $x < count($resultObject); $x++){
			if($resultObject[$x]->data['houseData'] != -1){
				$resultObject[$x]->data['houseData'] = PolyHouse::getHouse($mysqli, $resultObject[$x]->data['houseData'], $owner);
			}
		}
		return $resultObject;
	}
}
?>