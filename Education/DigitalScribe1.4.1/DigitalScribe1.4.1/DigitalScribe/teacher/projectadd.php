<?
require("checkpass.php");

require("../access.inc.php");

if(isset($HTTP_SESSION_VARS['secure_ghost'])) {
$newID=$HTTP_SESSION_VARS['secure_ghost'];
}
else {
$newID=$HTTP_SESSION_VARS['secure_id'];
}

mysql_connect("$host","$login","$pass") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

mysql_select_db("$db") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

if (isset($_POST['Submit'])) {

$project = $HTTP_POST_VARS['project'];
$type = $_POST['type'];
$grade = $HTTP_POST_VARS['grade'];

IF ($project != NULL && $type != NULL && $grade != NULL)
{

$name = $HTTP_POST_VARS['name'];
$user = $HTTP_POST_VARS['user'];
$date = $HTTP_POST_VARS['date'];


IF ($type == "stuwork") {
mysql_query("INSERT INTO ".$conf['tbl']['projecttable']." (project, teachername, teacheruser, grade, date, archive, TID) VALUES
('".addslash($project)."','".addslash($name)."','".addslash($user)."','".addslash($grade)."','$date','no','$newID')");
} //end if type=stuwork


IF ($type == "homework") {
mysql_query("INSERT INTO ".$conf['tbl']['projecthomework']." (teach_id, proj_title, live, grade, t_name, t_user) VALUES
('$newID','".addslash($project)."','n','".addslash($grade)."','".addslash($name)."','".addslash($user)."')");
} //end if type=homework

header("Location: teacheradmin.php?code=y");
exit;
} //end if blank project

header("Location: projectadd.php?t=$type&err=1");

} //end if submited




$showproject2 = mysql_query("SELECT grade,user,name,filename FROM ".$conf['tbl']['teachers']." WHERE ID = $newID");
WHILE($pro = mysql_fetch_array($showproject2)) {
$user = "$pro[user]";
$name = "$pro[name]";
$filename = "$pro[filename]";
}

echo "<HTML><HEAD><TITLE>Teacher Administration</TITLE>";
echo "<LINK REL ='stylesheet' HREF='../style.css' TYPE='text/css'>";
include("../header1.php");
?>

<FORM METHOD=POST ACTION=projectadd.php>

<?
if($_GET['t']==stuwork) { $type="stuwork"; $type1="Student Work"; $title="Project Title"; }
if($_GET['t']==homework) { $type="homework"; $type1="a Class for Homework Assignments"; $title="Class Title"; }
?>

<span class=title>Add <? echo($type1); ?></span>

<? if ($_GET['err']==1) { echo "<P><span class=title><FONT COLOR=green>Error: Please enter a $title and a grade level.</font></span>"; } ?>

<P><table><tr><td>
<? echo($title); ?>:</td><td><INPUT TYPE=TEXT NAME=project WIDTH=50></td></tr>
<tr><td>Grade Level:
</td><td><SELECT NAME=grade>
<OPTION VALUE=""> </OPTION>
<?
$grade = mysql_query("SELECT grades from ".$conf['tbl']['grades']." ");
WHILE ($grd = mysql_fetch_array($grade)) {

echo "<OPTION VALUE=\"$grd[grades]\">".deslash($grd['grades'])."</OPTION>";
}
?>
</SELECT>
</td></tr><tr><td colspan=2>
<INPUT TYPE=hidden NAME=type value="<? echo($type); ?>">
<INPUT TYPE=HIDDEN NAME=newID VALUE="<? echo $newID ?>">
<INPUT TYPE=HIDDEN NAME=name VALUE="<? echo $name ?>">
<INPUT TYPE=HIDDEN NAME=user VALUE="<? echo $user ?>">
<INPUT TYPE=HIDDEN NAME=date VALUE="<? echo gmdate('F j, Y') ?>">
<INPUT TYPE=SUBMIT NAME=Submit VALUE="Submit"></FORM>
</td></tr></table>
<?

include("../footer.php");

?>