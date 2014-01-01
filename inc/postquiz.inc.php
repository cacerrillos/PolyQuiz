<?
$studentuuid = $_GET['uuid'];
?>
<script type="text/javascript">
function findElement(element_id) {
  if (document.getElementById && document.getElementById(element_id)) {
   return document.getElementById(element_id);
  } else {
    return false;
  }
}

function hideElement(element_id) {
  element = findElement(element_id)
  if (element) {
    element.style.display = 'none';
    return element;
  } else {
    return false;
  }
}

function showElement(element_id) {
  element = findElement(element_id)
  if (element) {
    element.style.display = '';
    return element;
  } else {
    return false;
  }
}
</script>
<?
$qfm = new quizFromMysql();
$quiz = $qfm->getQuiz($studentuuid);
$numofq = $quiz->numberofquestions;
include_once("func/config.func.php");
?>
  <div class="content">
    <h1>Detailed Results</h1>
<?
	if(isset($_SESSION["is_admin"])){
	?>
	<p>Welcome back admin!</p>
    <p><font color="#00FF00">Answered Correctly</font><br />
    <b>Correct Answer</b><br />
    <font color='#FF0000'>Incorrect Answer</font>
    </p>
    <p>
    
    <?
	echo "<b>".$quiz->lname.", ".$quiz->fname."</b><br><br>";
	for($x = 0; $x <$numofq; $x++){
		$thisquestion = $quiz->getQuestion($x);
		echo ($x+1).") ".$thisquestion->question."<br>";
		for($y = 0; $y <count($thisquestion->answerArray); $y++){
			if($thisquestion->answer==$y){
				echo "<b>";
			}
			if($thisquestion->response==$y && $thisquestion->answer!=$y){
				echo "<font color='#FF0000'>";
			}
			if($thisquestion->response==$y && $thisquestion->answer==$y){
				echo "<font color='#00FF00'>";
			}
			echo $alphabet[$y];
			echo ") ".$thisquestion->answerArray[$y];
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
		echo "<br>";
	}
	//var_dump($quiz);
	?>
    </p>
    <a href="func/admin.logout.php">Logout</a>
    <?
	} else {
	
?>
    <p>
    <div id="login" style="margin-left:20px">
    <h2>Login</h2>
    <form id="adminlogin" action="func/admin.login.php" method="post">
    User: <input type="text" name="user"><br>
    Pass: <input type="password" name="pass"><br>
    <input type="submit" name="submit" value="Login"><br>
    </form>
    </div>
    </p>
<?
}
?>
    <!-- end .content --></div>