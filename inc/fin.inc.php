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
											/*
												<header class="major">
													<h2>Subheading</h2>
												</header>
												<footer>
													<a href="#" class="button alt fa fa-arrow-circle-o-right">Do Something</a>
												</footer>
												*/
												?>
											</section>
								
									</div>
								</div>
								<div class="8u skel-cell-important">
									<div id="content">

										<!-- Content -->
									
											<article>
												<header class="major">
													<h2>Results</h2>
												</header>
                                                <p>Congratulations!</p>
                                                <div id="quizz" style="margin-left:20px">
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
                                                </div>
											</article>
								
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>