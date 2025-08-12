<?php

error_reporting(0);
set_time_limit(90);
ini_set('track_errors', true);

$MYSQL_VERSION = '3.23.48';
$PHP_VERSION = '4.3.0';

$DEFAULT_CONFIG = array (
	"DefaultFrontendLanguage"	=> "en",
	"DefaultCharacterSet"		=> "iso-8859-1",
	"MaxLoginAttempts"		=> "3",
	"MaxIdleTime"			=> "6000",
	"SuperUsers"			=> "root",
	"WebMasters"			=> "root",
	"AuthenticationType"	=> "default",
	"BackendSuffix"			=> "_edit"
);

$ALLOWED_BLANK = array (
	"WebDatabase" => array (
		"password"
	),
	"UserDatabase" => array (
		"password"
	)
);

 ############################################################################
# Takes to version numbers returns 1 if the first one is greater, or 0, or -1
function version_no_compare($ver1, $ver2) {
	$v1s = explode(".",ereg_replace("[^0-9\.]","",$ver1));
	$v2s = explode(".",ereg_replace("[^0-9\.]","",$ver2));
	$i = 0;
	while(true) {
		if($i >= count($v1s) && $i >= count($v2s)) return 0;
		if($i >= count($v2s) && $i <  count($v1s)) return 1;
		if($i >= count($v1s) && $i <  count($v2s)) return -1;
		if($v1s[$i] > $v2s[$i]) return 1;
		if($v1s[$i] < $v2s[$i]) return -1;
		$i++;
	}
}

function gpc_stripslashes($var) {
	if (get_magic_quotes_gpc()) {
		$var = stripslashes($var);
	}
	return $var;
}

/*
	Check the PHP Version, and the INI file settings before continuing. Not much point unless they are right. :)
*/

if (!minimum_version($PHP_VERSION)) {
	echo "We need PHP Version ".$PHP_VERSION." or higher to use MySource. Download a newer version from <a href=\"http://www.php.net/\">http://www.php.net/</a>, install that, and try again.<br>";
	exit;
}
if (!check_ini_file()) {
	echo "MySource uses Short Tags. This needs to be activated before running MySource. Please edit your php.ini (usually located either in /etc/php.ini, /usr/local/apache/etc/php.ini, or c:\&lt;windows path&gt;\php.ini (windows path can be windows, winnt, win2k etc).<br>";
	exit;
}

function check_ini_file() {
	$short_tag = ini_get("short_open_tag");
	if ($short_tag == 0) {
		return false;
	} else {
		return true;
	}
}

function minimum_version($vercheck) {
	$minver = explode(".", $vercheck);
	$curver = explode(".", phpversion());
	if (($curver[0] < $minver[0]) || (($curver[0] == $minver[0]) && ($curver[1] < $minver[1])) || (($curver[0] == $minver[0]) && ($curver[1] == $minver[1]) && ($curver[2][0] < $minver[2][0]))) {
		return false;
	} else {
		return true;
	}
}

function find_sql($dir, $file, $prune=array()) {

	# If we're meant to skip it, let's skip it.
	if (in_array("$dir/$file", $prune)) return;

	# find files from a specified directory.
	if(file_exists("$dir/$file")) {
		return "$dir/$file";
	}

	if(!$d = opendir($dir)) {
		echo "Unable to open Directory: $dir".__FILE__.__LINE__.": " . $php_errormsg . "<br>";
		return false;
	}

	$result = array();
	while($f = readdir($d)) {
		if (is_dir("$dir/$f") && $f[0] != "." && $f != "CVS") {
			$result[$f] = find_sql("$dir/$f",$file, $prune);
		}
		if (empty($result[$f])) unset($result[$f]);
	}
	closedir($d);
	return $result;
}

function show_break() {
?>
	<tr>
		<td colspan="2">
			<hr width="95%" size="1" noshade>
		</td>
	</tr>
<?php
} # end show_break

