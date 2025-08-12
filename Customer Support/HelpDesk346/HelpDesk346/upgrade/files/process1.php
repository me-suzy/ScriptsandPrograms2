<?php
	// assign the variables
	$dbhost = $_POST['dbhost'];
	$dbname = $_POST['dbname'];
	$dbuser = $_POST['dbuser'];
	$dbpass = $_POST['dbpass'];
	$prefix = preg_replace('/_*$/', '', $_POST['prefix']);
	
	// attempt to make the connection and select the database, a failure means break down and we can start the session
	if (mysql_connect($dbhost, $dbuser, $dbpass) && mysql_select_db($dbname))
	{
		session_start();
		$_SESSION['dbhost'] = $dbhost;
		$_SESSION['dbname'] = $dbname;
		$_SESSION['dbuser'] = $dbuser;
		$_SESSION['dbpass'] = $dbpass;
		$_SESSION['prefix'] = $prefix;
        
        // write the data out to the config file, one level above
        $fp = fopen('../config.php', 'w');
        fwrite($fp, "<?php\n
				define('DB_PREFIX', '".$prefix."');\n
				define('DB_HOST', '" . $dbhost . "');\n
				define('DB_UNAME', '" . $dbuser . "');\n
				define('DB_DBNAME', '" . $dbname . "');\n
				define('DB_PASS', '" . $dbpass . "');
			?>");
		
		// create an array of table names
		$res = mysql_query("show tables;");
		while ($r = mysql_fetch_row($res))
			$arrTable[] = $r[0];
			
		// process the various tables
		if (in_array($prefix . "_priorities", $arrTable)) {
			$cmd = "drop table " . $prefix . "_priorities";
			mysql_query($cmd) or die(mysql_error());	
		}
		
		if (in_array($prefix . "_status", $arrTable))
			exit("Invalid State - Status Table Exists - For Upgrade it Cannot");
			
		if (in_array($prefix . "_categories", $arrTable))
			exit("Invalid State - Catagory Table Exists = For Upgarde it cannot");
			
		// create the tables
		$cmd  = "create table " . $prefix . "_categories (";
		$cmd .= "id int not null auto_increment primary key,";
		$cmd .= "name varchar(30) not null default '',";
		$cmd .= "priority int not null default '0')";
		mysql_query($cmd) or die(mysql_error());
		
		$cmd  = "create table " . $prefix . "_priorities (";
		$cmd .= "pid int not null auto_increment primary key,";
		$cmd .= "priority varchar(50) not null default '',";
		$cmd .= "severity int not null default '1')";
		mysql_query($cmd) or die(mysql_error());
		
		$cmd  = "create table " . $prefix . "_status (";
		$cmd .= "id int not null auto_increment primary key,";
		$cmd .= "name varchar(255) not null default '',";
		$cmd .= "position int not null default '0',";
		$cmd .= "icon varchar(10) not null default '',";
		$cmd .= "color varchar(10) not null default '')";
		mysql_query($cmd) or die(mysql_error());
		
?>
<html>
	<head>
		<title>Performed Stage 1 of 6 of the Upgrade Process</title>
	</head>
	
	<body>
		<b>Status Report</b><br/>
		I have checked for the information that would indicate an improper upgrade action. Having passed these conditions I have prepared the
		database to recieve the new data to support The Helpdesk Reloaded Version 4.  Click Next to continue with the upgarde.<br/>
		<a href="../priority.php">Next >>></a>
	</body>
</html>
</html>
<?php
	}
	else {
		echo "Incorrect Connection or Database Information - Use the 'Back' button to reenter the data";
	}
?>