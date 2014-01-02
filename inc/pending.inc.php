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
													<h2>Page Title</h2>
													<span class="byline">Which means the sidebar is on the left</span>
												</header>
                                                  <h3><a href="?p=admin">The Core</a></h3>
    <h1>Pending Quizes</h1>
<?
	if(isset($_SESSION["is_admin"])){
	?>
	<p>Welcome back admin!</p>
    <p>
    <script src="js/ajaxpending.js"></script>
<script type="text/javascript">refreshdiv2();</script>
    <div id="timediv2" style="margin-left:20px">
    
    </div>
    <p><a href="func/admin.logout.php">Logout</a></p>
    <?
	} else {
	
?>
    <p>
    <div id="login" style="margin-left:20px">
    <h2>Login</h2>
    <form id="adminlogin" action="func/admin.login.php" method="post">
    User: <input type="text" name="user"><br>
    Pass: <input type="password" name="pass"><br>
    <input type="submit" name="submit" value="Login"><br>
    </form>
    </div>
    </p>
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