function config_form($msg="Configure MySource") {
?>
<form name="configmysource" method="post" action="<?php echo $_SERVER['PHP_SELF']?>?action=configure">
	<table border="0" cellspacing="0" cellpadding="5">
		<tr>
			<td colspan="2" align="left">
				<?php echo $msg?>
			</td>
		</tr>
		<tr>
			<td valign="top">
				1. Where is MySource installed on this system?&nbsp;<br />
				(eg. c:\mysource, /home/mysource)
			</td>
			<td valign="top">
				<input type="text" name="MySource_Install_Location" size="30" value="<?php echo (!empty($_POST['MySource_Install_Location'])) ? gpc_stripslashes($_POST['MySource_Install_Location']) : dirname(__FILE__);?>">
			</td>
		</tr>
		<tr>
			<td valign="top">
				2. Please enter a System Name:&nbsp;<br /> 
				This is simply a symbolic name used to identify the site.<br />
				Typically something like 'My Site' or 'My System'
			</td>
			<td valign="top">
				<input type="text" name="System_Name" size="30" value="<?php echo gpc_stripslashes($_POST['System_Name'])?>">
			</td>
		</tr>
		<? show_break(); ?>
		<tr>
			<td valign="top">
				3. Please enter a Web Database Name:&nbsp;
			</td>
			<td valign="top">
				<input type="text" name="Web_Data_base[db]" size="30" value="<?php echo (!$_POST['Web_Data_base']['db']) ? "mysource" : gpc_stripslashes($_POST['Web_Data_base']['db']) ?>">
			</td>
		</tr>
		<tr>
			<td valign="top">
				4. Please enter a Web Database Host:&nbsp;
			</td>
			<td valign="top">
				<input type="text" name="Web_Data_base[host]" size="30" value="<?php echo (!$_POST['Web_Data_base']['host']) ? "localhost" : gpc_stripslashes($_POST['Web_Data_base']['host']) ?>">
			</td>
		</tr>
		<tr>
			<td valign="top">
				5. Please enter a Web Database Username:&nbsp;
			</td>
			<td valign="top">
				<input type="text" name="Web_Data_base[user]" size="30" value="<?php echo gpc_stripslashes($_POST['Web_Data_base']['user']) ?>">
			</td>
		</tr>
		<tr>
			<td valign="top">
				6. Please enter your Web Database Password:&nbsp;
			</td>
			<td valign="top">
				<input type="password" name="Web_Data_base[password]" size="30" value=""><br>
			</td>
		</tr>
		<? show_break(); ?>
		<tr>
			<td valign="top">
				7. Please enter a User Database Name:<br>This can be the same as the Web Database Name.&nbsp;
			</td>
			<td valign="top">
				<input type="text" name="User_Data_base[db]" size="30" value="<?php echo (!$_POST['User_Data_base']['db']) ? "mysource" : gpc_stripslashes($_POST['User_Data_base']['db']) ?>">
			</td>
		</tr>
		<tr>
			<td valign="top">
				8. Please enter a User Database Host:&nbsp;
			</td>
			<td valign="top">
				<input type="text" name="User_Data_base[host]" size="30" value="<?php echo (!$_POST['User_Data_base']['host']) ? "localhost" : gpc_stripslashes($_POST['User_Data_base']['host']) ?>">
			</td>
		</tr>
		<tr>
			<td valign="top">
				9. Please enter a User Database Username:&nbsp;
			</td>
			<td valign="top">
				<input type="text" name="User_Data_base[user]" size="30" value="<?php echo gpc_stripslashes($_POST['User_Data_base']['user']) ?>">
			</td>
		</tr>
		<tr>
			<td valign="top">
				10. Please enter your User Database Password:&nbsp;
			</td>
			<td valign="top">
				<input type="password" name="User_Data_base[password]" size="30" value=""><br>
			</td>
		</tr>
		<? show_break(); ?>
		<tr>
			<td valign="top">
				11. Please enter a MySource Web Master Email Address:&nbsp;
			</td>
			<td valign="top">
				<input type="text" name="Web_Master_Email" size="30" value="<?php echo gpc_stripslashes($_POST['Web_Master_Email']) ?>">
			</td>
		</tr>
		<? show_break(); ?>
		<tr>
			<td valign="top" colspan="2">
				12. Please choose the type of statistics you would like to use:&nbsp;
			</td>
		</tr>
		<tr>
			<td valign="top" colspan="2" align="center">
				<table border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td valign="top">
							<input type="radio" name="StatisticsReporter" value="s" <?php echo ($_POST['StatisticsReporter'] == 'simple') ? 'CHECKED' : '';?>>
						</td>
						<td valign="top">
							Simple statistics
						</td>
					</tr>
					<tr>
						<td valign="top">
							&nbsp;
						</td>
						<td valign="top">
							This will record a running total only for each page and file.
							Your database will stay around the same size,
							but you won't get anything useful out of the statistics provided.
						</td>
					</tr>
					<tr>
						<td valign="top">
							<input type="radio" name="StatisticsReporter" value="moderate" <?php echo ($_POST['StatisticsReporter'] == 'moderate') || (!isset($_POST['StatisticsReporter'])) ? 'CHECKED' : '';?>>
						</td>
						<td valign="top">
							Moderate statistics (Recommended)
						</td>
					</tr>
					<tr>
						<td valign="top">
							&nbsp;
						</td>
						<td valign="top">
							This will record a total hit count per page and file per day.
							While your database will grow in size, it will be a moderate growth rate.
							Only one log entry per page per day.
						</td>
					</tr>
					<tr>
						<td valign="top">
							<input type="radio" name="StatisticsReporter" value="detailed" <?php echo ($_POST['StatisticsReporter'] == 'detailed') ? 'CHECKED' : '';?>>
						</td>
						<td valign="top">
							Detailed statistics
						</td>
					</tr>
					<tr>
						<td valign="top">
							&nbsp;
						</td>
						<td valign="top">
							This will record a hit count per page and file based on the userid and sessionid of the person viewing the item.
							You will be able to break down statistics to which minute / hour an item was viewed.
							This means the database can get very large very quickly. 3 log entries per item view.
							This is recommended if you use your own server.
						</td>
					</tr>
					<tr>
						<td valign="top">
							<input type="radio" name="StatisticsReporter" value="none" <?php echo ($_POST['StatisticsReporter'] == 'none') ? 'CHECKED' : '';?>>
						</td>
						<td valign="top">
							No statistics
						</td>
					</tr>
					<tr>
						<td valign="top">
							&nbsp;
						</td>
						<td valign="top">
							You're not interested in statistics. Other software (webalizer, awstats, other) will suffice.
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<? show_break(); ?>
		<tr>
			<td valign="top">
				13. Please enter a MySource Root Password:&nbsp;
				<p align="center">(Confirm):</p>
			</td>
			<td valign="top">
				<input type="password" name="Root[password]" size="30" value=""><br>
				<input type="password" name="Root[password_confirmation]" size="30" value="">
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type="submit" value=" Submit ">&nbsp;&nbsp;<input type="reset" value=" Clear ">
			</td>
		</tr>
	</table>
</form>
<?php
} # end function config_form()

