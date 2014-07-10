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
													<h2>Results & Grading</h2>
													<span class="byline"><h3>Free Response Grading</h3></span>
												</header>
    <?
	if(isset($_SESSION["is_admin"])){
	?>
    <? if(!isset($_GET['uuid'])){
		?>
    <div id="seeresults" style="margin-left:20px">
    <?
	mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
	mysql_select_db($db_name) or die(mysql_error()); 
	$overall = mysql_query("SELECT * FROM quizzes WHERE owner='".$_SESSION['dbext']."' ORDER BY quizname ASC"); 
	$none[0]=false;
	$none[1]=false;
	$none[2]=false;
	$none[3]=false;
	$none[4]=false;
	while($overalldata = mysql_fetch_array($overall)){
		$house[0] = "North";
		$house[1] = "South";
		$house[2] = "East";
		$house[3] = "West";
		$house[4] = "Other";
		?>
        <?
		for($x = 0; $x <= 4; $x++){
			$dataquiz = mysql_query("SELECT * FROM results WHERE house='".$house[$x]."' AND quizuuid='".$overalldata['uuid']."' AND frgraded='0' AND owner='".$_SESSION['dbext']."' ORDER BY lastname ASC");
			
			if(mysql_num_rows($dataquiz)>0){
			echo "<h3>".$overalldata['quizname']." - ".$house[$x]."</h3>";	
			?>
            <table cellpadding="5px" border="2px">
            <tr>
                <? //<td>#</td> 
				?>
        		<td>Last</td>
       			<td>First</td>
                <td>FR Score</td>
                <td>FR Psbl.</td>
                <td>FR Per.</td>
                <td>Grade FR</td>
                <? // <td>Date</td> ?>
       			<td>Date & Time</td>
       			<? //<td>IP</td>
               // <td>Session</td>
			   ?>
                </tr>
            <?
			while($perhousedata = mysql_fetch_array($dataquiz)){
				if($perhousedata['flag']=="yes"){
					?>
                    <tr bgcolor="#FF9900">
                    <?
				} else {
					?>
                    <tr>
                    <?
				}
				?>
                
                
                <? //<td><a href="?postquizadmin" target="_blank"><? echo $perhousedata['id'];
				?>
				<? //</a></td>
				?>
        		<td><a href="?p=postquizadmin&uuid=<? echo $perhousedata['id']; ?>" target="_blank"><? echo $perhousedata['lastname']; ?></a></td>
       			<td><a href="?p=postquizadmin&uuid=<? echo $perhousedata['id']; ?>" target="_blank"><? echo $perhousedata['firstname']; ?></a></td>
                <td><?
				if($perhousedata['frpossible']>0){
					echo $perhousedata['frscore']; 
				} else {
					echo "N/A";
				}
				?></td>
                <td><?
				if($perhousedata['frpossible']>0){
					echo $perhousedata['frpossible']; 
				} else {
					echo "N/A";
				}
				 ?></td>
                <td><?
				if($perhousedata['frpossible']>0){
				 echo round((($perhousedata['frscore']/$perhousedata['frpossible'])*100), 2);
				 } else {
					echo "N/A";
				}
				 ?></td>
                <td><?
                if($perhousedata['frgraded']==0){
				?>
                <a href="?p=gradefr&uuid=<? echo $perhousedata['id'];?>" target="_blank">Grade</a>
                <?	
				} else {
					if($perhousedata['frpossible']>0){
				?>
                <font color="#00FF00">Graded</font>
                <?	
					} else {
					?>
                    <font color="#00FF00">N/A</font>
                    <?	
					}
				}
				?></td>
                <? /* <td><? echo $perhousedata['datestamp']; ?></td> */ ?>
       			<td><? echo date("Y-M-d-D H:i", $perhousedata['timestamp']); ?></td>
       			<? //<td><? echo $perhousedata['ip']; </td> ?>
                <? //<td><? echo $perhousedata['session'];</td> ?>
                <?
			}
			?>
            </table>
            <?
		} else {
			$none[$x]=true;
		}
		
		}
		?>
        <?
	}
	?>
    <?
	
	if($none[0] && $none[1] && $none[2] && $none[3] && $none[4]){
		
?>
		 <h3>All free response questions have been graded!</h3>
<?
	}
	?>
    </div>
    <?
	}
	?>
    <?
	if(isset($_GET['uuid'])){
		$mysqli = new mysqli($db_host, $db_user, $db_password);
		$mysqli -> select_db($db_name);
		if(mysqli_connect_errno()){
			echo "Connection Failed: " . mysqli_connect_errno();
			exit();
		}
		$numrows = 0;
		if($stmt = $mysqli -> prepare("SELECT * FROM results WHERE id=? AND frgraded=0 AND owner=?")){
			$stmt -> bind_param("ss", $_GET['uuid'], $_SESSION['dbext']);
			$stmt -> execute();
			$stmt -> store_result();
			$numrows = $stmt->num_rows;
			$stmt -> bind_result($info['id'], $info['firstname'], $info['lastname'], $info['quizuuid'], $info['rawscore'], $info['possiblescore'], $info['percentage'], $info['datestamp'], $info['timestamp'], $info['ip'], $info['house'], $info['session'], $info['object'], $info['flag'], $info['frscore'], $info['frpossible'], $info['frgraded'], $info['owner']);
			$stmt -> fetch();
			$stmt -> close();
		}
		$mysqli -> close();
		if($numrows>0){
			$quiz = unserialize($info['object']);
			?>
            <h3><? echo $quiz->name; ?></h3>
            <h4><? echo $info['lastname'].", ".$info['firstname']; ?></h4>
            <form name="grading<? echo $_GET['uuid']; ?>" action="func/gradingfr.func.php" method="post">
            <input type="hidden" name="uuid" value="<? echo $_GET['uuid']; ?>">
            <?
			for($x = 0; $x < $quiz->numberofquestions; $x++){
				if($quiz->questions[$x]->type==1){
					?>
                    <div class="question">
                    <?
					echo ($x+1).") ".$quiz->questions[$x]->question;
					?>
                    </div>
                    <div style=" outline:solid medium #000; margin-left:40px">
                    <?
					echo $quiz->questions[$x]->response."<br>";
					?>
                    </div>
                   <br><p style="margin-left:40px; margin-top:5px">Score:<select name="score<? echo $x; ?>">
                    <?
					for($y = 0; $y <= $quiz->questions[$x]->points; $y++){
						?>
                        <option value="<? echo $y; ?>"><? echo $y; ?></option>
                        <?
					}
					?>
                    </select>
                    </p>
                    <?
				}
			}
			?>
            <br>
            <input type="submit" value="Submit" name="submit" style="margin-left:20px"><br><br>
            </form>
            <?
		} else {
		?>
        <h3>This quiz's free responses have already been graded!</h3>
        <?	
		}
	}
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
