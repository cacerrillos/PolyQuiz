<?
$mysqli = new mysqli($db_host, $db_user, $db_password);
$mysqli -> select_db($db_name);
if(mysqli_connect_errno()) {
	echo "Connection Failed: " . mysqli_connect_errno();
	exit();
}
$count = 0;
if($stmt = $mysqli->prepare("SELECT value FROM stats WHERE id='totalresults';")){
	$stmt -> execute();
	$stmt -> bind_result($count);
	$stmt -> store_result();
	$stmt -> fetch();
	$stmt -> close();
} else {
	echo $mysqli->error;
}
?>
<div class="container">
	<div class="row" style="margin:0;">
		<img src="images/title.png" style="margin-left: auto; margin-right: auto; width: 100%; padding-left: 0px;">
	</div>
	<div class="row" style="margin-top:0px;">
		<div class="12u">
			<paper-material>
				<div class="container 30%">
					<h2 style="margin: 0;">PolyQuiz, The Online Quiz Site</h2>
					<span class="byline">
						Made for students, teachers, and <strong>treehuggers</strong><br />
						in only <? echo lines(); ?> lines of code!<br />
						<strong><? echo $count; ?></strong> quizzes taken, and <strong>counting</strong>!<br />
						<br />
						Now accpeting <a href="?p=register">registrations</a>!
					</span>
				</div>
			</paper-material>
		</div>
	</div>
</div>