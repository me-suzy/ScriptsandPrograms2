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

$db=mysql_connect("$dbhost","$dbuser","$dbpass");
mysql_select_db($dbname,$db);

$uid=$_REQUEST['uid'];
$ref=$_REQUEST['ref'];

	$info=mysql_query("select * from banneruser where id='$uid'");
	$get_info=mysql_fetch_array($info);
	$stats=mysql_query("select * from bannerstats where uid='$uid'");
	$get_stats=mysql_fetch_array($stats);
	$name=$get_info[name];
	$email=$get_info[email];
	$userlogin=$get_info[login];
	$userpass=$get_info[pass];
	$category=$get_stats[category];
	$exposures=$get_stats[exposures];
	$credits=$get_stats[credits];
	$clicks=$get_stats[clicks];
	$siteclicks=$get_stats[siteclicks];
	$approved=$get_stats[approved];
	$defaultacct=$get_stats[defaultacct];
	$histexposures=$get_stats[histexposures];
	$raw=$get_stats[raw];
	$newsletter=$get_info[newsletter];
	$rawformatted=htmlspecialchars($raw);

	$total_found="0";
	$banners=mysql_query("select * from bannerurls where uid='$uid'");
	$total_found=mysql_num_rows($banners);

	if($email){
		  $emaillink= "[<a href=\"emailuser.php?id=$uid\">$LANG_email_button_send</a>]";
		}	

$get_cats=mysql_query("select * from bannercats");
$num_cats=@mysql_num_rows($get_cats);
if($num_cats > "0"){
	$get_catname=mysql_query("select category from bannerstats where uid='$uid'");
	$touchcat=mysql_fetch_array($get_catname);
	$catno=$touchcat[category];
	$snag_catname=mysql_query("select catname from bannercats where id='$catno'");
	$touchcatname=mysql_fetch_array($snag_catname);
	$catname=$touchcatname[catname];
	$catlist.="<option selected value=\"$catno\">$catname</option>";
	$get_cats=mysql_query("select * from bannercats where id != '$catno'");
		while($get_rows=mysql_fetch_array($get_cats)){
		$catlist.="<option value=\"$get_rows[id]\">$get_rows[catname]</option>"; 
		}
	}else{ 
	$catlist="$LANG_edit_nocats";
	}

	 if($defaultacct==1){
		$default="<input type=radio name=\"defaultacct\">$LANG_no&nbsp;&nbsp;&nbsp;<input type=radio checked name=\"defaultacct\" value=\"defaultacct\">$LANG__yes";
	}else{
		$default="<input type=radio checked name=\"defaultacct\">$LANG_no&nbsp;&nbsp;&nbsp;<input type=radio name=\"defaultacct\" value=\"defaultacct\">$LANG_yes";
	}


	 if($newsletter==1){
		$newsletteropt="<input type=radio name=\"newsletter\">$LANG_no&nbsp;&nbsp;&nbsp;<input type=radio checked name=\"newsletter\" value=\"newsletter\">$LANG_yes";
  }else{
		$newsletteropt="<input type=radio checked name=\"newsletter\">$LANG_no&nbsp;&nbsp;&nbsp;<input type=radio name=\"newsletter\" value=\"newsletter\">$LANG_yes";
  }

if($sellcredits == "1"){
	$saleshist="[<a href=\"commerce_display.php?SID=$session&user=$uid\" target=_blank>$LANG_edit_saleshist</a>]";
}else{
	$saleshist="";
}

$bannername_construct="$total_found $LANG_edit_banners <b>$userlogin</b>";

$page = new Page('../template/admin_edit_form.php');
$page->replace_tags(array(
'css' => "$css",
'session' => "$session",
'baseurl' => "$baseurl",
'title' => "$exchangename - $LANG_edit_heading",
'shorttitle' => "$LANG_edit_heading",
'uid' => "$uid",
'realname' => "$LANG_edit_realname",
'nameval' => "$name",
'login' => "$LANG_edit_login",
'loginval' => "$userlogin",
'pass' => "$LANG_edit_pass",
'passval' => "$userpass",
'email' => "$LANG_edit_email",
'emailval' => "$email",
'emaillink' => "$emaillink",
'sitelink' => "$sitelink",
'category' => "$LANG_edit_category",
'cat_list' => "$catlist",
'exposures' => "$LANG_edit_exposures",
'exposuresval' => "$exposures",
'credits' => "$LANG_edit_credits",
'creditsval' => "$credits",
'saleshist' => "$saleshist",
'clicks' => "$LANG_edit_clicks",
'clicksval' => "$clicks",
'siteclick' => "$LANG_edit_siteclicks",
'siteclicksval' => "$siteclicks",
'raw' => "$LANG_edit_raw",
'rawval' => "$rawformatted",
'status' => "$LANG_edit_status",
'approved' => "$LANG_edit_approved",
'notapproved' => "$LANG_edit_notapproved",
'defaultacct' => "$LANG_edit_defaultacct",
'defaultopt' => "$default",
'ref' => "$_REQUEST[ref]",
'sendletter' => "$LANG_edit_sendletter",
'newsletteropt' => "$newsletteropt",
'validate_button' => "$LANG_edit_button_val",
'reset_button' => "$LANG_edit_button_reset",
'delete_button' => "$LANG_edit_button_del",
'banners' => "$LANG_stats_banner_hdr",
'editbanners' => "$LANG_edit_bannerlink",
'totalbanners' => "$bannername_construct",
'ref' => "$ref",
'menu' => 'admin_menuing.php',
'footer' => '../footer.php'));

$page->output();
}
?>
