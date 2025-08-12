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
// let's check the image...
$imagestuff = @getimagesize($newbanurl);
$imagewidth = $imagestuff[0];
$imageheight = $imagestuff[1];
$error=0;
$bannerurl=$newbanurl;
			// Validate the Banner Width and Height
			if($imagewidth==''){
				$error = 1;
				$error_html .= "$LANG_addconf_err_noban<br><br>\n";
		}
			if($imagewidth != $bannerwidth){
				$error=1;
				$error_html .= "$LANG_addconf_err_width<br><br>\n";
		}
			if($imageheight != $bannerheight){
				$error=1;
				$error_html .= "$LANG_addconf_err_height<br><br>\n";
		}
		if($error=="1"){
					$msg.="<b>$LANG_addconf_err_explain:</b><p>";
					$msg.="$error_html";
				} else {
			mysql_query("update bannerurls set bannerurl='$bannerurl' where id='$bid'");
			mysql_query("update bannerstats set approved='0' where uid=$id");
					$msg= "$LANG_editbanner_message<p><img src=\"$newbanurl\">";
				}
			$page = new Page('../template/client_editbanner.php');
			$page->replace_tags(array(
			'css' => "$css",
			'session' => "$session",
			'baseurl' => "$baseurl",
			'title' => "$exchangename - $LANG_editbanner_title",
			'shorttitle' => "$LANG_editbanner_title",
			'msg' => "$msg",
			'back' => "$LANG_back",
			'footer' => '../footer.php',
			'menu' => 'client_menuing.php'));

			$page->output();
}
		
?>
