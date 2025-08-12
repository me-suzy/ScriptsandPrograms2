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
$session=$_REQUEST[SID];
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
session_register(login);
session_register(pass);
session_register(id);

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

$bid=$_REQUEST[bid];

	// let's get the banner info, for sanity and security, let's first
	// get the banner based on the id and the uid.
$get_banner_del=mysql_query("select * from bannerurls where id=$bid and uid=$id");
$banner = @mysql_fetch_array($get_banner_del);
$position = $banner[position];

	// now let's num the rows and make sure we got a result..
$banner_del_count=@mysql_num_rows($get_banner_del);

	// here comes the sanity..
	if($banner_del_count != 1){
		include("../lang/errors.php");
		$msg="$LANG_bannerdel_error";
	}else{

	//locate the position...
	$posquery=mysql_query("select pos from bannerurls where id='$bid' limit 1");
	$get_pos=@mysql_fetch_array($posquery);
	$pos=$get_pos[pos];

	//delete the banner
	$delete=mysql_query("delete from bannerurls where id='$bid'");
	if($allow_upload=="Y"){
		$filename="$upload_path/$id-$pos";
		unlink($filename);
		}
	$msg="$LANG_delbanconf_success";
	}

	$page = new Page('../template/client_delbanconf.php');
	$page->replace_tags(array(
	'css' => "$css",
	'session' => "$session",
	'baseurl' => "$baseurl",
	'title' => "$exchangename - $LANG_delban_title",
	'msg' => "$msg",
	'footer' => '../footer.php',
	'menu' => 'client_menuing.php'));

			$page->output();

}
?>
