<?php
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

} else {
include "header.php";
?>
<div class="main2-box">

<div class="side-headline"><b>Edit Account Details:</b></div>

<br>

<?
$a=$_GET['a'];
if ($a == "") {
include "config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
//Get the data
$query = "SELECT id, username, emailadd, fullname, country, date, sig, avatar FROM users WHERE username='$_SESSION[Username]'"; 
 
$result = mysql_query($query); 
/* Here we fetch the result as an array */ 
while($r=mysql_fetch_array($result)) 
{ 
/* This bit sets our data from each row as variables, to make it easier to display */ 
$id=$r["id"]; 
$username=$r["username"]; 
$emailadd=$r["emailadd"]; 
$fullname=$r["fullname"]; 
$country=$r["country"]; 
$date=$r["date"]; 
$sig=$r["sig"]; 
$avatar=$r["avatar"]; 
// display it all
?>
<div align="center"><br>
<form method="post" name="edit" action="editac.php?a=edit">
Full Name:<br><input type="text" name="fullname" value="<?=$fullname;?>"><br><br>
E-mail Address:<br><input type="text" name="emailadd" value="<?=$emailadd;?>"><br><br>
Country:<br><input type="text" name="country" value="<?=$country;?>"><br><br>
Forum Signature:<br><input type="text" name="sig" value="<?=$sig;?>"><br><br>
Avatars:<br>
<script language="javascript" type="text/javascript">
<!--
function go( num ) {
  if( !num ) return;
  var prev = document.getElementById( 'oav' ).value;
  if( !prev ) return;
  document.getElementById( 'oav' ).value = ( document.getElementById( 'avatar' ).value );
}
//-->
</script>
<select name="avatar" id="avatar" onchange="go( this.options[this.selectedIndex].value );">
<option value="<?=$avatar;?>" selected>Current Avatar</option>
<option value="images/af.jpg">Africa</option>
<option value="images/asia.jpg">Asia</option>
<option value="images/at.jpg">Antartica</option>
<option value="images/au.jpg">Australia</option>
<option value="images/eu.jpg">Europe</option>
<option value="images/world.jpg">World</option>
<option value="images/na.jpg">North America</option>
<option value="images/us.jpg">USA</option>
<option value="images/sa.jpg">South America</option>
<option value="http://example.com/avatar.jpg">Other</option>
 </select> <input id="oav" type="text" value="<?=$avatar;?>" name="otherav"><br><br>
<input type="submit" value="Change Details">
</form>
</div>
<?
} 
mysql_close($db); 
echo "<br>";
} else { }
if ($a =="edit") {
include "config.php"; 
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "UPDATE users SET fullname='$_POST[fullname]', emailadd ='$_POST[emailadd]', country='$_POST[country]', sig='$_POST[sig]', avatar='$_POST[otherav]'  WHERE username = '$_SESSION[Username]'"; 
$result = mysql_query($query); 
echo "Account ";
mysql_close($db); 
include "config.php"; 
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "UPDATE forum SET sig='$_POST[sig]', avatar='$_POST[avatar]'  WHERE poster = '$_SESSION[Username]'"; 
$result = mysql_query($query); 
echo "Updated";
echo '<meta http-equiv="refresh" content="1;url=accounts.php">'; 
mysql_close($db); 
} else { }
?>
</div>
<div class="right1">
<div class="side-box">
<div class="side-headline">Options:</div>
<br>
&nbsp;&nbsp;&nbsp;-<a href="changep.php">Change Password</a><br>
&nbsp;&nbsp;&nbsp;-<a href="editac.php">Edit Information</a><br>
&nbsp;&nbsp;&nbsp;-<a href="feedback.php">Feedback</a><br>
&nbsp;&nbsp;&nbsp;-<a href="<?=$_SERVER['PHP_SELF'];?>?mode=logout">Logout</a><br>
&nbsp;
</div><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br> <br><br> 
</div>
<?

include "footer.php";
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