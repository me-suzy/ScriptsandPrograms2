<?php
/* Written by Gerben Schmidt, http://scripts.zomp.nl */

ob_start();
include_once("functions.php");
include('config.php');
include("session.php");
include("header.php");

if(!$_SESSION["loggedIn"]){
?>

You are not allowed to view this page, please log in first.
<?
}
else
{

if($_POST['Submit']){

if(!$_POST[email])
{
$messages[]="please provide an email adress";
}

if(!$_POST[server])
{
$messages[]="please provide a mailserver URL";
}

if(!$_POST[user])
{
$messages[]="please provide a mail username";
}

if(!$_POST[password])
{
$messages[]="please provide a mail password";
}

if(!$_POST[shared])
{
$messages[]="please select true or false from the 'shared' dropdown menu";
}


if(!empty($messages)){
	displayErrors($messages);
}

if(empty($messages)) {
		

	$query="UPDATE $table_moblog SET email = '$_POST[email]', server = '$_POST[server]', user = '$_POST[user]', password = '$_POST[password]', shared = '$_POST[shared]', use_moblog = '$_POST[use_moblog]' WHERE id = '1'";
	$result=mysql_query($query, $link) or die("Died inserting data into db.  Error returned if any: ".mysql_error());

header("Location: $_SERVER[php_self]?message=5");
ob_end_flush();

	}


}

if($_GET[message]){ 
displayMessage($_GET[message]);
  }


if(!$_POST['Submit']){

$moblog = loadMoblogSettings($link,$table_moblog);

?>


<form name="myform" method="post" enctype="multipart/form-data">
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="72%" valign="top"><table width="91%"  border="0" cellspacing="0" cellpadding="0" class="text">
        <tr>
          <td colspan="2"><h1><? echo "$lang_moblog_settings"; ?></h1></td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><span class="title">What is a moblog? </span></td>
          </tr>
        <tr>
          <td colspan="2">The moblog system of Zomplog allows you to update your weblog through email or even your mobile phone. Great if you get an idea on the road, or want to post pictures during your trip to, let's say, Russia! From now on Zomplog is with you everywhere you go!</td>
        </tr>
        <tr>
          <td width="27%">&nbsp;</td>
          <td width="73%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><span class="title">How does it work? </span></td>
          </tr>
        <tr>
          <td colspan="2"><p>In order for the system to filter which mails are meant for posting on your weblog, and which aren't, you should enclose the body text of your email in &quot;#&quot;-signs. Other emails will just be ignored by the system. If you attach an image to your email, it will also be posted on your blog! Needless to say, the title field of your email will also be the title field of your blog post. It works with emails sent through your SMS/MMS-Gateway and with all normal email messages.</p>
              <p>Users of your Zomplog configuration can use the email-adress they provided in their user-profile for sending emails from. Other emails are rejected. More information can be found <a href="help.php?id=1">here</a>. </p></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><span class="title">Set up a moblog email-adress</span></td>
        </tr>
        <tr>
          <td colspan="2">You, as an administrator, will need an email adress that the script can check for incoming mails. This will be the adress you, and other users of the system, can send their blog posts to. This can be your normal email-account, because the moblog system filters out all messages that aren't meant for posting.</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><span class="title">Incoming mail settings</span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>email adress </td>
          <td><input name="email" type="text" id="email" value="<? echo "$moblog[email]"; ?>"></td>
        </tr>
        <tr>
          <td valign="top">mail server </td>
          <td><input name="server" type="text" id="server" value="<? echo "$moblog[server]"; ?>">
      e.g. 'localhost' or 'pop.mailserver.com' </td>
        </tr>
        <tr>
          <td>mail username </td>
          <td><input name="user" type="text" id="user" value="<? echo "$moblog[user]"; ?>">
      this is usually the part before '@' </td>
        </tr>
        <tr>
          <td>mail password </td>
          <td><input name="password" type="text" id="password" value="<? echo "$moblog[password]"; ?>">
      your mail password </td>
        </tr>
        <tr>
          <td valign="top">shared email adress?</td>
          <td><input name="shared" type="text" id="shared" value="<? echo "$moblog[shared]"; ?>">
      be careful with this setting!</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>if you use the above email-account for other purposes than moblogging, set to &quot;TRUE&quot;, if the email-adress is dedicated to moblogging, set to &quot;FALSE&quot; </td>
        </tr>
        <tr>
          <td><span class="title">Activate Moblog </span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>moblog on </td>
          <td><? if($moblog[use_moblog]){
	?>
              <input name="use_moblog" type="checkbox" id="use_moblog" value="1" checked>
              <?
	}
	else
	{
	?>
              <input name="use_moblog" type="checkbox" id="use_moblog" value="1">
              <?
	}
	?></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><input type="submit" name="Submit" value="Submit"></td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
      <td width="28%" valign="top"><?php include('menu.php'); ?>&nbsp;</td>
    </tr>
  </table>
</form>


<?
}
}
include('footer.php');
?>