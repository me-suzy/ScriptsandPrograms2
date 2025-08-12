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
// If they are NOT logged in then show the form to login...
if ($_SESSION['Logged_In'] != "True") {
?>
<style type="text/css">
<!--
body {
font-family: verdana, arial, helvetica, sans-serif; font-size: 11px; background-color: white; }
table {
font-family: verdana, arial, helvetica, sans-serif; font-size: 11px;}
a:link { font-family: verdana; font-size: 11px; color: blue; text-decoration: none; }
a:visited { font-family: verdana; font-size: 11px; color: blue; text-decoration: none; }
a:active { font-family: verdana; font-size: 11px; color: blue; text-decoration: none; }
a:hover { font-family: verdana; font-size: 11px; color: blue; text-decoration: underline; }
div.boxxy {position: absolute; display: block; width: 300px; padding: 0px; margin-bottom: 2px; margin-right: 2px; margin-left: 202px; margin-top: 168px; border: 1px solid gray; background-color: white;}
div.boxtext {position: absolute; display: block; height:18px padding: 0px; margin-bottom: auto; margin-right: auto; margin-left: 211px; margin-top: 162px; background-color: white;}
div.tbord {border-left: 1px solid gray; border-top: 1px solid gray; border-bottom: 1px solid gray; background-color: white;}
div.grey-box {width: 500px; background: silver; margin-top: 2px; border: 1px solid gray; text-align: left;}
//-->
</style>	
<div class="boxxy"><br><br><form method="post" action="login.php?page=<?=$_GET['page'];?>">
<table cellpadding="0" cellspacing="0" border="0" align="center"><tr><td style="border-left: 1px solid gray; border-top: 1px solid gray; border-bottom: 1px solid gray;">Username:</td><td><input type="text" name="Username" style="border: 1px solid gray;"></td></tr><tr><td height="2"></td></tr><tr><td style="border-left: 1px solid gray; border-top: 1px solid gray; border-bottom: 1px solid gray;">Password:</td><td><input type="password" name="Password" style="border: 1px solid gray;"></td></tr><tr><td height="2"></td></tr><tr><td colspan="2" align="right"><input type="submit" style="border: 1px solid gray; font-family: verdana; font-size: 11px; background-color: white;" name="Submit" value="Submit"></td></tr><tr><td height="2"></td></tr><tr><td colspan="2" align="right"><a href="fpass.php">Forget your Password?</a></td></tr></table>        <input type="hidden" name="Submitted" value="True"></form>
</div>
<div class="boxtext" align="center">&nbsp;<b>aWebNews Admin Login</b>&nbsp;</div><?
}
else
{
if ($_GET['page'] == "") {
$pagetogo = "index.php";
} else {
$pagetogo = $_GET['page'];
}
?>
      <div class="side-headline">Login </div><div align="center"><br>Your are logged in as: <b><?=$_SESSION['Username'];?></b><br>Please <a href="<?=$_SERVER['PHP_SELF'];?>?mode=logout">Logout.</a><br>&nbsp;
<META HTTP-EQUIV="refresh" content="1; URL=<?=$pagetogo;?>">
</div>
<?

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
mysql_close($db); 
?>


