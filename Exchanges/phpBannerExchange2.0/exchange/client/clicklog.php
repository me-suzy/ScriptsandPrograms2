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

		$session=$_REQUEST['session'];
		$ban=$_REQUEST['bid'];

	$banner=mysql_query("select * from bannerurls where id='$ban'");
	$get_banner=mysql_fetch_array($banner);
	$targeturl=$get_banner[targeturl];
	$bannerurl=$get_banner[bannerurl];

	$clicklog=mysql_query("select * from bannerclicklog where siteid='$id' order by time desc");
	$getnum=@mysql_num_rows($clicklog);
		if($getnum > '0' AND $getnum =! ''){
			while($get_clicklog=mysql_fetch_array($clicklog)){
				$date=date("m/d/Y H:m:s", $get_clicklog[time]);
			$fromdata.="<tr><td> $date</td><td> $get_clicklog[ip]</td></tr>";
			}
		}else{
			$fromdata="<tr><td colspan=\"3\" class=\"tablebodycenter\">$LANG_noclicks</td></tr>";
		}

	$clicklog=mysql_query("select * from bannerclicklog where clickedtosite='$id' order by time desc");
	$banner=mysql_query("select * from bannerurls where id='$ban'");
	$get_banner=mysql_fetch_array($banner);
	$getnum=@mysql_num_rows($clicklog);
		if($getnum > '0' AND $getnum =! ''){
			if($date_format=="0"){
				$datetimeformat="m/d/Y H:m:s";
			}else{
				$datetimeformat="d/m/Y H:m:s";
			}
				while($get_clicklog=@mysql_fetch_array($clicklog)){
					$date=date("$datetimeformat", $get_clicklog[time]);
					$todata.="<tr><td class=\"tablebodycenter\"> $date</td><td class=\"tablebodycenter\"> $get_clicklog[ip]</td></tr>";
				}
		}else{
			$todata="<tr><td colspan=\"3\" class=\"tablebodycenter\">$LANG_noclicks</td></tr>";
		}

	
		$page = new Page('../template/client_clicklog.php');
		$page->replace_tags(array(
		'css' => "$css",
		'session' => "$session",
		'baseurl' => "$baseurl",
		'title' => "$exchangename - $LANG_clicklog",
		'shorttitle' => "$LANG_clicklog",
		'data1' => "$fromdata",
		'data2' => "$todata",
		'from' => "$LANG_clicklog_from",
		'to' => "$LANG_clicklog_to",
		'ip' => "$LANG_clicklog_ip",
		'date' => "$LANG_clicklog_date",
		'back' => "$LANG_back1",
		'url' => "$targeturl",
		'bannerurl' => "$bannerurl",
		'menu' => 'client_menuing.php',
		'footer' => '../footer.php'));
		$page->output();
}
	?>

