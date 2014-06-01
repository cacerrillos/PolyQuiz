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
													<h2>Session Management</h2>
													<span class="byline"><h3>Quizzes In Progress</h3></span>
												</header>    
<?
	if(isset($_SESSION["is_admin"])){
	?>
    <p>
    <script src="js/ajaxpending.js"></script>
<script type="text/javascript">refreshdiv2();</script>
    <div id="timediv2" style="margin-left:20px">
    
    </div>
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