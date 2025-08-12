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

require_once('lib/template_class.php');
include("css.php");
include("config.php");
include("lang/common.php");
$session=session_id();

$page = new Page('template/common_menuing.php');
$page->replace_tags(array(
'baseurl' => "$baseurl",
'session' => "$session",
'menu_head' => "$LANG_menu_options",
'home' => "$LANG_backtologin",
'lostpw' => "$LANG_lostpw",
'faq' => "$LANG_faq",
'signup' => "$LANG_signup",
'rules' => "$LANG_rules",
'cou' => "$LANG_tocou",
'extras' => "$LANG_menu_extras",
'topbanns' => "$LANG_topbanns",
'overallstats' => "$LANG_overallstats"));

$page->output();

?>
