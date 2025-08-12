<?
require("checkpass.php");
$ID = $HTTP_GET_VARS['ID'];

require("../access.inc.php");

mysql_connect("$host","$login","$pass") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

mysql_select_db("$db") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

if ($HTTP_GET_VARS['publish']==nopublish || $HTTP_GET_VARS['publish']==publish) {
   $date=gmdate('F j, Y');
   mysql_query("UPDATE ".$conf['tbl']['projecttable']." SET publish='$HTTP_GET_VARS[publish]', date ='$date' WHERE ID='$ID'");
}

if ($HTTP_GET_VARS['archive']==yes || $HTTP_GET_VARS['archive']==no) { //archive project
   mysql_query("UPDATE ".$conf['tbl']['projecttable']." SET archive='$HTTP_GET_VARS[archive]' WHERE ID='$ID'");
}

if ($HTTP_GET_VARS['approve']==approve || $HTTP_GET_VARS['approve']==noapprove) { //approve student work
   mysql_query("UPDATE ".$conf['tbl']['studentwork']." SET active='$HTTP_GET_VARS[approve]' WHERE id='$HTTP_GET_VARS[id]'");
   $integer = $HTTP_GET_VARS['integer'];
   header("Location:skip.php?ID=$ID&stunum=$integer");
}

if ($HTTP_GET_VARS['mode']==delete) { //delete student entry to project
   @unlink("../images/$HTTP_GET_VARS[image]");
   mysql_query("DELETE FROM ".$conf['tbl']['studentwork']." WHERE id=$HTTP_GET_VARS[id]");
}

