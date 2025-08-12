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

// we assume the admin knows what he's doing..
// so we won't check anything except to insure the username isn't taken.

// reassign all variables from superglobals:
$ulogin=$_REQUEST['ulogin'];
$approved=$_REQUEST['approved'];
$defaultacct=$_REQUEST['defaultacct'];
$newsletter=$_REQUEST['newsletter'];
$newpass=$_REQUEST['newpass'];
$name=$_REQUEST['name'];
$email=$_REQUEST['email'];
$category=$_REQUEST['category'];
$exposures=$_REQUEST['exposures'];
$startcredits=$_REQUEST['startcredits'];
$clicks=$_REQUEST['clicks'];
$siteclicks=$_REQUEST['siteclicks'];
$approved=$_REQUEST['approved'];
$defaultacct=$_REQUEST['defaultacct'];
$rawform=$_REQUEST['rawform'];

// check Username
$loginname=mysql_query("select id from banneruser where login='$ulogin'");
$numloginname=@mysql_num_rows($loginname);
if(!@mysql_num_rows($loginname)){
	$go="1";
}

	if($approved==Approved){
		$approved=1;
		$yesno="Yes";
	}else{
		$approved=0;
		$yesno="No";
	}
	if($defaultacct==defaultacct){	
		$defaultacct=1;
		$defaultyn="Yes";
	}else{
		$defaultacct=0;
		$defaultyn="No";
	}
	if($newsletter==newsletter){	
		$newsletter=1;
		$letteryn="Yes";
	}else{
		$newsletter=0;
		$letteryn="No";
	}

	$timestamp=time();
	if($usemd5="Y"){
		$fixedpass=md5($newpass);
		$rawformatted=htmlspecialchars($rawform);
		$update=mysql_query("insert into banneruser values('','$ulogin','$fixedpass','$name','$email','$newsletter')");
		$get_uid=mysql_query("select * from banneruser where login='$ulogin'");
		$get_userid=mysql_fetch_array($get_uid);
	$userid=$get_userid[id];
		$statsupdate=mysql_query("insert into bannerstats values('$userid','$category','$exposures','$startcredits','$clicks','$siteclicks','$approved','$defaultacct','0','$rawformatted','$timestamp')");
	}else{
		$rawformatted=htmlspecialchars($rawform);
		$update=mysql_query("insert into banneruser values('','$ulogin','$newpass','$name','$email','$newsletter')");
		$get_uid=mysql_query("select id from banneruser where login='$ulogin'");
		$get_userid=mysql_fetch_array($get_uid);
		$userid=$get_userid[id];
		$statsupdate=mysql_query("insert into bannerstats values('$userid','$category','$exposures','$startcredits','$clicks','$siteclicks','$approved','$defaultacct','0','$rawformatted','$timestamp')");
	}
	if($go=="1"){
		$page = new Page('../template/admin_addacctconfirm.php');
		$page->replace_tags(array(
			'css' => "$css",
			'session' => "$session",
			'baseurl' => "$baseurl",
			'uid' => "$uid",
			'title' => "$exchangename - $LANG_addacct_title",
			'shorttitle' => "$LANG_addacct_title",
			'addmsg' => "$LANG_addacct_msg",
			'button' => "$LANG_addacct_button",
			'msg' => "$LANG_login_error",
			'menu' => 'admin_menuing.php',
			'footer' => '../footer.php'));
		$page->output();
	}else{
		include("../lang/errors.php");
		$error.="$LANG_error_header<p>";
		$error.="$LANG_addacct_error";

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
		} 
	}
?>