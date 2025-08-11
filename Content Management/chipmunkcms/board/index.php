<?
include 'connect.php';
session_start();
include "admin/var.php";
include "smilies.php";
while (@ob_end_clean()); 
?>
<?php
print "<title>$sitetitle</title>";
?>

<link rel="stylesheet" href="style.css" type="text/css">
<body>
<center>

<?
include "title.php";
?>
</center>
<br><br>
</font>
<center>
<?php
if(isset($_SESSION['user']))
{
  $user=$_SESSION['user'];
  $getuser="SELECT * from b_users where username='$user'";
  $getuser2=mysql_query($getuser) or die("Could not get user info");
  $getuser3=mysql_fetch_array($getuser2);
  $thedate=date("U");
  $checktime=$thedate-200;
  $uprecords="Update b_users set lasttime='$thedate' where userID='$getuser3[userID]'";
  mysql_query($uprecords) or die("Could not update records");
  if($getuser3[tsgone]<$checktime)
  {
    $updatetime="Update b_users set tsgone='$thedate', oldtime='$getuser3[tsgone]' where userID='$getuser3[userID]'";
    mysql_query($updatetime) or die("Could not update time");
  }
}
else
{
  $chipcookie = $HTTP_COOKIE_VARS["$cookiename"];
  $userID=$chipcookie[0];
  $pass=$chipcookie[1];
  $thedate=date("U");
  $checktime=$thedate-200;
  $getuser="SELECT * from b_users where userID='$userID' and password='$pass'";
  $getuser2=mysql_query($getuser) or die("COuld not draw cookies");
  $getuser3=mysql_fetch_array($getuser2);
  if(strlen($getuser3[username])>1)
  {
    $_SESSION['user']=$getuser3[username];
    $uprecords="Update b_users set lasttime='$thedate' where userID='$getuser3[userID]'";
    mysql_query($uprecords) or die("Could not update records");
    if($getuser3[tsgone]<$checktime)
    {
      $updatetime="Update b_users set tsgone='$thedate', oldtime='$getuser3[tsgone]' where userID='$getuser3[userID]'";
      mysql_query($updatetime) or die("Could not update time");
    }
  }
  else
  {
    $ip=$_SERVER["REMOTE_ADDR"];
    $insertguestip="REPLACE guestsonline set time='$thedate', guestip='$ip'";
    mysql_query($insertguestip) or die("Could not insert guestip");
  }
}
if(strlen($getuser3[username])<1)
{
  $getuser3[status]=-1;
}
if(isset($_GET['forumID'])&&isset($_GET['ID'])&&$_GET['ID']!=0) //If looking at specific post
 {
   if(!isset($_GET['start']))
   {
    $start=0;
   }
   else
   {
    $start=$_GET['start'];
   }
   $forumID=$_GET['forumID'];
   $ID=$_GET['ID'];
   $user=$_SESSION['user'];
   $getranks="SELECT * from b_ranks order by postsneeded ASC";
   $getranks2=mysql_query($getranks);
   $updateviews="update b_posts set views=views+1 where ID='$ID'";
   mysql_query($updateviews) or die("Could not update views");
   print "<table class='maintable'>";
   print "<tr class='headline'><td><center>User Options</center></td></tr>";
   print "<tr class='forumrow'><td>";
   if (isset($_SESSION['user']))
   {
     print "<b>Logged in as $user--</b><A href='usercp.php?username=$user'><b>User CP</b></a>--<A href='logout.php'><b>Logout</b></a>";
   }
   if (!isset($_SESSION['user']))
    {
       print "<A href='register.php'><b>Register</b></a>--<A href='login.php'><b>Login</b></a>";
    }
   print "--<A href='top.php'><b>Top 20 Posters</b></a>--<A href='search.php'><b>Search Topics</b></a>";
   print "</td></tr></table><br><br>";
   print "<table class='maintable' cellspacing='1'>";
   $getthread="SELECT * from b_posts, b_forums where b_forums.ID=b_posts.postforum and b_posts.ID='$ID'";
   $getthread2=mysql_query($getthread) or die("Could not get thread");
   $getthread3=mysql_fetch_array($getthread2);
   if(!isset($_SESSION['user']))
   {
     $getuser3[status]=-1;
   }
   if($getthread3[permission_min]>$getuser3[status])
   {
     die("<table class='maintable'><tr class='headline'><td><center>No permission</center></td></tr><tr class='forumrow'><td><center>You do not have permission to view this thread</center></td></tr></table>");
   }
   print "<tr class='regrow'><td colspan='2'><p align='left'><A href='index.php'>Forum Main</a>>><A href='index.php?forumID=$getthread3[postforum]'>$getthread3[name]</a>>>$getthread3[title]</p></td>";
   print "<td><p align='right'><a href='newtopic.php?forumID=$forumID'>New Topic</a>";
   print "-";
   print "<a href='reply.php?forumID=$forumID&ID=$ID'>Reply</a>";
   if($getuser3[status]>1)
   {
      print "-<A href='poststicky.php?forumID=$forumID'>Post Sticky</a>";
   }
   print "</p></td></tr></table>";
   


   print "<table class='maintable'>";
   $postselect="SELECT * from b_users u, b_posts p WHERE u.userID = p.author and p.ID='$ID'";
   $postselect2=mysql_query($postselect) or die(mysql_error());
   $threadselect="SELECT * FROM b_users u, b_posts p  WHERE p.threadparent='$ID' and u.userID = p.author order by p.ID ASC limit $start, $numrepliesperpage";
   $threadselect2=mysql_query($threadselect) or die(mysql_error());  
   print "<tr class='headline'><td valign='top'>";
   print "<center>Author</center></td><td valign='top'><center>Post</center></td></tr>"; 
     
    
   while($postselect3=mysql_fetch_array($postselect2))
   {
    $postselect3[post]=strip_tags($postselect3[post]);
    $postselect3[post]=BBCode($postselect3[post]);
    if($postselect3[nosmilies]==0)
    {
      $postselect3[post]=Smiley($postselect3[post]); 
    }  
    if($postselect3['rank']!='0')
    {
      $rank=$postselect3[rank];
    }
    else
    {  
      $rank=getrank($postselect3[posts],$getranks2);
    }
    $group=getstatus($postselect3[status]);
    if(mysql_num_rows($getranks2)>0)
    {
      mysql_data_seek($getranks2, 0); 
    }
    if($start==0) //if the start is zero then select a topic
    { 
     if($postselect3[username]!="Guest")
     {
      print "<tr class='forumrow'><td width='20%' valign='top'><A href='profile.php?userID=$postselect3[userID]'><b>$postselect3[username]</b></a><br>";
      if($allowavatar=="Yes" && strlen($postselect3[avatar])>0)
      {
         $postselect3[avatar]=strip_tags($postselect3[avatar]);
         print "<img src='$postselect3[avatar]' height='$avatarheight' width='$avatarwidth' border='0'><br>";
      }
      print "Rank:$rank<br>Group: $group<br>Posts: $postselect3[posts]<br>";
      if($getuser3[status]>=3)
      {
        print "IP: $postselect3[ipaddress]<br><br>";
      }
      else
      {
        print "IP Logged<br><br>";
      }
      if($usepms=="Yes" && $postselect3[usepm]==1) //display PM
      {
         print "PM ID and RPS ID: $postselect3[userID]<br>";
         print "<A href='pm/writepm.php?userID=$postselect3[userID]'>PM [$postselect3[username]]</a><br><br>";
      }
      if($playrps=="Yes" && $postselect3[rps]==1)
      {
         print "RPS Score: $postselect3[rpsscore]<br>";
         print "<A href='challengerps.php?ID=$postselect3[userID]'>RPS challenge</a><br><br>";
      }
      if(strlen($postselect3['photo'])>1)
      {
         print "<A href=\"javascript:popWin('viewphoto.php?ID=$postselect3[userID]',800, 600)\">View Member Photo</a><br><br>";
      }
      if($getuser3[status]>=2)
      {
        print "<A href='banuser.php?userID=$postselect3[author]'>Ban User</a>"; //display ban user for admins
      }
      print "</td>";
     }
      else
     {
       print "<tr class='forumrow'><td width='20%' valign='top'><b>$postselect3[username]</b><br>Group: Unregistered<br>";
       if($getuser3[status]>=3)
       {
         print "IP: $postselect3[ipaddress]<br>";
       }
       else
       {
         print "IP Logged<br>";
       }
       
       print "</td>";
     }
     print "<td width='80%' valign='top'>Last replied to on $postselect3[timepost]<br>";
     print "<A href='edit.php?forumID=$forumID&ID=$postselect3[ID]'>Edit Post</a>|<A href='quote.php?forumID=$forumID&ID=$postselect3[ID]'>Quote</a>";
     if($getuser3[status]>0)
     {
       if($postselect3[locked]==0)
       {
         print "|<A href='lockthread.php?ID=$postselect3[ID]'>Lock Thread</a>";
       }
       else
       {
         print "|<A href='unlockthread.php?ID=$postselect3[ID]'>Unlock Thread</a>";
       }
         print "|<A href='deletepost.php?ID=$postselect3[ID]'>Delete Thread</a>|<A href='movethread.php?ID=$postselect3[ID]'>Move Thread</a>";
     }
     print "<hr>";
     print "$postselect3[post]<br>";
     if(($allowsigs=="Yes" || $allowsigs="yes")&&$postselect3[sig]) // if signatures are allowed
     {
       $postselect3[sig]=strip_tags($postselect3[sig]);
       $postselect3[sig]=Smiley($postselect3[sig]);
       $postselect3[sig]=BBcode($postselect3[sig]);
       print "-----------------------------<br>";
       print "$postselect3[sig]<br>";
     }
     print "<hr></td></tr>";
    }
   }
  $i=0;
   while($threadselect3=mysql_fetch_array($threadselect2))
   {
      
       $threadselect3[post]=strip_tags($threadselect3[post]);
       $threadselect3[post]=BBCode($threadselect3[post]);
       if($threadselect3[nosmilies]==0)
       {
         $threadselect3[post]=Smiley($threadselect3[post]);
       }
       if($threadselect3['rank']=='0')
       {
         $rank1=getrank($threadselect3[posts],$getranks2);
       }
       else
       {
         $rank1=$threadselect3['rank'];
       }
       $groups=getstatus($threadselect3[status]);
       mysql_data_seek($getranks2, 0); 
     if($threadselect3[username]!="Guest")
     {
       print "<tr class='forumrow'><td width='20%' valign='top'><A href='profile.php?userID=$threadselect3[userID]'><b>$threadselect3[username]</a></b><br>";
       if($allowavatar=="Yes" && strlen($threadselect3[avatar])>0)
       {
         $threadselect3[avatar]=strip_tags($threadselect3[avatar]);
         print "<img src='$threadselect3[avatar]' height='$avatarheight' width='$avatarwidth' border='0'><br>";
       }
       print "Rank:$rank1<br>Group: $groups<br>Posts: $threadselect3[posts]<br>";
       if($getuser3[status]>=3)
       {
         print "IP: $threadselect3[ipaddress]<br>";
       }
       else
       {
         print "IP Logged<br>";
       }
       if($usepms=="Yes" && $threadselect3[usepm]==1)
       {
         print "PM ID and RPS ID: $threadselect3[userID]<br>";
         print "<A href='pm/writepm.php?userID=$threadselect3[userID]'>[PM $threadselect3[username]]</A><BR><br>";
       }
       if($playrps=="Yes" && $threadselect3[rps]==1)
       {
         print "RPS score: $threadselect3[rpsscore]<br>";
         print "<A href='challengerps.php?ID=$threadselect3[userID]'>RPS challenge</a><br><br>";
       }
       if(strlen($threadselect3['photo'])>1)
       {
         print "<A href=\"javascript:popWin('viewphoto.php?userID=$threadselect3[userID]',800, 600)\">View Member Photo</a><br><br>";
       }
       if($getuser3[status]>=2)
       {
         print "<A href='banuser.php?userID=$threadselect3[userID]'>Ban User</a>";
       }
       
       print "</td>";
     }
     else
     {
       print "<tr class='forumrow'><td width='20%' valign='top'><b>$threadselect3[username]</b><br>Group: unregistered<br>";
       if($getuser3[status]>=3)
       {
         print "IP: $threadselect3[ipaddress]";
       }
       else
       {
         print "IP Logged";
       }

      
       print "</td>";
     }
     print "<td width='80%' valign='top'>Posted at $threadselect3[timepost]<br>";
     print "<A href='edit.php?forumID=$forumID&ID=$threadselect3[ID]'>Edit post</a>|<A href='quote.php?forumID=$forumID&ID=$threadselect3[ID]'>Quote</a>";
     if($getuser3[status]>0)
     {
         print "|<A href='deletepost.php?ID=$threadselect3[ID]'>Delete post</a>";
     }
     print "<hr>";
     print "$threadselect3[post]<br>";
     if(($allowsigs=="Yes" || $allowsigs="yes")&&$threadselect3[sig]) // if signatures are allowed
     {
       $threadselect3[sig]=strip_tags($threadselect3[sig]);
       $threadselect3[sig]=Smiley($threadselect3[sig]);
       $threadselect3[sig]=BBcode($threadselect3[sig]);
  
       print "-----------------------------<br>";
       print "$threadselect3[sig]<br>";
     }
 
     print "<hr></td></tr>";
     $i++;
   }
   print "<tr class='catline'><tr height='10'></td></tr>";
   print "</table>"; 
   print "<table class='regrow'><tr><td>";
   print "<p align='right'><b>Page:</b> ";  
   $order="SELECT COUNT(*) FROM b_users u, b_posts p  WHERE p.threadparent='$ID' and u.userID = p.author order by p.ID ASC";
   $order2=mysql_query($order) or die("2");
   $d=0;
   $f=0;
   $g=1;
   $order3=mysql_result($order2,0);
   $prev=$start-$numrepliesperpage;
   $next=$start+$numrepliesperpage;
   if($start>=$numrepliesperpage)
   {
     print "<A href='index.php?forumID=$forumID&ID=$ID'>First</a>&nbsp&nbsp;&nbsp;";
     print "<A href='index.php?forumID=$forumID&ID=$ID&start=$prev'><<</a>&nbsp;";
   }
   while($f<$order3)
   {
     if($f%$numrepliesperpage==0)
       {
         if($f>=$start-3*$numrepliesperpage&&$f<=$start+7*$numrepliesperpage)
         {
           print "<A href='index.php?forumID=$forumID&ID=$ID&start=$d'><b>$g</b></a> ";
           $g++;
         }
       }
     $d=$d+1;
     $f++;
   }
   if($start<$order3-$numrepliesperpage)
   {
     print "&nbsp;<A href='index.php?forumID=$forumID&ID=$ID&start=$next'>>></a>&nbsp;&nbsp;&nbsp;";
     $last=$order3-$numrepliesperpage;
     print "<A href='index.php?forumID=$forumID&ID=$ID&start=$last'>Last</a>";
   }
   print "</td></tr></table>";
   print "</p><br><br><center><font size='1'>Powered by © <A href='http://www.chipmunk-scripts.com'>Chipmunk Board</a></center>";
    if($getuser3[status]>=3)
    {
      print "<center><A href='admin/index.php'>Admin CP</a></center>";
    }


 }

