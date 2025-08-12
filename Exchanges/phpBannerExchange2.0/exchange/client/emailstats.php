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
//   Copyright 2003-2005 by eschew.net Productions.   //
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
	$error.="$LANG_error_mysqlconnect<p> ";
	$error.=mysql_error();
}
	@mysql_select_db($dbname,$db);
	session_start();
	header("Cache-control: private"); //IE 6 Fix 
	$login=$_SESSION['login'];
	$pass=$_SESSION['pass'];
	$id=$_SESSION['id'];

	$result = mysql_query("select * from banneruser where login='$login' AND pass='$pass'");
	$get_userinfo=@mysql_fetch_array($result);
	$id=$get_userinfo[id];
	$login=$get_userinfo[login];
	$pass=$get_userinfo[pass];
    
	if($login=="" AND $pass=="" OR $pass=="") {
		if(!$err){
			include("../lang/errors.php");
			$error.="$LANG_error_header<p>";
			$error.="$LANG_login_error_client";
		}
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
	//get user info..
	$userinfo=mysql_query("select * from banneruser where id='$id'");
	$get_userinfo=mysql_fetch_array($userinfo);

	$statsresults=mysql_query("select * from bannerstats where uid='$id'");	
	$get_stats=@mysql_fetch_array($statsresults);
	// Declare the variables to make life easier..	
	$exposures = $get_stats[exposures];	
	$credits = $get_stats[credits];
	$clicks = $get_stats[clicks];
	$siteclicks = $get_stats[siteclicks];
	$approved = $get_stats[approved];
	$default = $get_stats[defaultacct];
	
	//get the date and stuff
	$datestuff = $get_stats[startdate];
	$startdate = date("M d Y", $datestuff);	
	if($exposures == 0){	
		$expday = 0;	
	}else{	
		$time=time();
		$days = $time - $datestuff;	
		$seconds = $days / 86400;

			if($seconds > 0){
				$expdaymath = $exposures / $seconds;
				$expday= round($expdaymath, 2);	
			}else{	
				$expday=$exposures;	
			}	
		}
	
	// Get the total number of times people have seen a banner on the site..
	$totviews = $get_stats[histexposures];
	
	// Snag the ratio and do the percentage math for exposures...
	if($siteclicks < '0' OR $exposures < '0'){ 
		$outratiomath=$exposures / $siteclicks;	
		$outrat = @round($outratiomath);
		$outratio = $outrat;
	}else{
		$outratio="N/A";	
	}	

	if($siteclicks > '0' OR $exposures > '0'){
		$outpercentmath=$siteclicks / $exposures;	
		$outpermath = $outpercentmath * 100;	
		$outpercent= @round($outpermath, 2);
	}else{	
		$outpercent="0";	
	}	
	
	if($exposures > '0' and $clicks > '0'){ 
		$inratiomath=$totviews / $clicks;	
		$inrat = @round($inratiomath);
		$inratio = $inrat;
	}else{	
		$inratio="N/A";
	}	
	
	if($exposures > '0' and $clicks > '0'){ 
		$inpercentmath = $clicks / $totviews;	
		$inpermath = $inpercentmath * 100;	
		$inpercent= @round($inpermath, 2);
	}else{	
		$inpercent="0";
	}

	// extra stuff we need..
	$name=$get_userinfo[name];
	$email=$get_userinfo[email];
	$createdate= $get_stats[startdate];
	
	include("../template/mail/client_mailstats.php");

	mail($email,$subject,$content,"From: $ownermail");	
	$page = new Page('../template/client_mailstats.php');
	$page->replace_tags(array(
		'css' => "$css",
		'session' => "$session",	
		'baseurl' => "$baseurl",	
		'title' => "$exchangename - $LANG_emailstats_title",	
		'shorttitle' => "$LANG_emailstats_title",	
		'msg' => "$LANG_emailstats_msg",
		'footer' => '../footer.php',	
		'menu' => 'client_menuing.php'));	
	$page->output();
	}
?>