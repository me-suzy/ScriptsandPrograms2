<?php

include_once("header.php");
include_once("left.php");

// make sure product exists/has been selected
if( !empty($product) ) {
	
	$select_prod = mysql_query( "SELECT * FROM ".$prefix."store_inventory WHERE product='$product'");
	$totalrows = mysql_num_rows($select_prod);

	if( $totalrows == 0 )
		$lng['noproduct'] = "1";
	else {
		$lng['noproduct'] = "";
		
		// build page for product
		while ($row = mysql_fetch_array($select_prod)) {
			$title = $row["title"];
			$image = $row["image"];
			$description = $row["description"];
			$quantity = $row["quantity"];
			$price = $row["price"];
			$sale_price = $row["sale_price"];
			$cat_id = $row["cat_id"];
			$popularity = $row["popularity"];
		
			if ( !is_file ( "images/product/$image" ) )
				$row['image'] = "nophoto.gif";
		
			
			//
	
			$lng['product'][0] = $row;
		
		}

		// add 1 to products popularity
		$new_pop = $popularity + 1;
		$sql_update = "update ".$prefix."store_inventory set popularity='$new_pop' where product='$product';";
		$result = mysql_query ($sql_update); 

		$title=stripslashes($title);
		$description = stripslashes($description);
		$description = nl2br($description);

		$select_cat = mysql_query( "SELECT * FROM ".$prefix."store_category WHERE cat_id='$cat_id'");
		while ($row = mysql_fetch_array($select_cat))
		{
			$category = $row["category"];
			$cat_image = $row["cat_image"];
		}
		
		// navigation if directed from search.php
		if( $searchlink == "yes" ) {
			$lng['searchlink'] = "<a href=\"index.php\" target=\"_self\" class=\"menu\">".$lng[11]."</a> > <a href=\"search.php?search=$search&page=$page\" target=\"_self\" class=\"menu\">".$lng[224]."</a> > <font class=\"BodyText03\">$title</font><br>";
			$lng['catnav'] = "";
		}
		else
		{
			// navigation if directed from elsewhere
							
			GetCategoryPath ( $cat_id, &$catnav );
			$catnav = array_reverse ( $catnav );
			
			//
			
			$lng['searchlink'] = "";
		}

			
		if( $quantity >= 0 ) {
			$lng['product'][0]['quantity'] = "$quantity";
			$lng['product'][0]['226'] = $lng['226'];
		}
		else	$lng['product'][0]['quantity'] = "";
								
		if( $sale == "Y" && $row['sale_price'] !== "0.00" ) {
			
			$lng['product'][0]['new_price'] = "<p><b>".$lng[33].":&nbsp;&nbsp;$currency<s>$price</s></b>";
			$lng['product'][0]['new_price'] .= "&nbsp;&nbsp;<font class=\"saleColor\">$currency$sale_price</font></p>";
		}
					
		if( $sale == "Y" && $sale_price == "0.00" )
			$lng['product'][0]['new_price'] = "<p><b>".$lng[229].":&nbsp;&nbsp;$currency$price</b></p>";
					
		if( $sale == "N")
			$lng['product'][0]['new_price'] = "<p><b>".$lng[229].":&nbsp;&nbsp;$currency$price</b></p>";
		
		$lng['product'][0]['230'] = $lng['230'];
		$lng['product'][0]['231'] = $lng['231'];
		$lng['product'][0]['232'] = $lng['232'];
		$lng['product'][0]['233'] = $lng['233'];
						
		
// Product attributes

$sql = "SELECT * FROM `".$prefix."store_atributes_groups` ORDER BY name";
if (  $result = mysql_query ($sql) ) {
	
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
				
		$result_atr = array ();
		
		$sql_atr = "SELECT * FROM `" . $prefix . "store_atributes` WHERE parent='" . $res['n'] . "' ORDER BY name";
		if (  $result_atr = mysql_query ($sql_atr) ) {
						
			if ( !empty ( $result_atr ) ) {
				
				$atr = array ();
				
				while ( $res_atr = mysql_fetch_array( $result_atr ) ) {
										
					if ( in_array ( $res_atr['n'], $added ) ) {
						$atr[] = $res_atr;
					}
							
				}
				
			}
			
		
		}
		
		
		$res['atr'] = $atr;
		$lng['product'][0]['attributes'][] = $res;
		
	}

}
		
//

	
	}
} 
else	$lng['noproduct'] = "1";

$lng['session'] = $session;
$lng['catnav'] = $catnav;

// Processing templates

$tmpl = new Template ( "html/view_product.html" );

$tmpl -> param ( 'lng', array ( $lng ) );
$tmpl -> param ( 'design_dir', "design/" . $design_directory . "/" );

echo $tmpl -> parse();


// Footer

include_once("right.php");
include_once("footer.php");

?>
