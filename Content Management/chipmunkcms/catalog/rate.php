<link rel='stylesheet' href='style.css' type='text/css'>
<?php
include "connect.php";
include "admin/var.php";
print "<center>";
print "<table class='maintable'>";
print "<tr class='headline'><td><center><b>Rate Tutorial</b></center></td></tr>";
print "<tr class='mainrow''><td>";
if(isset($_POST['submit']))
{
  $now=date("U");
  $duptime=$now-3600*24*30;
  $ID=$_POST['id'];
  $rating=$_POST['rating'];
  $address=$_SERVER["REMOTE_ADDR"];
  $checkdupvote="SELECT * from tut_ip where ip='$address' and votedfor='$ID'";
  $check2=mysql_query($checkdupvote) or die("Could not check duplicate vote");
  $check3=mysql_fetch_array($check2);
  if($check3)
  {
    print "You already voted for this tutorial recently";
  }
  else
  {
     $updatestuff="Update tut_entries set totalscore=totalscore+'$rating',totalvotes=totalvotes+1 where tutid='$ID'";
     mysql_query($updatestuff) or die("Could not update stuff");
     $getstuff="SELECT * from tut_entries where tutid='$ID'";
     $getstuff2=mysql_query($getstuff) or die("Could not get stuff");
     $getstuff3=mysql_fetch_array($getstuff2);
     $avgscore=$getstuff3[totalscore]/$getstuff3[totalvotes];
     $daysent=$now-$getstuff3[timeadded];
     $days=$daysent/(3600*24);
     $rankscore=($avgscore*$getstuff3[totalvotes])/$days;
     $updates="Update tut_entries set avgvote='$avgscore', rankscore='$rankscore' where tutid='$ID'";
     mysql_query($updates) or die("Could not get updates");
     $trackvote="Insert into tut_ip (votedfor,ip,time) values('$ID','$address','$now')";
     mysql_query($trackvote) or die("Could not track vote");
     print "Rating succesful, <A href='index.php'>Back to main</a>.";
  }
  $deltime="DELETE from tut_ip where time<'$duptime'";
  mysql_query($deltime) or die("Could not delete");

}
else
{
   print "Rate from 1 to 5, with 5 being the best.";
   $ID=$_GET['ID'];
   print "<form action='rate.php' method='post'>";
   print "<input type='hidden' name='id' value='$ID'>";
   print "<select name='rating'>";
   print "<option value='1'>1--very poor</option>";
   print "<option value='2'>2--poor</option>";
   print "<option value='3'>3--fair</option>";
   print "<option value='4'>4--good</option>";
   print "<option value='5'>5--excellent</option>";
   print "</select><br><br>";
   print "<input type='submit' name='submit' value='submit'></form>";

}

print "</td></tr></table>";
?>
