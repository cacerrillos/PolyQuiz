<?
class PolySession {
	public $sessionid;
	public $sessionkey;
	public $name;
	public $owner;
	public $quiz;
	public $date;
	public $data = array();
	public function __construct(){
		$this->name = "DEFAULT NAME";
		$this->quiz = 1;
		$this->data['status'] = true;
		$this->data['show'] = true;
		$this->data['house'] = 0;
		$date = new DateTime();
		$this->date = $date->getTimestamp();
	}
	
	public static function createCheckMysql($mysqli){
		$toReturn = new self();
		$tempId = substr(uniqid(), 6, 6);
		$valid = false;
		while(!$valid){
			if($stmt = $mysqli->prepare(
				"SELECT `sessionid` FROM `sessions` WHERE `sessionid`=? LIMIT 1;"
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
		if($stmt = $mysqli->prepare("SELECT `sessionid`, `sessionkey`, `quiz`, `owner`, `name`, `data`, `date` FROM `sessions` WHERE `owner`=?;")){
			$stmt->bind_param("i", $owner);
			$stmt->execute();
			$stmt->bind_result($sessionid, $sessionkey, $quiz, $owner, $name, $data, $date);
			$stmt->execute();
			while($stmt->fetch()){
				$thisThang = new self();
				$thisThang->sessionid = $sessionid;
				$thisThang->sessionkey = $sessionkey;
				$thisThang->owner = $owner;
				$thisThang->name = $name;
				$thisThang->quiz = $quiz;
				$thisThang->date = $date;
				$thisThang->data = json_decode($data);
				
				array_push($toReturn, $thisThang);
			}
			$stmt->close();
		}
		return $toReturn;
	}
	public function delete($mysqli, $owner){
		$status = false;
		if($owner == $this->owner){
			if($stmt = $mysqli->prepare("DELETE FROM `sessions` WHERE `owner` = ? AND `sessionid` = ?;")){
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
			$this->data['status'] = true;
		} else {
			$this->data['status'] = false;
		}
		$status = true;
		return $status;
	}
	public function setShowScores($setTo){
		$status = false;
		if($setTo){
			$this->data['show'] = true;
		} else {
			$this->data['show'] = false;
		}
		$status = true;
		return $status;
	}
	public function saveToMysql($mysqli, $owner){
		$toReturn = false;
		if($this->owner == $owner){
			if($stmt = $mysqli->prepare("INSERT INTO `sessions` (`sessionid`, `sessionkey`, `quiz`, `owner`, `name`, `data`, `date`) VALUES (?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE `name`=?, `data`=?;")){
				$stmt->bind_param("ssiississ", $this->sessionid, $this->sessionkey, $this->quiz, $this->owner, $this->name, json_encode($this->data), $this->date, $this->name, json_encode($this->data));
				$stmt->execute();
				$stmt->close();
				$toReturn = true;
			} else {
				$toReturn = $mysqli->error;
			}
		}
		return $toReturn;
	}
	public static function fromMySQL($mysqli, $uuid, $owner){
		$toReturn = false;
		if($stmt = $mysqli->prepare("SELECT `sessionid`, `sessionkey`, `quiz`, `owner`, `name`, `data`, `date` FROM `sessions` WHERE `owner`=? AND `sessionid`=?;")){
			$stmt->bind_param("is", $owner, $uuid);
			$stmt->execute();
			$stmt->bind_result($sessionid, $sessionkey, $quiz, $owner, $name, $data, $date);
			$stmt->execute();
			while($stmt->fetch()){
				$thisThang = new self();
				$thisThang->sessionid = $sessionid;
				$thisThang->sessionkey = $sessionkey;
				$thisThang->owner = $owner;
				$thisThang->name = $name;
				$thisThang->quiz = $quiz;
				$thisThang->date = $date;
				$thisThang->data = (array)json_decode($data);
				$toReturn = $thisThang;
			}
			$stmt->close();
		}
		return $toReturn;
	}
}
?>