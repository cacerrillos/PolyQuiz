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
				$thisThang->data = json_decode($data);
				$toReturn = $thisThang;
			}
			$stmt->close();
		}
		return $toReturn;
	}
}
?>