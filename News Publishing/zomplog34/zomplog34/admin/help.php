<?
include_once("functions.php");
include('config.php');
include("session.php");
include("header.php");

$user = loadUser($_SESSION['login'],$link,$table_users);

$moblog = loadMoblogSettings($link,$table_moblog);

function helpIneedsomebody($id) {
global $user, $moblog;
switch($id){
	
	case 1:
	$message = "<div class='title'>What is a moblog?</div>The moblog system of Zomplog allows you to update your weblog through email or even your mobile phone. 
	Great if you get an idea on the road, or want to post pictures during your journey to, let's say, Russia! 
	From now on Zomplog is with you everywhere you go!
	<br /><br />
	<div class='title'>How does it work?</div>
	In order for the system to filter which mails are meant for posting on your weblog, and which aren't, you should enclose the body text of your 
	email in &quot;#&quot;-signs. Other emails will just be ignored by the system. If you attach an image to your email, it will also be 
	posted on your blog! Needless to say, the title field of your email will also be the title field of your blog post. It works with 
	emails sent through your SMS/MMS-Gateway and with all normal email messages.
	<br /><br />
	<div class='title'>Updating your weblog through email</div>
	Ok, by now you have a slight idea of how the system works. Guess you want to start doing it now! Here's a step-by-step approach:
	<br />
	1. Your site administrator has opened an email-account to which you can send your weblog posts. This email-adress is <b>$moblog[email]</b>.<br />
	2. For security reasons there's only one email-adres you can send messages from. This is the email-adress you filled out in your personal profile. <b>$user[email]</b><br />
	3. All you need to do is send an email from  your email-adress <b>$user[email]</b> to <b>$moblog[email]</b> and make sure the body-text is enclosed in '#'-signs. If you attach an image, it will 
	automatically turn up in your post.<br />
	4. Once someone visits your site, the script checks <b>$moblog[email]</b> for new emails. If there is one, it gets added to the site.";
	break;
	
	case 2:
	// for future zomplog release
	$message = "Nothing found";
	break;
	}
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="text">
  <tr>
    <td>
	<?
echo "$message";		
	?>
	</td>
  </tr>
  </table>
  <br />
  <?
}

helpIneedsomebody($_GET[id]);


include("footer.php");
?>