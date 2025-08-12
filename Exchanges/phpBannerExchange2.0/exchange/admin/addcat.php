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

		$catname=$_REQUEST['catname'];

		// Validate the Name	
		if(strlen(trim($catname)) < 2){		
			$err = 1;		
			$error_html .= "$LANG_addcat_tooshort!<br><br>\n";	
		}	

		if(strlen(trim($name)) > 50){		
			$err = 1;		
			$error_html .= "$LANG_addcat_toolong!<br><br>\n";
		}		
		
		$check_catname=mysql_query("select * from bannercats where catname = '$catname'");		$get_catname=@mysql_fetch_array($check_catname);	
		$exists=$get_catname[catname];	

		if($exists == $catname){		
			$err = 1;		
			$error_html .= "$LANG_addcat_exists!<br><br>\n";	
		}	
		if($err=="1"){
			include("../lang/errors.php");
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

			$insert=mysql_query("insert into bannercats values ('','$catname')",$db);	
			$msg="$LANG_addcat_added";	
			
		}
		
		header("Location: catmain.php?SID=$session");

		}
	?>