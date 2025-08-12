<?php

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------

session_start();

include ("config.php");
include ("settings.inc.php");
include_once ("header.inc.php");

// link back
$goback="<br><a href=\"javascript:history.back()\">$lng[342]</a>";

//
// if session is not registered go to login page

if( !isset( $_SESSION["admin"] ) && !isset( $_SESSION["demo"] ) )
	abcPageExit("<Script language=\"javascript\">window.location=\"login.php\"</script>");

echo "<h2>".$lng[887]."</h2>";

if( $_SERVER["REQUEST_METHOD"] == "POST" )
	extract( $_POST );

// if submit has not been clicked
if( !$submit )
{

if ( !isset ( $_GET['id'] ) )
	abcPageExit("<br><h4>".$lng[305]."</h4>$goback</p>");


$sql_cntr = "select * from ".$prefix."store_delivery where id='$_GET[id]'";
$result_cntr = mysql_query($sql_cntr);
$delivery = mysql_fetch_assoc ($result_cntr);
	
echo "
<form action=\"edit_delivery.php\" method=\"post\">

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
				
		echo"<option value=\"$cat_id_dd\"";
		
		if ( $cat_id_dd == $delivery['category_id'] )
			echo "selected";
		
		echo ">$catname</option>";
	}
    echo"</select>
</td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[880].":</b></td>
<td valign=\"top\"><textarea class=\"textbox\" name=\"description\">$delivery[description]</textarea></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[881].":</b></td>
<td valign=\"top\">\n";

	$countries = GetRegions ();
			
	// Drop down menu to build full category!
	print "\t\t\t\t\t<select name=\"country_id\">";

	foreach( $countries as $cntr ){							
		
		echo"\t\t\t\t\t\t<option value=\"".$cntr['id']."\"";
		
		if ( $cntr['id'] == $delivery['country_id'] )
			echo "selected";
		
		echo ">".$cntr['name']."</option>\n";
	}
    echo"\t\t\t\t\t</select>
</td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[882].",$currency:</b></td>
<td valign=\"top\"><input class=\"textbox\" type=\"textbox\" name=\"price\" value=\"$delivery[price]\"></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[903].",$currency:</b></td>
<td valign=\"top\"><input class=\"textbox\" type=\"textbox\" name=\"item_price\" value=\"$delivery[item_price]\"></td>
</tr>

<tr>
<td valign=\"top\">&nbsp;</td>
<td valign=\"top\">
<input type=\"hidden\" name=\"id\" value=\"$delivery[id]\">
<INPUT class=\"submit\" TYPE=\"submit\" NAME=\"submit\" VALUE=\"".$lng[887]."\"></td>
</tr>
</table>

</form>";

} // if !$submit

// if submit has been clicked
if( $submit && !$_SESSION['demo'] )
{
	
	// link back
	$goback="<br><a href=\"javascript:history.back()\">".$lng[305]."</a>";
		
	$sql_cntr = "select country_id from ".$prefix."store_delivery where country_id='$country_id' and category_id='$category_id' and id!='$id'";
	$result_cntr = mysql_query($sql_cntr);
	if ( $num_cntr = mysql_fetch_assoc ($result_cntr) )
		abcPageExit("<h4>".$lng[900]."</h4>$goback");
	
	if ( $price == "" || !is_numeric ( $price ) || $item_price == "" || !is_numeric ( $item_price ) )
		abcPageExit("<h4>".$lng[901]."</h4>$goback");
	
	// if upload image was clicked
	
	$_country = mysql_escape_string($country);
	
	$sql_insert = 	"update ".$prefix."store_delivery set
			`category_id` = '$category_id',
			`price` = '$price',
			`item_price` = '$item_price',
			`country_id` = '$country_id',
			`description` = '$description'
			where id='$id'
			";
	
	$result = mysql_query($sql_insert)
		or abcPageExit("Invalid query: " . mysql_error() . $goback);
			
echo "
<h3>".$lng[888].": $name ".$lng[889]."!</h3>
<p><a href=\"deliveries.php\">".$lng[877]."</a></p>";
	
}

if( $submit && $_SESSION['demo'] ) {
	
	include('_guest_access.php');
	
}

include ("footer.inc.php");

?>
