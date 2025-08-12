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

$nowtime=time();
$rmtime=$nowtime+$expiretime;
$ip=$REMOTE_ADDR;
//remove old timestamps from the log
$remove=mysql_query("delete from bannerlogs where timestamp < '$nowtime'");

//check for dupe
$check=mysql_query("select timestamp from bannerlogs where uid='$siteid' AND ipaddr='$ip' AND page='$page'");
$getnum=@mysql_num_rows($check);

if($getnum > 0){
	$update_bid=mysql_query("update bannerstats set exposures=exposures+1 where uid=$pick");
}else{
$update_bid=mysql_query("update bannerstats set exposures=exposures+1 where uid=$pick");
$update_uid=mysql_query("update bannerstats set credits=credits+$steexp, histexposures=histexposures+1 where uid=$bid");
}

$logit=mysql_query("insert into bannerlogs values('$siteid','$ip','$page','$rmtime')");
?>
