<?php
include('configure.php');
if ($admin_password == $valid_password){$valid = 1;}
else{$valid = 0;}
if ($submit_login){
if ($login_password == $admin_password){
setcookie("valid_password", "$login_password", time()+300);
$valid = 1;}
else{
$failure="Incorrect password!";
$valid=0;}}
if (!$valid){
echo $failure;
?>

<html>
<head>
<title>SFLinks delete a link</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>

<form method="post" action="<?=$PHP_SELF?>">
<div class="loginform">
<input type="hidden" name="submit_login" value="1">

<p align="center"><b>Please enter your administration password</b></p>

<p align="center"><input type="password" name="login_password" size="20"></p>
<p align="center"><input type="submit" value="log in"></p>
</div>


<?
}if ($valid){
?>

<html>
<head>
<title>SFLinks delete a link</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>

<?php 

print "<h1>Delete a Link</h1>";

print"<form method='post' action='$PHP_SELF'>

<fieldset>
	<legend>Delete a Record</legend>
	
<p>Note: Only do this if you're absolutely sure.  There is no confirmation of deletion, nor is there any way to reverse the process.  Be sure you have the correct ID before continuing!!!!<br>
<b><a href=\"#\" onClick=\"list=window.open('showids.php','list','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=350,height=500'); return false;\">Click here to locate the appropriate ID number</a></b></p>

<p><b>ID:</b> <input type='text' name='id' size='10'></p>

<p>Press 'Submit' - do not hit 'Enter'</p>

</fieldset>

<input type='submit' name='submit' value='Submit'>

</form>";

if($submit) {

include('configure.php');


$sql = "DELETE FROM $table WHERE id = $id";
$result = mysql_query($sql) or print ("Can't delete from table $table.<br />" . $sql . "<br />" . mysql_error());

if ($result != false)
{
print "<h2>Success!  You have deleted entry #$id!</h2>";
}

mysql_close();
}
include('footer.php');
}

?>