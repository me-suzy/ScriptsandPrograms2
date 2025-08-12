<? session_start();
include ("snews.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta http-equiv="Content-Language" content="English" />
	<meta name="Author" content="Solucija.com" />
	<meta name="Robots" content="index,follow" />
	<meta name="Description" content="sNews | Single file CSS and XHTML valid CMS" />
	<meta name="Keywords" content="snews, simple, cms, css, xhtml, valid" />
	<link rel="stylesheet" type="text/css" href="images/style.css" />
	<? title(); ?>
</head>
<body>
	<div class="content">
		<div class="hmenu">
			<? categories(); ?>
		</div>
		
		<div class="header">
			<h1><a href="http://snews.solucija.com/" title="sNews">sNews</a></h1><p>Single file CMS</p>
		</div>
			
		<div class="left">
			<div class="menu">
				<? menu_items(); ?>
			</div>
						
			<div class="menu">
			<? searchform(); ?>
			</div>
			
			<div class="new">
			<p><b>New articles:</b></p>
				<? new_articles(3); ?>
			</div>
			
			<div class="past">
			<p><b>Past entries:</b></p>
				<? past_entries(4,3); ?>
			</div>
			<? left(); ?>
		</div>
		
		<div class="center">
		<? center(15); ?>	
		</div>

		<div class="footer">
  			<div class="right">
    			<p>Powered by <a href="http://snews.solucija.com" title="Single file CSS and XHTML valid CMS">sNews</a></p>	
  				<p>&copy; Copyright 2005 <a href="http://www.yoursite.com/" title="YourSite">YourSite</a>, All rights reserved <img src='images/arrow.gif' alt='' /> <a href="?action=login" title="sNews login">Login</a></p>
    		</div>
    		<p><a href="index.php?action=rss">RSS Feed</a></p>
    		<p><a href="http://jigsaw.w3.org/css-validator/check/referer" title="Validate CSS">CSS</a> and <a href="http://validator.w3.org/check/referer" title="Validate XHTML">XHTML</a> barbecue</p>
		</div>
	</div>
</body>
</html>