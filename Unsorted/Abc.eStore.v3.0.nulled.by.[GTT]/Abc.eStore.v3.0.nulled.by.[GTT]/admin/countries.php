<?php

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------

session_start();

include ("config.php");
include ("settings.inc.php");
$url = "index";
include_once ("header.inc.php");

//
// if session is not registered go to login page

if( !isset( $_SESSION["admin"] ) && !isset( $_SESSION["demo"] ) )
	abcPageExit("<Script language=\"javascript\">window.location=\"login.php\"</script>");

echo "<h2>".$lng[864]."</h2>";

if ( $_SESSION['demo'] )
	echo "<p><font color='red'>".$lng[782]."</font></p>";

// Delete country

if( isset ( $_GET['delete'] ) && !$_SESSION['demo'] ) {
		
	if ( $quer0 = mysql_query ("select country_id from ".$prefix."store_countries where parent_id='$_GET[delete]' or country_id='$_GET[delete]'") )
		while ( $res0 = mysql_fetch_assoc ($quer0) ) {
									
			if ( $quer = mysql_query ("select customer_id from ".$prefix."store_customer where country='$res0[country_id]'") )
				while ( $res = mysql_fetch_assoc ($quer) ) {
					
					mysql_query ("delete from ".$prefix."store_customer_discount_categories where customer_id='".$res['customer_id']."'");
					
				}
		
		mysql_query ("delete from ".$prefix."store_customer where country='$res0[country_id]'");
		
		}
	
	
	mysql_query ("delete from ".$prefix."store_countries where parent_id='$_GET[delete]' or country_id='$_GET[delete]'");
	mysql_query ("delete from ".$prefix."store_delivery where country_id='$_GET[delete]'");
	mysql_query ("delete from ".$prefix."store_tax where country_id='$_GET[delete]'");
	
}

//

$select = mysql_query ("select * from ".$prefix."store_countries where parent_id='0' order by country");
	
echo "<form action=\"countries.php\" method=\"post\" target=\"_self\">

<table border=\"1\" bordercolor=\"#e0e0e0\" cellpadding=\"4\" cellspacing=\"0\" width=\"95%\">

<tr bgcolor=\"#e6e6e6\">
<td align=\"center\" width=\"40%\"><b>".$lng[793]."</b></td>
<td align=\"center\" width=\"5%\"><b>".$lng[349]."</b></td>
<td align=\"center\" width=\"5%\"><b>".$lng[350]."</b></td>
</tr>";

while ($row = mysql_fetch_array($select)){
	
		$country = $row["country"];
		$country_id = $row["country_id"];
					
	echo "
	<tr>
	<td><b>$country</b></td>
	<td align=\"center\"><a href=\"edit_country.php?country_id=$country_id\">$lng[349]</a></td>
	<td align=\"center\"><a href=\"javascript:decision('$lng[874]','countries.php?delete=$country_id');\">$lng[350]</a></td>
	</tr>";


	// Regions
	
	$select_parent = mysql_query ("select * from ".$prefix."store_countries where parent_id='".$country_id."' order by country");
	
	while ($row_parent = mysql_fetch_array($select_parent)){
		
		$country_parent = $row_parent["country"];
		$country_id_parent = $row_parent["country_id"];
					
		echo "
		<tr>
		<td>$country -> $country_parent</td>
		<td align=\"center\"><a href=\"edit_country.php?country_id=$country_id_parent\">$lng[349]</a></td>
		<td align=\"center\"><a href=\"javascript:decision('$lng[875]','countries.php?delete=$country_id_parent');\">$lng[350]</a></td>
		</tr>";
		
	}
	
}
	
echo "</table>";

include("footer.inc.php");

?>