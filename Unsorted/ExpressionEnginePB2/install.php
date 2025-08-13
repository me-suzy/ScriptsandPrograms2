<?php
/*
=====================================================
 ExpressionEngine - by pMachine
-----------------------------------------------------
 Nullified by GTT
-----------------------------------------------------
 Copyright (c) 2003 - 2004 pMachine, Inc.
=====================================================
 THIS IS COPYRIGHTED SOFTWARE
 PLEASE READ THE LICENSE AGREEMENT
=====================================================
 File: install.php
-----------------------------------------------------
 Purpose: Installation file
=====================================================
*/

error_reporting(E_ALL);
set_magic_quotes_runtime(0);


$data = array(
				'app_version'		=> '009',
				'ext'				=> '.php',
				'ip'				=> '',
				'database'			=> 'mysql',
				'db_conntype'		=> 1,
				'system_dir'		=> 'system',
				'db_hostname'		=> 'localhost',
				'db_username'		=> '',
				'db_password'		=> '',
				'db_name'			=> '',
				'db_prefix'			=> 'exp',
				'site_name'			=> '',
				'site_url'			=> '',
				'site_index'		=> '',
				'cp_url'			=> '',
				'cp_index'			=> 'index.php',
				'username'			=> '',
				'password'			=> '',
				'screen_name'		=> '',
				'email'				=> '',
				'webmaster_email'	=> '',
				'deft_lang'			=> 'english',
				'template'			=> '01',
				'server_timezone'	=> 'UTC',
				'daylight_savings'	=> '',
				'redirect_method'	=> 'redirect',
				'upload_folder'		=> 'uploads/',
				'image_path'		=> '../images/',
				'member_images'		=> 'member_images/'
			);



foreach ($_POST as $key => $val)
{
	if ( ! get_magic_quotes_gpc())
		$val = addslashes($val);

	if (isset($data[$key]))
	{
		$data[$key] = trim($val);	
	}
}

if ( ! ereg("/$", $data['site_url']))
{
	$data['site_url'].'/';
}

$data['system_dir'] = str_replace("/", "", $data['system_dir']);


define('EXT', $data['ext']);

$page = (! isset($_GET['page']) || $_GET['page'] > 4) ? 1 : $_GET['page'];


if (phpversion() < '4.1.0')
{
	$page = 0;
}


// HTML HEADER
page_head();


// Unsupported version of PHP
// --------------------------------------------------------------------
// --------------------------------------------------------------------


if ($page == 0)
{
?>

<div class="error">Error:&nbsp;&nbsp;Unsupported PHP version</div>


<p><b>In order to install ExpressionEngine, your server must be running PHP version 4.1 or newer.</b></p>

<p>Your server is currently running PHP version: <?php echo phpversion(); ?></p>


<?php
}




// PAGE ONE
// --------------------------------------------------------------------
// --------------------------------------------------------------------


if ($page == 1)
{
?>
     
<div id='content'>

<h1>Welcome to the ExpressionEngine Installation Wizard</h1>


<h5>ExpressionEngine is installed in five steps.</h5> 


<br />

<h2>STEP 1</h2>

<h4>Get Your Settings</h4>

<p>In order to install ExpressionEngine you will need to know 4 pieces of information.</p>

<p>If you do not know what they are, please contact your hosting provider and ask them.</p>


<ol>
<li><strong>MySQL Database Name</strong></li>
<li><strong>MySQL Server Address</strong></li>
<li><strong>MySQL Username</strong></li>
<li><strong>MySQL Password</strong></li>
</ol>

<br />


<h2>STEP 2</h2>

<h4>Rename the "system" folder</h4>

<p>This is an <b>optional, but recommended</b> step that increases security by keeping the directory containing your ExpressionEngine backend files hidden from public access.</p>

<p><b>To perform this step:</b>&nbsp; Rename the directory called <b>system</b>, located inside the root <b>ExpressionEngine</b> directory.</p>

<p>Choose a name that is not easily guessed.</p>

<br />



<h2>STEP 3</h2>
<h4>Upload the Files</h4>
<p>
Using an FTP program like WS_FTP, Transmit, Fetch, etc. upload the ExpressionEngine files to your server.
</p>

<br />


<h2>STEP 4</h2>

<h4>Set File Permissions</h4>

<p><span class='red'>Note: If you are hosted on a Windows server, skip this step</span></p>

<p>If you are using a Unix server you must set the following files to <b>666</b></p>


<ul>
<li><strong>path.php</strong></li>
<li><strong>/system/config.php</strong></li>
<li><strong>/system/config_bak.php</strong></li>
</ul>

<p>You must set the following two directories to <b>777</b></p>


<ul>
<li><strong>/images/uploads</strong></li>
<li><strong>cache&nbsp;&nbsp;</strong>(located in the "system" folder)</li>
</ul>


<br />

<h2>STEP 5</h2>

<h4>Choose the Look of Your Weblog</h4>

<p>Browse our template library and choose your favorite one.  
<br /><br />
Make a note of the ID number.  You will need it in the next step.
<br />
    
    
    
<br />

<h2>STEP 6</h2>

<h4>Run the Installation Wizard</h4>

<p>If you have performed the above steps and you are ready to install ExpressionEngine:</p>
<p><a href='install.php?page=2'>Click here to begin!</a></p>
    
    
<?php
}



// PAGE TWO
// --------------------------------------------------------------------
// --------------------------------------------------------------------


elseif ($page == 2)
{
    system_folder_form();
}


// PAGE THREE
// --------------------------------------------------------------------
// --------------------------------------------------------------------


elseif ($page == 3)
{

// Does the 'system' directory exist?
// --------------------------------------------------------------------

    if ($data['system_dir'] == '' OR ! is_dir('./'.$data['system_dir']))
    {
        ?><div class='error'>Error: Unable to locate the directory you submitted.</div><?php
        system_folder_form();
        page_footer();
    
        exit;
    }


// Are the various files and directories writable?
// --------------------------------------------------------------------
    
    $system_path = './'.trim($data['system_dir']).'/';
    
    $writable_things = array(
    							'path.php',
    							$system_path.'config.php', 
    							$system_path.'cache/'
    						);
    
    
    $not_writable = array();
    
    foreach ($writable_things as $val)
    {        
        if ( ! is_writable($val))
        {
            $not_writable[] = $val;
        }
    }
    
    if ( ! is_writable("./images/uploads"))
    {
        $not_writable[] = "uploads";
    }
    
    
    $i = count($not_writable);
    
    if ($i > 0)
    {
        $d = ($i > 1) ? 'directories or files' : 'directory or file';
  
        echo "<div class='error'><br />Error: The following $d can not be written to:</div><br />";
                
        foreach ($not_writable as $bad)
        {
			echo '<strong>'.$bad.'</strong><br />';
        }
                
            $item = ($i > 1) ? 'items' : 'item';
        ?> 
                
        <p>In order to run this installation, the file permissions on the above <?php echo $item; ?> must be set as indicated in the instructions.</p>
        
        
        <p><b>Once you are done, please run this installation script again.</b></p>
        
        <?php
        
        page_footer();
        
        exit;
    }
    
	if ( ! file_exists($system_path.'config'.$data['ext']))
	{
        echo "<div class='error'><br />Error: Unable to locate your config.php file.  Please make sure you have uploaded all components of this software.</div><br />";
	
        page_footer();
        
        exit;
    }
    else
    {
		require($system_path.'config'.$data['ext']);
    }
    
        
    if (isset($conf['install_lock']) AND $conf['install_lock'] == 1)
    {
    ?>
    
    <div class="error">Warning:  Your installation lock is set</div>
    
    <p>As a security precaution this installation script can only be run once.</p>
    
    <p>In order to run the installation again, locate the file called <b>config.php</b> and delete its contents.</b>
    
    <p>Once you've done this, <a href="install.php?page=2">click here</a> to continue</p>
    
    <?php
    
    exit;
    }
    
    
    
    

// Server and admin settings
// --------------------------------------------------------------------

    settings_form();

}





// PAGE FOUR
// --------------------------------------------------------------------
// --------------------------------------------------------------------


elseif ($page == 4)
{

    if ($data['db_hostname'] == '' AND $data['db_username'] == '' AND $data['db_name'] == '')
    {
    	echo "<p>An errror occured.  <a href='install.php'>Click here</a> to return to the main page</a>";
		page_footer();
		exit;    
    } 


	$errors = array();
	
    $system_path = './'.trim($data['system_dir']).'/';
    
	if ( ! file_exists($system_path.'config'.$data['ext']))
	{
     	$errors[] = "Unable to locate the file called \"config.php\".  Please make sure you have uploaded all components of this software.";
    }
    else
    {
		require($system_path.'config'.$data['ext']);
    }
	
    
    if (isset($conf['install_lock']) AND $conf['install_lock'] == 1)
    {
        $errors[] = "Your installation lock is set. Locate the file called <b>config.php</b> and delete its contents";
    }
	

    if (
        $data['db_hostname'] == '' ||
        $data['db_username'] == '' ||
        $data['db_name']     == '' ||
        $data['site_name']   == '' ||
        $data['username']    == '' ||
        $data['password']    == '' ||
        $data['email']       == '' 
       )
    {
        $errors[] = "You left some form fields empty";
    } 

    if (strlen($data['username']) < 4)
    {
        $errors[] = "Your username must be at least 4 characters in length";
    }
    
    
    if (strlen($data['password']) < 5)
    {
        $errors[] = "Your password must be at least 5 characters in length";
    }

	//  Is password the same as username?

	$lc_user = strtolower($data['username']);
	$lc_pass = strtolower($data['password']);
	$nm_pass = strtr($lc_pass, 'elos', '3105');


	if ($lc_user == $lc_pass || $lc_user == strrev($lc_pass) || $lc_user == $nm_pass || $lc_user == strrev($nm_pass))
	{
		$errors[] = "Your password can not be based on the username";
	}        

	if ( ! eregi("^([-a-zA-Z0-9_\.\+])+@([-a-zA-Z0-9_\.\+]+\.)+[a-z]{2,6}$", $data['email']))
	{
		if ( ! preg_match("/^[^@\s]+@([-_\.a-z0-9]+\.)+[a-z]{2,6}$/ix", $data['email']))
		{
			$errors[] = "The email address you submitted is not valid";
		}
	}
	
	
	if ($data['screen_name'] == '')
	{
		$data['screen_name'] = $data['username'];
	}


//  CONNECT TO DATABASE
// --------------------------------------------------------------------

	$db_prefix = ($data['db_prefix'] == '') ? 'exp' : $data['db_prefix'];
    
	if ( ! file_exists($system_path.'db/db.'.$data['database'].$data['ext']))
	{
     	$errors[] = "Unable to locate the database file.  Please make sure you have uploaded all components of this software.";
    }
    else
    {
		require($system_path.'db/db.'.$data['database'].$data['ext']);
    }
            
    $db_config = array(
                        'hostname'  	=> $data['db_hostname'],
                        'username'  	=> $data['db_username'],
                        'password'  	=> $data['db_password'],
                        'database'  	=> $data['db_name'],
                        'db_conntype'	=> $data['db_conntype'],
                        'prefix'    	=> $db_prefix,
                        'enable_cache'	=> FALSE
                      );
                      
    $DB = new DB($db_config);
        
	if ( ! $DB->db_connect(0))
	{
        $errors[] = "Unable to connect to the database server";
	}
	
	if ( ! $DB->select_db())
	{
        $errors[] = "Unable to select the database";
	}
	


	if ( ! file_exists($system_path.'language/'.$data['deft_lang'].'/email_data'.$data['ext']))
	{
        $errors[] = "Unable to locate the file containing your email templates.  Make sure you have uploaded all components of this software.";
	}
	else
	{
		require($system_path.'language/'.$data['deft_lang'].'/email_data'.$data['ext']);
	}



//  DISPLAY ERRORS
// --------------------------------------------------------------------

	
	if (count($errors) > 0)
	{
		echo "<h2>The following Errors were encountered</h2>";
	
		echo "<ol>";
		
		foreach ($errors as $doh)
		{
			echo "<li>".$doh."</li>";
		}
	
		echo "</ol>";
	
		echo "<div class='border'><p><br /><b>Please correct the errors and resubmit the form</b><br /><br /></p></div>";
	
		settings_form();
		page_footer();
		exit;    
	}

	
	

//  Prep user submitted data for DB insertion
// --------------------------------------------------------------------
// --------------------------------------------------------------------

    // -------------------------------------
    // Include default template data
    // -------------------------------------

    $theme  = 'theme_'.$data['template'].'.php';

    require './'.$data['system_dir'].'/themes/'.$theme;			


    // -------------------------------------
    // Get user's IP address
    // -------------------------------------
        
    $CIP = ( ! isset($_SERVER['HTTP_CLIENT_IP']))       ? FALSE : $_SERVER['HTTP_CLIENT_IP'];
    $FOR = ( ! isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? FALSE : $_SERVER['HTTP_X_FORWARDED_FOR'];
    $RMT = ( ! isset($_SERVER['REMOTE_ADDR']))          ? FALSE : $_SERVER['REMOTE_ADDR'];    
    
    if ($CIP) 
    {
        $cip = explode('.', $CIP);
        
        $data['ip'] = ($cip['0'] != current(explode('.', $RMT))) ? implode('.', array_reverse($cip)) : $CIP;
    }
    elseif ($FOR) 
    {
        $data['ip'] = (strstr($FOR, ',')) ? end(explode(',', $FOR)) : $FOR;
    }
    else
        $data['ip'] = $RMT;


    // -------------------------------------
    // Encrypt password and Unique ID 
    // -------------------------------------
    
    if (phpversion() >= 4.2)
        mt_srand();
    else
        mt_srand(hexdec(substr(md5(microtime()), -8)) & 0x7fffffff);    
    
    $unique_id = uniqid(mt_rand());
    
    $password = stripslashes($data['password']);
    
        
    if ( ! function_exists('sha1'))
    {
        if ( ! function_exists('mhash'))
        {        
            require './'.$data['system_dir'].'/core/core.sha1'.$data['ext'];           
        
            $SH = new SHA;
            
            $unique_id = $SH->encode_hash($unique_id);            
            $password  = $SH->encode_hash($password);            
        }
        else
        {
            $unique_id = bin2hex(mhash(MHASH_SHA1, $unique_id));
            $password  = bin2hex(mhash(MHASH_SHA1, $password));
        }
    }
    else
    {
        $unique_id = sha1($unique_id);
        $password  = sha1($password);
    }
       
    
    // -------------------------------------
    // Fetch current time as GMT
    // -------------------------------------
    
    $time	= time(); 
    $now	= mktime(gmdate("H", $time), gmdate("i", $time), gmdate("s", $time), gmdate("m", $time), gmdate("d", $time), gmdate("Y", $time));
    $year	= gmdate('Y', $now);
    $month	= gmdate('m', $now);
    $day	= gmdate('d', $now);
   
		    

//  DEFINE DB TABLES
// --------------------------------------------------------------------
// --------------------------------------------------------------------

// Session data

$D[] = 'exp_sessions';

$Q[] = "CREATE TABLE exp_sessions (
  session_id varchar(40) default '0' NOT NULL,
  member_id int(10) default '0' NOT NULL,
  admin_sess tinyint(1) default '0' NOT NULL,
  ip_address varchar(16) default '0' NOT NULL,
  user_agent varchar(50) NOT NULL,
  last_visit int(10) unsigned DEFAULT '0' NOT NULL,
  PRIMARY KEY (session_id)
)";


// System stats

$D[] = 'exp_stats';

$Q[] = "CREATE TABLE exp_stats (
  weblog_id int(6) unsigned NOT NULL default '0',
  total_members mediumint(7) NOT NULL default '0',
  total_entries mediumint(8) default '0' NOT NULL,
  total_comments mediumint(8) default '0' NOT NULL,
  total_trackbacks mediumint(8) default '0' NOT NULL,
  last_entry_date int(10) unsigned default '0' NOT NULL,
  last_comment_date int(10) unsigned default '0' NOT NULL,
  last_trackback_date int(10) unsigned default '0' NOT NULL,
  last_visitor_date int(10) unsigned default '0' NOT NULL, 
  most_visitors mediumint(7) NOT NULL default '0',
  most_visitor_date int(10) unsigned default '0' NOT NULL,
  KEY (weblog_id)
)";


