<?
require("checkpass.php");

?>
<HTML><HEAD><TITLE>Teacher Administration</TITLE>
<LINK REL ="stylesheet" HREF="../style.css" TYPE="text/css">
<? 
include("../header1.php");
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


$showproject2 = mysql_query("SELECT grade,user,name,filename FROM ".$conf['tbl']['teachers']." WHERE ID = $newID");
WHILE($pro = mysql_fetch_array($showproject2)) {
$grade = "$pro[grade]";
$user = "$pro[user]";
$name = "$pro[name]";
$filename = "$pro[filename]";
$teacher = deslash($name);
}
?>
<span class=title>Teacher Administration for <? echo $teacher ?></span> - <A HREF=../login.php?logout=1>Logout</A>

<UL>
<LI><A HREF=projectadd.php?t=stuwork>Create a project for student work</A>.
<LI><A HREF=projectadd.php?t=homework>Add a class to give homework to</A>.
</UL>

<?

if ($_GET['code']=='y') { echo "<P><span class=title><font color=green>Project Added Below</font></span>"; }

$showproject = mysql_query("SELECT project, ID FROM ".$conf['tbl']['projecttable']." WHERE TID  = '$newID'");

echo "<UL><B>Student Work Projects</B>";
WHILE($projecttable = mysql_fetch_array($showproject)) {

echo "<LI><A HREF='indepthadmin.php?ID=$projecttable[ID]'>".deslash($projecttable['project'])."</A>";
$nostu=1;
}
if (!isset($nostu)) { echo "<LI>No student work created."; }
echo "</UL>";


$showhw = mysql_query("SELECT proj_title, proj_ID FROM ".$conf['tbl']['projecthomework']." WHERE teach_id = '$newID'");

echo "<UL><B>Classes that Have Homework</B>";
WHILE($hw = mysql_fetch_array($showhw)) {

echo "<LI><A HREF='indepth_hw.php?ID=$hw[proj_ID]'>".deslash($hw['proj_title'])."</A>";
$nohw=1;
}
if (!isset($nohw)) { echo "<LI>No classes have homework."; }
echo "</UL>";



$showup = mysql_query("SELECT ID FROM ".$conf['tbl']['teachers']." WHERE ID = '$newID'");
WHILE ($teach = mysql_fetch_array($showup)) {
echo "<P><A HREF='edituser.php?ID=$teach[ID]'>Change your password, e-mail address, or name</A>.";
}
include("../footer.php");
?>