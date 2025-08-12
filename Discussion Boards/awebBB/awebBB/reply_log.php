<?
$user123=$_POST['Username'];
//Get the data
$loghere = "query$id";
$loghere = "SELECT id, username, password FROM users WHERE username='$user123'"; 

$result21 = "result$id";
$result21 = mysql_query($loghere); 
/* Here we fetch the result as an array */ 
while($r=mysql_fetch_array($result21)) 
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
    <div class="breaker">Login</div><div align="center"><br>
<form method="post" action="ndis.php?c=<?=$_GET['c'];?>&tid=<?=$_GET['tid'];?>&t=<?=$_GET['t'];?>&a=refresh">
        Username:<br><input type="text" size="20" name="Username"><br>
        Password:<br><input type="password" size="20" name="Password"><br>
        <input type="hidden" name="Submitted" value="True">
        <input type="Submit" name="Submit" value="Submit"> <br>[ <a href="register.php">Register</a> ] [ <a href="fpass.php">Forgot Password?</a> ] </form> </div>
<?
} else {
?>

<?
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