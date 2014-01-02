		<!-- Main Wrapper -->
			<div id="main-wrapper">
				<div class="main-wrapper-style2">
					<div class="inner">
						<div class="container">
							<div class="row">
								<div class="4u">
									<div id="sidebar">

										<!-- Sidebar -->
											<section>
												<header class="major">
													<h2><i class="fa fa-info-circle"></i> Quiz Status</h2>
												</header>
												<footer>
                                                <?
                                                if(!isset($_SESSION['firstname']) && !isset($_SESSION['lastname'])){
													?>
													<strong>You are not currently taking a quiz!</strong>
                                                    <?
												} else {
													$quiz = $_SESSION['quiz'];
													$uuid = $quiz->uuid;
													
													$mysqli = new mysqli($db_host, $db_user, $db_password);
													$mysqli -> select_db($db_name);
													if(mysqli_connect_errno()) {
														echo "Connection Failed: " . mysqli_connect_errno();
														exit();
													}
													if($stmt = $mysqli -> prepare("SELECT * FROM quizes WHERE uuid = ?")){
															$stmt -> bind_param("s", $uuid);
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
														if($stmt = $mysqli -> prepare("SELECT * FROM quizes WHERE uuid = ?")){
															$stmt -> bind_param("s", $uuid);
															$stmt -> execute();
															$stmt -> bind_result($result['uuid'], $result['quizname'], $result['quizsubject'], $result['status']);
															$stmt -> store_result();
															$stmt -> fetch();
															$stmt -> close();
															$quizname = $result['quizname'];
														}
													}
													
												?>
                                                <table style="border-spacing: 10px;border-collapse: separate;">
                                                    <tr>
                                                        <td>First Name:</td>
                                                        <td style="color:#000;"><? echo $_SESSION['firstname']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Last Name:</td>
                                                        <td style="color:#000;"><? echo $_SESSION['lastname']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Quiz:</td>
                                                        <td style="color:#000;"><? echo $quizname; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>House:</td>
                                                        <td style="color:#000;"><? echo $quiz->getHouse(); ?></td>
                                                    </tr>
                                                </table>
                                                    <a href="?p=exit" <? if(!isset($_SESSION['firstname']) && !isset($_SESSION['lastname'])){ echo "onclick='window.onbeforeunload = null;'";}?> class="button alt fa fa-trash-o">Exit Quiz</a>
                                                    <?
												}
												?>
												</footer>
											</section>
								
									</div>
								</div>
								<div class="8u skel-cell-important">
									<div id="content">

										<!-- Content -->
									
											<article>
												<header class="major">
													<h2><i class="fa fa-file"></i> Take a Quiz</h2>
												</header>
                                                <?

