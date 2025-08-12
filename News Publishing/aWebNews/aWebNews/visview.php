<?php
// This script was developed by Adam M. B. from aWeb Labs
// Visit us at http://www.labs.aweb.com.au
// for forum problems, bugs, or ideas email yougotmail@gmail.com
// thanks for trying out or using this news script
include "" . $path_to_news . "config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
if ($news_width1 == "") {
$news_width = "550px";
} else {
$news_width = $news_width1;
}
?>
<!-- News powered by aWebNews @ www.labs.aweb.com.au aWebNews:
A free news and comment management script. --><style type="text/css">
<!--
div.green-box {width:<?=$news_width;?>; padding: 1px; background:#D0EED0; margin-top: 2px; border: 1px solid green; text-align: center;}
div.grey-box {width: <?=$news_width;?>; background: #DDDDDD; margin-top: 2px; border: 1px solid gray; text-align: left;}
//-->
</style>
<?
if ($number_of_stories == "") {
$numnow = "8";
} else {
$numnow = $number_of_stories;
}
if ($_GET['a'] == "") {
if ($_GET['b'] == "newc") {
$wherelimiter = " WHERE cid = '$_GET[cid]'";
} else {
$wherelimiter = "";
}
$query = "SELECT id, cid, category, title, author, shorta, longa, datetime, eauthor FROM news$wherelimiter ORDER BY id DESC LIMIT 0,$numnow"; 
$result = mysql_query($query); 
while($r=mysql_fetch_array($result)) 
{ 
$id = $r["id"];  
$cid = $r["cid"];  
$category = $r["category"];  
$title = $r["title"];  
$author = $r["author"];  
$shorta = $r["shorta"];  
$longa = $r["longa"];  
$datetime = $r["datetime"];  
$eauthor = $r["eauthor"];  
$query7="SELECT * FROM comments WHERE cid = '$cid'";
$result7 = mysql_query($query7);
$commentsnum = mysql_num_rows($result7);
?>
<table cellpadding="0" cellspacing="0" width="<?=$news_width;?>" style="margin-top: 2px; border: 1px solid gray; padding: 3px;" align="center"><tr><td style="border-bottom: 1px solid #DDDDDD"><b><?=$category;?>: <?=$title;?></b> <i>by: <a href="mailto:<?=$eauthor;?>"><?=$author;?></a></i></td></tr><tr><td><?=$shorta;?></td></tr><tr><td align="right" style="border-top: 1px dashed #DDDDDD;"><a href="<?=$_SERVER['PHP_SELF'];?>?a=c&cid=<?=$cid;?>"><?=$commentsnum;?> Comments</a> | <?=$datetime;?></td></tr></table>
<? }
} else { }
if ($_GET['a'] == "c") { ?>
<div align="center">
<?
$query = "SELECT id, cid, category, title, author, shorta, longa, datetime, eauthor FROM news WHERE cid = '$_GET[cid]' LIMIT 0,1"; 
$result = mysql_query($query); 
while($r=mysql_fetch_array($result)) 
{ 
$id = $r["id"];  
$cid = $r["cid"];  
$category = $r["category"];  
$title = $r["title"];  
$author = $r["author"];  
$shorta = $r["shorta"];  
$longa = $r["longa"];  
$datetime = $r["datetime"];  
$eauthor = $r["eauthor"];  
$query7="SELECT * FROM comments WHERE cid = '$cid'";
$result7 = mysql_query($query7);
$commentsnum = mysql_num_rows($result7);
?>
<table cellpadding="0" cellspacing="0" width="<?=$news_width;?>" style="margin-top: 2px; border: 1px solid gray; padding: 3px;" align="center"><tr><td style="border-bottom: 1px solid #DDDDDD"><b><?=$category;?>: <?=$title;?></b> <i>by: <a href="mailto:<?=$eauthor;?>"><?=$author;?></a></i></td></tr><tr><td><?
if ($longa == "") {
echo $shorta;
} else {
echo $longa;
}
?></td></tr><tr><td align="right" style="border-top: 1px dashed #DDDDDD;"><?=$commentsnum;?> Comments | <?=$datetime;?></td></tr></table>
<? }
?>
<div class="green-box"><table cellpadding="0" cellspacing="0" border="0" align="center"><tr><td>
<form method="get" action="<?=$_SERVER['PHP_SELF'];?>"><input type="hidden" name="cid" value="<?=$_GET['cid'];?>"><input type="hidden" name="a" value="<?=$_GET['a'];?>">Sort By: <select name="c" id="c"><option value="id DESC">Newest First</option><option value="id">Oldest First</option></select><input type="submit" value="Change"></form></td><td><form method="post" action="<?=$_SERVER['PHP_SELF'];?>?b=newc&cid=<?=$_GET['cid'];?>"><input type="submit" value="Reply"></form></td></tr></table>
</div></div>
<?
if ($_GET['c'] == "") {
$sorty = "id DESC";
} else { 
$sorty = $_GET['c'];
}
$query3 = "SELECT id, yname, emailadd, subject, comment, datetime FROM comments WHERE cid = '$_GET[cid]' ORDER BY $sorty"; 
$result3 = mysql_query($query3); 
while($r=mysql_fetch_array($result3)) 
{ 
$id = $r["id"];  
$yname = $r["yname"];  
$emailadd = $r["emailadd"];  
$subject = $r["subject"];  
$comment = $r["comment"];  
$datetime = $r["datetime"];  
?>
<table cellpadding="0" cellspacing="0" width="<?=$news_width;?>" style="margin-top: 2px; border: 1px solid gray; padding: 1px;" align="center"><tr><td style="border-bottom: 1px solid #DDDDDD"><b><?=$subject;?></b></td></tr><tr><td><?=$comment;?></td></tr><tr><td align="right" style="border-top: 1px dashed #DDDDDD;"><a href="<?=$emailadd;?>" target="_Blank"><?=$yname;?></a> | <?=$datetime;?></td></tr></table>
<?
}
} else { }
if ($_GET['b'] == "newc") {
$datetime = date("l dS of F Y h:i:s A"); 
?>
<div align="center">
<form method="post" name="news" action="<?=$_SERVER['PHP_SELF'];?>?b=newc&c=post&cid=<?=$_GET['cid'];?>">
<div class="grey-box"><table cellpadding="0" cellspacing="0" border="0"><tr><td width="130">Your Name:</td><td><input type="text" size="30" name="yname"></td></tr></table></div>
<div class="grey-box"><input type="hidden" name="datetime" value="<?=$datetime;?>"><input type="hidden" name="cid" value="<?=$_GET['cid'];?>">
<table cellpadding="0" cellspacing="0" border="0"><tr><td width="130">Email / Website:</td><td><input type="text" size="30" name="emailadd" value="mailto:"></td></tr></table></div>
<div class="grey-box">
<table cellpadding="0" cellspacing="0" border="0"><tr><td width="130">Comment Subject:</td><td><input type="text" size="30" name="subject"></td></tr></table></div><div class="grey-box">
Comment Text:<br>
<textarea rows="5" cols="40" name="comment"></textarea></div><div class="grey-box"><div align="center">
<input type="submit" value="Post Comment"></div></div></form>
<br>
<?
if ($_GET['c'] == "post") {
$query8 = "INSERT INTO comments(cid, yname, emailadd, subject, comment, datetime) 
VALUES('$_GET[cid]','$_POST[yname]','$_POST[emailadd]','$_POST[subject]','$_POST[comment]','$_POST[datetime]')"; 
mysql_query($query8); 
echo "<div align=\"center\">Comment Saved</div>";
$cider = $_POST['cid'];
?>
<meta http-equiv="refresh" content="1;url=<?=$_SERVER['PHP_SELF'];?>?a=c&cid=<?=$_GET['cid'];?>"> 
<?
} else { }
} else { }
mysql_close($db); 

?> 