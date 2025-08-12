<?php
ini_set('session.gc_maxlifetime', 43200);
ob_start('ob_gzhandler');
session_start();

include("config.php");
include($config['include_dir']."Smarty.class.php");
include($config['include_dir']."timesheet.class.php");

/* Load custom functions */
include('include/functions.php');

$ts = new Timesheet($config['db_name']);
$X = new Smarty();
$X->template_dir    =  $config['template_dir'];
$X->compile_dir     =  $config['template_dir']."templates_c";
include($config['include_dir'].'smarty_db_handler.php');

$X->assign('config',$config);
$X->assign('version',$version);

if ($_REQUEST['msg'])
{
	list($msg_color,$msg) = split("\|",base64_decodE($_REQUEST[msg]));
	$X->assign('msg',$msg);
	$X->assign('msg_color',$msg_color);
}

@mysql_pconnect($config['db_host'],$config['db_user'],$config['db_pass']);
@mysql_select_db($config['db_name']);
if (mysql_errno())
{
	$_REQUEST['page'] = 'install';
}	

//...... Check installation if we have an install request
if ($_REQUEST['page']	== 'install') $installing = 1;
if ($_REQUEST['action'] == 'install') 
{
	unset($_REQUEST['page']);
	$installing = 1;
}

if ($installing)
{
	$Q="SELECT id FROM users WHERE id=1 LIMIT 1";
	list($has_admin) = @mysql_fetch_row(mysql_query($Q));
	if ($has_admin) $installing = 0;
}

//...... Check for login status
if ( (!isset($_SESSION['id'])) && (!$_REQUEST['action'] == "login") && (!$installing))
{
	$_REQUEST['page'] = "login";
}

//...... Default to index page
if (!isset($_REQUEST['page']) && !isset($_REQUEST['action'])) $_REQUEST['page']='index';

if (isset($_REQUEST['page']))
{
	$X->assign('config',$config);

	//...... Basename keeps us out of path-hack harm
	$thisPage = basename($_REQUEST['page']);

	//...... Mode changes for paths happen in config.php
	if (file_exists($config['page_dir']."$thisPage.php")) 
		include($config['page_dir']."$thisPage.php");

	/**************************/
	/* Template Display Logic */
	/**************************/
	$X->assign('thisPage',$thisPage);

	//...... Adding nohead=1 to request will suppress headers/footers
	if (!isset($_REQUEST['nohead'])) 
	{
		$X->display("header.html");
		include($config['include_dir']."Sajax.php");

		//...... Menu setup is in the page directory, mode dependent
		if (file_exists($config['page_dir']."menu.php") && ($_SESSION['id']))
		{
			//...... Load menu parameters
			include($config['page_dir']."menu.php");

			//...... Set up menu system
			include($config['include_dir']."/menu_builder.php");

			if ($_REQUEST['mode'] == 'admin' && $_SESSION['admin'] || $_REQUEST['mode'] != 'admin')
				$X->display("menu.html");
		}
	}

	//...... Watch this one .. It can lead to "blank.html" pages?
	if (file_exists($config['template_dir'].$thisPage.".html"))
	{	
		$X->display($thisPage.".html");
	}
	else $X->display('blank.html');
	if (!isset($_REQUEST['nohead'])) 
	{
		$X->display("footer.html");
	}
}
else
{
	$thisAction = basename($_REQUEST['action']);

	include($config['action_dir']."$thisAction.php");
	if (isset($redirect_to)) header($redirect_to);
}
?>