function generate_config($location) {
	global $DEFAULT_CONFIG;
	$config_file = "";
	$FIELDS_NOT_IN_CONFIG = array ("MySource_Install_Location", "Root");
	reset($_POST);
	while(list($key,$data) = each($_POST)) {
		if (!in_array($key,$FIELDS_NOT_IN_CONFIG)) {
			if (!is_array($data)) {
				$config_file .= gpc_stripslashes(str_replace("_","",$key)." ".$data."\n");
			} else {
				$config_file .= gpc_stripslashes(str_replace("_","",$key));
				reset($data);
				while(list($subk,$subdata) = each($data)) {
					if (!preg_match("/confirmation/",$subk)) {
						$config_file .= gpc_stripslashes(" ".$subdata);
					}
				}
				$config_file .= "\n";
			}
		}
	}
	reset($DEFAULT_CONFIG);
	while(list($key,$data) = each($DEFAULT_CONFIG)) {
		$config_file .= gpc_stripslashes($key." ".$data."\n");
	}

	$config_path = $location."conf/mysource.conf";
	if (is_file($config_path)) {
		rename($config_path,$location."conf/mysource.conf.old");
	}
	$fp = fopen($config_path,"w");
	fputs($fp,$config_file);
	fclose($fp);
	$location = str_replace("\/\/","\/",$location);

	$apache_config = "<VirtualHost ".$_SERVER['SERVER_ADDR'].">\n";
	$apache_config .= "\tServerAdmin ".$_POST['Web_Master_Email']."\n";
	$apache_config .= "\tDocumentRoot \"".$location."web\"\n";
	$apache_config .= "\tServerName ".$_SERVER['SERVER_NAME']."\n";

	if ($_POST['Statistics'] != 'n') {
		$apache_config .= "\tSetEnv MySource_LogVisitors on\n";
	}

	$apache_config .= "\t<Directory \"".$location."web\">\n";
	$apache_config .= "\t\tAllowOverride All\n";
	$apache_config .= "\t\tOrder allow,deny\n";
	$apache_config .= "\t\tAllow from all\n";
	$apache_config .= "\t</Directory>\n";
	$apache_config .= "\t<Directory \"".$location."squizlib\">\n";
	$apache_config .= "\t\tAllowOverride All\n";
	$apache_config .= "\t\tOrder allow,deny\n";
	$apache_config .= "\t\tAllow from all\n";
	$apache_config .= "\t</Directory>\n";
	$apache_config .= "\t<Directory \"".$location."data/unrestricted\">\n";
	$apache_config .= "\t\tAllowOverride All\n";
	$apache_config .= "\t\tOrder allow,deny\n";
	$apache_config .= "\t\tAllow from all\n";
	$apache_config .= "\t</Directory>\n";
	$apache_config .= "\tAliasMatch \"^(/.*)?/__lib(.*)$\"       \"".$location."web/__lib\$2\"\n";
	$apache_config .= "\tAliasMatch \"^(/.*)?/__squizlib(.*)$\"  \"".$location."squizlib\$2\"\n";
	$apache_config .= "\tAliasMatch \"^(/.*)?/__data(.*)$\"      \"".$location."data/unrestricted\$2\"\n";
	$apache_config .= "\t# Any HTTP request made at this domain followed by '/_edit'\n";
	$apache_config .= "\t# will open the editing interface for that site, page etc.\n";
	$apache_config .= "\tAliasMatch \"^(/.*)?/_edit(.*)$\"       \"".$location."web/edit\$2\"\n";
	$apache_config .= "\t# Any *other* HTTP request made at this domain gets handled\n";
	$apache_config .= "\t# by the MySource web frontend controller script.\n";
	$apache_config .= "\tAliasMatch \"^(/.*)?$\"                 \"".$location."web/index.php\"\n\n";
	$apache_config .= "\t# Example alternative at a particular subdirecory of this domain:\n";
	$apache_config .= "\t#AliasMatch \"^/path/to/mysource/section(/.*)?/__lib(.*)$\"       \"".$location."web/__lib\$2\"\n";
	$apache_config .= "\t#AliasMatch \"^/path/to/mysource/section(/.*)?/__squizlib(.*)$\"  \"".$location."squizlib\$2\"\n";
	$apache_config .= "\t#AliasMatch \"^/path/to/mysource/section(/.*)?/__data(.*)$\"      \"".$location."data/unrestricted\$2\"\n";
	$apache_config .= "\t#AliasMatch \"^/path/to/mysource/section(/.*)?/_edit(.*)$\"       \"".$location."web/edit\$2\"\n";
	$apache_config .= "\t#AliasMatch \"^/path/to/mysource/section(/.*)?$\"                 \"".$location."web/index.php\"\n";
	$apache_config .= "\t</VirtualHost>";
	echo "Configuration generated successfully.<br>";
	echo "Please check & add the following to your apache configuration file, and restart the apache server:<br><br>";
	echo nl2br(htmlspecialchars($apache_config));
}

