<div class="container" id="thisPageContainer">
	<div class="row">
		<div class="12u">
			<paper-material>
				<h2 class="nomargins">Results & Grading</h2>
				<?
				$sortbysession = false;
				if(isset($_GET['sortby'])) {
					if($_GET['sortby']=="session"){
						$sortbysession = true;
						?>
						Sort by <a href="?p=results">Quiz & House</a> | Session
						<?
					} else {
						?>
						Sort by <a href="?p=results">Quiz & House</a> | <a href="?p=results&sortby=session">Session</a>
						<?
					}
				} else {
					?>
					Sort by Quiz & House | <a href="?p=results&sortby=session">Session</a>
					<?
				}
				?>
			</paper-material>
		</div>
	</div>
	<div class="row">
		<? if(isset($_SESSION["is_admin"])){
		$mysqli = new mysqli($db_host, $db_user, $db_password);
		$mysqli -> select_db($db_name);
		if(!$sortbysession){
			$quizUUIDS = array();
			$house[0] = "North";
			$house[1] = "South";
			$house[2] = "East";
			$house[3] = "West";
			$house[4] = "Other";
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
		} else {
			$info = array(array(), array(), array());//uuids, sessionnames, house
			$sessionsToLookup = array();
			if($stmt = $mysqli->prepare("SELECT session FROM `results` WHERE owner = ?;")) {
				$stmt->bind_param("s", $_SESSION['dbext']);
				$stmt->execute();
				$stmt->bind_result($thisSessionUUID);
				$stmt->store_result();
				while($stmt->fetch()) {
					array_push($sessionsToLookup, $thisSessionUUID);
				}
				$stmt->close();
				$unique = array_unique($sessionsToLookup);
				sort($unique);
			} else {
				echo $mysqli->error;	
			}
			for($z = 0; $z < count($unique); $z++){
				array_push($info[0], $unique[$z]);
			}
			for($c = 0; $c < count($info[0]); $c++) {
				if($stmt = $mysqli->prepare("SELECT sessionname, house FROM `sessions` WHERE owner=? AND uuid=?;")) {
					$stmt->bind_param("ss", $_SESSION['dbext'], $info[0][$c]);
					$stmt->bind_result($thisName, $house);
					$stmt->execute();
					$stmt->store_result();
					$stmt->fetch();
					if(isset($thisName)) {
						array_push($info[1], $thisName);
					} else {
						array_push($info[1], "Unknown Session Name");	
					}
					if(isset($house)) {
						array_push($info[2], $house);
					} else {
						array_push($info[2], "Unknown");	
					}
					$stmt->close();
					
				} else {
					echo $mysqli->error;
				}
			}
			array_multisort($info[1], $info[0], $info[2]);
			for($xSession = 0; $xSession < count($info[0]); $xSession++){
				if($stmt = $mysqli->prepare(
				"SELECT id, firstname, lastname, rawscore, possiblescore, percentage, frscore, frpossible, frgraded, timestamp
				FROM `results` WHERE session=? AND owner=? ORDER BY lastname ASC;")){
					$stmt->bind_param("ss", $info[0][$xSession], $_SESSION['dbext']);
					$stmt->bind_result($id, $firstname, $lastname, $rawscore, $possiblescore, $percentage, $frscore, $frpossible, $frgraded, $timestamp);
					$stmt->execute();
					$stmt->store_result();
					$num = $stmt->num_rows;
					if($num>0){
						?>
						<div class="12u">
							<paper-material>
								<?
								echo "<h3 style='display:inline;'>".$info[1][$xSession]." - ".$info[2][$xSession]." - ".$num." Result(s)</h3>";
								?>
								<br />
								<? //<a href='func/export.func.php?sessionuuid=<? echo $info[0][$xSession]; >' target="_blank">Download Quiz Results</a> ?>
								<a rel="#<? echo $info[0][$xSession]; ?>"><font color="#FF0000">Delete Entire Session</font></a>
								<br /><br />
								<table cellpadding="5px" border="2px" width="100%">
									<tr>
										<? //<td>#</td> 
										?>
										<td>Delete</td>
										<td>Last</td>
										<td>First</td>
										<td>Score</td>
										<td>FR Score</td>
										<td>Grade FR</td>
										<? // <td>Date</td> ?>
										<td>Date & Time</td>
										<? //<td>Session</td> ?>
									</tr>
									<admin-quiz-results-row
									uuid=""
									firstname="First Name"
									lastname="Last Name"
									score="Score"
									freeresponsescore="Free Response Score"
									datetime="Date & Time"
									></admin-quiz-results-row>
									<?
									while($stmt->fetch()){
										?>
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
											<td><a rel="#<? echo $id; ?>"><font color="#FF0000">Delete</font></a></td>
											<td><a href="?p=postquizadmin&uuid=<? echo $id; ?>" target="_blank"><? echo $lastname; ?></a></td>
											<td><a href="?p=postquizadmin&uuid=<? echo $id; ?>" target="_blank"><? echo $firstname; ?></a></td>
											<td><? echo $percentage."% (".$rawscore."/".$possiblescore.")"; ?></td>
											<td><? echo round((($frscore/$frpossible)*100), 2)."% (".$frscore."/".$frpossible.")"; ?></td>
											<td>
											<? if($frgraded==0){ ?>
											<a href="?p=gradefr&uuid=<? echo $id;?>" target="_blank">Grade</a>
											<?	} else { if($frpossible>0){ ?>
											<font color="#00FF00">Graded</font>
											<?	} else { ?>
												<font color="#00FF00">N/A</font>
												<?	
												}
											}
											?>
											</td>
											<td><? echo date("Y-M-d-D H:i", $timestamp); ?></td>
										</tr>	
									<?
									}
									?>
								</table>
							</paper-material>
						</div>
				<?	}
					$stmt->close();
				} else {
					echo $mysqli->error;
				}
			}
		}
		?>
		<div class="simple_overlay" id="all" style="width:175px; min-height:75px;">
			<p style="margin-left:10px;">
			<center>
				<h1 style="color:WHITE; padding-bottom:0px; margin-bottom:0px;">Are you sure?</h1>
				<a href="func/resultdelete.func.php?uuid=all"><font color="#FF0000">Delete All</font></a>
			</center>
			</p>
		</div>
		<p><a rel="#all"><font color="#FF0000">Delete All</font></a></p>
		<? } ?>
	</div>
</div>