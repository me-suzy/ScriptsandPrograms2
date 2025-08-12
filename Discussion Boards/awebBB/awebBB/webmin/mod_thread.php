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
if($_GET['c'] == ""){
echo "<b>Select a category:</b><br><br><ol type=\"1\">";
include "../config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "SELECT category FROM fcat ORDER BY id"; 
$result = mysql_query($query); 
while($r=mysql_fetch_array($result)) 
{ 
$cname=$r["category"]; 
echo "<li><a href=\"mod_thread.php?c=$cname\">$cname</a></li>"; 
} 
mysql_close($db); 
echo "<li><a href=\"mod_thread.php?c=Everything\">All Threads</a></li>"; 
echo "</ol>";
} else {
if($_GET['id'] == ""){
?>
<b>Threads About <?=$_GET['c'];?>:</b><br><br>
<ol type="i">
<?
if ($_GET['c'] == "Everything") {
$catidentifier = "";
} else { 
$catidentifier = " WHERE categories = '$_GET[c]'";
}
include "../config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
//Get the data
$query = "SELECT id, tid, categories, tname, poster, date FROM flist$catidentifier ORDER BY id DESC"; 
$result = mysql_query($query); 
/* Here we fetch the result as an array */ 
while($r=mysql_fetch_array($result)) 
{ 
/* This bit sets our data from each row as variables, to make it easier to display */ 
$id=$r["id"]; 
$tid=$r["tid"]; 
$title=$r["tname"]; 
$poster=$r["poster"]; 
$date=$r["date"]; 
$gdate=$r["date"];
$cat1=$_GET['c'];



echo "<li><b>$title</b> <i>Started by $poster, Replies:";
$query2="SELECT * FROM forum WHERE tid = $tid";
$result2 = mysql_query($query2);
 $num_rows2 = mysql_num_rows($result2);
$presum=$num_rows2;
echo "$presum"; 
echo "</i> [ <a href='mod_thread.php?id=$id&c=$cat1&tid=$tid&t=$title&a=dit'>Edit</a> ] [ <a href='mod_thread.php?id=$id&a=del&tid=$tid&c=$cat1'>Delete</a> ]<br></li>";
} 
mysql_close($db); 
echo "</ol>";
} else {

if($_GET['a'] == "del"){
include "../config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$sql = "DELETE FROM flist WHERE id = '$_GET[id]'";
$query = mysql_query($sql) or die("Cannot delete record.<br>" . mysql_error());
echo "Thread ";
mysql_close($db); 
include "../config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$sql = "DELETE FROM forum WHERE tid = '$_GET[tid]'";
$query = mysql_query($sql) or die("Cannot delete record.<br>" . mysql_error());
echo "Deleted!";
?>
<meta http-equiv="refresh" content="0;url=index.php">
<? 
mysql_close($db); 
} else { }

if($_GET['a'] == "dit"){
include "../config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "SELECT categories, tname FROM flist WHERE id = '$_GET[id]'"; 
$result = mysql_query($query); 
while($r=mysql_fetch_array($result)) 
{ 
$id=$r["id"]; 
$categories=$r["categories"]; 
$tname=$r["tname"]; 
?>
<b>Modify a thread:</b><br><br>
<form name="threads" method="post" action="mod_thread.php?id=<?=$_GET['id'];?>&tid=<?=$_GET['tid'];?>&c=<?=$_GET['c'];?>&a=editnow"> 
Thread Category:<br>
<select name="category" id="category">
                      <option value="<?=$categories;?>" selected><?=$categories;?></option>
<?php 
//connection to database
$query3 = "SELECT category FROM fcat WHERE category != '$categories'"; 
$result3 = mysql_query($query3); 
while($r3=mysql_fetch_array($result3)) 
{ 
$cname=$r3["category"]; 
echo "<option value=\"$cname\">$cname</option>"; 
}
?>
 </select><br>
Thread Name:<br>
<input type="text" name="tname" value="<?=$tname;?>"><br>
<input type="submit" name="Submit" value="Modify Thread">
</form>
<?
}
mysql_close($db); 
} else { }
if ($_GET['a'] == "editnow") {
include "../config.php"; // As you can see we connected to the database with config
$db = mysql_connect($db_host, $db_user, $db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "UPDATE flist SET categories='$_POST[category]', tname='$_POST[tname]' WHERE id = '$_GET[id]'"; 
$result = mysql_query($query); 
echo "Modified ";
mysql_close($db); 
include "../config.php"; // As you can see we connected to the database with config
$db = mysql_connect($db_host, $db_user, $db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "UPDATE forum SET categories='$_POST[category]', tname='$_POST[tname]' WHERE tid = '$_GET[tid]'"; 
$result = mysql_query($query); 
echo "Thread";
echo '<meta http-equiv="refresh" content="0;url=index.php">'; 
mysql_close($db); 
} else { }

}
}
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