else if(isset($_GET['forumID'])&&(!isset($_GET['ID']) || $_GET['ID']==0)) //looking at specific forum index
 {
   if(!isset($_GET['start']))
   {
     $start=0;
   }
   else
   {
     $start=$_GET['start'];
   }
   $forumID=$_GET['forumID'];
   $ID=$_GET['ID'];
   $user=$_SESSION['user'];
   $selection="SELECT * from b_posts,b_users where  b_posts.author=b_users.userID  and b_posts.threadparent='NADA' and b_posts.postforum='$forumID' order by b_posts.value DESC, b_posts.telapsed DESC limit $start, $numtopicsperpage";     
   $selection2=mysql_query($selection);
  
   print "<table class='maintable'>";
   print "<tr class='headline'><td><center>User Options</center></td></tr>";
   print "<tr class='forumrow'><td>";
   if (isset($_SESSION['user']))
   {
     print "<b>Logged in as $user--</b><A href='usercp.php?username=$user'><b>User CP</b></a>--<A href='logout.php'><b>Logout</b></a>";
   }
   if (!isset($_SESSION['user']))
    {
       print "<A href='register.php'><b>Register</b></a>--<A href='login.php'><b>Login</b></a>";
    }
   print "--<A href='top.php'><b>Top 20 Posters</b></a>--<A href='search.php'><b>Search Topics</b></a>";
   print "</td></tr></table><br>";
   print "<table class='maintable' cellspacing='0'>";
   print "<tr class='regrow'><td valign='top'><p align='left'>";
   $getforuminfo="SELECT * from b_forums where ID='$forumID'";
   $getforuminfo2=mysql_query($getforuminfo) or die("Could not get forum info");
   $getforuminfo3=mysql_fetch_array($getforuminfo2);
   if(!isset($_SESSION['user']))
   {
     $getuser3[status]=-1;
   }
   if($getforuminfo3[permission_min]>$getuser3[status])
   {
     die("<table class='maintable'><tr class='headline'><td><center>No permission</center></td></tr><tr class='forumrow'><td><center>You do not have permission to access this forum</center></td></tr></table>");
   }
   print "<A href='index.php'>Forum Main</a>>>$getforuminfo3[name]</p>";
   print "</p></td>";
   print "<td valign='top'><p align='right'>";
   print "<a href='newtopic.php?forumID=$forumID'>New Topic</a>";
   if($getuser3[status]>1)
   {
     print "--<A href='poststicky.php?forumID=$forumID'>Post Sticky</a>";
   }
   print "</p></td></tr></table>";
   print "<table class='maintable' cellspacing='1'>";
   print "<tr class='headline'>";
   print "<td width='40%' colspan='2'>Topic</td>";
   print "<td width='20%' g'>Topic Starter</td>";
   print "<td width='5%'>Replies</td>";
   print "<td width='5%'>Views</td>";
   print "<td width='30%' >Last Post</td></tr>";
   while($selection3=mysql_fetch_array($selection2))
      {
         print "<tr class='forumrow'>";
         print "<td width='2%'>";
         if($selection3[value]>0)
         {
           if($selection3[locked]==1)
           {
             print "<img src='images/lockedsticky.gif' border='0'></td>";
           }
           else
           {
             print "<img src='images/sticky.gif' border='0'></td>";
           }
         } 
         else if($selection3[locked]==0)
         {
           if($selection3[telapsed]>$getuser3[oldtime])
           {
             print "<img src='images/yesnewposts.gif' border='0'></td>";
           }
           else
           {
             print "<img src='images/topic.gif' border='0'></td>";
           }
         }
         else if($selection3[locked]==1)
         {
           print "<img src='images/locked.gif' border='0'></td>";
         }
         print "<td width='38%'><A href='index.php?forumID=$forumID&ID=$selection3[ID]'><b>$selection3[title]</b></a></td>";
         print "<td width='20%'>$selection3[username]</td>";
         print "<td width='5%'>$selection3[numreplies]</td>";
         print "<td width='5%'>$selection3[views]</td>";
         print "<td width='30%'>$selection3[timepost]<br>Last Post by: <b>$selection3[lastpost]</b></td></tr>";
      }
  print "<tr><td colspan='6' class='catline'><center>Powered by © <A href='http://www.chipmunk-scripts.com'>Chipmunk Board</a></td></tr>";
  print "</table>";
  print "<table border='0' width=90%>";
  print "<tr><td class='regrow'>";
  print "<p align='right'>";
  $order="SELECT COUNT(*) from b_posts,b_users where b_users.userID=b_posts.author and b_posts.threadparent='NADA' and b_posts.postforum='$forumID' order by b_posts.telapsed DESC";
  $order2=mysql_query($order);
  $d=0;
  $f=0;
  $g=1+$d/$numtopicsperpage;
  $order3=mysql_result($order2,0);
  $prev=$start-$numtopicsperpage;
  $next=$start+$numtopicsperpage;
  print " Page: ";
  if($start>=$numtopicsperpage)
  {
    print "<A href='index.php?forumID=$forumID'>First</a>&nbsp&nbsp;&nbsp;";
    print "<A href='index.php?forumID=$forumID&start=$prev'><<</a>&nbsp;";
  }
  while($f<$order3)
   {
      if($f%$numtopicsperpage==0)
       {
        if($f>=$start-3*$numtopicsperpage&&$f<=$start+7*$numtopicsperpage)
         {
           print "<A href='index.php?forumID=$forumID&start=$d'>$g</a> ";     
         }
       }
     $d++;
     $g=1+$d/$numtopicsperpage;
     $f++;
   }
  if($start<=$order3-$numtopicsperpage)
  {
    print "&nbsp;<A href='index.php?forumID=$forumID&start=$next'>>></a>&nbsp;&nbsp;&nbsp;";
    $last=$order3-$numtopicsperpage;
    print "<A href='index.php?forumID=$forumID&start=$last'>Last</a>";
  }
  print "</p></td></tr></table><br><br>";
  if($getuser3[status]>=3)
    {
      print "<center><A href='admin/index.php'>Admin CP</a></center>";
    }
   
 }  

