<?
/**
Construct -> Set Attributes -> Randomize


*/
include_once("config.func.php");
include_once("img.class.php");
class PolyAnswer {
	public $answer, $points;
	function __construct($answer, $points){
		$this->answer = $answer;
		$this->points = $points;
	}
	function getAnswer(){
		return $this->answer;
	}
	function getPoints(){
		return $this->points;
	}
}
class PolyQuestion {
	public $points, $imagegroup;
	public $type; //Multiple(0), FreeResponse(1), Matching(2), Survey(3), MultipleX(4)
	public $set = false, $correct = null;
	public $question, $response = -1, $answerArray, $answer, $freeresponse, $left_res, $left, $right, $left_ans, $pointsEarned = 0, $freeGraded = false;
	public $isextracredit, $extracreditpoints, $displayextracredit;
	public $includeCanvas;
	public $canvasValue;
	function __construct($type, $points){
		$this->type = $type;
		$this->points = intval($points);
		$this->imagegroup = new imageGroup();
	}
	//String, Array, NumberOfIndex
	function setAttributesMC($question, $answerArray, $answer){
		$this->question = $question;
		$this->answerArray = $answerArray;
		$this->answer = $answer;
		
	}
	function setAttributesXC($question, $answerArray){
		$this->question = $question;
		$this->answerArray = $answerArray;
	}
	function setAttributesMATCH($left, $right, $left_ans){
		$this->left = $left;
		$this->right = $right;
		$this->left_ans = $left_ans;
		$this->points = count($left);
		for($x = 0; $x < count($left); $x++){
			$this->left_res[$x] = -1;
		}
	}
	function setIsExtraCredit($bool){
		$this->isextracredit = $bool;
	}
	function setCanvasValue($value){
		$this->canvasValue = base64_encode($value);
	}
	function getCanvasValue(){
		return base64_decode($this->canvasValue);
	}
	function isExtraCredit(){
		return $this->isextracredit;
	}
	function setAttributesFREERESPONSE($question){
		$this->question = $question;
	}
	function paint($alphabet, $curans){
		if($this->includeCanvas){
			?>
            <div id="closedCanvas" style="display: none; margin-left:20px">
        		<a href="#" onclick="showElement('openCanvas'); hideElement('closedCanvas'); return false;"><img src="inc/icon_arrow_right.gif" />Show Canvas</a>
            </div>
			<div id="openCanvas" style="margin-left:20px;">
                <a href="#" onclick="showElement('closedCanvas'); hideElement('openCanvas'); return false;"><img src="inc/icon_arrow_down.gif" />Hide Canvas</a>
                <div class="tools">
                    <a href="#myCanvas" data-color="#000" style="width: 20px; background: #000;"></a>
                    <a href="#myCanvas" data-color="#f00" style="width: 20px; background: #f00;"></a>
                    <a href="#myCanvas" data-color="#ff0" style="width: 20px; background: #ff0;"></a>
                    <a href="#myCanvas" data-color="#0f0" style="width: 20px; background: #0f0;"></a>
                    <a href="#myCanvas" data-color="#0ff" style="width: 20px; background: #0ff;"></a>
                    <a href="#myCanvas" data-color="#00f" style="width: 20px; background: #00f;"></a>
                    <a href="#myCanvas" data-color="#f0f" style="width: 20px; background: #f0f;"></a>
                    <a href="#myCanvas" data-color="#fff" style="width: 20px; background: #fff;"></a>
                    <a href="#myCanvas" data-size="3" style="background: #ccc">Size 3</a>
                    <a href="#myCanvas" data-size="5" style="background: #ccc">Size 5</a>
                    <a href="#myCanvas" data-size="10" style="background: #ccc">Size 10</a>
                    <a href="#myCanvas" data-size="25" style="background: #ccc">Size 25</a>
                    <a href="#myCanvas" data-tool="marker">Marker</a>
                    <a href="#myCanvas" data-tool="eraser">Eraser</a>
                    <a href="#myCanvas" data-clear="true">Clear</a>
                </div>
                <canvas id="myCanvas" width="600px" height="400px" style="border: 1px solid #ccc;"></canvas>
                <input type="hidden" name="canvasValue" id="canvasValue" />
                <script type="text/javascript">
					$(function() {
						$('#myCanvas').sketch();
					});
					var canvas = document.getElementById("myCanvas");
					var ctx = canvas.getContext("2d");
					var image = new Image();
					image.src = "<? echo $this->getCanvasValue(); ?>";
					image.onload = function() {
						ctx.drawImage(image, 0, 0);
					};
				</script>
            </div>
            <?
		}
		if($this->type==0){
			?>
            <div class="question">
				<?
                for($x = 0; $x < count($this->answerArray); $x++){
                    if($x %2 ==0){
                        $color = "999";
                    } else {
                        $color = "CCC";
                    }
                ?>
                <div class="questionLetter" style="background-color:#<? echo $color; ?>; padding-left:10px; padding-right:10px; width:100%; margin-top:10px; margin-bottom:10px;">
                    <input type="radio" id="radio<? echo $x; ?>" name="answer" value="<? echo $x; ?>" <? if($this->response==$x) { echo "checked"; }?>>
                    <label for="radio<? echo $x; ?>">
                        <span><? echo $alphabet[$x].") "; ?></span>
                        <span style="color:#000; font-size:1em"><? echo $this->answerArray[$x] ?></span>
                    </label>
                </div>
                <?
                }
                ?>
                <div style="background-color:#666; padding-left:10px;">
                    <h3 style="color:#FFF;">
                        <input id="skip" type="radio" name="answer" value="-1" <? if($curans=="-1"){ echo "checked"; } ?>/>
                        <label for="skip">Skip</label>
                    </h3>
                </div>
            </div>
            <?
		}
		if($this->type==4){
			?>
            <div class="question">
				<?
                for($x = 0; $x < count($this->answerArray); $x++){
                    if($x %2 ==0){
                        $color = "999";
                    } else {
                        $color = "CCC";
                    }
                ?>
                <div class="questionLetter" style="background-color:#<? echo $color; ?>; padding-left:10px; padding-right:10px; width:100%; margin-top:10px; margin-bottom:10px;">
                    <input type="radio" id="radio<? echo $x; ?>" name="answer" value="<? echo $x; ?>" <? if($this->response==$x) { echo "checked"; }?>>
                    <label for="radio<? echo $x; ?>">
                        <span><? echo $alphabet[$x].") "; ?></span>
                        <? $thisAnswer = $this->answerArray[$x]; ?>
                        <span style="color:#000; font-size:1em"><? echo $thisAnswer->getAnswer(); ?></span>
                    </label>
                </div>
                <?
                }
                ?>
                <div style="background-color:#666; padding-left:10px;">
                    <h3 style="color:#FFF;">
                        <input id="skip" type="radio" name="answer" value="-1" <? if($curans=="-1"){ echo "checked"; } ?>/>
                        <label for="skip">Skip</label>
                    </h3>
                </div>
            </div>
            <?
		}
		if($this->type==1){
			?>
            <div class="question">
            <textarea rows="3" cols="20" name="answer"><? echo $curans; ?></textarea>
            </div>
            <?
		}
		if($this->type==2){
			//echo $this->question."<br><br>";
			for($x = 0; $x < count($this->left); $x++){
				if($x %2 ==0){
					$color = "999";
				} else {
					$color = "CCC";
				}
			?>
            <div class="question">
                <div class="questionLetter">
                    <div style="background-color:#<? echo $color; ?>; margin-top:5px; margin-bottom:5px; padding-left:10px; padding-right:10px;">
                        <div style="float:left;">
                            <div style="margin-bottom:0;">
                                <span style="margin-bottom:0; color:#000;">
									<? echo ($x+1).") "; ?>
                                </span>
                                <select name="ans<? echo $x; ?>">
                                    <option value="-1"></option>
                                    <?
                                    for($y = 0 ; $y < count($this->right); $y++){
                                        ?>
                                        <option value="<? echo $y; ?>" <? if($this->left_res[$x]==$y){ echo "selected"; }?>>
											<? echo $alphabet[$y]; ?>
                                        </option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <span style="text-transform:none; color:#000; font-size:1.2em;">
									<? echo " ".$this->left[$x]; ?>
                                </span>
                            </div>
                        </div>
                        <div style="float:right;">
                            <span style="margin-bottom:0; color:#000;">
                                <? echo "<b>".$alphabet[$x]."</b>) ";?>
                            </span>
                            <span style="text-transform:none; color:#000; font-size:1.2em;">
                                <? echo $this->right[$x]; ?>
                            </span>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                </div>
            </div>
			<?
			}
		}
	}
	function paintGlobalQuestionOptions(){
		?>
        Extra Credit
        <select name="extracredit" size="1">
            <option value="0">No</option>
            <option value="1" <? if($this->isExtraCredit()){ echo "selected";}?>>Yes</option>
        </select>
        Display Extra Credit Label
        <select name="extracreditdisplay" size="1">
            <option value="0">No</option>
            <option value="1" <? if($this->displayextracredit==true){ echo "selected";}?>>Yes</option>
        </select>
        Use Canvas
        <select name="includeCanvas" size="1">
            <option value="0">No</option>
            <option value="1" <? if($this->includeCanvas==true){ echo "selected";}?>>Yes</option>
        </select>
        <?
	}
	function paintEdit($alphabet){
		if($this->type==0){
			?>
            <input type="hidden" name="answerNum" value="<? echo count($this->answerArray); ?>" />
            Points:<input type="number" name="points" value="<? echo $this->getPoints(); ?>" />
            <? $this->paintGlobalQuestionOptions(); ?>
            <br /><br />
            <?
            for($x = 0; $x < count($this->answerArray); $x++){
                ?>
                <input type="radio" name="answer" value="<? echo $x; ?>" <? if($this->answer==$x) { echo "checked"; } ?> />
                <? echo $alphabet[$x].") "; ?>
                <textarea rows="2" cols="42" name="<? echo $x; ?>text"><? echo $this->answerArray[$x]; ?></textarea>
                <br /><br />
            <?
            }
		}
		if($this->type==1){
			?>
			Points:<input type="number" name="points" value="<? echo $this->getPoints(); ?>" />
            <? $this->paintGlobalQuestionOptions(); ?>
            <br /><br />
            <?
		}
		if($this->type==2){
			?>
			<? $this->paintGlobalQuestionOptions(); ?>
            <br /><br />
			<?
			for($x = 0; $x < count($this->left); $x++){
				if($x %2 ==0){
					$color = "999";
				} else {
					$color = "CCC";
				}
				?>
				<input type="hidden" name="answerNum" value="<? echo count($this->right); ?>" />
				<div style="background-color:#<? echo $color; ?>; margin-top:5px; margin-bottom:5px;">
					<div style="float:left;">
						<h3 style="margin-bottom:0;">
							<? echo ($x+1).") Question "; ?>
							<textarea rows="3" cols="20" name="left<? echo $x; ?>"><? echo $this->left[$x]; ?></textarea>
						</h3>
					</div>
					<div style="float:left;">
						<h3 style="margin-bottom:0;">
							<? echo "<b>".$alphabet[$x]."</b>) Answer to #".($x+1).""; ?>
							<textarea rows="3" cols="20" name="right<? echo $x; ?>"><? echo $this->right[$x]; ?></textarea>
						</h3>
					</div>
					<div style="clear: both;"></div>
				</div>
				<?
			}
		}
		if($this->type==4){
			?>
			<? $this->paintGlobalQuestionOptions(); ?>
			<br /><br />
			<?
			for($x = 0; $x < count($this->answerArray); $x++){
				?>
				<select name="<? echo $x; ?>points" size="1">
					<?
					for($b = 0; $b <= $this->getPoints(); $b++){
						?>
						<option value="<? echo $b; ?>" <? if($this->answerArray[$x]->getPoints()==$b){ echo "selected"; } ?>><? echo $b; ?></option>
						<?
					}
					?>
				</select>
				<? echo $alphabet[$x].") "; ?>
				<textarea rows="2" cols="42" name="<? echo $x; ?>text"><? echo $this->answerArray[$x]->getAnswer(); ?></textarea>
				<br /><br />
				<?
			}
		}
	}
	function paintAdd($alphabet){
		//if($this->ty
	}
	function freeHasBeenGraded(){
		return $this->freeGraded;	
	}
	function setFreeHasBeenGraded($bool){
		$this->freeGraded = $bool;
	}
	function randomize(){
		if($this->type==0){
			$temp = $this->answerArray;
			$newTemp;
			$tempnumbers = range(1, count($temp));
			$tempNewAnswer;
			shuffle($tempnumbers);
			for($x = 0; $x <count($tempnumbers); $x++){
				$tempnumbers[$x] = $tempnumbers[$x]-1;
			}
			for($x = 0; $x < count($temp); $x++){
				$newTemp[$tempnumbers[$x]] = $temp[$x];
				if($x==$this->answer){
					$tempNewAnswer = $tempnumbers[$x];
				}
			}
			$this->answerArray = $newTemp;
			$this->answer = $tempNewAnswer;
		}
		if($this->type==2){
			$right = $this->right;
			$tempnewnums = range(1, count($this->left));
			shuffle($tempnewnums);
			for($x = 0; $x <count($tempnewnums); $x++){
				$tempnewnums[$x] = $tempnewnums[$x]-1;
			}
			for($x = 0; $x < count($this->left); $x++){
				$newTemp[$tempnewnums[$x]] = $right[$x];
			}
			//$randomizer = new arrayRandom();
			//$arshuff = $randomizer->sortMultishuffle($ar);
			$this->right = $newTemp;
		}
	}
	function setResponse($response){
		if($this->type==0){
			$this->response = intval($response);
			$this->correct = $this->check();
			if($this->correct()){
				$this->pointsEarned = $this->points;
			}
		}
		if($this->type==1){
			$this->response = $response;
			$this->freeresponse = $response;
			
		}
		if($this->type==2){
			$this->left_res = $response;
			$this->pointsEarned = 0;
			for($x = 0; $x < count($this->left); $x++){
				if($response[$x]!=-1){
					if($this->left_ans[$x]==$this->right[$response[$x]]){
						$this->pointsEarned+=1;
					}
				}
			}
			//$this->correct = $this->check();
		}
		if($this->type==4){
			$this->response = intval($response);
			$this->pointsEarned = $this->answerArray[intval($response)]->getPoints();
		}
	}
	function getResponse(){
		return $this->response;
	}
	function omitted(){
		if($this->response == "-1"){
			return true;
		} else {
			return false;
		}
	}
	function correct(){
		return $this->correct;
	}
	function check(){
		if($this->response == $this->answer){
			return true;
		} else {
			return false;
		}
	}
	function getPoints(){
		return $this->points;
	}
	function getPointsEarned(){
		return $this->pointsEarned;
	}
}
class arrayRandom {
	public $_feeds=array();
	public $_feedIterator=0;
	function _sorter($a,$b){
		if(!isset($GLOBALS['_feeds'][$GLOBALS['_feedIterator']])){$GLOBALS['_feedIterator']=0;}; 
		return ((float)$GLOBALS['_feeds'][$GLOBALS['_feedIterator']++] < 0.5)?1:-1;
	}
	
	function sortMultishuffle($arrayOfArrays=array()){
		if(!is_array($arrayOfArrays)){return false;};
		GLOBAL $_feeds, $_feedIterator;
		$_feeds=array();
		$amountOfFeeds=sizeof($arrayOfArrays[0]);
		for($i=0; $i < $amountOfFeeds; $i++){
			$_feeds[]='0.'.mt_rand();/*floating number as a string*/
		}
		for($s=0; $s < sizeof($arrayOfArrays); $s++){
			$_feedIterator=0;
			usort($arrayOfArrays[$s], array($this, "_sorter"));
		}
		return $arrayOfArrays;
	}
}
?>