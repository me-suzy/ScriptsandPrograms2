<?php
include "admin/connect.php";
if(!isset($_GET['ID']))
{
  //do nothing
}
else
{
  $ID=$_GET['ID'];
  $update="SELECT * from rl_links where ID='$ID'";
  $update2=mysql_query($update) or die("Could not update");
  $update3=mysql_fetch_array($update2);
  $addhit="update rl_links set out=out+'1' where ID='$ID'";
  mysql_query($addhit) or die("Could not update stat");
  print "<META HTTP-EQUIV = 'Refresh' Content = '1; URL =$update3[url]'>";
}
?>