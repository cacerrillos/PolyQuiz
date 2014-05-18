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
		<!-- Main Wrapper -->
			<div id="main-wrapper">
				<div class="main-wrapper-style1">
					<div class="inner">
						<!-- Feature 1 -->
							<section class="container box-feature1">
								<div class="row">
									<div class="12u">
										<header class="first major">
											<h2>PolyQuiz, The Online Quiz Site</h2>
											<span class="byline">Made for students, teachers, and <strong>treehuggers</strong><br />in only <? echo lines(); ?> lines of code!<br /><strong><? echo $count; ?></strong> quizzes taken, and <strong>counting</strong>!<br /><br />Now accpeting <a href="?p=register">registrations</a>!</span>
                                            
										</header>
									</div>
								</div>
							</section>
					</div>
				</div>
			</div>