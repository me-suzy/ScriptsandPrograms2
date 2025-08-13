<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| functions.php :: The main function library for the script            |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*    $Id: functions.php,v 1.00.0.0 05/11/2002 17:25:04 mark Exp $    */

# Firstly create an array of all HTTP post/get variables

$_SUBMIT = array_merge($HTTP_POST_VARS,$HTTP_GET_VARS);

# Turn off magic_quotes runtime

set_magic_quotes_runtime(0);

# Get rod of f*cken magic_quotes_gpc

if(get_magic_quotes_gpc()) {
	
	foreach($_SUBMIT as $key => $value) {
		
		$_SUBMIT[$key] = stripslashes($value);
		
	}
	
}

# Some compatibility for PHP 3, to register the $PHP_SELF variable

$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'];

# Bit of compression

ob_start("ob_gzhandler"); // GZIP compression

# Start the functions

/**
 * @return void
 * @desc Function Authorises a user to ensure they are logged 
 */
function auth_user() {
	
	# Authorise User Function
	# - Similar to authorise admin function
	# :: Globalise essential variables
	
	global $HTTP_COOKIE_VARS,$CONF;
	
	# Build variables from cookie elements
	
		$user_data = array();
	
		$user_data[0] = $HTTP_COOKIE_VARS['user_data']['0'];
		$user_data[1] = $HTTP_COOKIE_VARS['user_data']['1'];
		$user_data[2] = $HTTP_COOKIE_VARS['user_data']['2'];

	switch($CONF['userdriver']) {
		
		case "modernbill";
		
			# Using the modernbill database
			# :: Setup the elements
			
			$db1 = new Database;
			
			$db1->Connect($CONF['mbdbname']);
			
			$query = "SELECT * FROM `$CONF[mbtable_prefix]client_info`
			    	 WHERE
			    	 client_email = '$user_data[0]' AND
			    	 client_real_pass  = '$user_data[1]'";
			
		break;
		
		case "database";
		
			# Using edgepanel database
			# :: Setup the elements
			
			
			$db1 = new Database;
	
			$db1->Connect($CONF['dbname']);
			
			$query = "SELECT * FROM `$CONF[table_prefix]users`
				 WHERE
				 email = '$user_data[0]' AND
				 password = '$user_data[1]'";
			
		break;
		
	}
				
	# We have the cookie data, check it's all there
    
	for($i=0;$i<3;$i++) {
		
		if(empty($user_data[$i])) {
			
			header("Location: login.php");
			
			exit();
			
			break;
			
		}
	
	}
	
	# All data is there, the cookie integrity is fine,
	# now let's check the login info is OK
	
	
	# Retrieve a user with this username/password
	# combination
	
	$result = $db1->Query($query);
	
	# Check a user is present
	
	if($db1->num_rows($result) != 1) {
		
		# Invalid user!
		
		header("Location: login.php");
		
		exit();
		
	}
	
	$db1->close();
	
}

/**
 * @return void
 * @desc Output HTML to the template object 
 */
function output($output_string) {
	
	# First of all globalise the template object
	
	global $template;
	
	# Add the supplied output to the page content variable
	
	$template->pagecontent .= $output_string;
	
}

class Template {
	
	var $template;             # The template file to be used
	var $html;                 # The HTML output sent to the browser
	var $pagecontent;          # The page content 
	var $parameters = array(); # Parameters array
	var $tag;                  # Tag variable changed on the fly
	var $bypass_news;		# Skip the news bar
	
	# The following function will set the page
	# content variable as an entry in the
	# parameters array, simple !
	
	function setPageContent() {
		
		$this->parameters["PAGE_CONTENT"] = $this->pagecontent;
		
	}

	# This function creates the page by loading
	# the template and placing the content into
	# the template
	
