<?php
/*********************************************************
 * Name: upgrade.php
 * Author: Dave Conley
 * Contact: realworld@blazefans.com
 * Description: This file will upgrade your script to the latest version
 * Version: 4.0
 * Last edited: 9th March, 2005
 *********************************************************/
// Set warning level
error_reporting  (E_ERROR | E_WARNING | E_PARSE);
set_magic_quotes_runtime(0);

define( 'INSTALL', "RWD4" );
define( 'ROOT_PATH', "./" );

// Create our superglobal wotsit so we can save doing the same things over and over
class wotsit
{
	var $path = "";
	var $url = "";
	var $skinurl = "";
	var $loaded_templates = array();
	var $skin_global;
	var $skin_wrapper;
}

$rwdInfo = new wotsit();

// Load required libraries
require_once (ROOT_PATH."/functions/global_functions.php");
require_once (ROOT_PATH."/functions/lang.php");
require_once (ROOT_PATH."/functions/output.php");
require_once (ROOT_PATH."/functions/mysql.php");

// Load config
$CONFIG = array();
require_once (ROOT_PATH."/globalvars.php");

// Create helper globals because I'm too lazy to type $CONFIG["array"] all the time
$rwdInfo->path = $CONFIG["sitepath"];
$rwdInfo->url = $CONFIG["siteurl"];
$rwdInfo->skinurl = ROOT_PATH."/skins/install";

// Our skin handler
$OUTPUT = new CDisplay();
// Global functions
$std    = new func();
// Get data from global arrays
$IN 	= $std->saveGlobals();

$lang = array();
$langpref = "1";
require_once (ROOT_PATH."/lang/".$langpref."/lang_global.php");
$lang_2 = $lang;
require_once (ROOT_PATH."/lang/".$langpref."/lang_warn.php");
$lang_3 = $lang;
require_once (ROOT_PATH."/lang/".$langpref."/lang_error.php");
$lang_4 = $lang;
$lang = array_merge($lang_2, $lang_3, $lang_4);


class upgrade
{
    function upgrade()
    {
		global $std, $IN, $CONFIG, $DB, $OUTPUT;
		
		ob_start();		
	
		if ( !empty($IN['ver']) )
		{
			// Load the database
			$dbinfo = array("sqlhost"	=> $CONFIG["sqlhost"],
					"sqlusername"	=> $CONFIG["sqlusername"],
					"sqlpassword"	=> $CONFIG["sqlpassword"],
					"sqldatabase"	=> $CONFIG["sqldatabase"],
					"sql_tbl_prefix"=> $CONFIG["sqlprefix"]);
	
			$DB = new mysql($dbinfo);
			if ( !$DB->db )
				$std->error("failed to connect to database");
		}
		
		install_head("RW::Download", "UPGRADE");
	    new_table();
		
		switch($IN['ver'])
		{
			case '100':
				$this->v100();
				break;
			
			case '110':
				$this->v110();
				break;
				
			case '120':
				$this->v120();
				break;
				
			case '200':
				$this->v200();
				break;
			
			case '300':
				$this->v300();
				break;
				
			case '310':
				$this->v310();
				break;
			
			case '320':
				$this->v320();
				break;
				
			case '321':
				$this->v321();
				break;
				
			case 'RC1':
				$this->v4RC1();
				break;
				
			case 'RC2':
				$this->v4RC2();
				break;

            case '401':
				$this->v401();
				break;
				
			case 'done':
				$this->finishup();
				break;
				
			default:
				$this->welcome();
				break;
		}

		end_table();
	    install_foot();
		
		$content = ob_get_contents();
		ob_end_clean();

		if ( !empty($IN['ver']) )
			$std->saveConfig();	
		$OUTPUT->add_output($content);
		$OUTPUT->print_output();
    }

    function doPre40Setup()
    {
		global $configman, $std, $CONFIG, $DB;
	
		// Create new array and copy old variables
		$CONFIG = array();
		$CONFIG = $configman['Main'];
	
		// theres a lot of new variables to add but this will be done in each module to try and keep it all tidy
		// this one, however, is important
		$CONFIG['sqlprefix'] = 'dl_';
	
		$std->saveConfig();
	
		// Now we can do the version checking
		// Load the database
		$dbinfo = array("sqlhost"		=> $CONFIG["sqlhost"],
						"sqlusername"	=> $CONFIG["sqlusername"],
						"sqlpassword"	=> $CONFIG["sqlpassword"],
						"sqldatabase"	=> $CONFIG["sqldatabase"],
						"sql_tbl_prefix"=> $CONFIG["sqlprefix"]);
	
		$DB = new mysql($dbinfo);
		
		$prefix = $CONFIG['sqlprefix'];
	
		// Are we running 3.2.1
		if ( $DB->field_exists( 'email', $prefix.'users') )
		    return "321";
		// 3.2.0
		if ( $DB->field_exists( 'fileType', $prefix.'links') )
		    return "320";
		// 3.1
		if ( !empty($CONFIG['skin']) )
		    return "310";
		// 3.0
		if ( $DB->field_exists( 'uid', $prefix.'custom_data') )
		    return "300";
		// 2.0
		if ( $DB->field_exists( 'sTime', $prefix.'users') )
		    return "200";
		// 1.2
		if ( $DB->field_exists( 'mirrors', $prefix.'links') )
		    return "120";
		// 1.1
		if ( $DB->field_exists( 'comments', $prefix.'links') )
		    return "110";
		
		return "100";
    }
	
