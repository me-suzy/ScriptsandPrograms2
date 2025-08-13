<html>
<head>

<?php

$version = "2.2.10n";
$turn = 1;

if (!$stepnum) {$stepnum =1;}


include("../includes/adodb/adodb.inc.php");
include("../includes/security_lib.php");
include("../includes/functions_lib.php");
include("../includes/sessions_lib.php");


function deter_sysdir()
{
	global $SCRIPT_FILENAME;
	global $SCRIPT_NAME;
	global $PATH_TRANSLATED;
	global $PHP_SELF;
	global $DOCUMENT_ROOT;

	if (!$SCRIPT_FILENAME)
	{	
			//echo $sysdir."<BR>";
			$sysdir = dirname(preg_replace('#(/|\\\)+#', '/', $PATH_TRANSLATED));
			$sysdir = eregi_replace("admin","",$sysdir);	
	}
	else
	{
		if ('php.exe' == basename($SCRIPT_NAME)) 
		{
			if (isset($PATH_TRANSLATED) && !strpos($PATH_TRANSLATED, 'php.exe')) 
				 $sysdir = $PATH_TRANSLATED;
			else 
			{
			     $sysdir = $DOCUMENT_ROOT .
				 substr($PHP_SELF, strpos($PHP_SELF, 'php.exe') + 7);
		    }
						
			$sysdir = dirname(preg_replace('#(/|\\\)+#', '/', $sysdir));
			$sysdir = eregi_replace("admin","",$sysdir);	
		}	
		else
		{
			return	$sysdir = eregi_replace("admin","",dirname($SCRIPT_FILENAME));
		}
	}

	return $sysdir;
}

function deter_urldir()
{
	global $REQUEST_URI;
	global $PATH_INFO;
	

	if (!$REQUEST_URI)
	{	
		  $urldir = ereg_replace("admin","",dirname($PATH_INFO)); 
		
	}
	else
	{
		$urldir = ereg_replace("admin","",dirname($REQUEST_URI)); 
	}

	return $urldir;
}


function ntcheck()
{
	global $SERVER_SOFTWARE;

	if (eregi("win",$SERVER_SOFTWARE) || eregi("iis",$SERVER_SOFTWARE) || eregi("microsoft",$SERVER_SOFTWARE))
		return 1;
	else
		return 0;
}

function usercheck($dbhost, $dbuser, $db, $dbpass, $dbtype, $dbport) //CyKuH [WTN]
{
	global $block;
	global $dbcreated;
	global $error5;
	global $stepnum;
	global $sysdir;
	global $first;
	global $last;
	global $urldir;
	global $version;
	global $SERVER_NAME;

	if ($dbtype == "postgres7")
	{
	if ($dbhost)
		$dbhost = "host=$dbhost ";
	if ($dbport)
		{	$dbhost .= "port=$dbport ";
			$dbport = "";
		}
	if ($db)
		{	$dbhost .= "dbname=$db ";
			$db = "";
		}
	if ($dbuser)
		{	$dbhost .= "user=$dbuser ";
			$dbuser = "";
		}
	if ($dbpass)
		{	$dbhost .= "password=$dbpass";
			$dbpass = "";
		}
	}

	if(!$conn=&ADONewConnection($dbtype) || !$conn->PConnect($dbhost, $dbuser, $dbpass, $db))
	{
		$error5 = "Could not connect to the specified database.  Please check the database information.";
		$stepnum = 4;
		$block = 1;
		return 0;
	}

	else
	{								
		$rs = &$conn->Execute("SELECT * FROM inl_users WHERE user_name='root'");
		if(!$rs || $rs->EOF)
			return 0;
		else
			return 1;
	}
}

// CyKuH [WTN]
function installpath($first, $last, $sysdir, $urldir, $ip)
{
	global $stepnum;
	global $error21;

	if ($sysdir == "" || $urldir == "")
	{
		$error21 = "The server and the url paths can not be left empty.  You need at least a trailing slash";
		$stepnum = 2;
		return 0;
	}

	$dir = "includes/";
	$file = $sysdir.$dir."config.php";
	$dir2 = $sysdir."themes";
	$dir3 = $sysdir."languages";

	if (!file_exists($file))
	{
		$error21 = "Install could not locate all In-link files at the specified path. (Missing: \"$file\")  Please, check that the path matches the exact location of In-link or check to make sure that your installation package is complete.";
		$stepnum = 2;
		return 0;
	}

	if (!file_exists($dir2))
	{
		$error21 = "Install could not locate all In-link files at the specified path. (Missing: \"$dir2\")  Please, check that the path matches the exact location of In-link or check to make sure that your installation package is complete.";
		$stepnum = 2;
		return 0;
	}

	if (!file_exists($dir3))
	{
		$error21 = "Install could not locate all In-link files at the specified path. (Missing: \"$dir3\")  Please, check that the path matches the exact location of In-link or check to make sure that your installation package is complete.";
		$stepnum = 2;
		return 0;
	}


if (check_netconn())
	{
	$file = "CyKuH [WTN] Remove Call Home" . $first. "&last=". $last . "&reg=" . $pass. "&urldir=" . urlencode($urldir). "&ip=" .urlencode($ip);	
	$fd = @fopen ($file, "r");
	fclose ($fd);
	}
	return 1;
}

function checkpath()
{
	global $block;
	global $error2;
	global $SCRIPT_FILENAME;
	
	if (ntcheck())
	{
		$error2 = "It seems that you are running Windows NT/2000 on your server. The system path sometimes may not be determined correctly on Microsoft servers. Please, check the path:";
	}
	elseif (!eregi("admin/install.php",$SCRIPT_FILENAME))
	{
		$error2 = "There is an unknown problem determening the exact location of In-link on the server";
	}

}



