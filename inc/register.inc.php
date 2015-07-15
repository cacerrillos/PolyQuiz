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
											</section>
									</div>
								</div>
								<div class="8u skel-cell-important">
									<div id="content">
										<!-- Content -->
											<article>
												<header class="major">
													<h2>Register</h2>
												</header>
                                                <h5>Register today to start making your very own online quizzes with multiple choice, free response, and matching questions!</h5>
                                                <form name="register" method="post" action="func/register.func.php">
                                                <span style="display:inline-block;">
                                                <?
												if(isset($_GET['error'])){
													if($_GET['error']=="1"){
														?>
														<span style="color:#F00">Username is already taken!</span>
														<?
													}
												}
												?>
                                                <table>
                                                <tr>
                                                    <td style="border:0">Username:</td>
                                                    <td style="border:0"><input type="text" name="username" autocorrect="off" autocomplete="off" autocapitalize="off"/></td>
                                                </tr>
                                                <tr>
                                                    <td style="border:0">Password:</td>
                                                    <td style="border:0"><input type="password" name="password" autocorrect="off" autocomplete="off" autocapitalize="off" /></td>
                                                </tr>
                                                </table>
                                                <div align="center">
                                                <input type="submit" name="submit" value="Register" onclick="window.onbeforeunload = null" class="button fa"/>
                                                </div>
                                                </span>
                                                </form>
											</article>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>