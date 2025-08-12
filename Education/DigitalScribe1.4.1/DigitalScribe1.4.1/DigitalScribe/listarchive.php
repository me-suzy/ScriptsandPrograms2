<HTML><HEAD><TITLE>Archived Student Work</TITLE>
<LINK REL ="stylesheet" HREF="style.css" TYPE="text/css">
<? 
include("header1.php"); 
echo '<span class=title>Archived Student Work</span><P>';
?>

<P>Below is a listing of archived student work that is available for viewing online.  You can view current student work <A HREF=liststuwork.php>here</A>.

<?
$order = "grade, date, TID, project";

require("access.inc.php");

mysql_connect("$host","$login","$pass") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

mysql_select_db("$db") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

$showing = mysql_query("SELECT distinct(grade) FROM ".$conf['tbl']['projecttable']." WHERE publish='publish' && archive='yes' ORDER By grade");
WHILE($wor = mysql_fetch_array($showing)) {

		echo "<P><TABLE BORDER=0 ALIGN=CENTER WIDTH=95%><TR><TD COLSPAN=3 ALIGN=CENTER><B><span class=title>".deslash($wor['grade'])."</span></B></TD></TR>";
		echo "<TR ALIGN=CENTER><TD><B>Date Published</B></TD><TD><B>Title</B></TD><TD><B>Teacher</B></TD></TR>";
		
		$show = mysql_query("SELECT teachername,project,TID,ID,date FROM ".$conf['tbl']['projecttable']." WHERE publish='publish' && archive='yes' && grade=\"$wor[grade]\" ORDER BY $order");
		WHILE($work = mysql_fetch_array($show)) {
			$work['teachername'] = deslash($work['teachername']);
			$work['project'] = deslash($work['project']);
			$work['date'] = deslash($work['date']);
			echo "<TR ALIGN=CENTER><TD>$work[date]</TD><TD><A HREF='stuworkdisplay.php?ID=$work[ID]&teacher=$work[TID]'>$work[project]</A></TD><TD>$work[teachername]</TD></TR>";
		} //end select
	echo "</TABLE>";

$no=1;
} // end project select

if (!isset($no)) { echo "<P><B>No student work has been archived.</B>"; }
include("footer.php");
?>