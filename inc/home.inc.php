<?
unset($_SESSION["firstname"]);
unset($_SESSION["lastname"]);
session_unset();
?>

  <div class="content">
    <h1>Home</h1>
    <p>Take a quiz!</p>
    <div id="quizz" style="margin-left:20px">
    <?
//	var_dump($_SESSION);
	mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
	mysql_select_db($db_name) or die(mysql_error()); 
	$data = mysql_query("SELECT * FROM quizes ORDER BY quizname DESC;");
	if(mysql_num_rows($data)==0){
		echo "Sorry, no quizes available!";
	}
	if($use==true){
		?>
        <a href="?p=takequiz">Take a Quiz!</a>
        <?
	} else {
		while($info = mysql_fetch_array($data)){
			if($info['status']==1){
				echo "<a href='?p=takequiz&UUID=".$info['uuid']."'>".$info['quizname']."</a><br>";
			}
			
		}
	}
	?>
    </div>
    <!-- end .content --></div>