<?php
include "../admin/var.php";
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
    print "<tr class='headline'><td><center>Send PM-<A href='pm.php'><font color='white'>Back to PM main</font></a></center></td></tr>";
    print "<tr class='forumrow'><td>";
    if(isset($_POST['submit']))
    {
       $nameID=$_POST['nameID'];
       $suser="SELECT * from b_users where userID='$nameID'";
       $suser2=mysql_query($suser) or die("Could not get user");
       $suser3=mysql_fetch_array($suser2);
       if(strlen($suser3[username])<1)
       {
         print "There is no player with taht ID. Please go back to <A href='pm.php'>PM Main</a>.";
       }     
       else if(strlen($_POST['subject'])<1)
       {
         print "You did not enter a subject. Please go back to <A href='pm.php'>PM Main</a>";
       }
       else if(strlen($_POST['message'])<1)
       {
         print "You did not enter a message. Please go back to <A href='pm.php'>PM Main</a>";
       }
       else
       {
          $subject=$_POST['subject'];
          $message=$_POST['message'];
          $date=date("U");
          $vartime=date("D M d, Y H:i:s");
          $sendmessage="INSERT into b_pms (sender, receiver,subject,message,therealtime,vartime) values('$getuser3[userID]','$nameID','$subject','$message','$date','$vartime')";
          mysql_query($sendmessage) or die("Could not send message");
          print "PM Sent, please go back to <A href='pm.php'>PM Main</a>.";
       }


    }
    else
    {
       if($usepms=="Yes" && $getuser3[usepm]==1)
       {
         $countpm="SELECT COUNT(*) AS pmcount from b_pms where receiver='$getuser3[userID]'";
         $countpm2=mysql_query($countpm) or die("Could not count pms");
         $pmcount=mysql_result($countpm2,0);
         if ($pmcount>$maxpms)
         {
            print "This user has surpassed his/her maximum inbox limit. Please go back to <A href='../index.php'>Main</a>.";
         }
         else
         {
            print "<form action='writepm.php' method='post'>";
            if(isset($_GET[userID]))
            {
              $userID=$_GET['userID'];
              print "<input type='hidden' name='nameID' value='$userID'>";              
            }
            else
            {
              print "ID of recipient:<br>";
              print "<input type='text' name='nameID' size='5'><br>";
            }
            print "Subject:<br>";
            print "<input name='subject' type='text' size='30'><br>";
            print "Message:<br>";
            print "<textarea name='message' rows='5' cols='40'></textarea><br><br>";
            print "<input type='submit' name='submit' value='send'></form>";
         }
       }
       else
       {
         print "Either the administrator has turned PMS off or the user has chosen not to use PMs. Please go back to <a href='../index.php'>Main</a>";
       }

    }
    print "</td></tr></table>";       
    print "<font size='1'>Script Produced by © <A href='http://www.chipmunk-scripts.com'>Chipmunk Scripts</a></font>";
    
  }
else
  {
    print "Sorry, not logged in  please <A href='login.php'>Login</a><br>";
  
  }

?>

