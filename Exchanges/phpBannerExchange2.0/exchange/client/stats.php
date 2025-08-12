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
require_once('../lib/template_class.php');
include("../lang/client.php");

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

if($_REQUEST['SID']){
	session_start();
	header("Cache-control: private"); //IE 6 Fix 
	$session=session_id();
	$login=$_SESSION['login'];
	$pass=$_SESSION['pass'];
	$id=$_SESSION['id'];
}else{
	if($usemd5 == "Y"){

		$encpw=md5($pass);
		$result = mysql_query("select * from banneruser where login='$login' AND pass='$encpw'");
		$get_userinfo = mysql_fetch_array($result);
	}else{
		$result = mysql_query("select * from banneruser where login='$login' AND pass='$pass'");
		$get_userinfo=mysql_fetch_array($result);
	}
	
	session_start();
	header("Cache-control: private"); //IE 6 Fix 
	$session=session_id();
	$login=$get_userinfo[login];
	$pass=$get_userinfo[pass];
	$id=$get_userinfo[id];
	$_SESSION['login']=$login;
	$_SESSION['pass']=$pass;
	$_SESSION['id']=$id;
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
// Let's get some stats, shall we?

	$statsresults=mysql_query("select * from bannerstats where uid='$id'");
	$get_stats=mysql_fetch_array($statsresults);
// Declare the variables to make life easier..
	$uid=$get_stats[uid];
	$exposures = $get_stats[exposures];
	$credits = $get_stats[credits];
	$clicks = $get_stats[clicks];
	$siteclicks = $get_stats[siteclicks];
	$approved = $get_stats[approved];
	$default = $get_stats[defaultacct];
	$histexposures = $get_stats[histexposures];

//get the date and stuff
	$datestuff = $get_stats[startdate];
	$startdate = date("M d Y", $datestuff);
	if($exposures == 0){
		$expday = 0;
	}else{
		$time=time();
		$days = $time - $datestuff;
		$seconds = $days / "86400";
		
		if($seconds > 0){
			$expdaymath = $exposures / $seconds;
			$expday= round($expdaymath, 3);
		}else{
			$expday="0";
		}
	}
// Get the total number of times people have seen a banner on the site..
	$totviews = $get_stats[histexposures];
// Snag the ratio and do the percentage math for exposures...

	if($siteclicks > '0' AND $histexposures > '0'){ 
		$outratiomath=$histexposures / $siteclicks;
		$outrat = @round($outratiomath);
		$outratio = $outrat.':1';

		$outpercentmath=$siteclicks / $histexposures;
		$outpermath = $outpercentmath * 100;
		$outpercent= @round($outpermath, 2);
	}else{
		$outratio="N/A";
		$outpercent="0";
	}

	if($exposures > '0' and $clicks > '0'){ 
		$inratiomath=$exposures / $clicks;
		$inrat = @round($inratiomath);
		$inratio = $inrat.':1';
		
		$inpercentmath = $clicks / $exposures;
		$inpermath = $inpercentmath * 100;
		$inpercent= @round($inpermath, 2);
	}else{
		$inratio="N/A";
		$inpercent="0";
	}

	//grab the count for the banners..
	$banners = mysql_query("select * from bannerurls where uid='$id'");
	$total_found=mysql_num_rows($banners);

	// referral stats
	if($referral_program == "Y"){
	$referrals = mysql_query("select * from bannerrefs where refid='$id' and given='1'");
	$total_refs = @mysql_num_rows($referrals);
	$total_ref_credits = $referral_bounty * $total_refs;
	$pendingrefs = mysql_query("select * from bannerrefs where refid='$id' and given='0'");
	$total_pending_refs = @mysql_num_rows($pendingrefs); 
	}

	if($banexp=='1'){
			$banexp_msg= "$LANG_stats_exp_normal <b>$totviews</b> $LANG_stats_exp_normal1 <b>$siteclicks</b> $LANG_stats_exp_normal2 <b>$credits</b> $LANG_stats_exp_normal3 <b>$steexp</b> $LANG_stats_exp_normal4";
		}else{
			$banexp_msg= "$LANG_stats_exp_weird <b>$totviews</b> $LANG_stats_exp_weird1 <b>$siteclicks</b> $LANG_stats_exp_weird2 <b>$credits</b> $LANG_stats_exp_weird3 <b>$steexp</b> $LANG_stats_exp_weird4"; 
		}
if($referral_program == "Y"){
$referral_msg= "<p>$LANG_stats_referral1 <b>$total_ref_credits</b> $LANG_stats_referral2 <b>$total_refs</b> $LANG_stats_referral3 <b>$total_pending_refs</b> $LANG_stats_referral4";
}
		if($approved==1){
		$approved_msg= "<p><b>$LANG_stats_approved</b><p>";
	}else{
		$approved_msg= "<p><b>$LANG_stats_unapproved</b><p>";
	}

$page = new Page('../template/client_stats.php');
$page->replace_tags(array(
'css' => "$css",
'session' => "$session",
'baseurl' => "$baseurl",
'title' => "$exchangename - $LANG_stats_title $login",
'shorttitle' => "$LANG_stats_title $login",
'startdate' => "$LANG_stats_startdate",
'siteexposure' => "$LANG_stats_siteexpos",
'siteclicks' => "$LANG_stats_siteclicks",
'percentage' => "$LANG_stats_percent",
'ratio' => "$LANG_stats_ratio",
'start_data' => "$startdate",
'siteexp_data' => "$totviews",
'siteclicks_data' => "$siteclicks",
'outpercent_data' => "$outpercent",
'outratio_data' => "$outratio",
'exposures' => "$LANG_stats_exposures",
'avgexposures' => "$LANG_stats_avgexp",
'clicks' => "$LANG_stats_clicks",
'inpercent' => "$LANG_stats_percent",
'inratio' => "$LANG_stats_ratio",
'exposures_data' => "$exposures",
'avgexp_data' => "$expday",
'clicks_data' => "$clicks",
'inpercent_data' => "$inpercent",
'inratio_data' => "$inratio",
'banexp_msg' => "$banexp_msg",
'credits_hdr' => "$LANG_commerce_credits",
'credits' => "$credits",
'approved_msg' => "$approved_msg",
'bannercount' => "$LANG_stats_bannercount",
'found' => "$total_found",
'starttip' => "$LANG_tip_startdate",
'siteexptip' => "$LANG_tip_siteexposure",
'clicksfromtip' => "$LANG_tip_clickfrom",
'percentouttip' => "$LANG_tip_percentout",
'ratioouttip' => "$LANG_tip_ratioout",
'exposurestip' => "$LANG_tip_exposures",
'avgexptip' => "$LANG_tip_avgexp",
'clickstip' => "$LANG_tip_clicks",
'percentintip' => "$LANG_tip_percentin",
'ratioin' => "$LANG_tip_ratioin",
'creditstip' => "$LANG_tip_credits",
'footer' => '../footer.php',
'menu' => 'client_menuing.php'));

$page->output();
}

?>