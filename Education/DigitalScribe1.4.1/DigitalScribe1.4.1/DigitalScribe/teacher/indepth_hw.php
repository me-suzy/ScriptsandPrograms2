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

if ($HTTP_GET_VARS['publish']=='n' || $HTTP_GET_VARS['publish']=='y') {
   mysql_query("UPDATE ".$conf['tbl']['projecthomework']." SET live='$HTTP_GET_VARS[publish]' WHERE proj_ID='$ID'");
}


if ($HTTP_GET_VARS['delete_proj']=='y' && isset($HTTP_GET_VARS['ID'])) {
 
    mysql_query("DELETE FROM ".$conf['tbl']['homework']." WHERE proj_id=$HTTP_GET_VARS[ID]");
    mysql_query("DELETE FROM ".$conf['tbl']['projecthomework']." WHERE proj_ID=$HTTP_GET_VARS[ID]");
  
    header("Location:teacheradmin.php");
  
  } //end delete project



if ($HTTP_GET_VARS['delete']=='y' && isset($HTTP_GET_VARS['hw_id']) && isset($HTTP_GET_VARS['ID'])){
 
    mysql_query("DELETE FROM ".$conf['tbl']['homework']." WHERE hw_id=$HTTP_GET_VARS[hw_id]");
  
  } //end delete 1 hw assignment



if(isset($HTTP_POST_VARS['change_hw'])) {


mysql_query("UPDATE ".$conf['tbl']['homework']." SET title=\"$HTTP_POST_VARS[new_title]\", month_due=\"$HTTP_POST_VARS[new_month_due]\", day_due='$HTTP_POST_VARS[new_day_due]', work=\"$HTTP_POST_VARS[new_work]\" WHERE hw_id='$HTTP_POST_VARS[hw_id]'");

}//end change_hw


if(isset($HTTP_POST_VARS['submit'])) {

mysql_query("UPDATE ".$conf['tbl']['projecthomework']." SET proj_title='".addslash($HTTP_POST_VARS[title])."', grade='".addslash($HTTP_POST_VARS[grade])."' WHERE proj_ID='$ID'");

}//end update project hw

