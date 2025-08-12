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

echo "<h2>".$lng[807]."</h2>";

if( $_SERVER["REQUEST_METHOD"] == "GET" )
	extract( $_GET );

if ((!$edit) or(!del)){

$sql_select = "select * from ".$prefix."store_inventory where product ='$product'";
$result = mysql_query($sql_select);
$count = mysql_num_rows($result);

if( $count == 0 )
	abcPageExit( "<p>".$lng[808]."</p>" );

if( $row = mysql_fetch_array($result) )
{
	$title = $row["title"];
	$product = $row["product"];
	$description = $row["description"];
	$quantity = $row["quantity"];
	$price = $row["price"];
	$image = $row["image"];
	$package = $row["package"];
	$special = $row["special"];
}

$description = nl2br($description);

echo "<a href=\"javascript:history.back(0)\">".$lng[809]."</a>
<p>
<FORM ACTION=\"edit_product.php\" METHOD=\"POST\">
<input type=\"hidden\" name=\"product\" value=\"$product\">

<table border=\"1\" bordercolor=\"#e6e6e6\" cellpadding=\"4\" cellspacing=\"0\" width=\"95%\">
<tr>
<td valign=\"top\"><b>".$lng[810].":</b></td>
<td valign=\"top\">$title&nbsp;</td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[811].":</b></td>
<td valign=\"top\">$description&nbsp;</td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[812].":</b></td>
<td valign=\"top\">";

if( $quantity >= 0 )
	echo $quantity;
else
	echo "Unlimited";

echo "&nbsp;</td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[813]." ($currency):</b></td>
<td valign=\"top\">$price&nbsp;</td>
</tr>
";

if ( $sale_price )
echo "
<tr>
<td valign=\"top\"><b>".$lng[511]." ($currency):</b></td>
<td valign=\"top\">$sale_price&nbsp;</td>
</tr>
";

if ( $special )
echo "
<tr>
<td valign=\"top\"><b>".$lng[938].":</b></td>
<td valign=\"top\">
";

if ( $special )
	echo "+";
else	echo "-";

echo "
&nbsp;</td>
</tr>
";

echo "
<tr>
<td valign=\"top\"><b>".$lng[814].":</b></td>
<td valign=\"top\">$package&nbsp;</td>
</tr>

";

if ($image!=="nophoto.gif" && !empty ($image) )
echo "
<tr>
<td valign=\"top\"><b>".$lng[815].":</b></td>
<td valign=\"top\"><img src=\"../images/product/$image\"></td>
</tr>
";

echo "
<tr>
<td valign=\"top\"><b>".$lng[816].":</b></td>
<td valign=\"top\">
";

// Product attributes

$sql = "SELECT * FROM `".$prefix."store_atributes_groups` ORDER BY name";
if (  $result = mysql_query ($sql) ) {
	
echo <<<GROUP
<table border="0" cellspacing="2" cellpadding="2" width="100%" align="center">
GROUP;

	while ( $res = mysql_fetch_array( $result ) ) {
		
		// Get inserted attributes
		
		$added = array ();
				
		$sql_added = "SELECT * FROM `" . $prefix . "store_atributes_link` WHERE group_id='" . $res['n'] . "' AND product_id='$product' LIMIT 1";
		if (  $result_added = mysql_query ($sql_added) ) {
			
			$res_added = mysql_fetch_array( $result_added );
			if ( !empty ( $res_added['atributes'] ) )
			$added = explode ( " ", $res_added['atributes'] );
			
		}
			
		if ( empty ($added) )
			continue;
		
		//
						
		echo "<tr><td><b>" . $res['name'];
		echo "</b>:</td><td align=\"left\">";
		
		$result_atr = array ();
		
		$sql_atr = "SELECT * FROM `" . $prefix . "store_atributes` WHERE parent='" . $res['n'] . "' ORDER BY name";
		if (  $result_atr = mysql_query ($sql_atr) ) {
						
			if ( !empty ( $result_atr ) ) {
											
				$i = 0;
				
				while ( $res_atr = mysql_fetch_array( $result_atr ) ) {
					
					$i = 1;
					
					if ( in_array ( $res_atr['n'], $added ) )
						echo "$res_atr[name] ";
					
				}
				
				if ( $i == 0 )
					echo "<br><i>".$lng[817]."</i><br>&nbsp;";
			}
			
		
		}
		
		
		echo "</td></tr>";
		
	}

echo <<<GROUP
</table>
GROUP;

}
		
//

echo "
</td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[818].":</b></td>
<td valign=\"top\"><textarea name='code' cols='55' rows='3'>$site_url/view_product.php?product=$product</textarea></td>
</tr>

<tr>
<td valign=\"top\">&nbsp;</td>
<td valign=\"top\"><INPUT class=\"submit\" TYPE=\"submit\" VALUE=\"".$lng[819]."\">&nbsp;&nbsp;<INPUT class=\"submit\" TYPE=\"button\" name=\"del\" VALUE=\"".$lng[820]."\" onclick=\"javascript:decision('".$lng[821]."',
'products.php?product=$product&del=1&image=$image')\"></td>
</tr>
</table>
</form>";
}

include ("footer.inc.php");

?>