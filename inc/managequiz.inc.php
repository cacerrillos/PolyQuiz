<?
include_once("func/question.obj.php");
	mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
	mysql_select_db($db_name) or die(mysql_error()); 
	$uuid = mysql_real_escape_string($_GET['UUID']);
	$quizinfoq = mysql_query("SELECT * FROM quizes WHERE uuid='$uuid'");
	$quizinfo = mysql_fetch_array($quizinfoq);
	$query = mysql_query("SELECT * FROM ".$uuid." ORDER BY id ASC;");
	$numrows = mysql_num_rows($query);
	if(isset($_GET['page'])){
		$offset = intval($_GET['page'])*10;
	} else {
		$offset = 0;
	}
	$limitedq = mysql_query("SELECT * FROM ".$uuid." ORDER BY id ASC LIMIT ".$offset.", 10;");
?>
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
window.onload=function() {
   hideElement('loading');
   showElement('quizz');
}
</script>
		<!-- Main Wrapper -->
			<div id="main-wrapper">
				<div class="main-wrapper-style2">
					<div class="inner">
						<div class="container">
							<div class="row">
								<div class="4u">
									<div id="sidebar">

										<!-- Sidebar -->
											<section>
												<header class="major">
													<h2>Subheading</h2>
												</header>
												<footer>
													<a href="#" class="button alt fa fa-arrow-circle-o-right">Do Something</a>
												</footer>
											</section>
								
									</div>
								</div>
								<div class="8u skel-cell-important">
									<div id="content">

										<!-- Content -->
									
											<article>
												<header class="major">
													<h2>Page Title</h2>
													<span class="byline">Which means the sidebar is on the left</span>
												</header>
                                                <h3><a href="?p=admin">The Core</a></h3>
    <p>Managing Quiz: <? echo $quizinfo['quizname']; ?></p>
    <div id="loading" style="margin-left:20px">
    Please wait while the text editors load...
    </div>
    <div id="quizz" style="margin-left:20px; display:none;">
    <p><a href="func/renumber.func.php?uuid=<? echo $uuid; ?>">Reset!</a></p>
    <form name="editquiz" action="func/edit.func.php" method="post">
    <input type="hidden" name="uuid" value="<? echo $uuid; ?>" />
    <input type="radio" name="status" value="1" <? if($quizinfo['status']==1){ echo "checked"; }?>/>Enabled | <input type="radio" name="status" value="0" <? if($quizinfo['status']==0){ echo "checked"; }?>/>Disabled <input type="submit" name="submit" value="Change" /><br /><br />
    </form>
    <h2>Add A Question</h2>
    <form action="?p=addquestion&uuid=<? echo $uuid; ?>" method="post">
    <input type="hidden" name="uuid" value="<? echo $uuid; ?>" />
    Type: <select name="type">
    <option value="0">Multiple Choice</option>
    <option value="1">Free Response</option>
    <option value="2">Matching</option>
    </select>
    # of Answers:
    <select name="num">
    <?
	for($x = 1; $x <=20; $x++){
		echo "<option value='".$x."'>".$x."</option>";
	}
	?>
    </select>
    <input type="submit" name="submit" value="Add!" />
    </form>
    <br />
    <?
