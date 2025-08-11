<?php
include "connect.php";
session_start();
print "<link rel='stylesheet' href='../style.css' type='text/css'>";
$user=$_SESSION['user'];
$selectuser="SELECT * from b_users where username='$user'";
$selectuser2=mysql_query($selectuser);
$selectuser3=mysql_fetch_array($selectuser2);

if ($selectuser3[status]>="3")
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
      print "<table width='90%' border='0'>";
      print "<tr class='headline'><td>User Management";
      print "</td></tr>";
      print "<tr class='forumrow'><td>";
      if(isset($_POST['submit']) || isset($_POST['userword'])||isset($_GET['userword']))
      {
         if (!isset($_GET['start']))
         {
            $start=0;
         }
         else
         {
            $start=$_GET['start'];
         }
         $userword=$_POST['userword'];
         $getusers="SELECT * from b_users where username like '%$userword%' and username!='Guest' order by username ASC limit $start, 30";
         $getusers2=mysql_query($getusers) or die("Could not get users");
         print "<table class='maintable'>";
         print "<tr class='headline'><td>Username</td><td>Email</td><td>Title</td><td>status</td><td>Banned?</td><td>Edit</td><td>Delete</td></tr>";
         while($getusers3=mysql_fetch_array($getusers2))
         {
           $status=getstatus($getusers3[status]);
           print "<tr class='forumrow'><td>$getusers3[username]</td><td>$getusers3[email]</td><td>$getusers3[rank]</td><td>$status</td><td>$getusers3[banned]</td><td><A href='edituser.php?userID=$getusers3[userID]'>Edit</a></td><td><A href='deleteuser.php?userID=$getusers3[userID]'>Delete</td></tr>";
         }
         print "</table>";
         print "<table class='regrow'><tr><td>";
         print "<p align='right'><b>Page:</b> ";  
         $order="SELECT * from b_users where username like '%$userword%' and username!='Guest' order by username ASC ";
         $order2=mysql_query($order) or die("2");
         $num=mysql_num_rows($order2);
         $d=0;
         $f=0;
         $g=1;
         $prev=$start-30;
         $next=$start+30;
         if($start>=30)
         {
           print "<A href='usermn.php?userword=$userword&start=$prev'><<</a>&nbsp;";
         }
         while($order3=mysql_fetch_array($order2))
         {
          if($f>=$start-30&&$f<=$start+7*30)
         {
          if($f%30==0)
          {
            print "<A href='usermn.php?userword=$userword&start=$d'><b>$g</b></a> ";
            $g++;
          }  
         }
          $d=$d+1;
          $f++;
         }
        if($start<=$num-30)
        {
          print "<A href='usermn.php?userword=$userword&start=$next'>>>";
        }
        print "</td></tr></table>";

      }
      else
      {
         print "<form action='usermn.php' method='post'>";
         print "Search for user:<br>";
         print "<input type='text' name='userword' size='20'><br><br>";
         print "<input type='submit' name='submit' value='Search for User'></form>";
      }
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

<?php
//function for getting member status
function getstatus($statnum)
{
  if ($statnum==0)
  {
     return "members";
  }
  else if($statnum==1)
  {
     return "moderators";
  }
  else if($statnum==2)
  {
    return "supermoderators";
  }
  else if($statnum==3)
  {
    return "administrators";
  }
  else if($statnum==4)
  {
    return "Head Administrator";
  }
}
?>
    