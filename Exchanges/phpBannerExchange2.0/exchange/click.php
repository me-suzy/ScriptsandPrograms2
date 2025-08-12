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

// Historical click logging mod
// Submitted by Murder4Al - kobe @ publinet.be

include("config.php");
if($use_gzhandler==1){
	ob_start("ob_gzhandler");
}

$db=mysql_connect("$dbhost","$dbuser","$dbpass");
mysql_select_db($dbname,$db);

$bannerid = $_REQUEST['bid']; //Banner ID
$uid=$_REQUEST['uid'];
$ban=$_REQUEST['ban'];

if(ctype_digit($bannerid) and ctype_digit($uid) and ctype_digit($ban)){

	// strip tags for possible CSS exploit.
	$bannerid = strip_tags($bannerid);
	$bannerid = htmlentities($bannerid);
	$uid = strip_tags($uid);
	$uid = htmlentities($uid);
	$ban = strip_tags($ban);
	$ban = htmlentities($ban);

	$update_clicks=mysql_query("update bannerstats set clicks=clicks+1 where uid='$bannerid'");
	$update_clickfrom=mysql_query("update bannerstats set siteclicks=siteclicks+1 where uid='$uid'");
	$update_clickbanner=mysql_query("update bannerurls set clicks=clicks+1 where id='$ban'");

//click mod log update..
	if($log_clicks=="Y"){
		$nowtime=time();
		$ip=$_SERVER['REMOTE_ADDR'];
		$update_logs=mysql_query("insert into bannerclicklog values('','$uid','$bannerid','$ban','$ip','$page','$nowtime')");
	}

	$get_rows=mysql_query("select targeturl from bannerurls where id='$ban'");
	$get_url=@mysql_fetch_array($get_rows);
	$clickurl=$get_url[targeturl];

	header("Location: $clickurl");
	
}else{
	echo "Invalid Banner!";
}

?>