<?php

if($_POST['sent_data']) {
	
	$mysql_host		= $_POST['mysql_host'];
	$mysql_db		= $_POST['mysql_db'];
	$mysql_username	= $_POST['mysql_username'];
	$mysql_password	= $_POST['mysql_password'];
	
	$conn = @mysql_connect($mysql_host, $mysql_username, $mysql_password);
	
	if($conn) {
		
		mysql_select_db($mysql_db, $conn);
		
		$mysql_error	= "";
		
		// relation_file2category
		$sql = "
		CREATE TABLE `relation_file2category` (
		  `file_id` int(4) unsigned NOT NULL default '0',
		  `category_id` int(4) unsigned NOT NULL default '0'
		) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;
		";
		$mysql_result = mysql_query($sql, $conn);
		
		// relation_group2category
		$sql = "
		CREATE TABLE `relation_group2category` (
		  `group_id` int(4) unsigned NOT NULL default '0',
		  `category_id` int(4) unsigned NOT NULL default '0'
		) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;
		";
		$mysql_result = mysql_query($sql, $conn);
		
		// relation_user2group
		$sql = "
		CREATE TABLE `relation_user2group` (
		  `user_id` int(4) unsigned NOT NULL default '0',
		  `group_id` int(4) unsigned NOT NULL default '0'
		) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;
		";
		$mysql_result = mysql_query($sql, $conn);
		
		// user_category
		$sql = "
		CREATE TABLE `user_category` (
		  `category_id` int(4) unsigned NOT NULL auto_increment,
		  `category_subof` int(4) unsigned default '0',
		  `category_name` varchar(128) collate latin1_german1_ci default NULL,
		  PRIMARY KEY  (`category_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;
		";
		$mysql_result = mysql_query($sql, $conn);
		
		// user_files
		$sql = "
		CREATE TABLE `user_files` (
		  `file_id` int(4) unsigned NOT NULL auto_increment,
		  `file_name` varchar(128) collate latin1_german1_ci default NULL,
		  `file_desc` text collate latin1_german1_ci,
		  `file_source` varchar(128) collate latin1_german1_ci default NULL,
		  `file_date` datetime default NULL,
		  `file_size` int(32) unsigned NOT NULL default '0',
		  PRIMARY KEY  (`file_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;
		";
		$mysql_result = mysql_query($sql, $conn);
		
		// user_group
		$sql = "
		CREATE TABLE `user_group` (
		  `group_id` int(4) unsigned NOT NULL auto_increment,
		  `group_name` varchar(128) collate latin1_german1_ci default NULL,
		  PRIMARY KEY  (`group_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;
		";
		$mysql_result = mysql_query($sql, $conn);
		
		// user_profile
		$sql = "
		CREATE TABLE `user_profile` (
		  `user_id` int(4) unsigned NOT NULL auto_increment,
		  `user_role` enum('user','admin') collate latin1_german1_ci default 'user',
		  `user_username` varchar(128) collate latin1_german1_ci default NULL,
		  `user_password` varchar(128) collate latin1_german1_ci default NULL,
		  `user_email` varchar(128) collate latin1_german1_ci default NULL,
		  `user_form` enum('mr','mrs') collate latin1_german1_ci default 'mr',
		  `user_firstname` varchar(128) collate latin1_german1_ci NOT NULL default '',
		  `user_lastname` varchar(128) collate latin1_german1_ci NOT NULL default '',
		  `user_company` varchar(128) collate latin1_german1_ci default NULL,
		  `user_registered` datetime default '0000-00-00 00:00:00',
		  `user_status` enum('active','inactive') collate latin1_german1_ci default 'active',
		  PRIMARY KEY  (`user_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;
		";
		$mysql_result = mysql_query($sql, $conn);
		
		// user_profile data
		$sql = "
		INSERT INTO `user_profile` (`user_id`, `user_role`, `user_username`, `user_password`, `user_email`, `user_form`, `user_firstname`, `user_lastname`, `user_company`, `user_registered`, `user_status`) VALUES(\"1\", \"admin\", \"admin\", \"21232f297a57a5a743894a0e4a801fc3\", \"deine@email.com\", \"mr\", \"Vorname\", \"Nachname\", \"Firma\", \"2005-08-04 09:57:34\", \"active\");
		";
		$mysql_result = mysql_query($sql, $conn);
		
		// website_config
		$sql = "
		CREATE TABLE `website_config` (
		  `config_key` varchar(128) collate latin1_german1_ci default NULL,
		  `config_value` varchar(128) collate latin1_german1_ci default NULL
		) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;
		";
		$mysql_result = mysql_query($sql, $conn);
		
		$mysql_error = mysql_error();
	}
	else {
		
		$mysql_error = mysql_error();
	}
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>FileManager - Installation</title>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
	<script language="JavaScript" type="text/javascript" src="system/resources/scripts/javascripts.js"></script>
	<link rel="stylesheet" type="text/css" href="system/resources/css/stylesheets.css">
</head>

<body>

<div id="content">
	<?php
	echo "
	<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\" bgcolor=\"#E9EBF4\">
		<tr>
			<td><b>Installation - MySQL Verbindungsdaten</b></td>
		</tr>
	</table>
	<br>
	";
	if(!isset($_POST['sent_data'])) {
		
		echo "
		<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\">
			<tr>
				<td valign=\"top\">
					<form action=\"install.php\" method=\"post\">
						<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\">
							<tr>
								<td>Host:</td>
								<td><img src=\"system/resources/images/0.gif\" width=\"10\" height=\"1\" border=\"0\" alt=\"\"></td>
								<td><input type=\"text\" name=\"mysql_host\" style=\"width:200px;\"></td>
							</tr>
							<tr>
								<td>Datenbank:</td>
								<td><img src=\"system/resources/images/0.gif\" width=\"10\" height=\"1\" border=\"0\" alt=\"\"></td>
								<td><input type=\"text\" name=\"mysql_db\" style=\"width:200px;\"></td>
							</tr>
							<tr>
								<td>Benutzername:</td>
								<td><img src=\"system/resources/images/0.gif\" width=\"10\" height=\"1\" border=\"0\" alt=\"\"></td>
								<td><input type=\"text\" name=\"mysql_username\" style=\"width:200px;\"></td>
							</tr>
							<tr>
								<td>Passwort:</td>
								<td><img src=\"system/resources/images/0.gif\" width=\"10\" height=\"1\" border=\"0\" alt=\"\"></td>
								<td><input type=\"text\" name=\"mysql_password\" style=\"width:200px;\"></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td><img src=\"system/resources/images/0.gif\" width=\"10\" height=\"1\" border=\"0\" alt=\"\"></td>
								<td><input type=\"submit\" name=\"sent_data\" value=\"Installieren\"></td>
							</tr>
						</table>
					</form>
					<br>
				</td>
			</tr>
		</table>
		";
	}
	if(isset($_POST['sent_data']) && $mysql_result == true && $mysql_error == "") {
		
		echo "
		Die Tabellen wurden erfolgreich angelegt.<br>
		Konfigurieren Sie nun die Applikation:
		<ul>
			<li>Tragen sie in 'filemanager/system/classes/config.class.php' Die Datenbank-Verbindung ein.</li>
			<li>Ersteilen Sie dem Verzeichnis 'filemanager/data/' Schreibrechte.</li>
		</ul>
		Dann können Sie sich mit admin / admin in den Administrationsbereich einloggen.
		<br><br>
		<a href=\"index.php?action=main.login\">Login</a>
		<br><br>
		";
	}
	if(isset($_POST['sent_data']) && $mysql_error != "") {
		
		echo "
		Die Tabellen konnten nicht angelegt werden.<br>
		MySQL-Error: $mysql_error
		<br><br>
		<a href=\"javascript:history.back();\">Zurück</a>
		<br><br>
		";
	}
	?>
</div>

</body>
</html>