function dbcheck($dbhost, $dbuser, $db, $dbpass, $dbtype, $dbport) //CyKuH [WTN]
{
	global $block;
	global $dbcreated;
	global $error5;
	global $stepnum;
	global $sysdir;
	global $first;
	global $last;
	global $urldir;
	global $version;
	global $SERVER_NAME;

	if ($dbtype == "postgres7")
	{
	if ($dbhost)
		$dbhost = "host=$dbhost ";
	if ($dbport)
		{	$dbhost .= "port=$dbport ";
			$dbport = "";
		}
	if ($db)
		{	$dbhost .= "dbname=$db ";
			$db = "";
		}
	if ($dbuser)
		{	$dbhost .= "user=$dbuser ";
			$dbuser = "";
		}
	if ($dbpass)
		{	$dbhost .= "password=$dbpass";
			$dbpass = "";
		}
	}

	if(!$conn=&ADONewConnection($dbtype) || !$conn->PConnect($dbhost, $dbuser, $dbpass, $db))
	{
		$error5 = "Could not connect to the specified database.  Please check the database information.";
		$stepnum = 4;
		$block = 1;
		return 0;
	}

	elseif(!@fclose(@fopen($sysdir . "includes/config.php","a")))
	{
		$error5 = "The installation script can not write to the config file. You will have to edit the top portion of config.php manually in order for the system to work.";
	}

	//update config.php w/ server info
	$configfile = $sysdir . "includes/config.php";
	$fd = @fopen($configfile, "r");
	$cfg = fread($fd, 8000);
	@fclose($fd);
	$cfg = ereg_replace("sql_server(.+)#sql_server", "sql_server = \"$dbhost\";#sql_server", $cfg);
	$cfg = ereg_replace("sql_user(.+)#sql_user", "sql_user = \"$dbuser\";#sql_user", $cfg);
	$cfg = ereg_replace("sql_pass(.+)#sql_pass", "sql_pass = \"$dbpass\";#sql_pass", $cfg);
	$cfg = ereg_replace("sql_db(.+)#sql_db", "sql_db = \"$db\";#sql_db", $cfg);
	$cfg = ereg_replace("sql_type(.+)#sql_type", "sql_type = \"$dbtype\";#sql_type", $cfg);
	$fd = @fopen($configfile, "w");
	
	@fputs($fd, $cfg);
	@fclose($fd);

	$rs = &$conn->Execute("SELECT value FROM inl_config WHERE name='db_version'");
	$db_version = $rs->fields[0];

	if(!$db_version || $db_version< "2.0.08")
		return 0; //version too old

	if ($dbtype == "mysql")
		$idfield = "int NOT NULL auto_increment";
	elseif ($dbtype == "postgres7")
		$idfield = "serial";
	elseif ($dbtype == "mssql")
		$idfield = "int IDENTITY (1, 1) NOT NULL ";


	if($db_version <= "2.1.2n")
	{	
		$conn->Execute("CREATE TABLE inl_sessions (ses_id  $idfield, ses_time int DEFAULT '0' NOT NULL,user_id int DEFAULT '0' NOT NULL, user_perm int DEFAULT '0' NOT NULL, num_res varchar(25) NULL, link_order varchar(25) NULL, link_sort varchar(25) NULL, cat_order varchar(25) NULL, cat_sort varchar(25) NULL, lang varchar(25) NULL, theme varchar(25) NULL, PRIMARY KEY (ses_id))"); //create sessions

		$conn->Execute("INSERT INTO inl_config (name,value) VALUES ('session_get','1')");
		$conn->Execute("INSERT INTO inl_config (name,value) VALUES ('session_cookie','1')");
		$conn->Execute("INSERT INTO inl_config (name,value) VALUES ('ses_expiration','3600')");
		$conn->Execute("INSERT INTO inl_config VALUES ('reg_ip', '$reg_ip')");
		$conn->Execute("INSERT INTO inl_config VALUES ('reg_name', '$SERVER_NAME')");
		$conn->Execute("UPDATE inl_config SET value='$keya' WHERE name='keya'");
		$conn->Execute("UPDATE inl_config SET value='$keyb' WHERE name='keyb'");
				$conn->Execute("UPDATE inl_config SET value='$version' WHERE name='db_version'");
	}

	if($db_version <= "2.2.3n")
	{	$conn->Execute("ALTER TABLE inl_sessions ADD destin varchar(250) NULL");
		$conn->Execute("ALTER TABLE inl_users DROP user_ses");
		$conn->Execute("CREATE INDEX cat_id ON inl_lc (cat_id)");
		$conn->Execute("CREATE INDEX link_id ON inl_lc (link_id)");
		$conn->Execute("UPDATE inl_config SET value='$keya' WHERE name='keya'");
		$conn->Execute("UPDATE inl_config SET value='$keyb' WHERE name='keyb'");
		$conn->Execute("UPDATE inl_config SET value='$reg_ip' WHERE name='reg_ip'");
		$conn->Execute("UPDATE inl_config SET value='$SERVER_NAME' WHERE name='reg_name'");
		$conn->Execute("UPDATE inl_config SET value='$version' WHERE name='db_version'");
	}

	if($db_version <= "2.2.4n")
	{	$conn->Execute("UPDATE inl_config SET value='$keya' WHERE name='keya'");
		$conn->Execute("UPDATE inl_config SET value='$keyb' WHERE name='keyb'");
		$conn->Execute("UPDATE inl_config SET value='$reg_ip' WHERE name='reg_ip'");
		$conn->Execute("UPDATE inl_config SET value='$SERVER_NAME' WHERE name='reg_name'");
		$conn->Execute("UPDATE inl_config SET value='$version' WHERE name='db_version'");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'show_status_url', '1')");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'multiple_search_instances', '1')");
	}
	if($db_version <= "2.2.7n")
	{
		$conn->Execute("ALTER TABLE inl_search_log ADD search_action INT DEFAULT '0' not null AFTER log_keyword");
		$conn->Execute("ALTER TABLE inl_search_log ADD search_cat INT DEFAULT '0' not null AFTER search_action");
		$conn->Execute("UPDATE inl_config SET value='$keya' WHERE name='keya'");
		$conn->Execute("UPDATE inl_config SET value='$keyb' WHERE name='keyb'");
		$conn->Execute("UPDATE inl_config SET value='$reg_ip' WHERE name='reg_ip'");
		$conn->Execute("UPDATE inl_config SET value='$SERVER_NAME' WHERE name='reg_name'");
		$conn->Execute("UPDATE inl_config SET value='$version' WHERE name='db_version'");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'extended_search', '1')");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'high_lighting_tag1', '<B>')");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'high_lighting_tag2', '</B>')");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'do_link_name', '1')");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'do_link_desc', '1')");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'do_link_url', '1')");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'do_link_image', '0')");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'do_link_cust1', '0')");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'do_link_cust2', '0')");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'do_link_cust3', '0')");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'do_link_cust4', '0')");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'do_link_cust5', '0')");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'do_link_cust6', '0')");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'do_cat_name', '1')");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'do_cat_desc', '1')");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'do_cat_image', '0')");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'do_cat_cust1', '0')");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'do_cat_cust2', '0')");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'do_cat_cust3', '0')");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'do_cat_cust4', '0')");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'do_cat_cust5', '0')");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'do_cat_cust6', '0')");	
	}
	if($db_version <= "2.2.8n")
	{
		$conn->Execute("CREATE TABLE inl_fav (user_id int not null default '0', link_id int not null default '0')");
   	$conn->Execute("CREATE INDEX fav_user_id ON inl_fav (user_id)");
   	$conn->Execute("CREATE INDEX fav_link_id ON inl_fav (link_id)");
		$conn->Execute("CREATE TABLE inl_rel_cats (cat_id int not null default '0', rel_id int not null default '0')");
   	$conn->Execute("CREATE INDEX rel_cat_id ON inl_rel_cats (cat_id)");
   	$conn->Execute("CREATE INDEX rel_rel_id ON inl_rel_cats (rel_id)");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'rcols', '2')");
		$conn->Execute("UPDATE inl_config SET value='$keya' WHERE name='keya'");
		$conn->Execute("UPDATE inl_config SET value='$keyb' WHERE name='keyb'");
		$conn->Execute("UPDATE inl_config SET value='$reg_ip' WHERE name='reg_ip'");
		$conn->Execute("UPDATE inl_config SET value='$SERVER_NAME' WHERE name='reg_name'");
		$conn->Execute("UPDATE inl_config SET value='$version' WHERE name='db_version'");
	}
	if($db_version <= "2.2.9n")
	{

		$conn->Execute("CREATE TABLE inl_keywords (keyword_id $idfield,keyword varchar(50) NOT NULL default '',PRIMARY KEY  (keyword_id))");
		$conn->Execute("UPDATE inl_config SET value='$keya' WHERE name='keya'");
		$conn->Execute("UPDATE inl_config SET value='$keyb' WHERE name='keyb'");
		$conn->Execute("UPDATE inl_config SET value='$reg_ip' WHERE name='reg_ip'");
		$conn->Execute("UPDATE inl_config SET value='$SERVER_NAME' WHERE name='reg_name'");
		$conn->Execute("UPDATE inl_config SET value='$version' WHERE name='db_version'");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'pconnect', '')");
		$conn->Execute("UPDATE inl_config set value=CONCAT(value,'1') where name='email_perm'");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'subcat_order', 'cat_name')");
		$conn->Execute("INSERT INTO inl_config VALUES ( 'subcat_sort', 'asc')");
		
	}
	if($db_version == "2.2.10n")
	{
		$conn->Execute("UPDATE inl_config SET value='$keya' WHERE name='keya'");
		$conn->Execute("UPDATE inl_config SET value='$keyb' WHERE name='keyb'");
		$conn->Execute("UPDATE inl_config SET value='$reg_ip' WHERE name='reg_ip'");
		$conn->Execute("UPDATE inl_config SET value='$SERVER_NAME' WHERE name='reg_name'");	
	}
	return 1;
}

