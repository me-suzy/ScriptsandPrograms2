<?
include "connect.php";
session_start();
?>

<center>
<table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#6B84AE" width="460" id="AutoNumber1" height="198">
  <tr>
    <td width="460" background="topbackground.jpg" height="20" valign="top">
   <font color="blue"><center><b>Chipmunk Stories<b></center></font></td>
  </tr>
  <tr>
    <td width="460" height="177" bgcolor="#F2F2F2" valign="top">
<?
if (isset($_SESSION['username']))
{
if(isset($_GET['ID'])) //looking at specific story
 {
  $ID=$_GET['ID'];
  $r="SELECT * from s_titles where ID='$ID'";
  $r2=mysql_query($r) or die("could not select title");
  $r3=mysql_fetch_array($r2);
  print "<center>$r3[title]</center><br><br>";
  $story="SELECT * from s_entries where parent='$ID'";
  $story2=mysql_query($story) or die("Could not select story");
  while($story3=mysql_fetch_array($story2))
    {
       $story3[entry]=htmlspecialchars($story3[entry]);
       $story3[entry]=wordwrap($story3[entry], 30, "\n", 1);
       print "$story3[entry]<br>";
       print "<A href='delete.php?ID=$story3[ID]'>Delete entry</a><br><br>";
    }
    print "<br><br><font size='1'>Powered by © <A href='http://www.chipmunk-scripts.com'><font size='1'>Chipmunk         
    stories</font></a></font>";
  }
 
 else //looking at root
  {
     $titleselect="SELECT * from s_titles";
     $titleselect2=mysql_query($titleselect) or die ("Could not select stories");
     while ($titleselect3=mysql_fetch_array($titleselect2))
     {
       print "<A href='deletepost.php?ID=$titleselect3[ID]'>$titleselect3[title]</a>($titleselect3[numposts] parts)<br>";
     }
     print "<br><br><font size='1'>Powered by © <A href='http://www.chipmunk-scripts.com'><font size='1'>Chipmunk         
     stories</font></a></font>";
  }
 }
else
{
  print "Not logged in as admin";
}
?>
</td></tr></table></center>
 