if ($HTTP_POST_VARS['mode']=='editstudent') { //update student entry
    $newstufirstname = $HTTP_POST_VARS['newstufirstname'];
    $newstulastname = $HTTP_POST_VARS['newstulastname'];
    $newtitle = $HTTP_POST_VARS['newtitle'];
    $newstuwork = $HTTP_POST_VARS['newstuwork'];
    $id = $HTTP_POST_VARS['id'];
    $integer = $HTTP_POST_VARS['integer'];

    mysql_query("UPDATE ".$conf['tbl']['studentwork']." SET stufirstname='".addslash($newstufirstname)."',
stulastname='".addslash($newstulastname)."', title='".addslash($newtitle)."', stuwork='".addslash($newstuwork)."' WHERE id = '$id'");
    header("Location:skip.php?ID=$HTTP_POST_VARS[ID]&stunum=$integer");
}

if ($HTTP_GET_VARS['mode']==deleteproject) { //delete project & student work associated with project
    $user = $HTTP_GET_VARS['user'];
    $image = $HTTP_GET_VARS['image'];

    @unlink("../images/$image");

    //get student work that is in project
    $showing = mysql_query("SELECT ".$conf['tbl']['studentwork'].".filename,".$conf['tbl']['studentwork'].".id FROM ".$conf['tbl']['studentwork']." ,".$conf['tbl']['projecttable']." WHERE ".$conf['tbl']['projecttable'].".TID = '$user' && ".$conf['tbl']['studentwork'].".TID = '$user' && ".$conf['tbl']['projecttable'].".ID = '$ID' && ".$conf['tbl']['studentwork'].".project = ".$conf['tbl']['projecttable'].".project");
    WHILE($stu = mysql_fetch_array($showing)) {
      $id=$stu['id'];
      $s_image=$stu['filename'];

      @unlink("../images/$s_image");

      mysql_query("DELETE FROM ".$conf['tbl']['studentwork']." WHERE id = $id");  //delete student work
    }

    mysql_query("DELETE FROM ".$conf['tbl']['projecttable']." WHERE ID = '$ID'"); //delete project

    header("Location:teacheradmin.php");
}

if ($HTTP_POST_VARS['mode']==editproject) { //edit project information
  $oldproject = $HTTP_POST_VARS['oldproject'];
  $newproject = $HTTP_POST_VARS['newproject'];
  $newdescription = $HTTP_POST_VARS['newdescription'];
  $ID = $HTTP_POST_VARS['ID'];
  $newgrade = "$HTTP_POST_VARS[grade]";

  mysql_query("UPDATE ".$conf['tbl']['projecttable']." SET ".$conf['tbl']['projecttable'].".project='".addslash($newproject)."', ".$conf['tbl']['projecttable'].".description='".addslash($newdescription)."', ".$conf['tbl']['projecttable'].".grade='".addslash($newgrade)."' WHERE ID='$ID'");

  mysql_query("UPDATE ".$conf['tbl']['studentwork']." SET project='".addslash($newproject)."' WHERE project='".addslash($oldproject)."'");

}

?>
<HTML><HEAD><TITLE>Teacher Administration</TITLE>
<LINK REL ="stylesheet" HREF="../style.css" TYPE="text/css">
 
 
<SCRIPT>
function openpopup() {
var popurl="print.php?ID=<? echo $ID ?>"
winpops=window.open
(popurl,"","width=300,height=350,toolbar,scrollbars,menubar,")
                     }
</SCRIPT>
<?
include("../header2.php");

if(isset($HTTP_SESSION_VARS['secure_ghost'])) {
$newID=$HTTP_SESSION_VARS['secure_ghost'];
}
else {
$newID=$HTTP_SESSION_VARS['secure_id'];
}
	$showproject23 = mysql_query("SELECT grade,user,name,filename FROM ".$conf['tbl']['teachers']." WHERE ID=$newID");
	WHILE($pro = mysql_fetch_array($showproject23)) {
	$user = "$pro[user]";
	$name = "$pro[name]";
	$filename = "$pro[filename]";
	$teacher = deslash($name);
	}

$id5 = "studentwork.id";

$sql = "SELECT COUNT(*) num, ".$conf['tbl']['studentwork'].".id FROM ".$conf['tbl']['studentwork']." , ".$conf['tbl']['projecttable']." WHERE ".$conf['tbl']['studentwork'].".TID = '$newID' && ".$conf['tbl']['projecttable'].".TID = '$newID' && ".$conf['tbl']['projecttable'].".ID = '$ID' && ".$conf['tbl']['studentwork'].".project = ".$conf['tbl']['projecttable'].".project GROUP BY ".$conf['tbl']['studentwork'].".id ORDER BY num DESC";
$query = mysql_query($sql);
$i=0;
WHILE($row = mysql_fetch_array($query)) {
    $i++;
    }


$showproject = mysql_query("SELECT * FROM ".$conf['tbl']['projecttable']." WHERE TID =
'$newID' AND ID = '$ID'");
WHILE($projecttable = mysql_fetch_array($showproject)) {

$projecttable['project'] = deslash($projecttable['project']);
$projecttable['description'] = deslash($projecttable['description']);

$grade = (deslash($projecttable['grade']));

IF (!$projecttable['imagename']) {
}

ELSE {
$image = "../images/$projecttable[imagename]";
@$size = GetImageSize("$image");


IF ($size[0] > 350) {
$width = 350;
$message = "Enlarge this Image";

                    }
ELSE {
$width = $size[0];
$message = "";
     }}

echo "<A NAME=top>";
echo "<span class=title><CENTER>Project: <B>$projecttable[project]</B></CENTER></span>";
echo "<BR>Go Back to your <A HREF=teacheradmin.php>list of projects</A>.";
echo " - <A HREF=../login.php?logout=1>Logout</A>";
echo "<BR><B>$i</B> Students have submitted their work.";
echo " - <A HREF='javascript:openpopup()'>Print out their names</A>.";
echo "<BR>Nothing Below?  Get started and <A HREF='../addstuwork2.php?teacher=$newID&bad=1'>add some student work</A>!";


$integer=0;
echo "<FORM METHOD=GET ACTION=skip.php>";
echo "<P>Jump to student: <SELECT NAME=stunum>";
$stulist = mysql_query("SELECT stufirstname,stulastname FROM ".$conf['tbl']['studentwork']." , ".$conf['tbl']['projecttable']." WHERE ".$conf['tbl']['studentwork'].".TID = '$newID' && ".$conf['tbl']['projecttable'].".TID = '$newID' && ".$conf['tbl']['projecttable'].".ID = '$ID' && ".$conf['tbl']['studentwork'].".project = ".$conf['tbl']['projecttable'].".project ORDER BY stufirstname, stulastname");
WHILE($list = mysql_fetch_array($stulist)) {

$list['stufirstname'] = deslash($list['stufirstname']);
$list['stulastname'] = deslash($list['stulastname']);


$integer=$integer+1;
echo "<OPTION VALUE=$integer>$list[stufirstname] $list[stulastname]</OPTION>";

}
echo "</SELECT><INPUT TYPE=HIDDEN NAME=ID VALUE=$ID> <INPUT TYPE=SUBMIT VALUE=GO></FORM>";


echo "<FORM METHOD=POST ACTION=indepthadmin.php>";
echo "<TABLE ALIGN=CENTER WIDTH=90% BORDER=1 CELLSPACING=0 CELLPADDING=4 BORDERCOLOR=LIGHTBLUE><TR ALIGN=CENTER>";
echo "<TD>Project Name</TD>";
echo "<TD>Online</TD>";
echo "<TD>Put Online or<BR>Take Offline</TD>";
echo "</TR><TR ALIGN=CENTER>";

echo "<TD><INPUT TYPE NAME=newproject VALUE=\"$projecttable[project]\" SIZE=30></TD>";
echo "<TD>";
IF ($projecttable['publish']=='publish')
{
echo "<B>Yes</B>";
}
ELSE
{
echo "<B>No</B>";
}
echo "</TD><TD>";
IF ($projecttable['publish']=='publish')
{
echo "<A HREF='indepthadmin.php?ID=$projecttable[ID]&publish=nopublish'>Take Offline</A>";
}
ELSE
{
echo "<A HREF='indepthadmin.php?ID=$projecttable[ID]&publish=publish'>Put Online</A>";
}
echo "</TD></TR><TR><TD COLSPAN=2>";
echo "<P>Project Description:<BR>";
echo "<TEXTAREA NAME=newdescription ROWS=6 COLS=50>$projecttable[description]</TEXTAREA>";
echo "<INPUT TYPE=HIDDEN NAME=oldproject VALUE=\"$projecttable[project]\">";
echo "<INPUT TYPE=HIDDEN NAME=ID VALUE=$projecttable[ID]>";
echo "<INPUT TYPE=HIDDEN NAME=mode VALUE=editproject>";
echo "<BR><INPUT TYPE=SUBMIT NAME=Submit VALUE='Update Project'>";
echo "<FONT COLOR=FFFFFF>............</FONT><A
HREF=indepthadmin.php?ID=$projecttable[ID]&user=$newID&image=$projecttable[imagename]&mode=deleteproject>Delete Project</A>";
echo "</TD><td align=center><BR>Grade Level:<BR><SELECT NAME=grade>";

echo "<OPTION VALUE=\"$grade\">$grade</OPTION>";

$grades = mysql_query("SELECT grades from ".$conf['tbl']['grades']." ");
WHILE ($grd = mysql_fetch_array($grades)) {

echo "<OPTION VALUE=\"$grd[grades]\">$grd[grades]</OPTION>";
}

echo "</select></FORM>";


echo "</td></TR><TR ALIGN=CENTER><TD COLSPAN=2>";

IF (!$projecttable['imagename'] OR $projecttable['imagename'] == "NULL" OR $projecttable['imagename'] == "Array") {
echo "<IMG SRC=../images/invis.gif WIDTH=1 HEIGHT=1>";
        }
ELSE {
echo "<A HREF=teachimage.php?image=$image&ID=$ID>$message<BR><IMG SRC=$image WIDTH=$width ALT='Image' BORDER=0></A>";
     }


echo "</TD><TD>";

IF (!$projecttable['imagename'] OR $projecttable['imagename'] == "NULL" OR $projecttable['imagename'] == "Array") {
echo "<A HREF='projectimage.php?ID=$ID&mode=no&image='>Add An Image</A>";
break;                                                              }
ELSE {
echo "<A HREF='projectimage.php?ID=$ID&image=$projecttable[imagename]&mode=no'>Change or Remove Image</A>";
break;     }
}

echo "</TD></TR><TR><TD COLSPAN=3>";
IF ($projecttable['archive']=='yes') {
echo "<B>This project is archived.</B> <A
HREF=indepthadmin.php?archive=no&ID=$HTTP_GET_VARS[ID]>Remove from the archive.";
}
ELSE {
echo "<A HREF=indepthadmin.php?archive=yes&ID=$HTTP_GET_VARS[ID]>Archive Project.</A>";
}


echo "</TD></TR></TABLE>";


$integer='0';

$showing = mysql_query("SELECT * FROM ".$conf['tbl']['studentwork']." , ".$conf['tbl']['projecttable']." WHERE ".$conf['tbl']['studentwork'].".TID = '$newID' && ".$conf['tbl']['projecttable'].".TID = '$newID' && ".$conf['tbl']['projecttable'].".ID = '$ID' && ".$conf['tbl']['studentwork'].".project = ".$conf['tbl']['projecttable'].".project ORDER BY stufirstname, stulastname");
WHILE($studentwork = mysql_fetch_array($showing)) {
$id2=$studentwork['id'];

$studentwork['stuwork'] = deslash($studentwork['stuwork']);
$studentwork['title'] = deslash($studentwork['title']);


echo "<FORM METHOD=POST ACTION=indepthadmin.php>";
echo "<P><TABLE ALIGN=CENTER WIDTH=90% BORDER=1 CELLSPACING=0 CELLPADDING=4 BORDERCOLOR=LIGHTBLUE><TR ALIGN=CENTER>";

$integer=$integer+1;


echo "<TD>First Name <A NAME=$integer></TD><TD>Last Name</TD><TD>Title</TD></TR>";




echo "<TR ALIGN=CENTER>";
echo "<TD><INPUT TYPE=TEXT NAME=newstufirstname VALUE=\"$studentwork[stufirstname]\" SIZE=12></TD>";
echo "<TD><INPUT TYPE=TEXT NAME=newstulastname VALUE=\"$studentwork[stulastname]\" SIZE=12></TD>";
echo "<TD><INPUT TYPE=TEXT NAME=newtitle
VALUE=\"$studentwork[title]\"></TD></TR><TR ALIGN=CENTER>";
echo "<TD>Approved</TD><TD>Give/Remove Approval</TD><TD>Image</TD></TR><TR ALIGN=CENTER>";
echo "<TD>";
IF ($studentwork['active']=='approve')
{
echo "<B>Yes</B>";
}
ELSE
{
echo "<B>No</B>";
}
echo "</TD><TD>";


IF ($studentwork['active']=='approve')
{
echo "<A HREF='indepthadmin.php?id=$id2&approve=noapprove&ID=$studentwork[ID]&integer=$integer'>Remove Approval</A>";
}
ELSE
{
echo "<A HREF='indepthadmin.php?id=$id2&approve=approve&ID=$studentwork[ID]&integer=$integer'>Give Approval</A>";
}
echo "</TD><TD>";
IF (!$studentwork['filename'] OR $studentwork['filename'] == "NULL" OR
$studentwork['filename'] == "Array") {
echo "<A HREF='newimage.php?id=$studentwork[id]&ID=$ID&integer=$integer&mode=no&image='>Add An Image</A>";
                                                                }
ELSE {
echo "<A HREF='newimage.php?id=$studentwork[id]&ID=$ID&image=$studentwork[filename]&integer=$integer&mode=no'>Change or Remove Image</A>";
     }
echo "</TD></TR><TR><TD COLSPAN=3>Work:<BR>";
echo "<TEXTAREA NAME=newstuwork COLS=50 ROWS=6>";
echo "$studentwork[stuwork]</TEXTAREA></TD></TR><TR ALIGN=CENTER>";
echo "<TD>";
echo "<INPUT TYPE=HIDDEN NAME=id VALUE=$studentwork[id]>";
echo "<INPUT TYPE=HIDDEN NAME=ID VALUE='$studentwork[ID]'>";
echo "<INPUT TYPE=HIDDEN NAME=integer VALUE='$integer'>";
echo "<INPUT TYPE=HIDDEN NAME=mode VALUE='editstudent'>";

echo "<INPUT TYPE=submit VALUE='Update Entry'>";

IF (!$studentwork['filename']) { $studentwork['filename'] = ""; }

echo "<P><A
HREF='indepthadmin.php?id=$studentwork[id]&ID=$ID&image=$studentwork[filename]&mode=delete'>Delete Entry</A>";
echo "<P><A HREF=move.php?ID=$ID&id=$studentwork[id]>Move To Another Project</A>";
echo "</TD><TD COLSPAN=2>";


$image = "../images/$studentwork[filename]";
@$size = GetImageSize("$image");

IF ($size[0] > 350) {
$width = 350;
$message = "Enlarge this Image";
                    }
ELSE {
$width = $size[0];
$message = "";
     }

IF (!$studentwork['filename'] OR $studentwork['filename'] == "NULL" OR
$studentwork['filename'] == "Array") {
echo "<IMG SRC=../images/invis.gif WIDTH=1 HEIGHT=1 BORDER=0>";
                                   }
ELSE {
echo "<A HREF=teachimage.php?image=$image&ID=$ID&integer=$integer>$message<BR><IMG SRC=$image WIDTH=$width BORDER=0></A>";
     }
echo "</FORM></TD></TR></TABLE>";
echo "<A HREF=#top>Back to top</A>.";
}
include("../footer.php");
?>