<?
class PolySession {
	public $sessionid;
	public $sessionkey;
	public $name;
	public $owner;
	public $quiz;
	public $house;
	public $date;
	public $active;
	public $show_scores;
	public $inDB = false;
	public function __construct(){
		$this->name = "DEFAULT NAME";
		$this->quiz = 1;
		$this->active = true;
		$this->show_scores = true;
		$this->house = 0;
		$date = new DateTime();
		$this->date = $date->getTimestamp();
	}
	
	public static function createCheckMysql($mysqli){
		$toReturn = new self();
		$tempId = substr(uniqid(), 6, 6);
		$valid = false;
		while(!$valid){
			if($stmt = $mysqli->prepare(
				"SELECT `session_id` FROM `session` WHERE `session_id`=? LIMIT 1;"
			)){
				$stmt->bind_param("s", $tempId);
				$stmt->execute();
				$stmt->store_result();
				if($stmt->num_rows == 0){
					$valid = true;
				} else {
					$tempId = substr(uniqid(), 6, 6);
				}
				$stmt->close();
			} else {
				die($mysqli->error);
			}
		}
		if($valid){
			$toReturn->sessionid = $tempId;
			$toReturn->sessionkey = substr(uniqid(), 11, 2);
		}
		return $toReturn;
	}
	public static function ownedBy($mysqli, $owner){
		$toReturn = array();
		if($stmt = $mysqli->prepare("SELECT `session_id`, `session_key`, `quiz_id`, `user_id`, `session_name`, `session_date`, `house_id`, `active`, `show_scores` FROM `session` WHERE `user_id`=?;")){
			$stmt->bind_param("i", $owner);
			$stmt->execute();
			$stmt->bind_result($sessionid, $sessionkey, $quiz, $owner, $name, $date, $house, $active, $show_scores);
			$stmt->execute();
			while($stmt->fetch()){
				$thisThang = new self();
				$thisThang->sessionid = $sessionid;
				$thisThang->sessionkey = $sessionkey;
				$thisThang->owner = $owner;
				$thisThang->name = $name;
				$thisThang->quiz = $quiz;
				$thisThang->house = $house;
				$thisThang->date = $date;
				$thisThang->active = $active ? true : false;
				$thisThang->show_scores = $show_scores ? true : false;
				
				array_push($toReturn, $thisThang);
			}
			$stmt->close();
		}
		return $toReturn;
	}
	public function delete($mysqli, $owner){
		$status = false;
		if($owner == $this->owner){
			if($stmt = $mysqli->prepare("DELETE FROM `session` WHERE `user_id` = ? AND `session_id` = ?;")){
				$stmt->bind_param("is", $owner, $this->sessionid);
				if($stmt->execute()){
					if($stmt->affected_rows == 1){
						$status = true;
					}
				} else {
					$status = $mysqli->errno;
				}
				$stmt->close();
			} else {
				echo $mysqli->error;
			}
		}
		return $status;
	}
	public function setStatus($setTo){
		$status = false;
		if($setTo){
			$this->active = true;
		} else {
			$this->active = false;
		}
		$status = true;
		return $status;
	}
	public function setShowScores($setTo){
		$status = false;
		if($setTo){
			$this->show_scores = true;
		} else {
			$this->show_scores = false;
		}
		$status = true;
		return $status;
	}
	public function saveToMysql($mysqli, $owner){
		$toReturn = array();
		$toReturn['status'] = false;
		if($this->owner == $owner){
			$active_int = intval($this->active);
			$show_scores_int = intval($this->show_scores);
			

			if($this->inDB) {
				if($stmt = $mysqli->prepare("UPDATE `session` SET `session_name`=?, `active`=?, `show_scores`=? WHERE `session_id` = ? AND `user_id` = ? LIMIT 1;")){

					$stmt->bind_param("siisi",  $this->name, $active_int, $show_scores_int, $this->sessionid, $owner);
					if($stmt->execute()) {
						$toReturn['status'] = true;
					} else {
						$toReturn['status_details'] = $stmt->errno;
						$toReturn['status_details_message'] = $stmt->error;
					}
					$stmt->close();
				} else {
					$toReturn['status_details'] = $mysqli->error;
				}
			} else {
				if($stmt = $mysqli->prepare("INSERT INTO `session` (`session_id`, `session_key`, `quiz_id`, `user_id`, `session_name`, `active`, `show_scores`, `session_date`, `house_id`) VALUES (?,?,?,?,?,?,?,?,?);")){
					$stmt->bind_param("ssiisiiii", $this->sessionid, $this->sessionkey, $this->quiz, $this->owner, $this->name, $active_int, $show_scores_int, $this->date, $this->house);
					if($stmt->execute()) {
						$toReturn['status'] = true;
						$inDB = true;
					} else {
						$toReturn['status_details'] = $stmt->errno;
						$toReturn['status_details_message'] = $stmt->error;
					}
					$stmt->close();
				} else {
					$toReturn['status_details'] = $mysqli->error;
				}
			}
			
		}
		return $toReturn;
	}
	public static function fromMySQL($mysqli, $uuid, $owner){
		$toReturn = false;
		if($stmt = $mysqli->prepare("SELECT `session_id`, `session_key`, `quiz_id`, `user_id`, `session_name`, `session_date`, `house_id`, `active`, `show_scores` FROM `session` WHERE `user_id`=? AND `session_id`=?;")){
			$stmt->bind_param("is", $owner, $uuid);
			$stmt->execute();
			$stmt->bind_result($sessionid, $sessionkey, $quiz, $owner, $name, $date, $house, $active, $show_scores);
			$stmt->execute();
			while($stmt->fetch()){
				$thisThang = new self();
				$thisThang->inDB = true;
				$thisThang->sessionid = $sessionid;
				$thisThang->sessionkey = $sessionkey;
				$thisThang->owner = $owner;
				$thisThang->name = $name;
				$thisThang->quiz = $quiz;
				$thisThang->house = $house;
				$thisThang->date = $date;
				$thisThang->active = $active ? true : false;
				$thisThang->show_scores = $show_scores ? true : false;
				$toReturn = $thisThang;
			}
			$stmt->close();
		}
		return $toReturn;
	}
}
?>