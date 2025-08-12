<HTML><HEAD><TITLE>View Homework</TITLE>
<LINK REL ="stylesheet" HREF="style.css" TYPE="text/css">
<? 
include("header1.php"); 
echo '<span class=title>Homework</span><P>';
?>
First, select your teacher:
<?
require("access.inc.php");

mysql_connect("$host","$login","$pass") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

mysql_select_db("$db") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());


$showteacher = mysql_query("SELECT name FROM ".$conf['tbl']['teachers']." WHERE level=3 GROUP BY name");

$rows = mysql_num_rows($showteacher);
$half = ceil($rows/2);
$num = $half;


echo "<TABLE><TR><TD VALIGN=TOP><UL>";
$showteachers = mysql_query("SELECT name, ID FROM ".$conf['tbl']['teachers']." WHERE level=3 GROUP BY name ORDER BY user LIMIT 0,$num");
WHILE($show = mysql_fetch_array($showteachers)) {

$show['name'] = deslash($show['name']);

IF ($show['ID'] == ''){}
ELSE{
echo "<LI><A HREF='viewhw2.php?t_id=$show[ID]'>$show[name]</A>";
}   }

echo "</UL></TD><TD VALIGN=TOP><UL>";


$showteachers2 = mysql_query("SELECT name, ID FROM ".$conf['tbl']['teachers']." WHERE level=3 GROUP BY name ORDER BY user LIMIT $half,$rows");
WHILE($show2 = mysql_fetch_array($showteachers2)) {

$show2['name'] = deslash($show2['name']);

IF ($show2['ID'] == ''){}
ELSE{
echo "<LI><A HREF='viewhw2.php?t_id=$show2[ID]'>$show2[name]</A>";
}   }


echo "</UL></TD></TR></TABLE>";
include("footer.php");
?>