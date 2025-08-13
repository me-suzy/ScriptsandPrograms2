<?php
	/*-------------------------------------------------------------------*\
	 | Editize API for PHP demo (Mac OS 10.1 Compatible)                 |
	 | by Kevin Yank                                                     |
	 |                                                                   |
	 | This example presents a form with only one instance of Editize.   |
	 | Due to a bug in Mac OS 10.1, multiple signed Java applets in a    |
	 | single page will cause the browser to hang when the security      |
	 | dialog box appears. This example therefore uses a single Editize  |
	 | applet with a built-in submit button, and should work well on     |
	 | Mac OS 10.1. NOTE: Mac OS 10.2 fixes this bug, so multiple        |
	 | instances of Editize may safely be used with that version.        |
	\*-------------------------------------------------------------------*/

	/* First we load the Editize API for PHP */
	require('editize.php'); 

	/* Assign some variables for the values that will be passed by the form
	   submissions in this script. */
	$title   = isset($HTTP_POST_VARS['title']) ? $HTTP_POST_VARS['title'] : "";
	$article = isset($HTTP_POST_VARS['article']) ? $HTTP_POST_VARS['article'] : "";
	
	/* So that this script will work on PHP installations with
	   magic_quotes_gpc turned on and off, we detect this setting and strip
	   off any slashes that have been added to our variables by PHP. */
	if (get_magic_quotes_gpc())
	{
		$title   = stripslashes($title);
		$article = stripslashes($article);
	}  
?>
<html>
<head>
<title>Editize API for PHP Demo</title>
<!-- The following styles reflect the formatting of the Web site, which
     Editize will be configured to emulate... -->
<style type="text/css">
p, body {
  font-family: Verdana;
  font-size: 10pt;
}
h1 {
  font-family: Arial;
  font-size: 20pt;
}
h3 {
  font-family: Arial;
  font-size: 16pt;
}
.articletext, .articletext p {
  font-family: Verdana;
  font-size: 14px;
}
.highlighted { color: red; }
</style>
<?php if (isset($HTTP_POST_VARS['edited'])): ?><base href="http://www.sitepoint.com/" /><?php endif; ?>
</head>
<body bgcolor="#FFFFFF" text="#000000">
<?php
	/* Determine if the form has been submitted or not. */
	if (!isset($HTTP_POST_VARS['edited'])):	// If the form has not been submitted
?>
<p>This sample form contains an Editize field. It's designed to look like a
   typical form that you might see in a content management system.</p>
<form action="http://<?=$HTTP_SERVER_VARS['HTTP_HOST'].$HTTP_SERVER_VARS['PHP_SELF']?>" method="post">
<h3>Title:</h3>
<input type="text" name="title" value="<?=htmlspecialchars($title)?>" size="30" />
<h3>Article:</h3>
<?php
	/* Here's our Editize field. We leave all the features enabled
	   and configure a 14 pixel font size, which matches the
	   stylesheet setting for the article text size (see above).
	   In addition, we set a base URL for images and provide an image
	   list URL. Finally, we enable the integrated submit button to
	   avoid having to use a second applet for this (which would hang
	   Mac OS 10.1). */
	$ed = new Editize;
	$ed->name = 'article';
	$ed->width = '100%';
	$ed->height = '400';
	$ed->basefontface = 'Verdana';
	$ed->basefontsize = '14';
	$ed->baseurl = 'http://www.sitepoint.com/';
	$ed->imglisturl = 'http://www.sitepoint.com/graphics/imglist.php';
	$ed->linkurls[] = 'mailto:';
	$ed->linkurls[] = 'http://www.sitepoint.com/article.php/';
	$ed->showsubmitbutton = TRUE;
	$ed->submitbuttonlabel = 'Submit Article';
	$ed->display($article);
?><br />
<input type="hidden" name="edited" value="true" />
</form>
<?php
	else: // If the form has been submitted
?>
<h1><?=$title?></h1>
<h3>Article:</h3>
<div class="articletext"><?=$article?></div>
<br clear="all" />
<!-- This form will re-submit the article for editing -->
<form action="http://<?=$HTTP_SERVER_VARS['HTTP_HOST'].$HTTP_SERVER_VARS['PHP_SELF']?>" method="POST">
<input type="hidden" name="title" value="<?=htmlspecialchars($title)?>" />
<input type="hidden" name="article" value="<?=htmlspecialchars($article)?>" />
<input type="submit" name="edit" value="Edit Further" />
</form>
<?php endif; ?>
</body>
</html>