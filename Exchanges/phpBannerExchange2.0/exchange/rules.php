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
$legal=mysql_query("select data from bannerconfig where name='rules'");
$get_legal=mysql_fetch_array($legal);
$legalcrap=$get_legal[data];
$legalcrap=stripslashes($legalcrap);

$page = new Page('template/rules.php');
$page->replace_tags(array(
'css' => "$css",
'session' => "$session",
'baseurl' => "$baseurl",
'title' => "$exchangename - $LANG_rules",
'shorttitle' => "$LANG_rules",
'rules' => "$legalcrap",
'exchange_name' => "$exchangename",
'site_name' => "$sitename",
'site_url' => "$baseurl",
'admin_name' => "$adminname",
'admin_mail' => "$ownermail",
'email' => "$LANG_lostpw_email",
'menu' => 'common_menuing.php',
'footer' => 'footer.php'));

$page->output();
?>