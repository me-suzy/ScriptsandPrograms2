<?PHP
include "connect.php";
session_start();
$user=$_SESSION['user'];
print "<link rel='stylesheet' href='../style.css' type='text/css'>";
$user=$_SESSION['user'];
$selectuser="SELECT * from b_users where username='$user'";
$selectuser2=mysql_query($selectuser);
$selectuser3=mysql_fetch_array($selectuser2);

if ($selectuser3[status]>=3)
   {
     print "<table border='0' class='maintable'>";
     print "<tr><td valign='top'><center>";
     print "<table width='70%' border='0'>";
     print "<tr class='headline'><td>Admin Options";
     print "</td></tr>";
     print "<tr class='forumrow'><td>";
     include "adminleft.php";
     print "</td></tr></table></center></td>";
     print "<td valign='top' width='75%'><p align='left'>";
     print "<table width='70%' border='0'>";
     print "<tr class='headline'><td>Delete User";
     print "</td></tr>";
     print "<tr class='forumrow'><td>";
     if(isset($_POST['submit']))
     {
       $userid=$_POST['userid'];
       $getuserinfo="SELECT * from b_users where userID='$userid'";
       $getuserinfo2=mysql_query($getuserinfo) or die("Could not grab user info");
       $getuserinfo3=mysql_fetch_array($getuserinfo2);
       if($selectuser3[status]>$getuserinfo3[status] || $selectuser3[userID]==$getuserinfo3[userID])
       {
          $username=$_POST['username'];
          $password=$_POST['password'];
          $userstatus=$_POST['userstatus'];
          $email=$_POST['email'];
          $rank=$_POST['rank'];
          $isbanned=$_POST['isbanned'];
          $sig=$_POST['sig'];
          if(strlen($password)<1)
          {
             $updateaccount="Update b_users set username='$username', status='$userstatus', email='$email', rank='$rank', banned='$isbanned', sig='$sig' where userID='$userid'";       
             mysql_query($updateaccount) or die("Could not update account");
             print "Account updated, password not changed.";        

          }
          else
          {
             $password=md5($password);
             $updateaccount="Update b_users set username='$username',password='$password', status='$userstatus', email='$email', rank='$rank', banned='$isbanned', sig='$sig' where userID='$userid'";
             mysql_query($updateaccount) or die("Could not update account");
             print "Account updated"; 
          }

       }
       else
       {
         print "You cannot edit someone's whose permissions are the same or higher than yours.";       
       }
     }
     else if(isset($_GET['userID']))
     {        
       $userid=$_GET['userID'];
       $getuserinfo="SELECT * from b_users where userID='$userid'";
       $getuserinfo2=mysql_query($getuserinfo) or die("Could not grab user info");
       $getuserinfo3=mysql_fetch_array($getuserinfo2);
       print "<form action='edituser.php' method='post'>";
       print "<input type='hidden' name='userid' value='$userID'>";
       print "Username:<br>";
       print "<input type='text' name='username' value='$getuserinfo3[username]'><br>";
       print "Password:(leave blank if no change)<br>";
       print "<input type='password' name='password'><br>";
       if ($getuserinfo3[status]==4)
       {
          print "Status: Head Administrator";
          print "<input type='hidden' name='userstatus' value='4'><br>";
       }
       else
       {
         print "Status:<br>";
         print "<select name='userstatus'>";
         print "<option value='0'>Member</option><br>";
         print "<option value='1'>Moderator</option><br>";
         print "<option value='2'>Supermoderator</option><br>";
         print "<option value='3'>Administrator</option><br>";
         print "</select><br>";
       }
       print "E-mail:<br>";
       print "<input type='text' name='email' value='$getuserinfo3[email]'><br>";
       print "Rank(Put 0 if you want it to be determined by # of posts)<br>";
       print "<input type='text' name='rank' value='$getuserinfo3[rank]'><br>";
       print "Is this user banned from posting?<br>";
       print "<select name='isbanned'>";
       print "<option value='No'>No</option><br>";
       print "<option value='Yes'>Yes</option><br>";
       print "</select><br>";
       print "Signature:<br>";
       print "<textarea name='sig' rows='5' cols='40'>$getuserinfo3[sig]</textarea><br>";
       print "<input type='submit' name='submit' value='Edit This user'></form>";

     }
     else
     {
        print "You did not select a user to Edit.";

     }
     print "</td></tr></table></p>";
     print "</td></tr></table>";    
     print "</center>";
   }
else
   {
     print "<table width='70%' border='0'>";
     print "<tr class='headline'><td><center>Not logged in as Admin</td></tr>";
     print "<tr class='forumrow'><td>";
     print "You are not logged in as Administrator, please log in.";
     print "<form method='POST' action='../authenticate.php'>";
     print "Type Username Here: <input type='text' name='username' size='15'><br>";
     print "Type Password Here: <input type='password' name='password' size='15'><br>";
     print "<input type='submit' value='submit' name='submit'>";
     print "</form>";
     print "</td></tr></table>";
   }

?>

