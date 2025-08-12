<HTML><HEAD>
<TITLE>Student Work</TITLE>
<LINK REL ="stylesheet" HREF="style.css" TYPE="text/css">
<?
include("header2.php"); 
require("access.inc.php");

mysql_connect("$host","$login","$pass") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

mysql_select_db("$db") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

$show = mysql_query("SELECT * FROM ".$conf['tbl']['studentwork']." WHERE(id='$HTTP_GET_VARS[id]')");
WHILE($studentwork = mysql_fetch_array($show)) {

$studentwork['project'] = deslash($studentwork['project']);
$studentwork['title'] = deslash($studentwork['title']);
$studentwork['stuwork'] = deslash($studentwork['stuwork']);
$studentwork['stuwork'] = nl2br($studentwork['stuwork']);
$studentwork['stufirstname'] = deslash($studentwork['stufirstname']);

$project = "$studentwork[project]";

$arch = mysql_query("SELECT project,archive FROM ".$conf['tbl']['projecttable']." WHERE(project=\"$project\")");
WHILE($archv = mysql_fetch_array($arch)) {

if($archv['archive'] == 'no') {
$url = "liststuwork";
}

else {
$url = "listarchive";
}

}//end project archive check

echo "<BR><A HREF=$url.php>Student Work</A> > ";
echo "<A HREF='stuworkdisplay.php?teacher=$HTTP_GET_VARS[teacher]&ID=$HTTP_GET_VARS[ID]'>$studentwork[project]</A> > $studentwork[title]";

	IF (isset($studentwork['filename']) AND $studentwork['filename'] != "NULL" AND $studentwork['filename'] != "Array") {
	$image = "images/$studentwork[filename]";
	@$size = GetImageSize("$image");


		IF ($size[0] > 400) {
		$width = 400;
		$message = "Enlarge this Image";
         }
		ELSE {
		$width = $size[0];
		$message = "";
 	     }

	} //end if image

IF (!$studentwork['filename'] OR $studentwork['filename'] == "NULL" OR
$studentwork['filename'] == "Array") {
echo "<P>";
}

ELSEIF (!$studentwork['stuwork'] AND !$studentwork['filename']) {
echo "<P>";
                                                                }
ELSEIF (!$studentwork['stuwork']) {
echo "<P ALIGN=CENTER><A HREF='showpic.php?id=$HTTP_GET_VARS[id]&ID=$HTTP_GET_VARS[ID]&teacher=$HTTP_GET_VARS[teacher]&image=$image&new=$HTTP_GET_VARS[new]'>$message<BR><IMG SRC=images/$studentwork[filename] WIDTH=$width BORDER=0 ALIGN=CENTER></A></P>";
                            }

ELSE {

echo "<P ALIGN=RIGHT><A HREF='showpic.php?id=$HTTP_GET_VARS[id]&ID=$HTTP_GET_VARS[ID]&teacher=$HTTP_GET_VARS[teacher]&image=$image&new=$HTTP_GET_VARS[new]'>$message<IMG SRC=images/invis.gif WIDTH=250 HEIGHT=1 BORDER=0><BR><IMG SRC=images/$studentwork[filename] WIDTH=$width BORDER=0 ALIGN=RIGHT></A></P>";
        }

echo "$studentwork[stuwork]";

$studentwork['stulastname'] = ereg_replace("[a-z]","",$studentwork['stulastname']);

echo "<P>By $studentwork[stufirstname] ";
echo "$studentwork[stulastname].";
echo "<P><A HREF=$url.php>Student Work</A> > ";
echo "<A HREF='stuworkdisplay.php?teacher=$HTTP_GET_VARS[teacher]&ID=$HTTP_GET_VARS[ID]'>$studentwork[project]</A> > $studentwork[title]";
}




$showing1 = mysql_query("SELECT ".$conf['tbl']['studentwork'].".id, ".$conf['tbl']['projecttable'].".ID, ".$conf['tbl']['projecttable'].".teacheruser, ".$conf['tbl']['studentwork'].".title,
".$conf['tbl']['studentwork'].".stufirstname FROM ".$conf['tbl']['studentwork'].",".$conf['tbl']['projecttable']." 
WHERE(".$conf['tbl']['studentwork'].".TID = '$HTTP_GET_VARS[teacher]' && ".$conf['tbl']['projecttable'].".TID = '$HTTP_GET_VARS[teacher]' && ".$conf['tbl']['projecttable'].".publish = 'publish' && ".$conf['tbl']['studentwork'].".active = 'approve' && ".$conf['tbl']['projecttable'].".ID = '$HTTP_GET_VARS[ID]' && ".$conf['tbl']['studentwork'].".project = ".$conf['tbl']['projecttable'].".project) ORDER BY title, stufirstname");
{

$nextprev = mysql_fetch_array($showing1);
for($inc=0;$inc<$HTTP_GET_VARS['new'];$inc++)
{
$nextprev = mysql_fetch_array($showing1);
$nextprev['title'] = deslash($nextprev['title']);
}

$new =$inc + 1;

IF (isset($nextprev['id'])) {
echo "<P ALIGN=CENTER><B>Move to the <A HREF='stuworkindiv.php?id=$nextprev[id]&ID=$HTTP_GET_VARS[ID]&teacher=$HTTP_GET_VARS[teacher]&new=$new'>Next Entry</A>.</B>";
}
ELSE { echo "<P ALIGN=CENTER><B>You've reached the end. Go back to the list of
<A HREF='stuworkdisplay.php?teacher=$HTTP_GET_VARS[teacher]&ID=$HTTP_GET_VARS[ID]'>Student Work.</A></B>";}
}
include("footer.php");
?>