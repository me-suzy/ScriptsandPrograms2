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
echo "<b>Edit Menu Settings</b><br><br>";
echo "<a href=\"menu_edit.php?a=new\">New Menu Item</a><br><br>";
//connection to database
?>
<table cellpadding="0" cellspacing="0" border="1" style="border: 1px solid silver;"><tr><td width="70" align="center"><i>Actions</i></td><td width="150" align="center"><i>Menu Item Text</i></td><td width="250" align="center"><i>Link Target</i></td></tr>
<?
include "../config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "SELECT id, bname, link FROM menu"; 
$result = mysql_query($query); 
while($r=mysql_fetch_array($result)) 
{ 
$id = $r["id"];  
$bname = $r["bname"];  
$link = $r["link"];  
?>
<tr><td align="center"><a href="menu_edit.php?a=delete&id=<?=$id;?>"><img src="../images/b_drop.png" border="0"></a>&nbsp;<a href="menu_edit.php?a=edit&id=<?=$id;?>"><img src="../images/b_edit.png" border="0"></a></td><td><?=$bname;?></td><td><?=$link;?></td></tr>

<? }
echo "</table><br>";
if ($_GET['a'] == "edit") {
$query2 = "SELECT id, bname, link FROM menu WHERE id = '$_GET[id]' LIMIT 0,1"; 
$result2 = mysql_query($query2); 
while($r=mysql_fetch_array($result2)) 
{ 
$id = $r["id"];  
$bname = $r["bname"];  
$link = $r["link"];  
?>
<b>Modifying Menu Item:</b><br>
<form method="post" action="menu_edit.php?a=edit&b=save" name="post">
<input type="hidden" name="id" value="<?=$id;?>">
Menu Item Text:&nbsp;&nbsp;<input type="text" name="bname" value="<?=$bname;?>"><br>
Menu Item Link:&nbsp;&nbsp;<input type="text" name="link" value="<?=$link;?>"><br>
<input type="submit" value="Save Changes"></form>
<?
}
if ($_GET['b'] == "save") {
$query2 = "UPDATE menu SET bname='$_POST[bname]', link='$_POST[link]' WHERE id='$_POST[id]'"; 
mysql_query($query2); 
echo "Saved";
echo "<META HTTP-EQUIV=\"refresh\" content=\"1; URL=menu_edit.php\">";
} else { }
} else { }
if ($_GET['a'] == "new") {
?>
<b>New Menu Item:</b><br>
<form method="post" action="menu_edit.php?a=new&b=save" name="post">
Menu Item Text:&nbsp;&nbsp;<input type="text" name="bname"><br>
Menu Item Link:&nbsp;&nbsp;<input type="text" name="link"><br>
<input type="submit" value="Save Changes"></form>
<?
if ($_GET['b'] == "save") {
$query3 = "INSERT INTO menu(bname, link) 
VALUES('$_POST[bname]', '$_POST[link]')"; 
mysql_query($query3); 
echo "Saved";
echo "<META HTTP-EQUIV=\"refresh\" content=\"1; URL=menu_edit.php\">";
} else { }
} else { }
if ($_GET['a'] == "delete") {
$query4 = "DELETE FROM menu WHERE id = '$_GET[id]'"; 
mysql_query($query4); 
echo "Deleted";
echo "<META HTTP-EQUIV=\"refresh\" content=\"1; URL=menu_edit.php\">";
} else { }
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
