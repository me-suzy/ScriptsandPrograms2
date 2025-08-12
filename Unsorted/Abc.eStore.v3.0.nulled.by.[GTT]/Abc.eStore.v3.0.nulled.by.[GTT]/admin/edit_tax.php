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

echo "<h2>".$lng[898]."</h2>";

if( $_SERVER["REQUEST_METHOD"] == "POST" )
	extract( $_POST );

// if submit has not been clicked
if( !$submit )
{

if ( !isset ( $_GET['id'] ) )
	abcPageExit("<br><h4>".$lng[305]."</h4>$goback</p>");


$sql_cntr = "select * from ".$prefix."store_tax where id='$_GET[id]'";
$result_cntr = mysql_query($sql_cntr);
$delivery = mysql_fetch_assoc ($result_cntr);

echo "
<form action=\"edit_tax.php\" method=\"post\">

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
<td valign=\"top\"><b>".$lng[893].":</b></td>
<td valign=\"top\"><input class=\"textbox\" type=\"textbox\" name=\"tax\" value=\"$delivery[tax]\"></td>
</tr>

<tr>
<td valign=\"top\">&nbsp;</td>
<td valign=\"top\">
<input type=\"hidden\" name=\"id\" value=\"$delivery[id]\">
<INPUT class=\"submit\" TYPE=\"submit\" NAME=\"submit\" VALUE=\"".$lng[898]."\"></td>
</tr>
</table>

</form>";

} // if !$submit

// if submit has been clicked
if( $submit && !$_SESSION['demo'] )
{
		
	$sql_cntr = "select country_id from ".$prefix."store_tax where country_id='$country_id' and category_id='$category_id' and id!='$id'";
	$result_cntr = mysql_query($sql_cntr);
	if ( $num_cntr = mysql_fetch_assoc ($result_cntr) )
		abcPageExit("<h4>".$lng[902]."</h4>$goback");
	
	if( $tax == "" )
		abcPageExit("<h4>".$lng[895]."</h4>$goback");
	
	// if upload image was clicked
	
	$_country = mysql_escape_string($country);
	
	$sql_insert = 	"update ".$prefix."store_tax set
			`category_id` = '$category_id',
			`tax` = '$tax',
			`country_id` = '$country_id',
			`description` = '$description'
			where id='$id'
			";
	
	$result = mysql_query($sql_insert)
		or abcPageExit("Invalid query: " . mysql_error() . $goback);
			
echo "
<h3>".$lng[896].": $name ".$lng[899]."!</h3>
<p><a href=\"taxes.php\">".$lng[891]."</a></p>";
	
}

if( $submit && $_SESSION['demo'] ) {
	
	include('_guest_access.php');
	
}

include ("footer.inc.php");

?>
