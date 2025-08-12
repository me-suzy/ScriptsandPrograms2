<?
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

$db=mysql_connect("$dbhost","$dbuser","$dbpass");
mysql_select_db($dbname,$db);

$go=mysql_query("select * from bannerstats");
while($get_em=mysql_fetch_array($go)){
	$record=$get_em[url];
	$id=$get_em[uid];
	echo "Updating UID: $id ...<br>";
	$update=mysql_query("update bannerurls set targeturl='$record' where uid=$id");
	echo "Done..<br>";
}