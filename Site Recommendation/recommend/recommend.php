<?php  
/***********************************************************/ 
/*  This script was created by Lee Penney.  Feel free to   */
/*  amend it and use it wherever you want, but please      */
/*  leave this credit intact.                              */
/*                                                         */
/*  My personal site is at www.thedigeratipeninsula.org.uk */
/*  and you can find more free scripts by me at            */
/*  www.viewfinderdesign.co.uk                             */
/*                                                         */
/*  To use this script, just change the site name and site */
/*  address details below, then put the file in a          */
/*  directory called 'recommend'.  Then put everything     */
/*  between the <script> tags in the enclosed index.html   */
/*  file in the html of the page you are using where you   */
/*  want the link to appear.                               */
/***********************************************************/ 

/* Change these details to suit your needs */

/* Enter the company name or site name here */
$sitename = "Viewfinder Design"; 

/* Enter the site web address */
$siteaddress = "http://www.viewfinderdesign.co.uk/";

?>
<html>
<head>
<title>Recommend to a Friend Script</title>
<!--<link href="default.css" rel="stylesheet" type="text/css" /> Uncomment this line if you have a CSS file you wish to use -->
<style type="text/css">
/* Some example styles, amend or remove as needed */
body { font-family: verdana, helvetica, sans-serif; font-size: 75%; }
label { float: left; clear: both; }
input { float: left; clear: both; font-family: verdana, helvetica, sans-serif; }
textarea { float: left; clear: both; width: 200px; font-family: verdana, helvetica, sans-serif; font-size: 0.98em; }
#yemail, #yname, #femail, #fname, #comments { width: 200px; border: 1px solid #bbb; background: #eee; }
#submit { margin-top: 1em; };
</style>
</head>

<body>
<?php

/* Do not edit below this line unless you know what you're doing */

$yname = $_POST['yname'];
$yemail = $_POST['yemail'];
$femail = $_POST['femail'];
$comments = $_POST['comments'];
$pageurl = $_GET['loc'];

function outputform() { 

$pageurl = $_GET['loc']; ?>

<form method="post" action="recommend.php?loc=<?php echo "$pageurl"; ?>"> 

<label for="yname">Your Name:</label> 
<input type="text" name="yname" id="yname" value="<?php echo "$_POST[yname]"; ?>" />
 
<label for="yemail">Your Email:</label>  
<input type="text" name="yemail" id="yemail" value="<?php echo "$_POST[yemail]"; ?>" />

<label for="femail">Friend's Email:</label> 
<input type="text" name="femail" id="femail" value="<?php echo "$_POST[femail]"; ?>" />

<label for="comments">Comments:</label>  
<textarea name="comments" rows="3" id="comments"><?php echo "$_POST[comments]"; ?></textarea>

<input type="submit" id="submit" name="submit" value="Send">

</form> 

<?php
}

if ($_POST['submit']) { 

	if (($yname=="") || ($femail=="")|| ($yemail==""))  {
print "<p><strong>Error:</strong> Please complete all of the required form fields.</p>";
outputform();
	} 
	else {
	  if (!eregi("^[a-z0-9]+([-_\.]?[a-z0-9])+@[a-z0-9]+([-_\.]?[a-z0-9])+\.[a-z]{2,4}", $yemail)) {
      print("<p><strong>Error:</strong> your email address is not in a valid format.</p>");
		  outputform();
		  exit;
    }
	  if (!eregi("^[a-z0-9]+([-_\.]?[a-z0-9])+@[a-z0-9]+([-_\.]?[a-z0-9])+\.[a-z]{2,4}", $femail)) {
      print("<p><strong>Error:</strong> your friend's email address is not in a valid format.</p>");
		  outputform();
		  exit;
    }
	$comments = stripslashes($comments);
	mail("$femail","$yname suggests that you stop by $sitename","\n\n$yname saw this on $sitename and thought you would should see it:\n\nURL: $pageurl \n\n Additional Comments: \n------------------------------------ \n$comments \n------------------------------------\n\nThis message was sent from $sitename ($siteaddress)","From:$yemail");
  echo "<p>Your recommendation to <strong>$femail</strong> has been sent.</p><p><a href=\"javascript:window.close()\">Close Window</a></p>";
}
}
else {
?> 
<p>Tell someone about <?php echo "$sitename"; ?>.</p>
<?php 
outputform();
} 
?>
</body>
</html>