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
<?
if ($_GET['id'] == "") {
echo "Categories: ";
//connection to database
include "../config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "SELECT category FROM fcat ORDER BY id"; 
$result = mysql_query($query); 
while($r=mysql_fetch_array($result)) 
{ 
$cname=$r["category"]; 
echo "<b><a href=\"edit_post.php?c=$cname\">$cname</a>&nbsp;&nbsp;&nbsp;</b>"; 
} 
mysql_close($db); 
echo "<br><br>";
if ($_GET['c'] == "") {
echo "Displaying Unfiled Posts";
} else { 
$rowstart = $_GET['rowstart'];
if ($rowstart == "") {
$rownow= "0";
} else { }
echo "<div align=\"center\">";
$getcat = $_GET['c'];
if ($rowstart == "0" OR $rowstart == "") {
} else {
$row11 = ($rowstart - 10);
	echo "<< <a href=\"$php_self?c=$getcat&rowstart=$row11\">Previous</a> | ";
} 

$row12 = ($rowstart + 10);
	echo "<a href=\"$php_self?c=$getcat&rowstart=$row12\">Next</a> >>";
}
echo "</div>";
//connection to database
include "../config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query4 = "SELECT id, tname, poster, fpost, sig, avatar, time, date FROM forum WHERE categories = '$_GET[c]' ORDER BY date LIMIT $rownow$rowstart,10"; 
$result4 = mysql_query($query4); 
while($r=mysql_fetch_array($result4))
{ 

$id=$r["id"]; 
$tname=$r["tname"]; 
$poster=$r["poster"]; 
$fpost=$r["fpost"]; 
$sig=$r["sig"]; 
$avatar=$r["avatar"]; 
$time=$r["time"]; 
$date=$r["date"]; 

echo "<div class=\"blue-box\"><div class=\"breaker\"><b>$tname</b> by $poster&nbsp;&nbsp;&nbsp;[ <a href=\"edit_post.php?id=$id\"><b>Edit</b></a> ] [ <a href=\"remove_post.php?id=$id&c=" . $_GET['c'] . "\"><b>Remove</b></a> ] </div><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\"><tr><td height=\"80\" width=\"80\" rowspan=\"2\"><img src=\"$avatar\" border=\"0\" align=\"left\" width=\"80\" height=\"80\"></td><td valign=\"top\"><div class=\"breaker\">$fpost</div></td></tr><tr><td valign=\"bottom\"><div align=\"right\"><i>$sig</i><br>$time - $date</div></td></tr></table></div>"; 
 
}



echo "<br></div>";

} else {


include "../config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "SELECT id, categories, tname, poster, fpost, sig, avatar, time, date FROM forum WHERE id = '$_GET[id]'"; 
$result = mysql_query($query); 
while($r=mysql_fetch_array($result)) 
{ 
$id=$r["id"]; 
$categories=$r["categories"]; 
$tname=$r["tname"]; 
$poster=$r["poster"]; 
$fpost=$r["fpost"]; 
$sig=$r["sig"]; 
$avatar=$r["avatar"]; 
$time=$r["time"]; 
$date=$r["date"]; 
?>
<b>Edit Forum Post:</b><br>
<form name="threads" method="post" action="edit_post.php?id=<?=$_GET['id'];?>&a=post"> 
Thread Name: <br><input type="text" name="tname" value="<?=$tname;?>" size="30"><br>
Select a Category:<br> 
<select name="category" id="category">
                      <option value="<?=$categories;?>" selected><?=$categories;?></option>
<?php 
//connection to database
include "../config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "SELECT category FROM fcat WHERE category != '$categories'"; 
$result = mysql_query($query); 
while($r=mysql_fetch_array($result)) 
{ 
$cname=$r["category"]; 
echo "<option value=\"$cname\">$cname</option>"; 
} 
mysql_close($db); ?>
 </select><br>
Thread Text: 
      <br> 
      <textarea name="fpost" cols="45" rows="5"><?=$fpost;?></textarea><br>
<input type="submit" name="Submit" value="Modify Thread">
</form>
<? 
} 

if ($_GET['a'] == "post") {
include "../config.php"; // As you can see we connected to the database with config
$db = mysql_connect($db_host, $db_user, $db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "UPDATE forum SET categories='$_POST[category]', tname ='$_POST[tname]', fpost ='$_POST[fpost]' WHERE id = '$_GET[id]'"; 
$result = mysql_query($query); 
echo "Modified";
echo '<meta http-equiv="refresh" content="0;url=edit_post.php">'; 
mysql_close($db); 
} else { }


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