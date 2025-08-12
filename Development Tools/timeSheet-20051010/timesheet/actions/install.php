<?php

$_SESSION['install'] = $_REQUEST;

if (!chmod ("config.php",0600))
{
	header("Location: index.php?page=install&msg=".base64_encode("red|Please make sure your permissions are correect for config.php (chmod 666 config.php)"));
	exit();
}

//..... Write config.php file with new results
$fd = fopen("config.php","w");
fputs($fd,"<?php \n\n");
fputs($fd,"/*************************/\n");
fputs($fd,"/****  AUTO CONFIGGED ****/\n");
fputs($fd,"/*************************/\n");
foreach($_REQUEST['conf'] as $key=>$value)
{
	fputs($fd,"\$config['$key']\t='$value';\n");
}
fputs($fd,"\n?>");
fclose($fd);

$config = $_REQUEST['conf'];


@mysql_pconnect($config['db_host'],$config['db_user'],$config['db_pass']);
if (mysql_errno())
{
	header("Location: index.php?page=install&msg=".base64_encode("red|Please check your mysql database settings:<br />".mysql_error()));
	exit();
}

$formVals = $_REQUEST['form'];

if (!is_email($formVals['email']))
{
	$error .= "Invalid email address\n";
}

if(strlen($formVals['password']) < 4)
{
	$error .= "Password must be > 4 characters.\n";
}
if(strlen($formVals['password']) < 2)
{
	$error .= "First name must be at least 2 characters.\n";
}
if(strlen($formVals['lastname']) < 2)
{
	$error .= "Last name must be at least 2 characters.\n";
}

//...... Do we have an error?
if (strlen($error))
{
	if ($_REQUEST['REF'])
	{
		header("Location: ".base64_decode($_REQUEST[REF])."&msg=".base64_encode("red|".nl2br($error)));
	}
	else header("Location: $_SERVER[HTTP_REFERER]&msg=".base64_encode("red|".nl2br($error)));

}
else
{
	//...... Creates our initial database
	mysql_query("CREATE DATABASE $config[db_name]");
	mysql_select_db($config['db_name']);

	if (mysql_error() && mysql_errno() != 1007)
	{
		header("Location: index.php?page=install&msg=".base64_encode("red|Please check your mysql database settings:<br />".mysql_error()));
		exit();
	}

	//...... Loads the sql data file
	$fd = fopen("sql/timesheet.sql","r");
	while($Q = fgets($fd,2048))
	{
		
		$qry[$x] .= $Q;
		if (preg_match("~TYPE=MyISAM;~",$Q))
		{
			$x++;
		}
	}
	
	//..... Gets rid of empty query
	unset($qry[$x]);

	//..... Go through each query and send them to mysql
	foreach($qry as $Q)
	{
		mysql_query($Q);		
		if (mysql_error())
		{
			header("Location: index.php?page=install&msg=".base64_encode("red|Please check your mysql database settings:<br />".mysql_error()));
			exit();
		}
	}

	//...... Creates the initial user
	$Q="INSERT INTO users 
		SET id='1',
		firstname='".addslashes(ucfirst($formVals['firstname']))."',
		lastname='".addslashes(ucfirst($formVals['lastname']))."',
		email='".addslashes(strtolower($formVals['email']))."',
		password='".addslashes($formVals['password'])."',
		dateAdded=NOW()";
	mysql_query($Q);
	print mysql_error();

	header("Location: index.php?page=login&msg=".base64_encode("green|<font color=white>Installation Complete!<Br />You may now log in.</font>"));
}


?>
