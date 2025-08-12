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
$a=$_GET['a'];
if ($a == "skyreg" ) {
$fullname=$_POST['fullname'];
$username=strtolower($_POST['username']);
$password1=$_POST['password1'];
$password2=$_POST['password2'];
$emailadd=$_POST['emailadd'];
$country=$_POST['country'];
if ($fullname == "") {
$fullnamebox="<div class=\"error-box\">";
$fullnamebox1="</div>";
} else { }
if ($username == "") {
$usernamebox="<div class=\"error-box\">";
$usernamebox1="</div>";
} else { }
if ($password1 == "") {
$pword1box="<div class=\"error-box\">";
$pword1box1="</div>";
} else { }
if ($password2 == "") {
$pword2box="<div class=\"error-box\">";
$pword2box1="</div>";
} else { }
if ($emailadd == "") {
$emailaddbox="<div class=\"error-box\">";
$emailaddbox1="</div>";
} else { }
if ($country == "") {
$countrybox="<div class=\"error-box\">";
$countrybox1="</div>";
} else { }
if ($fullname == "" OR $username == "" OR $password1 == "" OR $password2 == "" OR $emailadd == "" OR $country == "") {

$errormessage="<font color=\"red\">Please Fill in all Feilds.</font><br>";
} else { 

include "../config.php";
$db12 = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query12 = "SELECT username FROM users WHERE username = '$username'"; 
$result12 = mysql_query($query12); 
while($r=mysql_fetch_array($result12)) 
{ 
$username12=$r["username"]; 
}
if ($username12 == $username) {
$errormessage3="<font color=\"red\">The username chosen is in use, choose another.</font>";
} else { 


if ($password1 == $password2) {
$fullname=$_POST['fullname'];
$username=strtolower($_POST['username']);
$password=$_POST['password1'];
$emailadd=$_POST['emailadd'];
$schoolyear=$_POST['schoolyear'];
$country=$_POST['country'];
$defsig1=$_POST['defsig'];
$defimage1=$_POST['defimage'];
$userlevel=$_POST['userlevel'];



include "../config.php"; // As you can see we connected to the database with config
$db = mysql_connect($db_host, $db_user, $db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "INSERT INTO users(level, username, password, emailadd, fullname, country, date, sig, avatar) 
VALUES('$userlevel', '$username','$password','$emailadd','$fullname', '$country', now(), '$defsig1', '$defimage1')"; 
mysql_query($query); 
echo "Account Created ";
    echo "<META HTTP-EQUIV=\"refresh\" content=\"0; URL=index.php\">";
mysql_close($db); 

} else {
$errormessage2="<font color=\"red\">Passwords Entered do not Match.</font>";
}

}
mysql_close($db12);
}

} else { }

if ($a != "skyreg" OR $a != "reg") { ?>
<?
include "../config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "SELECT defimage, defsig FROM prefs"; 
$result = mysql_query($query); 
while($r=mysql_fetch_array($result)) 
{ 
$defimage = $r["defimage"]; 
$defsig = $r["defsig"]; 
?>
<b>New User Form:</b><br><br>
<div align="center"><?=$errormessage;?><?=$errormessage2;?><?=$errormessage3;?><br>
<form method="post" action="new_user.php?a=skyreg">
<input type="hidden" name="defimage" value="<?=$defimage;?>">
<input type="hidden" name="defsig" value="<?=$defsig;?>">
<?=$fullnamebox;?>Full Name:<br><input type="text" name="fullname" value="<?=$fullname;?>"><br>&nbsp;<?=$fullnamebox1;?>
<?=$usernamebox;?>Username:<br><input type="text" name="username" value="<?=$username;?>"><br>&nbsp;<?=$usernamebox1;?>
<?=$pword1box;?>Password:<br><input type="password" name="password1"><br>&nbsp;<?=$pword1box1;?>
<?=$pword2box;?>Retype Password:<br><input type="password" name="password2"><br>&nbsp;<?=$pword2box1;?>
<?=$emailaddbox;?>E-mail Address:<br><input type="text" name="emailadd" value="<?=$emailadd;?>"><br>&nbsp;<?=$emailaddbox1;?>
<?=$countrybox;?>Country:<br><input type="text" name="country" value="<?=$country;?>"><br>&nbsp;<?=$countrybox1;?>User Level:<br>
<select name="category" id="category">
                      <option value="3" selected>Normal User</option>
                      <option value="1">Administrator</option>
</select><br>
<input type="reset" value="Clear Form"> <input type="submit" value="Register">
</form>
</div>
<? 
} 
mysql_close($db); 
} else {  } 



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
