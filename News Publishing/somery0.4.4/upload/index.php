<?php include("admin/system/engine.php"); ?>

<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
<head>
<title> the aenied - somery testpage </title>
<meta name="author" content="virgil">
<meta name="keywords" content="virgil, thoughts, the aeneid, weblog, blog, e/n, en">
<meta name="description" content="another weblog">
<link rel="alternate" title="somery RSS feed" type="application/rss+xml" href="http://www.yoursite.net/feed.php">
<style>
 <!--
	td {font-family: verdana,tahoma,helvetica; color: 000000; font-size: 8pt;}
	A:link{text-decoration: none; color: #707070;}
	A:visited{text-decoration: none; color: #707070;}
	A:active{text-decoration: none; background-color: #800000; color: #e7e7e7;}
	A:hover{text-decoration: none; background-color: #800000; color: #e7e7e7;}
input
{
    background-color: #C5C5C5;
    border-bottom: #000000 1px solid;
    border-left: #000000 1px solid;
    border-right: #000000 1px solid;
    border-top: #000000 1px solid;
    color: #000000;
    font-family: Verdana, helvetica;
    font-size: 8pt;
}
select
{
    background-color: #C5C5C5;
    border-bottom: #000000 1px solid;
    border-left: #000000 1px solid;
    border-right: #000000 1px solid;
    border-top: #000000 1px solid;
    color: #000000;
    font-family: Verdana, helvetica;
    font-size: 8pt;
}
textarea
{
    background-color: #C5C5C5;
    border-bottom: #000000 1px solid;
    border-left: #000000 1px solid;
    border-right: #000000 1px solid;
    border-top: #000000 1px solid;
    color: #000000;
    font-family: Verdana, helvetica;
    font-size: 8pt;
}

 -->
</style>
</head>
<body bgcolor="#E7E7E7">

	<table align="center" width=400>
	 <tr>
	  <td valign="bottom" align="center">
		
		<a href="index.php"> the aenied - somery testpage </a>
			  

	  </td>
	 </tr>
	 <tr>
	  <td valign="top" align="center" width=400>
		
		<p align="Justify">
		<font face="verdana" size="1" color="#000000">

<table><tr><td valign=top>
<table bgcolor=efefef width=250 cellpadding=0 cellspacing=0><tr><td><b>archive</b></td></tr>
<tr><td bgcolor=cdcdcd>
		<?php archive("- %<br />","title","d/m/Y"); ?>
</td></tr>
</table><br />

<table bgcolor=efefef width=250 cellpadding=0 cellspacing=0><tr><td><b>previous/next</b></td></tr>
<tr><td bgcolor=cdcdcd>
		<?php prevnext(); ?>
</td></tr>
</table>




</td><td valign="top">
<?php while($row = mysql_fetch_object($result)) { ?>

<table bgcolor=efefef width=250 cellpadding=0 cellspacing=0><tr><td>
<b><?php permalink(); ?></b> - by <a href="mailto:<?php getauthor("email"); ?>"><?php getauthor("nickname"); ?></a><br>
<b>posted</b>: <?php getadate(); ?> - <?php getatime(); ?>
</td></tr>
<tr><td bgcolor=cdcdcd>
<?php body(); ?>
</td></tr>
<tr><td align=right>
<?php commentlink("no comments","1 comment","% comments"); ?>
</td></tr></table><br>
<?php include("comments.php"); ?><br>



<?php }; ?>


			<br><br><br><center>
			  <a href="index.php">home</a> | <a href="mailto:test@yahoo.com">contact</a> | <a href="admin">admin</a>
			</center>
		</font>
		

	  </td>
	 </tr>
	</table>

	</td></tr></table>
	<br>
</body>
</html>