function dbcreate($dbhost, $dbuser, $db, $dbpass, $dbtype, $dbport) //CyKuH [WTN]
{

	global $block;
	global $dbcreated;
	global $error5;
	global $stepnum;
	global $sysdir;
	global $first;
	global $last;
	global $urldir;
	global $version;
	global $SERVER_NAME;

	if ($dbtype == "postgres7")
	{
	if ($dbhost)
		$dbhost = "host=$dbhost ";
	if ($dbport)
		{	$dbhost .= "port=$dbport ";
			$dbport = "";
		}
	if ($db)
		{	$dbhost .= "dbname=$db ";
			$db = "";
		}
	if ($dbuser)
		{	$dbhost .= "user=$dbuser ";
			$dbuser = "";
		}
	if ($dbpass)
		{	$dbhost .= "password=$dbpass";
			$dbpass = "";
		}
	}
	if(!$conn=&ADONewConnection($dbtype) || !$conn->PConnect($dbhost, $dbuser, $dbpass, $db))
	{
		
		$error5 = "Could not connect to the specified database.  Please check the database information.";
		$stepnum = 4;
		$block = 1;
		return 0;
	}
	elseif(!@fclose(@fopen($sysdir . "includes/config.php","a")))
	{
		$error5 = "The installation script can not write to the config file. You will have to edit the top portion of config.php manually in order for the system to work.";
	}
	
	//update config.php w/ server info
	$configfile = $sysdir . "includes/config.php";
	$fd = @fopen($configfile, "r");
	$cfg = fread($fd, 8000);
	@fclose($fd);
	$cfg = ereg_replace("sql_server(.+)#sql_server", "sql_server = \"$dbhost\";#sql_server", $cfg);
	$cfg = ereg_replace("sql_user(.+)#sql_user", "sql_user = \"$dbuser\";#sql_user", $cfg);
	$cfg = ereg_replace("sql_pass(.+)#sql_pass", "sql_pass = \"$dbpass\";#sql_pass", $cfg);
	$cfg = ereg_replace("sql_db(.+)#sql_db", "sql_db = \"$db\";#sql_db", $cfg);
	$cfg = ereg_replace("sql_type(.+)#sql_type", "sql_type = \"$dbtype\";#sql_type", $cfg);
	$fd = @fopen($configfile, "w");
	fputs($fd, $cfg);
	@fclose($fd);

	//create tables
	
	//config
	if ($dbtype == "mysql")
		$inl_config_u = ", UNIQUE name (name)";
	elseif ($dbtype == "postgres7")
		$inl_config_u = ", UNIQUE (name)";
	elseif ($dbtype == "mssql")
		$inl_config_u = "";

	$query[0] = $conn->Execute("CREATE TABLE inl_config (
   name varchar(255) NOT NULL,
   value varchar(255) NOT NULL,
   PRIMARY KEY (name)
   $inl_config_u)");

	//config values
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'cat_new', '5')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'cat_order', 'cat_name')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'cat_sort', 'asc')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'cc1', '')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'cc2', '')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'cc3', '')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'cc4', '')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'cc5', '')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'cc6', '')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'cols', '2')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'datefmt', 'm-d-Y')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'db_version', '$version')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'default_image', 'images/default.gif')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'default_meta_keywords', 'meta keywords')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'default_meta_desc', 'meta description')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'email_perm', '111111111111111')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'filedir', '$sysdir')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'filepath', '$urldir')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'first_name', '$first')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'force_pick', '1')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'keya', '$keya')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'keyb', '$keyb')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'language', 'english')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'last_name', '$last')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'lc1', '')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'lc2', '')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'lc3', '')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'lc4', '')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'lc5', '')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'lc6', '')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'lim', '10')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'link_new', '2')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'link_order', 'link_name')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'link_pop', '5')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'link_sort', 'asc')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'link_top', '5')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'rate_perm', '3')");	
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'rating_expiration', '30')");	
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'reg_code', '$pass')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'reg_ip', '$reg_ip')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'reg_name', '$SERVER_NAME')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'review_expiration', '30')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'review_order', 'rev_date')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'review_perm', '8')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'review_sort', 'desc')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'root_link_perm', '3')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'server', '$SERVER_NAME')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'ses_expiration', '3600')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'session_get', '1')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'session_cookie', '1')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'sitename', 'In-Link 2')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'suggest_cat_perm', '7')");	
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'theme', 'default')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'uc6', '')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'uc5', '')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'uc4', '')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'uc3', '')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'uc2', '')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'uc1', '')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'use_pick_tpl', '1')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'user_perm', '2')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'show_status_url', '1')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'multiple_search_instances', '1')");
	
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'extended_search', '1')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'high_lighting_tag1', '<B>')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'high_lighting_tag2', '</B>')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'do_link_name', '1')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'do_link_desc', '1')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'do_link_url', '1')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'do_link_image', '0')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'do_link_cust1', '0')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'do_link_cust2', '0')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'do_link_cust3', '0')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'do_link_cust4', '0')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'do_link_cust5', '0')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'do_link_cust6', '0')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'do_cat_name', '1')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'do_cat_desc', '1')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'do_cat_image', '0')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'do_cat_cust1', '0')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'do_cat_cust2', '0')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'do_cat_cust3', '0')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'do_cat_cust4', '0')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'do_cat_cust5', '0')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'do_cat_cust6', '0')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'rcols', '2')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'pconnect', '')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'subcat_order', 'cat_name')");
	$query[1] = $conn->Execute("INSERT INTO inl_config VALUES ( 'subcat_sort', 'asc')");


