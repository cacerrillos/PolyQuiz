<div class="container">
	<div class="row">
		<div class="12u">
			<paper-material>
				<h2>Session Management</h2>
			</paper-material>
		</div>
	</div>
	<div class="row">
		<div class="12u">
			<paper-material>
				<?
				if(isset($_SESSION["is_admin"])){
				?>
				<h3>Create New Session</h3>
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
					$data = mysql_query("SELECT * FROM quizzes WHERE status='1' AND owner='".$_SESSION['dbext']."';");
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
			</paper-material>
		</div>
	</div>
	<div class="row">
		<div class="12u">
			<paper-material>
				<h3>Modify Session</h3>
					<?
					mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
					mysql_select_db($db_name) or die(mysql_error());
					$data = mysql_query("SELECT * FROM sessions WHERE owner='".$_SESSION['dbext']."' ORDER BY date ASC;");
					$numrows = mysql_num_rows($data);
					?>
					<table class="sessionstable">
					<tr>
					<td>Name</td>
					<td>ID</td>
					<td>Key</td>
					<td>Date</td>
					<td>Quiz</td>
					<td>House</td>
					<td>Status</td>
					<td>Show Scores</td>
					<td>Delete</td>
					<td>Take</td>
					</tr>
					<?
					while($info = mysql_fetch_array($data)){
						$quizuuid = $info['quiz'];
						$data1 = mysql_query("SELECT * FROM quizzes WHERE uuid='$quizuuid' AND owner='".$_SESSION['dbext']."'");
						$info1 = mysql_fetch_array($data1);
						?>
						<tr>
						<td><? echo $info['sessionname']; ?></td>
						<td><? echo $info['uuid'];?></td>
						<td><? echo $info['key']; ?></td>
						<td><? echo $info['date']; ?></td>
						<td><? echo $info1['quizname']; ?></td>
						<td><? echo $info['house']; ?></td>
						<td>
						<? if($info['status']==1){
								?>
								Open (<a href="func/ses.func.php?uuid=<? echo $info['uuid']; ?>&action=close">Close</a>)
								<?
							} else {
								?>
								Closed (<a href="func/ses.func.php?uuid=<? echo $info['uuid']; ?>&action=open">Open</a>)
								<?
							}
						?>
						</td>
						<td>
						<? if($info['score']==1){
								?>
								Show Scores (<a href="func/ses.func.php?uuid=<? echo $info['uuid']; ?>&action=dontshow">Don't Show</a>)
								<?
							} else {
								?>
								Don't Show (<a href="func/ses.func.php?uuid=<? echo $info['uuid']; ?>&action=show">Show Scores</a>)
								<?
							}
						?>
						</td>
						<td><a href="func/sessiondelete.func.php?uuid=<? echo $info['uuid'];?>"><font color="#FF0000">Delete</font></a></td>
						<td>Take</td>
						<tr>
						<?
					}
				?>
				</table>
				<a href="func/sessiondelete.func.php?uuid=all"><font color="#FF0000">Delete All Sessions</font></a>
				<?
				}
				?>
			</paper-material>
		</div>
	</div>
</div>