	function createPage() {
		
		# Globalise the configuration set
		
		global $CONF,$HTTP_COOKIE_VARS;
		
		# Admin details
		
		if(empty($HTTP_COOKIE_VARS['admin_data']['0'])) {
			
			$this->parameters["ADMIN_USER"] = "Not Logged In";
			
		}
		else {
			
			$this->parameters["ADMIN_USER"] = "Logged in as: <b>".$HTTP_COOKIE_VARS['admin_data']['0']."</b> ";
			
		}
		
		# Latest news
		
		if($this->bypass_news != 1) {
		
			show_news();
			
		}
		
		# Title Parameter
		
		$this->parameters["TITLE"] = $CONF['sitename'];
		
		# First set the page content array entry
		
		$this->setPageContent();
		
		# Get the user bar data from the user bar function
		
		$this->parameters["USER_BAR"] = userbar();
		
		# Get the page navigation function
		
		$this->parameters["ADMIN_PAGE_NAVIGATION"] = admin_page_navigation();
		
		# Set the template to the configured value
		
		if($this->template == "") { $this->template = $CONF['template']; }
		
		# Import the template code
		
		$this->html = implode(file($this->template),"");
		
		# Replace every tag in the template
		# with its entry in the parameters
		# array
		
		foreach($this->parameters as $key => $value) {
			
			$this->tag = '{' . "$key" . '}';
			
			$this->html = str_replace($this->tag,$value,$this->html);
			
		}
		
		# Output the created HTML code
		
		echo($this->html);
		
	}
	
	# This function will set a value in the parameters
	# array
	
	function setParameter($name,$value) {
		
		$this->parameters[$name] = $value;
		
	}
	
	# This concludes the template object
	
}

function userbar() {
	
	output("$_TEMPLATE[options_bar]<br><br>");
	
}

/*-------------------------------------------------+
| The database object, used to handle all database |
| connections                                      |
+-------------------------------------------------*/

class Database {

	
	var $connection;   # Connection Variable
	var $db;           # Database Variable
	var $dbname;       # Database name
	var $query;        # Query variable
	var $result;       # Result variable
	var $return;       # Return variable
	
