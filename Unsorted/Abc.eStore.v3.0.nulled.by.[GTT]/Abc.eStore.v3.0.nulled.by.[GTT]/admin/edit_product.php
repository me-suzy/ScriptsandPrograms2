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

echo "<h2>".$lng[489]."</h2>";

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
	
	$package = trim( $package );
}
elseif( $_SERVER["REQUEST_METHOD"] == "GET" )
	extract( $_GET );
	
// if submit is clicked to edit a product
if( $edit && !$_SESSION['demo'])
{
	// create link back to prev page
	$goback="<a href=\"javascript:history.back()\">".$lng[490]."</a>";

	if( !empty( $package ) && !file_exists( $package ) )
	{
		eval('$_msg="'.$lng[491].'";');
		abcPageExit("<p>".$_msg."</p><p>$goback</p>");
	}

	// make sure relevant fields were filled
	if( empty($title) || empty($price) )
		abcPageExit("<p>".$lng[492]." <font color=\"990000\"><b>*</b></font> ".$lng[493]."<br><br>$goback</p>");
		
	if( empty($cat_id) )
		abcPageExit("<p>".$lng[494]."<br><br>$goback</p>");
		
	// make sure formatted properly
	if( isset( $quantity_unlim ) )
		$quantity = -1;
		
	if( eregi("[a-z\.\!\"\£\$\%\^\&\*\(\)\-\+\{\}\:\;\'\@\~\#\\\|\<\>\?\/]", $quantity ) ) 
		abcPageExit("<p>".$lng[495]."</p><p>$goback</p>");

	if( eregi("[a-z\!\"\£\$\%\^\&\*\(\)\-\+\{\}\:\;\'\@\~\#\\\|\<\>\?\/]", $price ) ) 
		abcPageExit("<p>".$lng[496]."<br><br>$goback</p>");
		
	if( eregi("[a-z\!\"\£\$\%\^\&\*\(\)\-\+\{\}\:\;\'\@\~\#\\\|\<\>\?\/]", $sale_price ) ) 
		abcPageExit("<p>".$lng[497]."<br><br>$goback</p>");

	// update db
	if( empty( $sale_price ) )
		$sale_price = "0.00";
	
	// delete category image (or delete old image if update function)
	if( $image_function == "del" || $image_function == "up" )
	{
		$image_dupe = mysql_query("select * from ".$prefix."store_inventory where image = '$dbimage'"); 
		$image_total = mysql_num_rows($image_dupe);
		if( $image_total == 1 )
			abcDelProductImage( $dbimage );
		if( $image_function == "del" )
			$userfile_name = "";
	}
	
	// delete category image (or delete old image if update function)
	if( $image_function_small == "del" || $image_function == "up" )
	{
		$image_dupe_small = mysql_query("select * from ".$prefix."store_inventory where small_image = '$dbimage_small'"); 
		$image_total_small = mysql_num_rows($image_dupe_small);
		if( $image_total_small == 1 )
			abcDelProductImage( $dbimage_small );
		if( $image_function_small == "del" )
			$userfile_name_small = "";
	}
	
	// upload category image
	if( $image_function_small == "up" )
	{
		$path = "$site_dir/images/product/";
		$max_size = 200000;
	
		if( is_uploaded_file( $userfile_tmp_name_small ) )
		{
			if( $userfile_size_small > $max_size )
				abcPageExit("<h3>".$lng[498]."</h3>$goback\n");
	
			if( !abcIsImageContentType( $userfile_type_small  ) )
				abcPageExit("<h3>".$lng[499]."</h3>$goback\n");
			
			$res = copy( $userfile_tmp_name_small, $path . $userfile_name_small );
			if( !$res )
				abcPageExit("<h3>".$lng[500]."</h3>$goback\n");
			
		}
	}
	
	if( $image_function == "up" )
	{
		$path = "$site_dir/images/product/";
		$max_size = 200000;
	
		if( is_uploaded_file( $userfile_tmp_name ) )
		{
			if( $userfile_size > $max_size )
				abcPageExit("<h3>".$lng[501]."</h3>$goback\n");
	
			if( !abcIsImageContentType( $userfile_type  ) )
				abcPageExit("<h3>".$lng[502]."</h3>$goback\n");
			
			$res = copy( $userfile_tmp_name, $path . $userfile_name );
			if( !$res )
				abcPageExit("<h3>".$lng[503]."</h3>$goback\n");
			
		}
	}
	
	// no image update/delete
	if( empty( $image_function ) )
		$userfile_name = $dbimage;
	
	if( empty( $image_function_small ) )
		$userfile_name_small = $dbimage_small;
	
	$userfile_name = addslashes( $userfile_name );
	$userfile_name_small = addslashes( $userfile_name_small );
	$_description = mysql_escape_string( $description );
	$_title = mysql_escape_string($title);
	
	if ( !isset ( $special ) )
		$special = "";
	
	$sql_update = "update ".$prefix."store_inventory set
			title='$_title', description='$_description',
			quantity='$quantity', sale_price='$sale_price',
			price='$price', cat_id='$cat_id',
			image='$userfile_name',small_image='$userfile_name_small',
			package='$package',
			special='$special'
		where product='$product'";
	$result = mysql_query ($sql_update);
	
	$description = nl2br($description);


if ( !is_file ( "../images/product/$userfile_name_small" ) )
	$userfile_name_small_view = "nophoto_small.gif";
else	$userfile_name_small_view = $userfile_name_small;
	
if ( !is_file ( "../images/product/$userfile_name" ) )
	$userfile_name_view = "nophoto.gif";
else 	$userfile_name_view = $userfile_name;


// Set attributes

mysql_query ("DELETE FROM ".$prefix."store_atributes_link WHERE product_id='$product'");

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
			product_id=\"$product\",
			group_id=\"$group\",
			atributes=\"$atribute\"
			";
		mysql_query ($sql);
		
	}
	
}

