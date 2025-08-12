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

 $get_cats=@mysql_query("select * from bannercats");
 $num_cats=@mysql_num_rows($get_cats);
 if($num_cats > "0"){	
	 $get_catname=mysql_query("select category from bannerstats where uid='$id'");	
	 $touchcat=mysql_fetch_array($get_catname);	
	 $catno=$touchcat[category];
	 $snag_catname=@mysql_query("select catname from bannercats where id='$catno'");
	$touchcatname=@mysql_fetch_array($snag_catname);	
	$catname=$touchcatname[catname];
		while($get_rows=@mysql_fetch_array($get_cats)){
			$get_row_id=$get_rows[id];	
			$get_row_category=eregi_replace("_"," ",$get_rows[catname]);
			$catarray.="<option value=\"$get_row_id\">$get_row_category</option>";
			} 
		}else{	
			$catarray="$LANG_edit_nocats";
		}
		$page = new Page('../template/admin_addacct_form.php');
		$page->replace_tags(array(
		'css' => "$css",
		'session' => "$session",
		'baseurl' => "$baseurl",
		'uid' => "$uid",
		'title' => "$exchangename - $LANG_addacct_title",
		'shorttitle' => "$LANG_addacct_title",
		'name' => "$LANG_edit_realname",
		'login' => "$LANG_edit_login",
		'pass' => "$LANG_edit_pass",
		'email' => "$LANG_edit_email",
		'siteurl' => "$LANG_edit_siteurl",
		'category' => "$LANG_edit_category",
		'catarray' => "$catarray",
		'exposures' => "$LANG_edit_exposures",
		'credits' => "$LANG_edit_credits",
		'clicks' => "$LANG_edit_clicks",
		'siteclick' => "$LANG_edit_siteclicks",
		'raw' => "$LANG_edit_raw",
		'status' => "$LANG_edit_status",
		'approved' => "$LANG_edit_approved",
		'notapproved' => "$LANG_edit_notapproved",
		'defaultacct' => "$LANG_edit_defaultacct",
		'no' => "$LANG_no",
		'yes' => "$LANG_yes",
		'sendletter' => "$LANG_edit_sendletter",
		'defaultacct' => "$LANG_edit_defaultacct",
		'sendletter' => "$LANG_edit_sendletter",
		'validate' => "$LANG_edit_button_val",
		'reset' => "$LANG_edit_button_reset",
		'msg' => "$LANG_login_error",
		'menu' => 'admin_menuing.php',
		'footer' => '../footer.php'));
		
		$page->output();	
}	
?>