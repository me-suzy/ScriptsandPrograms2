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

	$err='0';
$uid=$_REQUEST['uid'];

		$banners = mysql_query("select * from bannerurls where uid='$uid'");
			while ($get_banner_rows=@mysql_fetch_array($banners)){
			$total_found=@mysql_num_rows($banners);
			$pos=$get_banner_rows[pos];
 			$code.="<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td colspan=\"2\"><a href=\"$get_banner_rows[targeturl]\" target=_blank><img src=\"$get_banner_rows[bannerurl]\"></a>";
			$code.="<br><form action=\"banners.php?SID=$session&bid=$get_banner_rows[id]&uid=$uid&action=changeurls\" method=post>$LANG_bannerurl: <input class=\"formbox\" type=\"text\" value=\"$get_banner_rows[bannerurl]\" size=\"40\" name=\"bannerurl\"><br>&nbsp;$LANG_targeturl: <input class=\"formbox\" type=\"text\" value=\"$get_banner_rows[targeturl]\" size=\"40\" name=\"targeturl\"><input class=\"formbox\" type=\"hidden\" value=\"$pos\" name=\"pos\"> <input class=\"button\" type=\"submit\" value=\"$LANG_menu_target\"></form></td><td valign=\"bottom\"><form action=\"deletebanner.php?SID=$session&bid=$get_banner_rows[id]&uid=$uid\" method=post><input type=\"hidden\" value=\"$get_banner_rows[bannerurl]\" name=\"bannerurl\"> <input type=\"hidden\" value=\"$pos\" name=\"pos\"><input class=\"button\" type=\"submit\" value=\"$LANG_button_banner_del\"></form></td></tr>";
			$code.="<tr><td>Views: $get_banner_rows[views] Clicks: $get_banner_rows[clicks]</td></tr>";
 			$code.="</td></tr></table><hr>";

			}
		if($allow_upload=="Y"){
			$pos="0";
			if ($maxbanners == '0' OR $total_found < $maxbanners){
					if($total_found == 0){
						$found_num= "<center>$LANG_stats_nobanner</center>";
						$banner_form="<form enctype=\"multipart/form-data\" action=\"uploadbanner.php?SID=$session&uid=$uid\" method=\"post\"><INPUT TYPE=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"1000000\"><INPUT TYPE=\"hidden\" name=\"uid\" value=\"$uid\">$LANG_filename: <input class=\"formbox\" name=\"userfile\" type=\"file\"><br>$LANG_targeturl <input class=\"formbox\" name=\"target\" value=\"http://\" type=\"text\" size=\"40\"><br><input class=\"button\" type=\"submit\" value=\"  $LANG_stats_hdr_add  \">";
				} else {
					if($total_found==1){
						$found_num= "<center>".$total_found." $LANG_banner_found.</center>";
						$banner_form="<form enctype=\"multipart/form-data\" action=\"uploadbanner.php?SID=$session&uid=$uid\" method=\"post\"><INPUT TYPE=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"1000000\"><INPUT TYPE=\"hidden\" name=\"uid\" value=\"$uid\">$LANG_filename: <input class=\"formbox\" name=\"userfile\" type=\"file\"><br>$LANG_targeturl: <input class=\"formbox\" name=\"target\" value=\"http://\" type=\"text\" size=\"40\"><br><input class=\"button\" type=\"submit\" value=\"  $LANG_stats_hdr_add  \">";
					}else{
						$found_num= "<center>".$total_found." $LANG_banner_found.</center>";
						$banner_form="<form enctype=\"multipart/form-data\" action=\"uploadbanner.php?SID=$session&uid=$uid\" method=\"post\"><INPUT TYPE=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"1000000\"><INPUT TYPE=\"hidden\" name=\"uid\" value=\"$uid\">$LANG_filename: <input class=\"formbox\" name=\"userfile\" type=\"file\"><br>$LANG_targeturl: <input class=\"formbox\" name=\"target\" value=\"http://\" type=\"text\" size=\"40\"><br><input class=\"button\" type=\"submit\" value=\"  $LANG_stats_hdr_add  \">";
					}
				}
			}else{
				$found_num= "<center>".$total_found." $LANG_banner_found.</center>";
				$banner_form= "$LANG_maxbanners";
			}

		}else{
				if ($maxbanners == '0' OR $total_found < $maxbanners){
					if($total_found == 0){
						$found_num= "<center>$LANG_stats_nobanner</center>";
						$banner_form="<form method=\"post\" action=\"banners.php?SID=$session&uid=$uid&submitban=1\">$LANG_bannerurl: <input class=\"formbox\" type=\"text\" name=\"bannerurl\" size=\"40\" value=\"http://\"><br>$LANG_targeturl <input class=\"formbox\" name=\"target\" value=\"http://\" type=\"text\" size=\"40\"><BR><input class=\"button\" type=\"submit\" value=\"  $LANG_stats_hdr_add  \"></form>";
				} else {
					if($total_found==1){
						$found_num= "<center>".$total_found." $LANG_banner_found.</center>";
						$banner_form="<form method=\"post\" action=\"banners.php?SID=$session&uid=$uid&submitban=1\">$LANG_bannerurl: <input class=\"formbox\" type=\"text\" name=\"bannerurl\" size=\"40\" value=\"http://\"><br>$LANG_targeturl: <input class=\"formbox\" name=\"target\" value=\"http://\" type=\"text\" size=\"40\"><BR><input class=\"button\" type=\"submit\" value=\"  $LANG_stats_hdr_add  \"></form>";
				}else{
						$found_num= "<center>".$total_found." $LANG_banner_found.</center>";
						$banner_form="<form method=\"post\" action=\"banners.php?SID=$session&uid=$uid&submitban=1\">$LANG_bannerurl: <input class=\"formbox\" type=\"text\" name=\"bannerurl\" size=\"40\" value=\"http://\"><br>$LANG_targeturl: <input class=\"formbox\" name=\"target\" value=\"http://\" type=\"text\" size=\"40\"><BR><input class=\"button\" type=\"submit\" value=\"  $LANG_stats_hdr_add  \"></form>";
					}
				}
				}else{
				$found_num= "<center>".$total_found." $LANG_banner_found.</center>";
				$banner_form= "$LANG_maxbanners";
			}
		}

	if($_REQUEST[submitban] or $_REQUEST[action]=="changeurls"){
		$bannerurl=$_REQUEST[bannerurl];
		//do some basic checks..
		$imagewidth="0";
		$imageheight="0";
		$imagestuff = @GetImageSize($bannerurl);
		$imagewidth = $imagestuff[0];
		$imageheight = $imagestuff[1];
			// Validate the Banner Width and Height
			if($imagewidth==''){
				include("../lang/errors.php");
				$err = "1";
				$error_html .= "$LANG_err_badimage<br><br>\n";
			}else{
				if($imagewidth != $bannerwidth){
					include("../lang/errors.php");
					$err="1";
					$error_html .= "$LANG_err_badwidth<br><br>\n";
				}
				if($imageheight != $bannerheight){
					include("../lang/errors.php");
					$err="1";
					$error_html .= "$LANG_err_badheight<br><br>\n";
				}
			}
	if($err=="1"){
		$bannerurl=$_REQUEST[bannerurl];
			$error = "$LANG_error_header<p>$error_html $LANG_tryagain";
			$page = new Page('../template/admin_error.php');
			$page->replace_tags(array(
			'css' => "$css",
				'session' => "$session",
				'baseurl' => "$baseurl",
				'title' => "$exchangename - $LANG_error",
				'shorttitle' => "$LANG_error",
				'error' => "$error",
				'menu' => 'admin_menuing.php',
				'footer' => '../footer.php'));
			$page->output();

		} else {
			if($_REQUEST[action]=="changeurls"){
				mysql_query("update bannerurls set bannerurl='$_REQUEST[bannerurl]',targeturl='$_REQUEST[targeturl]' where id='$_REQUEST[bid]'");
			}
			if($_REQUEST[submitban]){
				mysql_query("insert into bannerurls values('','$_REQUEST[bannerurl]','$_REQUEST[target]','0','0','$uid','0')");
			}

			header("Location: $baseurl/admin/banners.php?SID=$session&uid=$uid");
		}
	}
if($err=='0'){
		$page = new Page('../template/admin_banners.php');
			$page->replace_tags(array(
				'css' => "$css",
				'session' => "$session",
				'uid' => "$uid",
				'baseurl' => "$baseurl",
				'title' => "$exchangename - $LANG_stats_banner_hdr",
				'shorttitle' => "$LANG_stats_banner_hdr",
				'instructions' => "$LANG_banner_instructions",
				'code' => "$code",
				'maxbanners' => "$maxbanners",
				'addbanner' => "$LANG_stats_hdr_add",
				'foundnum' => "$found_num",
				'banner_form' => "$banner_form",
				'back' => "$LANG_back",
				'msg' => "$msg",
				'footer' => '../footer.php',
				'menu' => 'admin_menuing.php'));
			$page->output();
	}
}
?>