    function version_check()
    {
		global $configman, $CONFIG, $DB;
		
		// First test if it's pre-version 4.0 RC1
		if ( !empty($configman['Main']) )
		{
		    $version = $this->doPre40Setup();
		    return $version;
		}
	
		// We have at least a 4.0 database so we can do this the easy way
		// Check for latest releases first as you would imagine its more 
		// likely to find a match first
	
		// Load the database
		$dbinfo = array("sqlhost"	=> $CONFIG["sqlhost"],
				"sqlusername"	=> $CONFIG["sqlusername"],
				"sqlpassword"	=> $CONFIG["sqlpassword"],
				"sqldatabase"	=> $CONFIG["sqldatabase"],
				"sql_tbl_prefix"=> $CONFIG["sqlprefix"]);
	
		$DB = new mysql($dbinfo);
		
		$prefix = $CONFIG['sqlprefix'];

        // Are we running 4.0.2 or higher
        if ( $DB->field_exists( 'version', $prefix.'version') )
        {
            $DB->query("SELECT * FROM `dl_version` ORDER BY `date` DESC");
            if (($myrow = $DB->fetch_row()))
                return $myrow['version'];
        }

        if ( $CONFIG['overridetype'] )
            return "401";

		// Are we running 4.0 RC2?
		if ( $DB->field_exists( 'votes', $prefix.'links') )
		    return "RC3";
			
		// Are we running 4.0 RC2?
		if ( $DB->field_exists( 'lastDate', $prefix.'categories') )
		    return "RC2";
		
		// RC1?
		if ( $DB->field_exists( 'fid', $prefix.'filetypes') )
		    return "RC1";
		
		// Ermmmm.... ah.... well... we're a bit buggered if we get here arent we?
		    return "ERR";
    }

    function welcome()
    {
	    global $std;
	    
	    // ===================================================
	    // This is a fairly elaborate little function that 
	    // will check we have permission to do an upgrade
	    // then check which version of the script we are 
	    // currently running. Will also give the user option
	    // to override should the guess be wrong... It shouldn't be
	    // ===================================================
	    // OK so first check permision by testing for the 
	    // lock file, globalvars file and correct version of 
	    // PHP
	    // ===================================================
	    if ( file_exists('./upgrade.lock') )
	    {
		    $std->error("The upgrader has been locked for security. If you need to upgrade to a new version remove the 'upgrade.lock' file from the root directory.");
		    return;
	    }
	    if (!$this->minimum_version("4.1.0")) { 
	    
		    $std->error("Incorrect PHP version. PHP 4.1 is required to run this script");
		    return;
	    } 
	    $file = ROOT_PATH."/globalvars.php";
	    if ( ! file_exists($file) )
	    {
		    $std->error("Cannot locate the file 'globalvars.php'.");
		    return;
	    }
	    if ( ! is_writeable($file) )
	    {
		    $std->error("Cannot write to 'globalvars.php'. You can do this by using FTP to CHMOD to 0777");
		    return;
	    }

	    // ====================================================
	    // So far so good. Now find out the installed version 
	    // ====================================================
		$version = $this->version_check();
		
	    echo "<p>Welcome to RW::Download auto updater. This script will now attempt to upgrade 
		  RW::Download to the latest version. Before continuing, please 
		  ensure that you have successfully uploaded all the changed files as indicated 
			      in the upgrade folder onto your server. Also make sure all files have the correct 
		  properties set as stated in the docs/install.htm file.</p>";
	    echo "<p><strong>IT IS HIGHLY RECOMMENDED YOU BACKUP YOUR DATABASE BEFORE PROCEDING! To do this you need to create a copy of globalvars.php from your server and download an sql dump of the database RW::Download was installed to. See your hosting company if you are unsure of how to do this.</strong></p>";
	    
	    echo "<b>Auto detecting</b><br>";
		
		if ($version == "ERR") 
		{
		    echo "<font color='red'><b>RW::Upgrade was unable to detect your current software version. It is recommended you report this at rwscripts.com however you can continue the upgrade by selecting your software version below. It's probabable however that you may get errors during the upgrade process. Make sure backup your database!</b></font>";
			
			echo "<table width=\"120\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
			<form method=post enctype=\"multipart/form-data\" action=\"\" name=\"version\">
			      <tr>
				    <td>1.0x</td>
				    <td><input type=\"radio\" name=\"ver\" value=\"100\"></td>
			      </tr>
			      <tr>
				    <td>1.1x</td>
				    <td><input type=\"radio\" name=\"ver\" value=\"110\"></td>
			      </tr>
			      <tr>
				    <td>1.2x</td>
				    <td><input type=\"radio\" name=\"ver\" value=\"120\"></td>
			      </tr>
			      <tr>
				    <td>2.0x</td>
				    <td><input type=\"radio\" name=\"ver\" value=\"200\"></td>
			      </tr>
			      <tr>
				    <td>3.0x</td>
				    <td><input type=\"radio\" name=\"ver\" value=\"300\"></td>
			      </tr>
			      <tr>
				    <td>3.1x</td>
				    <td><input type=\"radio\" name=\"ver\" value=\"310\"></td>
			      </tr>
			      <tr>
				    <td>3.20</td>
				    <td><input type=\"radio\" name=\"ver\" value=\"320\"></td>
			      </tr>
			      <tr>
				    <td>3.21 3.22 3.23</td>
				    <td><input type=\"radio\" name=\"ver\" value=\"321\"></td>
			      </tr>
			      <tr>
				    <td>4.0RC1</td>
				    <td><input type=\"radio\" name=\"ver\" value=\"RC1\"></td>
			      </tr>
				  <tr>
				    <td>4.0RC2</td>
				    <td><input type=\"radio\" name=\"ver\" value=\"RC2\"></td>
			      </tr>
                  <tr>
				    <td>4.0.1</td>
				    <td><input type=\"radio\" name=\"ver\" value=\"401\"></td>
			      </tr>
			    </table><input type=\"submit\" name=\"submit\" value=\"Submit\"></form>";
				echo "<p align='right'><a href='upgrade.php?ver={$version}'>Continue -></a></p>";
			return;
		}
		else if ( $ver == "402" )
		{
			echo "We have detected you are already running the latest version of RW::Download. You do not need to run the upgrade script.";
			return;
		}
		echo "RW::Upgrade has detected you are running RW::Download ";
		
		switch( $version )
		{
			case '100':
				echo "1.0.x";
				break;
			
			case '110':
				echo "1.1.x";
				break;
				
			case '120':
				echo "1.2.x";
				break;
				
			case '200':
				echo "2.x";
				break;
			
			case '300':
				echo "3.0.x";
				break;
				
			case '310':
				echo "3.1.x";
				break;
			
			case '320':
				echo "3.2";
				break;
				
			case '321':
				echo "3.2.1, 3.2.2 or 3.2.3";
				break;
				
			case 'RC1':
				echo "4.0 RC1";
				break;
				
			case 'RC2':
				echo "4.0 RC2";
				break;
			
			case 'RC3':
				echo "4.0 RC3";
				break;

            case '401':
				echo "4.0.1";
				break;

            case '402':
                echo "4.0.2";
                break;

			default:
				echo "ERROR contact rwscripts.com";
				break;
		}
		echo ". If you know this to be incorrect, ensure you have not uploaded globalvars.php yet as this can confuse the upgrade script. Upload your backup globalvars.php before continuing.";

		echo "<p align='right'><a href='upgrade.php?ver={$version}'>Continue -></a></p>";
    }

