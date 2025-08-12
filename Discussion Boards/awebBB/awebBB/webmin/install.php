<?
if ($_GET['a'] == "") {
?>
<br><br><div align="center"><h2>Welcome to aWebBB</h2><br><br>
After editing the configuration file located at /forum/config.php and created a database named 'awebbb_forum' (or different) please click <a href="install.php?a=install">here</a> to install.<br>
</div>

<?
} else { }
if ($_GET['a'] == "install") {
echo "Installing MYSQL Tables...<br>";
include "../config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = 'CREATE TABLE `fcat` (
  `id` int(11) NOT NULL auto_increment,
  `category` varchar(50) default NULL,
  `description` varchar(250) default NULL,
  PRIMARY KEY  (`id`)
)';
$result = mysql_query($query); 
echo "Created Categories Table<br>";

$query2 = 'CREATE TABLE `flist` (
  `id` int(11) NOT NULL auto_increment,
  `tid` varchar(50) default NULL,
  `categories` varchar(30) default NULL,
  `tname` varchar(100) default NULL,
  `poster` varchar(50) default NULL,
  `date` date default NULL,
  PRIMARY KEY  (`id`)
)';
$result2 = mysql_query($query2); 
echo "Created Thread List Table<br>";

$query4 = 'CREATE TABLE `forum` (
  `id` int(11) NOT NULL auto_increment,
  `tid` varchar(50) default NULL,
  `categories` varchar(30) default NULL,
  `tname` varchar(100) default NULL,
  `poster` varchar(30) default NULL,
  `fpost` text,
  `sig` varchar(150) default NULL,
  `avatar` varchar(150) default NULL,
  `time` time default NULL,
  `date` date default NULL,
  PRIMARY KEY  (`id`)
)';
$result4 = mysql_query($query4); 
echo "Created Main Fourm Table<br>";

$query5 = 'CREATE TABLE `prefs` (
  `id` int(11) NOT NULL auto_increment,
  `sitename` varchar(99) default NULL,
  `forumname` varchar(99) default NULL,
  `sitetitle` varchar(99) default NULL,
  `menulink` varchar(11) default NULL,
  `normallink` varchar(11) default NULL,
  `defimage` varchar(150) default NULL,
  `defsig` varchar(150) default NULL,
  `backcolor` varchar(20) default NULL,
  `msitecolor` varchar(20) default NULL,
  `siteurl` varchar(150) default NULL,
  `headimage` varchar(150) default NULL,
  `hiwidth` varchar(4) default NULL,
  `hiheight` varchar(4) default NULL,
  `forumcolor` varchar(11) default NULL,
  `normaltext` varchar(11) default NULL,
  `copyright` varchar(150) default NULL,
  `email` varchar(100) default NULL,
  `adenable` varchar(11) default NULL,
  `adcode` text,
  `adlocation` varchar(20) default NULL,
  PRIMARY KEY  (`id`)
)';
$result5 = mysql_query($query5); 

$query7 = "INSERT INTO `prefs` VALUES (1, 'Site Name', 'Forum Name', 'Title of your site', 'white', 'blue', 'images/world.jpg', 'Love Life', 'white', 'blue', '', 'images/logo1.jpg', '700', '80', 'white', 'black','','','','','');"; 
mysql_query($query7); 

echo "Created Preferences Table<br>";

$query6 = 'CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `level` char(2) default NULL,
  `username` varchar(20) default NULL,
  `password` varchar(20) default NULL,
  `emailadd` varchar(50) default NULL,
  `fullname` varchar(40) default NULL,
  `country` varchar(30) default NULL,
  `date` date default NULL,
  `sig` varchar(150) default NULL,
  `avatar` varchar(150) default NULL,
  PRIMARY KEY  (`id`)
)';
$result6 = mysql_query($query6); 
echo "Created Users Table<br>";

$query7 = 'CREATE TABLE `menu` (
  `id` int(11) NOT NULL auto_increment,
  `bname` varchar(50) default NULL,
  `link` varchar(200) default NULL,
  PRIMARY KEY  (`id`)
)';
$result7 = mysql_query($query7); 
echo "Created Menu Table<br>";
echo '<meta http-equiv="refresh" content="1;url=install.php?a=users">'; 
} else { }
if ($_GET['a'] == "users") {
?>
Please Create the Administrative User Account:<br><br>
<style type="text/css">
<!--
div.error-box {width:200px; background:pink; margin-top: 2px; border: 1px solid red; text-align: center;}
//-->
</style>
<?
$a=$_GET['b'];
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
$query12 = "SELECT username FROM users WHERE username = '$_POST[username]'"; 
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



include "../config.php"; // As you can see we connected to the database with config
$db = mysql_connect($db_host, $db_user, $db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "INSERT INTO users(level, username, password, emailadd, fullname, country, date, sig, avatar) 
VALUES('1', '$username','$password','$emailadd','$fullname', '$country', now(), '$defsig1', '$defimage1')"; 
mysql_query($query); 
echo "Account Created";
echo '<meta http-equiv="refresh" content="1;url=install.php?a=done">'; 
mysql_close($db); 

} else {
$errormessage2="<font color=\"red\">Passwords Entered do not Match.</font>";
}

}
mysql_close($db12);
}

} else { }

if ($a != "skyreg" OR $a != "reg") { 
?>
<div align="center"><?=$errormessage;?><?=$errormessage2;?><?=$errormessage3;?><br>
<form method="post" action="install.php?a=users&b=skyreg">
<input type="hidden" name="defimage">
<input type="hidden" name="defsig">
<?=$fullnamebox;?>Full Name:<br><input type="text" name="fullname"><br>&nbsp;<?=$fullnamebox1;?>
<?=$usernamebox;?>Username:<br><input type="text" name="username"><br>&nbsp;<?=$usernamebox1;?>
<?=$pword1box;?>Password:<br><input type="password" name="password1"><br>&nbsp;<?=$pword1box1;?>
<?=$pword2box;?>Retype Password:<br><input type="password" name="password2"><br>&nbsp;<?=$pword2box1;?>
<?=$emailaddbox;?>E-mail Address:<br><input type="text" name="emailadd"><br>&nbsp;<?=$emailaddbox1;?>
<?=$countrybox;?>Country:<br><input type="text" name="country"><br>&nbsp;<?=$countrybox1;?>
<input type="reset" value="Clear Form"> <input type="submit" value="Register">
</form>
</div>
<? 
} else {  }

} else { }
if ($_GET['a'] == "done") {
?>
<div align="center">
<br><br>All done!!!<br><br>Please procede to the <a href="index.php">Admin section</a> and Edit the <a href="pref_edit.php">site preferences</a>.<br><br><font color="red"><h2>Remember to delete install.php AND upgrade.php for security reasons!!!</h2></font>
</div>
<?
} else { }
?>



