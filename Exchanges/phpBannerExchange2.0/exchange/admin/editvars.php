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
	$basepath = realpath("../");

	if($showimage=='Y'){
		$showimage_code.="<input type=\"radio\" value=\"Y\" checked name=\"showimage_input\">$LANG_yes <input type=\"radio\" name=\"showimage_input\" value=\"N\">$LANG_no";
	}else{
		 $showimage_code.="<input type=\"radio\" value=\"Y\" name=\"showimage_input\">$LANG_yes <input type=\"radio\" checked name=\"showimage_input\" value=\"N\">$LANG_no";
	}

	if($imagepos=='L'){ 
		$imagepos_code.="<input type=\"radio\" value=\"L\" checked name=\"imagepos_input\" class=\"formbox\" >$LANG_left <input type=\"radio\" value=\"R\" name=\"imagepos_input\" class=\"formbox\" >$LANG_right <input type=\"radio\" value=\"T\" name=\"imagepos_input\" class=\"formbox\" >$LANG_top <input type=\"radio\" value=\"B\" name=\"imagepos_input\" class=\"formbox\">$LANG_bottom";
	}
	
	if($imagepos=='R'){ 
		$imagepos_code.="<input type=\"radio\" value=\"L\" name=\"imagepos_input\" class=\"formbox\" >$LANG_left <input type=\"radio\" value=\"R\" checked name=\"imagepos_input\" class=\"formbox\" >$LANG_right <input type=\"radio\" value=\"T\" name=\"imagepos_input\" class=\"formbox\" >$LANG_top <input type=\"radio\" value=\"B\" name=\"imagepos_input\" class=\"formbox\">$LANG_bottom";
	}

	if($imagepos=='T'){ 
		$imagepos_code.="<input type=\"radio\" value=\"L\" name=\"imagepos_input\" class=\"formbox\" >$LANG_left <input type=\"radio\" value=\"R\" name=\"imagepos_input\" class=\"formbox\" >$LANG_right <input type=\"radio\" value=\"T\" checked name=\"imagepos_input\" class=\"formbox\" >$LANG_top <input type=\"radio\" value=\"B\" name=\"imagepos_input\" class=\"formbox\">$LANG_bottom";
	}

	if($imagepos=='B'){ 
		$imagepos_code.="<input type=\"radio\" value=\"L\" name=\"imagepos_input\" class=\"formbox\" >$LANG_left <input type=\"radio\" value=\"R\" name=\"imagepos_input\" class=\"formbox\" >$LANG_right <input type=\"radio\" value=\"T\" name=\"imagepos_input\" class=\"formbox\" >$LANG_top <input type=\"radio\" value=\"B\" checked name=\"imagepos_input\" class=\"formbox\">$LANG_bottom";
	}


		if($showtext=='Y'){
		$showtext_code.="<input type=\"radio\" value=\"Y\" checked name=\"showtext_input\">$LANG_yes <input type=\"radio\" name=\"showtext_input\" value=\"N\">$LANG_no";
	}else{
		 $showtext_code.="<input type=\"radio\" value=\"Y\" name=\"showtext_input\">$LANG_yes <input type=\"radio\" checked name=\"showtext_input\" value=\"N\">$LANG_no";
	}

	if($reqbanapproval=='Y'){
		$reqbanapproval_code.="<input type=\"radio\" value=\"Y\" checked name=\"reqbanapproval_input\">$LANG_yes <input type=\"radio\" name=\"reqbanapproval_input\" value=\"N\">$LANG_no";
	}else{
		 $reqbanapproval_code.="<input type=\"radio\" value=\"Y\" name=\"reqbanapproval_input\">$LANG_yes <input type=\"radio\" checked name=\"reqbanapproval_input\" value=\"N\">$LANG_no";
	}

		if($allow_upload=='Y'){ 
			$upload_code.="<input type=\"radio\" value=\"Y\" checked name=\"allow_upload_input\">$LANG_yes <input type=\"radio\" name=\"allow_upload_input\" value=\"N\">$LANG_no</td>";
		}else{
			$upload_code.="<input type=\"radio\" value=\"Y\" name=\"allow_upload_input\">$LANG_yes <input type=\"radio\" checked name=\"allow_upload_input\" value=\"N\">$LANG_no</td>";
	}

if($anticheat==''){
			$anticheat_code="<option selected value=\"\">None</option><option value=\"cookies\">Cookies</option><option value=\"DB\">Database</option>";
		}
