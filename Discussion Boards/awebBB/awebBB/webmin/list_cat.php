<?
session_start();

// connect to database and pull up info
include "../config.php";
$user123=$_POST['Username'];
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
//Get the data
$query = "SELECT id, level, username, password FROM users WHERE username='$user123' AND level='1'"; 
 
$result = mysql_query($query); 
/* Here we fetch the result as an array */ 
while($r=mysql_fetch_array($result)) 
{ 
/* This bit sets our data from each row as variables, to make it easier to display */ 
$id=$r["id"]; 
$_level=$r["level"]; 
$_Username=$r["username"]; 
$_Password=$r["password"]; 

// If the form was submitted
if ($_POST['Submitted'] == "True") {

    // If the username and password match up, then continue...
    if ($_POST['Username'] == $_Username && $_POST['Password'] == $_Password && $_level == 1) {

        // Username and password matched, set them as logged in and set the
        // Username to a session variable.
        $_SESSION['Logged_In'] = "True-Admin";
        $_SESSION['Level'] = "1";
        $_SESSION['Username'] = $_Username;
    }
}
} 
mysql_close($db); 
// If they are NOT logged in then show the form to login...
if ($_SESSION['Logged_In'] != "True-Admin") {
?>
<?
include "style.php";
?>
<div class="boxxy"><br><br><form method="post" action="<?=$_SERVER['PHP_SELF'];?>">
<table cellpadding="0" cellspacing="0" border="0" align="center"><tr><td style="border-left: 1px solid gray; border-top: 1px solid gray; border-bottom: 1px solid gray;">Username:</td><td><input type="text" name="Username" style="border: 1px solid gray;"></td></tr><tr><td height="2"></td></tr><tr><td style="border-left: 1px solid gray; border-top: 1px solid gray; border-bottom: 1px solid gray;">Password:</td><td><input type="password" name="Password" style="border: 1px solid gray;"></td></tr><tr><td height="2"></td></tr><tr><td colspan="2" align="right"><input type="submit" style="border: 1px solid gray; font-family: verdana; font-size: 11px; background-color: white;" name="Submit" value="Submit"></td></tr><tr><td height="2"></td></tr><tr><td colspan="2" align="right"><a href="../fpass.php">Forget your Password?</a></td></tr></table>        <input type="hidden" name="Submitted" value="True"></form>
</div>
<div class="boxtext" align="center">&nbsp;<b>aWebBB Admin Login</b>&nbsp;</div>

<?
}
else
{
include "header.php";
?>
<style type="text/css">
<!--
div.blue-box {width:600px; background:#e5ecf9; margin-top: 2px; border: 1px solid blue; text-align: left;}
div.breaker {margin-bottom: 2px; margin-right: 2px; margin-left: 2px; margin-top: 2px; border-bottom: 1px solid blue; text-align: left;}
//-->
</style>
<b>Listing of all categories:</b><br><br>
<?
include "../config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query1 = "SELECT category, description FROM fcat ORDER BY category DESC"; 
$result1 = mysql_query($query1); 
while($r=mysql_fetch_array($result1)) 
{
$category = $r["category"];
$description = $r["description"];
?>
<div class="blue-box">
<table cellpadding="0" cellspacing="0" border="0" width="594"><tr><td width="4"></td><td width="350" valign="top">
<b><?=$category;?>:</b><br><?=$description;?></td><td width="70" valign="top"><b>Topics:</b><br>
<?
$query3="SELECT * FROM flist WHERE categories = '$category'";
$result3 = mysql_query($query3);
 $num_rows3 = mysql_num_rows($result3);
echo "$num_rows3"; 
?>
</td><td width="70" valign="top"><b>Posts:</b><br>
<?
$query2="SELECT * FROM forum WHERE categories = '$category'";
$result2 = mysql_query($query2);
 $num_rows2 = mysql_num_rows($result2);
echo "$num_rows2"; 
?>
</td><td width="100" valign="top"><b>Last Post:</b><br>
<?
$query5 = "SELECT time, date FROM forum WHERE categories = '$category' ORDER BY date DESC LIMIT 0,1"; 
$result5 = mysql_query($query5); 
/* Here we fetch the result as an array */ 
while($r1=mysql_fetch_array($result5)) 
{
$time=$r1["time"]; 
$date=$r1["date"]; 
?>
<?=$date;?> @ <?=$time;?>
<?
}
?>
</td></tr></table>
</div>
<?
}
mysql_close($db); 

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
    echo "<META HTTP-EQUIV=\"refresh\" content=\"0; URL=" . $_SERVER['PHP_SELF'] . "\">";
}
} 


?> 

