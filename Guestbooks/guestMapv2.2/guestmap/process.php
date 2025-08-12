<?
//////////////////////////////////////////////////
//  guestMap v2.2    written by Chris Thompson  //
//                           www.thompsobd.com  //
//                                              //
//  If you use the code, leave the comments     //
//  in the code to give the creator due credit  //
//////////////////////////////////////////////////
class googleMap{
    function showMap($coord_array, $mapZoom, $centerpoint, $linecolor){
      	//we have to have an address
      	if (empty($coord_array)){
	  		echo "ERROR: You have not specified an address to map"; exit();
		}
		//$recommended = "<select name='recommended'><option value='0'>0</option></select>";
		$recommended = RECOMMENDED_DROPDOWN();
        //Still needs an error check to make sure that addresses have been found.  Test it by putting 642 Serene Ct Leland NC 28451 into the address array.  This is my current address but has not been mapped by google maps yet.
?>
			<script type="text/javascript">
			//<![CDATA[
	
			if (GBrowserIsCompatible()) {
				var icon = new GIcon();
					icon.image = "http://labs.google.com/ridefinder/images/mm_20_red.png"; //change this to be google default
					icon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
					icon.iconSize = new GSize(12, 20);
					icon.shadowSize = new GSize(22, 20);
					icon.iconAnchor = new GPoint(6, 20);
					icon.infoWindowAnchor = new GPoint(5, 1);
					
				var icon1 = new GIcon();
					icon1.image = "http://labs.google.com/ridefinder/images/mm_20_red.png"; //change this to be google default
					icon1.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
					icon1.iconSize = new GSize(0, 0);
					icon1.shadowSize = new GSize(0, 0);
					icon1.iconAnchor = new GPoint(6, 20);
					icon1.infoWindowAnchor = new GPoint(5, 1);
			
				var map = new GMap(document.getElementById("map"));
					map.addControl(new GSmallMapControl());
					map.addControl(new GMapTypeControl());
					map.centerAndZoom(new GPoint(<? echo $centerpoint; ?>), <? echo $mapZoom; ?>); //long,lat

<?
				$numMarkers = sizeof($coord_array);
				for ($i=0; $i<$numMarkers; $i++){
					$windowhtml = "".$coord_array[$i]['name']."<br /><br />".$coord_array[$i]['comment']."";
?>						
						var point<? echo $i; ?> = new GPoint(<? echo $coord_array[$i]['long']; ?>, <? echo $coord_array[$i]['lat']; ?>);
						var marker<? echo $i; ?> = new GMarker(point<? echo $i; ?>, icon);
						map.addOverlay(marker<? echo $i; ?>);
						GEvent.addListener(marker<? echo $i; ?>, 'click', function() {marker<? echo $i; ?>.openInfoWindowHtml("<? echo $windowhtml; ?>");});
<?
					/* Adds lines for who recommended them */
					$r = $coord_array[$i]['recommended'];
					if(!empty($r)) {
						$p++;
						$recommendedquery="SELECT * 
										   FROM guestmap
										   WHERE guestmap_ID = '$r';";	
						$recommendedresult = mysql_query($recommendedquery);if(!$recommendedresult) {echo "Doh!  Error getting recommended points from database."; exit;}
						$recommend_row = mysql_fetch_object($recommendedresult);
?>
							var polyline<? echo $p; ?> = new GPolyline([point<? echo $i; ?>,new GPoint(<? echo $recommend_row->guestmap_long; ?>,<? echo $recommend_row->guestmap_lat; ?>)],"<? echo $linecolor; ?>", 1, 0);
							map.addOverlay(polyline<? echo $p; ?>);
<?
					}
					/* ------------------------------------ */
				}
?>
				GEvent.addListener(map, 'click', function(overlay, point) {//listens for a click on the map to add a new mark
					if (point) {
						//map.addOverlay(new GMarker(point));
						var markera = new GMarker(point, icon1);
						map.addOverlay(markera);
						var y = point.y;
						var x = point.x;
						markera.openInfoWindowHtml("<form name='form1' method='post' action='guestmap.php?x="+x+"&y="+y+"'><table><tr><td align='right'>Name:&nbsp;</td><td><input name='guestmap_name' type='text' size='8'></td></tr><tr><td align='right'>Email:&nbsp;</td><td><input name='guestmap_email' type='text' size='8'></td></tr><tr><td align='right'>Website:&nbsp;</td><td><input name='guestmap_website' type='text' size='8'></td></tr><tr><td align='right'>Who recommended you?</td><td><? echo $recommended; ?></td></tr><tr><td align='right'>Comment:&nbsp;</td><td><textarea name='guestmap_comment' cols='35' rows='6' wrap='VIRTUAL'></textarea></td></tr><tr><td></td><td><input name='submit' type='submit'value='Post Your Location'></td></tr></table></form>");
					}
				});
			}					
			//]]>
			</script>
<?
	}
}
?>