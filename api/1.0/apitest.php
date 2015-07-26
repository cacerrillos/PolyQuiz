<?
include_once("objects/polyquiz.php");
$quiz = new PolyQuiz();

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