if(isset($HTTP_POST_VARS['new_hw'])) {

$teach = mysql_query("SELECT user,name FROM ".$conf['tbl']['teachers']." WHERE ID=$HTTP_POST_VARS[t_id]");
	WHILE($tstuff = mysql_fetch_array($teach)) {
	$t_user = "$tstuff[user]";
	$t_name = "$tstuff[name]";
	} //end get teacher user and name


mysql_query("INSERT INTO ".$conf['tbl']['homework']." (title, work, proj_id, t_name, t_user, month_due, day_due) VALUES
(\"$HTTP_POST_VARS[title]\",\"$HTTP_POST_VARS[work]\",\"$HTTP_POST_VARS[proj_id]\",\"$t_name\",\"$t_user\",\"$HTTP_POST_VARS[month_due]\",\"$HTTP_POST_VARS[day_due]\")");

}//end add hw

?>
<HTML><HEAD><TITLE>Teacher Homework Administration</TITLE>
<LINK REL ="stylesheet" HREF="../style.css" TYPE="text/css">
 
<?
include("../header1.php");

if(isset($HTTP_SESSION_VARS['secure_ghost'])) {
$newID=$HTTP_SESSION_VARS['secure_ghost'];
}
else {
$newID=$HTTP_SESSION_VARS['secure_id'];
}

echo "<BR>Go Back to your <A HREF=teacheradmin.php>list of projects</A>.";

$hwshow = mysql_query("SELECT * FROM ".$conf['tbl']['projecthomework']." WHERE proj_ID=$ID");
	WHILE($phw = mysql_fetch_array($hwshow)) {
	$grade = stripslashes($phw['grade']);
	$proj_title = stripslashes($phw['proj_title']);
	$live = "$phw[live]";


IF ($live == 'y') {
$live=Yes;
}
ELSE {
$live=No;
}

	$teacher = deslash($name);

echo "<FORM METHOD=POST ACTION=indepth_hw.php>";
echo "<TABLE ALIGN=CENTER WIDTH=90% BORDER=1 CELLSPACING=0 CELLPADDING=4 BORDERCOLOR=LIGHTBLUE><TR ALIGN=CENTER>";
echo "<TD>Class Title</TD>";
echo "<TD>Online</TD>";
echo "<TD>Put Online or<BR>Take Offline</TD>";
echo "</TR><TR ALIGN=CENTER>";
echo "<td><INPUT TYPE=text name=title value=\"$proj_title\" width=70></td>";
echo "<td><B>$live</B></td>";
echo "<td>";
IF ($live =='Yes')
{
echo "<A HREF='indepth_hw.php?ID=$phw[proj_ID]&publish=n'>Take Offline</A>";
}
ELSE
{
echo "<A HREF='indepth_hw.php?ID=$phw[proj_ID]&publish=y'>Put Online</A>";
}
echo "</td>";

echo "</tr><tr><td colspan=3>";
?>

<BR>Grade Level: <SELECT NAME=grade>
<OPTION VALUE="<? echo $grade ?>"><? echo deslash($grade) ?></OPTION>
<?
$grade = mysql_query("SELECT grades from ".$conf['tbl']['grades']." ");
WHILE ($grd = mysql_fetch_array($grade)) {

echo "<OPTION VALUE=\"$grd[grades]\">".deslash($grd['grades'])."</OPTION>";
}

echo "</select></td></tr>";
echo "<tr><td colspan=1>";
echo "<INPUT TYPE=hidden name=ID value=$_REQUEST[ID]>";
echo "<input type=submit name=submit value=\"Save Changes\"></td>";
echo "<td colspan=2 align=right><A href=\"indepth_hw.php?ID=$ID&delete_proj=y\">Delete this class</A></td>";
echo "</FORM></TR></TABLE>";

}//end project hw table
?>

<FORM METHOD=POST ACTION=indepth_hw.php>
<P><TABLE ALIGN=CENTER WIDTH=90% BORDER=1 CELLSPACING=0 CELLPADDING=4 BORDERCOLOR=LIGHTBLUE>
<TR ALIGN=CENTER>
<td colspan=2><B>Add Homework for the Class</B></td></tr><tr>
<td>Title:</td><td><INPUT TYPE=text name=title></td></tr><tr>
<td>Month Due:</td><td>
<SELECT NAME=month_due>
<OPTION VALUE="a">January</OPTION>
<OPTION VALUE="b">February</OPTION>
<OPTION VALUE="c">March</OPTION>
<OPTION VALUE="d">April</OPTION>
<OPTION VALUE="e">May</OPTION>
<OPTION VALUE="f">June</OPTION>
<OPTION VALUE="g">July</OPTION>
<OPTION VALUE="h">August</OPTION>
<OPTION VALUE="i">September</OPTION>
<OPTION VALUE="j">October</OPTION>
<OPTION VALUE="k">November</OPTION>
<OPTION VALUE="l">December</OPTION>
</SELECT>
</td></tr><tr>
<td>Day Due:</td><td>

<SELECT NAME=day_due>
<OPTION VALUE="1">1</OPTION>
<OPTION VALUE="2">2</OPTION>
<OPTION VALUE="3">3</OPTION>
<OPTION VALUE="4">4</OPTION>
<OPTION VALUE="5">5</OPTION>
<OPTION VALUE="6">6</OPTION>
<OPTION VALUE="7">7</OPTION>
<OPTION VALUE="8">8</OPTION>
<OPTION VALUE="9">9</OPTION>
<OPTION VALUE="10">10</OPTION>
<OPTION VALUE="11">11</OPTION>
<OPTION VALUE="12">12</OPTION>
<OPTION VALUE="13">13</OPTION>
<OPTION VALUE="14">14</OPTION>
<OPTION VALUE="15">15</OPTION>
<OPTION VALUE="16">16</OPTION>
<OPTION VALUE="17">17</OPTION>
<OPTION VALUE="18">18</OPTION>
<OPTION VALUE="19">19</OPTION>
<OPTION VALUE="20">20</OPTION>
<OPTION VALUE="21">21</OPTION>
<OPTION VALUE="22">22</OPTION>
<OPTION VALUE="23">23</OPTION>
<OPTION VALUE="24">24</OPTION>
<OPTION VALUE="25">25</OPTION>
<OPTION VALUE="26">26</OPTION>
<OPTION VALUE="27">27</OPTION>
<OPTION VALUE="28">28</OPTION>
<OPTION VALUE="29">29</OPTION>
<OPTION VALUE="30">30</OPTION>
<OPTION VALUE="31">31</OPTION>
</SELECT>

</td></tr><tr>
<td colspan=2>Assignment:<BR><TEXTAREA NAME=work ROWS=6 COLS=50></TEXTAREA></td></TR><TR>
<td colspan=2>
<INPUT TYPE=hidden name=proj_id value="<? echo($ID); ?>">
<INPUT TYPE=hidden name=ID value="<? echo($ID); ?>">
<INPUT TYPE=hidden name=t_id value="<? echo($newID); ?>">
<INPUT TYPE=submit name=new_hw value="Add Homework"></td></tr></table>

<?

$listhw = mysql_query("SELECT * FROM ".$conf['tbl']['homework']." WHERE proj_id=$ID ORDER BY month_due,day_due");
  WHILE($hw = mysql_fetch_array($listhw)) {


IF ($hw[month_due]=='a') { $hw[month_due]='January'; $realmonth='January'; }
ELSEIF ($hw[month_due]=='h') { $realmonth='August'; }
ELSEIF ($hw[month_due]=='i') { $realmonth='September'; }
ELSEIF ($hw[month_due]=='j') { $realmonth='October'; }
ELSEIF ($hw[month_due]=='k') { $realmonth='November'; }
ELSEIF ($hw[month_due]=='l') { $realmonth='December'; }
ELSEIF ($hw[month_due]=='b') { $realmonth='February'; }
ELSEIF ($hw[month_due]=='c') { $realmonth='March'; }
ELSEIF ($hw[month_due]=='d') { $realmonth='April'; }
ELSEIF ($hw[month_due]=='e') { $realmonth='May'; }
ELSEIF ($hw[month_due]=='f') { $realmonth='June'; }
ELSE { $realmonth='July'; }


  ?>

  <FORM METHOD=POST ACTION=indepth_hw.php>
  <P><TABLE ALIGN=CENTER WIDTH=90% BORDER=1 CELLSPACING=0 CELLPADDING=4 BORDERCOLOR=LIGHTBLUE>
  <TR ALIGN=CENTER>
  <td colspan=2><B>Current Homework Assignments</B></td></tr><tr>
  <td>Title:</td><td><INPUT TYPE=text name=new_title value="<? echo($hw[title]); ?>"></td></tr><tr>
  
  <td>Month Due:</td><td>
<SELECT NAME=new_month_due>
<OPTION VALUE="<? echo($hw['month_due']); ?>"><? echo($realmonth); ?></OPTION>
<OPTION VALUE="a">January</OPTION>
<OPTION VALUE="b">February</OPTION>
<OPTION VALUE="c">March</OPTION>
<OPTION VALUE="d">April</OPTION>
<OPTION VALUE="e">May</OPTION>
<OPTION VALUE="f">June</OPTION>
<OPTION VALUE="g">July</OPTION>
<OPTION VALUE="h">August</OPTION>
<OPTION VALUE="i">September</OPTION>
<OPTION VALUE="j">October</OPTION>
<OPTION VALUE="k">November</OPTION>
<OPTION VALUE="l">December</OPTION>
</SELECT>
</td></tr><tr>
<td>Day Due:</td><td>

<SELECT NAME=new_day_due>
<OPTION VALUE="<? echo($hw[day_due]); ?>"><? echo($hw['day_due']); ?></OPTION>
<OPTION VALUE="1">1</OPTION>
<OPTION VALUE="2">2</OPTION>
<OPTION VALUE="3">3</OPTION>
<OPTION VALUE="4">4</OPTION>
<OPTION VALUE="5">5</OPTION>
<OPTION VALUE="6">6</OPTION>
<OPTION VALUE="7">7</OPTION>
<OPTION VALUE="8">8</OPTION>
<OPTION VALUE="9">9</OPTION>
<OPTION VALUE="10">10</OPTION>
<OPTION VALUE="11">11</OPTION>
<OPTION VALUE="12">12</OPTION>
<OPTION VALUE="13">13</OPTION>
<OPTION VALUE="14">14</OPTION>
<OPTION VALUE="15">15</OPTION>
<OPTION VALUE="16">16</OPTION>
<OPTION VALUE="17">17</OPTION>
<OPTION VALUE="18">18</OPTION>
<OPTION VALUE="19">19</OPTION>
<OPTION VALUE="20">20</OPTION>
<OPTION VALUE="21">21</OPTION>
<OPTION VALUE="22">22</OPTION>
<OPTION VALUE="23">23</OPTION>
<OPTION VALUE="24">24</OPTION>
<OPTION VALUE="25">25</OPTION>
<OPTION VALUE="26">26</OPTION>
<OPTION VALUE="27">27</OPTION>
<OPTION VALUE="28">28</OPTION>
<OPTION VALUE="29">29</OPTION>
<OPTION VALUE="30">30</OPTION>
<OPTION VALUE="31">31</OPTION>
</SELECT>

</td></tr><tr>
  
  
  <td colspan=2>Assignment:<BR><TEXTAREA NAME=new_work ROWS=6 COLS=50><? echo($hw[work]); ?></TEXTAREA></td></TR><TR>
  <td>
  <INPUT TYPE=hidden name=hw_id value="<? echo($hw[hw_id]); ?>">
  <INPUT TYPE=hidden name=ID value="<? echo($ID); ?>">
  <INPUT TYPE=submit name=change_hw value="Save Changes"></form>
  </td><td align=right>
  
  <?
  echo "<A href=\"indepth_hw.php?ID=$ID&hw_id=$hw[hw_id]&delete=y\">Delete this Homework</A>";
  echo "</td></tr></table>";
  echo "<A HREF=#top>Back to top</A>.";

  } //end list hw

include("../footer.php");
?>