if(!isset($_GET['UUID']) && $use==false){
	echo "Invalid quiz ID!";
} else {
	if($use==false){
		$uuid = $_GET['UUID'];
	}
//	$data = mysql_query("SELECT * FROM quizes WHERE uuid='". mysql_real_escape_string() ."';");
	$mysqli = new mysqli($db_host, $db_user, $db_password);
	$mysqli -> select_db($db_name);
	if(mysqli_connect_errno()){
		echo "Connection Failed: " . mysqli_connect_errno();
		exit();
	}
	if($stmt = $mysqli -> prepare("SELECT * FROM quizes WHERE uuid=?")){
		$stmt -> bind_param("s", $uuid);
		$stmt -> execute();
		$stmt -> store_result();
		$numrows = $stmt->num_rows;
		$stmt -> bind_result($info['uuid'], $info['quizname'], $info['quizsubject'], $info['status']);
		$stmt -> fetch();
		$stmt -> close();
	}
	$mysqli -> close();
//	$numrows = $result->num_rows;
	if($numrows==0 && $use==false){
		echo "Invalid quiz ID!";
	} else {
	//	$info = mysql_fetch_array($data);
		if($info['status']==0 && $use ==false){
			echo "Quiz is not enabled!";
		} else {
			//quiz is good to go
			if(!isset($_SESSION['firstname']) && !isset($_SESSION['lastname'])){
				$mysqli = new mysqli($db_host, $db_user, $db_password);
				?>
                <div style="overflow:auto; width:100%;">
                <div style="display: inline-block; float:left; padding-left:10px; padding-right:10px;">
                <h4>Start a Quiz</h4>
                <form name="prequiz" method="post" action="func/prequiz.func.php">
                                <input type="hidden" name="type" value="session" />
                <span style="display:inline-block">            
                <table>
                <tr>
                	<td>First Name:</td>
                    <td><input type="text" name="firstname" autocorrect="off" autocomplete="off" autocapitalize="on"></td>
                </tr>
                <tr>
                	<td>Last Name:</td>
                    <td><input type="text" name="lastname" autocorrect="off" autocomplete="off" autocapitalize="on"></td>
                </tr>
                <tr>
                	<td>Session ID:</td>
                    <td><input type="text" name="sessionid" autocorrect="off" autocomplete="off" autocapitalize="off"/></td>
                </tr>
                <tr>
                	<td>Session Key:</td>
                    <td><input type="text" name="sessionkey" autocorrect="off" autocomplete="off" autocapitalize="off"/></td>
                </tr>
                </table>
                <div align="center">
                <input type="submit" name="submit" value="Begin" onclick="window.onbeforeunload = null;" class="button fa">
                </div>
                </span>
                </form>
                </div>
                <div style="display:inline-block; float:left; padding-left:10px; padding-right:10px;">
                <h4>Resume A Quiz</h4>
                <form name="restorequiz" method="post" action="func/prequiz.func.php">
                <span style="display:inline-block;">
                <table>
                <tr>
                	<td>Resume Id:</td>
                    <td><input type="text" name="id" autocorrect="off" autocomplete="off" autocapitalize="off"/></td>
                </tr>
                <tr>
                	<td>Resume Key:</td>
                    <td><input type="text" name="key" autocorrect="off" autocomplete="off" autocapitalize="off" /></td>
                </tr>
                </table>
                <div align="center">
                <input type="submit" name="submit" value="Resume" onclick="window.onbeforeunload = null" class="button fa"/>
                </div>
                </span>
                </form>
                </div>
                </div>
                <?
				$mysqli -> close();
			} else {
				$quiz = $_SESSION['quiz'];
				if(!isset($_GET['num'])){
					$num = 0;
				} else {
					$num = $_GET['num'];
				}
				//echo $num;
				if(!$num==0 && !isset($_SESSION["answers"][$num-1])){
					//skipped question
				//	echo $num;
				//echo "Checkpoint";
				} else {
					
					//echo "Checkpoint";
					//var_dump($quiz);
					$mysqli = new mysqli($db_host, $db_user, $db_password);
					$mysqli -> select_db($db_name);
					if(mysqli_connect_errno()){
						echo "Connection Failed: " . mysqli_connect_errno();
						//exit();
					}
					/*
						$stmt = $mysqli -> prepare("SELECT * FROM ".$mysqli->real_escape_string($uuid)." WHERE id = ?;");
						if(false===$stmt){
							die(htmlspecialchars($mysqli->error));
						}
						$stmt -> bind_param("i", intval($num));
						$stmt -> execute();
						$stmt -> store_result();
						$numrows = $stmt ->num_rows;
						$stmt -> bind_result($quiz['id'], $quiz['question'], $quiz['a'], $quiz['b'], $quiz['c'], $quiz['d'], $quiz['answer']);
						$stmt -> fetch();
						$stmt -> close(); */
					//	$quizquery = mysql_query("SELECT * FROM ". mysql_real_escape_string($_GET['UUID']) ." WHERE id='".$num."';");
						$questions = $quiz -> questions;
						//echo count($questions);
											//var_dump($questions);

						if(isset($questions[intval($num)])){
							$thisquestion = $quiz -> getQuestion(intval($num));
							echo "<h2 style='margin-bottom:5px'>#".($num+1)."</h2>";
							echo "<h4>".$thisquestion->question."</h4>";
							$thisquestion -> imagegroup -> printThumbnails("normal");
							if(isset($_SESSION["answers"][$num])){
								$curans = $_SESSION["answers"][$num];
							} else {
								if($thisquestion->type==1){
									$curans = "";
								} else {
								$curans = -2;
								}
							}
							//var_dump($thisquestion);
							?>
             	           <form name="<? echo $num; ?>" method="post" action="func/quiz.func.php">
                	        <input type="hidden" name="uuid" value="<?
				if($use==false){
					echo mysqli_real_escape_string($mysqli, $_GET['UUID']);
				} else {
					echo $quiz->uuid();
				}
				?>">
                    	    <input type="hidden" name="num" value="<? echo $num; ?>">
<?
$thisquestion->paint($alphabet, $curans);
?>
                                
                        	<?
							if($num!=0){
								?>
                            	<input type="submit" name="submit" value="Previous" onclick="window.onbeforeunload = null;" class="button fa alt fa-arrow-circle-o-left">
                            	<?
							}
							?>
                        	<input type="submit" name="submit" value="Next" onclick="window.onbeforeunload = null;" class="button fa fa-arrow-circle-o-left">
                        	</form>
                        	<?
						} else if($num==count($questions)){
							//echo $quiz->possiblepoints;
							echo '<h4>You have reached the end of the quiz!<br>You may go back and check your answers, or click "Finish" to end the quiz!</h4>'; 
							?>
                            <form name="finishquiz" method="post" action="func/quiz.func.php">
                            <input type="hidden" name="uuid" value="<? echo $_GET['UUID']; ?>">
                    	    <input type="hidden" name="num" value="<? echo $num; ?>">
                            <input type="submit" name="submit" value="Previous" onclick="window.onbeforeunload = null;" class="button fa alt fa-arrow-circle-o-left">
                            <input type="submit" name="submit" value="Finish" onclick="window.onbeforeunload = null;" class="button fa fa-arrow-circle-o-left">
                            </form>
                            <?
						}
				}
				
			}
			
		}
	}
}
?>
    </div>
											</article>
								
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>