if($anticheat=="cookies"){
			$anticheat_code="<option selected value=\"cookies\">Cookies</option><option value=\"\">None</option><option value=\"DB\">Database</option>";
}
if($anticheat=="DB"){
			$anticheat_code="<option selected value=\"DB\">Database</option><option value=\"cookies\">Cookies</option><option value=\"\">None</option>";
}

		if($referral_program=='Y'){
			$referral_code="<input type=\"radio\" value=\"Y\" checked name=\"referral_input\">$LANG_yes <input type=\"radio\" name=\"referral_input\" value=\"N\">$LANG_no</td>";
		}else{
			$referral_code="<input type=\"radio\" value=\"Y\" name=\"referral_input\">$LANG_yes <input type=\"radio\" checked name=\"referral_input\" value=\"N\">$LANG_no</td>";
		}



if($sellcredits=='1'){
	$sellcredits_code="<input type=\"radio\" value=\"1\" checked name=\"sellcredits_input\">$LANG_yes <input type=\"radio\" name=\"sellcredits_input\" value=\"0\">$LANG_no";
}else{
	$sellcredits_code="<input type=\"radio\" value=\"1\" name=\"sellcredits_input\">$LANG_yes <input type=\"radio\" checked name=\"sellcredits_input\" value=\"0\">$LANG_no";
}

if($sendemail=='Y'){
	$sendemail_code="<input type=\"radio\" value=\"Y\" checked name=\"sendemail_input\">$LANG_yes <input type=\"radio\" name=\"sendemail_input\" value=\"N\">$LANG_no";
}else{
	$sendemail_code="<input type=\"radio\" value=\"Y\" name=\"sendemail_input\">$LANG_yes <input type=\"radio\" checked name=\"sendemail_input\" value=\"N\">$LANG_no";
}

if($usemd5=='Y'){
	$usemd5_code="<input type=\"radio\" value=\"Y\" checked name=\"md5_input\">$LANG_yes <input type=\"radio\" name=\"md5_input\" value=\"N\">$LANG_no";
}else{
	$usemd5_code="<input type=\"radio\" value=\"Y\" name=\"md5_input\">$LANG_yes <input type=\"radio\" checked name=\"md5_input\" value=\"N\">$LANG_no";
}

if($use_gzhandler=='1'){
	$usegzhandler_code="<input type=\"radio\" value=\"1\" checked name=\"use_gzhandler_input\">$LANG_yes <input type=\"radio\" name=\"use_gzhandler_input\" value=\"0\">$LANG_no";
}else{
	$usegzhandler_code="<input type=\"radio\" value=\"1\" name=\"use_gzhandler_input\">$LANG_yes <input type=\"radio\" checked name=\"use_gzhandler_input\" value=\"0\">$LANG_no";
}

if($log_clicks=='Y'){
	$logclicks_code="<input type=\"radio\" value=\"Y\" checked name=\"log_clicks_input\">$LANG_yes <input type=\"radio\" name=\"log_clicks_input\" value=\"N\">$LANG_no";
}else{
	$logclicks_code="<input type=\"radio\" value=\"Y\" name=\"log_clicks_input\">$LANG_yes <input type=\"radio\" checked name=\"log_clicks_input\" value=\"N\">$LANG_no";
}

if($use_dbrand=='1'){
	$use_dbrand_code="<input type=\"radio\" value=\"1\" checked name=\"use_dbrand_input\">$LANG_yes <input type=\"radio\" name=\"use_dbrand_input\" value=\"0\">$LANG_no";
}else{
	$use_dbrand_code="<input type=\"radio\" value=\"1\" name=\"use_dbrand_input\">$LANG_yes <input type=\"radio\" checked name=\"use_dbrand_input\" value=\"0\">$LANG_no";
}

if($date_format==''){
	$dateformatcode="<option selected value=\"\"></option><option value=\"0\">mm/dd/yy</option><option value=\"1\">dd/mm/yy</option>";
}
if($date_format=='0'){
	$dateformatcode="<option selected value=\"0\">mm/dd/yy</option><option value=\"1\">dd/mm/yy</option>";
}
if($date_format=='1'){
	$dateformatcode="<option selected value=\"1\">dd/mm/yy</option><option value=\"0\">mm/dd/yy</option>";
}

