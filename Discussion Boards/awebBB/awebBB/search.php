<?
include "header.php";
// This forum was developed by Adam M. B. from aWeb Labs
// Visit us at http://www.labs.aweb.com.au
// for forum problems, bugs, or ideas email yougotmail@gmail.com
// thanks for trying out or using this forum
// aWebBB version 1.2 released under the GNU GPL
?>
<div class="side-headline"><b>
<?
if ($_GET['q'] == "") {
$title1 = "Search";
} else {
$souch=$_GET['q'];
$title1 = "Search Results For <b>$souch</b>:";	
}
echo $title1;
?>
</b></div><br>
<?
if ($_GET['a'] != 1) {
?>
<div align="center">
<div class="blue-box"><br><br><br><div align="center"><form action="search.php" method="get"><input type="hidden" name="a" value="1"><input type="text" name="q" size="45"><input type="submit" value="Search"></form><?=$error;?><br>&nbsp;</div></div>
<?
echo "</div>";
} else { }
$search=$_GET['q'];
if ($_GET['a'] == 1 & $search == "") {
$error="[ <b>Field cannot be left blank ]</b>";
?>
<div align="center">
<div class="blue-box"><br><br><br><div align="center"><form action="search.php" method="get"><input type="hidden" name="a" value="1"><input type="text" name="q" size="45"><input type="submit" value="Search"></form><?=$error;?><br>&nbsp;</div></div>
<?
echo "</div>";
} else { }
if ($_GET['a'] == 1 & $search != "") {
echo "<ol type=\"1\" start=\"" . $_GET['rowstart'] . "\">";
$souch=$_GET['n'];
$rowstart = $_GET['rowstart'];
if ($rowstart == "") {
$rownow= "0";
} else { }
// start search and connect to db
include "config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "SELECT id, tid, categories, tname, poster, fpost, time, date FROM forum WHERE tname LIKE '%$search%' || poster LIKE '%$search%' || fpost LIKE '%$search%' || time LIKE '%$search%' || date LIKE '%$search%' LIMIT $rownow$rowstart,10"; 
$result = mysql_query($query); 
/* Here we fetch the result as an array */ 
while ($r = mysql_fetch_assoc($result)) 
{ 

$id=$r["id"]; 
$tid=$r["tid"]; 
$category=$r["categories"]; 
$tname=$r["tname"]; 
$poster=$r["poster"]; 
$fpost=$r["fpost"]; 
$time=$r["time"]; 
$date=$r["date"]; 
?>
<li><font color="black"><b><a href="ndis.php?c=<?=$category;?>&tid=<?=$tid;?>&t=<?=$tname;?>"><?=$tname;?></a></b></font><br>
<?=$fpost;?><br>
<font color="green">Category: <a href="list.php?c=<?=$category;?>"><?=$category;?></a> | Posted by: <a href="dpost.php?p=<?=$poster;?>"><?=$poster;?></a> | Posted on <?=$date;?> at <?=$time;?></font><br>&nbsp;
</li>
<?
} 
mysql_close($db);
// end search
echo "</ol>";
$q123 = $_GET['q'];
if ($rowstart == "0" OR $rowstart == "") {
} else {
$row11 = ($rowstart - 10);
	echo "<a href=$php_self?a=1&q=$q123&rowstart=$row11>Previous</a> ";
} 

$row12 = ($rowstart + 10);
	echo "<a href=$php_self?a=1&q=$q123&rowstart=$row12>Next</a> ";
} else { }
include "footer.php";
?>