<?php
//killmonster amin index
include '../connect.php';
session_start();
?>

<link rel="stylesheet" href="../style.css" type="text/css">
<?php
if (isset($_SESSION['user'])) 
  {
    $user=$_SESSION['user'];
    $getuser="SELECT * from b_users where username='$user'";
    $getuser2=mysql_query($getuser) or die("Could not get user info");
    $getuser3=mysql_fetch_array($getuser2);
    print "<center>";
    print "<table class='maintable'>";
    print "<tr class='headline'><td><center>PMs</center></td></tr>";
    print "<tr class='forumrow'><td>";
    print "<center><A href='writepm.php'>Send PM</a>-<A href='deleteread.php'>Delete all read pms</a>-<A href='../index.php'>Back to message board</a></center>";
    print "<center><table class='maintable'><tr class='headline'><td colspan='5'><center>Your PM box</center></td></tr>";
    print "<tr class='headline'><td></td><td>From</td><td>Subject</td><td>Time sent</td><td>Delete</td></tr>";
    $getyourpms="SELECT * from b_pms as a,b_users as b where a.receiver='$getuser3[userID]' and b.userID=a.sender order by a.therealtime DESC";
    $getyourpms2=mysql_query($getyourpms) or die(mysql_error());
    while($getyourpms3=mysql_fetch_array($getyourpms2))
    {
      print "<tr class='forumrow'><td valign='top' width=6%>";
      if($getyourpms3[hasread]==0)
      {
        print "<img src='../images/yesnewposts.gif' border='0'>";
      }
      else
      {
        print "<img src='../images/topic.gif' border='0'>";
      }
      print "</td><td valign='top'>$getyourpms3[username]</td>";
      print "<td valign='top'>";
      print "<A href='readpm.php?ID=$getyourpms3[pmID]'>$getyourpms3[subject]</a></td><td>$getyourpms3[vartime]</td>";
      print "<td valign='top'><A href='delpm.php?ID=$getyourpms3[pmID]'>Delete PM</a></td></tr>";
    }
    print "<tr class='headline' height='7'><td colspan='5'></td></tr></table><br><br>";

    print "</td></tr></table>";       
    print "<font size='1'>Script Produced by © <A href='http://www.chipmunk-scripts.com'>Chipmunk Scripts</a></font>";
    
  }
else
  {
    print "Sorry, not logged in  please <A href='login.php'>Login</a><br>";
  
  }

?>