	function Connect($dbname) { // This function will connect to the db
            
         	global $CONF;
         	
         	$this->connection = @mysql_connect($CONF['dbhost'],$CONF['dbuser'],$CONF['dbpass']);

	        	if(!$this->connection) { // No connection made, return an error

                     	echo("<html>
                           	<head>
                           	<title>$CONF[sitename] - Fatal Error</title>
                           	</head>
                           	<body bgcolor=#FFFFFF text=#000000>
                           	<font face=Tahoma size=2><h3><b>Fatal Error</b></h3>A connection to the database could not be established. <br><br>Please <a href='index.php'>Try Again</a>,
                           	though if the problem persists it is recommended that you contact the <a href='mailto:$CONF[adminemail]?subject=Database Connection Error'>Webmaster</a>.<br><br>
                           	</body>
                           	</html>");
                
                     	exit();

            	}

            	$this->db = @mysql_select_db($dbname);

	        	if(!$this->db) { // No connection made, return an error

                  	echo("<html>
                        	<head>
                        	<title>$CONF[sitename] - Fatal Error</title>
                        	</head>
                        	<body bgcolor=#FFFFFF text=#000000>
                        	<font face=Tahoma size=2><h3><b>Fatal Error</b></h3>A connection to the database could not be established. <br><br>Please <a href='index.php'>Try Again</a>,
                        	though if the problem persists it is recommended that you contact the <a href='mailto:$CONF[adminemail]?subject=Database Connection Error'>Webmaster</a>. <br><br>
                        	</body>
                        	</html>");
                
                  	exit();

            	}

     	}
     
     	function Query($query) { // This will execute the query currently set to the query variable 
                          	
              	global $CONF,$template;
                   	     
              	$this->result = @mysql_query($query);            	

              	return $this->result;           		
                           
     	}
     
     	function fetch_row($result_set) {
     	        	    
     	      	return mysql_fetch_array($result_set);
     	
     	}
     
     	function num_rows($result_set) {
     	        	
     	      	return mysql_num_rows($result_set);
     	    
     	}
     	
     	function close() {
     		
     		mysql_close($this->connection);
     		
     	} 
     	
     	# This concludes the database object

}

/**
 * @return void
 * @desc Display a latest network news table 
 */
function show_news() {
	
	# Globalise config data and template objects
	
	global $CONF,$template;
	
	# Start the news output
	
	$latestnews = "<table width=100% cellpadding=0 cellspacing=0><tr><Td><b>
               <div class=heading>Network News</div></b><br></td></tr>";
	
	# Build the latest news table for the template

	$db = new Database;

	$db->Connect($CONF['dbname']);

	$result = $db->Query("SELECT * FROM `$CONF[table_prefix]news` ORDER BY `dateadded` DESC LIMIT $CONF[numnews]");
	
	if($db->num_rows($result) == 0) {
		
		$latestnews .= "<tr><td>There is currently no news</td></tr>";
		
	}

	while($row_info = $db->fetch_row($result)) {
	
		# Print all news items
	
		$latestnews .= "<tr><td><b>&raquo; <a href='news.php?id=$row_info[id]'>
	                         $row_info[title]</a></b></td></tr>";
	
	}

	$latestnews .= "</table><br><img src=i/h-separator.gif vspace=5>
		       <img src=i/user-logout.gif align=bottom>&nbsp;&nbsp;<a href='logout.php'>Logout of System</a>";

	# Set the template parameter

	$template->setParameter("LATEST_NEWS",$latestnews);
	
}

/*-------------------------------------------------+
| The auth admin function authorises an admin that |
| "claims" to be logged in                         |
+-------------------------------------------------*/

function authadmin($userlevel) {
	
	global $HTTP_COOKIE_VARS,$CONF;	
	
	# Build variables from cookie elements
	
		$admin_data = array();
	
		$admin_data[0] = $HTTP_COOKIE_VARS['admin_data']['0'];
		$admin_data[1] = $HTTP_COOKIE_VARS['admin_data']['1'];
		$admin_data[2] = $HTTP_COOKIE_VARS['admin_data']['2'];
		$admin_data[3] = $HTTP_COOKIE_VARS['admin_data']['3'];
	
	# We have the cookie data, check it's all there
    
	for($i=0;$i<4;$i++) {
		
		if(empty($admin_data[$i])) {
			
			header("Location: login.php");
			
			exit();
			
			break;
			
		}
	
	}
	
	# All data is there, the cookie integrity is fine,
	# now let's check the login info is OK
	
	$db1 = new Database;
	
	$db1->Connect($CONF['dbname']);
	
	# Retrieve a user with this username/password
	# combination
	
	$result = $db1->Query("SELECT * FROM `$CONF[table_prefix]admins`
			    WHERE
			    username = '$admin_data[0]' AND
			    password = '$admin_data[1]'");
	
	# Check a user is present
	
	if($db1->num_rows($result) != 1) {
		
		# Invalid user!
		
		header("Location: login.php");
		
		exit();
		
	}
	
	# Check the user is of the required level
	
	if($admin_data[2] > $userlevel) {
		
		# Not high enough!
		
		header("Location: index.php?e=1");
		
		exit();
		
	}
	
	$db1->close();
	            
}

function admininfobox($text) {
	
	return "<table cellpadding=5 cellspacing=0 style=\"border: 1px solid #999999\" width=100%>
	        <tr bgcolor=#eeeeee><td valign=top width=17><img src=../i/information.gif vspace=2></td>
	        <td valign=top><b>Note:</b> $text</td></tr></table>";
	
}

function tableheading($text) {
	
	global $_TEMPLATE;
	
	output("<table width=100% cellpadding=0 cellspacing=0>
         <tr><td colspan=2 style=\"border-bottom: 1px solid $_TEMPLATE[border_color]\">
          <table width=115 cellpadding=0 cellspacing=0><tr><td bgcolor=$_TEMPLATE[border_color]>
          <font color=white><b><center>$text</center></b></font>
          </td></tr></table>
         </td></tr>");
	
}

function clear($string) {
	
	return ereg_replace("\"","&quot;",$string);
	
}

# Error handling function

function timeout_error($errno, $errstr, $errfile, $errline) {
	
	global $template,$server_info;
	
	if($errno != "8") {
	
		output("<div class=heading>Viewing Server: $server_info[title]</div><br>");
	
		output("There was an error reaching the server you are attempting to access,
	        		suggesting that the server is experiencing major difficulties. You can
	        		either:
	        		<br><br>&raquo; <a href='newticket.php'>Open A New Support Ticket</a><br>
	        		&raquo; <a href='serverstatus.php'>View Another Server's Status</a>");
		
		$template->createPage();
	
		exit();
	
	}
	
}

# (Admin) Page Navigation Function

function admin_page_navigation() {
	
	global $HTTP_SERVER_VARS;
	
	# Determine the current page and return the string
	
	$string = "<a href='index.php'>Admin Control Panel</a> &raquo; ";
	
	# The catalogue from which the page is determined
	
	$catalogue = array(
	
			"index.php" => "Home Page",
			"login.php" => "Login Page",
			"addservernews.php" => "Add a new Server News Item",
			"servers.php" => "Server Management",
			"servernews.php" => "Server News Management",
			"copyservernews.php" => "Copy Server News Item",
			"editservernews.php" => "Edit Server News Item",
			"support.php" => "Help Desk",
			"news.php" => "News Management",
			"useradmins.php" => "User Management",
			"config.php" => "Script Configuration",
			"supportfields.php" => "Manage Support Ticket Fields",
			"supportcategories.php" => "Manage Support Ticket Categories",
			"cleanup.php" => "Database Cleanup",
			"answersupport.php" => "Answer Support Ticket",
			"deleteservernews.php" => "Delete Server News Item",
			"editsupportcategory.php" => "Edit Support Category",
			"update.php" => "Check For Update",
			"defaults.php" => "Set Script Defaults",
			"livechat.php" => "Live Chat Module",
			"users.php" => "User Management"
			
			);
			
	$thispage = $HTTP_SERVER_VARS['PHP_SELF'];
	
	$thispage = ereg("([[:alnum:]]+)(\.)(php)(.*)$",$thispage,$regs);
	
	$thispage = $regs[1] . ".php";
	
	$string .= $catalogue[$thispage];
	
	return $string;		
	
}

function input_name($field_name) {
	
	$field_name = ereg_replace("[[:space:]]","",$field_name);
	$field_name = ereg_replace("\$","",$field_name);
	$field_name = ereg_replace("\"","",$field_name);
	$field_name = ereg_replace("\'","",$field_name);
	$field_name = strtolower($field_name);
	
	return $field_name;
	
}

# Template Information

$_TEMPLATE["options_bar"] = ":: <a href='newticket.php'>Open New Support 
                             Ticket</a> :: <a href='request.php'>Request 
                             Upgrades</a>";

$_TEMPLATE["welcome_msg"] = "Welcome to the $CONF[sitename] server 
			  control panel.";

# Admin Panel Variables

$_TEMPLATE["border_color"] = "#999999";

$_TEMPLATE["light_background"] = "#F5F5F5";

$_TEMPLATE["dark_background"] = "#EEEEEE";

$full_border = "style=\"border-right: 1px solid $_TEMPLATE[border_color];
		      border-left: 1px solid $_TEMPLATE[border_color];
		      border-bottom: 1px solid $_TEMPLATE[border_color];\"";

$left_border = "style=\"border-left: 1px solid $_TEMPLATE[border_color];
		      border-bottom: 1px solid $_TEMPLATE[border_color];\"";

$right_border = "style=\"border-right: 1px solid $_TEMPLATE[border_color];
		       border-bottom: 1px solid $_TEMPLATE[border_color];\"";
	
?>