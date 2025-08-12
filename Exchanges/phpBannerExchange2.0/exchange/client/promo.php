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

if($use_gzhandler==1){
ob_start("ob_gzhandler");
}

require_once('../lib/template_class.php');
include("../lang/client.php");

// Begin login stuff
$db=mysql_connect("$dbhost","$dbuser","$dbpass");
mysql_select_db($dbname,$db);
$result = mysql_query("select * from banneruser where login='$login' AND pass='$pass'");
$get_userinfo=@mysql_fetch_array($result);
$id=$get_userinfo[id];
$login=$get_userinfo[login];
$pass=$get_userinfo[pass];

session_start();
$session=session_id();
$login=$_SESSION['login'];
$pass=$_SESSION['pass'];
$id=$_SESSION['id'];

if($login=="" AND $pass=="" OR $pass=="") {
	$page = new Page('../template/client_login_error.php');	
	$page->replace_tags(array(	
		'css' => "$css",
		'session' => "$session",	
		'baseurl' => "$baseurl",	
		'title' => "$exchangename - $LANG_login_error_title",	
		'shorttitle' => "$LANG_login_error_title",	
		'msg' => "$LANG_login_error",	
		'footer' => '../footer.php'));	
	$page->output();	
	session_destroy();
}else{

if($_REQUEST[submit]){
	$update='1';
	$promocode=$_REQUEST[promocode];
	$query=mysql_query("select * from bannerpromos where promocode='$promocode' limit 1");
	$num=@mysql_num_rows($query);
	$get_info=mysql_fetch_array($query);

	if($num== '1'){

		if($get_info[promotype] != '1'){
			$output=$LANG_coupon_clntwrongtype;
			$update='0';
		}
		if($get_info[promousertype] == '0'){
			$output=$LANG_coupon_userwrongtype;
			$update='0';
		}

		$checkquery=mysql_query("select * from bannerpromologs where uid='$id' and promoid='$get_info[promoid]' order by usedate desc");
		$usenum=@mysql_num_rows($checkquery);
		$checklastuse=mysql_fetch_array($checkquery);

			if($usenum){
				if($get_info[promoreuse] == '1'){
					$reuse=$get_info[promoreuseint] * 86400; // 86400=24 hours in seconds
					$eligible=$reuse + $checklastuse[usedate];
					$elig_format=date("d F Y", $eligible);
					$eligibledate=strtotime("$elig_format");
			
					if($eligibledate > time()){
						include("../lang/errors.php");
						$output="$LANG_coupon_noreuseyet <b>$elig_format</b>.";
						$update='0';
					}else{
						$update='1';
					}

				}else{
					include("../lang/errors.php");
					$output="$LANG_coupon_noreuse";
					$update='0';
				}
			}
		}else{
			include("../lang/errors.php");
			$output=$LANG_coupon_nocoup;
			$update="0";
		}		
	}

	$promoid=$get_info[promoid];

	if($update =="1"){
		$timestamp=time();
		mysql_query("insert into bannerpromologs values('','$id','$promoid','$timestamp')");
		mysql_query("update bannerstats set credits=credits+$get_info[promocredits] where uid='$id'");
		$output="$LANG_coupon_success $get_info[promocredits] $LANG_coupon_success2";
	}

	$page = new Page('../template/client_promo.php');
	$page->replace_tags(array(
	'css' => "$css",
	'session' => "$session",
	'baseurl' => "$baseurl",
	'title' => "$exchangename - $LANG_coupon_menuitem",
	'bannerurl' => "$bannerurl",
	'msg' => "$LANG_coupon_instructions",
	'output' => "$output",
	'submit' => "$LANG_submit",
	'footer' => '../footer.php',
	'menu' => 'client_menuing.php'));

	$page->output();
}
?>