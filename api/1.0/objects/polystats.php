<?
class PolyStats {
	public $version = 0;
	public $data = array();
	public function __construct($quizUUID, $sessionid, $count){
		$this->data['quizUUID'] = intval($quizUUID);
		$this->data['sessionData'] = $sessionid;
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
		$sessions = PolySession::ownedBy($mysqli, $owner);
		for($x = 0; $x < count($sessions); $x++){
			if($stmt = $mysqli->prepare("SELECT COUNT(`id`) FROM `results` WHERE `session` = ? AND `quiz` = ? AND `owner` = ? LIMIT 1;")){
				$stmt->bind_param("sii", $sessions[$x]->sessionid, $quiz, $owner);
				$stmt->execute();
				$stmt->bind_result($count);
				while($stmt->fetch()){
					if($all || $count > 0){
						array_push($resultObject, new self($quiz, $sessions[$x]->sessionid, $count));
					}
				}
				$stmt->close();
			} else {
				die($mysqli->error);
			}
		}
		for($x = 0; $x < count($resultObject); $x++){
			if($resultObject[$x]->data['sessionData'] != -1){
				$resultObject[$x]->data['sessionData'] = PolySession::fromMySQL($mysqli, $resultObject[$x]->data['sessionData'], $owner);
			}
		}
		return $resultObject;
	}
}
?>