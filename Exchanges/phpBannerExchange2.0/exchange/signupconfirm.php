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
include("css.php");
include("lang/errors.php");
include("lang/common.php");
if($use_gzhandler==1){
	ob_start("ob_gzhandler");
}

$db=mysql_connect("$dbhost","$dbuser","$dbpass");
mysql_select_db($dbname,$db);

session_start();
session_register(ref);

require_once('lib/template_class.php');
include("config.php");
include("lang/common.php");
$session=session_id();

$name=$_REQUEST['name'];
$email=$_REQUEST['email'];
$login=$_REQUEST['login'];
$pass=$_REQUEST['pass'];
$pass2=$_REQUEST['pass2'];
$newsletter=$_REQUEST['newsletter'];
$targeturl=$_REQUEST['targeturl'];
$category=$_REQUEST['category'];
$bannerurl=$_REQUEST['bannerurl'];
$coupon=$_REQUEST['coupon'];
$ref=$_SESSION['ref'];

	$err=0;

	if($_REQUEST['submit']){

		// check to see if the coupon is valid.
		if($_REQUEST[coupon]){
			$promo=mysql_query("select * from bannerpromos where promocode='$coupon' limit 1");
			$num=@mysql_num_rows($promo);
			$coup_array=@mysql_fetch_array($promo);

		// check to make sure the coupon is for 'mass credits'
		// because that's the only coupon type that will work with
		// a new sign up!

			$promotype=$coup_array[promotype];
			if(!$num or $promotype != '1'){
				$err = 1;
				$error_html .= "$LANG_err_nocoupon<br><br>\n";
			}
		}

		// Validate the Name
		if(strlen(trim($name)) < 2){
			$err = 1;
			$error_html .= "$LANG_err_nametooshort.<br><br>\n";
		}

		if(strlen(trim($name)) > 100){
			$err = 1;
			$error_html .= "$LANG_err_nametoolong.<br><br>\n";
		}

		// Validate the Login
		if(strlen(trim($login)) > 20){
			$err = 1;
			$error_html .= "$LANG_err_loginshort.<br><br>\n";
		}

		if(strlen(trim($login)) < 2){
			$err = 1;
			$error_html .= "$LANG_err_loginlong.<br><br>\n";
		}


		$check_login=mysql_query("select * from banneruser where login = '$login'");
		$get_login=@mysql_fetch_array($check_login);
		$exists=$get_login[login];
		$existsmail=$get_login[email];
		if($exists == $login){
			$err = 1;
			$error_html .= "$LANG_err_logininuse!<br><br>\n";
		}

		if($emailexists == $email){
			$err = 1;
			$error_html .= "$LANG_err_emailinuse!<br><br>\n";
		}

		if($allow_upload=='N'){
			if($imagestuff = @GetImageSize($bannerurl)){
				$imagewidth = $imagestuff[0];
				$imageheight = $imagestuff[1];

				// Validate the Banner Width and Height
				if($imagewidth != $bannerwidth){
					$err=1;
					$error_html .= "$LANG_err_badwidth<br><br>\n";
			}
				if($imageheight != $bannerheight){
					$err=1;
					$error_html .= "$LANG_err_badheight<br><br>\n";
			}
		}else{
			$err = 1;
			$error_html .= "$LANG_err_badimage<br><br>\n";
		}
	}

			// Validate the Email Address
			$regexp = "^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$";
			if (!eregi($regexp, $email)){
				$err = 1;
				$error_html .= "$LANG_err_email.<br><br>\n";
		}

		// Validate the Password
		if($pass != $pass2){
			$err = 1;
			$error_html .= "$LANG_err_passmismatch\n";
		}
		if(strlen(trim($pass)) < 4){
			$err = 1;
			$error_html .= "$LANG_err_passshort.<br><br>\n";
	    }

				if($err=="1"){
					$error="<br><b>$LANG_rejected</b></font><blockquote>".$error_html."</blockquote>\n [<a href=\"javascript:history.go(-1)\">$LANG_back</a>]";
					
					$page = new Page('template/admin_error.php');
					$page->replace_tags(array(
					'css' => "$css",
					'session' => "$session",
					'baseurl' => "$baseurl",
					'title' => "$exchangename - $LANG_signupwords",
					'shorttitle' => "$LANG_signconf_title",
					'error' => "$error",
					'menu' => 'common_menuing.php',
					'footer' => 'footer.php'));

					$page->output();

				} else {
					if($usemd5 == 'Y'){
						$encpass = md5($pass);
						$insert=mysql_query("insert into banneruser values ('','$login','$encpass','$name','$email','$newsletter')",$db);
					}else{
						$insert=mysql_query("insert into banneruser values ('','$login','$pass','$name','$email','$newsletter')",$db);
					}
			$timestamp=time();
			$get_id=mysql_query("select * from banneruser where login='$login'");
			$get_rows=mysql_fetch_array($get_id);
			$insert_stats=mysql_query("insert into bannerstats values ('$get_rows[id]','$category','0','$startcredits','0','0','0','0','0','','$timestamp')",$db);
			if($allow_upload=='N'){
			$insert_banner=mysql_query("insert into bannerurls values ('','$bannerurl','$targeturl','0','0','$get_rows[id]','0')");
			}
			if($ref =! '0' or ''){
				$insert_banner=mysql_query("insert into bannerrefs values ('','$ref','$get_rows[id]','0')");
			}
			if($_REQUEST[coupon]){
				$newcredits=$coup_array[promocredits];
				$promoid=$coup_array[promoid];
				$query=mysql_query("update bannerstats set credits=credits+'$newcredits' where uid='$get_rows[id]'");
				$logupdate=mysql_query("insert into bannerpromologs values('','$get_rows[id]','$promoid','$timestamp')");
				$coupon_msg=$LANG_coupon_added;
			}

if($allow_upload=="Y"){
	$uploading=$LANG_signup_uploadmsg;
}else{
	$uploading="";
}
$page = new Page('template/signconf.php');
$page->replace_tags(array(
'session' => "$session",
'css' => "$css",
'baseurl' => "$baseurl",
'title' => "$exchangename - $LANG_signconf_title",
'shorttitle' => "$LANG_signconf_title",
'thanks' => "$LANG_signup_thanks",
'info' => "$LANG_signupinfo",
'uploading' => "$uploading",
'coup_msg' => "$coupon_msg",
'menu' => 'common_menuing.php',
'footer' => 'footer.php'));

$page->output();

session_destroy();
					if($sendemail == "Y"){
					include("template/mail/mail_newadmin.php");
				mail($ownermail,$subject,$content,"From: $email");
						}else{
						}
					include("template/mail/mail_newuser.php");
				mail($email,$usrsubject,$usrcontent,"From: $ownermail");
				}
}
