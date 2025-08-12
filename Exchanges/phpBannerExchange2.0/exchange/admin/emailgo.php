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

		$cat=$_REQUEST['cat'];
		$override=$_REQUEST['override'];
		$subject=$_REQUEST['subject'];
		$body=$_REQUEST['body'];

		if($cat != '0'){
			if($override == Y){
				$msg.= "$LANG_emailgo_msg_all<br>";	
				$results=mysql_query("select banneruser.* from banneruser left join bannerstats on banneruser.id=bannerstats.uid where category='$cat'");
				}else{
					$msg.= "$LANG_emailgo_msg_only<br>";
					$results=mysql_query("select banneruser.* from banneruser left join bannerstats on banneruser.id=bannerstats.uid where category='$cat' and newsletter='1'");
					}
				}else{
					if($override == Y){	
						$msg.= "$LANG_emailgo_msg_all<br>";	
						$results=mysql_query("select * from banneruser");
					}else{
						$msg.= "$LANG_emailgo_msg_only<br>";
						$results=mysql_query("select * from banneruser where newsletter='1'");
						}
				}		
				
				while($get_stuff=mysql_fetch_array($results)){
					$email=$get_stuff[email];
					$name=$get_stuff[name];
					$login=$get_stuff[login];
					$id=$get_stuff[id];
			// we're replacing fools! Strip all the variables and line feeds..
			$search = array ("'<script[^>]*?>.*?</script>'si",	
				"'<[\/\!]*?[^<>]*?>'si",
				"'([\r\n])[\s]+'",
				"'&(quot|#34);'i",
				"'&(amp|#38);'i",
				"'&(lt|#60);'i",
				"'&(gt|#62);'i",
				"'&(nbsp|#160);'i",
				"'&(iexcl|#161);'i",
				"'&(cent|#162);'i",	
				"'&(pound|#163);'i",
				"'&(copy|#169);'i",	
				"'&#(\d+);'e");	

			$replace = array ("",
				"",
				"\\1",
				"\"",
				"&",
				"<",
				">",
				" ",
				chr(161),
				chr(162),
				chr(163),
				chr(169),
				"chr(\\1)");
			
			$text = preg_replace ($search, $replace, $body);
			// Fix line breaks..
			$body.="$removal";
			$body=nl2br($body); 
			//rid ourselves of the evil that is slashes!
			$body=stripslashes($body);
			$body=eregi_replace("%username%", "$name", "$body");
			$body=eregi_replace("%login%", "$login", "$body");
			$body=eregi_replace("%email%", "$email", "$body");
			$body=eregi_replace("%id%", "$id", "$body");
			$body=eregi_replace("%statstable%", "$baseurl", "$body");
			$status.= "$LANG_emailing: <b>$id, $email</b>";	

			/* To send HTML mail, you can set the Content-type header. */
			$headers  = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

			/* additional headers */
			$headers .= "From: $exchangename <$owneremail>\r\n";
			mail($email, $subject, $body, $headers);
			$status.= "..$LANG_done.<br>";	
			}
		$page = new Page('../template/admin_emailgo.php');
		$page->replace_tags(array(
			'css' => "$css",
			'session' => "$session",
			'baseurl' => "$baseurl",
			'title' => "$exchangename - $LANG_email_title",
			'shorttitle' => "$LANG_email_title",
			'msg' => "$msg",
			'status' => "$status",
			'back2' => "$LANG_back2",
			'menu' => 'admin_menuing.php',
			'footer' => '../footer.php'));
		$page->output();
	}	
?>