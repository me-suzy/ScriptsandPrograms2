<?
// This forum was developed by Adam M. B. from aWeb Labs
// Visit us at http://www.labs.aweb.com.au
// for forum problems, bugs, or ideas email yougotmail@gmail.com
// thanks for trying out or using this forum
// aWebBB version 1.2 released under the GNU GPL
include "config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
//Get the data

$query = "SELECT sitename, forumname, sitetitle, menulink, normallink, defimage, defsig, backcolor, msitecolor, siteurl, headimage, hiwidth, hiheight, forumcolor, normaltext, adenable, adcode, adlocation FROM prefs LIMIT 0,1"; 
 
$result = mysql_query($query); 
/* Here we fetch the result as an array */ 
while($r=mysql_fetch_array($result)) 
{ 
/* This bit sets our data from each row as variables, to make it easier to display */ 
$sitename = $r["sitename"]; 
$forumname = $r["forumname"]; 
$sitetitle = $r["sitetitle"]; 
$menulink = $r["menulink"]; 
$normallink = $r["normallink"]; 
$definamge = $r["defimage"]; 
$defsig = $r["defsig"]; 
$backcolor = $r["backcolor"]; 
$msitecolor = $r["msitecolor"]; 
$siteurl = $r["siteurl"]; 
$headimage = $r["headimage"]; 
$hiwidth = $r["hiwidth"]; 
$hiheight = $r["hiheight"];  
$forumcolor = $r["forumcolor"];  
$normaltext = $r["normaltext"]; 
$adenable = $r["adenable"];  
$adcode = $r["adcode"];  
$adlocation = $r["adlocation"];  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/1999/REC-html401-19991224/">
<html>
 <head>
  <title><?=$sitetitle;?> - Powered by aWebBB</title>
<style type="text/css">
<!--

body {
font-family: verdana, arial, helvetica, sans-serif; font-size: 11px; color: <?=$normaltext;?>; background-color: <?=$backcolor;?>; }
table {
font-family: verdana, arial, helvetica, sans-serif; font-size: 11px;}
a:link { font-family: verdana; font-size: 11px; color: <?=$normallink;?>; text-decoration: none; }
a:visited { font-family: verdana; font-size: 11px; color: <?=$normallink;?>; text-decoration: none; }
a:active { font-family: verdana; font-size: 11px; color: <?=$normallink;?>; text-decoration: none; }
a:hover { font-family: verdana; font-size: 11px; color: <?=$normallink;?>; text-decoration: underline; }
a.menu:link { font-family: verdana; font-size: 11px; color: <?=$menulink;?>; text-decoration: none; }
a.menu:visited { font-family: verdana; font-size: 11px; color: <?=$menulink;?>; text-decoration: none; }
a.menu:active { font-family: verdana; font-size: 11px; color: <?=$menulink;?>; text-decoration: none; }
a.menu:hover { font-family: verdana; font-size: 11px; color: <?=$menulink;?>; text-decoration: underline; }
div.content {display: block; width: 700px; padding: 0px; margin-bottom: auto; margin-right: auto; margin-left: auto; margin-top: 2px; text-align: center; border: 1px solid <?=$msitecolor;?>; background-color: <?=$forumcolor;?>;}
div.mainframe {display: block; width: 700px; padding: 0px; margin-bottom: auto; margin-right: auto; margin-left: auto; margin-top: auto; text-align: center;}
div.header {display: block; width: 700px; padding: 0px; margin-bottom: auto; margin-right: auto; margin-left: auto; margin-top: auto; text-align: center; border: 1px solid <?=$msitecolor;?>; background-color: <?=$forumcolor;?>;}
div.footer {display: block; width: 700px; padding: 0px; margin-bottom: auto; margin-right: auto; margin-left: auto; margin-top: 2px; text-align: center; border: 1px solid <?=$msitecolor;?>; background-color: <?=$forumcolor;?>;}
div.ad-box {display: block; width: 700px; padding: 0px; margin-bottom: 2px; margin-right: auto; margin-left: auto; margin-top: 2px; text-align: center; border: 1px solid <?=$msitecolor;?>; background-color: <?=$forumcolor;?>;}
div.buttonsbar {display: block; width: 700px; padding: 0px margin-bottom: auto; margin-right: auto; margin-left: auto; margin-top: auto; text-align: center; border-top: 0px solid <?=$msitecolor;?>; background-color: <?=$msitecolor;?>;}
div.main-box {margin-left:2px; padding:1px; width: 692px; background:<?=$forumcolor;?>; margin-bottom: 2px; margin-right: 2px; margin-top: 2px; border: 1px dashed black; text-align: left;}
div.main2-box {position:absolute; margin-left:2px; padding-left:2px; width: 538px; background:<?=$forumcolor;?>; margin-bottom: 2px; margin-right: 2px; margin-top: 0px; border: 0px inset black; text-align: left;}
div.side-box {margin-left:548px; width:139px; padding-left:2px; background:<?=$forumcolor;?>; margin-bottom: 2px; margin-right: 2px; margin-top: 2px; border: 1px dotted black; text-align: left;}
div.error-box {width:200px; background:pink; margin-top: 2px; border: 1px solid red; text-align: center;}
div.blue-box {width:600px; background:#e5ecf9; margin-top: 2px; border: 1px solid blue; text-align: left;}
div.bluein-box {width:350px; background:#e5ecf9; margin-top: 2px; border: 1px solid blue; text-align: left;}
div.grey-box {width:400px; background:#DDDDDD; margin-top: 2px; border: 1px solid gray; text-align: left;}
div.greyin-box {width:300px; background:#DDDDDD; margin-top: 2px; margin-left: 2px; border: 1px solid gray; text-align: left;}
div.side-headline {background:<?=$forumcolor;?>; margin-bottom: 2px; margin-right: 2px; margin-top: 2px; border-bottom: 1px solid <?=$msitecolor;?>; text-align: left;}
div.breaker {margin-bottom: 2px; margin-right: 2px; margin-left: 2px; margin-top: 2px; border-bottom: 1px solid blue; text-align: left;}
div.right1 {background-color: <?=$forumcolor;?>;}

//-->
</style>

 </head>
 <body>

<div class="mainframe">
<?
if ($adenable == "yes" & $adlocation == "top") {
echo "<div class=\"ad-box\">" . $adcode . "</div>";
} else { } 
?>
  <div class="header"><a href="<?=$siteurl;?>">
<?
if ($headimage == "") {
$headerimage="<h2>$sitename</h2>";
} else { 
$headerimage="<img src=\"$headimage\" width=\"$hiwidth\" height=\"$hiheight\" border=\"0\" alt=\"$sitename Forum\">";
}
?>
<?=$headerimage;?></a>
 <div class="buttonsbar"><font color="white">
<?
$query = "SELECT bname, link FROM menu"; 
$result = mysql_query($query); 
/* Here we fetch the result as an array */ 
while($r=mysql_fetch_array($result)) 
{ 
$bname = $r["bname"];
$link = $r["link"];
echo "&nbsp;&nbsp;<a class=\"menu\" href=\"$link\">$bname</a>&nbsp;&nbsp;";
}
?></font>
</div>
  </div> 
<?
if ($adenable == "yes" & $adlocation == "below") {
echo "<div class=\"ad-box\">" . $adcode . "</div>";
} else { } 
?>
  <div class="content">
<div class="main-box">
<?
} 
mysql_close($db); 
?>