// Online users

$D[] = 'exp_online_users';

$Q[] = "CREATE TABLE exp_online_users (
 weblog_id int(6) unsigned NOT NULL default '0',
 member_id int(10) default '0' NOT NULL,
 name varchar(50) default '0' NOT NULL,
 ip_address varchar(16) default '0' NOT NULL,
 date int(10) unsigned default '0' NOT NULL,
 anon char(1) NOT NULL,
 KEY (date)
)";


// Actions table
// Actions are events that require processing. Used by modules class.

$D[] = 'exp_actions';

$Q[] = "CREATE TABLE exp_actions (
 action_id int(4) unsigned NOT NULL auto_increment,
 class varchar(50) NOT NULL,
 method varchar(50) NOT NULL,
 PRIMARY KEY (action_id)
)";

// Modules table
// Contains a list of all installed modules

$D[] = 'exp_modules';

$Q[] = "CREATE TABLE exp_modules (
 module_id int(4) unsigned NOT NULL auto_increment,
 module_name varchar(50) NOT NULL,
 module_version varchar(12) NOT NULL,
 has_cp_backend char(1) NOT NULL default 'n',
 PRIMARY KEY (module_id)
)";

// Referrer tracking table

$D[] = 'exp_referrers';

$Q[] = "CREATE TABLE exp_referrers (
  ref_id int(10) unsigned NOT NULL auto_increment,
  ref_from varchar(120) NOT NULL,
  ref_to varchar(120) NOT NULL,
  ref_ip varchar(16) default '0' NOT NULL,
  ref_date int(10) unsigned default '0' NOT NULL,
  ref_agent varchar(100) NOT NULL,
  user_blog varchar(40) NOT NULL,
  PRIMARY KEY (ref_id)
)";


// Blacklist table for referrers

$D[] = 'exp_blacklisted';

$Q[] = "CREATE TABLE exp_blacklisted (
 blacklisted_type VARCHAR(20) NOT NULL,
 blacklisted_value TEXT NOT NULL
)";


// Security Hashes
// Used to store hashes needed to process forms in 'secure mode'

$D[] = 'exp_security_hashes';

$Q[] = "CREATE TABLE exp_security_hashes (
 date int(10) unsigned NOT NULL,
 hash varchar(40) NOT NULL,
 KEY (hash)
)";

// Password Lockout
// If password lockout is enabled, a user only gets
// four attempts to log-in within a specified period.
// This table holds the a list of locked out users

$D[] = 'exp_password_lockout';

$Q[] = "CREATE TABLE exp_password_lockout (
 login_date int(10) unsigned NOT NULL,
 ip_address varchar(16) default '0' NOT NULL,
 user_agent varchar(50) NOT NULL,
 KEY (login_date),
 KEY (ip_address),
 KEY (user_agent)
)";

// Reset password
// If a user looses their password, this table
// holds the reset code.

$D[] = 'exp_reset_password';

$Q[] = "CREATE TABLE exp_reset_password (
  member_id int(10) unsigned NOT NULL,
  resetcode varchar(12) NOT NULL,
  date int(10) NOT NULL
)";

// Mailing list
// Notes: "authcode" is a random hash assigned to each member
// of the mailing list.  We use this code in the "usubscribe" link
// added to sent emails.

$D[] = 'exp_mailing_list';

$Q[] = "CREATE TABLE exp_mailing_list (
 user_id int(10) unsigned NOT NULL auto_increment,
 authcode varchar(10) NOT NULL,
 email varchar(50) NOT NULL,
 PRIMARY KEY (email),
 KEY (user_id)
)";

// Mailing List Queue
// When someone signs up for the mailing list, they are sent
// a confirmation email.  This prevents someone from signing 
// up another person.  This table holds email addresses that
// are pending activation.

$D[] = 'exp_mailing_list_queue';

$Q[] = "CREATE TABLE exp_mailing_list_queue (
  email varchar(50) NOT NULL,
  authcode varchar(10) NOT NULL,
  date int(10) NOT NULL
)";

// Email Cache
// We store all email messages that are sent from the CP

$D[] = 'exp_email_cache';

$Q[] = "CREATE TABLE exp_email_cache (
  cache_id int(6) unsigned NOT NULL auto_increment,
  cache_date int(10) unsigned default '0' NOT NULL,
  total_sent int(6) unsigned NOT NULL,
  from_name varchar(70) NOT NULL,
  from_email varchar(70) NOT NULL,
  recipient text NOT NULL,
  cc text NOT NULL,
  bcc text NOT NULL,
  recipient_array mediumtext NOT NULL,
  subject varchar(120) NOT NULL,
  message text NOT NULL,
  mailinglist char(1) NOT NULL default 'n',
  mailtype varchar(6) NOT NULL,
  wordwrap char(1) NOT NULL default 'y',
  priority char(1) NOT NULL default '3',
  PRIMARY KEY (cache_id)
)";

// Cached Member Groups
// We use this table to store the member group assignments
// for each email that is sent.  Since you can send email
// to various combinations of members, we store the member
// group numbers in this table, which is joined to the 
// table above when we need to re-send an email from cache.

$D[] = 'exp_email_cache_mg';

$Q[] = "CREATE TABLE exp_email_cache_mg (
  cache_id int(6) unsigned NOT NULL,
  group_id tinyint(3) NOT NULL,
  KEY (cache_id)
)";


// Email Console Cache
// Emails sent from the member profile email console are saved here.

$D[] = 'exp_email_console_cache';

$Q[] = "CREATE TABLE exp_email_console_cache (
  cache_id int(6) unsigned NOT NULL auto_increment,
  cache_date int(10) unsigned default '0' NOT NULL,
  member_id int(10) unsigned NOT NULL,
  member_name varchar(50) NOT NULL,
  ip_address varchar(16) default '0' NOT NULL,
  recipient varchar(70) NOT NULL,
  recipient_name varchar(50) NOT NULL,
  subject varchar(120) NOT NULL,
  message text NOT NULL,
  PRIMARY KEY (cache_id)
)";

// Email Tracker
// This table is used by the Email module for flood control.

$D[] = 'exp_email_tracker';

$Q[] = "CREATE TABLE exp_email_tracker (
email_id int(10) unsigned NOT NULL auto_increment,
email_date int(10) unsigned default '0' NOT NULL,
sender_ip varchar(16) NOT NULL,
sender_email varchar(75) NOT NULL ,
sender_username varchar(50) NOT NULL ,
number_recipients int(4) unsigned default '1' NOT NULL,
PRIMARY  KEY (email_id) 
)";


// Member table
// Contains the member info

/*
Note: The following fields are intended for use
with the "user_blog" module.

  weblog_id int(6) unsigned NOT NULL default '0',
  template_id int(6) unsigned NOT NULL default '0',
  upload_id int(6) unsigned NOT NULL default '0',
*/


$D[] = 'exp_members';
 
$Q[] = "CREATE TABLE exp_members (
  member_id int(10) unsigned NOT NULL auto_increment,
  group_id tinyint(3) NOT NULL default '0',
  weblog_id int(6) unsigned NOT NULL default '0',
  tmpl_group_id int(6) unsigned NOT NULL default '0',
  upload_id int(6) unsigned NOT NULL default '0',
  username varchar(50) NOT NULL,
  screen_name varchar(50) NOT NULL,
  password varchar(40) NOT NULL,
  unique_id varchar(40) NOT NULL,
  authcode varchar(10) NOT NULL,
  email varchar(50) NOT NULL,
  url varchar(75) NOT NULL,
  location varchar(50) NOT NULL,
  occupation varchar(80) NOT NULL,
  interests varchar(120) NOT NULL,
  bday_d int(2) NOT NULL,
  bday_m int(2) NOT NULL,
  bday_y int(4) NOT NULL,
  aol_im varchar(50) NOT NULL,
  yahoo_im varchar(50) NOT NULL,
  msn_im varchar(50) NOT NULL,
  icq varchar(50) NOT NULL,
  bio text NOT NULL,
  ip_address varchar(16) default '0' NOT NULL,
  join_date int(10) unsigned default '0' NOT NULL,
  last_visit int(10) unsigned default '0' NOT NULL, 
  total_entries smallint(5) unsigned NOT NULL default '0',
  total_comments smallint(5) unsigned NOT NULL default '0',
  last_entry_date int(10) unsigned default '0' NOT NULL,
  last_comment_date int(10) unsigned default '0' NOT NULL,
  last_email_date int(10) unsigned default '0' NOT NULL,
  in_authorlist char(1) NOT NULL default 'n',
  accept_admin_email char(1) NOT NULL default 'y',
  accept_user_email char(1) NOT NULL default 'y',
  notify_by_default char(1) NOT NULL default 'y',
  language varchar(50) NOT NULL,
  timezone varchar(8)  NOT NULL,
  daylight_savings char(1) default 'n' NOT NULL,
  time_format char(2) default 'us' NOT NULL,
  theme varchar(32) NOT NULL,
  tracker text NOT NULL,
  template_size varchar(2) NOT NULL default '28',
  notepad text NOT NULL,
  notepad_size varchar(2) NOT NULL default '18',
  quick_links text NOT NULL,
  pmember_id int(10) NOT NULL default '0',
  PRIMARY KEY (member_id)
)";

