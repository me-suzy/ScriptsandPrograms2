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

// Begin login stuff
$db=mysql_connect("$dbhost","$dbuser","$dbpass");
mysql_select_db($dbname,$db);
session_start();
header("Cache-control: private"); //IE 6 Fix 
$session=session_id(); 

$login = $_SESSION['login'];
$pass = $_SESSION['pass'];

$result = mysql_query("select * from banneradmin where adminuser='$login' AND adminpass='$pass'");
$get_userinfo=mysql_fetch_array($result);
$login=$get_userinfo[adminuser];
$pass=$get_userinfo[adminpass];

if($login=="" AND $pass=="" OR $pass==""){
	$page = new Page('../template/admin_login_error.php');
	$page->replace_tags(array(
		'css' => "$css",
		'session' => "$session",
		'baseurl' => "$baseurl",
		'title' => "$exchangename - $LANG_login_error_title",
		'shorttitle' => "$LANG_login_error_title",	
		'msg' => "$LANG_login_error",
		'footer' => '../footer.php'));	
	$page->output();
	session_destroy();	
	
	}else{ 	
		$newcatname=$_REQUEST['newcatname'];
		$catid=$_REQUEST['catid'];

		$insert=mysql_query("update bannercats set catname='$newcatname' where id='$catid'");
		
		$page = new Page('../template/admin_editcat_confirm.php');
		$page->replace_tags(array(
			'css' => "$css",
			'session' => "$session",
			'baseurl' => "$baseurl",
			'title' => "$exchangename - $LANG_editcat_title",
			'shorttitle' => "$LANG_editcat_title",
			'message' => "$LANG_editcat_success",
			'back' => "$LANG_back",
			'menu' => 'admin_menuing.php',
			'footer' => '../footer.php'));
		$page->output();
	}	
?>