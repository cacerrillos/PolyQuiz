<?
include_once("../../func/config.func.php");
include_once("objects/polyquiz.php");
$mysqli = new mysqli($db_host, $db_user, $db_password);
$mysqli->select_db($db_name);

$tt = PolyStats::getStats($mysqli, 5555, -1337, false);
echo json_encode($tt)."<br>";

$teeeet = PolyHouse::getHouses($mysqli, -1337);
echo json_encode($teeeet);

$test = PolyQuiz::AllOwned($mysqli, "asdas");
echo json_encode($test);

$qqq = PolyQuiz::fromMySQL($mysqli, 1);


echo "<pre>".$qqq->toJSON()."</pre>";

$quiz = new PolyQuiz();

echo "<pre>".$quiz->toJSON()."</pre>";

$a = new PolyAnswerStandard("2", 0);
$b = new PolyAnswerStandard("4", 1);
$c = new PolyAnswerStandard("6", 0);
$d = new PolyAnswerStandard("8", 0);

$pq = new PolyQuestionStandard("What is 2+2?");

$pq->addAnswer($a);
$pq->addAnswer($b);
$pq->addAnswer($c);
$pq->addAnswer($d);

$pq2 = new PolyQuestionMatching("Match the following");

$pq2->addAnswer(new PolyAnswerMatching("AA", "AA", 1));
$pq2->addAnswer(new PolyAnswerMatching("bb", "bb", 1));
$pq2->addAnswer(new PolyAnswerMatching("cc", "cc", 1));
$pq2->addAnswer(new PolyAnswerMatching("dd", "dd", 1));


$quiz->addQuestion($pq);
$quiz->addQuestion($pq2);


echo "<pre>".$quiz->toJSON()."</pre>";


?>