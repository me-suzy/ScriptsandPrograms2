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
require_once('lib/template_class.php');
include("lang/common.php");
include("css.php");

if($use_gzhandler==1){
ob_start("ob_gzhandler");
}

session_start();
$session=session_id();

//get the old session ref data:
if($_REQUEST['SID']){
	$ref=$_SESSION[ref];
}else{
	$ref=$_REQUEST[ref];
}

if(!$ref or $ref==''){
		$_SESSION[ref]="0";
	}else{
		$_SESSION[ref]=$ref;
	}

$page = new Page('template/clientlogin.php');
$page->replace_tags(array(
'css' => "$css",
'session' => "$session",
'baseurl' => "$baseurl",
'title' => "$exchangename - $LANG_indtitle",
'instructions' => "$LANG_login_instructions",
'headertitle' => "$LANG_headertitle",
'login' => "$LANG_login",
'pass' => "$LANG_pw",
'login_button' => "$LANG_login_button",
'menu' => 'common_menuing.php',
'footer' => 'footer.php'));

$page->output();

?>