//
	
echo "<h3>".$lng[504].": $title<br>".$lng[505]."</h3>

<form ACTION=\"edit_product.php\" METHOD=\"POST\">
<input type=\"hidden\" name=\"product\" value=\"$product\">

<table border=\"1\" bordercolor=\"#e6e6e6\" cellpadding=\"4\" cellspacing=\"0\" width=\"95%\">
<tr>
<td valign=\"top\"><b>".$lng[506].":</b></td>
<td valign=\"top\">$title</td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[507].":</b></td>
<td valign=\"top\">$description</td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[508].":</b></td>
<td valign=\"top\">";

if( $quantity >= 0 )
	echo $quantity;
else
	echo $lng[509];

echo "</td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[510]." ($currency):</b></td>
<td valign=\"top\">$price</td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[511]." ($currency):</b></td>
<td valign=\"top\">$sale_price</td>
</tr>
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


<tr>
<td valign=\"top\"><b>".$lng[512].":</b></td>
<td valign=\"top\">$package&nbsp;</td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[513].":</b></td>
<td valign=\"top\"><img src=\"../images/product/$userfile_name_small_view\"></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[514].":</b></td>
<td valign=\"top\"><img src=\"../images/product/$userfile_name_view\"></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[515].":</b></td>
<td valign=\"top\">
";

// Product atributes

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
					echo "<br><i>".$lng[516]."</i><br>&nbsp;";
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
<td valign=\"top\"><b>".$lng[517].":</b></td>
<td valign=\"top\"><textarea name='code' cols='55' rows='3'>$site_url/view_product.php?product=$product</textarea></td>
</tr>

<tr>
<td valign=\"top\">&nbsp;</td>
<td valign=\"top\"><INPUT class=\"submit\" TYPE=\"submit\" name=\"again\" VALUE=\"".$lng[518]."\"></td>
</tr>
</table>
</form>";
}

