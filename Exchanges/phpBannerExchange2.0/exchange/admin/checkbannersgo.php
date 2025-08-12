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

// Begin loginstuff
if(!$db=@mysql_connect("$dbhost","$dbuser","$dbpass")){
	include("../lang/errors.php");
	$err="1";
	$error.="$LANG_error_header<p>";
	$error.="$LANG_error_mysqlconnect ";
	$error.=mysql_error();
}

@mysql_select_db($dbname,$db);

session_start();
header("Cache-control: private"); //IE 6 Fix 
$session=session_id(); 
$login = $_SESSION['login'];
$pass = $_SESSION['pass'];

$result = mysql_query("select * from banneradmin where adminuser='$login' AND adminpass='$pass'");
$get_userinfo=mysql_fetch_array($result);
$login=$get_userinfo[adminuser];
$pass=$get_userinfo[adminpass];

    if($login=="" AND $pass=="" OR $pass=="" OR $err=="1") {
		include("../lang/errors.php");
		$error.="$LANG_error_header<p>";
		$error.="$LANG_login_error";

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
$session=$_REQUEST['SID'];

$banners = mysql_query("select * from bannerurls");
$count=mysql_num_rows($banners);

while($get_banners=mysql_fetch_array($banners)){
$bannerurl=$get_banners[bannerurl];
$uid=$get_banners[uid];
$target=$get_banners[targeturl];
$imagestuff = @getimagesize($bannerurl);
$imagewidth = $imagestuff[0];
$imageheight = $imagestuff[1];
$statuslang="$LANG_status_OK";
$status="OK";
	if($imagewidth==''){
		$statuslang="<font color=\"#FF0000\">$LANG_status_broken!</font>";
		$status="BROKEN";
	}

	if($imagewidth != $bannerwidth){
		$statuslang="<font color=\"#FF0000\">$LANG_status_broken!</font>";
		$status="BROKEN";
	}
	
	if($imageheight != $bannerheight){
		$statuslang="<font color=\"#FF0000\">$LANG_status_broken!</font>";
		$status="BROKEN";
	}

	if(!@fopen($target, "r")){
		$statuslang="<font color=\"#FF0000\">$LANG_status_broken!</font>";
		$status="BROKEN";
	}

	if($status != "OK"){
		$change=mysql_query("update bannerstats set approved='0' where uid='$uid'");
	}else{
	}
	echo "<a href=\"$baseurl/admin/banners.php?SID=$session&uid=$uid\"><b>$get_banners[id]</b></a>: $bannerurl [<b>$statuslang</b>]<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>$LANG_targeturl:</b> <a href=\"$target\">$target</a> [<b>$status</b>]<br>";
}

	}
