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
													<span class="byline"><h3>Results</h3></span>
												</header>
<?
	if(isset($_SESSION["is_admin"])){
	?>
    <div id="seeresults" style="margin-left:20px">
    <?
	$mysqli = new mysqli($db_host, $db_user, $db_password);
	$mysqli -> select_db($db_name);
	$quizUUIDS = array();
	if($stmt = $mysqli->prepare("SELECT quizuuid FROM `results` WHERE owner = ?;")) {
		$stmt->bind_param("s", $_SESSION['dbext']);
		$stmt->execute();
		$stmt->bind_result($thisQuizUUID);
		$stmt->store_result();
		while($stmt->fetch()) {
			array_push($quizUUIDS, $thisQuizUUID);
		}
		$uniqueQuizUUIDs = array_unique($quizUUIDS);
		sort($uniqueQuizUUIDs);
		
		$stmt->close();
	} else {
		echo $mysqli->error;	
	}
	$info = array(array(), array());
	for($c = 0; $c < count($uniqueQuizUUIDs); $c++) {
		array_push($info[0], $uniqueQuizUUIDs[$c]);
		if($stmt = $mysqli->prepare("SELECT quizname FROM quizzes WHERE owner=? AND uuid=?;")) {
			$stmt->bind_param("ss", $_SESSION['dbext'], $uniqueQuizUUIDs[$c]);
			$stmt->bind_result($thisName);
			$stmt->execute();
			$stmt->store_result();
			$stmt->fetch();
			if(isset($thisName)) {
				array_push($info[1], $thisName);
			} else {
				array_push($info[1], "Unknown Quiz Name");	
			}
			$stmt->close();
		} else {
			echo $mysqli->error;
		}
	}
	array_multisort($info[1], $info[0]);
	$house[0] = "North";
	$house[1] = "South";
	$house[2] = "East";
	$house[3] = "West";
	$house[4] = "Other";
	for($c = 0; $c < count($info[0]); $c++) {
		for($x = 0; $x <= 4; $x++){
			$dataquiz = mysql_query("SELECT * FROM results WHERE house='".$house[$x]."' AND quizuuid='".$info[0][$c]."' AND owner='".$_SESSION['dbext']."' ORDER BY lastname ASC");
			if(mysql_num_rows($dataquiz)>0){
$tempString = preg_replace('/\s+/', '', $info[0][$c].$house[$x]);
?>

<?
			echo "<h3 style='display:inline;'>".$overalldata['quizname']." - ".$house[$x]."</h3>";	
			?>
<div id="closed<? echo $tempString; ?>" style="margin-left:20px">
        <a href="#" onclick="showElement('open<? echo $tempString; ?>'); hideElement('closed<? echo $tempString; ?>'); return false;"><img src="inc/icon_arrow_right.gif" />View <? echo $overalldata['quizname']." - ".$house[$x]; ?> Results</a>
    </div>
<div id="open<? echo $tempString; ?>" style="display: none; margin-left:20px;">
        <a href="#" onclick="showElement('closed<? echo $tempString; ?>'); hideElement('open<? echo $tempString; ?>'); return false;"><img src="inc/icon_arrow_down.gif" />Hide <? echo $overalldata['quizname']." - ".$house[$x]; ?> Results</a><br />
           <br><br>
           <div class="simple_overlay" id="<? echo $overalldata['uuid'].$house[$x]; ?>" style="width:250px; min-height:75px;">
            		<p style="margin-left:10px; margin-top:10px;">
                    <center>
<form name="deleteAllResultsInQuiz" action="func/resultdelete.func.php" method="post" style="margin-left:10px; margin-bottom:10px;">
<input type="hidden" name="uuid" value="<? echo $overalldata['uuid']; ?>">
<input type="hidden" name="house" value="<? echo $house[$x]; ?>">
<img id="captcha<? echo $tempString; ?>" src="/securimage/securimage_show.php" alt="CAPTCHA Image" /><br>
<input type="text" name="captcha_code<? echo $tempString; ?>" size="10" maxlength="6" /><br>

	<a href="#" onclick="document.getElementById('captcha<? echo $tempString; ?>').src = '/securimage/securimage_show.php?' + Math.random(); return false">[ Different Image ]</a>
<br><input type="submit" value="Delete this Quiz/House">
</form>
</center>
</p>
</div>
<a href='func/export.func.php?quizuuid=<? echo $overalldata['uuid']; ?>' target="_blank">Download Quiz Results (All Houses)</a>
<a rel="#<? echo $overalldata['uuid'].$house[$x]; ?>"><font color="#FF0000">Delete Entire House</font></a>
<? /*
//<h4><a href="func/resultdelete.func.php?uuid=<? echo $overalldata['uuid']; ?>&house=<? echo $house[$x]?>&delete=1"><font color="#FF0000">Delete This Quiz/House</font></a></h4>
*/
?>
            <table cellpadding="5px" border="2px">
            <tr>
                <? //<td>#</td> 
				?>
                <td>Delete</td>
        		<td>Last</td>
       			<td>First</td>
        		<td>Raw Score</td>
        		<td>Psbl. Score</td>
        		<td>Per.</td>
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
                <div class="simple_overlay" id="<? echo $perhousedata['id']; ?>" style="width:175px; min-height:75px;">
            		<p style="margin-left:10px;">
                    <center>
                    	<h1 style="color:WHITE; padding-bottom:0px; margin-bottom:0px;">Are you sure?</h1>
                    	<a href="func/resultdelete.func.php?uuid=<? echo $perhousedata['id']; ?>&delete=1"><font color="#FF0000">Delete</font></a>
                    </center>
                    </p>
                </div>
                
                <? //<td><a href="?postquizadmin" target="_blank"><? echo $perhousedata['id'];
				?>
				<? //</a></td>
				?>
                <td><a rel="#<? echo $perhousedata['id']; ?>"><font color="#FF0000">Delete</font></a></td>
        		<td><a href="?p=postquizadmin&uuid=<? echo $perhousedata['id']; ?>" target="_blank"><? echo $perhousedata['lastname']; ?></a></td>
       			<td><a href="?p=postquizadmin&uuid=<? echo $perhousedata['id']; ?>" target="_blank"><? echo $perhousedata['firstname']; ?></a></td>
        		<td><? echo $perhousedata['rawscore']; ?></td>
        		<td><? echo $perhousedata['possiblescore']; ?></td>
        		<td><? echo $perhousedata['percentage']; ?></td>
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
</div>
            <?
		}
		}
		?>
        <?
	} // End while
	?>
    <div class="simple_overlay" id="all" style="width:175px; min-height:75px;">
            		<p style="margin-left:10px;">
                    <center>
                    	<h1 style="color:WHITE; padding-bottom:0px; margin-bottom:0px;">Are you sure?</h1>
                    	<a href="func/resultdelete.func.php?uuid=all"><font color="#FF0000">Delete All</font></a>
                    </center>
                    </p>
                </div>
                <br /><br />
    <p><a rel="#all"><font color="#FF0000">Delete All</font></a></p>
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