function check_mysource_location($location) {
	if (!is_dir($location)) {
		return "This base directory doesn't exist<br>";
	}
	if (!is_dir($location."cache")) {
		if (!mkdir($location."cache",0775)) {
			return "Couldn't create the cache directory: " . $php_errormsg . "<br>";
		}
	}
	if (!is_dir($location."data")) {
		if (!mkdir($location."data",0775)) {
			return "Couldn't create the data directory: " . $php_errormsg . "<br>";
		}
	}
	$LINKS_TO_MAKE = array (
		"restricted/web",
		"restricted/site/design",
		"restricted/page",
		"restricted/users",
		"restricted/user",
		"unrestricted/web",
		"unrestricted/site/design",
		"unrestricted/page",
		"unrestricted/users",
		"unrestricted/user",
		"ftp"
	);

	reset($LINKS_TO_MAKE);
	while(list($k,$link) = each($LINKS_TO_MAKE)) {
		make_directory($location."data/".$link);
	}

	$ob_file = $location."squizlib/object/object.inc";
	$db_file = $location."squizlib/db/db.inc";

	if (is_file($ob_file) && is_file($db_file)) {
		include_once($ob_file);
		include_once($db_file);
	} else {
		return "Couldn't find the SquizLib directory.<br>";
	}
	return true;
}

function db_connect($dbase,$user,$host,$pw) {
	$db = new Db();
	$db->error_reporting(0);
	$db->connect($dbase,$user,$pw,$host);
	return $db;
}

