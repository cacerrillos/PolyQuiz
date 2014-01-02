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
													<h2>Admin Home</h2>
                                                    <?
													if(!isset($_SESSION["is_admin"])){
													?>
													<span class="byline">Please Log-In.</span>
                                                    <?
                                                    } else {
                                                    ?>
                                                    <span class="byline">Welcome back admin!</span>
                                                    <?
													}
													?>
												</header>
                                            	<?
												if(isset($_SESSION["is_admin"])){
													/*
												?>
                                                <table cellspacing="10px">
                                                	<tr>
                                                    	<td>
                                                            <div id="quizmanagement" style="margin-left:auto; margin-right:auto">
                                                            	<center>
                                                                    <h4 style="margin-bottom:0;">Quiz Management</h4>
                                                                    <a href="?p=quizadmin" class=""><i class="fa fa-cog"></i> Edit Quizzes</a>
                                                                </center>
                                                        	</div>
                                                        </td>
                                                        <td>
                                                            <div id="results">
                                                            	<center>
                                                                    <h4 style="margin-bottom:0;">Results & Grading</h4>
                                                                    <a href="?p=results" class=""><i class="fa fa-cog"></i> Quiz Results</a><br />
                                                                    <a href="?p=gradefr" class=""><i class="fa fa-cog"></i> Pending Free<br />Response Grading</a>
                                                                </center>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                    	<td>
                                                        <div id="sessions">
                                                        <center>
                                                        <h4 style="margin-bottom:0;">Session Management</h4>
                                                        <a href="?p=sessions" class=""><i class="fa fa-cog"></i> Edit Sessions</a><br>
                                                        <a href="?p=pendingsessions" class=""><i class="fa fa-cog"></i> Quizzes In Progress</a>
                                                        </center>
                                                    	</div>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </table>
                                                	
                                                    
                                                    
												<?
												*/
												}
												?>
                                                <img src="http://imgs.xkcd.com/comics/computer_problems.png" title="This is how I explain computer problems to my cat. My cat usually seems happier than me." alt="Computer Problems">
											</article>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>