												<header class="major">
													<a href="?p=admin" style="text-decoration: none;"><h2><i class="fa fa-cog"></i> The Core</h2></a>
												</header>
												<?
												if(isset($_SESSION["is_admin"])){
												?>
                                                    <h4 style="margin-bottom:0;">Session Management</h4>
                                                    <a href="?p=sessions" style="margin-left:20px"><i class="fa fa-cog"></i> Edit Sessions</a><br>
                                                    <a href="?p=pendingsessions" style="margin-left:20px"><i class="fa fa-cog"></i> Quizzes In Progress</a>
                                                	<h4 style="margin-bottom:0;">Quiz Management</h4>
                                                    <a href="?p=quizadmin" style="margin-left:20px"><i class="fa fa-cog"></i> Edit Quizzes</a><br>
                                                    <h4 style="margin-bottom:0;">Results & Grading</h4>
                                                    <a href="?p=results" style="margin-left:20px"><i class="fa fa-cog"></i> Quiz Results</a><br />
                                                    <a href="?p=gradefr" style="margin-left:20px"><i class="fa fa-cog"></i> Pending Free Response Grading</a>

                                                <?
												}
												?>
												<footer>
                                                <?
												if(isset($_SESSION["is_admin"])){
												?>
                                                    <a href="func/admin.logout.php" class="button alt fa fa-lock">Logout</a>
												<?
                                                } else {  
                                                ?>
                                                    <form id="adminlogin" action="func/admin.login.php" method="post">
                                                    <table>
                                                    <tr>
                                                    	<td><b>User:</b></td>
                                                    	<td><input type="text" name="user"></td>
                                                    </tr>
                                                    <tr>
                                                   		<td>Pass:</td>
                                                    	<td><input type="password" name="pass"></td>
                                                    </tr>
                                                    <tr>
                                                    	<td></td>
                                                    	<td><input type="submit" name="submit" value="Login" class="button alt fa fa-lock"></td>
                                                    </tr>
                                                    </table>
                                                    </form>
                                                <?
                                                }
                                                ?>
												</footer>