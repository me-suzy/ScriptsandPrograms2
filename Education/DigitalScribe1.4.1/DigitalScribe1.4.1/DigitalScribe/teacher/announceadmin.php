<?
require("checkpass.php");

if (isset($HTTP_GET_VARS['ID'])) {
$ID = $HTTP_GET_VARS['ID'];
}

else {
$ID = $HTTP_POST_VARS['ID'];
}

require("../access.inc.php");

mysql_connect("$host","$login","$pass") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

mysql_select_db("$db") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

if(isset($HTTP_POST_VARS['submit'])) {

$HTTP_POST_VARS['title']=addslash($HTTP_POST_VARS['title']);
$HTTP_POST_VARS['announcemnet']=addslash($HTTP_POST_VARS['announcement']);
$HTTP_POST_VARS['author']=addslash($HTTP_POST_VARS['author']);
$HTTP_POST_VARS['date']=addslash($HTTP_POST_VARS['date']);

mysql_query("INSERT INTO ".$conf['tbl']['announcements']." (title, announcement, author,
date) VALUES
(\"$HTTP_POST_VARS[title]\",\"$HTTP_POST_VARS[announcement]\",\"$HTTP_POST_VARS[author]\",'$HTTP_POST_VARS[date]')");


}//end add announcement

if (isset($HTTP_POST_VARS['update'])) { //update announcement

$HTTP_POST_VARS['newtitle']=addslash($HTTP_POST_VARS['newtitle']);
$HTTP_POST_VARS['newannouncemnet']=addslash($HTTP_POST_VARS['newannouncement']);
$HTTP_POST_VARS['newauthor']=addslash($HTTP_POST_VARS['newauthor']);
$HTTP_POST_VARS['newdate']=addslash($HTTP_POST_VARS['newdate']);

    mysql_query("UPDATE ".$conf['tbl']['announcements']." SET title=\"$HTTP_POST_VARS[newtitle]\",
announcement=\"$HTTP_POST_VARS[newannouncement]\", date=\"$HTTP_POST_VARS[newdate]\" WHERE a_id = \"$HTTP_POST_VARS[a_id]\"");

}//end update announcement

if ($HTTP_GET_VARS['delete']==y) {
   mysql_query("DELETE FROM ".$conf['tbl']['announcements']." WHERE a_id=$HTTP_GET_VARS[a_id]");
} //end delete announcement


?>
<HTML><HEAD><TITLE>Announcement Administration</TITLE>
<LINK REL ="stylesheet" HREF="../style.css" TYPE="text/css">
 
<?
include("../header1.php");

if(isset($HTTP_SESSION_VARS['secure_ghost'])) {
$newID=$HTTP_SESSION_VARS['secure_ghost'];
}
else {
$newID=$HTTP_SESSION_VARS['secure_id'];
}


$n_show = mysql_query("SELECT name FROM ".$conf['tbl']['teachers']." WHERE ID=$newID");
	WHILE($na = mysql_fetch_array($n_show)) {
	$na['name']=deslash($na['name']);
	$name=$na['name'];
}

echo "Hi $name! - <A HREF=../login.php?logout=1>Logout</A>";
?>
<FORM METHOD=POST ACTION=announceadmin.php>
<TABLE ALIGN=CENTER WIDTH=90% BORDER=1 CELLSPACING=0 CELLPADDING=4 BORDERCOLOR=LIGHTBLUE><TR ALIGN=CENTER><td><B>Add an Announcement</b></td></tr>
<tr><td>Title: <input type=text name=title></td></tr>
<tr><td>Announcement:<br>
<textarea name=announcement rows=6 cols=50></textarea>
</td></tr><tr><td>
<?  $date=gmdate('F j, Y'); ?>
<input type=hidden value="<? echo($date); ?>" name=date>
<input type=hidden value="<? echo($name); ?>" name=author>
<input type=submit value="Add Announcement" name=submit>
</td></tr></table>

<P><A HREF=announce_edituser.php?ID=<?echo($newID);?>>
Change your e-mail address, password, or name</A>.

<?
$show = mysql_query("SELECT * FROM ".$conf['tbl']['announcements']." ORDER BY a_id DESC");
	WHILE($anc = mysql_fetch_array($show)) {


$anc['announcement'] = deslash($anc['announcement']);
$anc['title'] = deslash($anc['title']);
$anc['date'] = deslash($anc['date']);

echo "<FORM METHOD=POST ACTION=announceadmin.php>";
echo "<P><TABLE ALIGN=CENTER WIDTH=90% BORDER=1 CELLSPACING=0 CELLPADDING=4 BORDERCOLOR=LIGHTBLUE><TR ALIGN=CENTER>";
echo "<td colspan=2><B>An Announcement</B></td></tr><tr align=center>";
echo "<TD>Title</TD>";
echo "<TD>Date</TD>";
echo "</TR><TR ALIGN=CENTER>";
echo "<td><INPUT TYPE=text name=newtitle value=\"$anc[title]\" width=70></td>";
echo "<td><INPUT TYPE=text name=newdate value=\"$anc[date]\" width=70></td>";
echo "</tr><tr><td colspan=2>";
echo "Announcement:<BR><textarea name=newannouncement cols=50 rows=6>$anc[announcement]</textarea>";
echo "</td></tr><tr><td>";
echo "<input type=hidden name=a_id value=$anc[a_id]>";
echo "<input type=submit name=update value=\"Save Changes\">";
echo "</td><td align=right>";
echo "<A href=\"announceadmin.php?a_id=$anc[a_id]&delete=y\">Delete this Announcement</A></td>";
echo "</FORM></TR></TABLE>";

}//end show announcements


include("../footer.php");
?>