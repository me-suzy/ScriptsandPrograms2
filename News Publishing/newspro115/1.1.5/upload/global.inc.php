<?php
/*
////////////////////////////////////////////////
//             Utopia Software                //
//      http://www.utopiasoftware.net         //
//             Utopia News Pro                //
////////////////////////////////////////////////
*/

/***************************************************************
   Settings
***************************************************************/

$version = '1.1.5';

error_reporting (E_ALL & ~E_NOTICE); // <-- production level error_reporting
// error_reporting (E_ALL); // <-- development level error_reporting

/***************************************************************
   Handle Magic Quotes
***************************************************************/
if (get_magic_quotes_gpc())
{
	function exec_gpc_stripslashes(&$arr)
	{
		if (is_array($arr))
		{
			foreach ($arr AS $_arrykey => $_arryval)
			{
				if (is_string($_arryval))
				{
					$arr["$_arrykey"] = stripslashes($_arryval);
				}
				elseif (is_array($_arryval))
				{
					$arr["$_arrykey"] = exec_gpc_stripslashes($_arryval);
				}
			}
		}
		return $arr;
	}

	$_GET = exec_gpc_stripslashes($_GET);
	$_POST = exec_gpc_stripslashes($_POST);
	$_COOKIE = exec_gpc_stripslashes($_COOKIE);
	if (is_array($_FILES))
	{
		foreach ($_FILES AS $key => $val)
		{
			$_FILES[$key]['tmp_name'] = str_replace('\\', '\\\\', $val['tmp_name']);
		}
	}
	$_FILES = exec_gpc_stripslashes($_FILES);
}
set_magic_quotes_runtime(0);

/***************************************************************
   Trim Default Action
***************************************************************/
if (isset($_POST['action']))
{
	$_POST['action'] = trim($_POST['action']);
}

/***************************************************************
   Set Developer Build
***************************************************************/

define('DEV_BUILD', false);
/*
 * DEV_BUILD
 * Default: define("DEV_BUILD", false);
 * Set above value to 'true' (without quotes) to enable certain
 * developer-only features:
 * query count, add custom templates
 */

/***************************************************************
   Load Database Driver
***************************************************************/
require('mysql.inc.php');
$DB = new DB_Driver;
$DB->hostname = $config['hostname'];
$DB->database = $config['database'];
$DB->user = $config['user'];
$DB->password = $config['password'];
$DB->persistent = $config['persistent'];

$DB->connect();
$DB->selectdb();
$DB->password = '';
$config['password'] = ''; // Might as well, right?

/***************************************************************
   Miscellaneous
***************************************************************/

unset($templatecache);
$templatecache = array();

define('TIMENOW', time());

/***************************************************************
   Define Global Phrases
***************************************************************/
$gp_invalidrequest = 'You have made an invalid request for this page.';
$gp_invalidemail = 'You have entered an invalid email address.';
$gp_allfields = 'All fields are required. Please go back and try again.';
$gp_permserror = 'You do not have permission to access this page.';
$gp_invalidpassword = 'You have entered an invalid username and password combination. Please go back and try again.';
?>