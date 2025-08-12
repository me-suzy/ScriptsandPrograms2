<?php

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------

session_start();

include ("config.php");
include ("settings.inc.php");
include_once ("header.inc.php");

//
// if session is not registered go to login page

if( !isset( $_SESSION["admin"] ) && !isset( $_SESSION["demo"] ) )
	abcPageExit("<Script language=\"javascript\">window.location=\"login.php\"</script>");

echo "<h2>".$lng[890]."</h2>";

$sql_cats = "select count(*) as cnt from ".$prefix."store_tax";
$res_cat = mysql_fetch_assoc($sql_cats);
$total = count( $cats );

echo "<p><b>".$lng[892].": $total</b></p>";

if( $_SERVER["REQUEST_METHOD"] == "POST" )
	extract( $_POST );

// if submit has not been clicked
if( !$submit )
{

echo "
<form action=\"add_tax.php\" method=\"post\">

<table border=\"1\" bordercolor=\"#e6e6e6\" cellspacing=\"0\" cellpadding=\"4\" width=\"95%\">

<tr>
<td valign=\"top\"><b>".$lng[269].":</b></td>
<td valign=\"top\">\n";

	$cats = abcFetchCategoryList();
	
	// Drop down menu to build full category!
	print "\t\t\t\t\t<select name=\"category_id\">";

	foreach( $cats as $cat ){							
		
		$catname = $cat["path"];
		$cat_id_dd = $cat["cat_id"];
		echo"<option value=\"$cat_id_dd\">$catname</option>";
	}
    echo"</select>
</td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[880].":</b></td>
<td valign=\"top\"><textarea class=\"textbox\" name=\"description\"></textarea></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[881].":</b></td>
<td valign=\"top\">\n";

	$countries = GetRegions ();
			
	// Drop down menu to build full category!
	print "\t\t\t\t\t<select name=\"country_id\">";

	foreach( $countries as $cntr ){							
		
		echo"\t\t\t\t\t\t<option value=\"".$cntr['id']."\">".$cntr['name']."</option>\n";
	}
    echo"\t\t\t\t\t</select>
</td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[893].",%:</b></td>
<td valign=\"top\"><input class=\"textbox\" type=\"textbox\" name=\"tax\"></td>
</tr>

<tr>
<td valign=\"top\">&nbsp;</td>
<td valign=\"top\"><INPUT class=\"submit\" TYPE=\"submit\" NAME=\"submit\" VALUE=\"".$lng[890]."\"></td>
</tr>
</table>

</form>";

} // if !$submit

// if submit has been clicked
if( $submit && !$_SESSION['demo'] ) {
	
	// link back
	$goback="<br><a href=\"javascript:history.back()\">".$lng[305]."</a>";
		
	$sql_cntr = "select country_id from ".$prefix."store_tax where country_id='$country_id' and category_id='$category_id'";
	$result_cntr = mysql_query($sql_cntr);
	if ( $num_cntr = mysql_fetch_assoc ($result_cntr) )
		abcPageExit("<h4>".$lng[902]."</h4>$goback");
	
	if( $tax == "" || !is_numeric ( $tax ) )
		abcPageExit("<h4>".$lng[895]."</h4>$goback");
		
	// if upload image was clicked
	
	$_country = mysql_escape_string($country);
	
	$sql_insert = 	"insert into ".$prefix."store_tax set
			`category_id` = '$category_id',
			`tax` = '$tax',
			`country_id` = '$country_id',
			`description` = '$description'
			";
	
	$result = mysql_query($sql_insert)
		or abcPageExit("Invalid query: " . mysql_error() . $goback);
			
echo "
<h3>".$lng[896].": $name ".$lng[897]."!</h3>
<p><a href=\"add_tax.php\">".$lng[890]."</a></p>
<p><a href=\"taxes.php\">".$lng[891]."</a></p>";
		
	
}

if( $submit && $_SESSION['demo'] ) {
	
	include('_guest_access.php');
	
}

include ("footer.inc.php");

?>
