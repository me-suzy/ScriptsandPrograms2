<?
// This forum was developed by Adam M. B. from aWeb Labs
// Visit us at http://www.labs.aweb.com.au
// for forum problems, bugs, or ideas email yougotmail@gmail.com
// thanks for trying out or using this forum
// aWebBB version 1.2 released under the GNU GPL
include "header.php";
?>
<div class="side-headline"><b>Statistics:</b></div>
<div align="center"><br>
<div class="blue-box">
<div class="breaker"><b>General</b></div><br>
<table cellpadding="0" cellspacing="0" border="0" width="594"><tr><td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>There are a total of 
<?
include "config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query2="SELECT * FROM forum";
$result2 = mysql_query($query2);
 $num_rows2 = mysql_num_rows($result2);
$presum=$num_rows2;
echo "$presum"; 
mysql_close($db);
?>
 posts in the forum.</b><br>&nbsp;
</td></tr>
<tr><td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Number of threads by category:</b> 
<?
include "config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query1 = "SELECT category, description FROM fcat ORDER BY category DESC"; 
$result1 = mysql_query($query1); 
while($r=mysql_fetch_array($result1)) 
{
$category = $r["category"];

$query2="SELECT * FROM flist WHERE categories = '$category'";
$result2 = mysql_query($query2);
 $num_rows2 = mysql_num_rows($result2);
echo "<br><i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$category: $num_rows2</i>";

}
mysql_close($db);
?><br>&nbsp;
</td></tr>
</table>
</div>

<div class="blue-box">
<div class="breaker"><b>Users</b></div><br>
<table cellpadding="0" cellspacing="0" border="0" width="594"><tr><td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>The total number of users on this website is: 
<?
include "config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query2="SELECT * FROM users";
$result2 = mysql_query($query2);
 $num_rows2 = mysql_num_rows($result2);
$presum=$num_rows2;
echo "$presum"; 
mysql_close($db);
?>
</b><br>&nbsp;
</td></tr>
<tr><td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Newest Registered Member:</b> <br>
<?
include "config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query4 = "SELECT username FROM users ORDER BY id DESC LIMIT 0,1"; 
$result4 = mysql_query($query4); 
while($r=mysql_fetch_array($result4)) 
{
$username=$r["username"];

?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="dpost.php?p=<?=$username;?>"><i><?=$username;?></i></a>
<?
}
mysql_close($db);
?><br>&nbsp;
</td></tr><tr><td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>The most recent post was made by:</b> <br>
<?
include "config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query1 = "SELECT poster, time, date FROM forum ORDER BY date DESC LIMIT 0,1"; 
$result1 = mysql_query($query1); 
while($r=mysql_fetch_array($result1)) 
{
$poster=$r["poster"];
$time=$r["time"];
$date=$r["date"];

?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="dpost.php?p=<?=$poster;?>"><i><?=$poster;?> on <?=$date;?> at <?=$time;?></i></a>
<?
}
mysql_close($db);
?><br>&nbsp;
</td></tr>
</table>
</div>
</div>
<?
include "footer.php";
?>