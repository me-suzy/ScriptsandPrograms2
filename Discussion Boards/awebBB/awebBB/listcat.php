<?
session_start();
// This forum was developed by Adam M. B. from aWeb Labs
// Visit us at http://www.labs.aweb.com.au
// for forum problems, bugs, or ideas email yougotmail@gmail.com
// thanks for trying out or using this forum
// aWebBB version 1.2 released under the GNU GPL

include "header.php";
?>
<div align="center">
<div class="side-headline"><b>Forum Sections:</b></div><br>

<?
include "config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query1 = "SELECT category, description FROM fcat ORDER BY category DESC"; 
$result1 = mysql_query($query1); 
while($r=mysql_fetch_array($result1)) 
{
$category = $r["category"];
$description = $r["description"];
?>
<div class="blue-box">
<table cellpadding="0" cellspacing="0" border="0" width="594"><tr><td width="4"></td><td width="350" valign="top">
<b><a href="list.php?c=<?=$category;?>"><?=$category;?>:</a></b><br><?=$description;?></td><td width="70" valign="top"><b>Topics:</b><br>
<?
$query3="SELECT * FROM flist WHERE categories = '$category'";
$result3 = mysql_query($query3);
 $num_rows3 = mysql_num_rows($result3);
echo "$num_rows3"; 
?>
</td><td width="70" valign="top"><b>Posts:</b><br>
<?
$query2="SELECT * FROM forum WHERE categories = '$category'";
$result2 = mysql_query($query2);
 $num_rows2 = mysql_num_rows($result2);
echo "$num_rows2"; 
?>
</td><td width="100" valign="top"><b>Last Post:</b><br>
<?
$query5 = "SELECT time, date FROM forum WHERE categories = '$category' ORDER BY date DESC LIMIT 0,1"; 
$result5 = mysql_query($query5); 
/* Here we fetch the result as an array */ 
while($r1=mysql_fetch_array($result5)) 
{
$time=$r1["time"]; 
$date=$r1["date"]; 
?>
<?=$date;?> @ <?=$time;?>
<?
}
?>
</td></tr></table>
</div>
<?
}
mysql_close($db); 
echo "</div>";
include "footer.php";
?>