// CP homepage layout
// Each member can have their own control panel layout.
// We store their preferences here.

$D[] = 'exp_member_homepage';

$Q[] = "CREATE TABLE exp_member_homepage (
 member_id int(10) unsigned NOT NULL,
 recent_entries char(1) NOT NULL default 'l',
 recent_entries_order int(3) unsigned NOT NULL default '0',
 recent_comments char(1) NOT NULL default 'l',
 recent_comments_order int(3) unsigned NOT NULL default '0',
 recent_members char(1) NOT NULL default 'n',
 recent_members_order int(3) unsigned NOT NULL default '0',
 site_statistics char(1) NOT NULL default 'r',
 site_statistics_order int(3) unsigned NOT NULL default '0',
 member_search_form char(1) NOT NULL default 'n',
 member_search_form_order int(3) unsigned NOT NULL default '0',
 notepad char(1) NOT NULL default 'r',
 notepad_order int(3) unsigned NOT NULL default '0',
 KEY (member_id)
)";

// Member Groups table

$D[] = 'exp_member_groups';

$Q[] = "CREATE TABLE exp_member_groups (
  group_id tinyint(3) unsigned NOT NULL auto_increment,
  group_title varchar(100) NOT NULL,
  is_locked char(1) NOT NULL default 'y', 
  can_view_offline_system char(1) NOT NULL default 'n', 
  can_view_online_system char(1) NOT NULL default 'y', 
  can_access_cp char(1) NOT NULL default 'y', 
  can_access_publish char(1) NOT NULL default 'n',
  can_access_edit char(1) NOT NULL default 'n',
  can_access_design char(1) NOT NULL default 'n',
  can_access_comm char(1) NOT NULL default 'n',
  can_access_modules char(1) NOT NULL default 'n',
  can_access_admin char(1) NOT NULL default 'n',
  can_admin_weblogs char(1) NOT NULL default 'n',
  can_admin_members char(1) NOT NULL default 'n',
  can_delete_members char(1) NOT NULL default 'n',
  can_admin_mbr_groups char(1) NOT NULL default 'n',
  can_admin_mbr_templates char(1) NOT NULL default 'n',
  can_ban_users char(1) NOT NULL default 'n',
  can_admin_utilities char(1) NOT NULL default 'n',
  can_admin_preferences char(1) NOT NULL default 'n',
  can_admin_modules char(1) NOT NULL default 'n',
  can_admin_templates char(1) NOT NULL default 'n',
  can_view_other_entries char(1) NOT NULL default 'n',
  can_edit_other_entries char(1) NOT NULL default 'n',
  can_assign_post_authors char(1) NOT NULL default 'n',
  can_delete_self_entries char(1) NOT NULL default 'n',
  can_delete_all_entries char(1) NOT NULL default 'n',
  can_view_other_comments char(1) NOT NULL default 'n',
  can_edit_own_comments char(1) NOT NULL default 'n',
  can_delete_own_comments char(1) NOT NULL default 'n',
  can_edit_all_comments char(1) NOT NULL default 'n',
  can_delete_all_comments char(1) NOT NULL default 'n',
  can_moderate_comments char(1) NOT NULL default 'n',
  can_send_email char(1) NOT NULL default 'n',
  can_send_cached_email char(1) NOT NULL default 'n',
  can_email_members char(1) NOT NULL default 'n',
  can_email_member_groups char(1) NOT NULL default 'n',
  can_email_mailinglist char(1) NOT NULL default 'n',
  can_email_from_profile char(1) NOT NULL default 'n',
  can_view_profiles char(1) NOT NULL default 'n',
  can_post_comments char(1) NOT NULL default 'n', 
  can_search char(1) NOT NULL default 'n',
  search_flood_control mediumint(5) unsigned NOT NULL,
  PRIMARY KEY (group_id)
)";




// Weblog access privs
// Member groups assignment for each weblog

$D[] = 'exp_weblog_member_groups';

$Q[] = "CREATE TABLE exp_weblog_member_groups (
  group_id tinyint(3) unsigned NOT NULL,
  weblog_id int(6) unsigned NOT NULL,
  KEY (group_id)
)";

// Module access privs
// Member Group assignment for each module

$D[] = 'exp_module_member_groups';

$Q[] = "CREATE TABLE exp_module_member_groups (
  group_id tinyint(3) unsigned NOT NULL,
  module_id mediumint(5) unsigned NOT NULL,
  KEY (group_id)
)";


// Template Group access privs
// Member group assignment for each template group

$D[] = 'exp_template_member_groups';

$Q[] = "CREATE TABLE exp_template_member_groups (
  group_id tinyint(3) unsigned NOT NULL,
  template_group_id mediumint(5) unsigned NOT NULL,
  KEY (group_id)
)";


// Member Custom Fields
// Stores the defenition of each field

$D[] = 'exp_member_fields';

$Q[] = "CREATE TABLE exp_member_fields (
 m_field_id int(4) unsigned NOT NULL auto_increment,
 m_field_name varchar(32) NOT NULL,
 m_field_label varchar(50) NOT NULL,
 m_field_type varchar(12) NOT NULL default 'text',
 m_field_list_items text NOT NULL,
 m_field_ta_rows tinyint(2) default '8',
 m_field_maxl tinyint(3) NOT NULL,
 m_field_width varchar(6) NOT NULL,
 m_field_search char(1) NOT NULL default 'y',
 m_field_required char(1) NOT NULL default 'n',
 m_field_public char(1) NOT NULL default 'y',
 m_field_reg char(1) NOT NULL default 'n',
 m_field_fmt char(5) NOT NULL default 'none',
 m_field_order int(3) unsigned NOT NULL,
 PRIMARY KEY (m_field_id)
)";

// Member Data
// Stores the actual data

$D[] = 'exp_member_data';

$Q[] = "CREATE TABLE exp_member_data (
 member_id int(10) unsigned NOT NULL,
 KEY (member_id)
)";

// Weblog Table

$D[] = 'exp_weblogs';

// Note: The is_user_blog field indicates whether the blog is
// assigned as a "user blogs" weblog

$Q[] = "CREATE TABLE exp_weblogs (
 weblog_id int(6) unsigned NOT NULL auto_increment,
 is_user_blog char(1) NOT NULL default 'n',
 blog_name varchar(40) NOT NULL,
 blog_title varchar(100) NOT NULL,
 blog_url varchar(80) NOT NULL,
 blog_description varchar(225) NOT NULL,
 blog_lang varchar(12) NOT NULL,
 blog_encoding varchar(12) NOT NULL,
 total_entries mediumint(8) default '0' NOT NULL,
 total_comments mediumint(8) default '0' NOT NULL,
 total_trackbacks mediumint(8) default '0' NOT NULL,
 last_entry_date int(10) unsigned default '0' NOT NULL,
 last_comment_date int(10) unsigned default '0' NOT NULL,
 last_trackback_date int(10) unsigned default '0' NOT NULL,
 cat_group int(6) unsigned NOT NULL, 
 status_group int(4) unsigned NOT NULL,
 deft_status varchar(50) NOT NULL default 'open',
 field_group int(4) unsigned NOT NULL,
 search_excerpt int(4) unsigned NOT NULL,
 enable_trackbacks char(1) NOT NULL default 'y',
 trackback_max_hits int(2) unsigned NOT NULL default '5', 
 trackback_field int(4) unsigned NOT NULL,
 deft_category varchar(60) NOT NULL,
 deft_comments char(1) NOT NULL default 'y',
 deft_trackbacks char(1) NOT NULL default 'y',
 weblog_require_membership char(1) NOT NULL default 'y',
 weblog_max_chars int(5) unsigned NOT NULL,
 weblog_html_formatting char(4) NOT NULL default 'all',
 weblog_allow_img_urls char(1) NOT NULL default 'y',
 weblog_auto_link_urls char(1) NOT NULL default 'y',
 comment_system_enabled char(1) NOT NULL default 'y',
 comment_require_membership char(1) NOT NULL default 'n',
 comment_moderate char(1) NOT NULL default 'n',
 comment_max_chars int(5) unsigned NOT NULL,
 comment_timelock int(5) unsigned NOT NULL default '0',
 comment_require_email char(1) NOT NULL default 'y',
 comment_text_formatting char(5) NOT NULL default 'xhtml',
 comment_html_formatting char(4) NOT NULL default 'safe',
 comment_allow_img_urls char(1) NOT NULL default 'n',
 comment_auto_link_urls char(1) NOT NULL default 'y',
 comment_notify char(1) NOT NULL default 'n',
 comment_notify_emails varchar(255) NOT NULL,
 PRIMARY KEY (weblog_id),
 KEY (cat_group),
 KEY (status_group),
 KEY (field_group)
)";



// Weblog Custom Field Groups

$D[] = 'exp_field_groups';

$Q[] = "CREATE TABLE exp_field_groups (
 group_id int(4) unsigned NOT NULL auto_increment,
 group_name varchar(50) NOT NULL,
 PRIMARY KEY (group_id)
)"; 


// Weblog Titles
// We store weblog titles separately from weblog data

$D[] = 'exp_weblog_titles';

$Q[] = "CREATE TABLE exp_weblog_titles (
 entry_id int(10) unsigned NOT NULL auto_increment,
 weblog_id int(4) unsigned NOT NULL,
 author_id int(10) unsigned NOT NULL default '0',
 pentry_id int(10) NOT NULL default '0',
 ip_address varchar(16) NOT NULL,
 title varchar(100) NOT NULL,
 url_title varchar(75) NOT NULL,
 status varchar(50) NOT NULL,
 allow_comments varchar(1) NOT NULL default 'y',
 allow_trackbacks varchar(1) NOT NULL default 'y',
 sticky varchar(1) NOT NULL default 'n',
 entry_date int(10) NOT NULL,
 year char(4) NOT NULL,
 month char(2) NOT NULL,
 day char(3) NOT NULL,
 expiration_date int(10) NOT NULL default '0',
 edit_date timestamp(14),
 recent_comment_date int(10) NOT NULL,
 comment_total int(4) unsigned NOT NULL default '0',
 trackback_total int(4) unsigned NOT NULL default '0',
 sent_trackbacks text NOT NULL,
 recent_trackback_date int(10) NOT NULL,
 PRIMARY KEY (entry_id),
 KEY (weblog_id),
 KEY (author_id)
)";


// Weblog Custom Field Defenitions

$D[] = 'exp_weblog_fields';

$Q[] = "CREATE TABLE exp_weblog_fields (
 field_id int(6) unsigned NOT NULL auto_increment,
 group_id int(4) unsigned NOT NULL, 
 field_name varchar(32) NOT NULL,
 field_label varchar(50) NOT NULL,
 field_type varchar(12) NOT NULL default 'text',
 field_list_items text NOT NULL,
 field_ta_rows tinyint(2) default '8',
 field_maxl tinyint(3) NOT NULL,
 field_required char(1) NOT NULL default 'n',
 field_search char(1) NOT NULL default 'n',
 field_fmt char(5) NOT NULL default 'xhtml',
 field_order int(3) unsigned NOT NULL,
 PRIMARY KEY (field_id),
 KEY (group_id)
)";

// Weblog data

$D[] = 'exp_weblog_data';

$Q[] = "CREATE TABLE exp_weblog_data (
 entry_id int(10) unsigned NOT NULL,
 weblog_id int(4) unsigned NOT NULL,
 field_id_1 text NOT NULL,
 field_ft_1 char(5) NOT NULL default 'xhtml',
 field_id_2 text NOT NULL,
 field_ft_2 char(5) NOT NULL default 'xhtml',
 field_id_3 text NOT NULL,
 field_ft_3 char(5) NOT NULL default 'xhtml',
 KEY (entry_id)
)";


// Ping Status
// This table saves the status of the xml-rpc ping buttons
// that were selected when an entry was submitted.  This
// enables us to set the buttons to the same state when editing

$D[] = 'exp_entry_ping_status';

$Q[] = "CREATE TABLE exp_entry_ping_status (
 entry_id int(10) unsigned NOT NULL,
 ping_id int(10) unsigned NOT NULL
)";

// Comment table

