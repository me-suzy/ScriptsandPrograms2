<?
$file_rev="041305";
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
// No template for this file because it will screw up the progressions..
	
	if($_REQUEST[option]=="1" or $_REQUEST[option]=="2"){
		echo "$LANG_updatemgr_checkperms<br>";
		$filename="$basepath/manifest.php";

		if(!file_exists($filename)){
			echo "$LANG_updatemgr_nomanifest<p>";
			exit();
		}

		if(!is_writable($filename)){
			echo "$LANG_updatemgr_permerror<p>";
			exit();
		}

		echo "$LANG_updatemgr_permok<p>";
		include("../manifest.php");
		echo "$LANG_updatemgr_manifestcheck<br>";
		if($timestamp==''){
			$time="$LANG_updatemgr_never";
		}else{
			$time=date(r,$timestamp);
		}
		
		echo "$LANG_updatemgr_manifeststamp: <b>$time</b><br>";
		echo "$LANG_updatemgr_url: <b>$updateurl</b><p>";
		echo "$LANG_updatemgr_popmanifest<p>";

		include("../lib/manifest_upd_class.php");

		$file=realpath('../manifest.php');

		$timestamp=time();
		$updateurl="http://www.eschew.net/scripts/phpbe/2.0/updates/master.xml";

		$manifest_data = '<?php'."\n\n";
		$manifest_data .= "//\n// Leave all of this alone. This file is populated\n// via the Update manager!\n//\n\n";
		$manifest_data .= "// General Config\n";
		$manifest_data .= '$timestamp = "' . $timestamp .'";' . "\n";
		$manifest_data .= '$updateurl = "' . $updateurl . '";' . "\n\n";

		$manifest_data .= "// Common Section\n";
		$manifest_data .= '$FILE_common_cou = "' . $FILE_common_cou . '";' . "\n";
		$manifest_data .= '$FILE_common_click = "' . $FILE_common_click . '";' . "\n";
		$manifest_data .= '$FILE_common_menu = "' . $FILE_common_menu . '";' . "\n";
		$manifest_data .= '$FILE_common_cookies = "' . $FILE_common_cookies . '";' . "\n";
		$manifest_data .= '$FILE_common_dblog = "' . $FILE_common_dblog. '";' . "\n";
		$manifest_data .= '$FILE_common_faq = "' . $FILE_common_faq . '";' . "\n";
		$manifest_data .= '$FILE_common_footer = "' . $FILE_common_footer . '";' . "\n";
		$manifest_data .= '$FILE_common_index = "' . $FILE_common_index . '";' . "\n";
		$manifest_data .= '$FILE_common_overall = "' . $FILE_common_overall . '";' . "\n";
		$manifest_data .= '$FILE_common_recoverpw = "' . $FILE_common_recoverpw . '";' . "\n";
		$manifest_data .= '$FILE_common_resetpw = "' . $FILE_common_resetpw . '";' . "\n";
		$manifest_data .= '$FILE_common_rules = "' . $FILE_common_rules . '";' . "\n";
		$manifest_data .= '$FILE_common_signup = "' . $FILE_common_signup . '";' . "\n";
		$manifest_data .= '$FILE_common_signconf = "' . $FILE_common_signconf . '";' . "\n";
		$manifest_data .= '$FILE_common_top = "' . $FILE_common_top . '";' . "\n";
		$manifest_data .= '$FILE_common_view = "' . $FILE_common_view . '";' . "\n\n";

		$manifest_data .= "// Common Section\n";
		$manifest_data .= '$FILE_user_addconfirm = "' . $FILE_user_addconfirm . '";' . "\n";
		$manifest_data .= '$FILE_user_banners = "' . $FILE_user_banners . '";' . "\n";
		$manifest_data .= '$FILE_user_category = "' . $FILE_user_category . '";' . "\n";
		$manifest_data .= '$FILE_user_categoryconf = "' . $FILE_user_categoryconf . '";' . "\n";
		$manifest_data .= '$FILE_user_changeurlconf = "' . $FILE_user_changeurlconf . '";' . "\n";
		$manifest_data .= '$FILE_user_clicklog = "' . $FILE_user_clicklog . '";' . "\n";
		$manifest_data .= '$FILE_user_menu = "' . $FILE_user_menu . '";' . "\n";
		$manifest_data .= '$FILE_user_commerce = "' . $FILE_user_commerce . '";' . "\n";
		$manifest_data .= '$FILE_user_delban = "' . $FILE_user_delban . '";' . "\n";
		$manifest_data .= '$FILE_user_delbanconf = "' . $FILE_user_delbanconf . '";' . "\n";
		$manifest_data .= '$FILE_user_editbanner = "' . $FILE_user_editbanner . '";' . "\n";
		$manifest_data .= '$FILE_user_editinfo = "' . $FILE_user_editinfo . '";' . "\n";
		$manifest_data .= '$FILE_user_editpass = "' . $FILE_user_editpass . '";' . "\n";
		$manifest_data .= '$FILE_user_emailstats = "' . $FILE_user_emailstats . '";' . "\n";
		$manifest_data .= '$FILE_user_gethtml = "' . $FILE_user_gethtml . '";' . "\n";
		$manifest_data .= '$FILE_user_index = "' . $FILE_user_index . '";' . "\n";
		$manifest_data .= '$FILE_user_logout = "' . $FILE_user_logout . '";' . "\n";
		$manifest_data .= '$FILE_user_infoconfirm = "' . $FILE_user_infoconfirm . '";' . "\n";
		$manifest_data .= '$FILE_user_passconfirm = "' . $FILE_user_passconfirm . '";' . "\n";
		$manifest_data .= '$FILE_user_promo = "' . $FILE_user_promo . '";' . "\n";
		$manifest_data .= '$FILE_user_remove = "' . $FILE_user_remove . '";' . "\n";
		$manifest_data .= '$FILE_user_stats = "' . $FILE_user_stats . '";' . "\n";
		$manifest_data .= '$FILE_user_uploadbanner = "' . $FILE_user_uploadbanner . '";' . "\n\n";

		$manifest_data .= "// Admin Section\n";
		$manifest_data .= '$FILE_admin_addacct = "' . $FILE_admin_addacct . '";' . "\n";
		$manifest_data .= '$FILE_admin_addacctconf = "' . $FILE_admin_addacctconf . '";' . "\n";
		$manifest_data .= '$FILE_admin_addadmin = "' . $FILE_admin_addadmin . '";' . "\n";
		$manifest_data .= '$FILE_admin_addcat = "' . $FILE_admin_addcat . '";' . "\n";
		$manifest_data .= '$FILE_admin_menu = "' . $FILE_admin_menu . '";' . "\n";
		$manifest_data .= '$FILE_admin_adminconf = "' . $FILE_admin_adminconf . '";' . "\n";
		$manifest_data .= '$FILE_admin_banners = "' . $FILE_admin_banners . '";' . "\n";
		$manifest_data .= '$FILE_admin_catmain = "' . $FILE_admin_catmain . '";' . "\n";
		$manifest_data .= '$FILE_admin_changedefban = "' . $FILE_admin_changedefban . '";' . "\n";
		$manifest_data .= '$FILE_admin_checkbanners = "' . $FILE_admin_checkbanners . '";' . "\n";
		$manifest_data .= '$FILE_admin_checkbannersgo = "' . $FILE_admin_checkbannersgo . '";' . "\n";
		$manifest_data .= '$FILE_admin_commerce = "' . $FILE_admin_commerce . '";' . "\n";
		$manifest_data .= '$FILE_admin_commercedisp = "' . $FILE_admin_commercedisp . '";' . "\n";
		$manifest_data .= '$FILE_admin_commercedit = "' . $FILE_admin_commercedit . '";' . "\n";
		$manifest_data .= '$FILE_admin_dbdump = "' . $FILE_admin_dbdump . '";' . "\n";
		$manifest_data .= '$FILE_admin_dbrestore = "' . $FILE_admin_dbrestore . '";' . "\n";
		$manifest_data .= '$FILE_admin_dbtools = "' . $FILE_admin_dbtools . '";' . "\n";
		$manifest_data .= '$FILE_admin_dbupload = "' . $FILE_admin_dbupload . '";' . "\n";
		$manifest_data .= '$FILE_admin_deladmin = "' . $FILE_admin_deladmin . '";' . "\n";
		$manifest_data .= '$FILE_admin_deladminconf = "' . $FILE_admin_deladminconf . '";' . "\n";
		$manifest_data .= '$FILE_admin_delcat = "' . $FILE_admin_delcat . '";' . "\n";
		$manifest_data .= '$FILE_admin_delcatconf = "' . $FILE_admin_delcatconf . '";' . "\n";
		$manifest_data .= '$FILE_admin_delacct = "' . $FILE_admin_delacct . '";' . "\n";
		$manifest_data .= '$FILE_admin_delacctconf = "' . $FILE_admin_delacctconf . '";' . "\n";
		$manifest_data .= '$FILE_admin_delbanner = "' . $FILE_admin_delbanner . '";' . "\n";
		$manifest_data .= '$FILE_admin_doupdate = "' . $FILE_admin_doupdate . '";' . "\n";
		$manifest_data .= '$FILE_admin_edit = "' . $FILE_admin_edit . '";' . "\n";
		$manifest_data .= '$FILE_admin_editcat = "' . $FILE_admin_editcat . '";' . "\n";
		$manifest_data .= '$FILE_admin_editcatconfirm = "' . $FILE_admin_editcatconfirm . '";' . "\n";
		$manifest_data .= '$FILE_admin_editconf = "' . $FILE_admin_editconf . '";' . "\n";
		$manifest_data .= '$FILE_admin_editcss = "' . $FILE_admin_editcss . '";' . "\n";
		$manifest_data .= '$FILE_admin_editpass = "' . $FILE_admin_editpass . '";' . "\n";
		$manifest_data .= '$FILE_admin_editstuff = "' . $FILE_admin_editstuff . '";' . "\n";
		$manifest_data .= '$FILE_admin_editvars = "' . $FILE_admin_editvars . '";' . "\n";
		$manifest_data .= '$FILE_admin_email = "' . $FILE_admin_email . '";' . "\n";
		$manifest_data .= '$FILE_admin_emailgo = "' . $FILE_admin_emailgo . '";' . "\n";
		$manifest_data .= '$FILE_admin_emailsend = "' . $FILE_admin_emailsend . '";' . "\n";
		$manifest_data .= '$FILE_admin_emailuser = "' . $FILE_admin_emailuser . '";' . "\n";
		$manifest_data .= '$FILE_admin_emailusergo = "' . $FILE_admin_emailusergo . '";' . "\n";
		$manifest_data .= '$FILE_admin_faq = "' . $FILE_admin_faq . '";' . "\n";
		$manifest_data .= '$FILE_admin_faqdel = "' . $FILE_admin_faqdel . '";' . "\n";
		$manifest_data .= '$FILE_admin_faqedit = "' . $FILE_admin_faqedit . '";' . "\n";
		$manifest_data .= '$FILE_admin_index = "' . $FILE_admin_index . '";' . "\n";
		$manifest_data .= '$FILE_admin_listall = "' . $FILE_admin_listall . '";' . "\n";
		$manifest_data .= '$FILE_admin_logout = "' . $FILE_admin_logout . '";' . "\n";
		$manifest_data .= '$FILE_admin_pause = "' . $FILE_admin_pause . '";' . "\n";
		$manifest_data .= '$FILE_admin_processedit = "' . $FILE_admin_processedit . '";' . "\n";
		$manifest_data .= '$FILE_admin_processfaq = "' . $FILE_admin_processfaq . '";' . "\n";
		$manifest_data .= '$FILE_admin_processvars = "' . $FILE_admin_processvars . '";' . "\n";
		$manifest_data .= '$FILE_admin_promodetails = "' . $FILE_admin_promodetails . '";' . "\n";
		$manifest_data .= '$FILE_admin_promos = "' . $FILE_admin_promos . '";' . "\n";
		$manifest_data .= '$FILE_admin_pwconfirm = "' . $FILE_admin_pwconfirm . '";' . "\n";
		$manifest_data .= '$FILE_admin_rmbackup = "' . $FILE_admin_rmbackup . '";' . "\n";
		$manifest_data .= '$FILE_admin_stats = "' . $FILE_admin_stats . '";' . "\n";
		$manifest_data .= '$FILE_admin_templates = "' . $FILE_admin_templates . '";' . "\n";
		$manifest_data .= '$FILE_admin_templateedit = "' . $FILE_admin_templateedit . '";' . "\n";
		$manifest_data .= '$FILE_admin_update = "' . $FILE_admin_update . '";' . "\n";
		$manifest_data .= '$FILE_admin_uploadbanner = "' . $FILE_admin_uploadbanner . '";' . "\n";
		$manifest_data .= '$FILE_admin_validate = "' . $FILE_admin_validate . '";' . "\n\n";

		$manifest_data .= "// Libraries\n";
		$manifest_data .= '$FILE_lib_manifest_upd_class = "' . $FILE_lib_manifest_upd_class . '";' . "\n";
		$manifest_data .= '$FILE_lib_ipn_in = "' . $FILE_lib_ipn_in . '";' . "\n";
		$manifest_data .= '$FILE_lib_template = "' . $FILE_lib_template . '";' . "\n";
		$manifest_data .= '$FILE_lib_ipnlib = "' . $FILE_lib_ipnlib . '";' . "\n";
		$manifest_data .= '$FILE_lib_paypalconf = "' . $FILE_lib_paypalconf . '";' . "\n";
		$manifest_data .= '$FILE_lib_class_compare = "' . $FILE_lib_class_compare . '";' . "\n\n";

		$manifest_data .= "// Language Files\n";
		$manifest_data .= '$FILE_lang_type = "' . $FILE_lang_type . '";' . "\n";
		$manifest_data .= '$FILE_lang_admin = "' . $FILE_lang_admin . '";' . "\n";
		$manifest_data .= '$FILE_lang_client = "' . $FILE_lang_client . '";' . "\n";
		$manifest_data .= '$FILE_lang_common = "' . $FILE_lang_common . '";' . "\n";
		$manifest_data .= '$FILE_lang_errors = "' . $FILE_lang_errors . '";' . "\n";

		$manifest_data .= '?' . '>'; // Done this to prevent highlighting editors getting confused!

		@umask(0011);
		$fp=@fopen('../manifest.php', 'wb');

		if(!@fputs($fp, $manifest_data, strlen($manifest_data))){
			//try again, this time, let's try chmodding..
			if(!@chmod($file, 0777)){
				echo "$LANG_updatemgr_permerror<p>";
				die();
			}

			echo "$LANG_updatemgr_permerror<p>";
			die();
		}else{
			fclose($fp);
			echo "<p>$LANG_updatemgr_successwrite<p>";
		}
	}

	if($_REQUEST[option]=="1" or $_REQUEST[option]=="3"){
// get the master update file from the remote server.
// script will die if it can't do this.
		echo "$LANG_updatemgr_getmaster<p>";

		include("../manifest.php");

// Downlaod and parse the XML/master list
		$handle = fopen("$updateurl", "r");
		$contents = '';
		while (!feof($handle)) {
			$data .= fread($handle, 8192);
		}
		fclose($handle);
		
		$res = xml_parser_create();
		xml_parse_into_struct($res, $data, $vals, $tags);
		xml_parser_free($res);
		$count=count($vals) / 2 - 1; //removes junk spaces and lines from count
		echo "<b>$count</b> $LANG_updatemgr_valsfound<br>";

		foreach ($vals as $value){
			 $$value[tag]=$value[value];
		}

		echo "$LANG_updatemgr_compare<p>";

		include("../lib/class_compare.php");
		
		if($upg=="0"){
			echo "<b>$LANG_updatemgr_notupgrade</b>";
			die();
		}

		if($upg=="1"){
			$number=count($upgurl);
			echo "<b>$number</b> $LANG_updatemgr_updwaiting<br>";
			foreach ($upgurl as $target){
				$newstr=str_replace("http://www.eschew.net/scripts/phpbe/2.0","","$target");
				$newstr=str_replace(".txt", "", "$newstr");
				$newstr=str_replace("$FILE_lang_type/", "", "$newstr");
				$newstr="".$newstr.".php";
				echo "<a href=\"$target\" target=\"_blank\">$newstr</a><br>";
			}
			echo "<br>[<a href=\"http://www.eschew.net/scripts/phpbe/2.0/releasenotes.php\" target=\"_blank\">$LANG_updatemgr_changelog</a>]<p>";
			echo "$LANG_updatemgr_updateinst<p>";
		}
	}
}
?>