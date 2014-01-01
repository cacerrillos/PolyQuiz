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
													<h2>The Core</h2>
												</header>
                                                
												<footer>
                                                <?
												if(isset($_SESSION["is_admin"])){
												?>
                                                    <a href="func/admin.logout.php" class="button alt fa fa-lock">Logout</a>
												<?
                                                } else {  
                                                ?>
                                                    <form id="adminlogin" action="func/admin.login.php" method="post">
                                                    <table>
                                                    <tr>
                                                    	<td><b>User:</b></td>
                                                    	<td><input type="text" name="user"></td>
                                                    </tr>
                                                    <tr>
                                                   		<td>Pass:</td>
                                                    	<td><input type="password" name="pass"></td>
                                                    </tr>
                                                    <tr>
                                                    	<td></td>
                                                    	<td><input type="submit" name="submit" value="Login" class="button alt fa fa-lock"></td>
                                                    </tr>
                                                    </table>
                                                    </form>
                                                <?
                                                }
                                                ?>
												</footer>
											</section>
								
									</div>
								</div>
								<div class="8u skel-cell-important">
									<div id="content">

										<!-- Content -->
									
											<article>
												<header class="major">
													<h2>Admin Home</h2>
                                                    <?
													if(!isset($_SESSION["is_admin"])){
													?>
													<span class="byline">Please Log-In.</span>
                                                    <?
                                                    } else {
                                                    ?>
                                                    <span class="byline">Welcome back admin!</span>
                                                    <?
													}
													?>
												</header>
                                            	<?
												if(isset($_SESSION["is_admin"])){
												?>
												<p>
												<a href="?p=quizadmin">Quiz Management</a><br>
												<h3 style="margin-bottom:0;">Results & Grading</h3>
												<a href="?p=results" style="margin-left:20px">Quiz Results</a><br />
												<a href="?p=gradefr" style="margin-left:20px">Pending Free Response Grading</a>
												<h3 style="margin-bottom:0;">Sessions</h3>
												<a href="?p=pendingsessions" style="margin-left:20px">Quizzes In Progress</a><br /><br />
												<div id="closed" style="margin-left:20px">
													<a href="#" onclick="showElement('open'); hideElement('closed'); return false;"><img src="inc/icon_arrow_right.gif" />View Sessions</a>
												</div>
												<div id="open" style="display: none; margin-left:20px;">
													<a href="#" onclick="showElement('closed'); hideElement('open'); return false;"><img src="inc/icon_arrow_down.gif" />Hide Sessions</a><br />
													<?
													mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
													mysql_select_db($db_name) or die(mysql_error());
													$data = mysql_query("SELECT * FROM sessions ORDER BY date ASC;");
													$numrows = mysql_num_rows($data);
													?>
													<table cellpadding="2px" border="2px">
													<tr>
													<td>Name</td>
													<td>ID</td>
													<td>Key</td>
													<td>Date</td>
													<td>Quiz</td>
													<td>House</td>
													<td>Status</td>
													<td>Delete</td>
													</tr>
													<?
													while($info = mysql_fetch_array($data)){
														$quizuuid = $info['quiz'];
														$data1 = mysql_query("SELECT * FROM quizes WHERE uuid='$quizuuid'");
														$info1 = mysql_fetch_array($data1);
														?>
														<tr>
														<td><? echo $info['sessionname']; ?></td>
														<td><? echo $info['uuid'];?></td>
														<td><? echo $info['key']; ?></td>
														<td><? echo $info['date']; ?></td>
														<td><? echo $info1['quizname']; ?></td>
														<td><? echo $info['house']; ?></td>
														<td><? if($info['status']==1){ echo "Open"; } else { echo "Closed"; }?></td>
														<td><a href="func/sessiondelete.func.php?uuid=<? echo $info['uuid'];?>"><font color="#FF0000">Delete</font></a></td>
														<tr>
														<?
													}
												?>
												</table>
												<a href="func/sessiondelete.func.php?uuid=all"><font color="#FF0000">Delete All Sessions</font></a>
												</div>
												<br /><br />
												<h3>Create New Session</h3>
												<div id="createsess" style="margin-left:20px">
												<form id="newsession" action="func/ses.func.php" method="post">
												<input type="hidden" name="date" value=" <? echo date("Y-m-d"); ?>" />
												Session Name: <input type="text" name="sessionname" /><br />
												House: <select name="house" size="1">
															<option value="North">North</option>
															<option value="South">South</option>
															<option value="East">East</option>
															<option value="West">West</option>
															<option value="Other">Other</option>
															</select><br />
												Quiz: <select name="quiz" size="1">
												<?
													mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
													mysql_select_db($db_name) or die(mysql_error());
													$data = mysql_query("SELECT * FROM quizes WHERE status='1';");
													$numrows = mysql_num_rows($data);
													while($info = mysql_fetch_array($data)){
														?>
														<option value="<? echo $info['uuid'];?>"><? echo $info['quizname']; ?></option>
														<?
													}
												?>
															</select><br />
														   
												<input type="radio" name="status" value="1" checked /> Open<br />
												<input type="radio" name="status" value="0" /> Closed<br />
												<input type="submit" name="submit" value="Create" />
												</form>
												</div>
												<h3>Modify Session</h3>
												<div id="modsess" style="margin-left:20px">
												<form id="modsess" action="func/ses.func.php" method="post">
												Session: <select name="uuid" size="1">
												<?
												mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
												mysql_select_db($db_name) or die(mysql_error());
												$data = mysql_query("SELECT * FROM sessions ORDER BY date ASC;");
												$numrows = mysql_num_rows($data);
												while($info = mysql_fetch_array($data)){
													$quizuuid = $info['quiz'];
													$data1 = mysql_query("SELECT * FROM quizes WHERE uuid='$quizuuid'");
													$info1 = mysql_fetch_array($data1);
													?>
													<option value="<? echo $info['uuid'];?>"><? echo $info['date']." - ".$info['sessionname']." - ".$info1['quizname']." - ".$info['house'];?> - <? if($info['status']==1){ echo "Open"; } else { echo "Closed"; }?></option>
													<?
												}
												?>
															</select><br />
												<input type="submit" name="submit" value="Open" /><input type="submit" name="submit" value="Close" />
												</form>
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