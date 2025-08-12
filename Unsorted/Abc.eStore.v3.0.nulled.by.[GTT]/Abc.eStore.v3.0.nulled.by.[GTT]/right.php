<?php


// Workout cart total price 

if($ShoppingCart)
	$session = $ShoppingCart;

$contents = $cart->display_contents($prefix,$session,$sale);
if( $contents['product'][0] !=  "" ) {
	
	// Workout cart total quantity
	$quan_total = mysql_query( "SELECT sum(quantity) as q FROM ".$prefix."store_shopping WHERE session='$session'");

	$row = mysql_fetch_array($quan_total);
	$lng['quan'] = $row['q'];
	$lng['currency'] = $currency;
	$lng['cart_total'] = $cart->cart_total($prefix,$session,$sale);
	
}   
else	{
	$lng['quan'] = "0";
}

$lng['noquan'] = !$lng['quan'];
			
// list top 10 popular products by hits
$query = "select * from ".$prefix."store_inventory order by popularity desc LIMIT 10";
$result = mysql_query( $query );

$no = 0;

while( $row = mysql_fetch_array($result) ) { 	
	// format title so not too long
	$row['title']=stripslashes($row['title']);
	
	if( strlen($row['title']) >= 23 ) {
		
		$row['title']=substr($row['title'],0,23);
		$row['title'] .= "..";
	}
	
	$no = $no + 1;
	
	$lng['popular'][] = $row;	

}

$lng['session'] = $session;

// Processing templates

$tmpl = new Template ( "html/right.html" );

$tmpl -> param ( 'lng', array ($lng) );
$tmpl -> param ( 'design_dir', "design/" . $design_directory . "/" );

echo $tmpl -> parse();

?>