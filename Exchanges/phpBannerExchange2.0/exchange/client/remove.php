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

if($use_gzhandler==1){
ob_start("ob_gzhandler");
}

require_once('../lib/template_class.php');
include("../lang/client.php");

$db=mysql_connect("$dbhost","$dbuser","$dbpass");
mysql_select_db($dbname,$db);

// We won't ask them if they want to be removed, we'll
// just remove them...

$fix_them=mysql_query("update banneruser set newsletter='0' where id='$id' and email='$email'") or die ("Unable to locate this account. Either your account has been removed or you are trying to hack us! Your IP address has been logged.");

$page = new Page('../template/client_removal.php');
$page->replace_tags(array(
'css' => "$css",
'baseurl' => "$baseurl",
'title' => "$exchangename - $LANG_mailingprefs",
'msg' => "$LANG_removal",
'footer' => '../footer.php'));

$page->output();
?>