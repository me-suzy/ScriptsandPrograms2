<?
require("checkpass.php");
?>
<HTML><HEAD><TITLE>List of Students</TITLE>
<LINK REL ="stylesheet" HREF="../style.css" TYPE="text/css">
</HEAD>
<BODY BGCOLOR="#FFFFFF" TEXT="#000000" LINK="#3333FF" ALINK="#3333FF" VLINK="#3333FF">

<DIV ALIGN=RIGHT><A HREF='javascript:;' onClick='window.print();return false'>Print Window</A></DIV>

<TABLE BORDER=0 ALIGN=CENTER WIDTH=250><TR><TD COLSPAN=5>Students who have submitted an entry for this project:</TD></TR><TR ALIGN=CENTER>
<TD><B>First Name</B></TD><TD WIDTH=10><IMG SRC=../images/invis.gif WIDTH=10></TD><TD><B>Last Name</B></TD><TD WIDTH=10><IMG SRC=../images/invis.gif WIDTH=10></TD><TD><B>Approved</B></TD></TR>

<?
require("../access.inc.php");

mysql_connect("$host","$login","$pass") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

mysql_select_db("$db") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());


if(isset($HTTP_SESSION_VARS['secure_ghost'])) {
$newID=$HTTP_SESSION_VARS['secure_ghost'];
}
else {
$newID=$HTTP_SESSION_VARS['secure_id'];
}


$ID = $HTTP_GET_VARS['ID'];


$showing = mysql_query("SELECT ".$conf['tbl']['studentwork'].".stufirstname,".$conf['tbl']['studentwork'].".stulastname,".$conf['tbl']['studentwork'].".active FROM ".$conf['tbl']['studentwork'].", ".$conf['tbl']['projecttable']." WHERE ".$conf['tbl']['studentwork'].".TID = '$newID' && ".$conf['tbl']['projecttable'].".TID = '$newID' && ".$conf['tbl']['projecttable'].".ID = '$ID' && ".$conf['tbl']['studentwork'].".project = ".$conf['tbl']['projecttable'].".project");
WHILE($names = mysql_fetch_array($showing)) {

echo "<TR ALIGN=CENTER><TD>".deslash($names['stufirstname'])."</TD><TD WIDTH=10><IMG SRC=../images/invis.gif WIDTH=10></TD><TD>".deslash($names['stulastname'])."</TD><TD WIDTH=10><IMG SRC=../images/invis.gif WIDTH=10></TD><TD>";
IF ($names['active']=='approve') {
echo "Yes";
                             }
ELSE {
echo "<B>No</B>";
     }
echo "</TD></TR>";

}
?>
</TABLE>
<BR>
<CENTER><A HREF=javascript:window.close()>Close Window</A></CENTER>
</BODY></HTML>