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
			
		$template=$_REQUEST['template'];
		$session=$_REQUEST['SID'];

		// build the list of templates...	
		// associate template filenames with a description in our libraries..but	
		// only if there's a need to..	
		
		$path=realpath("../template");
		$location = "$path/$template";	
		$handle = opendir($path);	
		while ($file = readdir($handle)) {	
			if (($file != ".") && ($file != "..") && ($file != ".htaccess") && $file != "css" && $file != "mail"){	
				$filelist[] = $file;	
			}	
		}	
		
		closedir($handle);	
		asort($filelist);
		while (list ($key, $val) = each ($filelist)){  
			$list.= "<option value=\"$val\"";  
			if ($template == $val) {     
				$list.= " selected";    
			}    
				$list.= ">$val</option>";   
		}	
				
		//make the output of the valid tags look pretty...	
		$valid_tags_formatted=ereg_replace('}{', '} {', $valid_tags);
		$file_template=implode('', file("$location"));	
		$file_template=htmlspecialchars($file_template);
		
		$page = new Page('../template/admin_templates.php');
		$page->replace_tags(array(
			'css' => "$css",
			'session1' => "$session",
			'baseurl1' => "$baseurl",
			'title1' => "$LANG_menu_templates",
			'filewrite' => "$filewritemsg",
			'msg1' => "$LANG_templates_message",
			'template1' => "$LANG_template_box",
			'list1' => "$list",
			'selected1' => "$selected",
			'template_var1' => "$template",
			'template_name1' => "$template",
			'template_data1' => "$file_template",
			'warning1' => "$LANG_templates_warning",
			'valid_tags1' => "$LANG_valid_tags $valid_tags_formatted",
			'submit1' => "$LANG_submit",
			'reset1' => "$LANG_reset",
			'preview1' => "$LANG_preview",
			'menu1' => 'admin_menuing.php',
			'footer1' => '../footer.php'))
			;$page->output();
		}
?>