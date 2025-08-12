<?php
if ($user_id!=1) {
	echo "<center><b>Access Denied</b></center>";
	exit;
}

if ($Submit==true) {
	// Turn off all error reporting
	error_reporting(0);
	
	// Check the form
	if ($admin_username==false OR $admin_password==false OR $library_name==false OR $library_owner==false OR $library_email==false) {
		echo "<html><body><script language=javascript1.1>alert('Please fill in the form'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
	}
	
	if ($admin_password!=$admin_password2) {
		echo "<html><body><script language=javascript1.1>alert('Admin passwords do not match'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;	
	}

	// Connect to MySQL database (you could change this to mysql_pconnect for a persistent connection)
	$db = @mysql_connect($server, $db_user, $db_pass) or die("<center>Unable to connect to database host on '".$server."' with user '".$db_user."'<br />"."Please check your settings are correct!<br /><a href=\"javascript:history.back()\">Back</a></center>");
	mysql_select_db($database) or die("<center>Connected to database host, but you need to create your database '".$database."' first!<br /><a href=\"javascript:history.back()\">Back</a></center>"); ;
	
	// Make config file function
	function make_config_file()
	{
		global $server, $database, $db_user, $db_pass, $mysql_pre, $mysql_items, $mysql_students, $mysql_loans, $mysql_admin, $library_name, $library_owner, $library_email, $overdue_fine;	
		
		$filename = 'library.config.inc.php';
		$content = '<?php $server = \''.$server.'\'; $db_user = \''.$db_user.'\'; $db_pass = \''.$db_pass.'\'; $database = \''.$database.'\'; $mysql_pre = \''.$mysql_pre.'\'; $mysql_items = \''.$mysql_items.'\'; $mysql_students = \''.$mysql_students.'\'; $mysql_loans = \''.$mysql_loans.'\'; $mysql_admin = \''.$mysql_admin.'\'; $db = mysql_connect($server, $db_user, $db_pass); mysql_select_db($database,$db); $library_name = \''.$library_name.'\'; $library_owner = \''.$library_owner.'\'; $library_email = \''.$library_email.'\'; $overdue_fine = '.$overdue_fine.';    $date = date("l, d F Y, g:i A",time()); $current_day = date(d); $current_month = date(m); $current_year = date(Y); ?>';
				
		if (!touch ($filename)) {
			echo "<center>Cannot make config file ($filename), please make sure this directory the script is in, has been CHMOD to 777 and that the $filename is CHMOD to 755<br /><a href=\"javascript:history.back()\">Back</a></center>";
			exit;
		}
		@chmod($filename, 0777);

		// Let's make sure the file exists and is writable first.
		if (is_writable($filename)) {
		
			// In our example we're opening $filename in append mode.
			// The file pointer is at the bottom of the file hence 
			// that's where $somecontent will go when we fwrite() it.
			if (!$handle = fopen($filename, 'a')) {
				 print "<center>Cannot open file ($filename)<br /><a href=\"javascript:history.back()\">Back</a></center>";
				 exit;
			}
		
			// Write $somecontent to our opened file.
			if (!fwrite($handle, $content)) {
				print "<center>Cannot write to file ($filename)<br /><a href=\"javascript:history.back()\">Back</a></center>";
				exit;
			}
					
			fclose($handle);
							
		} else {
			print "<center>The file $filename is not writable<br /><a href=\"javascript:history.back()\">Back</a></center>";
		}
	}
		
	// Check if there is a database in place
	$sqlAnyAdmins = mysql_query("SELECT * FROM $mysql_pre$mysql_admin LIMIT 0, 1",$db);	
	if ($resultAnyAdmins = mysql_fetch_array($sqlAnyAdmins)) {
		// Database is already there, make config file
		make_config_file();
	}
	else
	{
		// Database isn't there make it
		///////////////////////////////////		
		$sql="CREATE TABLE $mysql_pre$mysql_admin (
		  id int(5) NOT NULL auto_increment,
		  username varchar(16) NOT NULL default '',
		  password varchar(32) NOT NULL default '0',
		  name varchar(30) NOT NULL default '',
		  email longtext NOT NULL,
		  UNIQUE KEY username (username),
		  UNIQUE KEY id (id)
		) TYPE=MyISAM;";
		@mysql_query($sql) or die(mysql_error());
		///////////////////////////////////
		$sql="CREATE TABLE $mysql_pre$mysql_items (
		  id int(6) NOT NULL auto_increment,
		  name varchar(30) NOT NULL default '',
		  description longtext NOT NULL,
		  condition varchar(20) NOT NULL default '',
		  price int(11) NOT NULL default '0',
		  status varchar(6) NOT NULL default 'In',
		  last_student_id int(6) NOT NULL default '0',
		  notes longtext NOT NULL,
		  edited_by longtext NOT NULL,
		  UNIQUE KEY id (id)
		) TYPE=MyISAM;";
		@mysql_query($sql) or die(mysql_error());
		///////////////////////////////////
		$sql="CREATE TABLE $mysql_pre$mysql_loans (
		  id int(6) NOT NULL auto_increment,
		  item_id longtext NOT NULL,
		  student_id int(6) NOT NULL default '0',
		  date_out_day int(2) NOT NULL default '0',
		  date_out_month int(2) NOT NULL default '0',
		  date_out_year int(4) NOT NULL default '0',
		  date_in_day int(2) NOT NULL default '0',
		  date_in_month int(2) NOT NULL default '0',
		  date_in_year int(4) NOT NULL default '0',
		  status longtext NOT NULL,
		  notes longtext NOT NULL,
		  edited_by longtext NOT NULL,
		  UNIQUE KEY id (id)
		) TYPE=MyISAM;";
		@mysql_query($sql) or die(mysql_error());
		///////////////////////////////////
		$sql="CREATE TABLE $mysql_pre$mysql_students (
		  id int(6) NOT NULL auto_increment,
		  name varchar(35) NOT NULL default '',
		  dob_day int(2) default NULL,
		  dob_month int(2) default NULL,
		  dob_year int(4) default NULL,
		  address longtext NOT NULL,
		  post_code varchar(7) NOT NULL default '',
		  telephone varchar(12) NOT NULL default '0',
		  email longtext NOT NULL,
		  nus varchar(16) NOT NULL default '0',
		  cname longtext NOT NULL,
		  ctutor varchar(35) NOT NULL default '',
		  last_year int(4) default NULL,
		  notes longtext NOT NULL,
		  edited_by longtext NOT NULL,
		  UNIQUE KEY id (id)
		) TYPE=MyISAM;";
		@mysql_query($sql) or die(mysql_error());
		///////////////////////////////////
		$admin_password = md5($admin_password);
		$sql="INSERT INTO $mysql_pre$mysql_admin VALUES (1, '$admin_username', '$admin_password', '$admin_name', '$admin_email');";
		@mysql_query($sql) or die(mysql_error());
		///////////////////////////////////
		make_config_file();			
	}
	echo "<center>Library Setup Complete!<br><a href=$PHP_SELF>Continue</a></center>";
	exit;
}

