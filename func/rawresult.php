<?
session_start();
include_once("config.func.php");
include_once("genquiz.func.php");
$studentuuid = $_GET['uuid'];
?>
<?
$qfm = new quizFromMysql();
$quiz = $qfm->getQuiz($studentuuid);
$numofq = $quiz->numberofquestions;
if(isset($_SESSION["is_admin"])){
		?>
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
			</div>
				<?
			for($x = 0; $x <$numofq; $x++){
				?>
				<div class="row">
				<div class="12u">
				<paper-material>
				<?
				$thisquestion = $quiz->getQuestion($x);
				if($thisquestion->response == -1){
					echo ($x+1).") (Student earned 0 points, Question skipped!)".$thisquestion->question." <br>";
				} else {
					if($thisquestion->type!=4){
						echo ($x+1).") (Student earned ".$thisquestion->getPoints()." points)".$thisquestion->question." <br>";
					} else {
						echo ($x+1).") (Student earned ".$thisquestion->answerArray[$thisquestion->response]->getPoints()." points)".$thisquestion->question." <br>";
					}
				}
				if($thisquestion->includeCanvas){
					?>
					<img src="<? echo $thisquestion->getCanvasValue(); ?>" /><br />
					<?
				}
				for($y = 0; $y <count($thisquestion->answerArray); $y++){
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
					echo "<br>";
				}
				?>
				</paper-material>
				</div>
				</div>
				<?
			}
			?>	
			</div>
		<?
}
?>