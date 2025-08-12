<HTML><HEAD><TITLE>View Homework</TITLE>
<LINK REL ="stylesheet" HREF="style.css" TYPE="text/css">
<? 
include("header1.php"); 
echo '<span class=title>Homework</span><P>';

require("access.inc.php");

mysql_connect("$host","$login","$pass") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

mysql_select_db("$db") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

echo "<A HREF=viewhw1.php>Select your Teacher</A> > Select your Class<P>";

$count = mysql_query("SELECT proj_ID FROM ".$conf['tbl']['projecthomework']." WHERE teach_id='$HTTP_GET_VARS[t_id]' AND live='y'");
$i=0;
WHILE($row = mysql_fetch_array($count)) {
    $i++;
    }

  IF ($i<1) {
    echo '<B>This teacher has not put any homework assignments online. </B>';
    include("footer.php"); 
    exit;
  }



echo "Select your class:<UL>";
$class = mysql_query("SELECT proj_title,proj_ID,grade FROM ".$conf['tbl']['projecthomework']." WHERE teach_id='$HTTP_GET_VARS[t_id]'");
WHILE($c = mysql_fetch_array($class)) {

$c['proj_title'] = deslash($c['proj_title']);
$c['grade'] = deslash($c['grade']);

echo "<LI><A HREF=viewhw3.php?proj_ID=$c[proj_ID]>$c[proj_title]</A> - $c[grade]";

}

echo "</UL>";
include("footer.php");
?>