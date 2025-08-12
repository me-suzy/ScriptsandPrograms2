<?
$file_rev="041306";
////////////////////////////////////////////////////////
//                 phpBannerExchange                  //
//                   by: Darkrose                     //
//              (darkrose@eschew.net)                 //
//                                                    //
// You can redistribute this software under the terms //
// of the GNU General Public License as published by  //
// the Free Software Foundation; either version 2 of  //
// the License, or (at your option) any later         //
// version.                                           //
//                                                    //
// You should have received a copy of the GNU General //
// Public License along with this program; if not,    //
// write to the Free Software Foundation, Inc., 59    //
// Temple Place, Suite 330, Boston, MA 02111-1307 USA //
//                                                    //
//     Copyright 2004 by eschew.net Productions.      //
//   Please keep this copyright information intact.   //
////////////////////////////////////////////////////////

include("../config.php");
include("../css.php");
include("../lang/admin.php");
require_once('../lib/template_class.php');

if($use_gzhandler==1){
	ob_start("ob_gzhandler");
}

// Begin loginstuff
if(!$db=@mysql_connect("$dbhost","$dbuser","$dbpass")){
	include("../lang/errors.php");
	$err="1";
	$error.="$LANG_error_header<p>";
	$error.="$LANG_error_mysqlconnect ";
	$error.=mysql_error();
}

@mysql_select_db($dbname,$db);

session_start();
header("Cache-control: private"); //IE 6 Fix 
$session=session_id(); 
$login = $_SESSION['login'];
$pass = $_SESSION['pass'];

