<?
include_once("func/question.obj.php");
mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
mysql_select_db($db_name) or die(mysql_error()); 
$uuid = mysql_real_escape_string($_GET['UUID']);
$quizinfoq = mysql_query("SELECT * FROM quizzes WHERE uuid='$uuid' AND owner='".$_SESSION['dbext']."'");
$quizinfo = mysql_fetch_array($quizinfoq);
$query = mysql_query("SELECT * FROM `".$uuid."` ORDER BY id ASC;") or die(mysql_error());;
$numrows = mysql_num_rows($query);
if(isset($_GET['page'])){
	$offset = intval($_GET['page'])*10;
} else {
	$offset = 0;
}
$limitedq = mysql_query("SELECT * FROM `".$uuid."` ORDER BY id ASC LIMIT ".$offset.", 10;");
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
<div id="main-wrapper">
	<div class="main-wrapper-style2">
		<div class="inner">
			<div class="container">
				<div class="row">
					<div class="4u">
						<div id="sidebar">
							<section>
								<? include("inc/adminleftsidebar.inc.php"); ?>
							</section>
						</div>
					</div>
					<div class="8u skel-cell-important">
						<div id="content">
                        <?
						if(hasPermissions($uuid)){
						?>
							<article>
								<header class="major">
									<h2>Edit Quiz</h2>
									<span class="byline">Editing Quiz: <? echo $quizinfo['quizname']; ?><br />
										<form name="editquiz" action="func/edit.func.php" method="post">
											<input type="hidden" name="uuid" value="<? echo $uuid; ?>" />
											<input type="radio" name="status" value="1" <? if($quizinfo['status']==1){ echo "checked"; }?>/>Enabled | <input type="radio" name="status" value="0" <? if($quizinfo['status']==0){ echo "checked"; }?>/>Disabled <input type="submit" name="submit" value="Change" />
										</form>
										<a href="func/renumber.func.php?uuid=<? echo $uuid; ?>">Reset Numbers!</a>
									</span>
								</header>
								<div id="loading" style="margin-left:20px">
									Please wait while the text editors load...
								</div>
								<div id="quizz" style="margin-left:20px; display:none;">
									<p></p>
									<div id="addquestiondiv" style="margin-left:20px; margin-bottom:30px">
										<h3 style="margin-bottom:5px">Add A Question</h3>
										<form action="?p=addquestion&uuid=<? echo $uuid; ?>" method="post">
										<input type="hidden" name="uuid" value="<? echo $uuid; ?>" />
										<input type="hidden" name="quizname" value="<? echo $quizinfo['quizname']; ?>" />
										Type: <select name="type">
										<option value="0">Multiple Choice</option>
										<option value="1">Free Response</option>
										<option value="2">Matching</option>
										<option value="4">Multiple Choice (XChoice)</option>
										</select>
										# of Answers:
										<select name="num">
										<?
										for($x = 1; $x <=20; $x++){
											if($x==4){
												$mod = " selected";
											} else {
												$mod = "";
											}
											echo "<option value='".$x."'".$mod.">".$x."</option>";
										}
										?>
										</select>
										<input type="submit" name="submit" value="Add!" />
										</form>
									</div>
									<?
									if($numrows>0){
										while($questions = mysql_fetch_array($limitedq)){
											$quest = unserialize($questions['object']);
											?>
											<div id="<? echo $questions['id']; ?>_closed">
												<a href="#" onclick="showElement('<? echo $questions['id'];?>_open'); hideElement('<? echo $questions['id'];?>_closed'); return false;">
													<img src="inc/icon_arrow_right.gif" /><? echo $questions['id'].") ".htmlspecialchars(substr($quest->question, 0, 40)); ?>
												</a>
											</div>
											
											<div id="<? echo $questions['id']; ?>_open" style="display: none;">
												<a href="#" onclick="showElement('<? echo $questions['id'];?>_closed'); hideElement('<? echo $questions['id'];?>_open'); return false;">
													<img src="inc/icon_arrow_down.gif" /><? echo $questions['id'].") ".htmlspecialchars(substr($quest->question, 0, 40)); ?>
												</a>
												<br>
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
														<input type="file" name="file" id="file">
														<input type="submit" name="submit" value="Upload">
													</form>
													<form name="edit<? echo $questions['id']; ?>" method="post" action="func/edit.func.php">
														<input type="hidden" name="uuid" value="<? echo $uuid; ?>" />
														<input type="hidden" name="num" value="<? echo $questions['id']; ?>" />
														<input type="hidden" name="type" value="<? echo $quest->type; ?>" />
														<h4>
															Edit #<? echo $questions['id']; ?><br />
															Question/Directions:<br />
															<textarea rows="4" cols="42" name="question"><? echo $quest->question; ?></textarea>
															<?
															$quest->paintEdit($alphabet);
															?>
															<input type="submit" name="submit" value="Edit" /><br />
															<font color="#FF0000">!!!<input type="submit" name="submit" value="Delete" />!!!</font>
														</h4>
													</form>
												</div>
											</div>
											<?
										}
									} else {
										?>
										<h3>This Quiz has no questions!</h3>
										<?
									}
									?>
									<br /><br />
									<?
									//page # code
									$pages = ceil($numrows/10);
									for($x = 0; $x < $pages; $x++){
										if($x==$pages-1){
											echo '<a href="?p=managequiz&UUID='.$uuid.'&page='.$x.'">['.(((($x+1)-1)*10)+1).'+]</a> ';
										} else {
											echo '<a href="?p=managequiz&UUID='.$uuid.'&page='.$x.'">['.(((($x+1)-1)*10)+1).'-'.(($x+1)*10).']</a> ';
										}
									}
									$data = mysql_query("SELECT * FROM quizzes WHERE owner='".$_SESSION['dbext']."' ORDER BY quizname DESC;");
									if(mysql_num_rows($quizinfoq)==0){
										echo "Sorry, no quizzes available!";
									}
									?>
								</div>
							</article>
                            <?
						} else {
							?><h3>You don't have permissions to view this quiz!</h3><?
							echo $_SESSION['dbext'];
						}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>