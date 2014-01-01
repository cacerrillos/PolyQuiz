<?
$fn = $_SESSION['firstname'];
$ln = $_SESSION['lastname'];
$raw = $_SESSION['raw'];
$total = $_SESSION['total'];
$perc = $_SESSION['perc'];
$frgraded = $_SESSION['frgraded'];
$frp = $_SESSION['frp'];
$frt = $_SESSION['frt'];
session_unset();
?>
  <div class="content">
    <h1>Home</h1>
    <p>Congratulations!</p>
    <div id="quizz" style="margin-left:20px">
    <b><? echo $fn." ";?></b>you have finished the quiz with a score of<br>
    <? echo "<b>".$raw."</b> out of <b>".$total."</b>";?><br>
    <b><? echo $perc." %"; ?></b><br /><br />
    <?
	if($frgraded==0){
		?>
        Your free response score is <b>PENDING</b> out of <b><? echo $frp;?></b>
        <?
		/*
	} else {
		?>
         Your free response score is <b><? echo $frt; ?></b> out of <b><? echo $frp;?></b><br />
         <b><? echo round((($frt/$frp)*100), 2)." %"; ?></b>
        <?
		*/
	}
	?>
    <?
	
	?>
    </div>
    <!-- end .content --></div>