$D[] = 'exp_comments';

$Q[] = "CREATE TABLE exp_comments (
 comment_id int(10) unsigned NOT NULL auto_increment,
 entry_id int(10) unsigned NOT NULL default '0',
 weblog_id int(4) unsigned NOT NULL,
 author_id int(10) unsigned NOT NULL default '0',
 status char(1) NOT NULL default 'o',
 name varchar(50) NOT NULL,
 email varchar(50) NOT NULL,
 url varchar(75) NOT NULL,
 location varchar(50) NOT NULL, 
 ip_address varchar(16) NOT NULL,
 comment_date int(10) NOT NULL,
 edit_date timestamp(14),
 comment text NOT NULL,
 notify char(1) NOT NULL default 'n',
 PRIMARY KEY (comment_id),
 KEY (entry_id),
 KEY (author_id)
)";

// Trackback table.

$D[] = 'exp_trackbacks';

$Q[] = "CREATE TABLE exp_trackbacks (
 trackback_id int(10) unsigned NOT NULL auto_increment,
 entry_id int(10) unsigned NOT NULL default '0',
 weblog_id int(4) unsigned NOT NULL,
 title varchar(100) NOT NULL,
 content text NOT NULL,
 weblog_name varchar(100) NOT NULL,
 trackback_url varchar(200) NOT NULL,
 trackback_date int(10) NOT NULL,
 trackback_ip varchar(16) NOT NULL,
 PRIMARY KEY (trackback_id),
 KEY (entry_id)
)";


// Dummy table used to emulate MySQL Unions
// Since Unions are not supported until MySQL 4
// this table lets us emulate a union.  We use it to 
// join comments and trackbacks into one result set.

$D[] = 'exp_temp_union';

$Q[] = "CREATE TABLE exp_temp_union (
 num int(2) unsigned NOT NULL
)";

// Status Groups

$D[] = 'exp_status_groups';

$Q[] = "CREATE TABLE exp_status_groups (
 group_id int(4) unsigned NOT NULL auto_increment,
 group_name varchar(50) NOT NULL,
 PRIMARY KEY (group_id)
)"; 

// Status data

$D[] = 'exp_statuses';

$Q[] = "CREATE TABLE exp_statuses (
 status_id int(6) unsigned NOT NULL auto_increment,
 group_id int(4) unsigned NOT NULL,
 status varchar(50) NOT NULL,
 status_order int(3) unsigned NOT NULL,
 highlight varchar(30) NOT NULL,
 PRIMARY KEY (status_id),
 KEY (group_id)
)"; 


// Category Groups
// Note: The is_user_blog field indicates whether the blog is
// assigned as a "user blogs" weblog

$D[] = 'exp_category_groups';

$Q[] = "CREATE TABLE exp_category_groups (
 group_id int(6) unsigned NOT NULL auto_increment,
 group_name varchar(50) NOT NULL,
 is_user_blog char(1) NOT NULL default 'n',
 PRIMARY KEY (group_id)
)"; 

// Category data

$D[] = 'exp_categories';

$Q[] = "CREATE TABLE exp_categories (
 cat_id int(10) unsigned NOT NULL auto_increment,
 group_id int(6) unsigned NOT NULL,
 parent_id int(4) unsigned NOT NULL,
 cat_name varchar(60) NOT NULL,
 cat_image varchar(120) NOT NULL,
 PRIMARY KEY (cat_id),
 KEY (group_id)
)"; 


// Category posts
// This table stores the weblog entry ID and the category IDs
// that are assigned to it

$D[] = 'exp_category_posts';

$Q[] = "CREATE TABLE exp_category_posts (
 entry_id int(10) unsigned NOT NULL,
 cat_id int(10) unsigned NOT NULL,
 KEY (entry_id),
 KEY (cat_id)
)"; 

// Control panel log

$D[] = 'exp_cp_log';

$Q[] = "CREATE TABLE exp_cp_log (
  id int(10) NOT NULL auto_increment,
  member_id int(10) unsigned NOT NULL,
  username varchar(32) NOT NULL,
  ip_address varchar(16) default '0' NOT NULL,
  act_date int(10) NOT NULL,
  action varchar(200) NOT NULL,
  PRIMARY KEY  (id)
)"; 

// HTML buttons
// These are the buttons that appear on the PUBLISH page.
// Each member can have their own set of buttons

$D[] = 'exp_html_buttons';

$Q[] = "CREATE TABLE exp_html_buttons (
  id int(10) unsigned NOT NULL auto_increment,  
  member_id int(10) default '0' NOT NULL,
  tag_name varchar(32) NOT NULL,
  tag_open varchar(120) NOT NULL,
  tag_close varchar(120) NOT NULL,
  accesskey varchar(32) NOT NULL,
  tag_order int(3) unsigned NOT NULL,
  tag_row char(1) NOT NULL default '1',
  PRIMARY KEY (id)
)";


// Ping Servers
// Each member can have their own set ping server definitions

$D[] = 'exp_ping_servers';

$Q[] = "CREATE TABLE exp_ping_servers (
  id int(10) unsigned NOT NULL auto_increment,  
  member_id int(10) default '0' NOT NULL,
  server_name varchar(32) NOT NULL,
  server_url varchar(100) NOT NULL,
  port varchar(4) NOT NULL default '80',
  ping_protocol varchar(12) NOT NULL default 'xmlrpc',
  is_default char(1) NOT NULL default 'y',
  server_order int(3) unsigned NOT NULL,
  PRIMARY KEY (id)
)";

// Template Groups
// Note:  The 'is_user_blog' field is used to indicate
// whether a template group has been assigned to a
// specific user as part of the "user blogs" module

$D[] = 'exp_template_groups';

$Q[] = "CREATE TABLE exp_template_groups (
 group_id int(6) unsigned NOT NULL auto_increment,
 group_name varchar(50) NOT NULL,
 group_order int(3) unsigned NOT NULL,
 is_site_default char(1) NOT NULL default 'n',
 is_user_blog char(1) NOT NULL default 'n',
 PRIMARY KEY (group_id)
)";

// Template data

$D[] = 'exp_templates';

$Q[] = "CREATE TABLE exp_templates (
 template_id int(10) unsigned NOT NULL auto_increment,
 group_id int(6) unsigned NOT NULL,
 template_name varchar(50) NOT NULL,
 template_type varchar(16) NOT NULL default 'webpage',
 template_data text NOT NULL,
 template_notes text NOT NULL,
 cache char(1) NOT NULL default 'n',
 refresh int(6) unsigned NOT NULL,
 no_auth_bounce varchar(50) NOT NULL,
 allow_php char(1) NOT NULL default 'n',
 php_parse_location char(1) NOT NULL default 'o',
 hits int(10) unsigned NOT NULL,
 PRIMARY KEY (template_id),
 KEY (group_id)
)"; 

// Template "no access"
// Since each template can be made private to specific member groups
// we store member IDs of people who can not access certain templates

$D[] = 'exp_template_no_access';

$Q[] = "CREATE TABLE exp_template_no_access (
 template_id int(6) unsigned NOT NULL,
 member_group tinyint(3) unsigned NOT NULL
)";

// Specialty Templates
// This table contains the various specialty templates, like:
// Admin notification of new members
// Admin notification of comments and trackbacks
// Membership activation instruction
// Member lost password instructions
// Validated member notification
// Remove from mailinglist notification

$D[] = 'exp_specialty_templates';

$Q[] = "CREATE TABLE exp_specialty_templates (
 template_id int(6) unsigned NOT NULL auto_increment,
 enable_template char(1) NOT NULL default 'y',
 template_name varchar(50) NOT NULL,
 data_title varchar(80) NOT NULL,
 template_data text NOT NULL,
 PRIMARY KEY (template_id),
 KEY (template_name)
)"; 

// Global variables
// These are user-definable variables

$D[] = 'exp_global_variables';

$Q[] = "CREATE TABLE exp_global_variables (
 variable_id int(6) unsigned NOT NULL auto_increment,
 variable_name varchar(50) NOT NULL,
 variable_data text NOT NULL,
 user_blog_id int(6) NOT NULL default '0',
 PRIMARY KEY (variable_id),
 KEY (variable_name)
)";

// Revision tracker
// This is our versioning table, used to store each
// change that is made to a template.

$D[] = 'exp_revision_tracker';

$Q[] = "CREATE TABLE exp_revision_tracker (
 tracker_id int(10) unsigned NOT NULL auto_increment,  
 item_id int(10) unsigned NOT NULL,
 item_table varchar(20) NOT NULL,
 item_field varchar(20) NOT NULL,
 item_date int(10) NOT NULL,
 item_data text NOT NULL,
 PRIMARY KEY (tracker_id),
 KEY (item_id)
)";


// Upload preferences

// Note: The is_user_blog field indicates whether the blog is
// assigned as a "user blogs" weblog

$D[] = 'exp_upload_prefs';

$Q[] = "CREATE TABLE exp_upload_prefs (
 id int(4) unsigned NOT NULL auto_increment,
 is_user_blog char(1) NOT NULL default 'n',
 name varchar(50) NOT NULL,
 server_path varchar(100) NOT NULL,
 url varchar(70) NOT NULL,
 allowed_types varchar(3) NOT NULL default 'img',
 max_size varchar(16) NOT NULL,
 max_height varchar(6) NOT NULL,
 max_width varchar(6) NOT NULL,
 properties varchar(120) NOT NULL,
 pre_format varchar(120) NOT NULL,
 post_format varchar(120) NOT NULL,
 PRIMARY KEY (id)
)";

// Upload "no access"
// We store the member groups that can not access various upload destinations

$D[] = 'exp_upload_no_access';

$Q[] = "CREATE TABLE exp_upload_no_access (
 upload_id int(6) unsigned NOT NULL,
 upload_loc varchar(3) NOT NULL,
 member_group tinyint(3) unsigned NOT NULL
)";


// Search results

$D[] = 'exp_search';

$Q[] = "CREATE TABLE exp_search (
 search_id varchar(32) NOT NULL,
 search_date int(10) NOT NULL,
 member_id int(10) unsigned NOT NULL,
 ip_address varchar(16) NOT NULL,
 total_results int(6) NOT NULL,
 per_page tinyint(3) unsigned NOT NULL,
 query text NOT NULL,
 result_page varchar(70) NOT NULL,
 PRIMARY KEY (search_id)
)";




//  Define default DB data
// --------------------------------------------------------------------
// --------------------------------------------------------------------


// Template data

$Q[] = "insert into exp_template_groups(group_id, group_name, group_order, is_site_default) values ('', 'weblog',  '1', 'y')";

$Q[] = "insert into exp_templates(template_id, group_id, template_name, template_data) values ('', '1', 'index', '".addslashes(deft_weblog())."')";
$Q[] = "insert into exp_templates(template_id, group_id, template_name, template_data) values ('', '1', 'comments', '".addslashes(deft_comments())."')";
$Q[] = "insert into exp_templates(template_id, group_id, template_name, template_data) values ('', '1', 'preview', '".addslashes(deft_comment_preview())."')";
$Q[] = "insert into exp_templates(template_id, group_id, template_name, template_data) values ('', '1', 'trackbacks', '".addslashes(deft_trackbacks())."')";
$Q[] = "insert into exp_templates(template_id, group_id, template_name, template_data) values ('', '1', 'archives', '".addslashes(deft_archives())."')";
$Q[] = "insert into exp_templates(template_id, group_id, template_name, template_data) values ('', '1', 'categories', '".addslashes(deft_cetegory_archives())."')";
$Q[] = "insert into exp_templates(template_id, group_id, template_name, template_type, template_data) values ('', '1', 'rss_1.0', 'rss', '".addslashes(deft_rss_1())."')";
$Q[] = "insert into exp_templates(template_id, group_id, template_name, template_type, template_data) values ('', '1', 'rss_2.0', 'rss', '".addslashes(deft_rss_2())."')";
$Q[] = "insert into exp_templates(template_id, group_id, template_name, template_type, template_data) values ('', '1', 'rss_atom', 'rss', '".addslashes(deft_rss_atom())."')";
$Q[] = "insert into exp_templates(template_id, group_id, template_name, template_type, template_data) values ('', '1', 'weblog_css', 'css', '".addslashes(deft_stylesheet())."')";
$Q[] = "insert into exp_templates(template_id, group_id, template_name, template_data) values ('', '1', 'smileys', '".addslashes(deft_smileys())."')";
$Q[] = "insert into exp_templates(template_id, group_id, template_name, template_data) values ('', '1', 'referrers', '".addslashes(deft_referrers())."')";

