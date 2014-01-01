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
                                                  <h3><a href="?p=admin">The Core</a></h3>
    <h1>Quiz Management</h1>
<?
	if(isset($_SESSION["is_admin"])){
	?>
	<p>Welcome back admin!</p>
    <p>
    <h3>Create New Quiz</h3>
    <div id="create" style="margin-left:20px">
    <form id="newquiz" action="func/newquiz.func.php" method="post">
    Quiz Name: <input type="text" name="quizname"><br>
    <input type="radio" name="status" value="1" checked> Enabled<br>
	<input type="radio" name="status" value="0" > Disabled<br>
    <input type="submit" name="submit" value="Create">
    </form>
    </div>
    <h3>Manage Quiz</h3>
    <div id="quizmanage" style="margin-left:20px">
    <?
	mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
	mysql_select_db($db_name) or die(mysql_error()); 
	$data = mysql_query("SELECT * FROM quizes ORDER BY quizname ASC;");
	if(mysql_num_rows($data)==0){
		echo "You have no quizes!";
	}
	while($info = mysql_fetch_array($data)){
		echo "<a href='?p=managequiz&UUID=".$info['uuid']."'>".$info['quizname']."</a><br>";
	}
	?>
    <br><br>
    </div>
    </p>
    <p><a href="func/admin.logout.php">Logout</a>
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