    function v100()
    {
	    global $CONFIG, $DB, $std;
	    
	    $DB->query("ALTER TABLE dl_links ADD comments INT( 11 ) DEFAULT 0 NOT NULL");
	    $DB->query("ALTER TABLE dl_categories ADD downloads INT( 11 ) DEFAULT 0 NOT NULL");
	    // Setup comments and file counts
	    $DB->query("SELECT * FROM dl_categories");
	    while ( $myrow = $DB->fetch_row() )
	    {
		    $cat = $myrow['cid'];
		    $DB->query("SELECT * FROM dl_links WHERE categoryid={$cat}");
		    $num = $DB->num_rows();
		    $DB->query("UPDATE dl_categories SET downloads={$num} WHERE cid={$cat}");
	    }
	    $DB->query("SELECT * FROM dl_links");
	    while ( $myrow = $DB->fetch_row() )
	    {
		    $dlid = $myrow['did'];
		    $DB->query("SELECT * FROM dl_comments WHERE did={$dlid}");
		    $num = $DB->num_rows();
		    $DB->query("UPDATE dl_links SET comments={$num} WHERE did={$dlid}");
	    }

	    $std->info($path, "Data converted successfully.<br>"."updating options...");
	    $CONFIG['links_per_page'] = "30";
	    $CONFIG['comments_per_page'] = "30";
	    $CONFIG['default_sort'] = "date";
	    $CONFIG['default_order'] = "DESC";
	    $std->info($path, "Upgrade from 1.0 to 1.1 complete. Please wait.");
	    $this->v110();	
		echo "Upgrade from version 1.0 to 1.1 complete.";
	    addredirect("upgrade.php?ver=110");
    }

    function v110()
    {
	    global $CONFIG, $DB, $std;

	    $DB->query("ALTER TABLE dl_links ADD mirrors TEXT NOT NULL AFTER download");
	    echo "Upgrade from version 1.1 to 1.2 complete.";
	    addredirect("upgrade.php?ver=120");
    }

