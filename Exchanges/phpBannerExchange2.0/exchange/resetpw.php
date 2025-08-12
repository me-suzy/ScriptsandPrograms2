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

include("config.php");
include("css.php");
if($use_gzhandler==1){
ob_start("ob_gzhandler");
}

session_start();
session_register(ref);

require_once('lib/template_class.php');
include("config.php");
include("lang/common.php");
$session=session_id();

$db=mysql_connect("$dbhost","$dbuser","$dbpass");
mysql_select_db($dbname,$db);

$email=$_REQUEST['email'];

//check for an account
$get_info=mysql_query("select * from banneruser where email='$email'");
$check_exist=@mysql_fetch_array($get_info);
$num=@mysql_num_rows($get_info);
if($num < 1){
	include("lang/errors.php");
	$result=$LANG_lostpw_noacct;
}else{
$id=$check_exist[id];
$seed=mt_rand();
$string=md5($seed);
$newpw=substr("$string", 5, 8);

if($usemd5=="Y"){
	$encpw=md5($newpw);
	$update=mysql_query("update banneruser set pass='$encpw' where id='$id'");
}else{
$update=mysql_query("update banneruser set pass='$newpw' where id='$id'");
}
$result=$LANG_lostpw_success;
include("template/mail/mail_resetpw.php");
mail($email,$usrsubject,$usrcontent,"From: $ownermail");
}

$page = new Page('template/resetpw.php');
$page->replace_tags(array(
'css' => "$css",
'session' => "$session",
'baseurl' => "$baseurl",
'title' => "$exchangename - $LANG_lostpw_title",
'lostpw' => "$LANG_lostpw_title",
'result' => "$result",
'email' => "$email",
'menu' => 'common_menuing.php',
'footer' => 'footer.php'));

$page->output();

