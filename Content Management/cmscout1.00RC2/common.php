<?php
/**************************************************************************
    FILENAME        :   common.php
    PURPOSE OF FILE :   Includes all required files, configures templates, etc.
    LAST UPDATED    :   08 June 2005
    COPYRIGHT       :   © 2005 CMScout Group
    WWW             :   www.cmscout.za.org
    LICENSE         :   GPL vs2.0
    
    

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
**************************************************************************/
?>
<?php
error_reporting(0);
define("SCOUT_NUKE", 1);
if (file_exists("{$bit}install/install.php") && !file_exists("{$bit}config.php"))
{
    header("Location: {$bit}install/install.php");
}
elseif (file_exists("{$bit}install/install.php") && file_exists("{$bit}config.php"))
{
    include("{$bit}config.php");
    if (isset($dbhost))
    {
        die("Please ensure that the install directory has been deleted before continuing");
    }
    else
    {
        header("Location: {$bit}install/install.php");
    }
}
elseif (!file_exists("{$bit}install/install.php") && file_exists("{$bit}config.php"))
{
    require_once ("{$bit}includes/Smarty.class.php");
    require_once ("{$bit}includes/authorization.php");
    require_once ("{$bit}config.php");
    require_once ("{$bit}includes/db.php");
    require_once ("{$bit}includes/functions.php");
}
else
{
    die("The configuration file is missing. Normally Scout Nuke would try to install itself then, but it appears that the install file is also missing. Please fix this by either placing the correct configuration file or the install file where it is ment to be.");
}

/********************************************Start Smarty config***************************************************/
class Smarty_Site extends Smarty {
   function Smarty_Site($themedir)
   {
   
        // Class Constructor. These automatically get set with each new instance.

        $this->Smarty();

        $this->template_dir = 'templates/IreneBlue';
        $this->compile_dir = 'templates_c/';
        $this->config_dir = 'configs/';
        $this->cache_dir = 'cache/';
		$this->compile_check = true;
        
        $this->caching = false;
		$this->force_compile = true;
   }

}
/********************************************End Smarty config***************************************************/
$tpl = new Smarty_Site("");
$data = new database($dbname, $dbhost, $dbusername, $dbpassword, $dbprefix);
$Auth = new auth();

$debug = '';
$config = read_config();

if ($config['softdebug'] == 1)
{
    $starttime = microtime();
    $data->reset_counter();
}

$cookievalue = md5(time() . 'um');
$expire = time() + $config['session_length'];

$tpl->load_filter('output','gzip');

$check = $Auth->page_check();

$timestamp = time();
?>