if ($dbtype == "mysql")
	$idfield = "int NOT NULL auto_increment";
elseif ($dbtype == "postgres7")
	$idfield = "serial";
elseif ($dbtype == "mssql")
		$idfield = "int IDENTITY (1, 1) NOT NULL ";

	$query[2] = $conn->Execute("CREATE TABLE inl_cats (
	cat_id $idfield,
   cat_name varchar(255),
   cat_desc text NULL,
   cat_user int DEFAULT '0' NOT NULL,
   cat_sub int DEFAULT '0' NOT NULL,
   cat_perm smallint DEFAULT '0' NOT NULL,
   cat_pend smallint DEFAULT '0' NOT NULL,
   cat_vis smallint DEFAULT '0' NOT NULL,
   cat_links int DEFAULT '0' NOT NULL,
   cat_cats int DEFAULT '0' NOT NULL,
   cat_date int DEFAULT '0' NOT NULL,
   cat_pick int DEFAULT '0' NOT NULL,
   cat_image varchar(255) NULL,
   cat_cust int DEFAULT '0' NOT NULL,
   meta_keywords text NULL,
   meta_desc text NULL,
   PRIMARY KEY (cat_id))");

   $conn->Execute("CREATE INDEX cat_sub ON inl_cats (cat_sub)");

	//custom
	$query[3] = $conn->Execute("CREATE TABLE inl_custom (
   cust_id $idfield,
   cust1 varchar(255) NULL,
   cust2 varchar(255) NULL,
   cust3 varchar(255) NULL,
   cust4 text NULL,
   cust5 text NULL,
   cust6 text NULL,
   PRIMARY KEY (cust_id))");

	//e-mail
	$query[4] = $conn->Execute("CREATE TABLE inl_email (
   email_id $idfield,
   email_subject varchar(255) NULL,
   email_body text NULL,
   email_from varchar(50) NULL,
   email_reply varchar(50) NULL,
   email_to varchar(50) NULL,	
   PRIMARY KEY (email_id))");

	//link cats
	$query[5] = $conn->Execute("CREATE TABLE inl_lc (
   link_id int DEFAULT '0' NOT NULL,
   cat_id int DEFAULT '0' NOT NULL,
   link_pend int DEFAULT '0' NOT NULL)");

   $conn->Execute("CREATE INDEX link_id ON inl_lc (link_id)");
   $conn->Execute("CREATE INDEX cat_id ON inl_lc (cat_id)");

	//links
	$query[6] = $conn->Execute("CREATE TABLE inl_links (
   link_id $idfield,
   link_name varchar(254) NOT NULL,
   link_desc text NOT NULL,
   link_url varchar(254) NOT NULL,
   link_date int DEFAULT '0' NOT NULL,
   link_user int DEFAULT '0' NOT NULL,
   link_hits int DEFAULT '0' NOT NULL,
   link_votes int DEFAULT '0' NOT NULL,
   link_rating decimal(6,4) DEFAULT '0.0000' NOT NULL,
   link_pick smallint DEFAULT '0' NOT NULL,
   link_vis smallint DEFAULT '0' NOT NULL,
   link_image varchar(254) NULL,
   link_cust int DEFAULT '0' NOT NULL,
   link_numrevs int DEFAULT '0' NOT NULL,
   PRIMARY KEY (link_id))");

	//reviews
	$query[7] = $conn->Execute("CREATE TABLE inl_reviews (
   rev_id $idfield,
   rev_link int DEFAULT '0' NOT NULL,
   rev_user int DEFAULT '0' NOT NULL,
   rev_text text NULL,
   rev_date int DEFAULT '0' NOT NULL,
   rev_pend int DEFAULT '0' NOT NULL,
   PRIMARY KEY (rev_id))");

   $conn->Execute("CREATE INDEX rev_link ON inl_reviews (rev_link)");

	//search log
	$query[8] = $conn->Execute("CREATE TABLE inl_search_log (
   log_id  $idfield,
   log_type smallint DEFAULT '0' NOT NULL,
   log_date int DEFAULT '0' NOT NULL,
   log_search smallint DEFAULT '0' NOT NULL,
   log_keyword varchar(255) NOT NULL,
   search_action int DEFAULT '0' NOT NULL ,
   search_cat int DEFAULT '0' NOT NULL ,
   PRIMARY KEY (log_id))");

   //sessions
	$query[11] = $conn->Execute("CREATE TABLE inl_sessions (
   ses_id  $idfield,
   ses_time int DEFAULT '0' NOT NULL,
   user_id int DEFAULT '0' NOT NULL,
   user_perm int DEFAULT '0' NOT NULL,
   num_res varchar(25) NULL,
   link_order varchar(25) NULL,
   link_sort varchar(25) NULL,
   cat_order varchar(25) NULL,
   cat_sort varchar(25) NULL,
   lang varchar(25) NULL,
   theme varchar(25) NULL,
   destin varchar(250) NULL,
   PRIMARY KEY (ses_id))");

   $conn->Execute("CREATE INDEX ses_id ON inl_sessions (ses_id)");

	//users
	if ($dbtype == "mysql")
		$inl_users_u = ", UNIQUE user_name (user_name)";
	elseif ($dbtype == "postgres7")
		$inl_users_u = ", UNIQUE (user_name)";
	elseif ($dbtype == "")
		$inl_users_u = "";

	$query[9] = $conn->Execute("CREATE TABLE inl_users (
	   user_id $idfield,
	   user_name varchar(20) NOT NULL,
	   user_pass varchar(50) NOT NULL,
	   first varchar(50) NOT NULL,
	   last varchar(50) NOT NULL,
	   email varchar(255) NULL,
	   user_perm int DEFAULT '0' NOT NULL,
	   user_date int DEFAULT '0' NOT NULL,
	   user_cust int DEFAULT '0' NOT NULL,
	   user_status smallint DEFAULT '0' NOT NULL,
	   user_pend int DEFAULT '0' NOT NULL,
	   PRIMARY KEY (user_id)  $inl_users_u)");

	//votes
   $query[10] = $conn->Execute("CREATE TABLE inl_votes (
   stamp int DEFAULT '0' NOT NULL,
   vote_ip varchar(16) NOT NULL,
   vote_link int DEFAULT '0' NOT NULL,
   rev int DEFAULT '0' NOT NULL)");

   $query[12] = $conn->Execute("CREATE TABLE inl_fav (
	user_id INT (11) not null , 
	link_id INT (11) not null , 
	INDEX (user_id, link_id))");

   $query[13] = $conn->Execute("CREATE TABLE inl_rel_cats (
	cat_id INT (11) not null , 
	rel_id INT (11) not null ,
	INDEX (cat_id, rel_id))");

	$query[14] = $conn->Execute("CREATE TABLE inl_keywords (
	keyword_id $idfield,
	keyword varchar(50) NOT NULL default '',
	PRIMARY KEY  (keyword_id))");

   $conn->Execute("CREATE INDEX stamp ON inl_votes (stamp)");
   $conn->Execute("CREATE INDEX vote_ip ON inl_votes (vote_ip)");
   $conn->Execute("CREATE INDEX vote_link ON inl_votes (vote_link)");

	$dbcreated = 1;
	$error5 ="success";
		
	return 1;
}

