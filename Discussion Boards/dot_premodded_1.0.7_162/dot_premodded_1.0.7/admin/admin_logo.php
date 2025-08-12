<?php 
/*************************************************************************** 
 *                              file_name.php 
 *                            ------------------- 
 *   begin                : <DATE> 
 *   copyright            : (C) 2004 <YOUR NAME!> 
 *   email                :<E-MAIL or, WEBSITE!> 
 * 
 * 
 * 
 ***************************************************************************/ 

define('IN_PHPBB', 1); 

if( !empty($setmodules) ) 
{ 
   $file = basename(__FILE__); 
   $module['General']['Logo'] = "$file"; 
   return; 
} 

// 
// Let's set the root dir for phpBB - edited by abcde
// 
$phpbb_root_path = "./../"; 
require($phpbb_root_path . 'extension.inc'); 
require('./pagestart.' . $phpEx); 
include($phpbb_root_path . 'includes/functions_selects.'.$phpEx); 
include($phpbb_root_path . 'admin/page_header_admin.'.$phpEx); 
include($phpbb_root_path . 'admin/page_footer_admin.'.$phpEx); 


// standard session management 
$userdata = session_pagestart($user_ip, PAGE_TEMPLATE); 
init_userprefs($userdata); 

// set page title 
$page_title = 'TEMPLATE'; 

// assign template 
$template->set_filenames(array( 
        'body' => 'logo_body.tpl') 
); 


$template->pparse('body'); 

?>