$Q[] = "insert into exp_template_groups(group_id, group_name, group_order) values ('', 'member', '2')";
$Q[] = "insert into exp_templates(template_id, group_id, template_name, template_data) values ('', '2', 'index', '".addslashes(member_index())."')";

$Q[] = "insert into exp_template_groups(group_id, group_name, group_order) values ('', 'search', '3')";
$Q[] = "insert into exp_templates(template_id, group_id, template_name, template_data) values ('', '3', 'index', '".addslashes(search_index())."')";
$Q[] = "insert into exp_templates(template_id, group_id, template_name, template_data) values ('', '3', 'results', '".addslashes(search_results())."')";
$Q[] = "insert into exp_templates(template_id, group_id, template_name, template_type, template_data) values ('', '3', 'search_css', 'css', '".addslashes(search_css())."')";


// Specialty templates

$Q[] = "insert into exp_specialty_templates(template_id, template_name, data_title, template_data) values ('', 'offline_template', '', '".addslashes(offline_template())."')";
$Q[] = "insert into exp_specialty_templates(template_id, template_name, data_title, template_data) values ('', 'message_template', '', '".addslashes(message_template())."')";
$Q[] = "insert into exp_specialty_templates(template_id, template_name, data_title, template_data) values ('', 'admin_notify_reg', '".addslashes(trim(admin_notify_reg_title()))."', '".addslashes(admin_notify_reg())."')";
$Q[] = "insert into exp_specialty_templates(template_id, template_name, data_title, template_data) values ('', 'admin_notify_comment', '".addslashes(trim(admin_notify_comment_title()))."', '".addslashes(admin_notify_comment())."')";
$Q[] = "insert into exp_specialty_templates(template_id, template_name, data_title, template_data) values ('', 'admin_notify_trackback', '".addslashes(trim(admin_notify_trackback_title()))."', '".addslashes(admin_notify_trackback())."')";
$Q[] = "insert into exp_specialty_templates(template_id, template_name, data_title, template_data) values ('', 'mbr_activation_instructions', '".addslashes(trim(mbr_activation_instructions_title()))."', '".addslashes(mbr_activation_instructions())."')";
$Q[] = "insert into exp_specialty_templates(template_id, template_name, data_title, template_data) values ('', 'forgot_password_instructions', '".addslashes(trim(forgot_password_instructions_title()))."', '".addslashes(forgot_password_instructions())."')";
$Q[] = "insert into exp_specialty_templates(template_id, template_name, data_title, template_data) values ('', 'reset_password_notification', '".addslashes(trim(reset_password_notification_title()))."', '".addslashes(reset_password_notification())."')";
$Q[] = "insert into exp_specialty_templates(template_id, template_name, data_title, template_data) values ('', 'validated_member_notify', '".addslashes(trim(validated_member_notify_title()))."', '".addslashes(validated_member_notify())."')";
$Q[] = "insert into exp_specialty_templates(template_id, template_name, data_title, template_data) values ('', 'mailinglist_activation_instructions', '".addslashes(trim(mailinglist_activation_instructions_title()))."', '".addslashes(mailinglist_activation_instructions())."')";
$Q[] = "insert into exp_specialty_templates(template_id, template_name, data_title, template_data) values ('', 'comment_notification', '".addslashes(trim(comment_notification_title()))."', '".addslashes(comment_notification())."')";


// Mock table union

$Q[] = "insert into exp_temp_union (num) values ('0')";
$Q[] = "insert into exp_temp_union (num) values ('1')";

// Default weblog preference data

$Q[] = "insert into exp_weblogs (weblog_id, cat_group, blog_name, blog_title, blog_url, blog_lang, blog_encoding, total_entries, last_entry_date, status_group, deft_status, field_group, deft_comments, deft_trackbacks, trackback_field, comment_max_chars, comment_require_email, comment_require_membership, weblog_require_membership, comment_text_formatting, search_excerpt)  values ('', '1', 'weblog1', '".$DB->escape_str($data['site_name'])."', '".$data['site_url'].$data['site_index']."/weblog/index/', 'en', 'utf-8', '1', '$now', '1', 'open', '1', 'y', 'y', '2', '5000', 'y', 'n', 'y', 'xhtml', '2')";

// Custom field and field group data

$Q[] = "insert into exp_field_groups(group_id, group_name) values ('', 'Default Field Group')";

$Q[] = "insert into exp_weblog_fields(field_id, group_id, field_name, field_label, field_type, field_list_items, field_ta_rows, field_search, field_order) values ('', '1', 'summary', 'Summary', 'textarea', '', '6', 'n', '1')";
$Q[] = "insert into exp_weblog_fields(field_id, group_id, field_name, field_label, field_type, field_list_items, field_ta_rows, field_search, field_order) values ('', '1', 'body', 'Body', 'textarea', '', '10', 'y', '2')";
$Q[] = "insert into exp_weblog_fields(field_id, group_id, field_name, field_label, field_type, field_list_items, field_ta_rows, field_search, field_order) values ('', '1', 'extended', 'Extended text', 'textarea', '', '12', 'n', '3')";

// Custom statuses

$Q[] = "insert into exp_status_groups (group_id, group_name) values ('', 'Default Status Group')";

$Q[] = "insert into exp_statuses (status_id, group_id, status, status_order, highlight) values ('', '1', 'open', '1', '009933')";
$Q[] = "insert into exp_statuses (status_id, group_id, status, status_order, highlight) values ('', '1', 'closed', '2', '990000')";

// Member groups

$Q[] = "insert into exp_member_groups values ('', 'Super Admins', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', '0')";
$Q[] = "insert into exp_member_groups values ('', 'Banned',       'y', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', '30')";
$Q[] = "insert into exp_member_groups values ('', 'Guests',       'y', 'n', 'y', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'y', 'y', 'y', '30')";
$Q[] = "insert into exp_member_groups values ('', 'Pending',      'y', 'n', 'y', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'y', '30')";
$Q[] = "insert into exp_member_groups values ('', 'Members',      'y', 'n', 'y', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'y', 'y', 'y', 'y', '30')";

// Register the default admin

$quick_link = 'My Weblog|'.$data['site_url'].$data['site_index'].'|1';

$Q[] = "insert into exp_members (member_id, group_id, username, password, unique_id, email, screen_name, join_date, ip_address, timezone, daylight_savings, total_entries, last_entry_date, quick_links, language) values ('', '1', '".$DB->escape_str($data['username'])."', '".$password."', '".$unique_id."', '".$DB->escape_str($data['email'])."', '".$DB->escape_str($data['screen_name'])."', '".$now."', '".$data['ip']."', '".$data['server_timezone']."', '".$data['daylight_savings']."', '1', '".$now."', '$quick_link', '".$DB->escape_str($data['deft_lang'])."')";
$Q[] = "insert into exp_member_homepage (member_id, recent_entries_order, recent_comments_order, site_statistics_order, notepad_order) values ('1', '1', '2', '1', '2')";

// Default system stats

$Q[] = "insert into exp_stats (total_members, total_entries, last_entry_date) values ('1', '1', '".$now."')";

// HTML formatting buttons

$Q[] = "insert into exp_html_buttons values ('', '0', '<b>', '<b>', '</b>', 'b', '1', '1')";
$Q[] = "insert into exp_html_buttons values ('', '0', '<i>', '<i>', '</i>', 'i', '2', '1')";
$Q[] = "insert into exp_html_buttons values ('', '0', '<u>', '<u>', '</u>', 'u', '3', '1')";
$Q[] = "insert into exp_html_buttons values ('', '0', '<bq>', '<blockquote>', '</blockquote>', 'q', '4', '1')";
$Q[] = "insert into exp_html_buttons values ('', '0', '<strike>', '<strike>', '</strike>', 's', '5', '1')";

// Ping servers

$Q[] = "insert into exp_ping_servers values ('', '0', 'weblogs.com', 'http://rpc.weblogs.com/RPC2', '80', 'xmmlrpc', 'n', '1')";
$Q[] = "insert into exp_ping_servers values ('', '0', 'blo.gs', 'http://ping.blo.gs/', '80', 'xmmlrpc', 'n', '2')";
$Q[] = "insert into exp_ping_servers values ('', '0', 'blogrolling.com', 'http://rpc.blogrolling.com/pinger/', '80', 'xmmlrpc', 'n', '3')";
$Q[] = "insert into exp_ping_servers values ('', '0', 'blogshares.com', 'http://www.blogshares.com/rpc.php', '80', 'xmmlrpc', 'n', '4')";

// Create default categories

$Q[] = "insert into exp_category_groups (group_id, group_name, is_user_blog) values ('', 'Default Category Group', 'n')";

$Q[] = "insert into exp_categories (cat_id, group_id, parent_id, cat_name) values ('', '1', '0', 'Blogging')";
$Q[] = "insert into exp_categories (cat_id, group_id, parent_id, cat_name) values ('', '1', '0', 'Personal')";
$Q[] = "insert into exp_categories (cat_id, group_id, parent_id, cat_name) values ('', '1', '0', 'News')";

$Q[] = "insert into exp_category_posts (entry_id, cat_id) values ('1', '1')";

// Create a default weblog entry

$Q[] = "insert into exp_weblog_titles (entry_id, weblog_id, author_id, ip_address, entry_date, year, month, day, title, url_title, status) values ('', '1', '1',  '".$data['ip']."', '".$now."', '".$year."', '".$month."', '".$day."', 'First Entry', 'first_entry', 'open')";
$Q[] = "insert into exp_weblog_data (entry_id, weblog_id, field_id_2, field_ft_1, field_ft_2, field_ft_3) values ('1', '1', 'This is a sample weblog entry. You can delete it from your Control Panel.', 'xhtml', 'xhtml', 'xhtml')";

// Upload prefs
$props = "border=\"0\" alt=\"image\" name=\"image\"";
$Q[] = "insert into exp_upload_prefs (id, name, server_path, url, allowed_types, properties) values ('', 'Main Upload Directory', '".$data['image_path'].$data['upload_folder']."', '".$data['site_url'].'images/'.$data['upload_folder']."', 'all', '$props')";

// Actions

// Comment module
$Q[] = "INSERT INTO exp_modules (module_id, module_name, module_version, has_cp_backend) VALUES ('', 'Comment', '1.0', 'n')";
$Q[] = "INSERT INTO exp_actions (action_id, class, method) VALUES ('', 'Comment', 'insert_new_comment')";
$Q[] = "INSERT INTO exp_actions (action_id, class, method) VALUES ('', 'Comment_CP', 'delete_comment_notification')";

// Emoticon module
$Q[] = "INSERT INTO exp_modules (module_id, module_name, module_version, has_cp_backend) VALUES ('', 'Emoticon', '1.0', 'n')";

// Mailing List module
$Q[] = "INSERT INTO exp_modules (module_id, module_name, module_version, has_cp_backend) VALUES ('', 'Mailinglist', '1.0', 'y')";
$Q[] = "INSERT INTO exp_actions (action_id, class, method) VALUES ('', 'Mailinglist', 'insert_new_email')";
$Q[] = "INSERT INTO exp_actions (action_id, class, method) VALUES ('', 'Mailinglist', 'authorize_email')";
$Q[] = "INSERT INTO exp_actions (action_id, class, method) VALUES ('', 'Mailinglist', 'unsubscribe')";

// Member module
$Q[] = "INSERT INTO exp_modules (module_id, module_name, module_version, has_cp_backend) VALUES ('', 'Member', '1.0', 'n')";
$Q[] = "INSERT INTO exp_actions (action_id, class, method) VALUES ('', 'Member', 'registration_form')";
$Q[] = "INSERT INTO exp_actions (action_id, class, method) VALUES ('', 'Member', 'register_member')";
$Q[] = "INSERT INTO exp_actions (action_id, class, method) VALUES ('', 'Member', 'activate_member')";
$Q[] = "INSERT INTO exp_actions (action_id, class, method) VALUES ('', 'Member', 'member_login')";
$Q[] = "INSERT INTO exp_actions (action_id, class, method) VALUES ('', 'Member', 'member_logout')";
$Q[] = "INSERT INTO exp_actions (action_id, class, method) VALUES ('', 'Member', 'retrieve_password')";
$Q[] = "INSERT INTO exp_actions (action_id, class, method) VALUES ('', 'Member', 'reset_password')";
$Q[] = "INSERT INTO exp_actions (action_id, class, method) VALUES ('', 'Member', 'send_member_email')";

// Query module
$Q[] = "INSERT INTO exp_modules (module_id, module_name, module_version, has_cp_backend) VALUES ('', 'Query', '1.0', 'n')";

