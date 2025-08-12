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
		//open the db directory and see if there's any files there..
		
		$dir = opendir("db/"); 
		$file_list = "<center><table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" width=50% class=windowbg><tr><td class=\"tablehead\" width=30%><b>$LANG_db_buname</b></td><td class=\"tablehead\" width=50%><b>$LANG_db_budate</b></td><td class=\"tablehead\" width=20%><b>$LANG_db_delete</b></td><td class=\"tablehead\"><b>$LANG_db_restore</b></td></tr>"; 
		
		while ($file_name = readdir($dir)){ 
			if (($file_name != ".") && ($file_name != "..") && ($file_name != ".htaccess")){	
				$file_time=date("F j, Y",$file_name);	
				$file_list .= "<td class=\"tablebody\"><a href=\"db/$file_name\" target=\"_blank\">$file_name</a></td><td class=\"tablebody\">$file_time</td><td class=\"tablebody\"><a href=\"rmbackup.php?SID=$session&file_name=$file_name\">$LANG_db_delete</a></td><td class=\"tablebody\"><a href=\"dbrestore.php?SID=$session&file_name=$file_name\">$LANG_db_restore</td></tr>"; 	
			} 
		} 
		
		$file_list .= "</ul>"; 
		closedir($dir);

		$page = new Page('../template/admin_dbtools.php');
		$page->replace_tags(array(
			'css' => "$css",
			'session' => "$session",
			'baseurl' => "$baseurl",
			'title' => "$exchangename - $LANG_db_title",
			'instructions' => "$LANG_db_instructions",
			'filelist' => "$file_list",
			'newbuset' => "$LANG_db_newbuset",
			'upload' => "$LANG_db_upload",
			'upload_button' => "$LANG_db_upload_button",
			'menu' => 'admin_menuing.php',
			'footer' => '../footer.php'));
		$page->output();
	}
?>