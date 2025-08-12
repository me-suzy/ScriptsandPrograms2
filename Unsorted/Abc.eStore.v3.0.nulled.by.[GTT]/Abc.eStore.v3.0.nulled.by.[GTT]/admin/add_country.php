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

echo "<h2>".$lng[865]."</h2>";

$cats = abcFetchCountryList();
$total = count( $cats );

echo "<p><b>".$lng[867].": $total</b></p>";

if( $_SERVER["REQUEST_METHOD"] == "POST" )
	extract( $_POST );

// if submit has not been clicked
if( !$submit )
{

echo "
<form action=\"add_country.php\" method=\"post\">

<table border=\"1\" bordercolor=\"#e6e6e6\" cellspacing=\"0\" cellpadding=\"4\" width=\"95%\">
<tr>
<td valign=\"top\"><b>".$lng[866].":</b></td>
<td valign=\"top\"><input class=\"textbox\" type=\"textbox\" name=\"country\"></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[868].":</b></td>
<td valign=\"top\">\n";

	$countries = abcFetchCountryList();
			
	// Drop down menu to build full category!
	print "\t\t\t\t\t<select name=\"parent_id\"><option value=\"0\"></option>\n";

	foreach( $countries as $cntr )
	{							
		
		echo"\t\t\t\t\t\t<option value=\"".$cntr['country_id']."\">".$cntr['country']."</option>\n";
	}
    echo"\t\t\t\t\t</select>
</td>
</tr>

<tr>
<td valign=\"top\">&nbsp;</td>
<td valign=\"top\"><INPUT class=\"submit\" TYPE=\"submit\" NAME=\"submit\" VALUE=\"".$lng[865]."\"></td>
</tr>
</table>

</form>";

} // if !$submit

// if submit has been clicked
if( $submit && !$_SESSION['demo'] )
{
	// link back
	$goback="<br><a href=\"javascript:history.back()\">Try again</a>";
	 
	// if category was not chosen
	if( empty($country) )
		abcPageExit("<h4>".$lng[870]."</h4>$goback");
	
	$sql_cntr = "select country_id from ".$prefix."store_countries where country='$country'";
	$result_cntr = mysql_query($sql_cntr);
	if ( $num_cntr = mysql_fetch_assoc ($result_cntr) )
		abcPageExit("<h4>".$lng[871]."</h4>$goback");
	
	// if upload image was clicked
	
	$_country = mysql_escape_string($country);
	
	$sql_insert = 	"insert into ".$prefix."store_countries set
			country = '$_country',
			parent_id = '$parent_id'
			";
	
	$result = mysql_query($sql_insert)
		or abcPageExit("Invalid query: " . mysql_error() . $goback);
			
echo "
<h3>".$lng[793].": $country ".$lng[869]."!</h3>
<p><a href=\"add_country.php\">".$lng[865]."</a></p>
<p><a href=\"countries.php\">".$lng[864]."</a></p>";
		
	
}

if( $submit && $_SESSION['demo'] ) {
	
	include('_guest_access.php');
	
}

include ("footer.inc.php");

?>
