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
$top=mysql_query("select uid,exposures from bannerstats where defaultacct='0' order by exposures desc limit 0,$topnum");
$num=@mysql_num_rows($top);

if($num == '0' or $num ==''){
	$html="<tr><td colspan=2>$LANG_top10_nobanners</td>";
}else{
	while($get_top=@mysql_fetch_array($top)){
		$exposures=$get_top[exposures];
		$url=$get_top[url];
		$uid=$get_top[uid];
		$banner=mysql_query("select bannerurl,targeturl from bannerurls where uid='$uid'");
		$get_banner=mysql_fetch_array($banner);
		$banner=$get_banner[bannerurl];
		$url=$get_banner[targeturl];
		$html.="<tr><td class=\"tablebody\" width=\"90%\"><a href=\"$url\"><img src=\"$banner\"></a></td><td width=\"10%\" class=\"tablebody\">$exposures</td>";
	}
}
$page = new Page('template/top_x.php');
$page->replace_tags(array(
'css' => "$css",
'session' => "$session",
'baseurl' => "$baseurl",
'title' => "$exchangename - $LANG_top10_title",
'shorttitle' => "$LANG_top10_title",
'banners' => "$LANG_top10_banners",
'exposures' => "$LANG_top10_exposure",
'bannerurl' => "$html",
'site_url' => "$baseurl",
'admin_name' => "$adminname",
'admin_mail' => "$ownermail",
'email' => "$LANG_lostpw_email",
'menu' => 'common_menuing.php',
'footer' => 'footer.php'));

$page->output();

?>

