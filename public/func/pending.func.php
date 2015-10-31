<?
session_start();
include_once("genquiz.func.php");
include_once("config.func.php");
if(isset($_GET['uuid']) && isset($_SESSION["is_admin"])){
	mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
	mysql_select_db($db_name) or die(mysql_error()); 
	$uuid = mysql_real_escape_string($_GET['uuid']);
	if($uuid=="11223344"){
		$query = mysql_query("DELETE FROM polysessions WHERE owner='".$_SESSION['dbext']."'");
	} else {
		$query = mysql_query("DELETE FROM polysessions WHERE id='$uuid' AND owner='".$_SESSION['dbext']."'");
	}
	mysql_close();
	header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
	if(isset($_SESSION["is_admin"])){
		?>
		<table cellpadding="2px" border="2px">
			<tr>
				<td>Last Name</td>
				<td>First Name</td>
				<td>Quiz</td>
				<td>House</td>
				<td>Restore Id</td>
				<td>Restore Key</td>
				<td>Date</td>
				<td>Delete</td>
			</tr>
		<?
		mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
		mysql_select_db($db_name) or die(mysql_error()); 
		$overall = mysql_query("SELECT * FROM polysessions WHERE owner = '".$_SESSION['dbext']."'"); 
		while($overalldata = mysql_fetch_array($overall)){
			$tempquizses = unserialize($overalldata['data']);
			$tempfname = $tempquizses['firstname'];
			$templname = $tempquizses['lastname'];
			$tempquiz = $tempquizses['quiz'];
			
			?>
			<tr>
				<td><? echo $templname; ?></td>
				<td><? echo $tempfname; ?></td>
				<td><? echo $tempquiz->name;?></td>
				<td><? echo $tempquiz->getHouse();?></td>
				<td><? echo $overalldata['recoveryid'];?></td>
				<td><? echo $overalldata['recoverykey'];?></td>
				<td><? echo date("Y-M-d-D H:i", $overalldata['date']); ?></td>
				<td><a href="func/pending.func.php?uuid=<? echo $overalldata['id']; ?>">Delete</a></td>
			</tr>
			<?
		}
		?>
		</table>
		<h3>
			<a href="func/pending.func.php?uuid=11223344">Delete All</a>
		</h3>
		<br />
		<h3># Completed</h3>
		<?
		$mysqli = new mysqli($db_host, $db_user, $db_password);
		$mysqli -> select_db($db_name);
		if($stmt = $mysqli->prepare("SELECT value FROM stats WHERE id=?;")){
			$stmt -> bind_param("s", $_SESSION['dbext']);
			$stmt -> execute();
			$stmt -> bind_result($count);
			$stmt -> store_result();
			$stmt -> fetch();
			$stmt -> close();
			$count = intval($count);
			echo "<font size='48'>$count</font>";
		} else {
			echo $mysqli->error;
		}
	} else {
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
}
?>