<?
session_start();
include_once("config.func.php");
include_once("question.obj.php");
function resetnumbers($uuidr){
	global $db_host, $db_user, $db_password, $db_name;
	mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
	mysql_select_db($_SESSION['dbext']) or die(mysql_error()); 
	$query = mysql_query("SELECT * FROM `".$uuidr."` ORDER BY id ASC;");
	$number = mysql_num_rows($query);
	$counter = 1;
	while($info = mysql_fetch_array($query)){
		$queryy = mysql_query("UPDATE `".$uuidr."` SET id='$counter' WHERE id='$info[id]'");
		$counter++;
	}
	mysql_close();
}
if(isset($_SESSION["is_admin"])){
	if(isset($_POST['submit'])){
		$submit = $_POST['submit'];
		if($submit=="Change"){
			if(isset($_POST['uuid']) && isset($_POST['status'])){
				mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
				mysql_select_db($_SESSION['dbext']) or die(mysql_error()); 
				$uuid = mysql_real_escape_string($_POST['uuid']);
				$status = mysql_real_escape_string($_POST['status']);
				$query = mysql_query("UPDATE quizes SET status='$status' WHERE uuid='$uuid'");
				mysql_close();
				header('Location: ' . $_SERVER['HTTP_REFERER']);
			} else {
				header('Location: ' . $_SERVER['HTTP_REFERER']);
			}
		} else if($submit=="Add"){
			$mysqli = new mysqli($db_host, $db_user, $db_password);
			$mysqli -> select_db($_SESSION['dbext']);
			$uuid = $mysqli -> real_escape_string($_POST['uuid']);
			$type = $_POST['type'];
			if(intval($type)==0){
				$question = $_POST['question'];
				$numOfQuestions = intval($_POST['answerNum']);
				$pointValue = intval($_POST['points']);
				$answer = $_POST['answer'];
				$type = $_POST['type'];
				for($x = 0; $x < $numOfQuestions; $x++){
					$ansArray[$x] = $_POST[$x.'text'];
				}
				$object = new PolyQuestion($type, $pointValue);
				$object -> setAttributesMC($question, $ansArray, $answer);
			}
			if(intval($type)==1){
				$question = $_POST['question'];
				$pointValue = intval($_POST['points']);
				$type = $_POST['type'];
				$object = new PolyQuestion($type, $pointValue);
				$object -> setAttributesFREERESPONSE($question);
			}
			if(intval($type)==2){
				$numOfQuestions = intval($_POST['answerNum']);
				$type = $_POST['type'];
				$question = $_POST['question'];
				$object = new PolyQuestion($type, 1);
				for($x = 0; $x <$numOfQuestions; $x++){
					$left[$x] = $_POST['left'.$x];
					$right[$x] = $_POST['right'.$x];
					$left_ans[$x] = $_POST['right'.$x];
				}
				$object->setAttributesMATCH($left, $right, $left_ans);
				$object->question = $question;
			}
			if(intval($type)==4){
				$question = $_POST['question'];
				$numOfQuestions = intval($_POST['answerNum']);
				$pointValue = intval($_POST['points']);
				$type = $_POST['type'];
				for($x = 0; $x < $numOfQuestions; $x++){
					$ansArray[$x] = new PolyAnswer($_POST[$x.'text'], intval($_POST[$x.'points']));
				}
				$object = new PolyQuestion($type, $pointValue);
				$object -> setAttributesXC($question, $ansArray);
			}
			if(intval($_POST['extracredit'])==1){
				$object -> setIsExtraCredit(true);
			}
			if(intval($_POST['extracreditdisplay'])==1){
				$object -> displayextracredit = true;
			}
			if(intval($_POST['includeCanvas'])==1){
				$object -> includeCanvas = true;
			}
			if($stmt = $mysqli -> prepare("INSERT INTO `".$uuid."` (`id`, `object`) VALUES (?,?);")){
				$stmt -> bind_param("is",$y =0, serialize($object));
				$stmt -> execute();
				$stmt -> close();
			} else {
				echo $mysqli->error;
			}
			$mysqli -> close();
			resetnumbers($uuid);
			header('Location: ../?p=managequiz&UUID='.$uuid.'');
		} else if($submit=="Delete"){
			if(isset($_POST['uuid']) && isset($_POST['num'])){
				mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
				mysql_select_db($_SESSION['dbext']) or die(mysql_error()); 
				$uuid = mysql_real_escape_string($_POST['uuid']);
				$num = mysql_real_escape_string($_POST['num']);
				$query = mysql_query("DELETE FROM `".$uuid."` WHERE id='$num'");
				mysql_close();
				resetnumbers($uuid);
				header('Location: ' . $_SERVER['HTTP_REFERER']);
			}
		} else if($submit=="Edit"){
			$mysqli = new mysqli($db_host, $db_user, $db_password);
			$mysqli -> select_db($_SESSION['dbext']);
			$uuid = $mysqli -> real_escape_string($_POST['uuid']);
			$type = $_POST['type'];
			if(intval($type)==0){
				$num = intval($_POST['num']);
				$ansNum = $_POST['answerNum'];
				for($x = 0; $x < $ansNum; $x++){
					$temparray[$x] = $_POST[$x.'text'];
				}
				$quest = new PolyQuestion(MULTIPLE, $_POST['points']);
				$quest->setAttributesMC($_POST['question'], $temparray, $_POST['answer']);
			}
			if(intval($type)==1){
				$num = intval($_POST['num']);
				$quest = new PolyQuestion(1, $_POST['points']);
				$quest->setAttributesFREERESPONSE($_POST['question']);
			}
			if(intval($type)==2){
				$num = intval($_POST['num']);
				$ansNum = $_POST['answerNum'];
				$question = $_POST['question'];
				for($x = 0; $x <$ansNum; $x++){
					$left[$x] = $_POST['left'.$x];
					$right[$x] = $_POST['right'.$x];
					$left_ans[$x] = $_POST['right'.$x];
				}
				$quest = new PolyQuestion(2, 1);
				$quest->setAttributesMATCH($left, $right, $left_ans);
				$quest->question = $question;
			}
			if(intval($type)==4){
				$num = intval($_POST['num']);
				$ansNum = $_POST['answerNum'];
				for($x = 0; $x < $ansNum; $x++){
					$ansArray[$x] = new PolyAnswer($_POST[$x.'text'], intval($_POST[$x.'points']));
				}
				$quest = new PolyQuestion(4, $_POST['points']);
				$quest -> setAttributesXC($_POST['question'], $ansArray);
			}
			if(intval($_POST['extracredit'])==1){
				$quest -> setIsExtraCredit(true);
			} else {
				$quest -> setIsExtraCredit(false);
			}
			if(intval($_POST['includeCanvas'])==1){
				$quest -> includeCanvas = true;
			}
			if(intval($_POST['extracreditdisplay'])==1){
				$quest -> displayextracredit = true;
			} else {
				$quest -> displayextracredit = false;
			}
			if($stmt = $mysqli -> prepare("UPDATE `".$uuid."` SET object=? WHERE id=?")){
				$stmt -> bind_param("si", serialize($quest), $num);
				$stmt -> execute();
				$stmt -> close();
			} else {
				echo $mysqli->error;
			}
			$mysqli -> close();
			resetnumbers($_POST['uuid']);
			header('Location: ' . $_SERVER['HTTP_REFERER']);
			//header('Location: ../?p=managequiz&UUID='.$uuid.'');
		}
	} else {
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
} else {
	header('Location: ' . $_SERVER['HTTP_REFERER']);
}
?>