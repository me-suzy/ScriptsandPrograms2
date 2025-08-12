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
require_once('lib/template_class.php');
include("config.php");
include("lang/common.php");
$session=session_id();

$page = new Page('template/recoverpw_form.php');
$page->replace_tags(array(
'session' => "$session",
'css' => "$css",
'baseurl' => "$baseurl",
'title' => "$exchangename - $LANG_lostpw_title",
'lostpw' => "$LANG_lostpw_title",
'instruction' => "$LANG_lostpw_instructions",
'instruction2' => "$LANG_lostpw_instructions2",
'email' => "$LANG_lostpw_email",
'menu' => 'common_menuing.php',
'footer' => 'footer.php'));

$page->output();

?>

