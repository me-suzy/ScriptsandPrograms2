<?php
session_start();
include "header.php";
?>
<div class="side-headline"><b>Information About <?=$_GET['p'];?></b></div>
<div align="center"><br><div class="blue-box">
<?
include "config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query4 = "SELECT id, emailadd, fullname, country, date FROM users WHERE username='$_GET[p]' LIMIT 0,1"; 
$result4 = mysql_query($query4); 
while($r=mysql_fetch_array($result4)) 
{ 
/* This bit sets our data from each row as variables, to make it easier to display */ 
$id=$r["id"]; 
$emailadd=$r["emailadd"]; 
$fullname=$r["fullname"]; 
$country=$r["country"]; 
$date=$r["date"]; 
// display it all
echo "<b>General</b><br>";
echo "&nbsp;&nbsp;&nbsp;Email Address: <b>$emailadd</b><br>&nbsp;&nbsp;&nbsp;Country: <b>$country</b><br>&nbsp;&nbsp;&nbsp;Date " . $_GET['p'] . " signed up: <b>$date</b><br>"; 
}
?>
</div></div><br>
<div class="side-headline"><b>Posts By: <?=$_GET['p'];?></b></div>
<div align="center"><br>

<?
if ($_GET['id'] != "") {
$GetById = " WHERE id = '$_GET[id]'";
} else {
$GetById = " WHERE categories = '$_GET[c]'";
}
include "switcharray.php";
$query = "SELECT id, tid, categories, tname, poster, fpost, sig, avatar, time, date FROM forum WHERE poster = '$_GET[p]' ORDER BY date DESC"; 
 
$result = mysql_query($query); 
/* Here we fetch the result as an array */ 
while($r=mysql_fetch_array($result)) 
{ 
/* This bit sets our data from each row as variables, to make it easier to display */ 
$id=$r["id"]; 
$tid=$r["tid"]; 
$categories=$r["categories"]; 
$tname=$r["tname"]; 
$poster=$r["poster"]; 
$fpost=str_replace($smiliearray, $imagearray, $r["fpost"]); 
$sig=$r["sig"]; 
$avatar=$r["avatar"]; 
$time=$r["time"]; 
$date=$r["date"]; 

echo "<div class=\"blue-box\"><div class=\"breaker\"><a href=\"ndis.php?c=$categories&tid=$tid&t=$tname\"><b>$tname</b></a> by <a href=\"dpost.php?p=$poster\">$poster</a>, <i>Category: <a href=\"list.php?c=$categories\">$categories</a></i></div><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\"><tr><td height=\"80\" width=\"80\" rowspan=\"2\"><img src=\"$avatar\" border=\"0\" align=\"left\" width=\"80\" height=\"80\"></td><td valign=\"top\"><div class=\"breaker\">$fpost</div></td></tr><tr><td valign=\"bottom\"><div align=\"right\"><i>$sig</i><br>$time - $date</div></td></tr></table></div>"; 
 
}
mysql_close($db); 


echo "<br></div>";


include "footer.php";


?>