<?
session_start();

include_once("config.func.php");
if(isset($_POST['username'])){
	if(strlen($_POST['username'])>0 && strlen($_POST['password'])>0){
		$mysqli = new mysqli($db_host, $db_user, $db_password);
		$mysqli -> select_db($db_name);
		if(mysqli_connect_errno()) {
			echo "Connection Failed: " . mysqli_connect_errno();
			exit();
		}
		if($stmt = $mysqli -> prepare("SELECT * FROM users WHERE username = ?")){
			$stmt -> bind_param("s", $_POST['username']);
			$stmt -> execute();
			$stmt -> store_result();
			$num = $stmt -> num_rows;
			$stmt -> close();
			if($num==0){
				$permsid = md5($_POST['username'].md5($_POST['password']));
				if($stmt = $mysqli -> prepare("INSERT INTO stats VALUES (?, ?);")){
					$zero = "0";
					$ext = "_".$permsid;
					$stmt -> bind_param("ss", $ext, $zero);
					$stmt -> execute();
					$stmt -> close();
				} else {
					error_log($mysqli->error);
				}
				if($stmt = $mysqli -> prepare("INSERT INTO users (id,username,password,permissionsid) VALUES ('',?,?,?)")){
					$stmt -> bind_param("sss", $_POST['username'], md5($_POST['password']), $permsid);
					$stmt -> execute();
					$stmt -> close();
					if(mysqli_query($mysqli, "CREATE DATABASE IF NOT EXISTS `_".$permsid."`;")){
						mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
						mysql_select_db("_".$permsid) or die(mysql_error());
						$qu = mysql_query(
							"CREATE TABLE `images` (
							  `uuid` varchar(65) NOT NULL,
							  `url` text,
							  PRIMARY KEY (`uuid`)
							) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
						$qu = mysql_query(
							"CREATE TABLE `quizes` (
							  `uuid` varchar(255) NOT NULL DEFAULT '',
							  `quizname` text NOT NULL,
							  `quizsubject` text NOT NULL,
							  `status` int(11) DEFAULT '1',
							  PRIMARY KEY (`uuid`)
							) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
						$qu = mysql_query(
							"CREATE TABLE `results` (
							  `id` int(11) NOT NULL AUTO_INCREMENT,
							  `firstname` text,
							  `lastname` text,
							  `quizuuid` text,
							  `rawscore` text,
							  `possiblescore` text,
							  `percentage` text,
							  `datestamp` text,
							  `timestamp` text,
							  `ip` text,
							  `house` text,
							  `session` text,
							  `object` text,
							  `flag` varchar(11) DEFAULT 'no',
							  `frscore` int(11) DEFAULT '0',
							  `frpossible` int(11) DEFAULT '0',
							  `frgraded` int(11) DEFAULT '1',
							  PRIMARY KEY (`id`)
							) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
						$qu = mysql_query(
							"CREATE TABLE `sessions` (
							  `uuid` varchar(65) NOT NULL,
							  `key` text,
							  `house` text,
							  `status` int(11) DEFAULT NULL,
							  `quiz` text,
							  `date` text,
							  `sessionname` text,
							  PRIMARY KEY (`uuid`)
							) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
					} else {
						die(mysqli_error($mysqli));
					}
					
					header('Location: ../?p=admin');
					exit();
				} else {
					echo $mysqli->error;
				}
			} else {
				//return with error
				header('Location: ../?p=register&error=1');
				exit();
			}
		} else {
			echo $mysqli->error;
		}
	}
}

header('Location: ../?p=register');
?>