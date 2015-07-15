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
                                                <?
												include("inc/adminleftsidebar.inc.php");
												?>
											</section>
								
									</div>
								</div>
								<div class="8u skel-cell-important">
									<div id="content">
										<!-- Content -->
											<article>
												<header class="major">
													<h2>Add a Question</h2>
													<span class="byline">Editing Quiz: <? echo $_POST['quizname']; ?></span>
												</header>
                                                <?
	if(isset($_SESSION["is_admin"])){
	?>
    <form name="add" method="post" action="func/edit.func.php">
    <input type="hidden" name="uuid" value="<? echo $_POST['uuid']; ?>" />
        <input type="hidden" name="type" value="<? echo $_POST['type']; ?>" />
        <input type="hidden" name="answerNum" value="<? echo $_POST['num']; ?>" />
    <?
	if(intval($_POST['type'])==0){
	?>
        Points:<input type="number" name="points" value="1"> Extra Credit
        <select name="extracredit" size="1">
        	<option value="0">No</option>
            <option value="1">Yes</option>
        </select> Display Extra Credit Label
        <select name="extracreditdisplay" size="1">
        	<option value="0">No</option>
            <option value="1">Yes</option>
        </select>
        <h4>
        <textarea rows="4" cols="42" name="question"></textarea><br /><br />

        <br /><br />
        <?
		for($x = 0; $x < $_POST['num']; $x++){
		?>
        <input type="radio" name="answer" value="<? echo $x; ?>" /><? echo $alphabet[$x]; ?>) <textarea rows="2" cols="42" name="<? echo $x; ?>text"></textarea><br /><br />
        <?
		}
		?>
    <?
	}
	if(intval($_POST['type'])==1){
	?>
        Points:<input type="number" name="points" value="1"> Extra Credit
        <select name="extracredit" size="1">
        	<option value="0">No</option>
            <option value="1">Yes</option>
        </select>
        <h4>
        <textarea rows="4" cols="42" name="question"></textarea><br /><br />
        <br /><br />
    <?
	}
	if(intval($_POST['type'])==2){
		?>
		Directions:<textarea rows="4" cols="42" name="question"></textarea><br /><br />
        <?
			for($x = 0; $x < intval($_POST['num']); $x++){
				if($x %2 ==0){
					$color = "999";
				} else {
					$color = "CCC";
				}
			?>
				<div style="background-color:#<? echo $color; ?>; margin-top:5px; margin-bottom:5px;"><div style="float:left;">
				<h3 style="margin-bottom:0;"><? echo ($x+1).") Question"; ?>
				<? echo " "; ?>
                <textarea rows="3" cols="20" name="left<? echo $x; ?>"></textarea>
                </h3></div>
                <div style="float:left;"><h3 style="margin-bottom:0;">
                <?
				echo "<b>".$alphabet[$x]."</b>) Answer to #".($x+1)."";
				?>
                <textarea rows="3" cols="20" name="right<? echo $x; ?>"></textarea>
                </h3></div>
                <div style="clear: both;"></div>
                </div>
			<?
			}
	}
    ?>
     <?
	if(intval($_POST['type'])==4){
	?>
        <h3>Question Value: <? echo $_POST['num']; ?></h3>
        <input type="hidden" name="points" value="<? echo $_POST['num']; ?>"> Extra Credit
        <select name="extracredit" size="1">
        	<option value="0">No</option>
            <option value="1">Yes</option>
        </select> Display Extra Credit Label
        <select name="extracreditdisplay" size="1">
        	<option value="0">No</option>
            <option value="1">Yes</option>
        </select>
        <h4>
        <textarea rows="4" cols="42" name="question"></textarea><br /><br />

        <br /><br />
        <?
		for($x = 0; $x < $_POST['num']; $x++){
		?>
            
			<? echo $alphabet[$x]; ?>) Points: <select name="<? echo $x; ?>points" size="1">
                <?
                for($b = 0; $b <= $_POST['num']; $b++){
                    ?>
                    <option value="<? echo $b; ?>"><? echo $b; ?></option>
                    <?
                }
                ?>
            </select>
            <textarea rows="2" cols="42" name="<? echo $x; ?>text"></textarea><br /><br />
        <?
		}
		?>
    <?
	}
	?>
        <input type="submit" name="submit" value="Add" /><br />
        </h4>
<?
}
?>
											</article>
								
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>