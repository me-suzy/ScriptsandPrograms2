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

include("config.php");
include("css.php");
if($use_gzhandler==1){
ob_start("ob_gzhandler");
}

session_start();
session_register(ref);

require_once('lib/template_class.php');
include("config.php");
include("lang/common.php");
$session=session_id();

$db=mysql_connect("$dbhost","$dbuser","$dbpass");
mysql_select_db($dbname,$db);
// Total validated users..
	$users = mysql_query("select uid from bannerstats where approved='1'");
	$totusers = @mysql_num_rows($users);
// Total exposures..
	$exposures = mysql_query("select sum(histexposures) as exposure from bannerstats");
	$exp = @mysql_fetch_array($exposures);
	$totexp = $exp[exposure];
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
// Overall Ratio
	if($totexp == '0' OR $totsiteclicks == '0'){
		$totratio = "0";
	}else{
	$ratiomath=$totexp / $totsiteclicks;
	$totratio = round($ratiomath,2);
	}

$page = new Page('template/overall_stats.php');
$page->replace_tags(array(
'session' => "$session",
'css' => "$css",
'baseurl' => "$baseurl",
'title' => "$exchangename - $LANG_overallstats",
'shorttitle' => "$LANG_overallstats",
'users' => "$LANG_overall_totusers",
'totusers' => "$totusers",
'exposures' => "$LANG_overall_exposures",
'totexp' => "$totexp",
'banners' => "$LANG_overall_banners",
'totbanners' => "$totbanners",
'clicks' => "$LANG_overall_totclicks",
'totclicks' => "$totclicks",
'siteclicks' => "$LANG_overall_totsiteclicks",
'totsiteclicks' => "$totsiteclicks",
'ratio' => "$LANG_overall_ratio",
'totratio' => "$totratio",
'menu' => 'common_menuing.php',
'footer' => 'footer.php'));

$page->output();
?>

