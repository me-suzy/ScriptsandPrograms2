<?php

// get full URL
$_SERVER['FULL_URL'] = 'http';
if(isset($_SERVER['HTTPS']) and $_SERVER['HTTPS']=='on'){$_SERVER['FULL_URL'] .=  's';}
$_SERVER['FULL_URL'] .=  '://';
if(isset($_SERVER['SERVER_PORT']) and $_SERVER['SERVER_PORT']!='80'){$_SERVER['FULL_URL'] .=  $_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].$_SERVER['SCRIPT_NAME'];}
else{
$_SERVER['FULL_URL'] .=  $_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];}
if(isset($_SERVER['QUERY_STRING']) and $_SERVER['QUERY_STRING']>' '){$_SERVER['FULL_URL'] .=  '?'.$_SERVER['QUERY_STRING'];}
// end get full URL

// check login
if ($enable_authentication === 1)
{
	if ( !isset($_SESSION['logged_user_infos_ar']) ) {
		if (!empty($_COOKIE['interface_creator_md5_password']) and !empty($_COOKIE['interface_creator_username'])){
		$function = 'check_login';
		}
		else {
		$function = 'show_login_form';
		}
		header ('Location: '.$site_url.$dadabik_login_file.'?function='.$function.'&go_to=('.rawurlencode($_SERVER['FULL_URL']).')');
		die();
	} // end if
	if ($enable_authentication === 0) {
		// set the username to 'nobody' if the authentication is disabled (useful if there are some ID_user fields)
		$current_user = 'nobody';
		$current_user_is_administrator = 0;
	} // end if
	else {
		// get the current user
		$current_user = $_SESSION['logged_user_infos_ar']['username_user'];
		// if the user type correspond to the administrator type
		if ($_SESSION['logged_user_infos_ar']['user_type_user'] === $users_table_user_type_administrator_value) {
			$current_user_is_administrator = 1;
		} // end if
		else {
			$current_user_is_administrator = 0;
		} // end else
	} // end else
} // end if
// check admin login
if ($enable_admin_authentication === 1 and (isset($admin_check) and $admin_check ===1)) 
	{
		if ( !isset($_SESSION['logged_user_infos_ar']) )
		{
		if (!empty($_COOKIE['interface_creator_md5_password']) and !empty($_COOKIE['interface_creator_username'])){
		$function = 'check_login';
		}
		else {
		$function = 'show_login_form';
		}
		header ('Location: '.$site_url.$dadabik_login_file.'?function='.$function.'&go_to=('.rawurlencode($_SERVER['FULL_URL']).')');
		die();
		}
		else
		{
		$current_user = $_SESSION['logged_user_infos_ar']['username_user'];
			if ($_SESSION['logged_user_infos_ar']['user_type_user'] === $users_table_user_type_administrator_value)
			{
			$current_user_is_administrator = 1;
			}
			else
			{
			$current_user_is_administrator = 0;
			}
		}
	}
?>