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
					<div class="row not-small">
						<div class="12u">
							<paper-material>
								<div class="row">
									<div class="4u">
										<div class="row">
											<div class="12u">
												Session Name
											</div>
											<div class="-2u 10u">
												Quiz Name
											</div>
										</div>
									</div>
									<div class="2u">
										<div class="row">
											<div class="12u">Id</div>
											<div class="12u">Key</div>
										</div>
									</div>
									<div class="2u">
										Date
									</div>
									<div class="1u">
										House
									</div>
									<div class="3u">
										
									</div>
								</div>
							</paper-material>
						</div>
					</div>
					<div class="row only-small">
						<div class="12u">
							<paper-material>
								<div class="row">
									<div class="4u">
										<div class="row">
											<div class="12u">
												Session Name
											</div>
											<div class="-2u 10u">
												Quiz Name
											</div>
										</div>
									</div>
									<div class="2u">
										<div class="row">
											<div class="12u">Id</div>
											<div class="12u">Key</div>
										</div>
									</div>
									<div class="2u">
										DDate
									</div>
									<div class="1u">
										House
									</div>
									<div class="1u">
										
									</div>
								</div>
							</paper-material>
						</div>
					</div>
					<?
					while($info = mysql_fetch_array($data)){
						$quizuuid = $info['quiz'];
						$data1 = mysql_query("SELECT * FROM quizzes WHERE uuid='$quizuuid' AND owner='".$_SESSION['dbext']."'");
						$info1 = mysql_fetch_array($data1);
						?>
						<div class="row not-small">
							<div class="12u">
								<paper-material>
									<div class="row">
										<div class="4u">
											<div class="row">
												<div class="12u">
													<? echo $info['sessionname']; ?>
												</div>
												<div class="-2u 10u">
													<? echo $info1['quizname']; ?>
												</div>
											</div>
										</div>
										<div class="2u">
											<div class="row">
												<div class="12u"><? echo $info['uuid'];?></div>
												<div class="12u"><? echo $info['key']; ?></div>
											</div>
										</div>
										<div class="2u">
											<? echo $info['date']; ?>
										</div>
										<div class="1u">
											<? echo $info['house']; ?>
										</div>
										<div class="3u">
											<admin-session-action-buttons sessionid='<? echo $info['uuid']; ?>' <? if($info['status']==1) echo "sessionopen"; ?> <? if($info['score']==1) echo "sessionshow"; ?>></admin-session-action-buttons>
										</div>
									</div>
								</paper-material>
							</div>
						</div>
						<div class="row only-small">
							<div class="12u">
								<paper-material>
									<div class="row">
										<div class="5u 4u(xsmall)">
											<div class="row">
												<div class="12u">
													<? echo $info['sessionname']; ?>
												</div>
												<div class="-2u 10u">
													<? echo $info1['quizname']; ?>
												</div>
											</div>
										</div>
										<div class="2u">
											<div class="row">
												<div class="12u"><? echo $info['uuid'];?></div>
												<div class="12u"><? echo $info['key']; ?></div>
											</div>
										</div>
										<div class="2u 3u(xsmall)">
											<? echo $info['date']; ?>
										</div>
										<div class="1u">
											<? echo $info['house']; ?>
										</div>
										<div class="2u">
											<admin-session-action-buttons sessionid='<? echo $info['uuid']; ?>' <? if($info['status']==1) echo "sessionopen"; ?> <? if($info['score']==1) echo "sessionshow"; ?>></admin-session-action-buttons>
										</div>
									</div>
								</paper-material>
							</div>
						</div>
						<?
					}
				?>
				<?
				}
				?>
			</paper-material>
		</div>
	</div>
</div>
