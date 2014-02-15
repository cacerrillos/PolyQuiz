<?
include_once("config.func.php");
class PolySession {
	public $sessionid, $recoveryid, $recoverykey;
	function __construct($id){
		$this->sessionid = $id;
		$this->genIdKey();
	}
	function push(){
		global $db_host, $db_user, $db_password, $db_name;
		$mysqli = new mysqli($db_host, $db_user, $db_password);
		$mysqli -> select_db($db_name);
		if(mysqli_connect_errno()) {
			echo "Connection Failed: " . mysqli_connect_errno();
			exit();
		}
		if($stmt = $mysqli -> prepare("SELECT * FROM polysessions WHERE id = ?")){
				$stmt -> bind_param("s", $this->sessionid);
				$stmt -> execute();
				$stmt -> store_result();
				$num = $stmt -> num_rows;
				$stmt -> close();
		} else {
			echo $mysqli->error;
		}
		if($num==0){
			if($stmt = $mysqli -> prepare("INSERT INTO polysessions (id,recoveryid,recoverykey,data,date) VALUES (?,?,?,?,?)")){
				$stmt -> bind_param("sssss", $this->sessionid, $this->recoveryid, $this->recoverykey, serialize($_SESSION), time());
				$stmt -> execute();
				$stmt -> close();
			} else {
				echo $mysqli->error;
			}
		} else {
			if($stmt = $mysqli -> prepare("UPDATE polysessions SET data=?, date=? WHERE id=?")){
				$stmt -> bind_param("sss", serialize($_SESSION), time(), $this->sessionid);
				$stmt -> execute();
				$stmt -> close();
			} else {
				echo $mysqli->error;
			}
		}
	}
	function remove(){
		global $db_host, $db_user, $db_password, $db_name;
		$mysqli = new mysqli($db_host, $db_user, $db_password);
		$mysqli -> select_db($db_name);
		if(mysqli_connect_errno()) {
			echo "Connection Failed: " . mysqli_connect_errno();
			exit();
		}
		if($stmt = $mysqli -> prepare("DELETE FROM polysessions WHERE id = ?")){
			$stmt -> bind_param("s", $this->sessionid);
			$stmt -> execute();
			$stmt -> close();
		} else {
			echo $mysqli->error;
		}
	}
	function genIdKey(){
		$temp = md5($this->sessionid);
		$this->recoveryid = substr($temp, 4, 6);
		$this->recoverykey = substr($temp, 12, 2);
	}
}

class PolySessionRestore {
	public $sessionid, $recoveryid, $recoverykey;
	function __construct($recid, $reckey){
		$this->recoveryid = $recid;
		$this->recoverykey = $reckey;
	}
	function pull(){
		global $db_host, $db_user, $db_password, $db_name;
		$mysqli = new mysqli($db_host, $db_user, $db_password);
		$mysqli -> select_db($db_name);
		if(mysqli_connect_errno()) {
			echo "Connection Failed: " . mysqli_connect_errno();
			exit();
		}
		if($stmt = $mysqli -> prepare("SELECT * FROM polysessions WHERE recoveryid = ? AND recoverykey = ?")){
				$stmt -> bind_param("ss", $this->recoveryid, $this->recoverykey);
				$stmt -> execute();
				$stmt -> store_result();
				$num = $stmt -> num_rows;
				$stmt -> close();
		} else {
			echo $mysqli->error;
		}
		if($num==0){
			return false;
		} else {
			if($stmt = $mysqli -> prepare("SELECT * FROM polysessions WHERE recoveryid = ? AND recoverykey = ?")){
					$stmt -> bind_param("ss", $this->recoveryid, $this->recoverykey);
					$stmt -> execute();
					$stmt -> bind_result($resultq['id'], $resultq['recoveryid'], $resultq['recoverykey'], $resultq['data'], $resultq['date']);
					$stmt -> store_result();
					while($stmt -> fetch()){
						if(strlen($this->sessionid)<0){
							$this->sessionid = $resultq['id'];
						}
						$_SESSION = unserialize($resultq['data']);
						$this->remove();
					}
					$stmt -> close();
			} else {
				echo $mysqli->error;
			}
		}
	}
	function remove(){
		global $db_host, $db_user, $db_password, $db_name;
		$mysqli = new mysqli($db_host, $db_user, $db_password);
		$mysqli -> select_db($db_name);
		if(mysqli_connect_errno()) {
			echo "Connection Failed: " . mysqli_connect_errno();
			exit();
		}
		if($stmt = $mysqli -> prepare("DELETE FROM polysessions WHERE id = ?")){
			$stmt -> bind_param("s", $this->sessionid);
			$stmt -> execute();
			$stmt -> close();
		} else {
			echo $mysqli->error;
		}
	}
}
?>