else //looking at main index
{
      $getusertime="SELECT * from b_users where userid='$getuser3[userID]'";
      $getusertime2=mysql_query($getusertime) or die("Could not get user time");
      $getusertime3=mysql_fetch_array($getusertime2);
      $totalposts=0;
      $totaltopics=0;
      print "<table class='maintable' cellspacing='0'>";
      print "<tr class='regrow'><td valign='top'><A href='top.php'>Top 20 Posters</a>-<A href='search.php'>Search Topics</a>-<A href='toprated.php'>Hottest Members</a>";
      if($playrps=="Yes")
      {
        print "-<A href='toprps.php'>Top RPS players</a>";
      }
      print "</td><td valign='top'><p align='right'>";
      if(isset($_SESSION['user']))
      {
        print "Welcome $getuser3[username]-<A href='usercp.php'>User CP</a>-<A href='logout.php'>Logout</a>";
      }
      else
      {
        print "<A href='login.php'>Login</a>-<A href='register.php'>Register</a>";
      }
      print "</td></tr></table><br>";
      if(strlen($getuser3[username])>0)
      {
        if($playrps=="Yes" && $getuser3[rps]==1)
        {
           $string1 = "Your PMs and RPS(Rock Paper Scissors Challenges)"; //display string for title
        }
        else
        {
           $string1 = "Your PMs";
        }
        print "<table class='maintable'><tr class='headline'><td><center>$string1</center></td></tr>";
        print "<tr class='forumrow'><td>";
        $getnewpms="select sum(case when hasread = 0 then 1 else 0 end) as newpms, count(pmID) as allpms from b_pms where receiver ='$getuser3[userID]'";
        $getnewpms2=mysql_query($getnewpms) or die(mysql_error());
        $getnewpms3=mysql_fetch_array($getnewpms2);
        if(!$getnewpms3[newpms])
        {
           $getnewpms3[newpms]=0;
        }
        if($playrps=="Yes" && $getuser3[rps]==1) //if playing RPS
        {  
           $rps1 = "SELECT COUNT(*) AS rpss FROM b_rps where challenged='$getuser3[userID]' and accept='0'"; //Get RPS challenges
           $rps2=mysql_query($rps1) or die("blah");
           $rps3= mysql_result($rps2, 0);     
           print "Your have $getnewpms3[newpms] new PMs in your <A href='pm/pm.php'>Inbox</a> and a total of $getnewpms3[allpms] out of a limit of $maxpms PMs.You have $rps3 challenges for <A href='rpschallenge.php'>Rock-Paper Scissors</a>.";
        }
        else
        {
           print "Your have $newpms new PMs in your <A href='pm/pm.php'>Inbox</a> and a total of $allpms out of a limit of $maxpms PMs.";
        }
       
        print "</td></tr></table><br>";
      }
      //below we select all forums
      $forumselect1="SELECT * from b_forums order by sort ASC";
      $forumselect2=mysql_query($forumselect1) or die(mysql_error());
      print "<table class='maintable' cellspacing='1'>";
      print "<tr class='headline'>";
      print "<td valign='top' colspan='2'>Forum Name</td>";
      print "<td valign='top'>Topics</td>";
      print "<td valign='top'>Posts</td>";
      print "<td valign='top'>Last Post</td></tr>";
      $catselect="SELECT * from b_categories order by catsort ASC";
      $catselect2=mysql_query($catselect) or die("Could not select categories");
      while($catselect3=mysql_fetch_array($catselect2))
      {
        $catID=$catselect3[categoryid];
        print "<tr class='catline'><td colspan='5'>$catselect3[categoryname]</td></tr>";
        
        while($forumselect3=mysql_fetch_array($forumselect2))
        {
          if($forumselect3[parentID]==$catID&&$getuser3[status]>=$forumselect3[permission_min])
          {
            print "<tr class='forumrow'>";
            if($forumselect3[permission_min]=='-1')
            {
              if($forumselect3[lastposttime]>$getusertime3[oldtime])
              {
                print "<td><img src='images/postforum.jpg' border='0'></td>";
              }
              else
              {
                print "<td><img src='images/postforum.gif' border='0'></td>";
              }
            }
            else if($forumselect3[permission_min]=='0')
            {
              if($forumselect3[lastposttime]>$getusertime3[oldtime])
              {
                print "<td><img src='images/members.jpg' border='0'></td>";
              }
              else
              {
                print "<td><img src='images/members.gif' border='0'></td>";
              }
            }
            else if($forumselect3[permission_min]=='1')
            {
              if($forumselect3[lastposttime]>$getusertime3[oldtime])
              {
                print "<td><img src='images/modonly.jpg' border='0'></td>";
              }
              else
              {
                print "<td><img src='images/modonly.gif' border='0'></td>";
              }
            }   
            else if($forumselect3[permission_min]=='2')
            {
              if($forumselect3[lastposttime]>$getusertime3[oldtime])
              {
                print "<td><img src='images/supermodonly.jpg' border='0'></td>";
              }
              else
              {
                print "<td><img src='images/supermodonly.gif' border='0'></td>";
              }
            }  
            else if($forumselect3[permission_min]=='3')
            {
              if($forumselect3[lastposttime]>$getusertime3[oldtime])
              {
                print "<td><img src='images/adminonly.jpg' border='0'></td>";
              }
              else
              {
                print "<td><img src='images/adminonly.gif' border='0'></td>";
              }
            }     
            $totalposts=$totalposts+$forumselect3[numposts];
            $totaltopics=$totaltopics+$forumselect3[numtopics];
            print "<td valign='top'><A href='index.php?forumID=$forumselect3[ID]'><b>$forumselect3[name]</b></a><br>$forumselect3[description]</td>";
            print "<td valign='top'>$forumselect3[numtopics]</td>";
            print "<td valign='top'>$forumselect3[numposts]</td>";
            print "<td valign='top'>$forumselect3[lastpost]<br>Last post by: <b>$forumselect3[lastpostuser]</b></td></tr>";
          }
        }
        if(mysql_num_rows($forumselect2) > 0)
        {
          mysql_data_seek($forumselect2,0);
        }
      }
      print "</table>";
      print "<br><br>";
      include "useronline.php";
   
      print "<table class='maintable' cellspacing='1'>";
      print "<tr class='catline'><td colspan='2'><b>Basic Stats</b></td></tr>";
      print "<tr class='forumrow'><td rowspan='3'><img src='images/stats.gif'></td>";  
      $users1 = "SELECT COUNT(*) AS usercount FROM b_users where username!='Guest'";
      $users2=mysql_query($users1) or die("blah");
      $usercount= mysql_result($users2, 0); 
      print "<td>There are $usercount registered users who have posted a total of $totalposts posts in $totaltopics threads.";
      print"</td></tr>";
      print "</table>";
      print "<table class='maintable'>";
      print "<tr><td class='forumrow'>";
      print "<img src='images/postforum.gif' border='0'>&nbsp;General Access<br><br>";
      print "<img src='images/members.gif' border='0'>&nbsp;Members only<br><br>";
      print "<img src='images/modonly.gif' border='0'>&nbsp;Moderators, Supermoderators, Administrators<br><br>";
      print "<img src='images/supermodonly.gif' border='0'>&nbsp;Supermoderators and Administrators<br><br>";
      print "<img src='images/adminonly.gif' border='0'>&nbsp;Administrators Only<br><br>";
      print "<img src='images/postforum.jpg' border='0'>&nbsp;New Posts since your last visit<br><br>";
      print "<tr><td class='forumrow'>Powered by © <A href='http://www.chipmunk-scripts.com'>Chipmunk Board</a></center></td></tr>";
      print "</td></tr></table>"; 
      print "<br><center>"; 
      if($getuser3[status]>=3)
      {
        print "<A href='admin/index.php'>Admin CP</a></center>";
      }

}  