//	$questions = mysql_fetch_array($query);
	while($questions = mysql_fetch_array($limitedq)){
		$quest = unserialize($questions['object']);
		?>
        <div id="<? echo $questions['id']; ?>_closed">
        <a href="#" onclick="showElement('<? echo $questions['id']; ?>_open'); hideElement('<? echo $questions['id']; ?>_closed'); return false;"><img src="inc/icon_arrow_right.gif" /><? echo $questions['id'].") ".htmlspecialchars(substr($quest->question, 0, 40)); ?></a>
        </div>
        
        <div id="<? echo $questions['id']; ?>_open" style="display: none;">
        <a href="#" onclick="showElement('<? echo $questions['id']; ?>_closed'); hideElement('<? echo $questions['id']; ?>_open'); return false;"><img src="inc/icon_arrow_down.gif" /><? echo $questions['id'].") ".htmlspecialchars(substr($quest->question, 0, 40)); ?></a>
        <br><? //echo $quest->question; ?>
        <div style="margin-left:40px;">
                                <?
		if($questions['images']!=null && $questions['images']!=""){
			$imageGroup = unserialize($questions['images']);
		} else {
			$imageGroup = new imageGroup();
		}
		$imageGroup -> printThumbnailsAdmin($uuid, $questions['id']);
		
		?>
                <form action="func/img.up.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="uuid" value="<? echo $uuid; ?>" />
        <input type="hidden" name="num" value="<? echo $questions['id']; ?>" />
<label for="file">New Image:</label>
<input type="file" name="file" id="file"><input type="submit" name="submit" value="Upload">
</form>

        <form name="edit<? echo $questions['id']; ?>" method="post" action="func/edit.func.php">
        <input type="hidden" name="uuid" value="<? echo $uuid; ?>" />
        <input type="hidden" name="num" value="<? echo $questions['id']; ?>" />
        <input type="hidden" name="type" value="<? echo $quest->type; ?>" />
        <input type="hidden" name="answerNum" value="<? echo count($quest->right); ?>" />
        
        <h4>
        Edit #<? echo $questions['id']; ?><br />
        Question/Directions:<br />
        <textarea rows="4" cols="42" name="question"><? echo $quest->question; ?></textarea>
        <?
		if($quest->type==0){
		?>
        Points:<input type="number" name="points" value="<? echo $quest->getPoints(); ?>" /><br /><br />

        <br /><br />
        <?
		for($x = 0; $x < count($quest->answerArray); $x++){
		?>
        <input type="radio" name="answer" value="<? echo $x; ?>" <? if($quest->answer==$x) { echo "checked"; } ?> /><? echo $alphabet[$x]; ?>) <textarea rows="2" cols="42" name="<? echo $x; ?>text"><? echo $quest->answerArray[$x]; ?></textarea><br /><br />
        <?
		}
		?>
        
        <?
		}//end type
		if($quest->type==1){
		?>
        Points:<input type="number" name="points" value="<? echo $quest->getPoints(); ?>" /><br /><br />
        
        <?
		}//end type
		if($quest->type==2){
			for($x = 0; $x < count($quest->left); $x++){
				if($x %2 ==0){
					$color = "999";
				} else {
					$color = "CCC";
				}
			?>
				<div style="background-color:#<? echo $color; ?>; margin-top:5px; margin-bottom:5px;"><div style="float:left;">
				<h3 style="margin-bottom:0;"><? echo ($x+1).") Question"; ?>
				<? echo " "; ?>
                <textarea rows="3" cols="20" name="left<? echo $x; ?>"><? echo $quest->left[$x]; ?></textarea>
                </h3></div>
                <div style="float:left;"><h3 style="margin-bottom:0;">
                <?
				echo "<b>".$alphabet[$x]."</b>) Answer to #".($x+1)."";
				?>
                <textarea rows="3" cols="20" name="right<? echo $x; ?>"><? echo $quest->right[$x]; ?></textarea>
                </h3></div>
                <div style="clear: both;"></div>
                </div>
			<?
			}
		}
		?>
        <input type="submit" name="submit" value="Edit" /><br />
        <font color="#FF0000">!!!<input type="submit" name="submit" value="Delete" />!!!</font>
        </h4>
        </form>

        </div>
        </div>
        <?
	//	echo "#".$questions['id']." ".htmlspecialchars(substr($questions['question'], 0, 40))." <br>";
	}
	?>
    <br /><br />
    <?
	//page code
	$pages = ceil($numrows/10);
	for($x = 0; $x < $pages; $x++){
		if($x==$pages-1){
			echo '<a href="?p=managequiz&UUID='.$uuid.'&page='.$x.'">['.(((($x+1)-1)*10)+1).'+]</a> ';
		} else {
			echo '<a href="?p=managequiz&UUID='.$uuid.'&page='.$x.'">['.(((($x+1)-1)*10)+1).'-'.(($x+1)*10).']</a> ';
		}
	}
	?>
    <?
	/*
    	<div id="<? echo $questions['id']; ?>_add_closed">
        <a href="#" onclick="showElement('<? echo $questions['id']; ?>_add_open'); hideElement('<? echo $questions['id']; ?>_add_closed'); return false;"><img src="inc/icon_arrow_right.gif" />Add Question</a>
        </div>
        
        <div id="<? echo $questions['id']; ?>_add_open" style="display: none;">
        <a href="#" onclick="showElement('<? echo $questions['id']; ?>_add_closed'); hideElement('<? echo $questions['id']; ?>_add_open'); return false;"><img src="inc/icon_arrow_down.gif" />Add Question</a>
        <div style="margin-left:40px;">
        <h4>
        <form name="addquestion" action="func/edit.func.php" method="post">
        <input type="hidden" name="uuid" value="<? echo $uuid; ?>" />
        Question:<br />
        <textarea rows="4" cols="42" name="question"></textarea><br /><br />
        <input type="radio" name="answer" value="a"/>A) <textarea rows="2" cols="42" name="atext"></textarea><br /><br />
        <input type="radio" name="answer" value="b"/>B) <textarea rows="2" cols="42" name="btext"></textarea><br /><br />
        <input type="radio" name="answer" value="c"/>C) <textarea rows="2" cols="42" name="ctext"></textarea><br /><br />
        <input type="radio" name="answer" value="d"/>D) <textarea rows="2" cols="42" name="dtext"></textarea><br /><br />
        <input type="submit" name="submit" value="Add" /><br />
        </form>
        </h4>
        </div>
        </div>
    */
	?>
    <?
//	var_dump($_SESSION);
	$data = mysql_query("SELECT * FROM quizes ORDER BY quizname DESC;");
	if(mysql_num_rows($quizinfoq)==0){
		echo "Sorry, no quizes available!";
	}
	?>
    </div>
											</article>
								
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>