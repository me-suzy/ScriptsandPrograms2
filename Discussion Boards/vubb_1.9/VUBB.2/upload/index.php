<?php
/*
Copyright 2005 VUBB
*/

// Start Timer
$start_time = explode(' ', microtime());

// If install.php exists
if(@file_exists("install.php")) {
die("<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0;url=./install.php\">");
}

// Start the session!
session_start();

// Get the settings
include('includes/settings.php');

// Bring in header
include('includes/header.php');

// Modules
$modules = array(
'forum' => 'forum',
'viewforum' => 'viewforum',
'viewtopic' => 'viewtopic',
'register' => 'register',
'login' => 'login',
'usercp' => 'usercp',
'newtopic' => 'newtopic',
'newreply' => 'newreply',
'newpoll' => 'newpoll',
'editpost' => 'editpost',
'viewprofile' => 'viewprofile',
'members' => 'members'
);

// Set defualt module if none set
if (!isset($_GET['act']))
{
	$act = 'forum';
}

else
{
	$act = $_GET['act'];
}

// Include the current module
//if (in_array($act, $modules) && file_exists('modules/' . $act . '.php'))
if(file_exists("modules/".$act.".php"))
{
	
	include('modules/' . $act . '.php');
}

else
{
	error($lang['title']['error'],$lang['text']['no_module']);
}

// Get footer
include('includes/footer.php');

// End Timer
$end_time = explode(' ', microtime());
$total_time = round($end_time[1] + $end_time[0] - $start_time[1] - $start_time[0], 3);

echo "<div align='center'>" . $total_time . "</div>";
?>