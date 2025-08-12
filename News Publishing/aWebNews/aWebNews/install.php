<?
if ($_GET['a'] == "") {
?>
<br><br><div align="center"><h2>Welcome to aWebNews Version 1.0</h2><br><br>
After editing the configuration file located at /aWebNews/config.php and created a database named 'awebnews' (or different) please click <a href="install.php?a=install">here</a> to install.<br>
</div>

<?
} else { }
if ($_GET['a'] == "install") {
echo "Installing MYSQL Tables...<br>";
include "config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = 'CREATE TABLE `categories` (
  `id` int(11) NOT NULL auto_increment,
  `category` varchar(150) default NULL,
  `descript` text,
  PRIMARY KEY  (`id`)
)';
$result = mysql_query($query); 
echo "Created Categories Table<br>";

$query2 = 'CREATE TABLE `comments` (
  `id` int(11) NOT NULL auto_increment,
  `cid` varchar(11) default NULL,
  `yname` varchar(100) default NULL,
  `emailadd` varchar(100) default NULL,
  `subject` varchar(150) default NULL,
  `comment` text,
  `datetime` varchar(150) default NULL,
  PRIMARY KEY  (`id`)
)';
$result2 = mysql_query($query2); 
echo "Created Comments Table<br>";

$query4 = 'CREATE TABLE `news` (
  `id` int(11) NOT NULL auto_increment,
  `cid` varchar(11) default NULL,
  `category` varchar(50) default NULL,
  `title` varchar(150) default NULL,
  `author` varchar(75) default NULL,
  `shorta` text,
  `longa` text,
  `datetime` varchar(75) default NULL,
  `eauthor` varchar(150) default NULL,
  PRIMARY KEY  (`id`)
)';
$result4 = mysql_query($query4); 
echo "Created Main News Table<br>";

$query5 = 'CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(20) default NULL,
  `password` varchar(20) default NULL,
  `emailadd` varchar(50) default NULL,
  `fullname` varchar(40) default NULL,
  PRIMARY KEY  (`id`)
)';
$result5 = mysql_query($query5); 
echo "Created Users Table<br>";
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
if ($fullname == "" OR $username == "" OR $password1 == "" OR $password2 == "" OR $emailadd == "") {

$errormessage="<font color=\"red\">Please Fill in all Feilds.</font><br>";
} else { 

include "config.php";
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



include "config.php"; // As you can see we connected to the database with config
$db = mysql_connect($db_host, $db_user, $db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "INSERT INTO users(username, password, emailadd, fullname) 
VALUES('$username','$password','$emailadd','$fullname')"; 
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
<?=$fullnamebox;?>Full Name:<br><input type="text" name="fullname"><br>&nbsp;<?=$fullnamebox1;?>
<?=$usernamebox;?>Username:<br><input type="text" name="username"><br>&nbsp;<?=$usernamebox1;?>
<?=$pword1box;?>Password:<br><input type="password" name="password1"><br>&nbsp;<?=$pword1box1;?>
<?=$pword2box;?>Retype Password:<br><input type="password" name="password2"><br>&nbsp;<?=$pword2box1;?>
<?=$emailaddbox;?>E-mail Address:<br><input type="text" name="emailadd"><br>&nbsp;<?=$emailaddbox1;?>
<input type="reset" value="Clear Form"> <input type="submit" value="Register">
</form>
</div>
<? 
} else {  }

} else { }
if ($_GET['a'] == "done") {
?>
<div align="center">
<br><br>All done!!!<br><br>Please procede to the <a href="index.php">Admin section</a>to add news.<br><br><font color="red"><h2>Remember to delete install.php</h2></font>
</div>
<?
} else { }
?>