    function v120()
    {	
	    global $CONFIG, $DB, $std;

	    $DB->query("ALTER TABLE dl_users ADD level INT NOT NULL,
					     ADD sID varchar(32) NOT NULL default '',
					     ADD sData TEXT NOT NULL,
					     ADD sTime INT(11) NOT NULL default '0'");
	    $DB->query("ALTER TABLE dl_links CHANGE date date DATETIME DEFAULT NULL,
					     ADD owner INT DEFAULT '0' NOT NULL AFTER description");
	    $DB->query("ALTER TABLE dl_comments CHANGE date date DATETIME DEFAULT NULL");
	    $DB->query("UPDATE dl_users SET level = 1 WHERE id = 1");
	    
	    $DB->query("SELECT * FROM dl_users");
	    while ($myrow = $DB->fetch_row()) 
	    {
		    $newpass = md5($myrow["password"]);
		    $id = $myrow["id"];
		    $DB->query("UPDATE dl_users SET password = '{$newpass}' WHERE id = '{$id}'");
	    }
	    
	    $CONFIG['guest_uploads'] = "1";
	    $CONFIG['approve_uploads'] = "1";
	    $CONFIG['ul_set'] = "8M";
	    $CONFIG['session'] = "10";

		echo "Upgrade from version 1.2 to 2.0 complete.";
	    addredirect("upgrade.php?ver=200");
    }	

    // this is where things start getting interesing
    function v200()
    {
	    global $CONFIG, $DB, $std;
	    
	    $DB->query("ALTER TABLE dl_comments ADD uid INT(11) NOT NULL default '0',
						DROP rating");
	    
	    $DB->query("CREATE TABLE dl_custom (
	      cid int(11) NOT NULL auto_increment,
	      caption varchar(200) NOT NULL default '',
	      description varchar(250) NOT NULL default '',
	      field varchar(4) NOT NULL default '',
	      size tinyint(3) NOT NULL default '0',
	      max tinyint(3) NOT NULL default '0',
	      options text NOT NULL,
	      admins tinyint(1) NOT NULL default '0',
	      PRIMARY KEY  (cid)
	    )");
		    
	    $DB->query("CREATE TABLE dl_custom_data (
	      uid int(11) NOT NULL default '0',
	      custom1 text NOT NULL,
	      custom7 text NOT NULL
	    )");
		    
	    $DB->query("ALTER TABLE dl_links CHANGE date date DATETIME DEFAULT NULL");
		    
	    $DB->query("CREATE TABLE dl_rating (
	      rid int(11) NOT NULL auto_increment,
	      dlid int(11) NOT NULL default '0',
	      ip varchar(32) NOT NULL default '',
	      rating tinyint(2) NOT NULL default '0',
	      PRIMARY KEY  (rid)
	    )");
		    
	    $DB->query("DROP TABLE dl_sessions");
		    
	    $DB->query("CREATE TABLE dl_sessions (
	      id int(11) NOT NULL default '0',
	      sID varchar(32) NOT NULL default '',
	      sData text NOT NULL,
	      sTime int(11) NOT NULL default '0',
	      PRIMARY KEY  (id)
	    )");
	    
	    $DB->query("ALTER TABLE dl_users CHANGE level level INT(11) NOT NULL default '0'");
	    
	    $CONFIG['email'] = '';
	    $CONFIG['email_files'] = '0';
	    $CONFIG['email_unapp'] = '0';
	    // Member database type
	    $CONFIG['usertype'] = '0';
	    // Path to external member db
	    $CONFIG['userfilepath'] = '';
	    // URL to external member db
	    $CONFIG['userfileurl'] = '';
	    $CONFIG['adminperm'] = '|';
	    $CONFIG['modperm'] = '|';
	    $CONFIG['memberperm'] = '|';
	    $CONFIG['guestperm'] = '|';
	    
	    echo "Upgrade from version 2.0 to 3.0 complete.<br>";
		addredirect("upgrade.php?ver=300");
    }

    function v300()
    {
	    global $CONFIG, $std;

        echo "Upgrade from version 3.0 to 3.1 complete.";
	    addredirect("upgrade.php?ver=310");
    }

    function v310()
    {
	    global $CONFIG, $DB, $std;
	    
	    $DB->query("ALTER TABLE dl_categories ADD sortorder INT(11) NOT NULL default '0'");
		    
	    $DB->query("CREATE TABLE dl_groups (
	      gid int(11) NOT NULL default '0',
	      canView varchar(7) NOT NULL default 'checked',
	      canDownload varchar(7) NOT NULL default 'checked',
	      canSearch varchar(7) NOT NULL default 'checked',
	      dlLimitFiles int(11) NOT NULL default '0',
	      dlLimitSize int(11) NOT NULL default '0',
	      PRIMARY KEY  (gid)
	    )");
		    
	    $DB->query("INSERT INTO dl_groups VALUES    (0, 'checked', 'checked', 'checked', 0, 0),
							(1, 'checked', 'checked', 'checked', 0, 0),
							(2, 'checked', 'checked', 'checked', 0, 0),
							(3, 'checked', 'checked', 'checked', 0, 0),
							(4, 'checked', 'checked', 'checked', 0, 0)");
	    
	    $DB->query("CREATE TABLE dl_images (
			      id int(11) NOT NULL auto_increment,
			      realName varchar(255) NOT NULL default '',
			      dlid int(11) NOT NULL default '0',
			      size varchar(9) NOT NULL default '',
			      type varchar(255) NOT NULL default '',
			      PRIMARY KEY  (id)
			    )");
	    
	    $DB->query("ALTER TABLE dl_links ADD maskName varchar(255) NOT NULL default '',
					     ADD fileType varchar(255) NOT NULL default '',
					     ADD lastEdited datetime NOT NULL default '2003-09-01 00:00:00'");
						    
	    $DB->query("SELECT * FROM dl_links");
	    if ( $myrow = $DB->fetch_row() )
	    {
		    do
		    {
			    $oldthumb = $myrow["thumb"];
			    $oldid = $myrow["did"];
			    $DB->query("INSERT INTO dl_images ( realName, dlid ) VALUES ( '{$oldthumb}', '{$oldid}' )");

		    } while ( $myrow = $DB->fetch_row() );
	    }
	    echo "Upgrade from version 3.1 to 3.2 complete.";
	    addredirect("upgrade.php?ver=320");
    }

    function v320()
    {
	    global $CONFIG, $DB, $std;
	    
	    $DB->query("ALTER TABLE dl_users ADD email varchar(255) NOT NULL,
					     ADD regKey varchar(32) NOT NULL,
					     CHANGE id id INT( 11 ) NOT NULL AUTO_INCREMENT");
	    $DB->query("INSERT INTO dl_groups VALUES (5, 'checked', 'checked', 'checked', 0, 0)");	
	    echo "Upgrade from version 3.2 to 3.2.1 complete.";
	    addredirect("upgrade.php?ver=321");
    }

    function v321()
    {
	    global $CONFIG, $DB, $std;
	    
	    $DB->query("ALTER TABLE dl_categories ADD lastid int(11) NOT NULL default '0',
									      ADD lastTitle varchar(255) NOT NULL default '',
									      ADD canBrowse varchar(255) NOT NULL default '',
									      ADD canDL varchar(255) NOT NULL default '',
									      ADD canUL varchar(255) NOT NULL default '',
									      DROP PRIMARY KEY ,
									      ADD PRIMARY KEY ( `cid` )");
	    $DB->query("CREATE TABLE `dl_filetypes` (
			      `fid` int(11) NOT NULL auto_increment,
			      `mimetype` varchar(255) NOT NULL default '',
			      `maxsize` int(11) NOT NULL default '0',
			      `icon` varchar(255) NOT NULL default '',
			      `allowed` tinyint(1) NOT NULL default '1',
			      PRIMARY KEY  (`fid`)
			    )");
	
	    $DB->query("INSERT INTO `dl_filetypes` (`fid`, `mimetype`, `maxsize`, `icon`, `allowed`) VALUES
					   (1, 'application/zip', 400000, 'zip.gif', 1),
					   (2, 'application/octet-stream', 50000, 'txt.gif', 1),
					   (3, 'image/gif', 200000, 'gif.gif', 1),
					   (4, 'image/jpeg', 200000, 'jpg.gif', 1),
					   (5, 'application/msword', 50000, 'doc.gif', 1),
					   (6, 'text/html', 20000, 'htm.gif', 1),
					   (7, 'audio/mpeg', 2000000, 'mp3.gif', 1),
					   (8, 'video/quicktime', 2000000, 'mov.gif', 1),
					   (9, 'audio/x-realaudio', 200000, 'real.gif', 1),
					   (10, 'application/pdf', 200000, 'pdf.gif', 1),
					   (11, 'application/postscript', 200000, 'ps.gif', 1),
					   (12, 'text/plain', 2000, 'txt.gif', 1),
					   (13 , 'image/pjpeg', 1000000, 'jpg.gif', 1)");
	
	    $DB->query("ALTER TABLE `dl_groups`
			      DROP `canView`,
			      DROP `canDownload`,
			      DROP `canSearch`,
			      DROP `dlLimitFiles`,
			      DROP `dlLimitSize`,
			      ADD `name` varchar(255) NOT NULL,
			      ADD `users` INT (11) NOT NULL");
	    
	    $DB->query("TRUNCATE TABLE `dl_groups`");
	    $DB->query("INSERT INTO `dl_groups` (`gid`, `name`, `users`) VALUES
					(6, 'Unapproved', 0),
					(1, 'Super-Admin', 0),
					(2, 'Admin', 0),
					(3, 'Moderator', 0),
					(4, 'Members', 0),
					(5, 'Guests', 0)");
	    $DB->query("CREATE TABLE `dl_groupsextra` (
			      `gid` int(11) NOT NULL default '0',
			      `canSearch` tinyint(1) NOT NULL default '1',
			      `uploadType` tinyint(1) NOT NULL default '0',
			      `no_restrict` tinyint(1) NOT NULL default '0',
			      `limitFilesPeriod` tinyint(1) NOT NULL default '0',
			      `dlLimitFiles` int(11) NOT NULL default '0',
			      `limitSizePeriod` tinyint(1) NOT NULL default '0',
			      `dlLimitSize` int(11) NOT NULL default '0',
			      `resetOnExpire` tinyint(1) NOT NULL default '0',
			      `moderateAll` tinyint(1) NOT NULL default '0',
			      `moderateOwn` tinyint(1) NOT NULL default '1',
			      `acpAccess` tinyint(1) NOT NULL default '0',
			      `approveUL` tinyint(1) NOT NULL default '1',
			      `addComments` tinyint(1) NOT NULL default '1',
			      `editComments` tinyint(1) NOT NULL default '1',
			      `delComments` tinyint(1) NOT NULL default '0',
			      `postHTML` tinyint(1) NOT NULL default '0'
			    )");
	    
	    $DB->query("INSERT INTO `dl_groupsextra`  ( `gid` , `canSearch` , `uploadType` , `no_restrict` , `limitFilesPeriod` , `dlLimitFiles` , `limitSizePeriod` , `dlLimitSize` , `resetOnExpire` , `moderateAll` , `moderateOwn` , `acpAccess` , `approveUL` , `addComments` , `editComments` , `delComments` , `postHTML` ) VALUES
					      (6, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0),
					      (1, 1, 1, 1, 0, 5, 0, 5242880, 0, 1, 1, 1, 1, 1, 1, 1, 1),
					      (2, 1, 1, 1, 0, 5, 0, 5242880, 0, 1, 1, 1, 1, 1, 1, 1, 1),
					      (3, 1, 1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 1, 1, 1, 1),
					      (4, 1, 1, 1, 0, 0, 0, 0, 0, 0, 1, 0, 1, 1, 1, 1, 1),
					      (5, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0)");
					      
	    $DB->query("CREATE TABLE `dl_langsets` (
		      `lid` int(11) NOT NULL auto_increment,
		      `name` varchar(100) NOT NULL default '',
		      `author` varchar(255) NOT NULL default '',
		      UNIQUE KEY `lid` (`lid`)
		    )");
		    
	    $DB->query("ALTER TABLE `dl_links` CHANGE `download` `download` text NOT NULL, 
								       CHANGE `categoryid` `categoryid` int(11) NOT NULL,
								       ADD `mirrornames` text NOT NULL,
								       ADD `realsize` int(11) NOT NULL default '0',
								       ADD `views` int(11) NOT NULL default '0',
								       ADD `pinned` varchar(7) NOT NULL default ''");
	    $DB->query("CREATE TABLE `dl_logs` (
				      `lid` int(11) NOT NULL auto_increment,
				      `type` tinyint(2) NOT NULL default '0',
				      `file` int(11) NOT NULL default '0',
				      `filename` varchar(255) NOT NULL default '',
				      `referer` text NOT NULL,
				      `time` int(10) NOT NULL default '0',
				      `IP` varchar(15) NOT NULL default '',
				      UNIQUE KEY `lid` (`lid`)
				    )");
				    
	    $DB->query("CREATE TABLE `dl_memberextra` (
				      `mid` int(11) NOT NULL default '0',
				      `gid` int(11) NOT NULL default '0',
				      `downloaded` int(11) NOT NULL default '0',
				      `uploaded` int(11) NOT NULL default '0',
				      `approving` int(11) NOT NULL default '0',
				      `bandwidth` double NOT NULL default '0',
				      `files` int(11) NOT NULL default '0',
				      `bandwidth_time` int(11) NOT NULL default '0',
				      `files_time` int(11) NOT NULL default '0',
				      `receive_email` tinyint(1) NOT NULL default '1',
				      `skin` int(11) NOT NULL default '1',
				      `lang` int(11) NOT NULL default '1',
				      PRIMARY KEY  (`mid`),
				      UNIQUE KEY `mid` (`mid`)
				    )");
	    
	    $DB->query("CREATE TABLE `dl_moderators` (
			      `mid` int(11) NOT NULL auto_increment,
			      `catid` int(11) NOT NULL default '0',
			      `member_name` varchar(255) NOT NULL default '',
			      `member_id` int(11) NOT NULL default '0',
			      `canedit` tinyint(1) NOT NULL default '0',
			      `canmove` tinyint(1) NOT NULL default '0',
			      `candelete` tinyint(1) NOT NULL default '0',
			      `edit_comments` tinyint(1) NOT NULL default '0',
			      `del_comments` tinyint(1) NOT NULL default '0',
			      UNIQUE KEY `mid` (`mid`)
			    )");
		    
	    $DB->query("CREATE TABLE `dl_regbot` (
				      `rid` int(11) NOT NULL auto_increment,
				      `ip` varchar(15) NOT NULL default '',
				      `regKey` varchar(32) NOT NULL default '',
				      `regtime` int(11) NOT NULL default '0',
				      PRIMARY KEY  (`rid`)
				    )");
				    
	    $DB->query("ALTER TABLE `dl_sessions` DROP `sData`");
	    
	    $DB->query("CREATE TABLE `dl_skinsets` (
			      `setid` int(11) NOT NULL auto_increment,
			      `name` varchar(255) NOT NULL default '',
			      `author` varchar(255) NOT NULL default '',
			      UNIQUE KEY `setid` (`setid`)
			    )");
			    
	    $DB->query("CREATE TABLE `dl_templates` (
			      `tid` int(11) NOT NULL auto_increment,
			      `setid` int(11) NOT NULL default '0',
			      `groupname` varchar(100) NOT NULL default '',
			      `content` text NOT NULL,
			      `funcname` varchar(100) NOT NULL default '',
			      PRIMARY KEY  (`tid`),
			      KEY `tid` (`tid`)
			    )");
			    
	    $DB->query("ALTER TABLE `dl_users` CHANGE `level` `group` int(11) NOT NULL,
								       ADD `iplog` text NOT NULL,
								       DROP `sID`,
								       DROP `sData`,
								       DROP `sTime`");
	    
	    $CONFIG['allowMime'] = '1';
	    $CONFIG['allowRegister'] = '1';
	    $CONFIG['allowunknown'] = '1';
	    $CONFIG['copyright'] = '1';
	    $CONFIG['copystring'] = 'RW::Scripts v4.0 - www.rwscripts.com';
	    $CONFIG['dateformat'] = 'j-F-Y g:i a';
	    $CONFIG['debuglevel'] = '0';
	    $CONFIG['default_c_order'] = 'ASC';
	    $CONFIG['display_thumbs'] = '1';
	    $CONFIG['dupemail'] = '0';
	    $CONFIG['email_newuser'] = '0';
	    $CONFIG['isoffline'] = '0';
	    $CONFIG['leech_allownorefer'] = '0';
	    $CONFIG['max_word_length'] = '100';
	    $CONFIG['num_gall_cols'] = '3';
	    $CONFIG['num_stats'] = '5';
	    $CONFIG['offlinemsg'] = 'Downloads section is currently offline for maintainance testing';
	    $CONFIG['post_set'] = '';
	    $CONFIG['sitename'] = 'RW::Download 4.0';
	    $CONFIG['sqlprefix'] = 'dl_';
	    $CONFIG['thumbHeight'] = '150';
	    $CONFIG['thumbWidth'] = '150';
	    $CONFIG['thumb_generate'] = 'gd2';
	    $CONFIG['timeadjust'] = '+3';
	    $CONFIG['timezone'] = 'GMT';
	    $CONFIG['usegzip'] = '1';
	    $CONFIG['guestid'] = '5';

	    echo "Upgrade from version 3.2.x to 4.0 RC1 complete.";
	    addredirect("upgrade.php?ver=RC1");
    }

    function v4RC1()
    {
	    global $CONFIG, $DB, $std;
		    
	    $CONFIG['email_friend_msg'] = 'Dear {friend},

	    {username} has sent you this email to tell you about a file you may be interested in. The file can be found at {linkurl}.
	    
	    They also sent you this message
	    
	    ------------------------------------------------
	    
	    {message}';
	    $CONFIG['email_newfile_msg'] = 'This is an email from your download script at {siteurl}
	    
	    {username} has just uploaded a new file named {filename}. You can view the file at this location: {linkurl}
	    
	    You do not need to do anything as this file has been entered straight into the database.';
	    $CONFIG['email_newuser'] = '';
	    $CONFIG['email_newuser_msg'] = 'Dear {username},
	    
	    Thankyou for registering an account with our download manager at {siteurl}. You are now an approved member and can use our script and configure it to your tastes using the user control panel';
	    $CONFIG['email_newuseradmin_msg'] = 'This is an email from your download script at {siteurl}
	    
	    {username} has just registered a new account on your download manager. You do not need to do anything and this email is for information purposes only.';
	    $CONFIG['email_newuseradminconfirm_msg'] = 'This is an email from your download script at {siteurl}
	    
	    {username} has just registered a new account on your download manager. This user requires admin approval before they can become an approved member. Please log into your Admin Control Panel to do this: {adminlink}';
	    $CONFIG['email_newuserconfirm2_msg'] = 'Dear {username},
	    
	    Thankyou for registering an account with our download manager at {siteurl}. 
	    
	    IMPORTANT: Before you are an approved member an admin must validate your account manually. They have been informed they need to do this and you should be approved shortly. 
	    
	    Once you have been approved by an admin you will be a fully approved member and can configure the download manager to your own tastes using the user control panel';
	    $CONFIG['email_newuserconfirm_msg'] = 'Dear {username},
	    
	    Thankyou for registering an account with our download manager at {siteurl}. 
	    
	    IMPORTANT: Before you are an approved member you MUST confirm your email address. You can do this very easilly by clicking the following link or copying and pasting it into your browsers address bar:
	    
	    {confirmlink}
	    
	    Once you visit the link you will be a fully approved member and can configure the download manager to your own tastes using the user control panel';
	    $CONFIG['email_report_msg'] = 'This is an email from your download script at {siteurl}
	    
	    {username} has just reported a link is broken in your downloads database. The file can be found at:
	    {linkurl}';
	    $CONFIG['email_unappfile_msg'] = 'This is an email from your download script at {siteurl}
	    
	    {username} has just uploaded a new file named {filename}. You can view the file at this location: {linkurl}
	    
	    This file requires your approval before it is entered in the database. You can do this by logging into your admin control panel.';
	    $CONFIG['nopassthrough'] = '1';
	    $CONFIG['partial_transfers'] = '1';
	    $CONFIG['php_timeout'] = '2000';	
	    $CONFIG['dogdcheck'] = '1';
        $CONFIG['defaultSkin'] = '1';
        $CONFIG['defaultLang'] = '1';
	    
	    $DB->query("ALTER TABLE `dl_categories`
								       ADD `lastDate` int(11) NOT NULL,
								       ADD `realsize` int(11) NOT NULL default '0',
								       ADD `views` int(11) NOT NULL default '0',
								       ADD `pinned` varchar(7) NOT NULL default ''");
		    
	    echo "Upgrade from version 4.0RC1 to 4.0RC2 complete.";
	    addredirect("upgrade.php?ver=RC2");
    }

	function v4RC2()
	{
		global $CONFIG, $DB, $std;
		$DB->query("ALTER TABLE `dl_links` ADD `votes` int(11) NOT NULL default '0'");
		
		$CONFIG['regconfirm'] = '1';
		$CONFIG['approvedgroup'] = '4';
		$CONFIG['mailtype'] = 'mail';
		$CONFIG['smtphost'] = 'localhost';
		$CONFIG['smtpport'] = '25';
		$CONFIG['smtpusername'] = '';
		$CONFIG['smtppass'] = '';
		$CONFIG['overridetype'] = 'php.ini';
		$CONFIG['post_max_size'] = ini_get("post_max_size");
		
		echo "Upgrade from version 4.0RC2 to 4.0RC3 complete.";
	    addredirect("upgrade.php?ver=401");
	}

    function v401()
    {
        $DB->query("CREATE TABLE `dl_version` (
                      `upg_id` mediumint(8) NOT NULL auto_increment,
                      `version` varchar(5) NOT NULL default '',
                      `date` int(11) NOT NULL default '0',
                      UNIQUE KEY `upg_id` (`upg_id`)
                    )");
        $insert = array("version" => "402",
                        "date" => time());
        $DB->insert($insert, "dl_version");
        echo "Upgrade from version 4.0RC2 to 4.0RC3 complete.";
	    addredirect("upgrade.php?ver=done");
    }
	
    function finishup()
    {
	    global $configman, $std, $CONFIG;
	    
	    $std->saveConfig();
	    if ($locker = @fopen( $CONFIG["sitepath"].'/upgrade.lock', 'w' ) )
	    {
		    @fwrite( $locker, 'locked', 6 );
		    @fclose($locker);
		    
		    @chmod( $CONFIG["sitepath"].'/upgrade.lock', 0666 );
	    
		    $msg="The upgrader is now locked (to upgrade in future, remove the file 'upgrade.lock') It is reccommended that you remove the upgrade.php file before continuing.";
	    }
	    else
	    {
		    $msg="You must remove this file from your server now. leaving it here is a huge security risk";
	    }
	    echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
				      <tr> 
					    <td width=\"124\">&nbsp;</td>
					    <td width=\"30\">&nbsp;</td>
					    <td><p>We have saved your settings to your server.</p>
					      <p>You can now proceed to the admin control panel where you can 
						    log in with your admin username and password</p>
					      <p class=\"red\">$msg</p>
					      <p align=\"center\"><a href=\"index.php\">Continue</a></p></td>
				      </tr>
				    </table>";
    }

    function minimum_version( $vercheck ) 
    { 
	$minver = explode(".", $vercheck); 
	$curver = explode(".", phpversion()); 
	if (($curver[0] < $minver[0]) 
		|| (($curver[0] == $minver[0]) 
		&& ($curver[1] < $minver[1])) 
		|| (($curver[0] == $minver[0]) && ($curver[1] == $minver[1])
		&& ($curver[2][0] < $minver[2][0]))) 
	   return false; 
	else 
	   return true; 
    }     
}
function install_head($title="RW::Download", $subtitle="Admin CP")
{
	echo "<table width='90%'  border='0' cellspacing='0' cellpadding='0' align='center'>
  <tr>
    <td class='top1'>&nbsp;$title </td>
  </tr>
  <tr>
    <td class='top2'><table width='100%'  border='0' cellspacing='0' cellpadding='0'>
      <tr>
	<td width='250' bgcolor='#333333' class='smallheadtext'>+ $subtitle </td>
	<td width='18'><img src='skins/install/images/smallhead.gif' width='18' height='12'></td>
	<td>&nbsp;</td>
      </tr>
    </table> </td>
  </tr>
  <tr>
    <td class='main_frame_bg'>";
}

function install_foot()
{
	echo "</td>
		  </tr>
		</table>";
}

// Nice hack to save modifying all the new_table calls I mad in the admin section
function new_table($colspan = -1, $class="", $tdclass="", $width="100%", $colwidth="", $padding=2)
{
	global $OUTPUT;
	$output = $OUTPUT->new_table($colspan, $class, $tdclass, $width, $colwidth, $padding);
	echo $output;
}
function new_row($colspan = -1, $class="", $tdclass="", $width="")
{
	global $OUTPUT;
	$output = $OUTPUT->new_row($colspan, $class, $tdclass, $width);
	echo $output;
}
function new_col($colspan = -1, $tdclass="")
{
	global $OUTPUT;
	$output = $OUTPUT->new_col($colspan, $tdclass);
	echo $output;
}
function end_table()
{
	global $OUTPUT;
	$output = $OUTPUT->end_table();
	echo $output;
}

function addredirect($url)
{
	echo "<SCRIPT LANGUAGE='JavaScript'>
	<!--
	function redirect()
	{
		parent.location.href='{$url}'
	}
	-->
	</SCRIPT>
	
	<SCRIPT LANGUAGE='JavaScript'>
	<!--
			setTimeout('redirect()',1000)
	-->
	</SCRIPT>
	Upgrade is continuing. Click <a href='{$url}'>here</a> if you do not wish to wait.";
}
$loader = new upgrade();
?>