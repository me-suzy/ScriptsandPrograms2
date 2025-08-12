<?
$file_rev="041306";
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

$css_var=$_REQUEST['css_var'];
$session=$_REQUEST['SID'];
$editresult=$_REQUEST['editresult'];
	
	if($_REQUEST[submit]){
		$newcss=$_REQUEST['csspick'];
		
		$path=realpath("../");
		$location = "$path/css.php";

		$newcssvar = '<?php'."\n\n";
		$newcssvar.= '$css = "' . $newcss .'";' . "\n\n";
		$newcssvar.= '?' . '>'; // Done this to prevent highlighting editors getting confused!

		if($open_file=@fopen($location, 'w+')){
			fwrite($open_file, $newcssvar);
			fclose($open_file);
			header("Location: editcss.php?SID=$session");
		}else{
			$err="1";
			include("../lang/errors.php");
			$error="$LANG_editcsstemplate_cannotwrite";
		}
	}

		$defaultoption="<option selected value=\"$css\">$css</option>";

		$dir = opendir("../template/css"); 
		while ($file_name = readdir($dir)){ 
			if (($file_name != ".") && ($file_name != "..") && ($file_name != ".htaccess") && ($file_name != "$css")){
				$file_list .= "<option value=\"$file_name\">$file_name</option>"; 	
			} 
		} 
		closedir($dir);

		if($css_var=='' or !$css_var){
			$editoption="<option selected value=\"$css\">$css</option>";
			$dir = opendir("../template/css"); 

			while ($file_name = readdir($dir)){ 
				if (($file_name != ".") && ($file_name != "..") && ($file_name != ".htaccess") && ($file_name != "$css")){
					$edit_list .= "<option value=\"$file_name\">$file_name</option>"; 	
				} 
			} 
		}else{
			$editoption="<option selected value=\"$css_var\">$css_var</option>";
			$dir = opendir("../template/css"); 

			while ($file_name = readdir($dir)){ 
				if (($file_name != ".") && ($file_name != "..") && ($file_name != ".htaccess") && ($file_name != "$css_var")){
					$edit_list .= "<option value=\"$file_name\">$file_name</option>"; 	
				} 
			}
		}
		closedir($dir);

	if($_REQUEST[edit_load] or $_REQUEST[css_var]){
		$path=realpath("../template/css");
		$location = "$path/$css_var";	

		//make the output of the valid tags look pretty...	
		$valid_tags_formatted=ereg_replace('}{', '} {', $valid_tags);
		$file_template=implode('', file("$location"));	
		$file_template=htmlspecialchars($file_template);
		$file_template=stripslashes($file_template);
	}

	if($err=="1"){
		$page = new Page('../template/admin_error.php');
		$page->replace_tags(array(
			'css' => "$css",
			'session' => "$session",
			'baseurl' => "$baseurl",
			'title' => "$exchangename - $LANG_menu_editcss",
			'error' => "$error",
			'menu' => 'admin_menuing.php',
			'footer' => '../footer.php'));
		$page->output();
	}else{

	$page = new Page('../template/admin_editcss.php');
	$page->replace_tags(array(
		'css' => "$css",
		'session' => "$session",
		'baseurl' => "$baseurl",
		'title' => "$exchangename - $LANG_menu_editcss",
		'instructions' => "$LANG_editcss_directionstop",
		'edithead' => "$LANG_editcss_edithead",
		'instructions1' => "$LANG_editcss_instructions1",
		'defaultoption' => "$defaultoption",
		'edit_css' => "$edit_css",
		'editoption' => "$editoption",
		'edit_list' => "$edit_list",
		'css_var' => "$css_var",
		'css_dump' => "$file_template",
		'loadbutton' => "$LANG_editcss_loadbutton",
		'filelist' => "$file_list",
		'submit' => "$LANG_submit",
		'reset' => "$LANG_reset",
		'menu' => 'admin_menuing.php',
		'footer' => '../footer.php'));
	$page->output();
	}
}
?>