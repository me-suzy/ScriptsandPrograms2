<HTML><HEAD><TITLE>Student Work</TITLE>
<LINK REL ="stylesheet" HREF="style.css" TYPE="text/css">
<?
include("header1.php");

require("access.inc.php");

mysql_connect("$host","$login","$pass") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

mysql_select_db("$db") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

$show = mysql_query("SELECT * FROM ".$conf['tbl']['projecttable']." WHERE(ID=$HTTP_GET_VARS[ID])");
WHILE($projecttable = mysql_fetch_array($show)) {

$projecttable['project'] = deslash($projecttable['project']);
$projecttable['teachername'] = deslash($projecttable['teachername']);

$teachername=$projecttable['teachername'];

echo "<CENTER><span class=title>$projecttable[project]</span><BR><span class=body>By the class of $projecttable[teachername].</span></CENTER>";
                    }


$showing = mysql_query("SELECT * FROM ".$conf['tbl']['studentwork'].",".$conf['tbl']['projecttable']." 
WHERE(".$conf['tbl']['studentwork'].".TID = '$HTTP_GET_VARS[teacher]' && 
".$conf['tbl']['projecttable'].".TID = '$HTTP_GET_VARS[teacher]' && 
".$conf['tbl']['projecttable'].".publish = 'publish' && 
".$conf['tbl']['studentwork'].".active = 'approve' && 
".$conf['tbl']['projecttable'].".ID = '$HTTP_GET_VARS[ID]')");
WHILE($studentwork = mysql_fetch_array($showing)) {

$studentwork['description'] = nl2br($studentwork['description']);
$studentwork['description'] = deslash($studentwork['description']);

IF (!$studentwork['imagename'] OR $studentwork['imagename'] == "NULL" OR $studentwork['imagename'] == "Array") {}
ELSE {

$image = "images/$studentwork[imagename]";

$size = GetImageSize("$image");

IF ($size[0] > 400) {
$width = 400;
$message = "Enlarge this Image";
                    }
ELSE {
$width = $size[0];
$message = "";
     }


echo "<P><A HREF='showpic.php?ID=$HTTP_GET_VARS[ID]&teacher=$HTTP_GET_VARS[teacher]&image=$image'><DIV ALIGN=RIGHT>$message<BR><IMG SRC=$image WIDTH=$width ALT='Image' ALIGN=RIGHT BORDER=0></DIV></A>";
     }
echo "<P>$studentwork[description]";
echo "<P>";
break;                                                 }

echo "<UL>";
$new = 0;   
$showing = mysql_query("SELECT * FROM ".$conf['tbl']['studentwork'].", ".$conf['tbl']['projecttable']." 
WHERE(".$conf['tbl']['studentwork'].".TID = '$HTTP_GET_VARS[teacher]' && ".$conf['tbl']['projecttable'].".TID = '$HTTP_GET_VARS[teacher]' && ".$conf['tbl']['projecttable'].".publish = 'publish' && ".$conf['tbl']['studentwork'].".active = 'approve' && ".$conf['tbl']['projecttable'].".ID = '$HTTP_GET_VARS[ID]' && ".$conf['tbl']['studentwork'].".project = ".$conf['tbl']['projecttable'].".project) ORDER BY title, stufirstname");
WHILE($studentwork = mysql_fetch_array($showing)) {

$studentwork['stulastname'] = ereg_replace("[a-z]","",$studentwork['stulastname']);

$studentwork['title'] = deslash($studentwork['title']);

$new = $new + 1;
echo "<LI><A HREF='stuworkindiv.php?id=$studentwork[id]&ID=$HTTP_GET_VARS[ID]&teacher=$HTTP_GET_VARS[teacher]&new=$new'>$studentwork[title]</A> by $studentwork[stufirstname] $studentwork[stulastname].";
$no=1;
}
if (!isset($no)) { echo "<P><B>No student has been approved for this project, contact $teachername for more information.</B>"; }
echo "</UL>";

include("footer.php");
?>