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
													<h2>Subheading</h2>
												</header>
												<footer>
													<a href="#" class="button alt fa fa-arrow-circle-o-right">Do Something</a>
												</footer>
											</section>
								
									</div>
								</div>
								<div class="8u skel-cell-important">
									<div id="content">

										<!-- Content -->
									
											<article>
												<header class="major">
													<h2>Page Title</h2>
													<span class="byline">Which means the sidebar is on the left</span>
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
			//	$tempp = new imageGroup();
			//	$tempp ->addImage("abc");
			//	$tempp ->addImage("a1b2");
			//	$tempp ->addImage("1372238321477ebc65d13d6299d8714096bd3952a2");
			//	$tempp ->printThumbnails("normal");
				?>
                <form name="prequiz" method="post" action="func/prequiz.func.php">
                First Name: <input type="text" name="firstname" autocorrect="off" autocomplete="off" autocapitalize="off"><br>
                Last Name: <input type="text" name="lastname" autocorrect="off" autocomplete="off" autocapitalize="off"><br>
                <?
				if($use==false){
				?>
                <input type="hidden" name="type" value="normal" />
				<input type="hidden" name="uuid" value="<?
				if($use==false){
					echo mysqli_real_escape_string($mysqli, $_GET['UUID']);
				} else {
					echo $quiz->uuid();
				}
				?>"><br>
                House: <select name="house" size="5">
                <option value="North">North</option>
                <option value="South">South</option>
                <option value="East">East</option>
                <option value="West">West</option>
                </select>
                <?
				} else {
				?>
                <input type="hidden" name="type" value="session" />
                Session ID: <input type="text" name="sessionid" autocorrect="off" autocomplete="off" autocapitalize="off"/><br />
                Session Key: <input type="text" name="sessionkey" autocorrect="off" autocomplete="off" autocapitalize="off"/>
                <?
				}
				?>
                <br />
                <input type="submit" name="submit" value="Begin" onclick="window.onbeforeunload = null;">
                </form>
                <br />
                <h4>Resume A Quiz</h4>
                <form name="restorequiz" method="post" action="func/prequiz.func.php">
                Resume Id: <input type="text" name="id" autocorrect="off" autocomplete="off" autocapitalize="off"/><br />
                Resume Key: <input type="text" name="key" autocorrect="off" autocomplete="off" autocapitalize="off" /><br />
                <input type="submit" name="submit" value="Resume" onclick="window.onbeforeunload = null" />
                </form>
                <?
				$mysqli -> close();
			} else {
				$quiz = $_SESSION['quiz'];
				
				echo $quiz-> getHouse()."<br>".$_SESSION['lastname'].", ".$_SESSION['firstname']."<br>";
				echo "<br><br>";
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
							echo "<h2>#".($num+1)."</h2>";
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
                            	<input type="submit" name="submit" value="Previous" onclick="window.onbeforeunload = null;">
                            	<?
							}
							?>
                        	<input type="submit" name="submit" value="Next" onclick="window.onbeforeunload = null;">
                        	</form>
                        	<?
						} else if($num==count($questions)){
							//echo $quiz->possiblepoints;
							echo '<h4>You have reached the end of the quiz!<br>You may go back and check your answers, or click "Finish" to end the quiz!</h4>'; 
							?>
                            <form name="finishquiz" method="post" action="func/quiz.func.php">
                            <input type="hidden" name="uuid" value="<? echo $_GET['UUID']; ?>">
                    	    <input type="hidden" name="num" value="<? echo $num; ?>">
                            <input type="submit" name="submit" value="Previous" onclick="window.onbeforeunload = null;">
                            <input type="submit" name="submit" value="Finish" onclick="window.onbeforeunload = null;">
                            </form>
                            <?
						}
				}
				
			}
			
		}
	}
}
?>
<br><br>
<h3><a href="?p=exit" <? if(!isset($_SESSION['firstname']) && !isset($_SESSION['lastname'])){ echo "onclick='window.onbeforeunload = null;'";}?>>Exit Quiz</a></h3>
    </div>
											</article>
								
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>