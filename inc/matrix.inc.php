		<!-- Main Wrapper -->
			<div id="main-wrapper">
				<div class="main-wrapper-style2">
					<div class="inner">
						<div class="container">
							<div class="row">
								<div class="4u">
									<div id="sidebar">
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
                                        <article>
                                            <header class="major">
                                                <h2>Matrix Utilities</h2>
                                            </header>
                                            
                                            <?
											$pre = false;
											if(isset($_GET['type'])) {
												$pre = true;	
											}
											$predone = false;
											if($_GET['error'] == ""){
												$predone = true;
											}
											if(!$predone) {
												?>
											<form name="pre" action="func/matrix.func.php" method="post">
                                            <input type="hidden" name="form" value="pre" />
                                            <p>Operation</p>
                                            <select name="type">
                                                <option value="null" selected="selected"></option>
                                                <option value="add">Addition</option>
                                                <option value="sub">Subtraction</option>
                                                <option value="mult">Multiplication</option>
                                            </select>	
												<div>
													<div style="float:left; padding-right:20px;">
														<h3>Matrix A</h3>
														<div id="quizz" style="margin-left:20px">
															Dimensions:<br />
															Rows: <input name="ra" type="number" min="1" max="100" value="<? if($pre) { echo $_POST['ra']; } else { echo "1"; }?>"/><br />
															Columns: <input name="ca" type="number" min="1" max="100" value="<? if($pre) { echo $_POST['ca']; } else { echo "1"; }?>"/><br />
														</div>
													</div>
													<div style="float:left; padding-right:20px;">
														<h3>Matrix B</h3>
														<div id="quizz" style="margin-left:20px">
															Dimensions:<br />
															Rows: <input name="rb" type="number" min="1" max="100" value="<? if($pre) { echo $_POST['rb']; } else { echo "1"; }?>"/><br />
															Columns: <input name="cb" type="number" min="1" max="100" value="<? if($pre) { echo $_POST['cb']; } else { echo "1"; }?>"/><br />
														</div>
													</div>
												</div>
												<div style="clear:both;"></div><br /><br />
												<input type="submit" name="submit" value="Begin" />
												</form>
                                                <?
											} else {
												$ra = (int) $_GET['ra'];
												$ca = (int) $_GET['ca'];
												$rb = (int) $_GET['rb'];
												$cb = (int) $_GET['cb'];
												?>
                                                <form method="post" action="func/matrix.func.php">
                                                <input type="hidden" name="form" value="calc" />
												<div>
													<div style="float:left; padding-right:40px;">
														<h3>Matrix A</h3>
														<div id="quizz" style="margin-left:20px">
															<? echo $ra." Rows. ".$ca." Columns."; ?><br />
                                                            <?
															for($y = 0; $y < $ra; $y++){
																for($x = 0; $x < $ca; $x++){
																	?>
                                                                    <input type="number" name="<? echo "a".$y."[".$x."]"; ?>" size="8" style="width:4em;"/>
                                                                    <?
																}
																echo "<br>";
															}
															?>
														</div>
													</div>
													<div style="float:left; padding-right:40px;">
														<h3>Matrix B</h3>
														<div id="quizz" style="margin-left:20px">
															<? echo $rb." Rows. ".$cb." Columns."; ?><br />
                                                            <?
															for($y = 0; $y < $rb; $y++){
																for($x = 0; $x < $cb; $x++){
																	?>
                                                                    <input type="number" name="<? echo "b".$y."[".$x."]"; ?>" size="8" style="width:4em;"/>
                                                                    <?
																}
																echo "<br>";
															}
															?>
														</div>
													</div>
												</div>
												<div style="clear:both;"></div><br /><br />
												<input type="submit" name="submit" value="Calculate" />
												</form>
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