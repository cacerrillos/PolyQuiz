<?
$fn = $_SESSION['firstname'];
$ln = $_SESSION['lastname'];
$raw = $_SESSION['raw'];
$total = $_SESSION['total'];
$perc = $_SESSION['perc'];
$frgraded = $_SESSION['frgraded'];
$frp = $_SESSION['frp'];
$frt = $_SESSION['frt'];
$show = $_SESSION['show'];
session_unset();
?>
<div class="container">
	<div class="row">
		<div class="12u">
			<paper-material>
				<?
				if($show=="1"){
					?>
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
				} else {
					?>
					<b><? echo $fn." ";?></b>you have finished the quiz!
					<?
				}
				?>
			</paper-material>
		</div>
	</div>
</div>
