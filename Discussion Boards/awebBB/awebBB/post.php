<?
session_start();
// This forum was developed by Adam M. B. from aWeb Labs
// Visit us at http://www.labs.aweb.com.au
// for forum problems, bugs, or ideas email yougotmail@gmail.com
// thanks for trying out or using this forum
// aWebBB version 1.2 released under the GNU GPL
// connect to database and pull up info
include "config.php";
$user123=$_POST['Username'];
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
//Get the data
$query = "SELECT id, username, password FROM users WHERE username='$user123'"; 
 
$result = mysql_query($query); 
/* Here we fetch the result as an array */ 
while($r=mysql_fetch_array($result)) 
{ 
/* This bit sets our data from each row as variables, to make it easier to display */ 
$id=$r["id"]; 
$_Username=$r["username"]; 
$_Password=$r["password"]; 

// If the form was submitted
if ($_POST['Submitted'] == "True") {

    // If the username and password match up, then continue...
    if ($_POST['Username'] == $_Username && $_POST['Password'] == $_Password) {

        // Username and password matched, set them as logged in and set the
        // Username to a session variable.
        $_SESSION['Logged_In'] = "True";
        $_SESSION['Username'] = $_Username;
    }
}
} 
mysql_close($db); 
// If they are NOT logged in then show the form to login...
if ($_SESSION['Logged_In'] != "True") {

    echo "<META HTTP-EQUIV=\"refresh\" content=\"0; URL=login.php?page=" . $_SERVER['PHP_SELF'] . "\">";

}
else
{
    
include("header.php"); 

include "config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "SELECT sig, avatar FROM users WHERE username = '$_SESSION[Username]'"; 
$result = mysql_query($query); 
while($r=mysql_fetch_array($result)) 
{ 
$sig=$r["sig"]; 
$avatar=$r["avatar"]; 
?>
<script type="text/javascript">
<!--
	function insertext(text){
	document.post.fpost.value+=" "+ text;
        document.post.fpost.focus();
	}

//-->
	</script>
<style type='text/css'>
<!--
.bordercontrol {padding: 0px; border-right: 1px solid silver; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;}
A.post:hover, A.post:active {background-color: #DDDDDD}
A.post:hover .bordercontrol, A.post:active .bordercontrol {background-color: silver; padding: 0px; border:1px; border-thickness: 1px; border-color: grey; border-style: solid}
-->
</style>
<div class="side-headline"><b>New Forum Thread:</b></div><br>
<div align="center">
<form name="post" method="post" action="post.php?a=post"> 
<input type="hidden" name="sig" value="<?=$sig;?>"> 
<input type="hidden" name="avatar" value="<?=$avatar;?>"> 
<div class="grey-box">
Thread Name: <input type="text" name="tname" size="30" max="10"></div>
<div class="grey-box">Select a Category: 
<select name="category" id="category">
<?php 
if ($_GET['c'] != "") {
$catthing = " WHERE category != '$_GET[c]'";
$cit=$_GET['c'];
echo "<option value=\"$cit\" selected>$cit</option>";
} else {
}
//connection to database
include "config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "SELECT category FROM fcat$catthing"; 
$result = mysql_query($query); 
while($r=mysql_fetch_array($result)) 
{ 
$cname=$r["category"]; 
echo "<option value=\"$cname\">$cname</option>"; 
} 
mysql_close($db); ?>
 </select>
</div>
<div class="grey-box"><a class="post" href="javascript:insertext(':)')"><img class="bordercontrol" alt="smile" src="smilies/smile.gif" border="0" vspace="1" width="18" height="18"></a><a class="post" href="javascript:insertext(';)')"><img class="bordercontrol" alt="wink" src="smilies/wink.gif" border="0" vspace="1" width="18" height="18"></a><a class="post" href="javascript:insertext(':-p')"><img class="bordercontrol" alt="tongue" src="smilies/tongue.gif" border="0" vspace="1" width="18" height="18"></a><a class="post" href="javascript:insertext('>:o')"><img class="bordercontrol" alt="angry" src="smilies/angry.gif" border="0" vspace="1" width="18" height="18"></a><a class="post" href="javascript:insertext(':(')"><img class="bordercontrol" alt="sad" src="smilies/sad.gif" border="0" vspace="1" width="18" height="18"></a><a class="post" href="javascript:insertext(':-D')"><img class="bordercontrol" alt="laughing" src="smilies/laughing.gif" border="0" vspace="1" width="18" height="18"></a><a class="post" href="javascript:insertext('<b> </b>')" title="Bold"><img class="bordercontrol" src="format_edit/ed_format_bold.gif" border="0" width="18" height="18" vspace="1"></a><a class="post" href="javascript:insertext('<i> </i>')" title="Italics"><img class="bordercontrol" src="format_edit/ed_format_italic.gif" border="0" width="18" height="18" vspace="1"></a><a class="post" href="javascript:insertext('<s> </s>')" title="Strikethrough"><img class="bordercontrol" src="format_edit/ed_format_strike.gif" border="0" width="18" height="18" vspace="1"></a><a class="post" href="javascript:insertext('<u> </u>')" title="Underline"><img class="bordercontrol" src="format_edit/ed_format_underline.gif" border="0" vspace="1" width="18" height="18"></a><a class="post" href="javascript:insertext('<a href=www.website.com> linked text </a>')" title="Insert Link"><img class="bordercontrol" src="format_edit/ed_link.gif" border="0" vspace="1" width="18" height="18"></a><a class="post" href="javascript:insertext('<br>')" title="New Line"><img class="bordercontrol" src="format_edit/ed_left_to_right.gif" border="0" vspace="1" width="18" height="18"></a><a class="post" href="#" onClick="document.post.reset()"><img class="bordercontrol" src="format_edit/ed_delete.gif" border="0" vspace="1" width="18" height="18" title="Erase all"></a>
</div>
<div class="grey-box">
Thread Text: 
      <br> 
      <textarea name="fpost" cols="45" rows="7"></textarea></div>
<div class="grey-box"><div align="center">
<input type="submit" name="Submit" value="Post New Thread">
</form></div></div></div>
<? 
} 


if ($_GET['a'] == "post") {
$time1=date("H:i:s");
$rand=rand(1000000, 9999999);
include "config.php"; // As you can see we connected to the database with config
$db = mysql_connect($db_host, $db_user, $db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "INSERT INTO flist(tid, categories, tname, poster, date) 
VALUES('$rand','$_POST[category]','$_POST[tname]','$_SESSION[Username]', now())"; 
mysql_query($query); 
echo "Thread ";
mysql_close($db); 
include "config.php"; // As you can see we connected to the database with config
$db = mysql_connect($db_host, $db_user, $db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "INSERT INTO forum(tid, categories, tname, poster, fpost, sig, avatar, time, date) 
VALUES('$rand','$_POST[category]','$_POST[tname]','$_SESSION[Username]','$_POST[fpost]','$_POST[sig]','$_POST[avatar]','$time1', now())"; 
mysql_query($query); 
echo "Submitted";
echo '<meta http-equiv="refresh" content="0;url=index.php">'; 
mysql_close($db); 
} else { }
include("footer.php"); 

// If they want to logout then
if ($_GET['mode'] == "logout") {
    // Start the session
    session_start();

    // Put all the session variables into an array
    $_SESSION = array();

    // and finally remove all the session variables
    session_destroy();

    // Redirect to show results..
    echo "<META HTTP-EQUIV=\"refresh\" content=\"1; URL=" . $_SERVER['PHP_SELF'] . "\">";
}
} 

?>

