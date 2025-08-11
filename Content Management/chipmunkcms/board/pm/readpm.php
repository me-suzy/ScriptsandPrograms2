<?php
include "../admin/var.php";
include '../connect.php';
session_start();
?>

<link rel="stylesheet" href="../style.css" type="text/css">
<?php
if (isset($_SESSION['user'])) 
  {
    $getuser="SELECT * from b_users where username='$user'";
    $getuser2=mysql_query($getuser) or die("Could not get user info");
    $getuser3=mysql_fetch_array($getuser2);
    print "<center>";
    print "<table class='maintable'>";
    print "<tr class='headline'><td><center>PMs</center></td></tr>";
    print "<tr class='mainrow'><td>";
    if($usepms=="Yes")
    {
      $ID=$_GET['ID'];
      $getpm="SELECT * from b_pms as a, b_users as b where a.pmID='$ID' and a.receiver='$getuser3[userID]' and b.userID=a.sender";
      $getpm2=mysql_query($getpm) or die("Could not get pm");
      $getpm3=mysql_fetch_array($getpm2);
      print "<center><A href='pm.php'>Back to PM main</a>-<A href='replypm.php?ID=$getpm3[pmID]'>Reply</a></center>";
      print "<center><table class='maintable'><tr class='headline'><td colspan='4'><center>PM from $getpm3[playername](#$getpm3[ID])</center></td></tr>";
      print "<tr class='forumrow'><td>";
      $getpm3[message]=strip_tags($getpm3[message]);
      $getpm3[message]=htmlspecialchars($getpm3[message]);
      $getpm3[message]=nl2br($getpm3[message]);
      print "$getpm3[message]";
      print "</td></tr></table>";
      $updatenote="Update b_pms set hasread='1' where pmID='$ID' and receiver='$getuser3[userID]'";
      mysql_query($updatenote) or die(mysql_error());
    }
    else
    {
      print "The Administrator hs turned off PMs.";
      print "</td></tr></table>";    
    }   
      print "<font size='1'>Script Produced by © <A href='http://www.chipmunk-scripts.com'>Chipmunk Scripts</a></font>";
    
  }
else
  {
    print "Sorry, not logged in  please <A href='login.php'>Login</a><br>";
  
  }

?>

