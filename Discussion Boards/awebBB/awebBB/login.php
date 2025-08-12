<?php
session_start();
include "header.php"; 
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
?>
    <div class="side-headline">Login</div><div align="center"><br>
<form method="post" action="login.php?page=<?=$_GET['page'];?>">
        Username:<br><input type="text" size="20" name="Username"><br>
        Password:<br><input type="password" size="20" name="Password"><br>
        <input type="hidden" name="Submitted" value="True">
        <input type="Submit" name="Submit" value="Submit"> <br>[ <a href="register.php">Register</a> ] [ <a href="fpass.php">Forgot Password?</a> ] </form> </div>
<?
}
else
{
if ($_GET['page'] == "") {
$pagetogo = "index.php";
} else {
$pagetogo = $_GET['page'];
}
?>
      <div class="side-headline">Login </div><div align="center"><br>Your are logged in as: <b><?=$_SESSION['Username'];?></b><br>To log in as a different user please <a href="<?=$_SERVER['PHP_SELF'];?>?mode=logout">Logout.</a><br>&nbsp;
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
include "footer.php";

?>


