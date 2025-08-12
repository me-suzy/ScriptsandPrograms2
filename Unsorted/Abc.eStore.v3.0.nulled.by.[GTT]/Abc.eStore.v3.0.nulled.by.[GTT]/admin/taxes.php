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

echo "<h2>".$lng[891]."</h2>";

if ( $_SESSION['demo'] )
	echo "<p><font color='red'>".$lng[782]."</font></p>";

// Delete country

if( isset ( $_GET['delete'] ) && !$_SESSION['demo'] ) {
	
	mysql_query ("delete from ".$prefix."store_tax where id='$_GET[delete]'");
}

//

$select = mysql_query ("select ".$prefix."store_tax.* from ".$prefix."store_tax,".$prefix."store_countries WHERE ".$prefix."store_tax.country_id=".$prefix."store_countries.country_id order by ".$prefix."store_tax.category_id, ".$prefix."store_countries.parent_id, ".$prefix."store_countries.country");
	
echo "<form action=\"deliveries.php\" method=\"post\" target=\"_self\">

<table border=\"1\" bordercolor=\"#e0e0e0\" cellpadding=\"4\" cellspacing=\"0\" width=\"95%\">

<tr bgcolor=\"#e6e6e6\">
<td align=\"center\"><b>".$lng[346]."</b></td>
<td align=\"center\"><b>".$lng[880]."</b></td>
<td align=\"center\"><b>".$lng[881]."</b></td>
<td align=\"center\"><b>".$lng[893].",%</b></td>
<td align=\"center\"><b>".$lng[349]."</b></td>
<td align=\"center\"><b>".$lng[350]."</b></td>
</tr>";

while ($row = mysql_fetch_array($select)){
	
	$country = GetNameById ( 'country', 'country_id', 'store_countries', $row['country_id'] );
	$parent_id = GetNameById ( 'parent_id', 'country_id', 'store_countries', $row['country_id'] );
	$parent = GetNameById ( 'country', 'country_id', 'store_countries', $parent_id );
	
	$category = abcGetCategoryPath( $row['category_id'] );
	
	if ( !empty ( $parent ) )
		$country = $parent . "->" . $country;
	
	echo "
	<tr>
	<td>$category</td>
	<td>$row[description]&nbsp;</td>
	<td>$country</td>
	<td>$row[tax]</td>
	<td align=\"center\"><a href=\"edit_tax.php?id=$row[id]\">$lng[349]</a></td>
	<td align=\"center\"><a href=\"javascript:decision('$lng[886]','taxes.php?delete=$row[id]');\">$lng[350]</a></td>
	</tr>";


}
	
echo "</table>";

include("footer.inc.php");

?>