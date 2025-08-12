<?
$file_rev="041307";
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
include("lang/common.php");

if($use_gzhandler==1){
	ob_start("ob_gzhandler");
}

$uid=$_REQUEST['uid'];
$cat=$_REQUEST['catid'];
$uid = strip_tags($uid);
$uid = htmlentities($uid);

$cat = strip_tags($cat);
$cat = htmlentities($cat);

if(!$cat){
	$cat="0";
}

//if this is false, will return "Invalid Banner!" (see bottom)
if(ctype_digit($uid) and ctype_digit($cat)){
	$db=mysql_connect("$dbhost","$dbuser","$dbpass");
	mysql_select_db($dbname,$db);

	$status = mysql_query("select * from bannerconfig where name='exchangestate'");
	$get_status=mysql_fetch_array($status);
	$status=$get_status[data];
	if($status == '1'){
		//display default banner.
		$eligible=mysql_query("select * from bannerurls where uid='0' limit 1");
		$defaultbanner="1";
	}else{
		if($cat=="0" or !$cat){
			//display the banner + without category support.
			if($use_dbrand == 1){
				$eligible=mysql_query("select uid from bannerstats where approved='1' and credits >= '$steexp' and uid != '$uid' order by rand() limit 1");
			}else{
				$eligible=mysql_query("select uid from bannerstats where approved='1' and credits >= '$steexp' and uid != '$uid'");
			}
		}else{
			if($use_dbrand == 1){
				//display the banner + with category support.
				$eligible=mysql_query("select uid from bannerstats where approved='1' and credits >= '$steexp' and category = '$cat' and uid != '$uid' order by rand() limit 1");
			}else{
				$eligible=mysql_query("select uid from bannerstats where approved='1' and credits >= '$steexp' and category = '$cat' and uid != '$uid'");
			}
		}
		//check to see the number of banners found.
		$get_number=@mysql_num_rows($eligible);
		if($get_number == 0){
			if($use_dbrand == 1){
				$eligible=mysql_query("select uid from bannerstats where defaultacct='1' and uid != '$uid' and uid != '0' order by rand() limit 1");
			}else{
				$eligible=mysql_query("select uid from bannerstats where defaultacct='1' and uid != '$uid' and uid != '0'");
			}
		}
		$get_number=@mysql_num_rows($eligible);
		if($get_number == 0){
			//if none found, display the default account.
			$eligible=mysql_query("select bannerurl, targeturl from bannerurls where uid='0' limit 1");
			$defaultbanner="1";
		}
	}

	$find_num=@mysql_num_rows($eligible);

	//if we STILL can't find a banner, display an error.
	if($find_num == '0'){
		echo "You're getting this message because there's no default account or default banner set up! Set one up before proceeding!";
		die();
	}

	if($defaultbanner=="1"){
		$get_banner=mysql_fetch_array($eligible);
		$bannerurl=$get_banner[bannerurl];
		$bannerid=$get_banner[id];
		$update_uid=mysql_query("update bannerstats set credits=credits+$steexp,histexposures=histexposures+1 where uid='$uid'");
		$pick="0";
	}else{
		while($rand_rows = @mysql_fetch_array($eligible)){
			$id_array[] = $rand_rows[uid];
		}
	
		if($use_dbrand == 0){
			srand((double)microtime()*1000000); 
			@shuffle($id_array);
			srand((double)microtime()*1000000); 
			@shuffle($id_array);
		}
		
		$pick = $id_array[0];
		$takecred=mysql_query("update bannerstats set credits=credits-$banexp where uid='$pick'");

//anti-cheat
		if($anticheat=="cookies"){
			include("cookies.php");
		}

		if($anticheat=="DB"){
			include("dblog.php");
		}

		if($anticheat==""){
			$update_bid=mysql_query("update bannerstats set exposures=exposures+1 where uid='$pick'");
			$update_uid=mysql_query("update bannerstats set credits=credits+$steexp,histexposures=histexposures+1 where uid='$uid'");
		}

		if($use_dbrand == 1){
			$get_banner = mysql_query("select * from bannerurls where uid='$pick' order by rand() limit 1");
		}else{
			$get_banner = mysql_query("select * from bannerurls where uid='$pick'");
		}

		while($rand_ban = mysql_fetch_array($get_banner)){
			$ban_array[] = $rand_ban[id];
		}
	
		if($use_dbrand == 0){
			srand((double)microtime()*1000000); 
			@shuffle($ban_array);
			srand((double)microtime()*1000000); 
			@shuffle($ban_array);
		}

			$bannerid=$ban_array[0];
			$get_banner=mysql_query("select bannerurl from bannerurls where id='$bannerid'");
			$get_banner_url=mysql_fetch_array($get_banner);
			$update_bannerstats=mysql_query("update bannerurls set views=views+1 where id='$bannerid'");
			$raw_query=mysql_query("select raw from bannerstats where uid=$pick");
			$get_raw=@mysql_fetch_array($raw_query);
			$rawcode=$get_raw[raw];
		}
	if($rawcode != '0'){
		echo "$rawcode";
	}else{
	$bannerurl=$get_banner_url[bannerurl];
	}
?>
	<a href="<? echo "$baseurl"; ?>/click.php?uid=<? echo "$uid"; ?>&bid=<? echo "$pick"; ?>&ban=<? echo"$bannerid"; ?>" target="_blank"><img src="<? echo "$bannerurl"; ?>" border=0 width=<? echo "$bannerwidth"; ?> height=<? echo "$bannerheight"; ?>></a>
<?
}else{
	echo "Invalid Banner Code!";
}
?>
