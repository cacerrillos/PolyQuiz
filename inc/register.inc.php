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
                                                    <td style="border:0"><input type="text" name="password" autocorrect="off" autocomplete="off" autocapitalize="off" /></td>
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