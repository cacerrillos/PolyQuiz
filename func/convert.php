<?
include("question.obj.php");
if(isset($_POST['submit'])){
	mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
	mysql_select_db($db_name) or die(mysql_error());
	$uuid = mysql_real_escape_string($_POST['uuid']);
	$query1 = mysql_query("DROP TABLE IF EXISTS `".mysql_real_escape_string($uuid)."-converted"."`;");
		$query2 = mysql_query(
		"CREATE TABLE `".mysql_real_escape_string($uuid)."-converted"."` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` text,
  `images` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;"
);
	$data = mysql_query("SELECT * FROM ".$uuid.";");
	$counter = 0;
	while($info = mysql_fetch_array($data)){
		$question = $info['question'];
		$ansArray[0] = $info['a'];
		$ansArray[1] = $info['b'];
		$ansArray[2] = $info['c'];
		$ansArray[3] = $info['d'];
		$answerString = $info['answer'];
		if($answerString=="a" || $answerString=="A"){
			$answer = 0;
		} else if($answerString=="b" || $answerString=="B"){
			$answer = 1;
		} else if($answerString=="c" || $answerString=="C"){
			$answer = 2;
		} else if($answerString=="d" || $answerString=="D"){
			$answer = 3;
		}
		
		$images = $info['images'];
			$mysqli = new mysqli($db_host, $db_user, $db_password);
			$mysqli -> select_db($db_name);
			
			$pointValue = 1;
			$type = 0;

			$object = new PolyQuestion($type, $pointValue);
			$object -> setAttributesMC($question, $ansArray, $answer);
			//var_dump($object);
			if($stmt = $mysqli -> prepare("INSERT INTO `".$uuid."-converted"."` (`id`, `object`,`images`) VALUES (?,?,?);")){
				$stmt -> bind_param("iss",$y =0, serialize($object), $images);
				$stmt -> execute();
				$stmt -> close();
				$counter++;
			} else {
				echo $mysqli->error;
			}
			$mysqli -> close();
			
	}
	?>
    Converted <? echo $counter; ?> questions in quiz uuid <? echo $uuid; ?>
    <?
} else {
?>
<form method="post" action="convert.php">
UUID<input type="text" name="uuid">
<input type="submit" name="submit" value="Convert">
</form>
<?
}
?>