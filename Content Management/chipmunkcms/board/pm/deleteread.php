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
    print "<tr class='headline'><td><center>Delete all read PMS - <A href='pm.php'><font color='white'>Back to PM main</font></a></center></td></tr>";
    print "<tr class='forumrow'><td>";
    if(isset($_POST['submit']))
    {
       $delmessage="DELETE FROM b_pms where hasread=1 and receiver='$getuser3[userID]'";
       mysql_query($delmessage) or die("Could not delete message");
       print " Read PMs deleted, back to <A href='pm.php'>PM Main</a>"; 


    }
    else
    {
       print "Are you sure you want to delete this PM?<br>";
       print "<form action='deleteread.php' method='post'>";
       print "<input type='submit' name='submit' value='delete'></form>";
   

    }
    print "</td></tr></table>";       
    print "<font size='1'>Script Produced by © <A href='http://www.chipmunk-scripts.com'>Chipmunk Scripts</a></font>";
    
  }
else
  {
    print "Sorry, not logged in  please <A href='login.php'>Login</a><br>";
  
  }

?>

