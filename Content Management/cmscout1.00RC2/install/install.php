<?php
error_reporting(0);
require_once("../includes/Smarty.class.php");
require_once("../includes/functions.php");

function check_dll($dll)
{
	$suffix = ((defined('PHP_OS')) && (preg_match('#win#i', PHP_OS))) ? 'dll' : 'so';
	return ((@ini_get('enable_dl') || strtolower(@ini_get('enable_dl')) == 'on') && (!@ini_get('safe_mode') || strtolower(@ini_get('safe_mode')) == 'off') && @dl($dll . ".$suffix")) ? true : false;
}



$cms_root = './../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
$tpl = new smarty();
$tpl->template_dir = "../install/";
$installed = 0;
$errors = "";
$gotoplace = "";
$stage = $_POST['stage'];

if($stage == '' || !isset($stage))
{
    $stage = 0;
}
elseif ($stage == 0)
{
    $stage = 1;
}

if(isset($_POST['dldone']) && file_exists($cms_root . 'config.' . $phpEx) && filesize($cms_root . 'config.' . $phpEx) != 0)
{
    $stage = 2;
}
elseif(isset($_POST['dldone']) && (!file_exists($cms_root . 'config.' . $phpEx) || filesize($cms_root . 'config.' . $phpEx) == 0))
{
    $stage = 1;
    echo "<script>alert(\"You need to upload the config.php file to CMScouts directory before you can continue\");</script>";
}

//Check permissions and application versions
$available_dbms = array(
    'mysql'		=> 'mysql',
    'mysql4'	=> 'mysqli');

$php_version = phpversion();
$safemode = false;

if (version_compare($php_version, '4.1.0') < 0)
{
    $php = false;
}
else
{
    // We also give feedback on whether we're running in safe mode
    $php = true;
    if (@ini_get('safe_mode') || strtolower(@ini_get('safe_mode')) == 'on')
    {
        $safemode= true;
    }
}
$tpl->assign("php", $php);
$tpl->assign("safemode", $safemode);
$tpl->assign("php_version", $php_version);

foreach ($available_dbms as $dll)
{
    if (!extension_loaded($dll))
    {
        if (!check_dll($dll))
        {
            $db[$dll] = false;
            continue;
        }
    }
    $db[$dll] = true;
    $passed['db'] = true;
}

// Test for other modules
if (!extension_loaded('gd'))
{
    if (!check_dll('gd'))
    {
        $gd = false;
    }
}
else
{
    $gd = true;
}
$tpl->assign("gd", $gd);
$tpl->assign("mysql3", $db['mysql']);
$tpl->assign("mysql4", $db['mysqli']);

$directories = array('cache/', 'photos/', 'downloads/', 'templates_c/', 'avatars/', 'images/');

umask(0);

$passed['files'] = true;
foreach ($directories as $dir)
{
    $temp = explode('/', $dir);
    $itemname = $temp[0];
    $write[$itemname] = $exists[$itemname] = false;
    if (is_dir($cms_root . $dir))
    {
        $exists[$itemname] = true;
        if (is_writeable($cms_root . $dir))
        {
            $write[$itemname] = true;
        }
        else
        {
            $write[$itemname] = (@chmod($cms_root . $dir, 0777)) ? true : false;
        }
    }
    else
    {
        $write[$itemname] = $exists[$itemname] = (@mkdir($cms_root . $dir, 0777)) ? true : false;
    }
    $passed['files'] = ($exists[$itemname] && $write[$itemname] && $passed['files']) ? true : false;
}

// config.php ... let's just warn the user it's not writeable
$dir = 'config.'.$phpEx;
$write['config'] = $exists['config'] = true;
if (filesize($cms_root . $dir) != 0 && $stage != 2 && is_readable($cms_root . $dir))
{
    echo "<script>alert(\"The configuration file already exists. You need to delete it (Or empty it) before attempting to installing CMScout\"); window.location='$cms_root'</script>";
}
if (file_exists($cms_root . $dir))
{
    if (!is_writeable($cms_root . $dir))
    {
        $write['config'] = false;
    }
}
else
{
    $temp = fopen($cms_root . $dir,"w");
    fclose($temp);
    $write['config'] = (@chmod($cms_root . $dir, 0777)) ? true : false;
    $exists['config'] = file_exists($cms_root . $dir);
}

$tpl->assign("filesok", $passed['files']);
$tpl->assign("write", $write);
$tpl->assign("exists", $exists);


