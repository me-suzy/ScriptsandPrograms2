<HTML><HEAD><TITLE>View Homework</TITLE>
<LINK REL ="stylesheet" HREF="style.css" TYPE="text/css">
<? 
include("header1.php"); 
echo '<span class=title>Homework</span><P>';
?>


<?
require("access.inc.php");

mysql_connect("$host","$login","$pass") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

mysql_select_db("$db") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());


$cookie = mysql_query("SELECT proj_title,teach_id FROM ".$conf['tbl']['projecthomework']." WHERE proj_ID='$HTTP_GET_VARS[proj_ID]'");
WHILE($cc = mysql_fetch_array($cookie)) {
$cc_title=$cc[proj_title];
$cc_title = deslash($cc_title);
$cc_id=$cc[teach_id];

}


echo "<A HREF=viewhw1.php>Select your Teacher</A> > <A HREF=viewhw2.php?t_id=$cc_id>Select your Class</A> > $cc_title<P>";


$work = mysql_query("SELECT month_due,day_due,title,work FROM ".$conf['tbl']['homework']." WHERE proj_id='$HTTP_GET_VARS[proj_ID]' ORDER BY month_due,day_due");
WHILE($hw = mysql_fetch_array($work)) {

IF ($hw[month_due]=='a') { $hw[month_due]='January'; $realmonth='January'; }
ELSEIF ($hw[month_due]=='h') { $hw[month_due]='August'; $realmonth='August'; }
ELSEIF ($hw[month_due]=='i') { $hw[month_due]='September'; $realmonth='September'; }
ELSEIF ($hw[month_due]=='j') { $hw[month_due]='October'; $realmonth='October'; }
ELSEIF ($hw[month_due]=='k') { $hw[month_due]='November'; $realmonth='November'; }
ELSEIF ($hw[month_due]=='l') { $hw[month_due]='December'; $realmonth='December'; }
ELSEIF ($hw[month_due]=='b') { $hw[month_due]='February'; $realmonth='February'; }
ELSEIF ($hw[month_due]=='c') { $hw[month_due]='March'; $realmonth='March'; }
ELSEIF ($hw[month_due]=='d') { $hw[month_due]='April'; $realmonth='April'; }
ELSEIF ($hw[month_due]=='e') { $hw[month_due]='May'; $realmonth='May'; }
ELSEIF ($hw[month_due]=='f') { $hw[month_due]='June'; $realmonth='June'; }
ELSE { $hw[month_due]='July'; $realmonth='July'; }

$hw['work'] = nl2br($hw['work']);
$hw['work'] = deslash($hw['work']);
$hw['title'] = deslash($hw['title']);

echo "<P><TABLE width=95% border=1><tr><td width=150>";
echo "<B>Due</B>: $hw[month_due] $hw[day_due]</td><td><B>Title</B>: $hw[title]</td></tr>";
echo "<tr><td colspan=2><B>Homework:</B><BR>$hw[work]";
echo "</td></tr></table>";
$nohw=1;
}
if (!isset($nohw)) { echo "<P>This class does not have homework."; }
include("footer.php");
?>