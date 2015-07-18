<?
session_start();
include_once("../../func/config.func.php");
$debug = false;
if(isset($_SESSION["is_admin"])){
	$mysqli = new mysqli($db_host, $db_user, $db_password);
	$mysqli -> select_db($db_name);
	$resultObject = array();
	if($stmt = $mysqli->prepare(
		"SELECT `quizzes`.`uuid`, `quizzes`.`quizname`, `results`.`id`, `results`.`house`, `results`.`lastname`, `results`.`firstname`, `results`.`rawscore`, `results`.`possiblescore`, `results`.`timestamp`, `results`.`ip`, `results`.`session`, `results`.`flag`, `results`.`frscore`, `results`.`frpossible`, `results`.`frgraded` FROM `quizzes` INNER JOIN `results` ON `quizzes`.`uuid` = `results`.`quizuuid` WHERE (`quizzes`.`owner` = ? AND `results`.`owner` = ?) ORDER BY `quizzes`.`quizname` ASC, `results`.`house` ASC, `results`.`lastname` ASC;"
	)){
		$stmt->bind_param("ss", $_SESSION['dbext'], $_SESSION['dbext']);
		$stmt->execute();
		$stmt->bind_result($quizuuid, $quizname, $resultid, $resulthouse, $lastname, $firstname, $rawscore, $possiblescore, $timestamp, $ip, $session, $flag, $frscore, $frpossible, $frgraded);
		$stmt->store_result();
		while($stmt->fetch()){
			$resultObject[$quizuuid]['name'] = $quizname;
			$resultObject[$quizuuid]['results'][$resulthouse][$resultid]['uuid'] = $resultid;
			$resultObject[$quizuuid]['results'][$resulthouse][$resultid]['firstname'] = $firstname;
			$resultObject[$quizuuid]['results'][$resulthouse][$resultid]['lastname'] = $lastname;
			$resultObject[$quizuuid]['results'][$resulthouse][$resultid]['rawscore'] = $rawscore;
			$resultObject[$quizuuid]['results'][$resulthouse][$resultid]['possiblescore'] = $possiblescore;
			$resultObject[$quizuuid]['results'][$resulthouse][$resultid]['timestamp'] = date("Y-M-d-D H:i", $timestamp);//$timestamp;
			$resultObject[$quizuuid]['results'][$resulthouse][$resultid]['ip'] = $ip;
			$resultObject[$quizuuid]['results'][$resulthouse][$resultid]['session'] = $session;
			$resultObject[$quizuuid]['results'][$resulthouse][$resultid]['flag'] = $flag;
			$resultObject[$quizuuid]['results'][$resulthouse][$resultid]['frscore'] = $frscore;
			$resultObject[$quizuuid]['results'][$resulthouse][$resultid]['frpossible'] = $frpossible;
			$resultObject[$quizuuid]['results'][$resulthouse][$resultid]['frgraded'] = $frgraded;
		}
		$stmt->close();
	} else {
		echo $mysqli->error;
	}
	if($debug) {
		echo "<pre>".json_encode($resultObject, JSON_PRETTY_PRINT)."</pre>";
	} else {
		echo json_encode($resultObject);
	}
	exit();
	if(!$sortbysession){
		$quizUUIDS = array();
		$house[0] = "North";
		$house[1] = "South";
		$house[2] = "East";
		$house[3] = "West";
		$house[4] = "Other";
		if($stmt = $mysqli->prepare("SELECT `results`.`id`, `results`.`firstname`, `results`.`lastname`, `results`.`rawscore`, `results`.`possiblescore`, `results`.`percentage`, `results`.`frscore`, `results`.`frpossible`, `results`.`frgraded`, `results`.`timestamp`
							FROM `results` WHERE quizuuid=? AND owner=? ORDER BY lastname ASC;")) {
			
		}
		if($stmt = $mysqli->prepare("SELECT quizuuid FROM `results` WHERE owner = ?;")) {
			$stmt->bind_param("s", $_SESSION['dbext']);
			$stmt->execute();
			$stmt->bind_result($thisQuizUUID);
			$stmt->store_result();
			while($stmt->fetch()) {
				array_push($quizUUIDS, $thisQuizUUID);
			}
			$uniqueQuizUUIDs = array_unique($quizUUIDS);
			sort($uniqueQuizUUIDs);
			$quizUUIDS = $uniqueQuizUUIDs;
			$stmt->close();
		} else {
			echo $mysqli->error;
		}
		$info = array(array(), array());//uuids, testnames, numberofresults, house
		for($c = 0; $c < count($quizUUIDS); $c++) {
			array_push($info[0], $quizUUIDS[$c]);
			if($stmt = $mysqli->prepare("SELECT quizname FROM quizzes WHERE owner=? AND uuid=?;")) {
				$stmt->bind_param("ss", $_SESSION['dbext'], $quizUUIDS[$c]);
				$stmt->bind_result($thisName);
				$stmt->execute();
				$stmt->store_result();
				$stmt->fetch();
				if(isset($thisName)) {
					array_push($info[1], $thisName);
				} else {
					array_push($info[1], "Unknown Quiz Name");	
				}
				$stmt->close();
			} else {
				echo $mysqli->error;
			}
		}
		array_multisort($info[1], $info[0]);
		for($xQuiz = 0; $xQuiz < count($info[1]); $xQuiz++){
			for($xHouse = 0; $xHouse < count($house); $xHouse++){
				if($stmt = $mysqli->prepare("SELECT id, firstname, lastname, rawscore, possiblescore, percentage, frscore, frpossible, frgraded, timestamp
							FROM `results` WHERE quizuuid=? AND house=? AND owner=? ORDER BY lastname ASC;")){
					$stmt->bind_param("sss", $info[0][$xQuiz], $house[$xHouse], $_SESSION['dbext']);
					$stmt->execute();
					$stmt->bind_result($id, $firstname, $lastname, $rawscore, $possiblescore, $percentage, $frscore, $frpossible, $frgraded, $timestamp);
					$stmt->store_result();
					$UUIDANDHOUSE = $info[0][$xQuiz].$house[$xHouse];
					if($stmt->num_rows > 0){
						//DISPLAY HERE
						?>
						<div class="12u">
							<paper-material>
								<h3><? echo $info[1][$xQuiz]; ?> - <? echo $house[$xHouse]; ?> - <? echo $stmt->num_rows; ?> Result(s)</h3>
								<br />
								<a href='func/export.func.php?quizuuid=<? echo $info[0][$xQuiz]; ?>' target="_blank">Download Quiz Results</a>
								<a rel="#delete<? echo $UUIDANDHOUSE; ?>"><font color="#FF0000">Delete Entire House</font></a>
								<br /><br />
								<table cellpadding="5px" border="2px" width="100%">
									<tr>
										<td>Delete</td>
										<td>Last</td>
										<td>First</td>
										<td>Score</td>
										<td>FR Score</td>
										<td>Grade FR</td>
										<td>Date & Time</td>
									</tr>
									<admin-quiz-results-row
									uuid=""
									firstname="First Name"
									lastname="Last Name"
									score="Score"
									freeresponsescore="Free Response Score"
									datetime="Date & Time"
									></admin-quiz-results-row>
									<? while($stmt->fetch()){ ?>
										<div class="simple_overlay" id="<? echo $id; ?>" style="width:175px; min-height:75px;">
											<p style="margin-left:10px;">
											<center>
												<h1 style="color:WHITE; padding-bottom:0px; margin-bottom:0px;">Are you sure?</h1>
												<a href="func/resultdelete.func.php?uuid=<? echo $id; ?>&delete=1"><font color="#FF0000">Delete</font></a>
											</center>
											</p>
										</div>
										<admin-quiz-results-row
										uuid="<? echo $id; ?>"
										firstname="<? echo $firstname; ?>"
										lastname="<? echo $lastname; ?>"
										score="<? echo $percentage."% (".$rawscore."/".$possiblescore.")"; ?>"
										freeresponsescore="<? echo round((($frscore/$frpossible)*100), 2)."% (".$frscore."/".$frpossible.")"; ?>"
										datetime="<? echo date("Y-M-d-D H:i", $timestamp); ?>"
										></admin-quiz-results-row>
										<tr>
											<td><a rel="#<? echo $id;?>"><font color="#FF0000">Delete</font></a></td>
											<td><a href="?p=postquizadmin&uuid=<? echo $id;?>" target="_blank"><? echo $lastname; ?></a></td>
											<td><a href="?p=postquizadmin&uuid=<? echo $id;?>" target="_blank"><? echo $firstname; ?></a></td>
											<td><? echo $percentage."% (".$rawscore."/".$possiblescore.")"; ?></td>
											<td><? echo round((($frscore/$frpossible)*100), 2)."% (".$frscore."/".$frpossible.")"; ?></td>
											<td>
											<? if($frgraded==0){ ?>
												<a href="?p=gradefr&uuid=<? echo $id;?>" target="_blank">Grade</a>
											<?	} else { if($frpossible>0){ ?>
												<font color="#00FF00">Graded</font>
											<?	} else { ?>
												<font color="#00FF00">N/A</font>
												<?	}
											} ?>
											</td>
											<td><? echo date("Y-M-d-D H:i", $timestamp); ?></td>
										</tr>
									<? } ?>
								</table>
							</paper-material>
						</div>
				<?	}
				}
			}
		}
	}
}

?>