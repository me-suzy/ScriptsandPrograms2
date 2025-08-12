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

		$admins=mysql_query("select id,adminuser from banneradmin");
		while($admin_array=mysql_fetch_array($admins)){		
			$id=$admin_array[id];		
			$adminuser=$admin_array[adminuser];	
			$output.= "<td width=75%><b>$adminuser</b></b></td><td width=25%><a href=\"deladmin.php?SID=$session&id=$id\">$LANG_delete</a></b></td></tr>";	
		}

			$page = new Page('../template/admin_addadmin.php');
			$page->replace_tags(array('session' => "$session",
				'baseurl' => "$baseurl",
				'css' => "$css",
				'title' => "$exchangename - $LANG_addadmin_title",
				'shorttitle' => "$LANG_addadmin_title",
				'heading' => "$LANG_addadmin_list",
				'msg' => "$LANG_login_error",
				'output' => "$output",
				'list' => "$LANG_addadmin_list",
				'newlogin' => "$LANG_addadmin_newlogin",
				'newpass1' => "$LANG_addadmin_pass1",
				'newpass2' => "$LANG_addadmin_pass2",
				'submit' => "$LANG_submit",
				'reset' => "$LANG_reset",
				'menu' => 'admin_menuing.php',
				'footer' => '../footer.php'));
			$page->output();
		}
	?>