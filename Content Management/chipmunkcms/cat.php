<?php
session_start();
?>
<head><title>Free PHP Scripts -- Guestbook, directory, polls, and more</title>
<meta name="keywords" content="php, directory, scripts, polls, guestbooks, message boards, forums, web programming, free scripts">
<meta name="description" content="A large collection of php scripts and resources such as guestbooks, forums, polls, and other php scripts and tutorials for webmasters">
</head>



<link rel="stylesheet" href="style.css" type="text/css">
<center>

<?
include "headad.php";

?>
</center>
<center>
<table border='0' width='90%' cellspacing='20'>
<tr><td valign='top' width='25%'>
<?
include 'left.php';
if(isset($_SESSION['user']))
{
  $user=$_SESSION['user'];
  $getuser="SELECT * from b_users where username='$user'";
  $getuser2=mysql_query($getuser) or die("Could not get user info");
  $getuser3=mysql_fetch_array($getuser2);
  $thedate=date("U");
  $checktime=$thedate-200;
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
  if(strlen($getuser3[username])>0)
  {
    $_SESSION['user']=$getuser3[username];
    if($getuser3[tsgone]<$checktime)
    {
      $updatetime="Update b_users set tsgone='$thedate', oldtime='$getuser3[tsgone]' where userID='$getuser3[userID]'";
      mysql_query($updatetime) or die("Could not update time");
    }
  }
}
?>
</td>
<td valign='top' width='50%'>
<?php
if(!isset($_GET['start']))
{
  $start=0;
}
else
{
  $start=$_GET['start'];
}
?>
<?
print "<table class='maintable'><tr class='headline'><td><b><font color='white'><center>Webmaster Resources</center></font></b></td></tr>";
print "<tr class='forumrow'><td><center>";
?>
<script type="text/javascript"><!--
google_ad_client = "pub-8147412025236663";
google_ad_width = 468;
google_ad_height = 60;
google_ad_format = "468x60_as";
google_ad_channel ="1558239984";
google_color_border = "F2F2F2";
google_color_bg = "F2F2F2";
google_color_link = "F2F2F2";
google_color_url = "008000";
google_color_text = "000000";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</td></tr></table><br><br>
<?
$numentries=15;
$getarticles="SELECT * FROM b_articles AS a, b_users AS b LEFT JOIN b_posts c ON (c.articleidentifier=a.artID) WHERE b.userID=a.authorID and a.category='$ID' and a.validates='1' order by thetime DESC limit $start, $numentries ";
$getarticles2=mysql_query($getarticles) or die("BLAH");
while($getarticles3=mysql_fetch_array($getarticles2))
{
  $$getarticles3[titles]=strip_tags($getarticles3[titles]);
  $getarticles3[username]=strip_tags($getarticles3[username]);
  $getarticles3[shortdes]=strip_tags($getarticles3[shortdes]);
  $getarticles3[shortdes]=nl2br($getarticles3[shortdes]);
  $getarticles3[shortdes]=BBCode($getarticles3[shortdes]);
  
  print "<table class='maintable'><tr class='headline'><td><b><font color='white'><center>$getarticles3[titles] Posted by $getarticles3[username] at $getarticles3[thedate]</center></font></b></td></tr>";
  print "<tr class='forumrow'><td>$getarticles3[shortdes]<br><br>";
  print "<A href='more.php?ID=$getarticles3[artID]'>Full Story</a>|";
  if($getuser3[status]>=3)
  {
     print "<A href='board/admin/editarticle.php?ID=$getarticles3[artID]'>Edit</a>|<A href='board/admin/deletearticle.php?ID=$getarticles3[artID]'>Delete Article</a>|";    
  }
  if($getarticles3[articleidentifier]!=0)
  {
    $rep=$getarticles3[numreplies]+1;
    print "<A href='board/index.php?forumID=$getarticles3[postforum]&ID=$getarticles3[ID]'>Discuss this Article($rep posts)</a>";
  }
  else
  {
    print "<A href='board/articletopic.php?forumID=$getarticles3[forumtopic]&artID=$getarticles3[artID]'>Start a discussion on this Article</a>";
  }
  
  print "</td></tr></table><br><br>";
}
$ID=$_GET['ID'];
$order="SELECT * FROM b_articles AS a, b_users AS b LEFT JOIN b_posts c ON (c.articleidentifier=a.artID) WHERE b.userID=a.authorID and a.category='$ID' and a.validates='1' order by thetime DESC";
$order2=mysql_query($order) or die(mysql_error());
$d=0;
$f=0;
$g=1+$d/$numentries;


$num=mysql_num_rows($order2);

print "<font color='#$fontcolor'>Page:</font> ";
$prev=$start-$numentries;
$next=$start+$numentries;
if($start>=$numentries)
  {
    print "<A href='index.php?start=$prev'><<</a>&nbsp;";
  }
while($order3=mysql_fetch_array($order2))
{
 
 if($f>=$start-3*$numentries&&$f<=$start+7*$numentries)
 {
 
 if($f%$numentries==0)
  {
    

    print "<A href='index.php?start=$d'>$g</a> ";
    
  }
 }
$d=$d+1;
$g=1+$d/$numentries;
$f++;

}

if($start<=$num-$numentries)
  {
    print "<A href='index.php?start=$next'>>></a>&nbsp;";
  }


print "<center>";





?>






</font>



</td>
<td valign='top' width='25%'>
<?
include 'right.php';
?>
</td></tr></table>
<br><br>
<center>
Chipmunk php scripts is your one source for all free php scripts, such as guestbooks, link directories, forums, topsites, and more scripts.<br><br>
</center>
<center>
<?
include 'footer.php';
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
            $URLSearchString = " a-zA-Z0-9\:\&\/\-\?\.\=\_\~\#\'";
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
</center>