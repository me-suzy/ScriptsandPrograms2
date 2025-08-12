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

if(!$_SID){
session_start();
}else{
	session_destroy();
}

$page = new Page('../template/admin_loginform.php');
$page->replace_tags(array(
'css' => "$css",
'baseurl' => "$baseurl",
'title' => "$exchangename - $LANG_index_title",
'shorttitle' => "$LANG_index_title",
'login' => "$LANG_index_login",
'pass' => "$LANG_index_password",
'baseurl' => "$baseurl",
'menu' => 'admin_menuing.php',
'footer' => '../footer.php'));

$page->output();
?>