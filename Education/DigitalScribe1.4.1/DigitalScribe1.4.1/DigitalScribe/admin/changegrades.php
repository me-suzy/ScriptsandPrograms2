<?
require("checkpass2.php");
require("../teacher/checkpass.php");
?>
<HTML><HEAD><TITLE>Administration</TITLE>
<LINK REL ="stylesheet" HREF="../style.css" TYPE="text/css">

<?
include("../header1.php");
echo '<span class=title>Change Grade Levels in School</span><P>';
echo "<P><A HREF=admin.php>Go Back to Admin Home<A>";

require("../access.inc.php");

mysql_connect("$host","$login","$pass") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

mysql_select_db("$db") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

  IF($HTTP_GET_VARS[mode]==delete) {
    mysql_query("DELETE FROM ".$conf['tbl']['grades']." WHERE SID=$HTTP_GET_VARS[SID]");
  }

  IF(isset($HTTP_POST_VARS['submit'])) {
mysql_query("INSERT INTO ".$conf['tbl']['grades']." (grades) VALUES ('".addslash($HTTP_POST_VARS['grade'])."')");
  }


  IF($HTTP_GET_VARS[mode]==add) {

?>
<FORM ACTION=changegrades.php METHOD=POST>
<P>Enter Grade:<BR><INPUT TYPE=TEXT NAME=grade COLS=25>
  <P><INPUT TYPE=submit NAME=submit VALUE="Add Grade Level"></FORM>
  <?
  }


echo "<P><TABLE BORDER=1><TR><TD COLSPAN=2 ALIGN=CENTER><A HREF=changegrades.php?mode=add>Add a grade level</A>.</TD></TR><TR><TD>Current Grade Levels</TD><TD>Delete Grade Levels</TD></TR>";

$grade = mysql_query("SELECT grades,SID from ".$conf['tbl']['grades']."");
WHILE ($grd = mysql_fetch_array($grade)) {

echo "<TR><TD>".deslash($grd['grades'])."</TD><TD ALIGN=CENTER><A HREF=changegrades.php?mode=delete&SID=$grd[SID]>Delete</A></TD></TR>";
}
echo "</TABLE>";

include("../footer.php");
?>