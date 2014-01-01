<?
include_once("config.func.php");
include_once("question.obj.php");

$question = new PolyQuestion(2, 1);
$left[0] = "London";
$left[1] = "Paris";
$left[2] = "Athens";
$left[3] = "Moscow";
$left[4] = "Madrid";

$right[0] = "France";
$right[1] = "Spain";
$right[2] = "Greece";
$right[3] = "Russia";
$right[4] = "Great Britain";
$left_ans[0] = $right[4];
$left_ans[1] = $right[0];
$left_ans[2] = $right[2];
$left_ans[3] = $right[3];
$left_ans[4] = $right[1];
echo "<br>";
echo "<br>";
$question->setAttributesMATCH($left, $right, $left_ans);
$question->randomize();
var_dump($question);
echo"<br><br>";
echo serialize($question);
echo "<br><br>";
var_dump(unserialize('O:12:"PolyQuestion":15:{s:6:"points";i:3;s:10:"imagegroup";O:10:"imageGroup":1:{s:6:"images";a:0:{}}s:4:"type";s:1:"2";s:3:"set";b:0;s:7:"correct";N;s:8:"question";N;s:8:"response";i:-1;s:11:"answerArray";N;s:6:"answer";N;s:12:"freeresponse";N;s:8:"left_res";a:3:{i:0;i:-1;i:1;i:-1;i:2;i:-1;}s:4:"left";a:3:{i:0;s:9:"<p>q1</p>";i:1;s:9:"<p>q2</p>";i:2;s:9:"<p>q3</p>";}s:5:"right";a:3:{i:0;s:8:"<p>1</p>";i:1;s:8:"<p>2</p>";i:2;s:8:"<p>3</p>";}s:8:"left_ans";a:3:{i:0;s:8:"<p>1</p>";i:1;s:8:"<p>2</p>";i:2;s:8:"<p>3</p>";}s:12:"pointsEarned";i:0;}'));
?>