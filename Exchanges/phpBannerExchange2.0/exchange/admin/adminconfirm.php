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

		$session=$_REQUEST['session'];
		$newlogin=$_REQUEST['newlogin'];
		$pass1=$_REQUEST['pass1'];
		$pass2=$_REQUEST['pass2'];

include("../lang/errors.php");
		if(strlen($newlogin) > 20){		
			$err = 1;		
			$error_html .= "$LANG_adminconf_login_long.<br><br>\n";		
		}	

		if(strlen($newlogin) < 2){		
			$err = 1;		
			$error_html .= "$LANG_adminconf_login_short.<br><br>\n";		
		}	
		
		$check_login=mysql_query("select * from banneradmin where adminuser='$newlogin'");	
		$get_login=@mysql_num_rows($check_login);	
		if($get_login){		
			$err = 1;	
			$error_html .= "$LANG_adminconf_loginexist<br><br>\n";	
		}	
		
		if($pass1 != $pass2){		
			$err = 1;		
			$error_html .= "$LANG_adminconf_pw_mismatch!\n";	
		}		
		if(strlen($pass1) < 4){	
			$err = 1;	
			$error_html .= "$LANG_adminconf_pw_short.<br><br>\n";	
		}			

		if($err=="1"){
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

				if($usemd5 == Y){	
					$encpass = md5($pass1);		
					$insert=mysql_query("insert into banneradmin values ('','$newlogin','$encpass')",$db);
			}else{		
			$insert=mysql_query("insert into banneradmin values ('','$newlogin','$pass1')",$db);	
			}		

		header("Location: addadmin.php?SID=$session");		
		}
	}	

?>