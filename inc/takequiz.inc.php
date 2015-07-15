<div class="container">
	<div class="row">
		<div class="12u">
		<paper-material>
		<h2><i class="fa fa-info-circle"></i> Quiz Status</h2>
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
			if($stmt = $mysqli -> prepare("SELECT * FROM quizzes WHERE uuid = ?")){
					$stmt -> bind_param("s", $uuid);
					$stmt -> execute();
					$stmt -> store_result();
					$num = $stmt -> num_rows;
					$stmt -> close();
			} else {
				echo $mysqli->error;
			}
			if($num==0 && false){
				echo "000";
				return false;
			} else {
				?>
				<!-- <? echo $uuid; ?> -->
				<?
				if($stmt = $mysqli -> prepare("SELECT * FROM quizzes WHERE uuid = ?")){
					$stmt -> bind_param("s", $uuid);
					$stmt -> execute();
					$stmt -> bind_result($result['uuid'], $result['quizname'], $result['quizsubject'], $result['status'], $result['owner']);
					$stmt -> store_result();
					$stmt -> fetch();
					$stmt -> close();
					$quizname = $result['quizname'];
				} else {
					echo $mysqli->error;
				}
			}
			
		?>
		<table style="border-spacing: 10px;border-collapse: separate;">
			<tr style="border:0">
				<td style="border:0">First Name:</td>
				<td style="color:#000;border:0"><? echo $_SESSION['firstname']; ?></td>
			</tr>
			<tr style="border:0">
				<td style="border:0">Last Name:</td>
				<td style="color:#000;border:0"><? echo $_SESSION['lastname']; ?></td>
			</tr>
			<tr style="border:0">
				<td style="border:0">Quiz:</td>
				<td style="color:#000;border:0"><? echo $quizname; ?></td>
			</tr>
			<tr style="border:0">
				<td style="border:0">House:</td>
				<td style="color:#000;border:0"><? echo $quiz->getHouse(); ?></td>
			</tr>
		</table>
			<a href="?p=exit" <? if(!isset($_SESSION['firstname']) && !isset($_SESSION['lastname'])){ echo "onclick='window.onbeforeunload = null;'";}?> class="button alt fa fa-trash-o">Exit Quiz</a>
			<?
		}
		?>
		</paper-material>
		</div>
	</div>
</div>
<div class="container">
<div class="row">
<div class="12u">
<paper-material>
<h2><i class="fa fa-file"></i> Take a Quiz</h2>
</paper-material>
</div>
</div>
<div class="row">

<?
//quiz is good to go
if(!isset($_SESSION['firstname']) && !isset($_SESSION['lastname'])){
	$mysqli = new mysqli($db_host, $db_user, $db_password);
	?>
<div class="6u">
<paper-material>
	<h4>Start a Quiz</h4>
	<form name="prequiz" method="post" action="func/prequiz.func.php">
					<input type="hidden" name="type" value="session" />
		<take-a-quiz-form></take-a-quiz-form>
	<table>
	<tr style="border:0">
		<td style="border:0">First Name:</td>
		<td style="border:0"><input type="text" name="firstname" autocorrect="off" autocomplete="off" autocapitalize="on"></td>
	</tr>
	<tr style="border:0">
		<td style="border:0">Last Name:</td>
		<td style="border:0"><input type="text" name="lastname" autocorrect="off" autocomplete="off" autocapitalize="on"></td>
	</tr>
	<tr style="border:0">
		<td style="border:0">Session ID:</td>
		<td style="border:0"><input type="text" name="sessionid" autocorrect="off" autocomplete="off" autocapitalize="off"/></td>
	</tr>
	<tr style="border:0">
		<td style="border:0">Session Key:</td>
		<td style="border:0"><input type="text" name="sessionkey" autocorrect="off" autocomplete="off" autocapitalize="off"/></td>
	</tr>
	</table>
	<div align="center">
	<input type="submit" name="submit" value="Begin" onclick="window.onbeforeunload = null;" class="button fa">
	</div>
	</form>
		</paper-material>

	</div>
		<div class="6u">
	<paper-material>
	<h4>Resume A Quiz</h4>
	<form name="restorequiz" method="post" action="func/prequiz.func.php">
	<table>
	<tr>
		<td style="border:0">Resume Id:</td>
		<td style="border:0"><input type="text" name="id" autocorrect="off" autocomplete="off" autocapitalize="off"/></td>
	</tr>
	<tr>
		<td style="border:0">Resume Key:</td>
		<td style="border:0"><input type="text" name="key" autocorrect="off" autocomplete="off" autocapitalize="off" /></td>
	</tr>
	</table>
	<div align="center">
	<input type="submit" name="submit" value="Resume" onclick="window.onbeforeunload = null" class="button fa"/>
	</div>
	</form>
	</div>
	</paper-material>
	<?
	$mysqli -> close();
} else {
	$quiz = $_SESSION['quiz'];
	if(!isset($_GET['num'])){
		$num = 0;
	} else {
		$num = $_GET['num'];
	}
	if(!$num==0 && !isset($_SESSION["answers"][$num-1])){
		//skipped question
	} else {
		$mysqli = new mysqli($db_host, $db_user, $db_password);
		$mysqli -> select_db($db_name);
		if(mysqli_connect_errno()){
			echo "Connection Failed: " . mysqli_connect_errno();
			//exit();
		}
		$questions = $quiz -> questions;
		if(isset($questions[intval($num)])){
			$thisquestion = $quiz -> getQuestion(intval($num));
			echo "<h2 style='margin-bottom:5px'>#".($num+1)."</h2>";
			if($thisquestion->displayextracredit==true){
				$ecstatus = "[Extra Credit] ";
			} else {
				$ecstatus = "";
			}
			?>
            <span style='text-transform:none; word-wrap: break-word; font-family: "Arvo", serif; font-size:1.5em; color: #000;'><? echo $ecstatus.$thisquestion->question; ?></span>
            <?
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
			?>
			<form name="<? echo $num; ?>" method="post" action="func/quiz.func.php" onsubmit="addCanvas()">
			<input type="hidden" name="uuid" value="<? if($use==false){ echo mysqli_real_escape_string($mysqli, $_GET['UUID']); } else { echo $quiz->uuid(); } ?>">
			<input type="hidden" name="num" value="<? echo $num; ?>">
			<?
			$thisquestion->paint($alphabet, $curans);
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
?>
</paper-material>
</div>
</div>
</div>
