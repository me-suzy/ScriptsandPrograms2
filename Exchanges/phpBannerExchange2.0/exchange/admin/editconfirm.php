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
// Define variables from globals..
$ulogin=$_REQUEST['ulogin'];
$newpass=$_REQUEST['newpass'];
$name=$_REQUEST['name'];
$email=$_REQUEST['email'];
$newsletter=$_REQUEST['newsletter'];
$uid=$_REQUEST['uid'];
$exposures=$_REQUEST['exposures'];
$credits=$_REQUEST['credits'];
$clicks=$_REQUEST['clicks'];
$siteclicks=$_REQUEST['siteclicks'];
$approved=$_REQUEST['approved'];
$defaultacct=$_REQUEST['defaultacct'];
$raw=$_REQUEST['rawform'];
$category=$_REQUEST['category'];
$ref=$_REQUEST['ref'];

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

// we need to verify that the password did not change.
// if it did not and we use md5, we coud fsck up the
// user's login.
	if($usemd5==Y){
$pass_query=mysql_query("select * from banneruser where id='$uid'");
$get_pass=mysql_fetch_array($pass_query);
$upass=$get_pass[pass];
	if($upass != $newpass){
		$fixedpass = md5($newpass);
	}else{
		$fixedpass = $newpass;
	}
	}else{
		$fixedpass = $newpass;
	}
$raw_query=mysql_query("select raw from banneruser where id='$uid'");
$get_raw=@mysql_fetch_array($raw_query);
$raw=$get_raw[raw];
if($rawform == ''){
	$rawformatted=htmlspecialchars($raw);
	$update=mysql_query("update banneruser set login='$ulogin',pass='$fixedpass',name='$name',email='$email',newsletter='$newsletter' where id='$uid'");
	$statsupdate=mysql_query("update bannerstats set exposures='$exposures',credits='$credits',clicks='$clicks',siteclicks=$siteclicks,approved='$approved',defaultacct='$defaultacct',raw='$raw',category='$category' where uid='$uid'");
}else{
	$rawformatted=htmlspecialchars($rawform);
	$update=mysql_query("update banneruser set login='$ulogin',pass='$fixedpass',name='$name',email='$email',newsletter='$newsletter' where id='$uid'");
	$statsupdate=mysql_query("update bannerstats set category='category',exposures='$exposures',credits='$credits',clicks='$clicks',siteclicks=$siteclicks,approved='$approved',defaultacct='$defaultacct',raw='$rawform' category='$category' where uid='$uid'");
}
// check to see if this account was referred...
$referred=mysql_query("select * from bannerrefs where refid='$uid' and given='0'");
$count=@mysql_num_rows($referred);
	$get_referred=mysql_fetch_array($referred);
	$givee=$get_referred[uid];
	
// reset the flag and pay the referral bounty..
	$paidout=mysql_query("update bannerrefs set given='1' where uid='$givee'");
	$give_credits=mysql_query("update bannerstats set credits=credits+$referral_bounty where uid='$givee'");

if($ref=='listall'){
$backurl="listall.php?SID=";
$successmsg="$LANG_editconf_msg";
}else{
$backurl="validate.php?SID=";
$successmsg="$LANG_valconf_msg";
}


$page = new Page('../template/admin_editconf.php');
$page->replace_tags(array(
'css' => "$css",
'session' => "$session",
'baseurl' => "$baseurl",
'title' => "$exchangename - $LANG_edit_heading",
'shorttitle' => "$LANG_edit_heading",
'message' => "$successmsg",
'back' => "$LANG_back",
'backurl' => "$backurl",
'menu' => 'admin_menuing.php',
'footer' => '../footer.php'));

$page->output();
}

?>
