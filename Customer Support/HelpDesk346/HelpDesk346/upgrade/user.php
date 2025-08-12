<?php
	session_start();
	mysql_connect($_SESSION['dbhost'], $_SESSION['dbuser'], $_SESSION['dbpass']);
	mysql_select_db($_SESSION['dbname']);
	
	$path = getcwd();
	chdir('..');
	
	include_once "./includes/classes.php";
	chdir($path);
	
	include_once "./files/process5.php";
?>
<html>
	<head>
		<title>Performing Stage 5 of 6 - User Modification</title>
	</head>
	
	<body>
		<div>
		With this new User Handling subsystem we have given individual phone numbers to each of the users registered by the system.  To make
		this process easier we are going to allow for the submission of a global phone number for all users that do not have a phone number
		stored for them. With the new system you can edit this phone number once it is stored.
		</div>
		
		<table align="center" cellpadding="0" cellspacing="1" border="0">
		<form method="post" action="">
			<tr>
				<td>Global Phone Number:&nbsp;</td>
				<td><input type="text" name="phone" size="11" maxlength="15" />
			</tr>
			<tr><td colspan="2" align="center">
				<input type="submit" name="submit" value="Proceed to Next Step" />
			</td></tr>
		</form>
		</table>
	</body>
</html>