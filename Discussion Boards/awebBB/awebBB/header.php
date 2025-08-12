<? 
include "header2.php"; 
include "config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
//Get the data

$query = "SELECT sitename, forumname, sitetitle, menulink, normallink, defimage, defsig, backcolor, msitecolor, siteurl, headimage, hiwidth, hiheight FROM prefs"; 
 
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
?>
<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td bgcolor="<?=$msitecolor;?>">
<?
if ($_SESSION['Username'] == "" AND $_SESSION['Logged_In'] != "True" OR $_SESSION['Logged_In'] == "True-Admin") {
$log_button = "<a href=\"login.php\" class=\"menu\">Login</a>";
} else {
$log_button = "<a href=\"login.php?mode=logout\" class=\"menu\">Logout</a>";
}
?><a href="index.php" class="menu"><b>The <?=$sitename;?> Forum</b></a></td><td bgcolor="<?=$msitecolor;?>" align="right">&nbsp;&nbsp;&nbsp;<a href="post.php?c=<?=$_GET['c'];?>" class="menu">New Thread</a>&nbsp;&nbsp;&nbsp;<a href="search.php" class="menu">Search</a>&nbsp;&nbsp;&nbsp;<a href="stat.php" class="menu">Statistics</a>&nbsp;&nbsp;&nbsp;<a href="accounts.php" class="menu">Account Settings</a>&nbsp;&nbsp;&nbsp;<?=$log_button;?>&nbsp;&nbsp;&nbsp;</td></tr><tr><td bgcolor="<?=$msitecolor;?>" colspan="2" align="center">
<?
} 
mysql_close($db); 

//connection to database
include "config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "SELECT category FROM fcat ORDER BY id LIMIT 0,2"; 
$result = mysql_query($query); 
while($r=mysql_fetch_array($result)) 
{ 
$cname=$r["category"]; 
echo "<b><a href=\"list.php?c=$cname\" class=\"menu\">$cname</a>&nbsp;&nbsp;&nbsp;</b>"; 
} 
mysql_close($db); ?>
<b><a href="listcat.php" class="menu">List All</a></b>
</td></tr></table>