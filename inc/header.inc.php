<!DOCTYPE HTML>
<!--
	ZeroFour 2.5 by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>PolyQuiz</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,700,800" rel="stylesheet" type="text/css" />
        <link href='https://fonts.googleapis.com/css?family=Arvo' rel='stylesheet' type='text/css'>
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.dropotron.min.js"></script>
		<script src="js/config.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-panels.min.js"></script>
        <script src="js/jquery-1.7.2.min.js"></script>
		<script src="js/lightbox.js"></script>
        <script src="js/sketch.js"></script>
        <script src="js/overlay.js"></script>
        <link rel="stylesheet" type="text/css" href="js/mathquill.css">
        <script src="js/mathquill.min.js"></script>
        <script type="text/javascript" src="js/tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
		tinymce.PluginManager.load('equationeditor', '/build/js/plugin.min.js');
        tinymce.init({
            selector: "textarea",
            plugins: [
            <?
            if($page=="takequiz" || $page=="home"){
                ?>
                 "autolink link lists charmap spellchecker",
                 "searchreplace wordcount fullscreen",
                 "table contextmenu paste textcolor equationeditor"
                 <?
            } else {
                ?>
                 "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                 "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                 "save table contextmenu directionality emoticons template paste textcolor equationeditor"
                <?
            }
            ?>
           ],
		   toolbar: ['undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | equationeditor'],
		   content_css: 'build/mathquill.css'
         });
        </script>
        <script>
			function addCanvas(){
				var canvas = document.getElementById('myCanvas');
				var context = canvas.getContext('2d');
				var dataURL = canvas.toDataURL();
				document.getElementById("canvasValue").value = dataURL;
			}
		</script>
        <script type="text/javascript">
		$(document).ready(function() {
			$("a[rel]").overlay({
				left: 'center',
				top: 50,
				fixed: false});
		});
		</script>
        <style type="text/css">
		.simple_overlay {
		 
			/* must be initially hidden */
			display:none;
		 	/*position:absolute;
			/* place overlay on top of other elements */
			/*top:50%;
			left: 50%;*/
			z-index:10000;
			/* styling */
			background-color:#333;
			width:675px;
			min-height:200px;
			border:1px solid #666;
			color:#FFF;
			/* CSS3 styling for latest browsers */
			-moz-box-shadow:0 0 90px 5px #000;
			-webkit-box-shadow: 0 0 90px #000;
		}
		 
		/* close button positioned on upper right corner */
		.simple_overlay .close {
			background-image:url(images/close_popup.png);
			position:absolute;
			right:-15px;
			top:-15px;
			cursor:pointer;
			height:35px;
			width:35px;
		}
		</style>
        <link href="css/lightbox.css" rel="stylesheet" />
		<noscript>
			<link rel="stylesheet" href="css/skel-noscript.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-desktop.css" />
		</noscript>
		<!--[if lte IE 9]><link rel="stylesheet" href="css/ie9.css" /><![endif]-->
		<!--[if lte IE 8]><script src="js/html5shiv.js"></script><link rel="stylesheet" href="css/ie8.css" /><![endif]-->
		<!--[if lte IE 7]><link rel="stylesheet" href="css/ie7.css" /><![endif]-->
	</head>
    <?
	//$bodyclass = "homepage";
	if($page=="home"){
		$bodyclass = "homepage";
	} else {
		$bodyclass = "left-sidebar";
	}
	?>
	<body class="<? echo $bodyclass; ?>">

		<!-- Header Wrapper -->
			<div id="header-wrapper" style="position:relative;">
				<div class="container">
					<div class="row">
						<div class="12u">
						
							<!-- Header -->
								<header id="header">
									<div class="inner">
									
										<!-- Logo -->
											<a href="?p=home" id="logo" style="text-transform:none;"><img src="images/title.png" style="margin-left:10px; max-height:100%; max-width:100%" ></a> 
										<!-- Nav -->
											<nav id="nav">
												<ul>
													<li <? if($page=="home"){ echo 'class="current_page_item"'; } ?>><a href="?p=home"><i class="fa fa-home"></i> Home</a></li>
                                                    <li <? if($page=="register"){ echo 'class="current_page_item"'; } ?>><a href="?p=register"><i class="fa fa-plus-square"></i> Register</a></li>
                                                    <li <? if($page=="takequiz"){ echo 'class="current_page_item"'; } ?>><a href="?p=takequiz"><i class="fa fa-file"></i> Take a Quiz</a></li>
                                                    <li <? if($page=="math"){ echo 'class="current_page_item"'; } ?>><a href="?p=math"><i class="fa fa-flask"></i> Math</a></li>
                                                    <li <? if($page=="admin"){ echo 'class="current_page_item"'; } ?>><a href="?p=admin"><i class="fa fa-cog"></i> The Core</a></li>
												</ul>
											</nav>
									
									</div>
								</header>

							<!-- Banner -->
								<?
								if($page=="home"){
								?>
								<div id="banner">
                                    <div style="position: absolute; left: 50%; bottom: 5%; display:block;">
                                    <div style="position: relative; left: -50%;">
                                    <a href="?p=takequiz" class="button big fa"><i class="fa fa-file"></i> Take A Quiz!</a>
                                    </div>
                                    </div>
								</div>
                                <?
							}
                                ?>
						</div>
					</div>
				</div>
			</div>