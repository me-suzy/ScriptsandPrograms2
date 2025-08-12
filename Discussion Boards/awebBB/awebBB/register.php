<?php
// This forum was developed by Adam M. B. from aWeb Labs
// Visit us at http://www.labs.aweb.com.au
// for forum problems, bugs, or ideas email yougotmail@gmail.com
// thanks for trying out or using this forum
// aWebBB version 1.2 released under the GNU GPL
include("header.php"); 
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

include "config.php";
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



include "config.php"; // As you can see we connected to the database with config
$db = mysql_connect($db_host, $db_user, $db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "INSERT INTO users(level, username, password, emailadd, fullname, country, date, sig, avatar) 
VALUES('3', '$username','$password','$emailadd','$fullname', '$country', now(), '$defsig1', '$defimage1')"; 
mysql_query($query); 
echo "Account Created";
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
include "config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "SELECT defimage, defsig FROM prefs"; 
$result = mysql_query($query); 
while($r=mysql_fetch_array($result)) 
{ 
$defimage = $r["defimage"]; 
$defsig = $r["defsig"]; 
?>
<div class="side-headline"><b>Registration Form:</b></div>
<div align="center"><?=$errormessage;?><?=$errormessage2;?><?=$errormessage3;?><br>
<form method="post" action="register.php?a=skyreg">
<input type="hidden" name="defimage" value="<?=$defimage;?>">
<input type="hidden" name="defsig" value="<?=$defsig;?>">
<?=$fullnamebox;?>Full Name:<br><input type="text" name="fullname" value="<?=$fullname;?>"><br>&nbsp;<?=$fullnamebox1;?>
<?=$usernamebox;?>Username:<br><input type="text" name="username" value="<?=$username;?>"><br>&nbsp;<?=$usernamebox1;?>
<?=$pword1box;?>Password:<br><input type="password" name="password1"><br>&nbsp;<?=$pword1box1;?>
<?=$pword2box;?>Retype Password:<br><input type="password" name="password2"><br>&nbsp;<?=$pword2box1;?>
<?=$emailaddbox;?>E-mail Address:<br><input type="text" name="emailadd" value="<?=$emailadd;?>"><br>&nbsp;<?=$emailaddbox1;?>
<?=$countrybox;?>Country:<br><input type="text" name="country" value="<?=$country;?>"><br>&nbsp;<?=$countrybox1;?>
<input type="reset" value="Clear Form"> <input type="submit" value="Register">
</form>
</div>
<? 
} 
mysql_close($db); 
} else {  } 



include("footer.php"); 


?>
