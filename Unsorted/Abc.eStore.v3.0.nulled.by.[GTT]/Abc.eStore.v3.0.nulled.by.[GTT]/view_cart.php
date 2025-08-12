<?php

include_once("header.php");

extract( $_POST );
extract( $_GET );

// if product is to be added
if( $add ) {
		
	$quantity= $quan;
	$result = mysql_query("SELECT * FROM ".$prefix."store_inventory WHERE product='$add'");
	$row = mysql_fetch_array($result);
	
	// Insert attributes
	
	$description = "";
	
	if ( isset ( $atributes ) ) {
	
		if ( is_array ( $atributes ) ) {
		
			foreach ( $atributes as $group=>$atribute ) {
			
				if ( $atribute != "" ) {
					
					$description .= $group . ": " . $atribute . "\n";
					
				}
				
			}
			
		}
		
	}
	
	//
	
	$cart->add_item($prefix,$session,$row[product],$quantity,$description);
}

// if product is to be removed
if( $remove ) {
	
	if( $remove !== "all" ) {
		
		$cart->delete_item($prefix,$session,$remove);
	}
	
	if( $remove == "all" ) {
		
		$result = "delete FROM ".$prefix."store_shopping WHERE session='$session'";
		$row = mysql_query($result);
	}
}
   
// if contents is to be modified
if( $modify )
{
	$contents = $cart->display_contents($prefix,$session,$sale);
	for($i = 0; $i < sizeof($quantity); $i++)
	{
		$id = $contents['id'][$i];
		$oldquan = $contents[quantity][$i];
		$product = $contents[product][$i];
		$newquan = $quantity[$id];
		
		if( $newquan == 0 )
			;//mysql_query( "DELETE FROM ".$prefix."store_shopping WHERE session='$session' AND id='$id'");
		$cart->modify_quantity($prefix,$session,$id,$newquan);
	}
}
// End update cart 

// start contents of page




// used for secure.php to autosubmit form data
if( $url == "secure" )
	echo "onLoad='document.SecureForm.submit();'";
  
$url = $_SERVER['PHP_SELF'];
if(!$ShoppingCart)
	$url .=  "?session=$session";

$lng['url'] = $url;
  
// display contents

$contents = $cart->display_contents($prefix,$session,$sale);
$cat_discount=$cart->get_quantity_discounts($prefix, $session);

if( $contents['product'][0] !=  "" ) {
	
	$lng['nobasket'] = "";
			
	$x = 0;

	while( $x != $cart->num_items( $prefix, $session ) ) {
				
		$lng['basket'][] = array ( 	'title_strip'=>stripslashes($contents[title][$x]),
						'currency'=>$currency,
						'price'=>$contents['price'][$x],
						'price_no_discount'=>$contents['price_no_discount'][$x],
						'product'=>$contents['product'][$x],
						'atributes'=>nl2br ($contents['atributes'][$x]),
						'id'=>$contents['id'][$x],
						'quantity'=>$contents['quantity'][$x],
						'outofstock'=>$contents['outofstock'][$x],
						'cat_discount'=>(100*$cat_discount[$contents['cat_id'][$x]]),
						'prod_total'=>$contents['total'][$x],
						'form_prod_total'=>sprintf("%.2f", $contents[total][$x]),
						'remove'=>urlencode($contents['id'][$x]),
						'198'=>$lng['198'],
						'933'=>$lng['933']
						
		);
		
		$x++;
		
	}
	
    $lng['currency'] = $currency;
    $lng['cart_total'] = $cart->cart_total($prefix,$session,$sale);
 
	

}
else 	{
	$lng['nobasket'] = 1;
}

$lng['session'] = $session;

// Processing templates

$tmpl = new Template ( "html/view_cart.html" );

$tmpl -> param ( 'lng', array ( $lng ) );
$tmpl -> param ( 'design_dir', "design/" . $design_directory . "/" );

echo $tmpl -> parse();


// Footer

include_once("footer.php");

?>