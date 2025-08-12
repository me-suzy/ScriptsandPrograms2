<?php

// << -------------------------------------------------------------------- >>
// >> EXO Helpdesk Installation File
// >>
// >> INSTALL . PHP File - HelpDesk Installation File
// >> Started : November 18, 2003
// >> Version : 1.0
// >> Edited  : February 26, 2004
// << -------------------------------------------------------------------- >>

error_reporting ( E_ERROR );
define( 'INSTALL', 1 );

ob_start();

echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<META http-equiv=Content-Type content="text/html; charset=windows-1252">
	<title>:::EXO PHPDesk Installation:::</title>
	<link rel="stylesheet" href="tpl/Blue/style.css" type="text/css">
</head>
<body>
<div align="center">
<table><tr><td background="tpl/Blue/images/bg_desk.jpg"><img align="top" src="tpl/Blue/images/head.jpg"></td></table><br />';

if($_GET['step'] == NULL)
{
	echo "<b>Welcome to Installation of EXO PHPDesk.</b><br />";
	echo "Make sure you have already done the following:<br />
		- Have your mySQL database name.<br />
		- Have your mySQL username and password.<br />
		- Chmod chat logs dir to 0777. Its 'logs' by default.<br />
		- Chmod attachments dir to 0777. Its 'attachments' by default.<br />
		- Chmod the main dir of EXO PHPDesk to 0777<br /><br />
		If you have the above information then Please click below to proceed. <br />";
	echo "<a href='".$_SERVER['PHP_SELF']."?step=1'>>> Next Step >></a>";
}
elseif($_GET['step'] == '1')
{
	if($_POST['submit'] == "" && !file_exists('db_conf.php'))
	{
		echo "Please fill the following information correctly. It will attempt to create \"db_conf.php\".";
		echo "<table><form method='post' action='".$_SERVER['PHP_SELF']."?step=1'>";
		echo "<tr><td>mySQL Host : </td><td><input type='text' name='host'></td></tr>
			 <tr><td>mySQL Username: </td><td><input type='text' name='name'></td></tr>
			 <tr><td>mySQL Password: </td><td><input type='password' name='pass'></td></tr>
			 <tr><td>mySQL Database: </td><td><input type='text' name='database'></td></tr>
			 <tr><td>mySQL Extension: </td><td><input type='text' name='extension' value='phpdesk_'></td></tr>
			 <tr><td></td><td><input type='submit' value='submit' name='submit'></td></tr></table>";
	}
	else
	{
		$print = 'Please click below to move to the next step.<br />';
		$print.= "<a href='".$_SERVER['PHP_SELF']."?step=2'>>> Next Step >></a>";
		if(file_exists('db_conf.php'))
		{
			echo '"db_conf.php" already exists. Please edit it with right info, if you havn\'t already did it.<br />';
			echo $print;
		}
		elseif(!$_POST['host'] || !$_POST['name'] || !$_POST['database'] || !$_POST['extension'] )
		{
			echo "Please fill the information correctly and re-submit the form. <br />";
		}
		else
		{
			$config= "<?php\n$"."host = '{$_POST['host']}';\n$"."user = '{$_POST['name']}';\n$"."pass = '{$_POST['pass']}';\n$"."database = '{$_POST['database']}';\n$"."DB_EXT   = '{$_POST['extension']}';\n?>";
			if($fp = fopen('db_conf.php', 'w'))
			{
				fwrite($fp, $config);
				fclose($fp);
				echo $print;
			}
			else
			{
				echo "Can't open file db_conf.php for writing. Please chmod the main dir to 0777.<br />";
			}

		}
	}
}
elseif($_GET['step'] == '2' && file_exists('db_conf.php'))
{
	include_once('conf.php');

	$db = new mySQL();
	$db->db['db_user'] = $user;
	$db->db['db_host'] = $host;
	$db->db['db_pass'] = $pass;
	$db->db['db_datab'] = $database;
	$db->db['db_ext']	= ( empty( $DB_EXT )) ? 'phpdesk_' : $DB_EXT;
	$db->connect();
	
	$fp = fopen('schema/mysql.sql', 'r');
	$schema = fread($fp, filesize('schema/mysql.sql'));
	fclose($fp);
	
	$sqls = explode("\n", $schema);
	foreach ($sqls as $sql)
	{
		$pos = strpos($sql, 'phpdesk_');
		$table = substr($sql, $pos);
		$pos = strpos($table, '`');
		$table = substr($table, 0, $pos);

		if($db->query($sql))
		{
			if ( preg_match("/CREATE TABLE (\S+) \(/", $sql ))
			{
				echo 'Table Created Successfully: '.$table."<br />\n";
			}
		}
		else
		{
			echo 'Cannot Create Table: '.$table."<br />\n";
		}
	}
	$print = 'Please click below to move to the next step.<br />';
	$print.= "<a href='".$_SERVER['PHP_SELF']."?step=3'>>> Next Step >></a>";
	echo $print;
}
elseif($_GET['step']=='3')
{
	if($_POST['submit'] == "")
	{
		$path = str_replace('install.php', '', $_SERVER['SCRIPT_FILENAME']);
		$url = "http://".$_SERVER['HTTP_HOST'].str_replace('install.php','',$_SERVER['PHP_SELF']);
	?>
<script Language="Javascript">
<!--

function change()
{
	if(document.configs.mailtype.value=="None")
	{
		document.configs.mailhost.disabled = true;		
		document.configs.mailuser.disabled = true;
		document.configs.mailpass.disabled = true;
	}
	else
	{
		document.configs.mailhost.disabled = false;	
		document.configs.mailuser.disabled = false;
		document.configs.mailpass.disabled = false;	
	}
}

//-->
</script>
<body onLoad="change();">	
<table>
 <tr><td height="22" colspan="2" class="tdup" background="tpl/Blue/images/bg_td.jpg">Configurations</td></tr>
 <form method="post" action="<? echo $_SERVER['PHP_SELF']; ?>?step=3" name="configs">
  <tr><td colspan="2">All the Directories and URLs must have a slash at end.</td></tr>
  <tr><td>Template Dir:</td><td><input type="text" size="40" name="tpldir" value="tpl/Blue/"></td></tr>
  <tr><td>Language File:</td><td><input type="text" size="40" name="langfile" value="<? echo $path; ?>lang/lang_en.php"></td></tr>
  <tr><td>HelpDesk URL:</td><td><input type="text" size="40" name="helpurl" value="<? echo $url; ?>"></td></tr>
  <tr><td>Site Name:</td><td><input type="text" size="40" name="sitename" value="EXO PHPDesk"></td></tr>
  <tr><td>Reply Email:</td><td><input type="text" size="40" name="remail"></td></tr>
  <tr><td>ChatLog Dir:</td><td><input type="text" size="40" name="chatdir" value="<? echo $path; ?>logs/"></td></tr>
  <tr><td>Registrations:</td><td><select name="registrations">
  <option value="Closed">Closed</option><option value="Open" selected>Open</option></select></td></tr>
  <tr><td>Member View Stats: 
  <br><font size="1">Allow Members to see server status?</font></td><td><select name="mem_stats">
  <option value="1" selected>Yes</option>
  <option value="0">No</option></select></td></tr>  
  <tr><td>Get Emails:</td><td><select name="mailtype" onChange="change();">^option^
  <option value="None">Disabled</option><option value="IMAP">IMAP</option>
  <option value="POP3">POP3</option></select></tr>
  <tr><td>Mail Server Address:</td><td><input type="text" name="mailhost" value="{mailhost}"></td></tr>  
  <tr><td>Mail Server User:</td><td><input type="text" name="mailuser" value="{mailuser}"></td></tr>
  <tr><td>Mail Server Pass:</td><td><input type="password" name="mailpass"></td></tr>
    
  <tr><td></td><td><input type="submit" name="submit" value="submit"></td></tr>
 </form>
</table>
	<?
	}
	else
	{
		if(!$_POST['tpldir'] || !$_POST['langfile'] || !$_POST['helpurl'] || !$_POST['sitename'] || !$_POST['remail'] || !$_POST['chatdir'] || !$_POST['registrations'])
		{
			echo "All fields are required. <br />";
		}
		else
		{
			include_once('conf.php');

			$db = new mySQL();
			$db->db['db_user'] = $user;
			$db->db['db_host'] = $host;
			$db->db['db_pass'] = $pass;
			$db->db['db_datab'] = $database;
			$db->db['db_ext']	= $DB_EXT;
			$db->connect();		

			$PASS = (!empty($_POST['mailpass'])) ? ", mailpass='".base64_encode($_POST['mailpass'])."'" : "";
			$END = ($_POST['mailtype']!='None') ? ", mailtype='".$_POST['mailtype']."', mailhost='".$_POST['mailhost']."',
				mailuser='".$_POST['mailuser']."'".$PASS : ", mailtype='None'";

			$sql = "INSERT INTO phpdesk_configs SET tpldir='".$_POST['tpldir']."', langfile='".$_POST['langfile']."',
			helpurl='".$_POST['helpurl']."', sitename='".$_POST['sitename']."', remail='".$_POST['remail']."',
			chatdir='".$_POST['chatdir']."', registrations='".$_POST['registrations']."', mem_serv = '" . 
			$_POST['mem_stats'] ."', st_announce = '1', at_allow = '1', at_dir = 'attachments/', 
			at_size = '512000', at_ext = '.gif, .jpg, .jpeg, .txt', at_prefix = '_'" . $END;
			
			if($db->query($sql))
			{
				$print = 'Please click below to move to the next step.<br />';
				$print.= "<a href='".$_SERVER['PHP_SELF']."?step=4'>>> Next Step >></a>";
				echo $print;
			}
		}
	}
}
elseif($_GET['step']=='4')
{
	include_once('conf.php');
	if($_POST['submit'] == "")
	{
		echo "Create An Administrator Account<br />";
		echo "<table><form method='post' action='".$_SERVER['PHP_SELF']."?step=4'>
			<tr><td>Admin UserName: </td><td><input type='text' name='name'></td></tr>\n
			<tr><td>Admin Password: </td><td><input type='password' name='pass'></td></tr>\n
			<tr><td>Admin Email: </td><td><input type='text' name='email'></td></tr>\n
			<tr><td></td><td><input type='submit' value='submit' name='submit'></td></tr>\n
			</form></table>\n";
	}
	else
	{
		if(!$_POST['name'] || !$_POST['pass'] || !$_POST['email'])
		{
			echo 'Please fill all the fields.<br />';
		}
		else
		{
			$db->query("INSERT INTO phpdesk_admin(id,name,pass,email) VALUES('1', '".$_POST['name']."',
			'".md5($_POST['pass'])."', '".$_POST['email']."')");
			$print  = 'Congratulations!! The setup was completed successfully. Please click below to go to the administration area.<br />';
			$print .= "<a href='admin.php'>>> Administration >></a>";
			$print .= "<br><br>Please delete the install.php file now.<br>";
			echo $print;			
		}
	}
}
ob_end_flush();
?>