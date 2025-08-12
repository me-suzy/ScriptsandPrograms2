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

// Begin login stuff
if(!$db=@mysql_connect("$dbhost","$dbuser","$dbpass")){
	include("../lang/errors.php");
	$err="1";
	$error.="$LANG_error_header<p>";
	$error.="$LANG_error_mysqlconnect ";
	$error.=mysql_error();
}

@mysql_select_db($dbname,$db);
$login=$_REQUEST['login'];
$pass=$_REQUEST['pass'];

if($_REQUEST[SID]){
	session_start();
	header("Cache-control: private"); //IE 6 Fix 
	$session=session_id();
	$login=$_SESSION['login'];
	$pass=$_SESSION['pass'];
}else{
	if($usemd5 == "Y"){
		$encpw=md5($pass);
		$result = mysql_query("select * from banneradmin where adminuser='$login' AND adminpass='$encpw'");
		$get_userinfo = mysql_fetch_array($result);
		session_start();
		header("Cache-control: private"); //IE 6 Fix 
		$session=session_id();
		$login=$get_userinfo[adminuser];
		$pass=$get_userinfo[adminpass];
		$_SESSION['login']=$login;
		$_SESSION['pass']=$pass;
	}else{
		$result = mysql_query("select * from banneradmin where adminuser='$login' AND adminpass='$pass'");
		$get_userinfo=mysql_fetch_array($result);
		session_start();
		header("Cache-control: private"); //IE 6 Fix 
		$session=session_id();
		$login=$get_userinfo[adminuser];
		$pass=$get_userinfo[adminpass];
		$_SESSION['login']=$login;
		$_SESSION['pass']=$pass;
	}
}
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

// Check to see if our admins are good boys and girls and
// deleted or at least renamedthe /install folder as 
// directed..

if(is_dir('../install/')){
	$security_warning=$LANG_installdir_warning;
}

// Queries
// Check exchange status..
	$status = mysql_query("select * from bannerconfig where name='exchangestate'");
	$get_status=mysql_fetch_array($status);
	$status=$get_status[data];
// Total validated users..
	$users = mysql_query("select uid from bannerstats where approved='1' AND uid != '0'");
	$totusers = @mysql_num_rows($users);
// Get pending account stats...
	$pending = mysql_query("select * from bannerstats where approved='0'");
	$pendusers = @mysql_num_rows($pending);
// Total exposures for clients.
	$exposures = mysql_query("select sum(histexposures) as exposure from bannerstats");
	$exp = @mysql_fetch_array($exposures);
	$totexp = $exp[exposure]+0;
// Total Banners...
	$banners = mysql_query("select id from bannerurls");
	$totbanners = @mysql_num_rows($banners);
// Get clicks to sites..
	$get_clicky = mysql_query("select sum(clicks) as click from bannerstats");
	$clicky = @mysql_fetch_array($get_clicky);
	$totclicks = $clicky[click]+0;
// Get clicks from sites..
	$get_siteclicky = mysql_query("select sum(siteclicks) as siteclick from bannerstats");
	$siteclicky = @mysql_fetch_array($get_siteclicky);
	$totsiteclicks = $siteclicky[siteclick]+0;
// Free credits lurking about..
	$loosecreds = mysql_query("select sum(credits) as credit from bannerstats");
	$cred = @mysql_fetch_array($loosecreds);
	$totloosecred = $cred[credit]+0;

// Overall Ratio
	if($totexp == '0' OR $totsiteclicks == '0'){
		$ratio = "0";
	}else{
	$ratiomath=$totexp / $totsiteclicks;
	$ratio = round($ratiomath);
	}

	$total_found = "0";
	$pending = mysql_query("select * from bannerstats where approved='0'");
	$total_found=mysql_num_rows($pending);
	if($status=="1"){
		$statusmsg=$LANG_exchange_paused;
	}

	$page = new Page('../template/admin_stats.php');
	$page->replace_tags(array(
	'css' => "$css",
	'session' => "$session",
	'baseurl' => "$baseurl",
	'title' => "$exchangename - $LANG_stats_title",
	'stats_snapshot' => "$LANG_stats_statssnapshot $exchangename",
	'shorttitle' => "$LANG_stats_title",
	'valusers' => "$LANG_stats_valusr",
	'totexp' => "$LANG_stats_totexp",
	'loosecred' => "$LANG_stats_loosecred",
	'totalban' => "$LANG_stats_totalban",
	'pendusr' => "$LANG_stats_pendusr",
	'totclicks' => "$LANG_stats_totclicks",
	'totsicl' => "$LANG_stats_totsicl",
	'overrat' => "$LANG_stats_overrat",
	'val_totusers' => "$totusers",
	'val_totexp' => "$totexp",
	'val_totloosecred' => "$totloosecred",
	'val_totviewexp' => "$totbanners",
	'val_pendusers' => "$pendusers",
	'val_totclicks' => "$totclicks",
	'val_totsiteclicks' => "$totsiteclicks",
	'val_ratio' => "$ratio",
	'security_warning' => "$security_warning",
	'statusmessage' => "$statusmsg",
	'menu' => 'admin_menuing.php',
	'footer' => '../footer.php'));
	$page->output();
	
 }