if (!empty($_SERVER['SERVER_NAME']) || !empty($_ENV['SERVER_NAME']))
{
    $server_name = (!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : $_ENV['SERVER_NAME'];
}
else if (!empty($_SERVER['HTTP_HOST']) || !empty($_ENV['HTTP_HOST']))
{
    $server_name = (!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : $_ENV['HTTP_HOST'];
}
else
{
    $server_name = '';
}

if (!empty($_SERVER['SERVER_PORT']) || !empty($_ENV['SERVER_PORT']))
{
    $server_port = (!empty($_SERVER['SERVER_PORT'])) ? $_SERVER['SERVER_PORT'] : $_ENV['SERVER_PORT'];
}
else
{
    $server_port = '80';
}

$script_path = preg_replace('#install\/install\.' . $phpEx . '#i', '', $_SERVER['PHP_SELF']);

$tpl->assign("cmscoutaddress", $server_name.$script_path);
 

if($stage == 1)
{
    $allok = true;
    
    $database = isset($_POST['database']) ? $_POST['database'] : '';
    $admin = isset($_POST['admin']) ? $_POST['admin'] : '';
    $config = isset($_POST['config']) ? $_POST['config'] : '';
    
    if(empty($database) && empty($admin) && empty($config))
    {
        if ($passed['files'] == false)
        {
            $errors .= "Not all files and directories are writable. Please check which ones are giving the problem and fix it.";
            $allok = false; 
            if ($gotoplace == "")
                $gotoplace = "chmoding";
        }
        if ($php == false)
        {
            $errors .= "Your php version is incorrect. Please ask your service provider to upgrade your php version to at least 4.1.0";
            $allok = false; 
            if ($gotoplace == "")
                $gotoplace = "chmoding";
        }
        if ($db['mysql']==false && $db['mysqli'] == false)
        {
            $errors .= "You do not seem to have MySQL installed, please ask your service provider to install it for you";
            $allok = false; 
            if ($gotoplace == "")
                $gotoplace = "chmoding";
        }
        
        $database['hostname'] = $_POST['dbhostname'];
        $database['name'] = $_POST['databasename'];
        $database['username']= $_POST['databaseusername'];
        $database['password'] = $_POST['databasepassword'];
        $database['port'] = $_POST['dbport'];
        $database['prefix'] = $_POST['dbprefix'];
        if ($database['hostname'] == "")
        {
            $allok = false; 
            $errors .= "You need to supply a database hostname<br />";
            if ($gotoplace == "")
                $gotoplace = "database";
        }
        if ($database['name'] == "")
        {
            $allok = false; 
            $errors .= "You need to supply a database name<br />";
            if ($gotoplace == "")
                $gotoplace = "database";
        }
        if ($database['username'] == "")
        {
            $allok = false; 
            $errors .= "You need to supply a database username<br />";
            if ($gotoplace == "")
                $gotoplace = "database";
        }
        if ($database['prefix'] == "")
        {
            $allok = false; 
            $errors .= "You need to supply a table prefix<br />";
            if ($gotoplace == "")
                $gotoplace = "database";
        }
        if ($database['port'] == "" || $database['port'] == 0)
        {
            $dbport = 3306;
        } 
        
        $admin['name'] = $_POST['adminusername'];
        $admin['password'] = $_POST['adminpassword'];
        $admin['repass'] = $_POST['adminrepass'];
        $admin['email'] = $_POST['adminemail'];
        $config['webemail'] = $_POST['webemail'];
        if ($admin['name'] == "")
        {
            $allok = false; 
            $errors .= "You need to supply a administrator username<br />";
            if ($gotoplace == "")
                $gotoplace = "admin";
        }
        if ($admin['password'] == "")
        {
            $allok = false; 
            $errors .= "You need to supply a administrator password<br />";
            if ($gotoplace == "")
                $gotoplace = "admin";
        }
        elseif ($admin['password'] != $admin['repass'])
        {
            $allok = false; 
            $errors .= "Passwords do not match<br />";
            if ($gotoplace == "")
                $gotoplace = "admin";
        }
        if ($admin['email'] == "")
        {
            $allok = false; 
            $errors .= "You need to supply a administrator email address<br />";
            if ($gotoplace == "")
                $gotoplace = "admin";
        }
    
        if ($config['webemail'] == "")
        {
            $allok = false; 
            $errors .= "You need to supply a website email address<br />";
            if ($gotoplace == "")
                $gotoplace = "admin";
        }

        
        $config['address'] = $_POST['cmscoutaddress'];
        $config['troopname'] = $_POST['troopname'];
        $config['troopdesc'] = $_POST['troopslogon'];
        $config['sample'] = $_POST['sample'];
        $config['timezone'] = $_POST['zone'];
        if ($config['address'] == "" || $config['address'] == "http://")
        {
            $allok = false; 
            $errors .= "You need to supply the address that Scout Nuke is to be installed under<br />";
            if ($gotoplace == "")
                $gotoplace = "config";
        }
        if ($config['troopname'] == "")
        {
            $allok = false; 
            $errors .= "You need to supply a troop name<br />";
            if ($gotoplace == "")
                $gotoplace = "config";
        }
        if ($config['troopdesc'] == "")
        {
            $allok = false; 
            $errors .= "You need to supply a troop slogan/description<br />";
            if ($gotoplace == "")
                $gotoplace = "config";
        }
        
        
    
            if ($allok)
            {
                $dbconnection = mysql_connect("{$database['hostname']}:{$database['port']}", $database['username'], $database['password']);
                if (mysql_error() || !isset($dbconnection) || empty($dbconnection))
                {
                    $errors .= "Something is incorrect with your database settings, please check them and make sure that they are correct<br />";
                    $allok = false; 
                    if ($gotoplace == "")
                        $gotoplace = "database";
                }
                else
                {
                    $selectedb = mysql_select_db($database['name']);
                    if (mysql_error() || !isset($selectedb) || empty($selectedb))
                    {
                        $errors .= "The database that you specified does not exist. Please ensure that the database does exist.<br />";
                        $allok = false; 
                        if ($gotoplace == "")
                            $gotoplace = "database";
                    }
                }
            }
            
            $licenseagreement = $_POST['licenseagreement'];
            if ($licenseagreement == 0)
            {
                $errors .= "You need to accept the license agreement to install Scout Nuke<br />";
                $allok = false; 
                if ($gotoplace == "")
                    $gotoplace = "license";
            }
    }
    else
    {
        $database = unserialize(stripslashes(html_entity_decode($database)));        
        $downloads = unserialize(stripslashes(html_entity_decode($downloads)));
        $admin = unserialize(stripslashes(html_entity_decode($admin)));
        $config = unserialize(stripslashes(html_entity_decode($config)));
    }
    
    if($allok)
    {
        $direct = false;
        $config_data =
            "<?php
            /********************************************************************
               Auto Generated config file for CMScout
               DO NOT CHANGE THIS FILE!!
            *********************************************************************/
               \$dbhost = \"{$database['hostname']}\";
               \$dbusername = \"{$database['username']}\";
               \$dbpassword = \"{$database['password']}\";
               \$dbport = \"{$database['port']}\";
               \$dbname = \"{$database['name']}\";
               \$dbprefix = \"{$database['prefix']}\";
            \$phpex = \".$phpEx\";
            ?>";
            
            // Attempt to write out the config directly ...
            if (filesize($cms_root . 'config.' . $phpEx) == 0 && is_writeable($cms_root . 'config.' . $phpEx))
            {
                // Lets jump to the DB setup stage ... if nothing goes wrong below
                $stage = 2;
        
                if (!($fp = @fopen($cms_root . 'config.'.$phpEx, 'w')))
                {
                    // Something went wrong ... so let's try another method
                    $stage = 1;
                }
        
                if (!(@fwrite($fp, $config_data)))
                {
                    // Something went wrong ... so let's try another method
                    $stage = 1;
                }
                else
                {
                    $direct = true;
                    $stage = 2;
                }
                @fclose($fp);
            }

        // We couldn't write it directly so we'll give the user three alternatives
        if ($stage == 1)
        {          
            $ignore_ftp = false;
            // User is trying to upload via FTP ... so let's process it
            if (isset($_POST['sendftp']))
            {
                $ftp['user'] = $_POST['ftp_user'];
                $ftp['pass'] = $_POST['ftp_pass'];
                $ftp['dir'] = $_POST['ftp_dir'];
                if (($conn_id = @ftp_connect('localhost')))
                {
                    if (@ftp_login($conn_id, $ftp['user'], $ftp['pass']))
                    {
                        // Write out a temp file ... if safe mode is on we'll write it to our
                        // local cache/tmp directory
                        $tmp_path = (!@ini_get('safe_mode')) ? false : $cms_root . 'cache/';
                        $filename = tempnam($tmp_path, uniqid(rand()) . 'cfg');
    
                        $fp = @fopen($filename, 'w');
                        @fwrite($fp, $config_data);
                        @fclose($fp);
    
                        if (@ftp_chdir($conn_id, $ftp['dir']))
                        {
                            // So far, so good so now we'll try and upload the file. If it
                            // works we'll jump to stage 3, else we'll fall back again
                            if (@ftp_put($conn_id, 'config.' . $phpEx, $filename, FTP_ASCII))
                            {
                                $stage = 2;
                            }
                            else
                            {
                                // Since we couldn't put the file something is fundamentally wrong, e.g.
                                // the file is owned by a different user, etc. We'll give up trying
                                // FTP at this point
                                $ignore_ftp = true;
                            }
                        }
                        else
                        {
                            $ftperror = "Could not change to the directory. Please check that the path is correct<br />";
                        }
    
                        // Remove the temporary file now
                        @unlink($filename);
                    }
                    else
                    {
                        $ftperror = "There is something wrong with your ftp username or password. Please check that they are correct";
                    }
                    @ftp_quit($conn_id);
                }
            }
        }
        
        if($stage == 1)
        {
        			// Can we ftp? If we can then let's offer that option on top of download
			// We first see if the relevant extension is loaded and then whether a server is 
			// listening on the ftp port
			if (extension_loaded('ftp') && ($fsock = @fsockopen('localhost', 21, $errno, $errstr, 1)) && !$ignore_ftp)
			{
				@fclose($fsock);
    			if ($ftperror != "")
				{
                    $tpl->assign("ftperror", $ftperror);
                    $tpl->assign("ftp", $ftp);
				}
                $tpl->assign("ftpok", 1);
			}
            $tpl->assign("database", htmlentities(serialize($database)));
            $tpl->assign("admin", htmlentities(serialize($admin)));
            $tpl->assign("config", htmlentities(serialize($config)));
        }
    }
    else
    {
        $stage = 0;
        $tpl->assign("database", $database);
        $tpl->assign("admin", $admin);
        $tpl->assign("config", $config);
        $tpl->assign("licenseagreement", $licenseagreement);
    }
}

if($stage == 2)
{
    
   $sql = array();
    
    if ($direct == false)
    {
        $database = $_POST['database'];
        $admin =  $_POST['admin'];
        $config = $_POST['config'];
    
        if((isset($database) && $database != "") && (isset($admin) && $admin != "") && (isset($config) && $config != ""))
        {
            $database = unserialize(stripslashes(html_entity_decode($database)));
            $downloads = unserialize(stripslashes(html_entity_decode($downloads)));
            $admin = unserialize(stripslashes(html_entity_decode($admin)));
            $config = unserialize(stripslashes(html_entity_decode($config)));
        }
    }
    
    $address = $config['address'];
    $admin['name'] = safesql($admin['name'], "text");
    $admin['password'] = safesql(md5($admin['password']), "text");
    $admin['email'] = safesql($admin['email'], "text");
    $config['webemail'] = safesql($config['webemail'], "text");
    $config['address'] = safesql($config['address'], "text");
    $config['troopname'] = safesql($config['troopname'], "text");
    $config['troopdesc'] = safesql($config['troopdesc'], "text");
    $config['numnotice'] = safesql($config['numnotice'], "int");
    $config['numphoto'] = safesql($config['numphoto'], "int");
    $config['timezone'] = safesql($config['timezone'], "int");
    
    
    $database['prefix'] = trim($database['prefix']);
    
    $dbconnection = mysql_connect("{$database['hostname']}:{$database['port']}", $database['username'], $database['password']);
    $selectedb = mysql_select_db($database['name']);

    require("sqlschema.php");
    require("data.php");
  
    if ($config['sample'] == 2)
    {
        require("sampledata.php");
    }
    
    $numsql = count($sql);
    
    $errors = "";
    $isok = true;
    for($i=0;$i<$numsql;$i++)
    {
        $st = strip_tags($sql[$i]);
        $temp = mysql_query($sql[$i]) or die("Error with SQL statement $i $st.<br />Error was: " . mysql_error());
    }
    
    $stage = 3;
    $tpl->assign("cmscoutaddress", $address);
}



$tpl->assign("stage", $stage);
$tpl->assign("errors", $errors);
$tpl->assign("gotoplace", $gotoplace);
$tpl->assign("installed", $installed);
$tpl->display("install.tpl");
?>
