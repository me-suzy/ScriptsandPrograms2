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

// Begin login stuff
$db=mysql_connect("$dbhost","$dbuser","$dbpass");
mysql_select_db($dbname,$db);
$result = mysql_query("select * from banneruser where login='$login' AND pass='$pass'");
$get_userinfo=@mysql_fetch_array($result);
$id=$get_userinfo[id];
$login=$get_userinfo[login];
$pass=$get_userinfo[pass];

session_start();
$session=session_id();
$login=$_SESSION['login'];
$pass=$_SESSION['pass'];
$id=$_SESSION['id'];

if($login=="" AND $pass=="" OR $pass=="") {
	$page = new Page('../template/client_login_error.php');	
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

$info=mysql_query("select * from banneruser where id='$id'");
$get_info=mysql_fetch_array($info);

			$page = new Page('../template/client_editpass_form.php');
			$page->replace_tags(array(
			'css' => "$css",
			'session' => "$session",
			'baseurl' => "$baseurl",
			'title' => "$exchangename - $LANG_email_title",
			'shorttitle' => "$LANG_email_title",
			'pass1' => "$LANG_pass1_label",
			'pass2' => "$LANG_pass2_label",
			'subbutton' => "$LANG_pass_button",
			'reset' => "$LANG_reset",
			'footer' => '../footer.php',
			'menu' => 'client_menuing.php'));

			$page->output();

		
}
?>
