<?
if (!isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['passworda']) || !isset($_POST['char']) || !isset($_POST['email']) || !isset($_POST['rec']))
{
	echo '<div align="center">Please enter your registration details.</div>';
}
elseif ($_POST['password'] != $_POST['passworda'])
{
	echo '<div align="center">Your passwords do not match.</div>';
}
elseif (strlen($_POST['username'])<4 || strlen($_POST['username'])>16 || strlen($_POST['password'])<4 || strlen($_POST['password'])>16 || strlen($_POST['passworda'])<4 || strlen($_POST['passworda'])>16)
{
	echo '<div align="center">Username and password must be between 4 and 16 characters.</div>';
}
elseif (strlen($_POST['char'])>30)
{
	echo '<div align="center">Character names must be less than 30 characters.</div>';
}
elseif (strlen($_POST['email'])<5 || strlen($_POST['passworda'])>50)
{
	echo '<div align="center">Email addresses must be between 5 and 50 characters.</div>';
}
elseif (strlen($_POST['lvl'])>3 || $_POST['lvl']>100)
{
	echo '<div align="center">Lvl range must be between 1 and 100.</div>';
}
else {
	if (strlen($_POST['char'])<1)
	{
		$setchar = 'not set';
	}
	else {
		$setchar = $_POST['char'];
	}
	if (strlen($_POST['charclass'])<1)
	{
		$setclass = 'not set';
	}
	else {
		$setclass = $_POST['charclass'];
	}
	if (strlen($_POST['race'])<1)
	{
		$setrace = 'not set';
	}
		else {
		$setrace = $_POST['race'];
	}
	if (strlen($_POST['lvl'])<1)
	{
		$lvl = '1';
	}
	else
	{
		$lvl = $_POST['lvl'];
	}
	$checkuser = "SELECT username FROM users where username = '$_POST[username]'";
	$resultcheck = mysql_query($checkuser, $db_conn) or die ('query failed');
	if (mysql_num_rows($resultcheck) >0)
	{
		echo '<div align="center" class="log">The username you entered already exists, please select another username.</div>';
	}
	else {
	$query1 = "INSERT INTO users (username,passwd,email,rec) VALUES ('$_POST[username]','$_POST[password]','$_POST[email]','$_POST[rec]')"; 
   $result1 = mysql_query($query1, $db_conn) or die("query [$query] failed: ".mysql_error()); 
   $query2 = "INSERT INTO characters (username,charactername,level,charclass,race,main) VALUES ('$_POST[username]','$setchar','$lvl','$setclass','$setrace','1')";
   $result2 = mysql_query($query2, $db_conn) or die("query [$query2] failed: ".mysql_error()); 
   if (isset($result1) && isset($result2))
	{
	   echo '<div align="center">Your information has been entered into the database, admin will process your application shortly.</div>';
	}
	}
}


echo '<div align="center" class="log">';
echo '<form action="index.php?page=reg" method="post">';
echo 'User Name:<br />';
echo '<input type="text" size="16" name="username" style="font-size:10px;border:solid 1px";><br />';
echo 'Password:<br />';
echo '<input type="text" size="16" name="password" style="font-size:10px;border:solid 1px";><br />';
echo 'Password again:<br />';
echo '<input type=text name="passworda"  size="16" style="font-size:10px;border:solid 1px;"><br />';
echo 'Email:<br />';
echo '<input type=text name="email" size="50" style="font-size:10px;border:solid 1px;"><br />';
echo 'How did you find the site?:<br />';
echo '<input type="text" name="rec" style="font-size:10px;border:solid 1px;"><br />';
echo 'Main Character:<br />';
echo '<input type=text name="char" size="30" style="font-size:10px;border:solid 1px;"><br />';
echo 'Level:<br />';
echo '<input type=text name="lvl" size="3" style="font-size:10px;border:solid 1px;"><br />';
echo '<br />';
echo 'Class:<br />';
echo '<input type=text name="charclass" size="30" style="font-size:10px;border:solid 1px;"><br />';
echo '<br />';
echo 'Race:<br />';
echo '<input type=text name="race" size="30" style="font-size:10px;border:solid 1px;"><br />';
echo '<br />';
echo '<input type=image src="buttons\reg.gif" name="reg" value="Register" style="font-size:10px;">';
echo '</form>';
echo '</div>';


?>