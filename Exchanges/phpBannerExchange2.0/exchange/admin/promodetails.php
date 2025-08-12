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

// Promo Details..
$promoid=$_REQUEST[promoid];
$infoquery=mysql_query("select * from bannerpromos where promoid='$promoid'");
$get_info=@mysql_fetch_array($infoquery);

	if($get_info[promotype]=="1"){
		$lang_type="$LANG_promo_type1";
		$value="N/A";
		$credits=$get_info[promocredits];
	}
	if($get_info[promotype]=="2"){
		$lang_type="XX% $LANG_promo_type2";
		$value="$get_info[promovalue]% $LANG_promo_type2";
		$credits="N/A";
	}
	if($get_info[promotype]=="3"){
		$lang_type="$LANG_promo_type3";
		$value="$get_info[promocredits] - $currency_sign$get_info[promovalue] $currency_int";
		$credits=$get_info[promocredits];
	}

	if($get_info[promoreuse] == "0"){
		$reuse="No";
		$reusedays="N/A";
	}else{
		$reuse="Yes";
		$reusedays="$get_info[promoreuseint] $LANG_promodet_reuseintdays";
	}

	if($get_info[promousertype] == "0"){
		$usertype="$LANG_promo_newonly";
	}else{
		$usertype="$LANG_promo_all";
	}
	
	if($date_format == '1'){
		$time_formatted=date("m/d/y",$get_info[ptimestamp]);
	}

	if($date_format == '0'){
		$time_formatted=date("d/m/y",$get_info[ptimestamp]);
	}

	if($get_info[promostatus] == 0){
		$status="$LANG_promo_deleted";
	}else{
		$status="$LANG_promo_active";
	}
	
//get promostats.
$statsquery=mysql_query("select * from bannerpromologs where promoid='$promoid' order by usedate desc");
	$num=@mysql_num_rows($statsquery);
	if($num > 0){
		while($get_stats=@mysql_fetch_array($statsquery)){
			$uid=$get_stats[uid];
			$user=mysql_query("select login from banneruser where id='$uid' limit 1");
			$get_user=mysql_fetch_array($user);
			$username=$get_user[login];
		
			if($date_format == '1'){
				$usedate=date("m/d/y",$get_stats[usedate]);
			}

			if($date_format == '0'){
				$usedate=date("d/m/y",$get_stats[usedate]);
			}
			$code.="<tr><td class=\"tablebody\"><a href=\"edit.php?SID=$session&uid=$uid\">$username</a></td><td class=\"tablebody\">$usedate</td></tr>";
		}
	}else{
		$code="<tr><td class=\"tablebody\" colspan=\"2\">$LANG_promodet_nostats</td></tr>";
	}



	$page = new Page('../template/admin_promo_details.php');
	$page->replace_tags(array(
		'css' => "$css",
		'session' => "$session",
		'baseurl' => "$baseurl",
		'id' => "$LANG_promodet_id",
		'id_val' => "$get_info[promoid]",
		'name' => "$LANG_promodet_name",
		'name_val' => "$get_info[promoname]",
		'type' => "$LANG_promodet_type",
		'type_val' => "$lang_type",
		'value' => "$LANG_promodet_vals",
		'value_val' => "$value",
		'credits' => "$LANG_promodet_credits",
		'credits_val' => "$credits",
		'promocode' => "$LANG_promodet_code",
		'promocode_val' => "$get_info[promocode]",
		'reuse' => "$LANG_promodet_reuse",
		'reuse_val' => "$reuse",
		'reusedays' => "$LANG_promodet_reuseint",
		'reusedays_val' => "$reusedays",
		'usertype' => "$LANG_promodet_usertype",
		'usertype_val' => "$usertype",
		'timestamp' => "$LANG_promodet_timestamp",
		'timestamp_val' => "$time_formatted",
		'status' => "$LANG_promodet_status",
		'status_val' => "$status",
		'log_head' => "$LANG_promodet_loghead",
		'title' => "$exchangename - $LANG_promodet_title",
		'overview' => "$LANG_promodet_overview",
		'username' => "$LANG_edit_login",
		'coupusedate' => "$LANG_promodet_usedate",
		'code' => "$code",
		'menu' => 'admin_menuing.php',
		'footer' => '../footer.php'));
	$page->output();
	}	
?>