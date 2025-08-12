<?
session_start();
// This forum was developed by Adam M. B. from aWeb Labs
// Visit us at http://www.labs.aweb.com.au
// for forum problems, bugs, or ideas email yougotmail@gmail.com
// thanks for trying out or using this forum
// aWebBB version 1.2 released under the GNU GPL
include "header.php";
if ($_GET['c'] == "Bugzilla") {
$bugtext="<br>Please post any problems or errors with EduSlice in this section.";
} else { }
?>
<div align="center">
<div class="side-headline"><b>Threads About <?=$_GET['c'];?>:<?=$bugtext;?></b></div><br>
<div class="blue-box">
<table cellpadding="0" cellspacing="0" border="0" width="600"><tr><td width="320">
<div class="breaker"><b>Thread Name</b></div></td><td align="right" width="70"><div class="breaker"><b>Replies:</b></div></td><td align="right" width="120"><div class="breaker"><b>Started By:</b></div></td><td align="left" width="90"><div class="breaker"><b>Last Post</b></div></td></tr>
<?
include "config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
//Get the data
$query = "SELECT id, tid, categories, tname, poster, date FROM flist WHERE categories = '$_GET[c]' ORDER BY id DESC"; 
$result = mysql_query($query); 
/* Here we fetch the result as an array */ 
while($r=mysql_fetch_array($result)) 
{ 
/* This bit sets our data from each row as variables, to make it easier to display */ 
$id=$r["id"]; 
$tid=$r["tid"]; 
$title=$r["tname"]; 
$poster=$r["poster"]; 
$date=$r["date"]; 
$gdate=$r["date"];
$cat1=$_GET['c'];


echo "<tr><td><div class=\"breaker\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='ndis.php?c=$cat1&tid=$tid&t=$title'><b>- $title</b></a></div></td><td align=\"left\"><div class=\"breaker\"><div align=\"center\">";
$query2="SELECT * FROM forum WHERE tid = $tid";
$result2 = mysql_query($query2);
 $num_rows2 = mysql_num_rows($result2);
$presum=$num_rows2 - 1;
echo "$presum"; 


echo "</div></div></td><td align=\"left\"><div class=\"breaker\">$poster</div></td><td align=\"right\"><div class=\"breaker\">";
$query1 = "SELECT date FROM forum WHERE categories = '$_GET[c]' AND tid = '$tid' ORDER BY date DESC LIMIT 0,1"; 
$result1 = mysql_query($query1); 
/* Here we fetch the result as an array */ 
while($r=mysql_fetch_array($result1)) 
{
echo $r["date"]; 
}
echo "</div></td></tr>"; 
} 

mysql_close($db); 
echo "</table></div></div>";
include "footer.php";
?>