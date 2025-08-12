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
$catid=$_REQUEST['catid'];
if(!$catid){
$pending = mysql_query("select banneruser.id, banneruser.login from banneruser left join bannerstats on banneruser.id=bannerstats.uid where defaultacct='1' and approved='1' order by login asc");
}else{
	$pending = mysql_query("select banneruser.id, banneruser.login from banneruser left join bannerstats on banneruser.id=bannerstats.uid where defaultacct='1' and approved='1' and category='$catid' order by login asc");
}
	$found = 0;
	while ($get_rows=@mysql_fetch_array($pending)){
	$total_found=mysql_num_rows($pending);
	$found=1;
	$uid=$get_rows[id];
	$ulogin=$get_rows[login];
	$output_default.="<p><b><a href=\"edit.php?SID=$session&uid=$uid&ref=listall\">$ulogin</a></b><br>";
}
	if($found == 0){
		$output_default="$LANG_listall_nodef";
	} else {
			if($total_found == 1){
	$totaldef_html="<br>$total_found $LANG_listall_def_sing";
		}else{
	$total_html="<br>$total_found $LANG_listall_def_plur";
	}
	}

//let's list all the accounts
if(!$catid){
$pending = mysql_query("select banneruser.id, banneruser.login from banneruser left join bannerstats on banneruser.id=bannerstats.uid where defaultacct='0' and approved='1' order by login asc");
}else{
	$pending = mysql_query("select banneruser.id, banneruser.login from banneruser left join bannerstats on banneruser.id=bannerstats.uid where defaultacct='0' and approved='1' and category='$catid' order by login asc");
}
	$found = 0;
	while ($get_rows=@mysql_fetch_array($pending)){
	$total_found=mysql_num_rows($pending);
	$found=1;
	$uid=$get_rows[id];
	$ulogin=$get_rows[login];
	$output_normal.="<b><a href=\"edit.php?SID=$session&uid=$uid&ref=listall\">$ulogin</a></b><br>";
	}
	if($found == 0){
		$output_normal="$LANG_listall_nonorm";
	} else {
			if($total_found == 1){
	$totalnorm_html="<br>$total_found $LANG_listall_norm_sing";
		}else{
	$totalnorm_html="<br>$total_found $LANG_listall_norm_plur";
	}
	}
	}
$page = new Page('../template/admin_listall.php');
$page->replace_tags(array(
'css' => "$css",
'session' => "$session",
'baseurl' => "$baseurl",
'title' => "$exchangename - $LANG_listall_title",
'shorttitle' => "$LANG_listall_title",
'default_head' => "$LANG_listall_default",
'default_data' => "$output_default",
'total_default' => "$totaldef_html",
'normal_head' => "$LANG_listall_norm_head",
'normal_data' => "$output_normal",
'total_normal' => "$totalnorm_html",
'menu' => 'admin_menuing.php',
'footer' => '../footer.php'));

$page->output();
	?>