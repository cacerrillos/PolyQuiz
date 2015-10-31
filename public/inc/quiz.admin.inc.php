<div class="container">
	<div class="row">
		<div class="12u">
			<paper-material>
				<h2>Quiz Management</h2>
				<h3>Edit Quizzes</h3>
				<?
				if(isset($_SESSION["is_admin"])){
				?>
				<h3 style="margin-bottom:0px;">Create New Quiz</h3>
				<form id="newquiz" action="func/newquiz.func.php" method="post">
				Quiz Name: <input type="text" name="quizname"><br>
				<input type="radio" name="status" value="1" checked> Enabled<br>
				<input type="radio" name="status" value="0" > Disabled<br>
				<input type="submit" name="submit" value="Create">
				</form>
				<br />
				<h3>Copy All Questions</h3>
				<form id="copy" action="func/copy.func.php" method="post">
				From: <select name="fromuuid">
				<option value=""></option>
				<?
				mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
				mysql_select_db($db_name) or die(mysql_error()); 
				$data = mysql_query("SELECT * FROM quizzes WHERE owner='".$_SESSION['dbext']."' ORDER BY quizname ASC;");
				if(mysql_num_rows($data)>0) {
					while($info = mysql_fetch_array($data)){
						?>
						<option value="<? echo $info['uuid']; ?>"><? echo $info['quizname']; ?></option>
						<?
					}
				}
				?>
				</select><br />
				To: <select name="touuid">
				<option value=""></option>
				<?
				mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
				mysql_select_db($db_name) or die(mysql_error()); 
				$data = mysql_query("SELECT * FROM quizzes WHERE owner='".$_SESSION['dbext']."' ORDER BY quizname ASC;");
				if(mysql_num_rows($data)>0) {
					while($info = mysql_fetch_array($data)){
						?>
						<option value="<? echo $info['uuid']; ?>"><? echo $info['quizname']; ?></option>
						<?
					}
				}
				?>
				 </select>
				 <input type="submit" name="submit" value="Copy!" />
				 </form>
				 <h3 style="margin-bottom:0px;">Create Quiz from Backup</h3>
				 Coming soon!
					<h3>Manage Quiz</h3>
					<table>
					<?
					mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
					mysql_select_db($db_name) or die(mysql_error()); 
					$data = mysql_query("SELECT * FROM quizzes WHERE owner='".$_SESSION['dbext']."' ORDER BY quizname ASC;");
					if(mysql_num_rows($data)==0){
						echo "You have no quizzes!";
					} else {
						?>
						<tr>
							<td>Quiz Name</td>
							<td>PolyQuiz Backup</td>
							<td>Delete</td>
						</tr>
						<?
					}
					while($info = mysql_fetch_array($data)){
						?>
						<tr>
							<td><a href='?p=managequiz&UUID=<? echo $info['uuid']; ?>'><? echo $info['quizname']; ?></a></td>
							<td><a href='func/export.func.php?uuid=<? echo $info['uuid']; ?>' target="_blank">Download Quiz</a></td>
							<td><a href='func/quiz.admin.php?delete=<? echo $info['uuid']; ?>' style="color:#F00;">Delete</a></td>
						</tr>
					<?
					}
					?>
					</table>
					<br><br>
				<?
				}
				?>
			</paper-material>
		</div>
	</div>
</div>