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

if ($_GET['a'] == "post") {
$finpost = "fpost" . $_POST['randomnum'] . "";
$time1=date("H:i:s");
include "config.php"; // As you can see we connected to the database with config
$db = mysql_connect($db_host, $db_user, $db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "INSERT INTO forum(tid, categories, tname, poster, fpost, sig, avatar, time, date) 
VALUES('$_POST[tid]','$_POST[categ]','$_POST[tname]','$_SESSION[Username]','$_POST[$finpost]','$_POST[sig]','$_POST[avatar]','$time1', now())"; 
mysql_query($query); 
echo "Submitted";
?>
<meta http-equiv="refresh" content="0;url=ndis.php?c=<?=$_GET['c'];?>&tid=<?=$_GET['tid'];?>&t=<?=$_GET['t'];?>">
<?
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
