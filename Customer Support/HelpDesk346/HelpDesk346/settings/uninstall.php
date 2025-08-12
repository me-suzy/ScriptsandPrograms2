<?php
	session_start();
	$path = getcwd();
	chdir('..');
	
	include_once "checksession.php";
	include_once "./includes/settings.php";
	
	chdir($path);
	if (isset($_POST['cancel'])) {
		header("Location: ../actmgt.php");		//exit the presented action	
	}
?>
<html>
	<head>
		<title>Helpdesk Uninstall Application</title>
		<link rel="stylesheet" type="text/css" href="../style.css" />
	</head>
	
	<body>
	<table cellpadding="0" cellspacing="0" border="0">
		<tr><td colspan="2" align="center">
		<?php
			$ppath = '../';
			if ($OBJ->get('navigation') == 'B') {
				include_once "../dataaccessheader.php";
			}
			else {
				include_once "../textnavsystem.php";
			}
		?>
		</td></tr>
	</table>
	<form method="post" action="">
	<?php
		if (isset($_POST['confirm'])) {
			//open the connection
			mysql_connect(DB_HOST, DB_UNAME, DB_PASS);
			mysql_select_db(DB_DBNAME);
			
			print "<pre>";
			$q = "show tables;";
			$s = mysql_query($q) or die(mysql_error());
			while ($r = mysql_fetch_row($s))
			{
				if (preg_match('/^' . DB_PREFIX . '/', $r[0])) {
					mysql_query("drop table " . $r[0]);	
					echo "Dropped Table: " . $r[0] . "\n";
				}	
			}
			
			//close the connection
			mysql_close();
			
			//blow away the config file
			@unlink("../config.php");
			echo "Removed Configuration File\n";
			print "</pre>";
		}
		else {
	?>
		<div align="center" style="font-weight:bold; width:400px">
			All Data will be Lost - Are you sure you wish to Continue?<br/>
			<input type="submit" name="confirm" value="Yes" />&nbsp;
			<input type="submit" name="cancel" value="No" />
		</div>
	<?php
		}
	?>
	</form>
	</body>
</html>