//adding a space so it will show up right on the form..
$upload_path="$upload_path ";
$basepath="$basepath ";

		$page = new Page('../template/admin_editvars.php');
		$page->replace_tags(array(
			'css' => "$css",
			'session' => "$session",
			'base_url' => "$baseurl",
			'msg' => "$LANG_varedit_dirs",
			'dbhead' => "$LANG_dbinstall_head",
			'dbhost' => "$LANG_varedit_dbhost",
			'dbhost_data' => "$dbhost",
			'dblogin' => "$LANG_varedit_dblogin",
			'dbuser_data' => "$dbuser",
			'dbpass' => "$LANG_varedit_dbpass",
			'dbpass_data' => "$dbpass",
			'dbname' => "$LANG_varedit_dbname",
			'dbname_data' => "$dbname",
			'pathing_head' => "$LANG_pathing_head",
			'baseurl' => "$LANG_varedit_baseurl",
			'baseurl_data' => "$baseurl",
			'baseurl_note' => "$LANG_varedit_baseurl_note",
			'basepath' => "$LANG_varedit_basepath",
			'basepath_data' => "$basepath",
			'exchangename' => "$LANG_varedit_exchangename",
			'exchangename_data' => "$exchangename",
			'sitename' => "$LANG_varedit_sitename",
			'sitename_data' => "$sitename",
			'adminname' => "$LANG_varedit_adminname",
			'adminname_data' => "$adminname",
			'adminemail' => "$LANG_varedit_adminemail",
			'ownermail_data' => "$ownermail",
			'bannershead' => "$LANG_banners_head",
			'width' => "$LANG_varedit_width",
			'width_data' => "$bannerwidth",
			'height' => "$LANG_varedit_height",
			'height_data' => "$bannerheight",
			'pixels' => "$LANG_varedit_pixels",
			'defrat' => "$LANG_varedit_defrat",
			'steexp_data' => "$steexp",
			'banexp_data' => "$banexp",
			'defrat_msg' => "$LANG_varedit_defrat_msg",
			'showimage' => "$LANG_varedit_showimage",
			'showimage_code' => "$showimage_code",
			'imagepos_code' => "$imagepos_code",
			'imageurl' => "$LANG_varedit_imageurl",
			'imageurl_msg' => "$LANG_varedit_imageurl_msg",
			'imageurl_data' => "$imageurl",
			'showtext' => "$LANG_varedit_showtext",
			'showtext_value' => "$showtext_code",
			'exchangetext' => "$LANG_varedit_exchangetext",
			'exchangetext_value' => "$exchangetext",
			'reqbanapproval' => "$LANG_varedit_reqapproval",
			'reqbanapproval_data' => "$reqbanapproval_code",
			'upload' => "$LANG_varedit_upload",
			'upload_data' => "$upload_code",
			'maxsize' => "$LANG_varedit_maxsize",
			'max_filesize_data' => "$max_filesize",
			'upload_path' => "$LANG_varedit_uploadpath",
			'upload_path_data' => "$upload_path",
			'banner_dir_url' => "$LANG_varedit_upurl",
			'banner_dir_url_data' => "$banner_dir_url",
			'maxbanners' => "$LANG_varedit_maxbanners",
			'maxbanners_data' => "$maxbanners",
			'anticheat_head' => "$LANG_anticheat",
			'anticheat' => "$LANG_varedit_anticheat",
			'anticheat_code' => "$anticheat_code",
			'expiretime' => "$LANG_varedit_duration",
			'expiretime_data' => "$expiretime",
			'expiretime_msg' => "$LANG_varedit_duration_msg",
			'refncred_head' => "$LANG_referral_credits",
			'referral' => "$LANG_varedit_referral",
			'referral_code' => "$referral_code",
			'referral_bounty' => "$LANG_varedit_bounty",
			'referral_bounty_data' => "$referral_bounty",
			'startcredits' => "$LANG_varedit_startcred",
			'startcredits_data' => "$startcredits",
			'sellcredits' => "$LANG_varedit_sellcredits",
			'sellcredits_code' => "$sellcredits_code",
			'misc_head' => "$LANG_misc",
			'topnum' => "$LANG_varedit_topnum",
			'topnum_data' => "$topnum",
			'topnum_other' => "$LANG_varedit_topnum_other",
			'sendemail' => "$LANG_varedit_sendemail",
			'sendemail_code' => "$sendemail_code",
			'usemd5' => "$LANG_varedit_usemd5",
			'usemd5_code' => "$usemd5_code",
			'usegzhandler' => "$LANG_varedit_usegz",
			'usegzhandler_code' => "$usegzhandler_code",
			'logclicks' => "$LANG_varedit_logclicks",
			'logclicks_code' => "$logclicks_code",
			'usedbrand' => "$LANG_varedit_userand",
			'use_dbrand_code' => "$use_dbrand_code",
			'use_dbrand_warn' => "$LANG_varedit_userandwarn",
			'title' => "$exchangename - $LANG_editvars_title",
			'shorttitle' => "$LANG_editvars_title",
			'dateformat' => "$LANG_date_format",
			'dateformatcode' => "$dateformatcode",
			'yes' => "$LANG_yes",
			'no' => "$LANG_no",
			'submit' => "$LANG_submit",
			'reset' => "$LANG_reset",
			'menu' => 'admin_menuing.php',
			'footer' => '../footer.php'));
		$page->output();
		
	}
?>