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

define('IN_PHPBB', true); 
$phpbb_root_path = '.././'; 
include($phpbb_root_path . 'extension.inc'); 
include($phpbb_root_path . 'common.'.$phpEx); 
include($phpbb_root_path . 'admin/page_header_admin.'.$phpEx);
include($phpbb_root_path . 'admin/page_footer_admin.'.$phpEx);

// Above two line edited by abcde

// 
// Start session management 
// 
$userdata = session_pagestart($user_ip, PAGE_PROFILE); 
init_userprefs($userdata); 
// 
// End session management 
//

// set page title 
$page_title = 'TEMPLATE'; 


<br>
<br>
<table class="forumline" width="100%" cellspacing="1" cellpadding="4" border="0">
	<tr>
		<th class="thHead" height="25"><b>Information</b></th>
	</tr>
	<tr> 
		<td class="row1"><table width="100%" cellspacing="0" cellpadding="1" border="0">
			<tr> 
			</tr>
			<tr> 
				<td align="center"><span class="gen">


$uploaddir = '../templates/subSilver/images/';
$uploadfile = $uploaddir . basename($HTTP_POST_FILES['userfile']['name']);

echo '<pre>';
if (move_uploaded_file($HTTP_POST_FILES['userfile']['tmp_name'], $uploadfile)) 
{
   echo ?><p><font face="Verdana" size="2">Logo was uploaded sucessfully.<br>
Click <a href="admin/admin_logo.php">Here</a> to return to Logo Uploader.<br>
Click <a href="admin/index.php?pane=right">Here</a> to return to the Admin Index</font></font></p>

}
 else {
   echo ?><p><font face="Verdana" size="2">The image that you selected is invalid or the field is empty.<br>
Click <a href="admin/admin_logo.php">Here</a> to return to Logo Uploader.<br>
Click <a href="admin/index.php?pane=right">Here</a> to return to the Admin Index</font></p>

}

</table>
</table>
<br clear="all" />
?>