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
echo "<h3>Edit Site Design / Information</h3><br>";
//connection to database
include "../config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "SELECT sitename, forumname, sitetitle, menulink, normallink, defimage, defsig, backcolor, msitecolor, siteurl, headimage, hiwidth, hiheight, forumcolor, normaltext, copyright, email FROM prefs LIMIT 0,1"; 
$result = mysql_query($query); 
while($r=mysql_fetch_array($result)) 
{ 
$sitename = $r["sitename"]; 
$forumname = $r["forumname"]; 
$sitetitle = $r["sitetitle"]; 
$menulink = $r["menulink"]; 
$normallink = $r["normallink"]; 
$defimage = $r["defimage"]; 
$defsig = $r["defsig"]; 
$backcolor = $r["backcolor"]; 
$msitecolor = $r["msitecolor"]; 
$siteurl = $r["siteurl"]; 
$headimage = $r["headimage"]; 
$hiwidth = $r["hiwidth"]; 
$hiheight = $r["hiheight"];  
$forumcolor = $r["forumcolor"];  
$normaltext = $r["normaltext"];  
$copyright = $r["copyright"];  
$email = $r["email"];  
?>
<table cellpadding="2" cellspacing="2" border="0"><tr><td>
<form action="pref_edit_save.php" method="post">
Site Name: </td><td><input type="text" value="<? echo $sitename; ?>" name="sitename"></td></tr><tr><td>
Name of Forum: </td><td><input type="text" value="<? echo $forumname; ?>" name="forumname"></td></tr><tr><td>
Title of your Site: </td><td><input type="text" value="<? echo $sitetitle; ?>" name="sitetitle"></td></tr><tr><td>
Color of Menu Link Items: </td><td><input type="text" value="<? echo $menulink; ?>" name="menulink"></td></tr><tr><td>
Color of Normal Link Text: </td><td><input type="text" value="<? echo $normallink; ?>" name="normallink"></td></tr><tr><td>
Color of Text: </td><td><input type="text" value="<? echo $normaltext; ?>" name="normaltext"></td></tr><tr><td>
Background Color of Page: </td><td><input type="text" value="<? echo $backcolor; ?>" name="backcolor"></td></tr><tr><td>
Background Color of Forum: </td><td><input type="text" value="<? echo $forumcolor; ?>" name="forumcolor"></td></tr><tr><td>
Main Forum Color: </td><td><input type="text" value="<? echo $msitecolor; ?>" name="msitecolor"></td></tr><tr><td>
Default Avatar Image: </td><td>
<script language="javascript" type="text/javascript">
<!--
function go( num ) {
  if( !num ) return;
  var prev = document.getElementById( 'oav' ).value;
  if( !prev ) return;
  document.getElementById( 'oav' ).value = ( document.getElementById( 'avatar' ).value );
}
//-->
</script>
<select name="avatar" id="avatar" onchange="go( this.options[this.selectedIndex].value );">
<option value="<?=$defimage;?>" selected>Current Avatar</option>
<option value="images/af.jpg">Africa</option>
<option value="images/asia.jpg">Asia</option>
<option value="images/at.jpg">Antartica</option>
<option value="images/au.jpg">Australia</option>
<option value="images/eu.jpg">Europe</option>
<option value="images/world.jpg">World</option>
<option value="images/na.jpg">North America</option>
<option value="images/us.jpg">USA</option>
<option value="images/sa.jpg">South America</option>
<option value="http://example.com/avatar.jpg">Other</option>
 </select></td><td><input id="oav" type="text" value="<?=$defimage;?>" name="otherav"></td></tr><tr><td>
Default Forum Signature: </td><td><input type="text" value="<? echo $defsig; ?>" name="defsig"></td></tr><tr><td>
Full URL of Forum: </td><td><input type="text" value="<? echo $siteurl; ?>" name="siteurl"></td></tr><tr><td>
Email address for forum feedback: </td><td><input type="text" value="<? echo $email; ?>" name="email"></td></tr><tr><td>
Site Logo or Image: </td><td><input type="text" value="<? echo $headimage; ?>" name="headimage"></td><td>sample at images/logo1.jpg</td></tr><tr><td>
Copyright Notice: </td><td><input type="text" value="<? echo $copyright; ?>" name="copyright"></td></tr><tr><td>
Site Logo Width X Height:</td><td><table cellpadding="0" cellspacing="0" border="0"><tr><td>
<input type="text" value="<? echo $hiwidth; ?>" name="hiwidth" size="8"><input type="text" value="<? echo $hiheight; ?>" name="hiheight" size="8">
</td></tr></table></td></tr><tr><td>
<input type="submit" value="Save Changes"></form></td></tr></table>

<? }
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
