<?php
include 'connect.php';
session_start();

?>

<title>Chipmunk board</title>



<center>

<?php
include "title.php";
include "admin/var.php";
?>
</center>
<br><br>


<center>

<link rel="stylesheet" href="style.css" type="text/css">


<?php
$forumID=$_GET['forumID'];
$s=$_SERVER["REMOTE_ADDR"];
$checkip="SELECT * from b_banip where ip='$s'";
$checkip2=mysql_query($checkip) or die("Could not get ips");
$checkip3=mysql_fetch_array($checkip2);
if($checkip3)
{
   die("<table class='maintable'><tr class='headline'><td><center>New Topic</center></td></tr><tr class='forumrow'><td><center>Your IP was banned from posting</center></td></tr></table>");
}

if (isset($_SESSION['user'])||$guestposting=="Yes"||$guestposting=="yes")
{
$user=$_SESSION['user'];
$getid="SELECT * from b_users where username='$user'";
$getid2=mysql_query($getid) or die("could not get user");
$getid3=mysql_fetch_array($getid2);
$getforuminfo="SELECT * from b_forums where ID='$forumID'";
$getforuminfo2=mysql_query($getforuminfo) or die("COuld not get forum info");
$getforuminfo3=mysql_fetch_array($getforuminfo2);
if(!$_SESSION['user'])
   {
         $getid3[status]=-1;
   }
$thetime=date("U");
if($getid3[lastposttime]>$thetime-30)
{
  die("<table class='maintable'><tr class='headline'><td><center>New Topic</center></td></tr><tr class='forumrow'><td><center>You can only post once every 30 seconds</center></td></tr></table>");
}
if($getforuminfo3[permission_post]>$getid3[status])
{
   die("<table class='maintable'><tr class='headline'><td><center>New Topic</center></td></tr><tr class='forumrow'><td><center>You Do not have permission to post in this forum</center></td></tr></table>");
}
if($getid3[banned]=="Yes")
{
  die("<table class='maintable'><tr class='headline'><td><center>New Topic</center></td></tr><tr class='forumrow'><td><center>You have been banned from posting</center></td></tr></table>");
}   
if(isset($_POST['submitpost']))
 {
    if(!$_POST['title'] || !$_POST['post'] || !$_GET['forumID'])
      {
        print "<table class='maintable'>";
        print "<tr class='headline'><td><center>New Topic</center></td></tr>";
        print "<tr class='forumrow'><td><center>";
        print "One of the required fields was not filled in, please go back and try again";
        print "</td></tr></table>";
      }
    else
      {
       $name=$_POST['name'];
       $title=$_POST['title'];
       $post=$_POST['post'];
       $day=date("D M d, Y H:i:s");
       $timegone=date("U") ;
       if($_POST['nosmiley'])
       {
         $nosmiley=1;
       }
       else
       {
         $nosmiley=0;
       }
       if(!$_SESSION['user'])
       {
         $user="Guest";     
       }
       $name=strip_tags($name);
       $title=strip_tags($title);       
       $post=strip_tags($post);              
       $posting="INSERT INTO b_posts (author, title, post,timepost, telapsed, postforum,lastpost,nosmilies,ipaddress) values ('$name', '$title', '$post', '$day', '$timegone','$forumID','$user','$nosmiley','$s')";
       mysql_query($posting) or die("could not post");
       if(isset($_SESSION['user']))
       {
         $today=date("U");
         $updates="update b_forums set numtopics=numtopics+1, numposts=numposts+1, lastpost='$day', lastpostuser='$user',lastposttime='$today' where ID='$forumID'";
         mysql_query($updates);
         $updateuser="update b_users set Posts=Posts+1, lastposttime='$today' where username='$user'";
         mysql_query($updateuser) or die("COuld not update numposts");
       }
       else
       {
         $today=date("U");
         $updates="update b_forums set numtopics=numtopics+1, numposts=numposts+1, lastpost='$day', lastpostuser='Guest',lastposttime='$today' where ID='$forumID'";
         mysql_query($updates) or die("Could not update forums");
       }
       print "<table class='maintable'>";
       print "<tr class='headline'><td><center>New Topic</center></td></tr>";
       print "<tr class='forumrow'><td><center>";
       print "Thanks for posting... Redirecting to forum index <META HTTP-EQUIV = 'Refresh' Content = '2; URL =index.php?forumID=$forumID'>";
       print "</td></tr></table>";
      }
  }


 else
  {
    print "<table class='maintable'>";
    print "<tr class='headline'><td><center>New Topic</center></td></tr>";
    print "<tr class='forumrow'><td><center>";
    print "<table border='0'><tr class='forumrow'><td>";
    print "<form action='newtopic.php?forumID=$forumID' method='post' name='form'>";
    if(isset($_SESSION['user']))
    {
      print "<input type='hidden' name='name' value=$getid3[userID]><br>";
      print "<b>Name:</b> $user<br>"; 
    }
    else
    { 
      $getguest="SELECT * FROM b_users WHERE username='Guest'";
      $getguest2=mysql_query($getguest);
      $getguest3=mysql_fetch_array($getguest2);
      print "<input type='hidden' name='name' value='$getguest3[userID]'><br>";
      print "<b>Name:</b> Guest<br>";
    }    
    print "<b>Topic:</b><br>";
    print "<input type='text' name='title' length='15'><br><br>";
    print "<b>Message:</b><br><br>";
    print "<a onClick=\"addSmiley('[b][/b]')\"><img src='images/bold.gif'></a> ";
    print "<a onClick=\"addSmiley('[i][/i]')\"><img src='images/italic.gif'></a> ";
    print "<a onClick=\"addSmiley('[u][/u]')\"><img src='images/underline.gif'></a> ";
    print "<a onClick=\"addSmiley('[img][/img]')\"><img src='images/img.gif'></a> ";
    print "<a onClick=\"addSmiley('[url][/url]')\"><img src='images/url.gif'></a> ";
    print "<a onClick=\"addSmiley('[mail][/mail]')\"><img src='images/mail.gif'></a><br><br>";
    print "<textarea rows='6' name='post' cols='45'></textarea><br><br>";
    print "<input type='checkbox' name='nosmiley'>&nbsp;Disable Smilies<br><br>";
    print "<input type='submit' name='submitpost' value='Post'>";
    print "</form><br><br>";
    print "Clickable Smilies<br>";
    print "<a onClick=\"addSmiley(':)')\"><img src='images/smile.gif'></a> ";
    print "<a onClick=\"addSmiley(':(')\"><img src='images/sad.gif'></a> ";
    print "<a onClick=\"addSmiley(';)')\"><img src='images/wink.gif'></a> ";
    print "<a onClick=\"addSmiley(';smirk')\"><img src='images/smirk.gif'></a> ";	
    print "<a onClick=\"addSmiley(':blush')\"><img src='images/blush.gif'></a> ";
    print "<a onClick=\"addSmiley(':angry')\"><img src='images/angry.gif'></a> ";
    print "<a onClick=\"addSmiley(':shocked')\"><img src='images/shocked.gif'></a> ";
    print "<a onClick=\"addSmiley(':cool')\"><img src='images/cool.gif'></a> ";
    print "<a onClick=\"addSmiley(':ninja')\"><img src='images/ninja.gif'></a> ";
    print "<a onClick=\"addSmiley('(heart)')\"><img src='images/heart.gif'></a> ";
    print "<a onClick=\"addSmiley('(!)')\"><img src='images/exclamation.gif'></a> ";
    print "<a onClick=\"addSmiley('(?)')\"><img src='images/question.gif'></a><br>";
    print "<a onclick=\"addSmiley(':{blink}')\"><img src='images/winking.gif'></a>";
    print "<A onclick=\"addSmiley('{clover}')\"><img src='images/clover.gif'></a>";
    print "<a onclick=\"addSmiley(':[glasses]')\"><img src='images/glasses.gif'></a>";
    print "<a onclick=\"addSmiley(':[barf]')\"><img src='images/barf.gif'></a>";
    print "<a onclick=\"addSmiley(':[reallymad]')\"><img src='images/mad.gif'></a>";
    print "<script language=\"JavaScript\" type=\"text/javascript\">\n";
    print "function addSmiley(textToAdd)\n";
    print "{\n";
    print "document.form.post.value += textToAdd;";
    print "document.form.post.focus();\n";
    print "}\n";
    print "</script>\n";
    print "</td></tr></table></center>";
    print "</td></tr></table>";
 
 }

}

else
{
  print "<table class='maintable'>";
  print "<tr class='headline'><td><center>New Topic</center></td></tr>";
  print "<tr class='forumrow'><td><center>";
  print "Not logged in, please <A href='login.php'>Go here</a> to log in";
  print "</td></tr></table>";
}
 
?>



</center>   









<br><br>
<center>
