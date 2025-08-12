<?
//////////////////////////////////////////////////
//  guestMap v2.2    written by Chris Thompson  //
//                           www.thompsobd.com  //
//                                              //
//  If you use the code, leave the comments     //
//  in the code to give the creator due credit  //
//////////////////////////////////////////////////
include('guestmap/config.inc.php');
include ('guestmap/process.php');
	$mWidth = 580;
	$mHeight = 450;
	$mapZoom = 15;
	$centerpoint = "-11.25,22.59372606392931";
	$linecolor = "#FF0000";

$guestmap_name = $_POST['guestmap_name'];
$guestmap_comment = $_POST['guestmap_comment'];
$guestmap_email = $_POST['guestmap_email'];
$guestmap_website = $_POST['guestmap_website'];
$guestmap_recommended = $_POST['guestmap_recommended'];
$x = $_GET['x'];
$y = $_GET['y'];

if(!empty($guestmap_name)) {
	$guestmapip = $_SERVER["REMOTE_ADDR"];
	$month = date(n);
	$day = date(j);
	$year = date(Y);
	$g = date(g)+1;
	$time = date($g.":".i." ".a);
	$checklocationquery="SELECT guestmap_lat, guestmap_long
						 FROM guestmap;";
	$checklocationresult=mysql_query($checklocationquery);
	$guestmap_check = mysql_fetch_object($checklocationresult);
	if($guestmap_check->guestmap_long == $x && $guestmap_check->guestmap_lat == $y) {
			
	}
	else {
		$insertquery="INSERT INTO guestmap
					  (guestmap_long, guestmap_lat, guestmap_name, guestmap_comment, guestmap_email, guestmap_website, guestmap_ip, guestmap_month, guestmap_day, guestmap_year, guestmap_time, guestmap_recommended) 
					  VALUES ('$x', '$y', '$guestmap_name', '$guestmap_comment', '$guestmap_email', '$guestmap_website', '$guestmapip', '$month', '$day', '$year', '$time', '$guestmap_recommended');";
		$insertresult = mysql_query($insertquery);if(!$insertresult) {echo "Doh!  Error inserting your location into database."; exit;}
	}		
}
$query="SELECT * 
		FROM guestmap;";	
$result = mysql_query($query);if(!$result) {echo "Doh!  Error getting locations from database."; exit;}
$a = 0;
while ($guestmap_row = mysql_fetch_object($result)) {
	$coord_array[$a]['long'] = $guestmap_row->guestmap_long;
	$coord_array[$a]['lat'] = $guestmap_row->guestmap_lat;
	$coord_array[$a]['name'] = $guestmap_row->guestmap_name;
	$coord_array[$a]['comment'] = $guestmap_row->guestmap_comment;
	$coord_array[$a]['recommended'] = $guestmap_row->guestmap_recommended;
	$a++;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsof­t-com:vml">
<head>
<style type="text/css"> 
    v\:* { 
      behavior:url(#default#VML); 
    } 
</style> 
<script src="http://maps.google.com/maps?file=api&v=1&key=" type="text/javascript"></script>
<title>guestMap v2</title>
</head>

<body>
<div id="map" style="width: <? echo"$mWidth"; ?>px; height: <? echo"$mHeight"; ?>px; color: #000000;"></div>
				<? //create an instance of the mapping class
					
					$myGuestMap = new googleMap;
					
					//this is the size of the map //zoom lvl
					//this line is what actually displays the map and all that good junk...
					$myGuestMap->showMap($coord_array, $mapZoom, $centerpoint, $linecolor);
				?>
</body>
</html>