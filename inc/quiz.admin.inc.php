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
													<h2>Quiz Management</h2>
												</header>
<?
	if(isset($_SESSION["is_admin"])){
	?>
    <div id="maindivvvvvv" style="margin-top:0">
    <div id="createouter" style="display:inline-block; float:left; padding-left:10px; padding-right:10px;">
    <h3>Create New Quiz</h3>
    <div id="create" style="margin-left:20px">
    <form id="newquiz" action="func/newquiz.func.php" method="post">
    Quiz Name: <input type="text" name="quizname"><br>
    <input type="radio" name="status" value="1" checked> Enabled<br>
	<input type="radio" name="status" value="0" > Disabled<br>
    <input type="submit" name="submit" value="Create">
    </form>
    </div>
    </div>
    <div id="quizmanageouter" style="display:inline-block; float:left; padding-left:10px; padding-right:10px;">
        <h3>Manage Quiz</h3>
        <div id="quizmanageinner" style="margin-left:20px">
        <?
        mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
        mysql_select_db($_SESSION['dbext']) or die(mysql_error()); 
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
    </div>
    </div>
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