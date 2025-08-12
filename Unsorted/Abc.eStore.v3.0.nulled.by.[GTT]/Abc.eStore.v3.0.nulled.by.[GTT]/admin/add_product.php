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

echo "<h2>".$lng[288]."</h2>";

// see if categories are present
$sql_count = "select * from ".$prefix."store_category";
$result_count = mysql_query ($sql_count);
$total = mysql_num_rows($result_count);
	
if( $total == 0 )
	abcPageExit("
			<p><a href='add_category.php'><b>".$lng[288]."</b></a></p>
			<p>".$lng[289]."<p>");

// fetch posted parameters
if( $_SERVER["REQUEST_METHOD"] == "POST" )
{
	
	extract( $_POST );
	
	$userfile = $_FILES["userfile"];
	$userfile_name = $userfile['name'];
	$userfile_type = $userfile['type'];
	$userfile_size = $userfile['size'];
	$userfile_tmp_name = $userfile['tmp_name'];
	
	$userfile_small = $_FILES["userfile_small"];
	$userfile_name_small = $userfile_small['name'];
	$userfile_type_small = $userfile_small['type'];
	$userfile_size_small = $userfile_small['size'];
	$userfile_tmp_name_small = $userfile_small['tmp_name'];
	
	if( isset( $userfile['name'] ) && $userfile['name'] != '' )
		$upload_image = 'yes';
	else
	{
		$upload_image = 'no';
	}
	
	if( isset( $userfile_small['name'] ) && $userfile_small['name'] != '' )
		$upload_image_small = 'yes';
	else
	{
		$upload_image_small = 'no';
	}
	
	$package = trim( $package );

	$submit = true;
	
}

// if submit has not been clicked
if (!$submit)
{
echo "
<form enctype=\"multipart/form-data\" action=\"add_product.php\" method=\"post\">
<p><b>* ".$lng[290]."</b></p>
<table border=\"1\" bordercolor=\"#e6e6e6\" cellpadding=\"4\" cellspacing=\"0\" width=\"95%\">

<tr>
<td valign=\"top\"><b>*".$lng[291].":</b></td>
<td valign=\"top\"><input type=\"textbox\" name=\"title\" size=\"40\" class=\"textbox\"></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[292].":</b> </td>
<td valign=\"top\"><textarea name=\"description\" cols=\"40\" rows=\"8\"></textarea></td>
</tr>

<tr>
<td valign=\"top\"><b>*".$lng[293].":</b></td>
<td valign=\"top\">\n";

// Build drop down menu for full category!
echo "<select name=\"cat_id\">
<option value=\"none\">".$lng[294]."</option>\n";

	$cats = abcFetchCategoryList();
	
	// Drop down menu to build full category!
	foreach( $cats as $cat )
	{							
		$catname = $cat["path"];
		$cat_id_dd = $cat["cat_id"];
		echo "<option value=\"$cat_id_dd\">$catname</option>\n";
	}
	
echo "</select>
</td>
</tr>

<tr>
<td valign=\"top\"><b>*".$lng[295].":</b></td>
<td valign=\"top\"><input class=\"textbox\" type=\"textbox\" name=\"quantity\">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type=\"checkbox\" name=\"quantity_unlim\">  ".$lng[296]."</td>
</tr>

<tr>
<td valign=\"top\"><b>*".$lng[297]." ($currency):</b></td>
<td valign=\"top\"><input type=\"textbox\" name=\"price\" class=\"textbox\"></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[298]." ($currency):</b></td>
<td valign=\"top\"><input type=\"textbox\" name=\"sale_price\" class=\"textbox\"><br>(".$lng[299].")</td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[938].":</b></td>
<td valign=\"top\"><input type=\"checkbox\" name=\"special\" value=\"1\"></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[300]." (*.jpg, *.gif, *.png):</b></td>
<td valign=\"top\"><input type=\"file\" name=\"userfile_small\" class=\"file\"></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[301]." (*.jpg, *.gif, *.png):</b></td>
<td valign=\"top\"><input type=\"file\" name=\"userfile\" class=\"file\"></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[302].":</b></td>
<td valign=\"top\"><input type=\"text\" name=\"package\" size=50>
<br>".$lng[303]."
</td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[304].":</b></td>
<td valign=\"top\">
";

// Add product attributes

$sql = "SELECT * FROM `".$prefix."store_atributes_groups` ORDER BY name";
if (  $result = mysql_query ($sql) ) {
	
echo <<<GROUP
<table border="0" cellspacing="2" cellpadding="2" width="100%" align="center">
GROUP;

	while ( $res = mysql_fetch_array( $result ) ) {
		
		echo "<tr><td><b>" . $res['name'];
		echo "</b></td><td>";
		
		$result_atr = array ();
		
		$sql_atr = "SELECT * FROM `" . $prefix . "store_atributes` WHERE parent='" . $res['n'] . "' ORDER BY name";
		if (  $result_atr = mysql_query ($sql_atr) ) {
						
			if ( !empty ( $result_atr ) ) {
			
				$i = 0;
				
				while ( $res_atr = mysql_fetch_array( $result_atr ) ) {
					
					$i = 1;
					
					echo "<input type=\"checkbox\" name=\"add_atribute[$res_atr[n]]\" value=\"$res[n]\">$res_atr[name] ";
					
				}
				
				if ( $i == 0 )
					echo "<br><i>No attributes</i><br>&nbsp;";
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
<td valign=\"top\">&nbsp;</td>
<td valign=\"top\"><input type=\"submit\" name=\"submit\" value=\"".$lng[288]."\" class=\"submit\"></td>
</tr>
</table>

</form>\n";

	abcPageExit();
}

if ( !$_SESSION['demo'] )
{

// if submit has been clicked
	
// create back link
$goback="<p><a href=\"javascript:history.back()\">".$lng[305]."</a></p>";

if( !empty( $package ) && !file_exists( $package ) )
{
	eval('$_msg="'.$lng[306].'";');
	abcPageExit("<p>".$_msg."</p><p>$goback</p>");
}

// check required fields were filled
if( empty($title) || empty($price) )
	abcPageExit("<p>".$lng[307]."</p>
				<p>$goback</p>");
// check category was selected
if( $cat_id == "none" )
	abcPageExit("<p>".$lng[308]."</p>
				<p>$goback</p>");

// make sure quantity is apropriate
if( isset( $quantity_unlim ) )
	$quantity = -1;

if( eregi("[a-z\.\!\"\£\$\%\^\&\*\(\)\-\+\{\}\:\;\'\@\~\#\\\|\<\>\?\/]", $quantity ) )
	abcPageExit("<p>".$lng[309]."</p>
				<p>$goback</p>");

// make sure price is apropriate
if( eregi("[a-z\!\"\£\$\%\^\&\*\(\)\-\+\{\}\:\;\'\@\~\#\\\|\<\>\?\/]", $price ) )
	abcPageExit("<p>".$lng[310]."</p>
				<p>$goback</p>");

// make sure sale price is apropriate
if( eregi("[a-z\!\"\£\$\%\^\&\*\(\)\-\+\{\}\:\;\'\@\~\#\\\|\<\>\?\/]", $sale_price ) )
	abcPageExit("<p>".$lng[311]."</p>
				<p>$goback</p>");
				
// Handle image upload

if( $upload_image_small == "yes" )
{
	// Upload
	$path = "$site_dir/images/product/";
	$max_size = 200000;

	if( is_uploaded_file($userfile_tmp_name_small) )
	{
		if( $userfile_size_small > $max_size )
			abcPageExit("<h4>".$lng[312]."</h4>$goback");

		if( !abcIsImageContentType( $userfile_type_small  ) )
			abcPageExit("".$lng[313]."$goback");
		
		$res = copy($userfile_tmp_name_small, $path . $userfile_name_small);
		if( !$res )
			abcPageExit("<h4>".$lng[314]."</h4>$goback");
	}
	else
		echo $lng[315];
		
} // handle image upload

if( $upload_image == "yes" )
{
	// Upload
	$path = "$site_dir/images/product/";
	$max_size = 200000;

	if( is_uploaded_file($userfile_tmp_name) )
	{
		if( $userfile_size > $max_size )
			abcPageExit("<h4>".$lng[316]."</h4>$goback");

		if( !abcIsImageContentType( $userfile_type  ) )
			abcPageExit($lng[317]."$goback");
		
		$res = copy($userfile_tmp_name, $path . $userfile_name);
		if( !$res )
			abcPageExit("<h4>".$lng[318]."</h4>$goback");
	}
	else
		echo $lng[319];
		
} // handle image upload

// Insert new products

$sql = "SELECT max(product) AS total FROM ".$prefix."store_inventory";
$r = mysql_query($sql, $db);

if( !$r )
	die("Database access error.");
	
list($product) = mysql_fetch_array($r);
$product++;

if( empty($sale_price) )
	$sale_price="0.00";

$_title = mysql_escape_string($title);
$_description = mysql_escape_string($description);

if ( !isset ( $special ) )
	$special = "";

$sql_insert = "insert into ".$prefix."store_inventory
	( product, quantity, description, small_image, image, price, title, cat_id, sale_price, package, special )
	values
	('$product', '$quantity', '$_description', '$userfile_name_small', '$userfile_name', '$price',
	 '$_title', '$cat_id', '$sale_price', '$package', '$special')";

$result = mysql_query($sql_insert);
$description= nl2br($description);
$sale_price = sprintf("%.2f", $sale_price);
$price = sprintf("%.2f", $price);
$product_id = mysql_insert_id();


if ( !is_file ( "../images/product/" . $userfile_name ) )
	$userfile_name = "nophoto.gif";
	
if ( !is_file ( "../images/product/" . $userfile_name_small ) )
	$userfile_name_small = "nophoto_small.gif";

// Set attributes

if ( is_array ( $add_atribute ) ) {

	foreach ( $add_atribute as $natr=>$ngr ) {
	
		if ( !isset ( $atributes[$ngr] ) )
			$atributes[$ngr] = "";
		
		if ( !empty ( $atributes[$ngr] ) )
			$atributes[$ngr] .= " ";
		
		$atributes[$ngr] .= $natr;
		
	}
	
	foreach ( $atributes as $group=>$atribute ) {
	
		$sql = "INSERT INTO ".$prefix."store_atributes_link SET 
			product_id=\"$product_id\",
			group_id=\"$group\",
			atributes=\"$atribute\"
			";
		mysql_query ($sql);
		
	}
	
}

//
eval('$_msg="'.$lng[320].'";');
echo "
<h3>".$_msg."</h3>
<form action=\"edit_product.php\" method=\"get\">
<input type=\"hidden\" name=\"product\" value=\"$product\">
<table border=\"1\" bordercolor=\"#e6e6e6\" cellpadding=\"4\" cellspacing=\"0\" width=\"95%\">
<tr>
<td valign=\"top\" width=\"30%\"><b>".$lng[291].":</b></td>
<td valign=\"top\">$title&nbsp;</td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[292].":</b> </td>
<td valign=\"top\">$description&nbsp;</td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[295].":</b></td>
<td valign=\"top\">";

if( $quantity >= 0 )
	echo $quantity;
else
	echo $lng[296];

echo "&nbsp;</td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[297].":</b></td>
<td valign=\"top\">$currency$price&nbsp;</td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[298].":</b></td>
<td valign=\"top\">$currency$sale_price&nbsp;</td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[302].":</b></td>
<td valign=\"top\">$package&nbsp;</td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[304].":</b></td>
<td valign=\"top\">
";

// Edit product attributes

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
		echo "</b>:</td><td>";
		
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
					echo "<br><i>".$lng[325]."</i><br>&nbsp;";
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
<td valign=\"top\"><b>".$lng[300].":</b></td>
<td valign=\"top\"><img src=\"../images/product/$userfile_name_small\"></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[301].":</b></td>
<td valign=\"top\"><img src=\"../images/product/$userfile_name\"></td>
</tr>

<tr>
<td valign=\"top\">&nbsp;</td>
<td valign=\"top\"><INPUT class=\"submit\" TYPE=\"submit\"  VALUE=\"".$lng[324]."\"></td>
</tr>
</table>

</form>\n";

echo "<p><a href=add_product.php>".$lng[326]."</a></p>";

} else {

	include('_guest_access.php');
	
}

include ("footer.inc.php");

?>
