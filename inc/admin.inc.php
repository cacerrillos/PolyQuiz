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
function checkPass()
{
    //Store the password field objects into variables ...
    var pass1 = document.getElementById('pass1');
    var pass2 = document.getElementById('pass2');
    //Store the Confimation Message Object ...
    var message = document.getElementById('confirmMessage');
    //Set the colors we will be using ...
    var goodColor = "#66cc66";
    var badColor = "#ff6666";
    //Compare the values in the password field 
    //and the confirmation field
    if(pass1.value == pass2.value){
        //The passwords match. 
        //Set the color to the good color and inform
        //the user that they have entered the correct password 
        pass2.style.backgroundColor = goodColor;
        message.style.color = goodColor;
        message.innerHTML = "Passwords Match!"
    }else{
        //The passwords do not match.
        //Set the color to the bad color and
        //notify the user.
        pass2.style.backgroundColor = badColor;
        message.style.color = badColor;
        message.innerHTML = "Passwords Do Not Match!"
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
                                                    <span class="byline">
                                                    Welcome back admin!<br />
                                                    Quick Start Guide:<br />
                                                    1) Create a Quiz under "Edit Quizzes" & add questions to it.<br />
                                                    2) Create a Quiz Session under "Edit Sessions" and be sure to select your desired quiz.<br />
                                                    3) Use the Session Id & Session Key to let others take your quiz.<br />
                                                    4) View the results under "Quiz Results".<br />
                                                    *) Should any quiz taker accidentally exit their quiz early,<br />find their name under "Quizzes In Progress" and give them the "Restore Id" & "Restore Key".
                                                    </span>
                                                    <?
													}
													?>
												</header>
                                            	<?
												if(isset($_SESSION["is_admin"])){
												?>
                                                <h2>Password Management</h2>
                                                <h4>You will be logged out if your password was changed successfully.</h4>
                                                <form action="func/admin.password.php" method="post">
                                                <div class="tutorialWrapper">
                                                    <label for="oldpass">Old Password: </label>
                                                    <input type="password" name="oldpass" id="oldpass" />
                                                        <div class="fieldWrapper">
                                                            <label for="pass1">New Password: </label>
                                                            <input type="password" name="pass1" id="pass1">
                                                        </div>
                                                        <div class="fieldWrapper">
                                                            <label for="pass2">Confirm New Password: </label>
                                                            <input type="password" name="pass2" id="pass2" onkeyup="checkPass(); return false;">
                                                            <span id="confirmMessage" class="confirmMessage"></span>
                                                        </div>
                                                </div>
                                                <input type="submit" name="submit" value="Change" class="button alt fa fa-lock">
                                                </form>
                                                <?
												$xkcd[0]['src'] = "http://imgs.xkcd.com/comics/computer_problems.png";
												$xkcd[0]['title'] = "This is how I explain computer problems to my cat. My cat usually seems happier than me.";
												$xkcd[0]['alt'] = "Computer Problems";
												$xkcd[1]['src'] = "http://imgs.xkcd.com/comics/in_ur_reality.png";
												$xkcd[1]['title'] = "Hey, at least I ran out of staples.";
												$xkcd[1]['alt'] = "IN UR REALITY";
												$xkcd[2]['src'] = "http://imgs.xkcd.com/comics/a-minus-minus.png";
												$xkcd[2]['title'] = "You can do this one in every 30 times and still have 97% positive feedback.";
												$xkcd[2]['alt'] = "A-Minus-Minus";
												$xkcd[3]['src'] = "http://imgs.xkcd.com/comics/exploits_of_a_mom.png";
												$xkcd[3]['title'] = "Her daughter is named Help I'm trapped in a driver's license factory.";
												$xkcd[3]['alt'] = "Exploits of a Mom";
												$xkcd[4]['src'] = "http://imgs.xkcd.com/comics/pointers.png";
												$xkcd[4]['title'] = "Every computer, at the unreachable memory address 0x-1, stores a secret.  I found it, and it is that all humans ar-- SEGMENTATION FAULT.";
												$xkcd[4]['alt'] = "Pointers";
												$xkcd[5]['src'] = "http://imgs.xkcd.com/comics/a_new_captcha_approach.png";
												$xkcd[5]['title'] = "They'd use that Futurama episode with Fry's dog, but even spambots cry at that.";
												$xkcd[5]['alt'] = "A New CAPTCHA Approach";
												$xkcd[6]['src'] = "http://imgs.xkcd.com/comics/floor_tiles.png";
												$xkcd[6]['title'] = "The worst part is when sidewalk cracks are out-of-sync with your natural stride.";
												$xkcd[6]['alt'] = "Floor Tiles";
												$xkcd[7]['src'] = "http://imgs.xkcd.com/comics/donald_knuth.png";
												$xkcd[7]['title'] = "His books were kinda intimidating; rappelling down through his skylight seemed like the best option.";
												$xkcd[7]['alt'] = "Donald Knuth";
												$xkcd[8]['src'] = "http://imgs.xkcd.com/comics/lease.png";
												$xkcd[8]['title'] = "You should talk to the girl down the hall; I think you'd like her.  Lemme know if you find out why she's ordering all those colored plastic balls.";
												$xkcd[8]['alt'] = "Lease";
												
												$num = rand(0,8);
												/* <img src="<? echo $xkcd[$num]['src']; ?>" title="<? echo $xkcd[$num]['title']; ?>" alt="<? echo $xkcd[$num]['alt']; ?>"> */
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