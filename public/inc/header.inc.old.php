<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PolyQuiz</title>
<link rel="stylesheet" type="text/css" href="styles.css">
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
<!--[if lte IE 7]>
<style>
.content { margin-right: -1px; } /* this 1px negative margin can be placed on any of the columns in this layout with the same corrective effect. */
ul.nav a { zoom: 1; }  /* the zoom property gives IE the hasLayout trigger it needs to correct extra whiltespace between the links */
</style>
<![endif]--></head>
<body>
<?
?>
<div class="container">
  <div class="header"><a href="?p=home"><img src="images/logo.png" alt="Insert Logo Here" name="Insert_logo" width="400" height="90" id="Insert_logo" style="background-color: #8090AB; display:block;" /></a> 
    <!-- end .header --></div>
  <div class="sidebar1">
    <ul class="nav">
      <li><a href="?p=home" <? if(!isset($_SESSION["firstname"]) && !isset($_SESSION["lastname"])){ echo "onclick='window.onbeforeunload = null;'";}?>>Home</a></li>
      <li><a href="?p=survey">Survey</a></li>
      <li><a href="?p=admin" <? if(!isset($_SESSION["firstname"]) && !isset($_SESSION["lastname"])){ echo "onclick='window.onbeforeunload = null;'";}?>>The Core</a></li>
    </ul>
    <p></p>
    <!-- end .sidebar1 --></div>