$result = mysql_query("select * from banneradmin where adminuser='$login' AND adminpass='$pass'");
$get_userinfo=mysql_fetch_array($result);
$login=$get_userinfo[adminuser];
$pass=$get_userinfo[adminpass];

    if($login=="" AND $pass=="" OR $pass=="" OR $err=="1") {
		include("../lang/errors.php");
		$error.="$LANG_error_header<p>";
		$error.="$LANG_login_error";

		$page = new Page('../template/admin_error.php');
		$page->replace_tags(array(
			'css' => "$css",
			'session' => "$session",
			'baseurl' => "$baseurl",
			'title' => "$exchangename - $LANG_login_error_title",
			'shorttitle' => "$LANG_login_error_title",
			'error' => "$error",
			'menu' => "$menu",
			'footer' => '../footer.php'));
		$page->output();
	session_destroy();

	}else{ 

//fix the pathing output.
$basepath_input=ereg_replace(" ", "", "$_REQUEST[basepath_input]");
$upload_path_input=ereg_replace(" ", "", "$_REQUEST[upload_path_input]");

	$BE_build="013105";
		
		// Write out the config file.
		$config_data = '<?php'."\n\n";
		$config_data .= "//\n// phpBannerExchange auto-generated config file\n// Change this file at your own risk!\n//\n\n";
		$config_data .= "// DO NOT CHANGE THIS OR YOUR HANDS WILL FALL OFF\n";
		$config_data .= "// (and it could screw up future installation processes)\n";
		$config_data .= '$BE_build = "' . $BE_build .'";' . "\n\n";
		$config_data .= '$dbhost = "' . $_REQUEST[dbhost_input] . '";' . "\n";
		$config_data .= '$dbuser = "' . $_REQUEST[dbuser_input] . '";' . "\n";
		$config_data .= '$dbpass = "' . $_REQUEST[dbpass_input] . '";' . "\n";
		$config_data .= '$dbname = "' . $_REQUEST[dbname_input] . '";' . "\n\n";

		$config_data .= '$baseurl = "' . $_REQUEST[baseurl_input] . '";' . "\n";
		$config_data .= '$basepath = "' . $basepath_input . '";' . "\n";
		$config_data .= '$exchangename = "' . $_REQUEST[exchangename_input] . '";' . "\n";
		$config_data .= '$sitename = "' . $_REQUEST[sitename_input] . '";' . "\n";
		$config_data .= '$adminname = "' . $_REQUEST[adminname_input] . '";' . "\n";
		$config_data .= '$ownermail = "' . $_REQUEST[ownermail_input] . '";' . "\n\n";

		$config_data .= '$bannerwidth = "' . $_REQUEST[bannerwidth_input] . '";' . "\n";
		$config_data .= '$bannerheight = "' . $_REQUEST[bannerheight_input] . '";' . "\n";
		$config_data .= '$steexp = "' . $_REQUEST[steexp_input] . '";' . "\n";
		$config_data .= '$banexp = "' . $_REQUEST[banexp_input] . '";' . "\n";
		$config_data .= '$showimage = "' . $_REQUEST[showimage_input] . '";' . "\n";
		$config_data .= '$imagepos = "' . $_REQUEST[imagepos_input] . '";' . "\n";
		$config_data .= '$imageurl = "' . $_REQUEST[imageurl_input] . '";' . "\n";
		$config_data .= '$showtext = "' . $_REQUEST[showtext_input] . '";' . "\n";
		$config_data .= '$exchangetext = "' . $_REQUEST[exchangetext_input] . '";' . "\n";
		$config_data .= '$reqbanapproval = "' . $_REQUEST[reqbanapproval_input] . '";' . "\n";
		$config_data .= '$allow_upload = "' . $_REQUEST[allow_upload_input] . '";' . "\n";
		$config_data .= '$max_filesize = "' . $_REQUEST[max_filesize_input] . '";' . "\n";
		$config_data .= '$upload_path = "' . $upload_path_input . '";' . "\n";
		$config_data .= '$banner_dir_url = "' . $_REQUEST[banner_dir_url_input] . '";' . "\n";
		$config_data .= '$maxbanners  = "' . $_REQUEST[maxbanners_input] . '";' . "\n\n";

		$config_data .= '$anticheat = "' . $_REQUEST[anticheat_input] . '";' . "\n";
		$config_data .= '$expiretime = "' . $_REQUEST[cookielength_input] . '";' . "\n";

		$config_data .= '$referral_program = "' . $_REQUEST[referral_input] . '";' . "\n";
		$config_data .= '$referral_bounty = "' . $_REQUEST[bounty_input] . '";' . "\n";
		$config_data .= '$startcredits = "' . $_REQUEST[startcredits_input] . '";' . "\n";
		$config_data .= '$sellcredits = "' . $_REQUEST[sellcredits_input] . '";' . "\n";
		$config_data .= '$commerce_service = "' . $_REQUEST[service_input] . '";' . "\n\n";

		$config_data .= '$topnum = "' . $_REQUEST[topnum_input] . '";' . "\n";
		$config_data .= '$sendemail = "' . $_REQUEST[sendemail_input] . '";' . "\n";
		$config_data .= '$usemd5 = "' . $_REQUEST[md5_input] . '";' . "\n";
		$config_data .= '$use_gzhandler = "' . $_REQUEST[use_gzhandler_input] . '";' . "\n";
		$config_data .= '$log_clicks = "' . $_REQUEST[log_clicks_input] . '";' . "\n";
		$config_data .= '$use_dbrand = "' . $_REQUEST[use_dbrand_input] . '";' . "\n";
		$config_data .= '$date_format = "' . $_REQUEST[date_format_input] . '";' . "\n\n";
		$config_data .= "// Magic Quotes compatibility.. \n";
		$config_data .= '$exchangename = stripslashes($exchangename);' ."\n";
		$config_data .= '$sitename = stripslashes($sitename);' . "\n\n";
		$config_data .= '?' . '>'; // Done this to prevent highlighting editors getting confused!

	@umask(0111);
	$fp=fopen('../config.php', 'wb');
	$result = fputs($fp, $config_data, strlen($config_data));
	fclose($fp);
header("Location: editvars.php?SID=$session");
}
?>