function setpass($userpass1, $userpass2) //CyKuH [WTN]
{
	global $block;
	global $db;
	global $error6;
	global $stepnum;
	global $dbhost;
	global $dbuser;
	global $dbpass;
	global $dbtype;
	global $first;
	global $last;

	if ($dbtype == "postgres7")
	{
	if ($dbhost)
		$dbhost = "host=$dbhost ";
	if ($dbport)
		{	$dbhost .= "port=$dbport ";
			$dbport = "";
		}
	if ($db)
		{	$dbhost .= "dbname=$db ";
			$db = "";
		}
	if ($dbuser)
		{	$dbhost .= "user=$dbuser ";
			$dbuser = "";
		}
	if ($dbpass)
		{	$dbhost .= "password=$dbpass";
			$dbpass = "";
		}
	}


	if ($userpass1 != $userpass2)
	{
		$error6 = "Passwords do not match.  Please reenter.";
		$stepnum = 5;
		$block = 1;
		return 0;
	}
	elseif (strlen($userpass1) < 4)
	{
		$error6 = "The password must be at least 4 characters long";
		$stepnum = 5;
		$block = 1;
		return 0;
	}
	elseif(!$conn=&ADONewConnection($dbtype) || !$conn->PConnect($dbhost, $dbuser, $dbpass, $db))
	{
			$error6 = "Could not connect to the specified database.  Please go back and check the database information.";
			$stepnum = 5;
			$block = 1;
			return 0;
	}
	else
	{
		$passenc = md5($userpass1);
		$timestmp = time();

		$rs=&$conn->Execute("SELECT user_pass FROM inl_users WHERE user_name='root'");
		if($rs && !$rs->EOF)
			$conn->Execute("UPDATE inl_users SET user_pass='$passenc' WHERE user_name='root'");
		else
			$conn->Execute("insert into inl_users (user_name, user_pass, first, last, user_perm, user_status, user_date) values ('root', '$passenc', '$first', '$last', '1', '1', '$timestmp')");
			
		return 1;
	}
}

?>


<title>In-link 2 :: Installation Utility</title>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="admin.css" type="text/css">
</head>



<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
  <tr> 
    <td align="left" height="0"><img src="images/logo.gif" width="176" height="64" border="0"></td>
    <td align="right" height="0"> 
      <div align="right"> 
        <p class="title">Installation Utility</p>
      </div>
    </td>
  </tr>
  <tr class="bottomborder"> 
    <td bgcolor="#FCDC43" valign="middle" class="bottomborder" height="0"><img src="images/moto.gif" width="269" height="21" border="0" align="middle"></td>
    <td bgcolor="#FCDC43" valign="middle" class="bottomborder" height="0"> 
      <div align="right"><span class="small">In-link Version <b><?php echo $version; ?> </b></span></div>
    </td><!--CyKuH [WTN]-->
  </tr>
  <tr class="bottomborder" bgcolor="#FFFFFF"> 
    <td height="100%" valign="top" class="bottomborder" colspan="2"><br><br> 
      <div align="right" class="text"> 
        <table width="90%" border="0" cellspacing="0" cellpadding="2" class="tableborder" align="center">
          <tr> 
            <td class="tabletitle" bgcolor="#666666"> <?php 
// CyKuH [WTN]
if ($stepnum == 6 && $submit == "Next Step" && setpass($userpass1, $userpass2))
	$turn = 6;
elseif ($stepnum == 5 && $submit == "Next Step" && dbcheck($dbhost, $dbuser, $db, $dbpass, $dbtype, $dbport))
{	
	if (usercheck($dbhost, $dbuser, $db, $dbpass, $dbtype, $dbport))
	{	$turn = 6;
		$stepnum = 6;
	}
	else	
		$turn = 5;
}
elseif ($stepnum == 5 && $turn!=5 && $submit == "Next Step" && dbcreate($dbhost, $dbuser, $db, $dbpass, $dbtype, $dbport))
	$turn = 5;
elseif ($stepnum == 4 && $submit == "Next Step")
	$turn = 4;
elseif ($stepnum == 3 && $submit == "Next Step" && installpath($first, $last, $sysdir, $urldir, gethostbyname($SERVER_NAME)))
{	ntcheck();
	$turn = 3;	
}
elseif ($stepnum == 2 && $submit == "Next Step")
	$turn = 2;
elseif ($stepnum == 1)
	$turn = 1;
elseif($stepnum == 6) 
	echo "Installation Complete";
else
	echo "Step ".$stepnum." out of 5";