// Referrer module
$Q[] = "INSERT INTO exp_modules (module_id, module_name, module_version, has_cp_backend) VALUES ('', 'Referrer', '1.1', 'y')";

// RSS module
$Q[] = "INSERT INTO exp_modules (module_id, module_name, module_version, has_cp_backend) VALUES ('', 'Rss', '1.0', 'n')";

// Stats module
$Q[] = "INSERT INTO exp_modules (module_id, module_name, module_version, has_cp_backend) VALUES ('', 'Stats', '1.0', 'n')";

// Trackback module
$Q[] = "INSERT INTO exp_modules (module_id, module_name, module_version, has_cp_backend) VALUES ('', 'Trackback', '1.0', 'n')";
$Q[] = "INSERT INTO exp_actions (action_id, class, method) VALUES ('', 'Trackback_CP', 'receive_trackback')";

// Weblog module
$Q[] = "INSERT INTO exp_modules (module_id, module_name, module_version, has_cp_backend) VALUES ('', 'Weblog', '1.0', 'n')";

// Search module
$Q[] = "INSERT INTO exp_modules (module_id, module_name, module_version, has_cp_backend) VALUES ('', 'Search', '1.0', 'n')";
$Q[] = "INSERT INTO exp_actions (action_id, class, method) VALUES ('', 'Search', 'do_search')";

// Email module
$Q[] = "INSERT INTO exp_modules (module_id, module_name, module_version, has_cp_backend) VALUES ('', 'Email', '1.0', 'n')";
$Q[] = "INSERT INTO exp_actions (action_id, class, method) VALUES ('', 'Email', 'send_email')";


//  Create DB tables and insert data
// --------------------------------------------------------------------
// --------------------------------------------------------------------

    foreach($D as $kill)
    {
        $DB->query('drop table if exists '.$kill);
    }
        
    foreach($Q as $sql)
    {    
        if ($DB->query($sql) === FALSE)
        {
            echo "<div class='error'>Error: Unable to perform the SQL queries needed to install this program..</div>";
            settings_form();
            page_footer();
            exit;
        }
    }


// WRITE CONFIG FILE
// --------------------------------------------------------------------
// --------------------------------------------------------------------
    
    if ( ! ereg("/$", $data['cp_url'])) $data['cp_url'] .= '/';
        

    $config = array(
					'app_version'				=>	$data['app_version'],
                    'debug'                 	=>  '2',
                    'install_lock'          	=>  '1',
                    'db_hostname'           	=>  $data['db_hostname'],
                    'db_username'           	=>  $data['db_username'],
                    'db_password'           	=>  $data['db_password'],
                    'db_name'               	=>  $data['db_name'],
                    'db_type'               	=>  $data['database'],
                    'db_prefix'             	=>  ($data['db_prefix']  != '') ? $data['db_prefix']  : 'exp',
                    'db_conntype'           	=>  $data['db_conntype'],
                    'system_folder'         	=>  $data['system_dir'],
                    'cp_url'	            	=>  $data['cp_url'].$data['cp_index'],
                    'site_index'            	=>  $data['site_index'],
                    'site_name'              	=>  $data['site_name'],
                    'site_url'              	=>  $data['site_url'],
                    'doc_url'              		=>  $data['site_url'].'user_guide/',
                    'webmaster_email'       	=>  $data['webmaster_email'],
                    'enable_db_caching'	        =>  'y',
                    'db_cache_refresh'	        =>  '30',
                    'force_query_string'        =>  'n',
                    'safe_mode'        			=>  'n',
                    'show_queries'           	=>  'n',
                    'cookie_domain'         	=>  '',
                    'cookie_path'           	=>  '',
                    'cookie_prefix'         	=>  '',
                    'user_session_type'     	=>  'c', 
                    'admin_session_type'    	=>  'cs',
                    'allow_username_change' 	=>  'y',
                    'allow_multi_logins'    	=>  'y',
                    'password_lockout'			=>	'y',
                    'password_lockout_interval' =>  '1',
                    'require_ip_for_login'		=>  'y',
                    'allow_multi_emails'    	=>  'n',
                    'require_secure_passwords'  =>  'n',
                    'allow_dictionary_pw'  		=>  'y',
                    'name_of_dictionary_file'	=>	'',
                    'redirect_method'       	=>  $data['redirect_method'],
                    'deft_lang'             	=>  $data['deft_lang'],
                    'xml_lang'              	=>  'en',
                    'charset'               	=>  'utf-8',
                    'send_headers'          	=>  'y',
                    'gzip_output'           	=>  'n',
                    'log_referrers'         	=>  'y',
                    'is_system_on'          	=>  'y',
                    'time_format'           	=>  'us',
                    'server_timezone'       	=>  $data['server_timezone'],
                    'server_offset'         	=>  '',
                    'daylight_savings'      	=>  $data['daylight_savings'],
                    'mail_protocol'         	=>  'mail',
                    'smtp_server'           	=>  '',
                    'smtp_username'         	=>  '',
                    'smtp_password'         	=>  '',
                    'email_batchmode'       	=>  'n',
                    'email_batch_size'      	=>  '',
                    'mail_format'           	=>  'plain',
                    'word_wrap'             	=>  'y',
                    'email_console_timelock'	=>	'5',
                    'log_email_console_msgs'	=>	'y',
                    'cp_theme'              	=>  'default',
                    'un_min_len'            	=>  '4',
                    'pw_min_len'            	=>  '5',
                    'allow_member_registration' =>  'y',
                    'req_mbr_activation'    	=>  'email',
                    'new_member_notification'	=>	'n',
                    'mbr_notification_emails'	=>	'',
                    'require_terms_of_service'	=>	'y',
                    'default_member_group'  	=>  '5',
                    'member_theme'			  	=>  'default',
                    'member_images'				=>  $data['site_url'].'images/'.$data['member_images'],
                    'save_tmpl_revisions'   	=>  'y',
                    'secure_forms'          	=>  'y',
                    'deny_duplicate_data'       =>  'y',
                    'enable_censoring'      	=>  'n',
                    'censored_words'       		=>  '',
                    'banned_ips'            	=>  '',
                    'banned_emails'         	=>  '',
                    'banned_usernames'			=>	'',
                    'banned_screen_names'		=>	'',
                    'ban_action'            	=>  'restrict',
                    'ban_message'           	=>  'This site is currently unavailable',
                    'ban_destination'       	=>  'http://www.yahoo.com/',
                    'enable_emoticons'      	=>  'y',
                    'emoticon_path'         	=>  $data['site_url'].'images/smileys/',
                    'recount_batch_total'   	=>  '1000',
                    'enable_image_resizing'		=>	'y',
                    'image_resize_protocol'		=>	'gd2',
                    'image_library_path'		=>	'',
                    'thumbnail_prefix'			=>	'thumb',
                    'word_separator'			=>	'_'
                  );



// Write config file
// --------------------------------------------------------------------
// --------------------------------------------------------------------

     
		$conf  = "<?php\n\n";
		$conf .= "if ( ! defined('EXT')){\nexit('Invalid file request');\n}\n\n";
     
        foreach ($config as $key => $val)
        {
            $conf .= "\$conf['".$key."'] = \"".$val."\";\n";
        } 
        
        $conf .= '?'.'>';
             
        $cfile = './'.$data['system_dir'].'/config.php';
    
        if ( ! $fp = @fopen($cfile, 'wb'))
        {
            echo "<div class='error'>Error: unable to write the config file.</div>";
            page_footer();
            exit;
        }                
        
        fwrite($fp, $conf);
        fclose($fp);
        
        $cbfile = './'.$data['system_dir'].'/config_bak.php';
        
        if ($fp = @fopen($cbfile, 'wb'))
        {
            fwrite($fp, $conf);
            fclose($fp);
        }                


        
// Write the path.php file
// --------------------------------------------------------------------
// --------------------------------------------------------------------

		$path  = "<?php\n\n";
		
		$path .= '// DO NOT ALTER THIS FILE UNLESS YOU HAVE A REASON TO'."\n\n";
		$path .= '// Path to the directory containing your backend files'."\n\n";
		$path .= '$system_path = "./'.$data['system_dir'].'/"'.";\n\n";
		$path .= '$template_group = "";'."\n";
		$path .= '$template = "";'."\n";
		$path .= '$site_url = "";'."\n";
		$path .= '$site_index = "";'."\n\n";
		        
        $path .= '?'.'>';
                 
        if ( ! $fp = @fopen('path.php', 'wb'))
        {
            echo "<div class='error'>Error: unable to write the path.php file.</div>";
            page_footer();
            exit;
        }                
        
        fwrite($fp, $path);
        fclose($fp);

        
// Create cache directories
// --------------------------------------------------------------------
// --------------------------------------------------------------------

        $cache_path = './'.$data['system_dir'].'/cache/';
        
        $cache_dirs = array('db_cache', 'page_cache', 'tag_cache');
        
        $errors = array();
        
        foreach ($cache_dirs as $dir)
        {
            if ( ! is_dir($cache_path.$dir))
            {
                if ( ! @mkdir($cache_path.$dir, 0777))
                {
                    $errors[] = $dir;
                    
                    continue;
                }
                    
                @chmod($cache_path.$dir, 0777);
            }
       } 
        
        
       
// Show "success" page
// --------------------------------------------------------------------
// --------------------------------------------------------------------
    ?>        
    
    <h3>ExpressionEngine has been successfully installed!</h3>
    
    <?php
    
    if (count($errors) > 0)
    {
    ?>
    <p><span class="red">Please Note:  There was a problem creating your caching directories.  This is not a critical problem, but you may be unable to use the cachinng feature.</span></p>
    <?php
    }
    ?>
    
    <div class="border"><p><span class="red"><b>Important:</b>&nbsp; Using your FTP program, please delete the file called <b>install.php</b> from your server.  Leaving it on your server presents a security risk.</span></p></div>
    
    <p><a href='./<?php echo $data['system_dir']; ?>/index.php' target="_blank">Click here to access your control panel</a></p>
  
    <p><a href='<?php echo $data['site_url'].$data['site_index']; ?>'  target="_blank">Click here to view your weblog</a></p>
    
    <?php
}

// END PAGES
// --------------------------------------------------------------------
// --------------------------------------------------------------------




//  System folder form
// --------------------------------------------------------------------
// --------------------------------------------------------------------


function system_folder_form()
{
    global $data;
    
    
    $dir = ( ! isset($data['system_dir'])) ? 'system' : $data['system_dir'];    

?>
    <h2>Name of your "system" folder</h2>
    
    <p>As a security precaution you may have renamed the "<b>system</b>" folder, as instructed in Step 2.</p>
    
    <p>Please indicate the name here:</p>
    
    <form method='post' action='install.php?page=3'>
    
    <input type='text' name='system_dir' value='<?php echo $dir; ?>' size='20' class='input'>
    
    <br />
    
    <input type='submit' value'Submit' class='submit'>
    
    </form>
    
    </p>
<?php
}





//  Database Settings form
// --------------------------------------------------------------------
// --------------------------------------------------------------------

function settings_form()
{
    global $_SERVER, $data;
    
    $pathinfo = pathinfo(__FILE__);

    $self = $pathinfo['basename'];  
    
    $path = "http://" . $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];  
    
    $path = substr($path, 0, - strlen($self));  
    
    $dir = ($data['system_dir'] == '') ? 'system'  : $data['system_dir'];  
    $cp_url = ($data['cp_url'] == '') ? $path.$dir.'/' : $data['cp_url'];   
    $site_url = ($data['site_url'] == '') ? $path : $data['site_url'];   
    $site_index = ($data['site_index'] == '') ? 'index.php' : $data['site_index'];   

    $db_hostname        = ($data['db_hostname'] == '')      ? 'localhost' : stripslashes($data['db_hostname']);   
    $db_username        = ($data['db_username'] == '')      ? ''  	: stripslashes($data['db_username']);   
    $db_password        = ($data['db_password'] == '')      ? ''  	: stripslashes($data['db_password']);   
    $db_name            = ($data['db_name'] == '')          ? ''  	: stripslashes($data['db_name']);       
    $db_prefix          = ($data['db_prefix'] == '')        ? 'exp' : stripslashes($data['db_prefix'] );       
    $username           = ($data['username'] == '')         ? ''  	: stripslashes($data['username']);   
    $password           = ($data['password'] == '')         ? ''  	: stripslashes($data['password']);   
    $email              = ($data['email'] == '')            ? ''  	: stripslashes($data['email']);     
    $screen_name        = ($data['screen_name'] == '')      ? ''  	: stripslashes($data['screen_name']);     
    $redirect_method    = ($data['redirect_method'] == '')  ? ''  	: stripslashes($data['redirect_method']);     
    $daylight_savings   = ($data['daylight_savings'] == '') ? ''  	: stripslashes($data['daylight_savings']);     
    $webmaster_email    = ($data['webmaster_email'] == '')  ? ''  	: stripslashes($data['webmaster_email']);
    $template    		= ($data['template'] == '')  		? '01'	: stripslashes($data['template']);
    $site_name   		= ($data['site_name'] == '') 		? ''	: stripslashes($data['site_name']);     
    $deft_lang   		= ($data['deft_lang'] == '') 		? 'english'	: stripslashes($data['deft_lang']);     
	$timezone 			= ($data['server_timezone'] == '') ? 'UTC' 		: $data['server_timezone'];

    if ($redirect_method == '' || $redirect_method == 'redirect')
    {
        $redirect = 'checked="checked"';
        $refresh  = '';
    }
    else
    {
        $refresh  = 'checked="checked"';
        $redirect = '';
    }
    
    if ($daylight_savings == 'y')
    {
        $dst1 = 'checked="checked"';
        $dst2  = '';
    }
    else
    {
        $dst2  = 'checked="checked"';
        $dst1 = '';
    }
    