if ( $edit && $_SESSION['demo'] ) { // Demo
		
	echo ("<font color='red'>".$lng[519]."</font><br><br>
	<a href='products.php'>".$lng[520]."</a>");	
		
	}

if( !$edit )
{
	$sql_select = "select * from ".$prefix."store_inventory where product ='$product'";
	$result = mysql_query($sql_select);

	while ($row = mysql_fetch_array($result))
	{
		$title = $row["title"];
		$product = $row["product"];
		$description = $row["description"];
		$quantity = $row["quantity"];
		$price = $row["price"];
		$sale_price = $row["sale_price"];
		$dbimage = $row["image"];
		$dbimage_small = $row["small_image"];
		$oldcat_id = $row["cat_id"];
		$package = $row["package"];
		$special = $row["special"];
	}
	
	$title = str_replace("\"", "&quot;", $title );
	$title = str_replace("\'", "&#39;", $title );
	//$description = nl2br($description);

echo "
<form ENCTYPE=\"multipart/form-data\" ACTION=\"edit_product.php\" METHOD=\"POST\">

<p><b>* ".$lng[521]."</b></p>

<table border=\"1\" bordercolor=\"#e6e6e6\" cellpadding=\"4\" cellspacing=\"0\" width=\"95%\">
<tr>
<td valign=\"top\"><b>* ".$lng[506].":</b></td>
<td valign=\"top\"><input class=\"textbox\" type=\"textbox\"  name=\"title\" value=\"$title\"></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[507].":</b></td>
<td valign=\"top\"><textarea name=\"description\" cols=\"40\" rows=\"8\">$description</textarea></td>
</tr>

<tr>
<td valign=\"top\"><b>* ".$lng[522].":</b></td>
<td valign=\"top\">";
    
	// Drop down menu of full_cat_name!
    print "<select  name=\"cat_id\">";
	$cats = abcFetchCategoryList();
	
	foreach( $cats as $cat )
	{							
		$catname = $cat["path"];
		$cat_id_dd = $cat["cat_id"];
		if( $oldcat_id == $cat_id_dd )
			echo"\t\t<option value=\"$cat_id_dd\" selected>$catname</option>\n";
		else
			echo"\t\t<option value=\"$cat_id_dd\">$catname</option>\n";
	}

    echo " </select>
</td>
</tr>

<tr>
<td valign=\"top\"><b>* ".$lng[508].":</b></td>
<td valign=\"top\"><input class=\"textbox\" type=\"textbox\"  name=\"quantity\" value=\"";

if( $quantity >= 0 )
	echo $quantity;
	
echo "\">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type=\"checkbox\" name=\"quantity_unlim\" ";

if( $quantity < 0 )
	echo "checked";

if ( !is_file ( "../images/product/$dbimage_small" ) )
	$dbimage_small_view = "nophoto_small.gif";
else 	$dbimage_small_view = $dbimage_small;
	
if ( !is_file ( "../images/product/$dbimage" ) )
	$dbimage_view = "nophoto.gif";
else	$dbimage_view = $dbimage;

echo "> ".$lng[509]."</td>
</td>
</tr>

<tr>
<td valign=\"top\"><b>* ".$lng[510]." ($currency):</b></td>
<td valign=\"top\"><input class=\"textbox\" type=\"textbox\"  name=\"price\" value=\"$price\"></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[511]." ($currency):</b></td>
<td valign=\"top\"><input class=\"textbox\" type=\"textbox\"  name=\"sale_price\" value=\"$sale_price\"></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[938].":</b></td>
<td valign=\"top\"><input type=\"checkbox\" name=\"special\" value=\"1\"";
if($special) 
	echo "checked";

echo "
></td>
</tr>


<tr>
<td valign=\"top\"><b><font color=\"#CC0000\">".$lng[513].":</font></b></td>
<td valign=\"top\"><img src=\"../images/product/$dbimage_small_view\"></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[523]."</b></td>
<td valign=\"top\"><input name=\"image_function_small\" type=\"radio\" value=\"del\"></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[524]."</b></td>
<td valign=\"top\"><input name=\"image_function_small\" type=\"radio\" value=\"up\"></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[525]."</b></td>
<td valign=\"top\"><input name=\"image_function_small\" type=\"radio\" value=\"\" checked></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[526]." (*.jpg, *.gif, *.png):</b></td>
<td valign=\"top\"><INPUT class=\"file\" NAME=\"userfile_small\" TYPE=\"file\"></td>
</tr>

<tr>
<td valign=\"top\"><b><font color=\"#CC0000\">".$lng[527].":</font></b></td>
<td valign=\"top\"><img src=\"../images/product/$dbimage_view\"></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[528]."</b></td>
<td valign=\"top\"><input name=\"image_function\" type=\"radio\" value=\"del\"></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[529]."</b></td>
<td valign=\"top\"><input name=\"image_function\" type=\"radio\" value=\"up\"></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[530]."</b></td>
<td valign=\"top\"><input name=\"image_function\" type=\"radio\" value=\"\" checked></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[531]." (*.jpg, *.gif, *.png):</b></td>
<td valign=\"top\"><INPUT class=\"file\" NAME=\"userfile\" TYPE=\"file\"></td>
</tr>


<tr>
<td valign=\"top\"><b>".$lng[532].":</b></td>
<td valign=\"top\"><input type=\"text\" name=\"package\" size=\"50\" value=\"$package\">
<br>".$lng[533]."
</td>
</tr>

<input type=\"hidden\" name=\"product\" value=\"$product\">
<input type=\"hidden\" name=\"dbimage\" value=\"$dbimage\">
<input type=\"hidden\" name=\"dbimage_small\" value=\"$dbimage_small\">

<tr>
<td valign=\"top\"><b>".$lng[534].":</b></td>
<td valign=\"top\">
";

// Edit product attributes

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
			
				// Get inserted attributes
				
					$sql_added = "SELECT * FROM `" . $prefix . "store_atributes_link` WHERE group_id='" . $res['n'] . "' AND product_id='$product' LIMIT 1";
					if (  $result_added = mysql_query ($sql_added) ) {
						
						$res_added = mysql_fetch_array( $result_added );
						$added = explode ( " ", $res_added['atributes'] );
							
					}
					
				//
								
				$i = 0;
				
				while ( $res_atr = mysql_fetch_array( $result_atr ) ) {
					
					$i = 1;
					
					if ( in_array ( $res_atr['n'], $added ) )
						$checked = "checked";
					else	$checked = "";
					
					echo "<input type=\"checkbox\" name=\"add_atribute[$res_atr[n]]\" value=\"$res[n]\" $checked>$res_atr[name] ";
					
				}
				
				if ( $i == 0 )
					echo "<br><i>".$lng[516]."</i><br>&nbsp;";
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
<td valign=\"top\"><INPUT class=\"submit\" TYPE=\"submit\" name=\"edit\" VALUE=\"".$lng[535]."\"></td>
</tr>
</table>

</form>";

}

include("footer.inc.php");

?>