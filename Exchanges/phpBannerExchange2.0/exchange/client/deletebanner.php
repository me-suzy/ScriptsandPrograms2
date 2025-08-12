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

$session=$_REQUEST[SID];
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
			$bid=$_REQUEST[bid];

			$page = new Page('../template/client_del_banner.php');
			$page->replace_tags(array(
			'css' => "$css",
			'session' => "$session",
			'baseurl' => "$baseurl",
			'title' => "$exchangename - $LANG_delban_title",
			'shorttitle' => "$LANG_delban_title",
			'msg' => "$LANG_delban_warn",
			'bannerurl' => "$bannerurl",
			'bid' => "$bid",
			'button' => "$LANG_delban_button",
			'position' => "$pos",
			'warning' => "$LANG_addbanner_restwarn",
			'footer' => '../footer.php',
			'menu' => 'client_menuing.php'));

			$page->output();

}
?>