?>

<p><span class="red"><b>Note: </b> If you are not sure what any of these settings should be, please contact your hosting provider and ask them.</span></p>

<h2><br />Server Settings</h2>

<form method='post' action='install.php?page=4'>
<input type='hidden' name='system_dir' value='<?php echo $dir; ?>'>


<h5>Name of the index page of your ExpressionEngine site</h5>
<p>Unless you renamed the file, it will be called <b>index.php</b></p>
<p><input type='text' name='site_index' value='<?php echo $site_index; ?>' size='60'  class='input'></p>


<h5>URL to the directory where the above index page is located</h5>
<p>Typically this will be the root of your site (http://www.yourdomain.com/)</p>
<p>Do not include the index page in the URL</p>

<p><input type='text' name='site_url' value='<?php echo $site_url; ?>' size='60'  class='input'></p>


<h5>URL to your "<?php echo $dir; ?>" directory</h5> 
<p><input type='text' name='cp_url' value='<?php echo $cp_url; ?>' size='60'  class='input'></p>


<h5>Email address of webmaster</h5>

<p><input type='text' name='webmaster_email' value='<?php echo $webmaster_email; ?>' size='30'  class='input'></p>



<h5>What type of server are your hosted on?</h5>
<p>If you don't know, choose Unix</p>
<p>
<input type="radio" class='radio' name="redirect_method" value="redirect" <?php echo $redirect; ?> /> Unix (or Unix variant, like Linux, Mac OS X, BSD, Solaris, etc.)<br />
<input type="radio" class='radio' name="redirect_method" value="refresh"  <?php echo $refresh; ?> /> Windows (NT or IIs)
</p>


<h2><br />Database Settings</h2>

<h5>SQL Server Address</h5>
<p>(usually 'localhost')</p>

<p><input type='text' name='db_hostname' value='<?php echo $db_hostname; ?>' size='30' maxlength='60' class='input' /></p>


<h5>SQL Username</h5>

<p><input type='text' name='db_username' value='<?php echo $db_username; ?>' size='30'  maxlength='60' class='input' /></p>


<h5>SQL Password</h5>

<p><input type='text' name='db_password' value='<?php echo $db_password; ?>' size='30'  maxlength='50' class='input' /></p>


<h5>SQL Database</h5>

<p><input type='text' name='db_name' value='<?php echo $db_name; ?>' size='30' maxlength='60' class='input' /></p>


<h5>Database Prefix</h5>
<p>Use <b>exp</b> unless you need to use a different prefix</p>

<p><input type='text' name='db_prefix' value='<?php echo $db_prefix; ?>' size='12'  maxlength='30' class='input' /></p>


<h2><br />Create your admin account</h2>

<p>You will use these settings to access  your ExpressionEngine control panel</p>

<h5>Username</h5>
<p><span class='red'>Use at least four characters</span></p>

<p><input type='text' name='username' value='<?php echo $username; ?>' size='30' maxlength='50' class='input' /></p>

<h5>Password</h5>
<p><span class='red'>Use at least five characters</span></p>

<p><input type='text' name='password' value='<?php echo $password; ?>' size='30' maxlength='32' class='input' /></p>

<h5>Your email address</h5>

<p><input type='text' name='email' value='<?php echo $email; ?>' size='30'  maxlength='80' class='input' /></p>


<h5>Screen Name</h5>
<p>This is the name that will appear on your weblog entries</p>
<p>If you leave this field blank, your username will be used as your screen name</p>
<p><input type='text' name='screen_name' value='<?php echo $screen_name; ?>' size='30' maxlength='50' class='input' /></p>



<h5>Name of your site</h5>

<p><input type='text' name='site_name' value='<?php echo $site_name; ?>' size='30' class='input'></p>

<h2><br />Localization Settings</h2>


<h5>Choose Your Default Language</h5>

<p>
<?php echo language_pack_names($deft_lang); ?>
</p>




<h5>Your Time Zone</h5>

<p>


<select name='server_timezone' class='select'>

<?php $selected = ($timezone == 'UM12') ? " selected" : ""; ?>
<option value='UM12'<?php echo $selected; ?>>(UTC - 12:00) Enitwetok, Kwajalien</option>
<?php $selected = ($timezone == 'UM11') ? " selected" : ""; ?>
<option value='UM11'<?php echo $selected; ?>>(UTC - 11:00) Nome, Midway Island, Samoa</option>
<?php $selected = ($timezone == 'UM10') ? " selected" : ""; ?>
<option value='UM10'<?php echo $selected; ?>>(UTC - 10:00) Hawaii</option>
<?php $selected = ($timezone == 'UM9') ? " selected" : ""; ?>
<option value='UM9'<?php echo $selected; ?>>(UTC - 9:00) Alaska</option>
<?php $selected = ($timezone == 'UM8') ? " selected" : ""; ?>
<option value='UM8'<?php echo $selected; ?>>(UTC - 8:00) Pacific Time</option>
<?php $selected = ($timezone == 'UM7') ? " selected" : ""; ?>
<option value='UM7'<?php echo $selected; ?>>(UTC - 7:00) Mountain Time</option>
<?php $selected = ($timezone == 'UM6') ? " selected" : ""; ?>
<option value='UM6'<?php echo $selected; ?>>(UTC - 6:00) Central Time, Mexico City</option>
<?php $selected = ($timezone == 'UM5') ? " selected" : ""; ?>
<option value='UM5'<?php echo $selected; ?>>(UTC - 5:00) Eastern Time, Bogota, Lima, Quito</option>
<?php $selected = ($timezone == 'UM4') ? " selected" : ""; ?>
<option value='UM4'<?php echo $selected; ?>>(UTC - 4:00) Atlantic Time, Caracas, La Paz</option>
<?php $selected = ($timezone == 'UM25') ? " selected" : ""; ?>
<option value='UM25'<?php echo $selected; ?>>(UTC - 3:30) Newfoundland</option>
<?php $selected = ($timezone == 'UM3') ? " selected" : ""; ?>
<option value='UM3'<?php echo $selected; ?>>(UTC - 3:00) Brazil, Buenos Aires, Georgetown, Falkland Is.</option>
<?php $selected = ($timezone == 'UM2') ? " selected" : ""; ?>
<option value='UM2'<?php echo $selected; ?>>(UTC - 2:00) Mid-Atlantic, Ascention Is., St Helena</option>
<?php $selected = ($timezone == 'UM1') ? " selected" : ""; ?>
<option value='UM1'<?php echo $selected; ?>>(UTC - 1:00) Azores, Cape Verde Islands</option>
<?php $selected = ($timezone == 'UTC') ? " selected" : ""; ?>
<option value='UTC'<?php echo $selected; ?>>(UTC) Casablanca, Dublin, Edinburgh, London, Lisbon, Monrovia</option>
<?php $selected = ($timezone == 'UP1') ? " selected" : ""; ?>
<option value='UP1'<?php echo $selected; ?>>(UTC + 1:00) Berlin, Brussels, Copenhagen, Madrid, Paris, Rome</option>
<?php $selected = ($timezone == 'UP2') ? " selected" : ""; ?>
<option value='UP2'<?php echo $selected; ?>>(UTC + 2:00) Kaliningrad, South Africa, Warsaw</option>
<?php $selected = ($timezone == 'UP3') ? " selected" : ""; ?>
<option value='UP3'<?php echo $selected; ?>>(UTC + 3:00) Baghdad, Riyadh, Moscow, Nairobi</option>
<?php $selected = ($timezone == 'UP25') ? " selected" : ""; ?>
<option value='UP25'<?php echo $selected; ?>>(UTC + 3:30) Tehran</option>
<?php $selected = ($timezone == 'UP4') ? " selected" : ""; ?>
<option value='UP4'<?php echo $selected; ?>>(UTC + 4:00) Adu Dhabi, Baku, Muscat, Tbilisi</option>
<?php $selected = ($timezone == 'UP35') ? " selected" : ""; ?>
<option value='UP35'<?php echo $selected; ?>>(UTC + 4:30) Kabul</option>
<?php $selected = ($timezone == 'UP5') ? " selected" : ""; ?>
<option value='UP5'<?php echo $selected; ?>>(UTC + 5:00) Islamabad, Karachi, Tashkent</option>
<?php $selected = ($timezone == 'UP45') ? " selected" : ""; ?>
<option value='UP45'<?php echo $selected; ?>>(UTC + 5:30) Bombay, Calcutta, Madras, New Delhi</option>
<?php $selected = ($timezone == 'UP6') ? " selected" : ""; ?>
<option value='UP6'<?php echo $selected; ?>>(UTC + 6:00) Almaty, Colomba, Dhakra</option>
<?php $selected = ($timezone == 'UP7') ? " selected" : ""; ?>
<option value='UP7'<?php echo $selected; ?>>(UTC + 7:00) Bangkok, Hanoi, Jakarta</option>
<?php $selected = ($timezone == 'UP8') ? " selected" : ""; ?>
<option value='UP8'<?php echo $selected; ?>>(UTC + 8:00) Beijing, Hong Kong, Perth, Singapore, Taipei</option>
<?php $selected = ($timezone == 'UP9') ? " selected" : ""; ?>
<option value='UP9'<?php echo $selected; ?>>(UTC + 9:00) Osaka, Sapporo, Seoul, Tokyo, Yakutsk</option>
<?php $selected = ($timezone == 'UP85') ? " selected" : ""; ?>
<option value='UP85'<?php echo $selected; ?>>(UTC + 9:30) Adelaide, Darwin</option>
<?php $selected = ($timezone == 'UP10') ? " selected" : ""; ?>
<option value='UP10'<?php echo $selected; ?>>(UTC + 10:00) Melbourne, Papua New Guinea, Sydney, Vladivostok</option>
<?php $selected = ($timezone == 'UP11') ? " selected" : ""; ?>
<option value='UP11'<?php echo $selected; ?>>(UTC + 11:00) Magadan, New Caledonia, Solomon Islands</option>
<?php $selected = ($timezone == 'UP12') ? " selected" : ""; ?>
<option value='UP12'<?php echo $selected; ?>>(UTC + 12:00) Auckland, Wellington, Fiji, Marshall Island</option>
</select>
</p>


<p>Are you currently observing Daylight Saving Time?<br />
<input  class='radio' type="radio" name="daylight_savings" value="y" <?php echo $dst1; ?> /> Yes &nbsp;&nbsp;<input type="radio"  class='radio' name="daylight_savings" value="n" <?php echo $dst2; ?>  /> No
</p>


<h2><br />Choose your default template design</h2>

<p>

<select name='template' class='select'>
<?php $selected = ($template == '01') ? " selected" : ""; ?>
<option value='01'<?php echo $selected; ?>>Template 01</option>
<?php $selected = ($template == '02') ? " selected" : ""; ?>
<option value='02'<?php echo $selected; ?>>Template 02</option>
<?php $selected = ($template == '03') ? " selected" : ""; ?>
<option value='03'<?php echo $selected; ?>>Template 03</option>
<?php $selected = ($template == '04') ? " selected" : ""; ?>
<option value='04'<?php echo $selected; ?>>Template 04</option>
<?php $selected = ($template == '05') ? " selected" : ""; ?>
<option value='05'<?php echo $selected; ?>>Template 05</option>
<?php $selected = ($template == '06') ? " selected" : ""; ?>
<option value='06'<?php echo $selected; ?>>Template 06</option>
<?php $selected = ($template == '07') ? " selected" : ""; ?>
<option value='07'<?php echo $selected; ?>>Template 07</option>
<?php $selected = ($template == '08') ? " selected" : ""; ?>
<option value='08'<?php echo $selected; ?>>Template 08</option>
<?php $selected = ($template == '09') ? " selected" : ""; ?>
<option value='09'<?php echo $selected; ?>>Template 09</option>
<?php $selected = ($template == '10') ? " selected" : ""; ?>
<option value='10'<?php echo $selected; ?>>Template 10</option>
<?php $selected = ($template == '11') ? " selected" : ""; ?>
<option value='11'<?php echo $selected; ?>>Template 11</option>
<?php $selected = ($template == '12') ? " selected" : ""; ?>
<option value='12'<?php echo $selected; ?>>Template 12</option>
</select>
</p>


<p><br /><br /><input type='submit' value=' Click Here to Install ExpressionEngine! '  class='submit'></p>

</form>

<?php
}