function db_check($dbase,$user,$host,$pw) {
	$db = db_connect("mysql",$user,$host,$pw);
	if (!$db->ptr) {
		return $db->error_msg;
	} else {
		$db_check = $db->select("USE $dbase");
		if (!preg_match("/unknown database '$dbase'/i",$db_check)) {
			$db->select("CREATE DATABASE $dbase");
			return true;
		} else {
			return $db->error_msg;
		}
	}
}

function import_sql($db,$array) {
	# this was needed for the NT version, on mine, was very slow.
	set_time_limit(90);
	reset($array);
	while(list($k,$value) = each($array)) {
		if (is_array($value)) {
			import_sql($db,$value);
		}
		if ($value != "" && (!is_array($value))) {
			if (!$fp = fopen($value,"r")) {
				return false;
			}
			$sql = '';

			$db->error_reporting(0);
			$fcontents = file ($value);
			while (list ($line_num, $line) = each ($fcontents)) {
				$sql .= $line;
			}
			$queries = explode(";",$sql);
			while (list ($qid,$query) = each($queries)) {
				$db->select($query);
			}
		}
	}
}

function make_directory($dirname) {
	$subdirs=explode("/",$dirname);
	# element 0 is empty IF it's a location like "/home/mysource/.."
	# If it's something like "c:\mysource" for a windows installation,
	# element 0 is "c:". so we add it anyway since 9/10 times it will be blank.
	reset($subdirs);
	$base = '';
	while(list($k,$subd) = each($subdirs)) {
		if ($k == 1) {
			$base = $subdirs[0] . "/";
		}
		$base .= $subd . "/";
		if (!is_dir($base)) {
			if (!mkdir($base,0775)) {
				echo "Unable to open Directory: $base".__FILE__.__LINE__.": " . $php_errormsg . "<br>";
			}
		}
	}
}

