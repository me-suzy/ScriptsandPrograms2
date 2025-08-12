<HTML><HEAD><TITLE>Add Your Work</TITLE>
<LINK REL ="stylesheet" HREF="style.css" TYPE="text/css">
 
<?
include("header1.php");

echo '<span class=title>Add Student Work</span><P>';

require("access.inc.php");

mysql_connect("$host","$login","$pass") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

mysql_select_db("$db") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());



$sql = mysql_query("SELECT project FROM ".$conf['tbl']['projecttable']." WHERE TID = '$HTTP_GET_VARS[teacher]' && archive='no'");
$i=0;
WHILE($row = mysql_fetch_array($sql)) {
    $i++;
    }

  IF ($i<1) {
    echo '<B>Error: A teacher must login and create a project before student work can be added. </B>';
    include("footer.php"); 
    exit;
  }



IF ($HTTP_GET_VARS['bad'] == 'bad') {
echo "<P ALIGN=CENTER><B>You left a field blank, please try again.</P><BR></B>";
                    }
?>

<FORM ENCTYPE="multipart/form-data" METHOD=POST ACTION=add.php>
Please enter your:
<BR>First Name: <INPUT TYPE=text NAME=stufirstname>
<BR>Last Name: <INPUT TYPE=TEXT NAME=stulastname>
<BR>Title: <INPUT TYPE=TEXT NAME=title SIZE=40>
<BR>Work:<BR>
<TEXTAREA NAME=stuwork COLS=50 ROWS=6></TEXTAREA>
<BR>Image (Optional): <INPUT TYPE="FILE" SIZE="25" MAXLENGTH="500" NAME="userfile[]" VALUE="">
<BR>Project:<BR>

<?

$show = mysql_query("SELECT project FROM ".$conf['tbl']['projecttable']." WHERE TID = '$HTTP_GET_VARS[teacher]' && archive='no'");
WHILE ($projecttable = mysql_fetch_array($show)) {

$projecttable['project'] = deslash($projecttable['project']);

echo "<INPUT TYPE=RADIO NAME=project VALUE=\"$projecttable[project]\">";
echo "$projecttable[project]<BR>";
}

?>


<INPUT TYPE=HIDDEN NAME=teacher VALUE="<? echo $HTTP_GET_VARS['teacher'] ?>">
<INPUT TYPE=HIDDEN NAME=active VALUE=noapprove>
<BR><INPUT TYPE=submit VALUE=Submit></FORM>
<?
include("footer.php");
?>