?>

<br><center><A href='http://www.webhostinggate.com'>Web Hosting reviews</a>-<A href='http://www.thehostplanet.com/'>Web Hosting</a>-<A href='http://www.hosts2002.com/'>Website Hosting</a></center>
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
    
 
<?php
//function for getting ranks
   function getrank($numposts, $thequery)
   {
      while($therank=mysql_fetch_array($thequery))
      {
        if($numposts>=$therank[postsneeded])
        { 
           $rank=$therank[rankname];
        }
      }
      return $rank;
   }
?>

<? //BBCODE function
	//Local copy

	function BBCode($Text)
	    {
        	// Replace any html brackets with HTML Entities to prevent executing HTML or script
            // Don't use strip_tags here because it breaks [url] search by replacing & with amp
     

            // Convert new line chars to html <br /> tags
            $Text = nl2br($Text);

            // Set up the parameters for a URL search string
            $URLSearchString = " a-zA-Z0-9\:&amp;\/\-\?\.\=\_\~\#\'";
            // Set up the parameters for a MAIL search string
            $MAILSearchString = $URLSearchString . " a-zA-Z0-9\.@";

            // Perform URL Search
            $Text = preg_replace("(\[url\]([$URLSearchString]*)\[/url\])", '<a href="$1">$1</a>', $Text);
            $Text = preg_replace("(\[url\=([$URLSearchString]*)\]([$URLSearchString]*)\[/url\])", '<a href="$1" target="_blank">$2</a>', $Text);
            $Text = preg_replace("(\[URL\=([$URLSearchString]*)\]([$URLSearchString]*)\[/URL\])", '<a href="$1" target="_blank">$2</a>', $Text);
            // Perform MAIL Search
            $Text = preg_replace("(\[mail\]([$MAILSearchString]*)\[/mail\])", '<a href="mailto:$1">$1</a>', $Text);
            $Text = preg_replace("/\[mail\=([$MAILSearchString]*)\](.+?)\[\/mail\]/", '<a href="mailto:$1">$2</a>', $Text);

            // Check for bold text
            $Text = preg_replace("(\[b\](.+?)\[\/b])is",'<b>$1</b>',$Text);

            // Check for Italics text
            $Text = preg_replace("(\[i\](.+?)\[\/i\])is",'<I>$1</I>',$Text);

            // Check for Underline text
            $Text = preg_replace("(\[u\](.+?)\[\/u\])is",'<u>$1</u>',$Text);

            // Check for strike-through text
            $Text = preg_replace("(\[s\](.+?)\[\/s\])is",'<span class="strikethrough">$1</span>',$Text);

            // Check for over-line text
            $Text = preg_replace("(\[o\](.+?)\[\/o\])is",'<span class="overline">$1</span>',$Text);

            // Check for colored text
            $Text = preg_replace("(\[color=(.+?)\](.+?)\[\/color\])is","<span style=\"color: $1\">$2</span>",$Text);

            // Check for sized text
            $Text = preg_replace("(\[size=(.+?)\](.+?)\[\/size\])is","<span style=\"font-size: $1px\">$2</span>",$Text);

            // Check for list text
            $Text = preg_replace("/\[list\](.+?)\[\/list\]/is", '<ul class="listbullet">$1</ul>' ,$Text);
            $Text = preg_replace("/\[list=1\](.+?)\[\/list\]/is", '<ul class="listdecimal">$1</ul>' ,$Text);
            $Text = preg_replace("/\[list=i\](.+?)\[\/list\]/s", '<ul class="listlowerroman">$1</ul>' ,$Text);
            $Text = preg_replace("/\[list=I\](.+?)\[\/list\]/s", '<ul class="listupperroman">$1</ul>' ,$Text);
            $Text = preg_replace("/\[list=a\](.+?)\[\/list\]/s", '<ul class="listloweralpha">$1</ul>' ,$Text);
            $Text = preg_replace("/\[list=A\](.+?)\[\/list\]/s", '<ul class="listupperalpha">$1</ul>' ,$Text);
            $Text = str_replace("[*]", "<li>", $Text);
             $Text = preg_replace("(\[quote\](.+?)\[\/quote])is",'<center><table class="quotecode"><tr row="forumrow"><td>Quote:<br>$1</td></tr></table></center>',$Text);
            $Text = preg_replace("(\[code\](.+?)\[\/code])is",'<center><table class="quotecode"><tr row="forumrow"><td>Code:<br>$1</td></tr></table></center>',$Text);

            // Check for font change text
            $Text = preg_replace("(\[font=(.+?)\](.+?)\[\/font\])","<span style=\"font-family: $1;\">$2</span>",$Text);

    

            // Images
            // [img]pathtoimage[/img]
            $Text = preg_replace("/\[IMG\](.+?)\[\/IMG\]/", '<img src="$1">', $Text);
            $Text = preg_replace("/\[img\](.+?)\[\/img\]/", '<img src="$1">', $Text);
            // [img=widthxheight]image source[/img]
            $Text = preg_replace("/\[img\=([0-9]*)x([0-9]*)\](.+?)\[\/img\]/", '<img src="$3" height="$2" width="$1">', $Text);

	        return $Text;
		}
?>




<SCRIPT LANGUAGE="Javascript">
		//<!--
		// pop a windoid (Pictures)
		function popWin(url, w, h) 
		{
		 var madURL = url;
		 var x, y, winStr;
		 x=0; y=0;
		 self.name="opener";
		 winStr = "height="+h+",width="+w+",screenX="+x+",left="+x+",screenY="+y+",top="+y+",channelmode=0,dependent=0,directories=0,fullscreen=0,location=0,menubar=0,resizable=1,scrollbars=0,status=0,toolbar=0";
		 lilBaby = window.open(madURL, "_blank", winStr);
		}
		//--> </script>
</center>   

</body>
   
     