if ($act=="edit_config") {
	$filename = 'library.config.inc.php';
	if (!rename("$filename", "bak-library.config.inc.php")) {
		echo "<center>Cannot rename config file ($filename), please make sure this directory the script is in, has been CHMOD to 777 and that the $filename is CHMOD to 755 or rename the config file yourself<br /><a href=\"javascript:history.back()\">Back</a></center>";
		exit;	
	}
	setcookie ("PHPLibrary[username]");
	setcookie ("PHPLibrary[password]");
	echo "<center><a href=$PHP_SELF>Continue</a></center>";
	exit;
}
?>
<div align="center"><strong>MySQL Database Setup</strong></div>
<form name="form1" method="post" action="">
  <div align="center">
    <table border="1" cellpadding="2" cellspacing="0" bordercolor="#FFFFCC">
      <tr> 
        <td class=color3>Database Host:</td>
        <td class=color2><input name="server" type="text" id="server" value="localhost"></td>
      </tr>
      <tr> 
        <td class=color3>Database Name:</td>
        <td class=color2><input name="database" type="text" id="database" value="library"></td>
      </tr>
      <tr> 
        <td class=color3>Database Username:</td>
        <td class=color2><input name="db_user" type="text" id="db_user" value="root"></td>
      </tr>
      <tr> 
        <td class=color3>Database Password:</td>
        <td class=color2><input name="db_pass" type="password" id="db_pass"></td>
      </tr>
    </table>  
    <p><strong>MySQL Database Prefects </strong>(optional)</p>
    <table border="1" cellpadding="2" cellspacing="0" bordercolor="#FFFFCC">
      <tr> 
        <td class=color3>Table Prefect:</td>
        <td class=color2><input name="mysql_pre" type="text" id="server3" value="library_"></td>
      </tr>
      <tr> 
        <td class=color3>Items:</td>
        <td class=color2><input name="mysql_items" type="text" id="database3" value="items"></td>
      </tr>
      <tr> 
        <td class=color3>Students:</td>
        <td class=color2><input name="mysql_students" type="text" id="db_user3" value="students"></td>
      </tr>
      <tr>
        <td class=color3>Loans:</td>
        <td class=color2><input name="mysql_loans" type="text" id="mysql_loans" value="loans"></td>
      </tr>
      <tr> 
        <td class=color3>Admin:</td>
        <td class=color2><input name="mysql_admin" type="text" id="mysql_admin" value="admin"></td>
      </tr>
    </table>
    <p><strong>Library Login</strong></p>
    <table border="1" cellpadding="2" cellspacing="0" bordercolor="#FFFFCC">
      <tr> 
        <td class=color3>Username:</td>
        <td class=color2><input name="admin_username" type="text" id="mysql_pre"></td>
      </tr>
      <tr> 
        <td class=color3>Password:</td>
        <td class=color2><input name="admin_password" type="password" id="admin_password"></td>
      </tr>
      <tr>
        <td class=color3>Confirm Password:</td>
        <td class=color2><input name="admin_password2" type="password" id="admin_password2"></td>
      </tr>
      <tr> 
        <td class=color3>Full Name:</td>
        <td class=color2><input name="admin_name" type="text" id="admin_name" value=""></td>
      </tr>
      <tr> 
        <td class=color3>Email:</td>
        <td class=color2><input name="admin_email" type="text" id="admin_email"></td>
      </tr>
    </table>
    <p><strong>Library Configuration</strong></p>
    <table border="1" cellpadding="2" cellspacing="0" bordercolor="#FFFFCC">
      <tr> 
        <td class=color3>Library Name:</td>
        <td class=color2><input name="library_name" type="text" id="library_name"></td>
      </tr>
      <tr> 
        <td class=color3>Library Owner:</td>
        <td class=color2><input name="library_owner" type="text" id="library_owner"></td>
      </tr>
      <tr> 
        <td class=color3>Library Email:</td>
        <td class=color2><input name="library_email" type="text" id="library_email"></td>
      </tr>
      <tr> 
        <td class=color3>Overdue Fine:</td>
        <td class=color2><input name="overdue_fine" type="text" id="overdue_fine" value="0.10"></td>
      </tr>
      <tr> 
        <td class=color3>&nbsp;</td>
        <td class=color2><input type="submit" name="Submit" value="Ok"></td>
      </tr>
    </table>
    <p>&nbsp;</p>
  </div>
</form>
