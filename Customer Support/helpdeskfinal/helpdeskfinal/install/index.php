<?php
//
// Helpdesk installation script
// Installs helpdesk and configures database
//

require_once "../includes/tpl.php";

if(isset($_POST['btn_install']))
{
	$db_host = $_POST['db_host'];
	$db_name = $_POST['db_name'];
	$db_user = $_POST['db_user'];
	$db_pass = $_POST['db_pass'];

	$table_prefix = $_POST['table_prefix'];
	$hd_email = $_POST['hd_email'];
	$attach_dir = $_POST['attach_dir'];
	
	$admin_username = $_POST['admin_username'];
	$admin_pass		= $_POST['admin_pass'];
	$admin_pass2	= $_POST['admin_pass2'];
	$admin_firstname= $_POST['admin_firstname'];
	$admin_lastname	= $_POST['admin_lastname'];
	$admin_email	= $_POST['admin_email'];
	$admin_phone	= (!empty($_POST['admin_phone'])) ? ($_POST['admin_phone']) : (NULL);
	
	$errors = "";
	if(strlen($admin_username) < 3 || strlen($admin_username) > 25)
	{
		$errors .= "Username must be 3 to 25 characters long.<br>";
	}

	if(strlen($admin_pass) < 3 || strlen($admin_pass) > 25)
	{
		$errors .= "Password must be 3 to 25 characters long.<br>";
	}
	else
	{
		if($admin_pass != $admin_pass2)
			$errors .= "Passwords do not match.<br>";
	}

	if(empty($admin_firstname))
		$errors .= "First name field must be filled.<br>";
		
	if(empty($admin_lastname))
		$errors .= "Last name field must be filled.<br>";
		
	if(empty($admin_email))
	{
		$errors .= "E-Mail field must be filled.<br>";
	}
	else
	{
		// Check email format
		if(!preg_match("/[a-zA-Z0-9-_\.]+@[a-zA-Z0-9-_\.]+\.[a-zA-Z0-9]+/", $admin_email))
		{
			$errors .= "Invalid E-Mail format.";
		}
	}
	
	// If phone is filled, check format
	if(!empty($admin_phone) && !preg_match("/^[0-9]([0-9]|[\-])*[0-9]$/", $admin_phone))
		$errors .= "Phone field can contain only numbers and dashes (-).";
		
	// If there were errors, show them
	if(!empty($errors))
		error($errors);	

	// Check if all fields filled with right values
	if(empty($db_host) || empty($db_name) || empty($db_user) || empty($table_prefix) || empty($hd_email) || empty($attach_dir))
		error("Please fill all the required fields (marked with *)<br>");

	// Try to connect to db
	$conn = @mysql_connect($db_host, $db_user, $db_pass) or
				error("Cannot connect to the database using the info you provided<br>" . mysql_error());
	@mysql_select_db($db_name) or
				error("Cannot connect to the database using the info you provided<br>" . mysql_error());
				
	// Check if attachments folder exists
	if(!is_dir("../" . $attach_dir))
		error("Folder \"$attach_dir\" does not exist.");

	// Check if folder is writeable
	if(!is_writeable("../" . $attach_dir))
		error("Attachment folder is not writeable. Please set proper permissions.");
		
	// Check if config.php exists and delete it
	if(is_file("../includes/config.php"))
	{
		if(!is_writeable("../includes/config.php"))
			error("includes/config.php is not writeable. Please set proper permissions.");
			
		unlink("../includes/config.php");
	}
	
	// Create empty config.php
	touch("../includes/config.php");
	
	// Build config.php contents
	$tpl_config = new tpl("../install/config.tpl");
	
	$tpl_config_tags = array( "db_host" => $db_host,
							  "db_name" => $db_name,
							  "db_user" => $db_user,
							  "db_pass" => $db_pass,
							  "table_prefix" => $table_prefix,
							  "path_attachments" => $attach_dir );
	$tpl_config->parse($tpl_config_tags);
	
	$f_config = fopen("../includes/config.php","w");
	
	// Write config.php
	fwrite($f_config, $tpl_config->parsed);
	
	//
	// Create all required tables and default values
	//
	include "../includes/config.php";
	
	echo "<link rel=\"stylesheet\" href=\"../tpl/helpdesk.css\" type=\"text/css\">";
	
	// Create attachments table
	echo "Creating attachments table...<br>";
	mysql_query("CREATE TABLE $TABLE_ATTACHMENTS
				(attach_id int(11) NOT NULL auto_increment,
				attach_file varchar(40) default NULL,
				attach_origname varchar(255) default NULL,
				PRIMARY KEY  (attach_id))") or
		die("Error: Cannot create attachments table. " . mysql_error());
	
	// Create categories table
	echo "Creating categories table...<br>";
	mysql_query("CREATE TABLE $TABLE_CATS (
				 cat_id int(11) NOT NULL auto_increment,
				 cat_name varchar(255) default NULL,
				 cat_orderby int(11) NOT NULL default '0',
				 PRIMARY KEY (cat_id))") or
		die("Error: Cannot create categories table. " . mysql_error());
	// Add default categories
	mysql_query("INSERT INTO $TABLE_CATS (cat_id, cat_name, cat_orderby) VALUES (1, 'Other', 1);") or
		die("Error: Cannot create default categories. " . mysql_error());
		
	// Create config table
	echo "Creating configuration table...<br>";
	mysql_query("CREATE TABLE $TABLE_CONFIG
				 (config_name varchar(64) NOT NULL default '',
				 config_value text,
				 PRIMARY KEY (config_name))") or
		die("Error: Cannot create config table. " . mysql_error());
	// Add config values
	echo "Populating attachments table...<br>";
	mysql_query("INSERT INTO $TABLE_CONFIG (config_name, config_value) VALUES ('attach_max_size', '200'),
				('wait_before_repost', '10'),
				('tickets_pp', '15'),
				('replies_pp', '15'),
				('attach_show_imgs', '1'),
				('helpdesk_email', '$hd_email'),
				('helpdesk_timezone', '-8');") or
		die("Error: Cannot add configuration into database. " . mysql_error());
		
	// Crete priorities table
	echo "Creating priorities table...<br>";
	mysql_query("CREATE TABLE $TABLE_PRIORITIES
				 (priority_id int(11) NOT NULL auto_increment,
				 priority_name varchar(255) default NULL,
				 priority_orderby int(11) NOT NULL default '0',
				 PRIMARY KEY  (priority_id))") or
		die("Error: Cannot create priorties table. " . mysql_error());
		
	// Insert default priorities
	echo "Populating priorities table...<br>";
	mysql_query("INSERT INTO $TABLE_PRIORITIES
				(priority_id, priority_name, priority_orderby) VALUES
				(1, 'Critical', 1),
				(2, 'High', 2),
				(3, 'Normal', 3),
				(4, 'Low', 4);") or
		die("Error: Cannot create default priorities. " . mysql_error());
	
	// Create replies table
	echo "Creating replies table...<br>";
	mysql_query("CREATE TABLE $TABLE_REPLIES
				(reply_id int(11) NOT NULL auto_increment,
				ticket_id int(11) NOT NULL default '0',
				reply_content blob,
				reply_author int(11) NOT NULL default '0',
				reply_time int(11) default NULL,
				reply_attachment int(11) NOT NULL default '0',
				PRIMARY KEY  (`reply_id`))") or
  		die("Error: Cannot create replies table. " . mysql_error());
		
	// Create status table
	echo "Creating status table...<br>";
	mysql_query("CREATE TABLE $TABLE_STATUS
				(status_id int(11) NOT NULL auto_increment,
				status_name varchar(255) default NULL,
				status_orderby int(11) NOT NULL default '0',
				PRIMARY KEY (status_id))") or
		die("Error: Cannot create status table. " . mysql_error());
		
	// Create default status
	echo "Populating status table...<br>";
	mysql_query("INSERT INTO $TABLE_STATUS
				 (status_id, status_name, status_orderby) VALUES (1, 'Open', 1),
				 (2, 'Closed', 2),
				 (3, 'On Hold', 3),
				 (4, 'Resolved', 4);") or
		die("Error: Cannot create default status. " . mysql_error());
		
	// Create tickets table
	echo "Creating tickets table...<br>";
	mysql_query("CREATE TABLE $TABLE_TICKETS
				(ticket_id int(11) NOT NULL auto_increment,
				ticket_subject varchar(255) default NULL,
				ticket_firstreply int(11) NOT NULL default '0',
				ticket_cat int(11) NOT NULL default '0',
				ticket_priority int(11) NOT NULL default '0',
				ticket_status int(11) NOT NULL default '0',
				ticket_notify tinyint(1) default NULL,
				ticket_tech int(11) NOT NULL default '0',
				ticket_hide tinyint(1) default '0',
				PRIMARY KEY  (ticket_id))") or
		die("Error: Cannot create tickets table. " . mysql_error());
	
	// Create users table
	echo "Create users table...<br>";
	mysql_query("CREATE TABLE $TABLE_USERS
				(user_id int(11) NOT NULL auto_increment,
				user_name varchar(255) NOT NULL default '',
				user_firstname varchar(255) NOT NULL default '',
				user_lastname varchar(255) NOT NULL default '',
				user_password varchar(32) NOT NULL default '',
				user_email varchar(255) NOT NULL default '',
				user_phone varchar(50) default NULL,
				user_timezone varchar(5) default NULL,
				user_notify tinyint(1) NOT NULL default '0',
				user_level int(1) default NULL,
				user_ticket_priority int(11) NOT NULL default '0',
				user_ticket_cat int(11) NOT NULL default '0',
				user_self_only tinyint(1) NOT NULL default '0',
				PRIMARY KEY  (user_id))") or
		die("Error: Cannot create users table. " . mysql_error());
	
	// Create administrator
	mysql_query("INSERT INTO $TABLE_USERS
				(user_id, user_name, user_password, user_level, user_firstname, user_lastname, user_email, user_phone) VALUES
				(1, '$admin_username', md5('$admin_pass'), 1, '$admin_firstname', '$admin_lastname', '$admin_email', '$admin_phone')") or
		die("Cannot create helpdesk administrator. " . mysql_error());
	
	echo "<br><b>Installation complete</b><br>Don`t forget to delete the install directory to prevent abuse!";
	echo "<br><b><a href=\"../index.php\">Helpdesk Index</a></b>";
	
	exit();
}
#
# Show installation page
#

$page_title = "Helpdesk installation";
$tpl_install = new tpl("install.tpl");

echo $tpl_install->template;
?>