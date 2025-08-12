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
<title>SFLinks add a link</title>
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
<title>SFLinks add a link</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>

<?php
	print"<h1>Add Link</h1>";

print"<form method='post' action='$PHP_SELF'>

<fieldset>
	<legend>Add a Link to the Database</legend>

<table>
	<tr>
		<th>Url</th>
		<th>Name/Description</th>
		<th>Email of owner</th>
		<th>Image</th>
	</tr>
	<tr>
		<td><input type='text' name='url' size='25' value='http://'></td>
		<td><input type='text' name='name' size='25'></td>
		<td><input type='text' name='email' size='25'></td>
		<td><input type='text' name='img' size='15'</td>
	</tr>
</table>
<br /><br />

</fieldset>

<input type='submit' name='submit' value='Submit'>

</form>";

if($submit) {

include('configure.php');

$sql = "INSERT INTO $table (name,url,email,img) VALUES ('$name','$url','$email','$img')";
$result = mysql_query($sql) or print ("Can't insert into table $table.<br />" . $sql . "<br />" . mysql_error());

if ($result != false)
{
print "<h2>Your link has successfully been entered into the database!</h2>";
}

mysql_close();
}

include('footer.php'); 
}

?>