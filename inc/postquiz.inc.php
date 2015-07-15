<?
$studentuuid = $_GET['uuid'];
?>
<?
$qfm = new quizFromMysql();
$quiz = $qfm->getQuiz($studentuuid);
$numofq = $quiz->numberofquestions;
include_once("func/config.func.php");
?>
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
                                                <?
												include("inc/adminleftsidebar.inc.php");
												?>
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

											</article>
								
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>