<?
include_once("config.func.php");
include_once("question.obj.php");

$question = new PolyQuestion(MULTIPLE, 4);
var_dump($question);
echo "<br>";
echo "<br>";
$question->setAttributesMC("This is the question", array(0 => "Answer 1", 1 => "Answer 2", 2 => "Answer 3", 3 => "Answer 4", 4 => "Answer 5"), 2);
var_dump($question);
echo "<br>";
echo "<br>";
$question->randomize();
var_dump($question);

if($question->type==MULTIPLE){
	$curans = 0;
?>
	<input type="hidden" name="num" value="<? echo $question->uuid+1; ?>">
    <?
	for($x = 0; $x < count($question->answerArray); $x++){
		if($x %2 ==0){
			$color = "999";
		} else {
			$color = "CCC";
		}
	?>
<div style="background-color:#<? echo $color; ?>"><h4><? echo $alphabet[$x]; ?> <input type="radio" id="radio<? echo $x; ?>" name="answer" value="<? echo $x; ?>" <? if($curans==$x) { echo "checked"; }?>><label for="radio<? echo $x; ?>"> <? echo $question->answerArray[$x] ?></label></h4></div>
	<?
	}
	?>
                            <?
							if(true){
								?>
                                <div style="background-color:#CCC"><h4><input type="radio" id="skip" name="answer" value="-1" <? if($curans=="-1"){ echo "checked"; } ?>/><label for="skip">Skip</label></h4></div>
                                <?
							}
							?>
                        	<?
							if($num!=1){
								?>
                            	<input type="submit" name="submit" value="Previous" onclick="window.onbeforeunload = null;">
                            	<?
							}
							?>
                        	<input type="submit" name="submit" value="Next" onclick="window.onbeforeunload = null;">
                        	</form>
                            <br /><br />
<?	
}
echo serialize($question);
?>