?>
<html>
	<head>
		<title>Configure MySource</title>
		<style>
			p,body,td{
				font-family: verdana,arial;
				font-size: 12px;
			}
		</style>
	</head>
	<body background="" bgcolor="#FFFFFF">

	<?php
	$action = (isset($_GET['action'])) ? $_GET['action'] : '';
	switch ($action) {
		case "configure":
			$error_messages = array();
			reset($_POST);
			while(list($k,$data) = each($_POST)) {
				if (is_array($data)) {
					reset($data);
					while(list($subk,$subdata) = each($data)) {
						$blank_key = $ALLOWED_BLANK[str_replace("_","",$k)];
						if ( ($subdata == "") && (!in_array($subk,$blank_key)) ) {
							$error_messages[] = str_replace("_"," ",$k)." ".str_replace("_"," ",$subk);
						} else {
							if (preg_match("/confirmation/i",$subk)) {
								if ($data['password'] != $data['password_confirmation']) {
									$error_messages[] = "Passwords for ".str_replace("_"," ",$k)." do not match.";
								} # end password check.
							} # end if
						} # end if data blank or data == password.
					} # end while
				} else {
					if ($data == "" && (!in_array($k,$ALLOWED_BLANK))) {
						$error_messages[] = str_replace("_"," ",$k);
					} # end if
				} # end if data not an array
			} # end while
			if ($error_messages[0] != "") {
				$error_message = "You must fill in or check:<br>";
				reset($error_messages);
				while(list($eid,$error) = each($error_messages)) {
					$error_message .= " - ".$error."<br>";
				}
				config_form($error_message);
				exit;
			} else {
				$location = gpc_stripslashes(str_replace("\\","/",$_POST['MySource_Install_Location']));
				# If there are no other errors, we check that the mysource_install_location actually exists, and is available to us..
				if (substr($location,-1) != "/") {
					$location .= "/";
				}
				$check_location = check_mysource_location($location);
				if ($check_location != 1) {
					config_form($check_location);
					exit;
				}

				$error_message = '';

				$web_db = $_POST['Web_Data_base']['db'];
				$web_user = $_POST['Web_Data_base']['user'];
				$web_host = $_POST['Web_Data_base']['host'];
				$web_pw = $_POST['Web_Data_base']['password'];
				$user_db = $_POST['User_Data_base']['db'];
				$user_user = $_POST['User_Data_base']['user'];
				$user_host = $_POST['User_Data_base']['host'];
				$user_pw = $_POST['User_Data_base']['password'];

				$web_db_check = db_check($web_db,$web_user,$web_host,$web_pw);
				if ($web_db_check != 1) {
					$error_message .= "Couldn't create the Web Database.<br>Please check the error message below:<br>";
					$error_message .= "&nbsp;&nbsp;".$web_db_check."<br>";
				} else {
					$WEB_DB = db_connect($web_db,$web_user,$web_host,$web_pw);
					$web_version = $WEB_DB->server_version();
				}

				if ($user_db != $web_db) {
					$user_db_check = db_check($user_db,$user_user,$user_host,$user_pw);
					 if ($user_db_check != 1) {
						$error_message .= "Couldn't create the User Database.<br>Please check the error message below:<br>";
						$error_message .= "&nbsp;&nbsp;".$user_db_check."<br>";
					 } else {
						$USER_DB = db_connect($user_db,$user_user,$user_host,$user_pw);
						$user_version = $USER_DB->server_version();
					 }
				} else {
					$USER_DB = $WEB_DB;
					$user_version = $web_version;
				}

				$web_result  = version_no_compare($web_version, $MYSQL_VERSION);
				$user_result = version_no_compare($user_version, $MYSQL_VERSION);
				if(empty($web_version) || empty($user_version)){
					$connect_error = "";
					if(empty($web_version)) $connect_error .= "<b>Web Database</b>";
					if(empty($web_version) && empty($user_version)) $connect_error .= " or your ";
					if(empty($user_version)) $connect_error .= "<b>User Database</b>";
					?>
					<div style="background:FF9999;">
						Sorry but I could not connect to your <?=$connect_error?>. This can happen because:
						<ul>
							<li>The username and password you supplied are incorrect</li>
							<li>PHP cannot connect to MySQL or</li>
							<li>The version of MySQL you are using is not supported by MySource. We require version <?=$MYSQL_VERSION?> or later</li>
						</ul>
					</div>
					<hr>
					<?
					exit();
				} elseif ($web_result < 0) {
					?>
						Sorry, MySQL isn't recent enough. We require <?php echo $MYSQL_VERSION; ?>. You have <?php echo $web_version; ?>
					<?php
					exit();
				} else {
					if ($user_result < 0) {
						?>
							Sorry, MySQL isn't recent enough. We require <?php echo $MYSQL_VERSION; ?>. You have <?php echo $user_version; ?>
						<?php
						exit();
					}
				}

				if ($error_message) {
					config_form($error_message);
					exit;
				} else {
					# At this point, we don't want the statistics imported. That comes after.
					$web_prune = array();

					$simple_stats = $location . "/xtras/statistics/simple/mysql_web.sql";
					$moderate_stats = $location . "/xtras/statistics/moderate/mysql_web.sql";
					$detailed_stats = $location . "/xtras/statistics/detailed/mysql_web.sql";

					switch ($_POST['StatisticsReporter']) {
						case 'simple':
							$web_prune[] = $moderate_stats;
							$web_prune[] = $detailed_stats;
						break;
						case 'moderate':
							$web_prune[] = $simple_stats;
							$web_prune[] = $detailed_stats;
						break;
						case 'detailed':
							$web_prune[] = $simple_stats;
							$web_prune[] = $moderate_stats;
						break;
						case 'none':
							$web_prune[] = $simple_stats;
							$web_prune[] = $moderate_stats;
							$web_prune[] = $detailed_stats;
						break;
					}

					# Now we import the data, and generate the apache config.
					$sql_files = find_sql($location,"mysql_web.sql", $web_prune);

					import_sql($WEB_DB,$sql_files);
					$sql_files = find_sql($location,"mysql_users.sql");
					import_sql($USER_DB,$sql_files);

					$major_version = explode('.', $user_version);
					if ($major_version[0] > 3) {
						$root_user_sql = "INSERT INTO user (userid, login, password, firstname) VALUES (1, 'root', old_password('".$_POST['Root']['password']."'), 'root')";
					} else {
						$root_user_sql = "INSERT INTO user (userid, login, password, firstname) VALUES (1, 'root', password('".$_POST['Root']['password']."'), 'root')";
					}
					$USER_DB->insert($root_user_sql);

					$sql_files = find_sql($location,"mysql_web_populate.sql");
					import_sql($WEB_DB,$sql_files);
					$sql_files = find_sql($location,"mysql_users_populate.sql");
					import_sql($USER_DB,$sql_files);
					generate_config($location);
				}
			}

		break;
		default:
			config_form();
	} # end switch
	?>

	</body>
</html>