?></td><!--CyKuH [WTN]-->

          </tr><tr> 
            <td bgcolor="#F6F6F6"> 
              <form name="form1" method="post">

	<?php
	# DONE #####################################################################
	 if ($turn == 6): ?>
				
				<?php $block=0; ?>
				<table width="100%" border="0" cellspacing="0" cellpadding="4">
                  <tr bgcolor="#999999" valign="middle"> 
                    <td colspan="2" class="textTitle">Installation Complete!</td>
                  </tr>
                  <tr bgcolor="#F6F6F6" class="tableborder"> 
                    <td class="text" colspan="2"> 
                      <br><br>

					
                      <p align="center"><?php echo $first; ?>, your copy of In-link has been successfully installed. <br> Please, proceed to the <a href="index.php">Administration Panel</a> to configure it.
					  
					  <?php //or <br>proceed to the In-Link <a href="check.php">Verification Utility</a> to check the integrity of your installation.</p>?>		
			
                    </td>
                  </tr>
				    
                  <tr bgcolor="#F6F6F6" valign="middle"> 
                    <td class="text" colspan="2" valign="top"> 
                 <p></p>
                      <p align="right">&nbsp;<!--CyKuH [WTN]-->
						<input type="hidden" name="stepnum" value="7">
						<input type="hidden" name="sysdir" value="<?php echo $sysdir ?>">
						<input type="hidden" name="urldir" value="<?php echo $urldir ?>">
						<input type="hidden" name="first" value="<?php echo $first ?>">
						<input type="hidden" name="last" value="<?php echo $last ?>">
                        </span></p>
                    </td>
                  </tr>
                </table>
	<?php
	# SECURITY #####################################################################
	 elseif ($turn == 5): ?>

				<?php $block=0; ?>
				<table width="100%" border="0" cellspacing="0" cellpadding="4">
                  <tr bgcolor="#999999" valign="middle"> 
                    <td colspan="2" class="textTitle">Security</td>
                  </tr>
                  <tr bgcolor="#F6F6F6" class="tableborder"> 
                    <td class="text" colspan="2"> 
                      <p><i>In order to access In-link administration utility you need to set up a root administrative user.  This user can never be deleted and overrides all other users.</i></p>
                      

					
                      <p>Please, choose a safe password for the root user.</p>
		
			
                    </td>
                  </tr>
				     <tr bgcolor="#DEDEDE" class="tableborder"> 
					<td class="error" colspan="2">
					<?php echo $error6; ?></td>
				  </tr>
					<tr bgcolor="#DEDEDE" class="tableborder"> 
                    <td class="text">Root user:</td>
                    <td class="text"> 
                      root
                  </tr>
                  <tr bgcolor="#DEDEDE" class="tableborder"> 
                    <td class="text" nowrap>Root password:</td>
                    <td class="text"> 
                      <input type="password" name="userpass1" class="text">
                      <br>
                    </td>
                  </tr>
				    <tr bgcolor="#DEDEDE" class="tableborder"> 
                    <td class="text" nowrap>Retype password:</td>
                    <td class="text"> 
                      <input type="password" name="userpass2" class="text">
                      <br>
                    </td>
                  </tr>
               
                  <tr bgcolor="#F6F6F6" valign="middle"> 
                    <td class="text" colspan="2" valign="top"> 
                 <p><span class="hint"><img src="images/mark.gif" width="16" height="16" align="absmiddle">The root user overrides all of the admins and can not be deleted. Please, exercise caution when setting the root password. Your data can not be recovered if the password is lost.
