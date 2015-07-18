<?
$studentuuid = $_GET['uuid'];
?>
<?
$qfm = new quizFromMysql();
$quiz = $qfm->getQuiz($studentuuid);
$numofq = $quiz->numberofquestions;
include_once("func/config.func.php");
if(isset($_SESSION["is_admin"])){
	if(!isset($_GET["raw"])){
		?>
		<div class="container">
			<div class="row">
				<div class="12u">
					<paper-material>
						<h1>Detailed Results</h1>
						Welcome back admin!
						<font color="#00FF00">Answered Correctly</font><br />
						<b>Correct Answer</b><br />
						<font color='#FF0000'>Incorrect Answer</font>
						<?
							echo "<b>".$quiz->lname.", ".$quiz->fname."</b>";
						?>
					</paper-material>
				</div>
				<?
			for($x = 0; $x <$numofq; $x++){
				$thisquestion = $quiz->getQuestion($x);
				?>
				<div class="12u">
				<paper-material>
					<div class="row">
						<div class="12u">
							<?
							if($thisquestion->response == -1){
								echo ($x+1).") (Student earned 0 points, Question skipped!)".$thisquestion->question;
							} else {
								if($thisquestion->type!=4){
									echo ($x+1).") (Student earned ".$thisquestion->getPoints()." points)".$thisquestion->question;
								} else {
									echo ($x+1).") (Student earned ".$thisquestion->answerArray[$thisquestion->response]->getPoints()." points)".$thisquestion->question;
								}
							}
							?>
						</div>
						<?
						if($thisquestion->includeCanvas){
						?>
							<div class="12u">
								<paper-material><img src="<? echo $thisquestion->getCanvasValue(); ?>" /></paper-material>
							</div>
						<?
						}
						?>
						<?
						for($y = 0; $y <count($thisquestion->answerArray); $y++){
							?>
							<div class="12u">
							<?
							if($thisquestion->answer==$y){
								echo "<b>";
							}
							if($thisquestion->type!=4){
								if($thisquestion->response==$y && $thisquestion->answer!=$y){
									echo "<font color='#FF0000'>";
								}
								if($thisquestion->response==$y && $thisquestion->answer==$y){
									echo "<font color='#00FF00'>";
								}
							} else {
								if($thisquestion->response==$y){
									echo "<font color='#0000AA'>";
								}
							}
							echo $alphabet[$y];
							if($thisquestion->type==4){
								echo " (".$thisquestion->answerArray[$y]->getPoints()." points";	
							}
							if($thisquestion->type!=4){
								echo ") ".$thisquestion->answerArray[$y];
							} else {
								echo ") ".$thisquestion->answerArray[$y]->getAnswer();
							}
							if($thisquestion->response==$y && $thisquestion->answer!=$y){
								echo "</font>";
							}
							if($thisquestion->response==$y && $thisquestion->answer==$y){
								echo "</font>";
							}
							if($thisquestion->answer==$y){
								echo "</b>";
							}
							echo "</div>";
						}
						?>
						<div class="12u"></div>
					</div>
				
				</paper-material>
				</div>
				<?
			}
			?>	
			</div>
		</div>
		<?
	} else {
		?>
		content
		<?
	}
}
?>