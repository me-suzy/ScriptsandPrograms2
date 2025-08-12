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
include("../lang/client.php");
require_once('../lib/template_class.php');
$session=session_id();

if($sellcredits==1){
	$commerce="<a href=\"$baseurl/client/commerce.php?SID=$session\">$LANG_menu_commerce</a><br>";
	}

$page = new Page('../template/client_menuing.php');
$page->replace_tags(array(
'baseurl' => "$baseurl",
'session' => "$session",
'navigation' => "$LANG_menu_nav",
'home' => "$LANG_menu_home",
'logout' => "$LANG_menu_logout",
'stats' => "$LANG_menu_stats",
'emailstats' => "$LANG_menu_emstats",
'site' => "$LANG_menu_site",
'commerce' => "$commerce",
'histstats' => "$LANG_menu_histstats",
'changeurl' => "$LANG_menu_target",
'banners' => "$LANG_menu_banners",
'category' => "$LANG_menu_cat",
'gethtml' => "$LANG_menu_htmlcode",
'information' => "$LANG_menu_info",
'changemail' => "$LANG_menu_changeem",
'changepass' => "$LANG_menu_changepass",
'promo' => "$LANG_coupon_menuitem",
'overallstats' => "$LANG_overallstats"));

$page->output();

?>