</span></p>
      <p align="right">&nbsp;<!--CyKuH [WTN]-->
				<input type="hidden" name="stepnum" value="6">
				<input type="hidden" name="dbcreated" value="<?php echo $dbcreated ?>">
				<input type="hidden" name="sysdir" value="<?php echo $sysdir ?>">
				<input type="hidden" name="urldir" value="<?php echo $urldir ?>">
				<input type="hidden" name="first" value="<?php echo $first ?>">			
				<input type="hidden" name="last" value="<?php echo $last ?>">
				<input type="hidden" name="dbhost" value="<?php echo $dbhost ?>">
				<input type="hidden" name="db" value="<?php echo $db ?>">
				<input type="hidden" name="dbuser" value="<?php echo $dbuser ?>">
				<input type="hidden" name="dbpass" value="<?php echo $dbpass ?>">
				<input type="hidden" name="dbtype" value="<?php echo $dbtype ?>">
				<input type="hidden" name="dbport" value="<?php echo $dbport ?>">

						<input type="submit" name="submit" value="Next Step" class="button">
                        </span></p>
                    </td>
                  </tr>
                </table>


	<?php
	# DATABASE #####################################################################
	 elseif ($turn == 4): ?>

				<?php $block=0; ?>
				<table width="100%" border="0" cellspacing="0" cellpadding="4">
                  <tr bgcolor="#999999" valign="middle"> 
                    <td colspan="2" class="textTitle">Database Configuration</td>
                  </tr>
                  <tr bgcolor="#F6F6F6" class="tableborder"> 
                    <td class="text" colspan="2"> 
                      <p><i>In order for In-link to operate, it needs access to a MySQL database running on the server. </i></p>
                      

					
                      <p>Prior to submitting this form make sure that you have a MySQL installed and have a database set up on your server. The database user must have full access to the database.</p>
		
			
                    </td>
                  </tr>
				     <tr bgcolor="#DEDEDE" class="tableborder"> 
					<td class="error" colspan="2">
					<?php echo $error5; ?></td>
				  </tr>
					<tr bgcolor="#DEDEDE" class="tableborder"> 
                    <td class="text">SQL databse host:</td>
                    <td class="text"> 
                      <input type="text" name="dbhost" class="text" value="<?php echo $dbhost ?>">
                      <br>
                      <span class="small">Ex.: localhost</span></td>
                  </tr>
                  <tr bgcolor="#DEDEDE" class="tableborder"> 
                    <td class="text" nowrap>SQL database name:</td>
                    <td class="text"> 
                      <input type="text" name="db" class="text" value="<?php echo $db ?>">
                      <br>
                    </td>
                  </tr>
                  <tr bgcolor="#DEDEDE" class="tableborder">
                    <td class="text" nowrap>SQL database username:</td>
                    <td class="text">
                      <input type="text" name="dbuser" class="text" value="<?php echo $dbuser ?>">
                      <br>
                    </td>
                  </tr>
                  <tr bgcolor="#DEDEDE" class="tableborder">
                    <td class="text" nowrap>SQL database password:</td>
                    <td class="text">
                      <input type="password" name="dbpass" class="text" value="<?php echo $dbpass ?>">
                      <br>
                    </td>
                  </tr>
                  <tr bgcolor="#DEDEDE" class="tableborder">
                    <td class="text" nowrap>SQL database type:</td>
                    <td class="text">
                      <select name="dbtype" class="text">
						<option value="mysql"<?php if ($dbtype == "mysql"){echo " selected";} ?>>MySQL</option>
						<option value="postgres7"<?php if ($dbtype == "postgres7"){echo " selected";} ?>>PostgreSQL 7.0</option>
						<option value="mssql"<?php if ($dbtype == "mssql"){echo " selected";} ?>>Microsoft SQL</option>
					  </select>
                      <br>
                    </td>
                  </tr>
				  <tr bgcolor="#DEDEDE" class="tableborder">
                    <td class="text" nowrap>SQL database port (PostgreSQL only):</td>
                    <td class="text">
                      <input type="text" name="dbport" class="text" value="<?php echo $dbport ?>">
                      <br>
                    </td>
                  </tr>
                  <tr bgcolor="#F6F6F6" valign="middle"> 
                    <td class="text" colspan="2" valign="top"> 
                 <p><span class="hint"><img src="images/mark.gif" width="16" height="16" align="absmiddle">Visit: in-link.net/db.php for more information on how to set up and configure MySQL database.</span></p>
                      <p align="right">&nbsp;<!--CyKuH [WTN]-->
						<input type="hidden" name="stepnum" value="5">
						<input type="hidden" name="sysdir" value="<?php echo $sysdir ?>">
						<input type="hidden" name="urldir" value="<?php echo $urldir ?>">
						<input type="hidden" name="first" value="<?php echo $first ?>">
						<input type="hidden" name="last" value="<?php echo $last ?>">
						<input type="submit" name="submit" value="Next Step" class="button">
                        </span></p>
                    </td>
                  </tr>
                </table>
	<?php
	# PERMISSIONS #####################################################################
			elseif ($turn == 3): ?>
				
			<?php $block=0; ?>
			<table width="100%" border="0" cellspacing="0" cellpadding="4">
                  <tr bgcolor="#999999" valign="middle"> 
                    <td colspan="2" class="textTitle">File Permissions</td>
                  </tr>
                  <tr bgcolor="#F6F6F6" class="tableborder"> 
                    <td class="text" colspan="2"> 
                      <p><i>When you upload In-link files to your server the file permissions need to be set in order for In-link 2 to function properly. </i></p>

				<?php 
					if (ntcheck())
					{
						echo "<p>It seems that you are using Windows NT/2000. Install can not set the file permissions for you but it will attempt to check them.</p>";
						$permch="writeable";
					}
					else
					{
						echo "<p>In-link will now attempt to set file permissions automatically. Some server configurations will not allow that, in which case you either set the file permissions manually or use tha batch script that is included with the installation.</p>";
						$permch="777 (drwxrwxrwx)";
					}
				
				?>

					  <?php
							
						$dir = "includes/";
						$file = $sysdir.$dir."config.php";

						echo "*** Checking config.php... ";
					
						if (!@fclose(@fopen($file,"a")))
						{	if (!@chmod ($file, 0777))
							{	$error4 = "In-link could not set file permissions automatically due to the server configuration.  Please set the above permissions manually or use the batch script to do that.";
							}
							
							echo "<br><b>".$dir."config.php". "<span class=\"error\"> - set file permissions to $permch</span></b><br>";
						}
						else
							echo "<b><font color=green>OK</font></b><br>";
										

						$dir = $sysdir."themes/";
						$dir_op = opendir($sysdir."themes/");

						echo "*** Checking theme sets... ";
										
						while ($subdir = readdir($dir_op))
						{
							if (is_dir($dir.$subdir) && $subdir!=".." && $subdir!=".")
							{
								$dir2 = $dir.$subdir."/";
								$dir2_op = opendir($dir.$subdir."/");

								while ($file = readdir($dir2_op)) 
								{								
									if (is_file($dir2.$file))
									{
										if (!@fclose(@fopen($dir2.$file,"a")))
										{
											if (!@chmod ($dir2.$file, 0777))
											{
												$error4 = "In-link could not set file permissions automatically due to the server configuration.  Please set the above permissions manually or use the batch script to do that.";
											}

							        echo "<br>".$dir2.$file."<b><span class=\"error\"> - set file permissions to $permch</span></b>";
									$broken1 = 1;

										}	
									}
								}
							}
						}

						if ($broken1 == 0){
							echo "<b><font color=green>OK</font></b>";
						}

						$dir = $sysdir."languages/";
						$dir_op = opendir($sysdir."languages/");

						echo "<br>*** Checking language sets... ";
										
						while ($subdir = readdir($dir_op))
						{
							if (is_dir($dir.$subdir) && $subdir!=".." && $subdir!=".")
							{
								$dir2 = $dir.$subdir."/";
								$dir2_op = opendir($dir.$subdir."/");

								while ($file = readdir($dir2_op)) 
								{								
									if (is_file($dir2.$file))
									{
										if (!@fclose(@fopen($dir2.$file,"a")))
										{
											if (!@chmod ($dir2.$file, 0777))
											{
												$error4 = "In-link could not set file permissions automatically due to the server configuration.  Please set the above permissions manually or use the batch script to do that.";
											}

							        echo "<br>".$dir2.$file."<b><span class=\"error\"> - set file permissions to $permch</span></b>";
									$broken2 = 1;

										}	
									}
								}
							}
						}

						if ($broken2 == 0){
							echo "<b><font color=green>OK</font></b><br>";
						}

						$dir = $sysdir."admin/backup";
						$file = $dir . "/dump.txt";
						echo "<br>*** Checking backup directory... ";

						if (!@fclose(@fopen($file,"a")))
						{	if (!@chmod ($dir."/dump.txt", 0777))
							{
								$error4 = "In-link could not set file permissions automatically due to the server configuration.  Please set the above permissions manually or use the batch script to do that.";
							}

							echo "<br>".$dir. "/dump.txt<span class=\"error\"> - set file permissions to $permch</span><br>";
						}
						else
						{
							echo "<b><font color=green>OK</font></b><br>";
						}

					  ?>
					  					  
					  <p>
		  			<span class="error"><?php echo $error4 ?></span>
								
					<?php if ($error4!=""): ?>
		        <p>Please do not proceed further untill all of the file permissions are set, then reload this page </p><p><span class="hint"><img src="images/mark.gif" width="16" height="16" align="absmiddle">Visit: in-link.net/permissions.php for the information 
                        on how to set file permissions manually.</span></p>

					<?php else: ?>
					  					  
					  <p>In-link successfully checked and/or set all the file permissions.  Please, proceed to the next step.</p>

					<?php endif	?>	

					  </p>
					</td>
                  </tr>
                  
                  <tr bgcolor="#F6F6F6" valign="middle"> 
                    <td class="text" colspan="2" valign="top"> 
                 
                      <p align="left">&nbsp;</p><!--CyKuH [WTN]-->
                      <p align="right" class="hint"><span class="hint"> 						
			<input type="hidden" name="stepnum" value="4">
						<input type="hidden" name="sysdir" value="<?php echo $sysdir ?>">
						<input type="hidden" name="urldir" value="<?php echo $urldir ?>">
						<input type="hidden" name="first" value="<?php echo $first ?>">
						<input type="hidden" name="last" value="<?php echo $last ?>">
						<input type="submit" name="submit" value="Next Step" class="button">
                        </span></p>
                    </td>
                  </tr>
                </table>


    <?php
	# PATH #####################################################################
	 elseif ($turn == 2): ?>

				<?php $block=0; ?>
				<table width="100%" border="0" cellspacing="0" cellpadding="4">
                  <tr bgcolor="#999999" valign="middle"> 
                    <td colspan="2" class="textTitle">Program Location</td>
                  </tr>
                  <tr bgcolor="#F6F6F6" class="tableborder"> 
                    <td class="text" colspan="2"> 
                      <p><i>In-link needs to know its exact location on the server 
                        in order to function properly.</i></p>

					<?php echo checkpath();

					 if ($error2!="") { ?>
                      <p>In-link unsuccessfully or unreliabely attempted to detemine its location on the server. Please, check where the script is located on your server. Include trailing slashes (&quot;/&quot;) after the paths. </p>

					<?php } else { ?>
					  					  
					  <p>In-link automatically determined its location on the server. 
                        Please, check to make sure that the determined path matches to the correct location of the script on your server. 
                        Include trailing slashes (&quot;/&quot;) after the 
                        paths. </p>

					<?php }	?>	
						
					
                    </td>
                  </tr>
				     <tr bgcolor="#DEDEDE" class="tableborder"> 
					<td class="error" colspan="2">
					<?php echo $error21; ?>
					<?php echo $error2; ?>
					</td>
				  </tr>
                  <tr bgcolor="#DEDEDE" class="tableborder"> 
                    <td class="text">Absolute path:</td>
                    <td class="text"> 
                      <input type="text" name="sysdir" class="text" size="30" value="<?php echo deter_sysdir(); ?>">
                      <br>
                      <span class="small">Ex.: /home/user/public_html/inlink/</span></td>
                  </tr>
                  <tr bgcolor="#DEDEDE" class="tableborder"> 
                    <td class="text" nowrap>URL path:</td>
                    <td class="text"> 
                      <input type="text" name="urldir" class="text" size="30" value="<?php echo deter_urldir(); ?>">
                      <br>
                      <span class="small">Ex.: /inlink/</span></td>
                  </tr>
                  <tr bgcolor="#F6F6F6" valign="middle"> 
                    <td class="text" colspan="2" valign="top"> 
                      <p align="left"><span class="hint"><img src="images/mark.gif" width="16" height="16" align="absmiddle">Server 
                        path is the actual path to the files on your server. This 
                        is the path you would see when &quot;browsing&quot; to 
                        the In-link files on your server. On Unix/Linux you can 
                        see what this path is by typing in the command &quot;pwd&quot; 
                        in your shell session while located in the same directory 
                        where In-link is.</span></p>
                      <p align="left"><span class="hint"><img src="images/mark.gif" width="16" height="16" align="absmiddle">URL 
                        path is the the path that you would see in your browser 
                        address field; it is usually the URL path following your 
                        domain.</span></p>
                      <p align="right">&nbsp;<!--CyKuH [WTN]-->
			<input type="hidden" name="stepnum" value="3">
						<input type="hidden" name="first" value="<?php echo $first ?>">
						<input type="hidden" name="last" value="<?php echo $last ?>">
						<input type="submit" name="submit" value="Next Step" class="button">
                        </span></p>
                    </td>
                  </tr>
                </table>


     <?php 
 	# REGISTRATION #####################################################################
	 elseif ($turn == 1): ?>

				<?php $block=0; ?>
				<table width="100%" border="0" cellspacing="0" cellpadding="4">
                  <tr bgcolor="#999999" valign="middle"> 
                    <td colspan="2" class="textTitle">License</td>
                  </tr>
                  <tr bgcolor="#F6F6F6" class="tableborder"> 
                    <td class="text" colspan="2"> 
                      <p><i>Thank you for downloading In-link 2 (Nullified Edition) - the most                                                   powerful portal solution script up-to-date. This utility will 
                        help you to install In-link 2 by guiding you through the 
                        entire installation process. </i></p>
                      <p>Thanks to <b>CyKuH [WTN]</b> you do not have to worry                          			 about any of that registration crap. Simply enter the first and second 			 name you would like to register the software under and it shall be 			 done with no questions asked.</p>
					</td>
                  </tr>
                  <tr bgcolor="#DEDEDE" class="tableborder"> 
					<td class="error" colspan="2"><?php echo $error ?></td>
				  </tr>
                   <tr bgcolor="#DEDEDE" class="tableborder"> 
                    <td class="text">First Name:</td>
                    <td class="text"> 
                      <input type="text" name="first" class="text" value="<?php echo $first ?>">
                      <br>
                      <span class="small">Ex.: Vasja</span></td>
                  </tr>
				  <tr bgcolor="#F6F6F6" class="tableborder"> 
                    <td class="text">Last Name:</td>
                    <td class="text"> 
                      <input type="text" name="last" class="text" value="<?php echo $last ?>">
                      <br>
                      <span class="small">Ex.: Pupkin</span></td>
                  </tr>
                 
				  <?php 
				  if (!check_netconn()) 
				  {
					  ?>
				  <tr bgcolor="#DEDEDE" class="tableborder"> 
                    <td class="text" nowrap>Server Name</td>
                    <td class="text"> 
                      <span class="small">You are installing In-link on: "<?php echo $SERVER_NAME;?>"</span></td>
                  </tr>
				  <input type="hidden" name="static" value="1">
				  <?php } ?>
                  <tr bgcolor="#F6F6F6" valign="middle"> 
                    <td class="text" colspan="2" valign="top">
                      <p align="left"><span class="hint"><img src="images/mark.gif" width="16" height="16" align="absmiddle">
                      Disable function by CyKuH [WTN]
                        <img src="images/arrow1.gif" width="8" height="9" border="0"> 
                        </span> <span class="small"><br>
                        </span> </p>
                      <p align="left">&nbsp;</p>
                      <p align="right" class="hint"><span class="hint"> 
						
						
                        <input type="hidden" name="stepnum" value="2">
						<input type="submit" name="submit" value="Next Step" class="button">
                        </span></p>
                    </td>
                  </tr>
                </table>
	<?php endif	?>
			 </form>
                  <p align="left">
                  <span class="hint"><img src="images/mark.gif" width="16" height="16" align="absmiddle">If you run into problems with installation of In-link, then you must be a very silly billy.</span>
		</p>
              </td>
          </tr>

        </table>
      </div>
    </td>
  </tr>
</table>
</body>
</html>
