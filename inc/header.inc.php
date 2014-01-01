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
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,700,800" rel="stylesheet" type="text/css" />
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.dropotron.min.js"></script>
		<script src="js/config.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-panels.min.js"></script>
        <script src="js/jquery-1.7.2.min.js"></script>
		<script src="js/lightbox.js"></script>
        <script type="text/javascript" src="js/tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
        tinymce.init({
            selector: "textarea",
            plugins: [
            <?
            if($page=="takequiz" || $page=="home"){
                ?>
                 "autolink link lists charmap spellchecker",
                 "searchreplace wordcount fullscreen",
                 "table contextmenu paste textcolor"
                 <?
            } else {
                ?>
                 "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                 "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                 "save table contextmenu directionality emoticons template paste textcolor"
                <?
            }
            ?>
           ]
         });
        </script>
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
											<h1><a href="?p=home" id="logo" style="text-transform:none;">PolyQuiz</a></h1>
										
										<!-- Nav -->
											<nav id="nav">
												<ul>
													<li <? if($page=="home"){ echo 'class="current_page_item"'; } ?>><a href="?p=home">Home</a></li>
                                                    <li <? if($page=="takequiz"){ echo 'class="current_page_item"'; } ?>><a href="?p=takequiz">Take a Quiz</a></li>
                                                    <li <? if($page=="admin"){ echo 'class="current_page_item"'; } ?>><a href="?p=admin">The Core</a></li>
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
                                    <a href="?p=takequiz" class="button big fa">Take A Quiz!</a>
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