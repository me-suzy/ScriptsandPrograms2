<?php
session_start();
// This script was developed by Adam M. B. from aWeb Labs
// Visit us at http://www.labs.aweb.com.au
// for forum problems, bugs, or ideas email yougotmail@gmail.com
// thanks for trying out or using this news script
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
echo "save";
    echo "<META HTTP-EQUIV=\"refresh\" content=\"0; URL=login.php?page=" . $_SERVER['PHP_SELF'] . "\">";

}
else
{
include "style.php";
include "header.php";

$query7="SELECT * FROM comments";
$result7 = mysql_query($query7);
$commentsnum = mysql_num_rows($result7);
$query6="SELECT * FROM news";
$result6 = mysql_query($query6);
$newsnum = mysql_num_rows($result6);
$query5="SELECT * FROM categories";
$result5 = mysql_query($query5);
$catsnum = mysql_num_rows($result5);
?>
<div align="center"><div class="bluein-box2">
<b>Welcome to aWebNews, a news and comment management system.</b><br><br>
<b>Statistics:</b><br>
&nbsp;&nbsp;&nbsp;&nbsp;<?=$commentsnum;?> Comments<br>
&nbsp;&nbsp;&nbsp;&nbsp;<?=$newsnum;?> News Articles<br>
&nbsp;&nbsp;&nbsp;&nbsp;<?=$catsnum;?> Categories<br><br>
</div></div>
<?
} 
?><div align="center"><div class="bluein-box2">
<b>How to place news on your page:</b><br><br>
Change the page extension to '.php' and insert the following code where you want to news to appear:<br><br>
<font face="arial">
&lt;?php<br><font face="arial" color="green">// Number of Stories to display</font><br>
$number_of_stories = "8";<br><font face="arial" color="green">
// Width of news section</font><br>
$news_width1 = "550px";<br><font face="arial" color="green">
// Path to news directory in relation to page displaying the news</font><br>
$path_to_news = "news/";<br><font face="arial" color="green">
// Location of news page in relation to page displaying the news (visview.php)</font><br>
include "news/visview.php";<br>
?&gt;
</font>
<br><br>
</div></div>
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
    echo "<META HTTP-EQUIV=\"refresh\" content=\"0; URL=" . $_SERVER['PHP_SELF'] . "\">";
}
mysql_close($db); 

?> 