<?php



//you can modify these if you want
$GLOBALS['increment'] = 15; //the number of records to increment each page in a grid of rows
$GLOBALS['short_increment'] = 10; //the number of records to show on the main overview page
$GLOBALS['forbidden_filetypes'] = array('.pl','.cgi','.htaccess','.php','.inc','.perl','.js','.conf','.cfm','.asp','.aspx','.ini','.exe','.bat','.c','.dot','.xla','.dll','.vxd'); //for uploading files

//don't touch these
$GLOBALS['error'] = array();
$GLOBALS['owner'] = false;

//instantiate global objects
include("s_calendar.php");
include("s_course.php");
include("s_discussion.php");
include("s_filer.php");
include("s_grid.php");
include("s_material.php");
include("s_message.php");
include("s_news.php");
include("s_overview.php");
include("s_page.php");
include("s_project.php");
include("s_topic.php");
include("s_user.php");
include("s_wcdata.php");
include("s_work.php");

?>
