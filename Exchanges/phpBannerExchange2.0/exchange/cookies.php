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

	$cookieuid=$HTTP_COOKIE_VARS["cookieuid"];
	$cookiepage=$HTTP_COOKIE_VARS["cookiepage"];
	$time = mktime()+$expiretime;
	$date = date("l, d-M-y H:i:s", ($time));
		if($cookieuid != $uid OR $cookiepage != $page){
			header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
			setcookie("cookieuid",$siteid, time()+$expiretime);
			setcookie("cookiepage",$page, time()+$expiretime);
$update_bid=mysql_query("update bannerstats set exposures=exposures+1 where uid=$pick");
$update_uid=mysql_query("update bannerstats set credits=credits+$steexp, histexposures=histexposures+1 where uid=$bid");
		}else{
			header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
			setcookie("cookieuid",$siteid, time()+$expiretime);
			setcookie("cookiepage",$page, time()+$expiretime);
$update_bid=mysql_query("update bannerstats set exposures=exposures+1 where uid=$pick");
		}
?>