<HTML><HEAD><TITLE>View Announcements</TITLE>
<LINK REL ="stylesheet" HREF="style.css" TYPE="text/css">
<? 
include("header1.php"); 
echo '<span class=title>Announcements</span><P>';
require("access.inc.php");

mysql_connect("$host","$login","$pass") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

mysql_select_db("$db") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

$work = mysql_query("SELECT announcement,author,title,date FROM ".$conf['tbl']['announcements']." ORDER BY a_id DESC");
WHILE($a = mysql_fetch_array($work)) {


$a['announcement'] = nl2br($a['announcement']);
$a['announcement'] = deslash($a['announcement']);
$a['author'] = deslash($a['author']);
$a['title'] = deslash($a['title']);


echo "<P><TABLE width=95% border=1><tr><td>";
echo "<B>$a[title]</B></td><td align=right>Submitted $a[date]</td></tr>";
echo "<tr><td colspan=2>$a[announcement]<br>Submitted by $a[author]";
echo "</td></tr></table>";
$no=1;
}

if (!isset($no)) { echo "<P><B>No announcements have been posted.</B>"; }

include("footer.php");
?>