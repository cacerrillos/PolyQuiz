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
  <div class="content">
  <h3><a href="?p=admin">The Core</a></h3>
    <h1>Quiz Management</h1>
<?
	if(isset($_SESSION["is_admin"])){
	?>
	<p>Welcome back admin!</p>

    <h3>Results</h3>
    <div id="seeresults" style="margin-left:20px">
    <?
	mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
	mysql_select_db($db_name) or die(mysql_error()); 
	$overall = mysql_query("SELECT * FROM quizes ORDER BY quizname ASC"); 
	while($overalldata = mysql_fetch_array($overall)){
		$house[0] = "North";
		$house[1] = "South";
		$house[2] = "East";
		$house[3] = "West";
		$house[4] = "Other";
		?>
        <?
		for($x = 0; $x <= 4; $x++){
			$dataquiz = mysql_query("SELECT * FROM results WHERE house='".$house[$x]."' AND quizuuid='".$overalldata['uuid']."' ORDER BY lastname ASC");
			if(mysql_num_rows($dataquiz)>0){
$tempString = preg_replace('/\s+/', '', $overalldata['uuid'].$house[$x]);
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
<form name="deleteAllResultsInQuiz" action="func/resultdelete.func.php" method="post">
<input type="hidden" name="uuid" value="<? echo $overalldata['uuid']; ?>">
<input type="hidden" name="house" value="<? echo $house[$x]; ?>">
<img id="captcha<? echo $tempString; ?>" src="/securimage/securimage_show.php" alt="CAPTCHA Image" /><br>
<input type="text" name="captcha_code<? echo $tempString; ?>" size="10" maxlength="6" /><br>

	<a href="#" onclick="document.getElementById('captcha<? echo $tempString; ?>').src = '/securimage/securimage_show.php?' + Math.random(); return false">[ Different Image ]</a>
<br><input type="submit" value="Delete this Quiz/House">
</form>
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
                
                
                <? //<td><a href="?postquizadmin" target="_blank"><? echo $perhousedata['id'];
				?>
				<? //</a></td>
				?>
                <td><a href="func/resultdelete.func.php?uuid=<? echo $perhousedata['id']; ?>&delete=1"><font color="#FF0000">Delete</font></a></td>
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
	}
	?>
    <p><a href="func/resultdelete.func.php?uuid=all"><font color="#FF0000">Delete All</font></a></p>
    </div>
    <p><a href="func/admin.logout.php">Logout</a></p>
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
    <!-- end .content --></div>