// HTML FOOTER

page_footer();





//  HTML HEADER
// --------------------------------------------------------------------
// --------------------------------------------------------------------

function page_head()
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">

<head>
<title>ExpressionEngine | Installation Script</title>

<meta http-equiv='content-type' content='text/html; charset=UTF-8' />
<meta name='MSSmartTagsPreventParsing' content='TRUE' />
<meta http-equiv='expires' content='-1' />
<meta http-equiv= 'pragma' content='no-cache' />

<style type='text/css'>


body {
  margin:             0;
  padding:            0;
  font-family:        Verdana, Geneva, Helvetica, Trebuchet MS, Sans-serif;
  font-size:          12px;
  color:              #333;
  background-color:   #fff;
  }

a {
  font-size:          12px;
  text-decoration:    underline;
  font-weight:        bold;
  color:              #330099;
  background-color:   transparent;
  }
  
a:visited {
  color:              #330099;
  background-color:   transparent;
  }

a:active {
  color:              #ccc;
  background-color:   transparent;
  }

a:hover {
  color:              #000;
  text-decoration:    none;
  background-color:   transparent;
  }

.error {
  font-family:        Verdana, Trebuchet MS, Arial, Sans-serif;
  font-size:          13px;
  margin-bottom:      8px;
  font-weight:        bold;
  color:              #990000;
}

h1 {
  font-family:        Verdana, Trebuchet MS, Arial, Sans-serif;
  font-size:          20px;
  font-weight:        bold;
  color:              #000;
  margin-top:         15px;
  margin-bottom:      16px;
  background-color:   transparent;
  border-bottom:      #7B81A9 2px solid;
}

h2 {
  font-family:        Arial, Trebuchet MS, Verdana, Sans-serif;
  font-size:          18px;
  color:              #990000;
  letter-spacing:     2px;
  margin-top:         14px;
  margin-bottom:      8px;
  border-bottom:      #7B81A9 1px dashed;
  background-color:   transparent;
}
h3 {
  font-family:        Arial, Trebuchet MS, Verdana, Sans-serif;
  font-size:          18px;
  color:              #000;
  letter-spacing:     2px;
  margin-top:         15px;
  margin-bottom:      15px;
  border-bottom:      #7B81A9 1px dashed;
  background-color:   transparent;
}

h4 {
  font-family:        Verdana, Geneva, Trebuchet MS, Arial, Sans-serif;
  font-size:          16px;
  font-weight:        bold;
  color:              #333;
  margin-top:         5px;
  margin-bottom:      14px;
  background-color:   transparent;
}
h5 {
  font-family:        Verdana, Geneva, Trebuchet MS, Arial, Sans-serif;
  font-size:          12px;
  font-weight:        bold;
  color:              #000;
  margin-top:         16px;
  margin-bottom:      0;
  background-color:   transparent;
}

p {
  font-family:        Verdana, Geneva, Trebuchet MS, Arial, Sans-serif;
  font-size:          12px;
  font-weight:        normal;
  color:              #333;
  margin-top:         8px;
  margin-bottom:      8px;
  background-color:   transparent;
}

li {
  font-family:        Verdana, Trebuchet MS, Arial, Sans-serif;
  font-size:          11px;
  margin-bottom:      4px;
  color:              #000;
  margin-left:		  10px;
}

form {
  margin:         0;
}
.hidden {
  margin:         0;
  padding:        0;
  border:         0;
}
.input {
  border-top:         1px solid #999999;
  border-left:        1px solid #999999;
  background-color:   #fff;
  color:              #000;
  font-family:        Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
  font-size:          11px;
  height:             1.6em;
  padding:            .3em 0 0 2px;
  margin-top:          0;
  margin-bottom:       12px;
} 
.textarea {
  border-top:         1px solid #999999;
  border-left:        1px solid #999999;
  background-color:   #fff;
  color:              #000;
  font-family:        Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
  font-size:          11px;
  margin-top:         6px;
  margin-bottom:      3px;
}
.select {
  background-color:   #fff;
  font-family:        Arial, Verdana, Sans-serif;
  font-size:          10px;
  font-weight:        normal;
  letter-spacing:     .1em;
  color:              #000;
  margin-top:         6px;
  margin-bottom:      3px;
} 
.multiselect {
  border-top:         1px solid #999999;
  border-left:        1px solid #999999;
  background-color:   #fff;
  color:              #000;
  font-family:        Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
  font-size:          11px;
  margin-top:         6px;
  margin-bottom:      3px;
} 
.radio {
  color:              #000;
  margin-top:         7px;
  margin-bottom:      4px;
  padding:            0;
  border:             0;
  background-color:   transparent;
}
.checkbox {
  background-color:   transparent;
  margin:             3px;
  padding:            0;
  border:             0;
}
.submit {
  background-color:   #fff;
  font-family:        Arial, Verdana, Sans-serif;
  font-size:          10px;
  font-weight:        normal;
  letter-spacing:     .1em;
  padding:            1px 3px 1px 3px;
  margin-top:         6px;
  margin-bottom:      4px;
  text-transform:     uppercase;
  color:              #000;
}  


strong {
  font-weight: bold;
}

i {
  font-style: italic;
}

#simpleHeader {  
  background-color:   #828BD1;
  height:             40px;
  border-bottom:      #000 1px solid;
}
.solidLine { 
  border-top:          #999 1px solid;
  }
.logo {
  font-family:         Arial, Trebuchet MS, Verdana, Sans-serif;
  font-size:           14px;
  color:               #fff;
  height:              16px;
  letter-spacing:      0px;
  background:          transparent;
  text-align:          bottom;
  padding:             14px 0 0 20px; /* top right bottom left */ 
  }
  
.red {
  color:              #990000;
}
 
#content {
  left:          0px;
  right:         10px;
  margin:        0 35px 0 25px;
  }

.copyright {
  text-align:         center;
  font-family:        Verdana, Geneva, Helvetica, Trebuchet MS, Sans-serif;
  font-size:          9px;
  color:              #999999;
  line-height:        15px;
  margin-top:         20px;
  margin-bottom:      15px;
  padding:            20px;
  }
  
.border {
  border-bottom:      #7B81A9 1px dashed;
}

</style>

</head>

<body>

<div id='simpleHeader'>
<table border='0' cellspacing='0' cellpadding='0' width='96%'>
<tr>
      <td class='logo'>ExpressionEngine Public Beta 2, <font color="#FF0000" size="1">nullified 
        by GTT</font></td>
</tr>
</table>
</div>

<div id='content'>
<br />
<?php
}
// END




//  HTML FOOTER
// --------------------------------------------------------------------
// --------------------------------------------------------------------

function page_footer()
{
?>

<div class='copyright'>ExpressionEngine 1.0 - &#169; copyright 2003 - pMachine, Inc. - All Rights Reserved</div>

</div>

</body>
</html>
<?php
}




//  DEFAULT MESSAGE DATA
// --------------------------------------------------------------------
// --------------------------------------------------------------------



//  OFFLINE SYSTEM PAGE
// --------------------------------------------------------------------
// --------------------------------------------------------------------

function offline_template()
{
return <<<EOF
<html>
<head>

<title>System Offline</title>

<style type="text/css">

body { 
background-color:	#ffffff; 
margin:				50px; 
font-family:		Verdana, Arial, Tahoma, Trebuchet MS, Sans-serif;
font-size:			11px;
color:				#000;
background-color:	#fff;
}

a {
font-family:		Verdana, Arial, Tahoma, Trebuchet MS, Sans-serif;
font-weight:		bold;
letter-spacing:		.09em;
text-decoration:	none;
color:              #330099;
background-color:   transparent;
}
  
a:visited {
color:				#330099;
background-color:	transparent;
}

a:hover {
color:				#000;
text-decoration:    underline;
background-color:	transparent;
}

#content  {
border:				#999999 1px solid;
padding:			22px 25px 14px 25px;
}

h1 {
font-family:		Verdana, Arial, Tahoma, Trebuchet MS, Sans-serif;
font-weight:		bold;
font-size:			14px;
color:				#000;
margin-top: 		0;
margin-bottom:		14px;
}

p {
font-family:		Verdana, Arial, Tahoma, Trebuchet MS, Sans-serif;
font-size: 			12px;
font-weight: 		normal;
margin-top: 		12px;
margin-bottom: 		14px;
color: 				#000;
}
</style>

</head>

<body>

<div id="content">

<h1>System Offline</h1>

<p>This site is currently offline</p>

</div>

</body>

</html>
EOF;
}
// END



//  User Messages Template
// --------------------------------------------------------------------
// --------------------------------------------------------------------

function message_template()
{
return <<<EOF
<html>
<head>

<title>{title}</title>

{meta_refresh}

<style type="text/css">

body { 
background-color:	#ffffff; 
margin:				50px; 
font-family:		Verdana, Arial, Tahoma, Trebuchet MS, Sans-serif;
font-size:			11px;
color:				#000;
background-color:	#fff;
}

a {
font-family:		Verdana, Arial, Tahoma, Trebuchet MS, Sans-serif;
font-weight:		bold;
letter-spacing:		.09em;
text-decoration:	none;
color:              #330099;
background-color:   transparent;
}
  
a:visited {
color:				#330099;
background-color:	transparent;
}

a:active {
color:				#ccc;
background-color:	transparent;
}

a:hover {
color:				#000;
text-decoration:    underline;
background-color:	transparent;
}

#content  {
border:				#999999 1px solid;
padding:			22px 25px 14px 25px;
}

h1 {
font-family:		Verdana, Arial, Tahoma, Trebuchet MS, Sans-serif;
font-weight:		bold;
font-size:			14px;
color:				#000;
margin-top: 		0;
margin-bottom:		14px;
}

p {
font-family:		Verdana, Arial, Tahoma, Trebuchet MS, Sans-serif;
font-size: 			12px;
font-weight: 		normal;
margin-top: 		12px;
margin-bottom: 		14px;
color: 				#000;
}

ul {
margin-bottom: 		16px;
}

li {
list-style:			square;
font-family:		Verdana, Arial, Tahoma, Trebuchet MS, Sans-serif;
font-size: 			12px;
font-weight: 		normal;
margin-top: 		8px;
margin-bottom: 		8px;
color: 				#000;
}

</style>

</head>

<body>

<div id="content">

{heading}

{content}

{link}

</div>

</body>

</html>
EOF;
}
// END



// -----------------------------------------
//  Fetch names of installed languages
// -----------------------------------------
	
function language_pack_names($default)
{
	global $data;
	
    $source_dir = './'.trim($data['system_dir']).'/language/';

	$filelist = array();

	if ($fp = @opendir($source_dir)) 
	{ 
		while (false !== ($file = readdir($fp))) 
		{ 
			$filelist[count($filelist)] = $file;
		} 
	} 

	closedir($fp); 
	
	sort($filelist);

	$r  = "<div class='default'>";
	$r .= "<select name='deft_lang' class='select'>\n";
		
	for ($i =0; $i < sizeof($filelist); $i++) 
	{
		if ( ! eregi(".php$",  $filelist[$i]) AND 
			 ! eregi(".html$",  $filelist[$i]) AND
			 ! eregi(".DS_Store",  $filelist[$i]) AND
			 ! eregi("\.",  $filelist[$i])
		   )
			{
				$selected = ($filelist[$i] == $default) ? " selected='selected'" : '';
				
				$r .= "<option value='{$filelist[$i]}'{$selected}>".ucfirst($filelist[$i])."</option>\n";
			}
	}        

	$r .= "</select>";
	$r .= "</div>";

	return $r;
}
// END    

?>