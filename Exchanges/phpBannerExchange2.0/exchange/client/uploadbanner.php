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

$path="$upload_path/";
$target=$_REQUEST['target'];

$get_count=mysql_query("select * from bannerurls where uid='$id'");
	$found=@mysql_num_rows($get_count);

	if(!$found){
		$newcount=1;
		$newfile="$id-$newcount";
	}else{
		$newcount=$found+1;
		$newfile="$id-$newcount";
	}
		$filename="$path$newfile";
		$bannerurl="$banner_dir_url/$newfile";

		if(@move_uploaded_file($_FILES['userfile']['tmp_name'], $filename)){
//validate the image...
			$imagestuff = @getimagesize($filename);
			$imagewidth = $imagestuff[0];
			$imageheight = $imagestuff[1];
			$error=0;
			include("../lang/errors.php");
			
			if($filename==''){
				$error = 1;
				$error_html .= "$LANG_err_badimage<br><br>\n";
			}
			if($imagewidth==''){
				$error = 1;
				$error_html .= "$LANG_err_badimage<br><br>\n";
			}
			if($imagewidth != $bannerwidth){
				$error=1;
				$error_html .= "$LANG_err_badwidth<br><br>\n";
			}
			if($imageheight != $bannerheight){
				$error=1;
				$error_html .= "$LANG_err_badheight<br><br>\n";
			}
			if(filesize($filename) > $max_filesize){
				$error=1;
				$error_html .= "$LANG_err_filesize<br><br>\n";
			}

			if(!$get_url=@fopen($target,"r")){
				$error = 1;
				$error_html .= "$LANG_err_invalidurl<br><br>\n";
			}
				
				if($error=="1"){
					unlink($filename);
					$err="<b>$LANG_error_header</b><p>".$error_html."$LANG_tryagain";
					
					$page = new Page('../template/admin_error.php');
					$page->replace_tags(array(
						'css' => "$css",
						'session' => "$session",
						'baseurl' => "$baseurl",
						'title' => "$exchangename - $LANG_menu_banners",
						'shorttitle' => "$LANG_menu_banners",
						'error' => "$err",
						'menu' => "client_menuing.php",
						'footer' => '../footer.php'));
					$page->output();
				} else {

			mysql_query("insert into bannerurls values ('','$bannerurl','$target','0','0','$id','$newcount')");
 			if ($reqbanapproval == "Y") { //Only set appoved to false if said so in config
 				mysql_query("update bannerstats set approved='0' where uid=$id");
			}
			chmod($filename, 0644);
 			//Redirect to 'Your banners' Page
			header("Location: ".$baseurl."/client/banners.php?SID=$session");
	}
}

	}
	?>