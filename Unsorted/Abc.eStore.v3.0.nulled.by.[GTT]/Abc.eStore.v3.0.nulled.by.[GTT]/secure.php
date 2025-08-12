<?php

include_once("header.php");

extract( $_GET );
extract( $_POST );

// customers ip address for security
$ip= $_SERVER["REMOTE_ADDR"];

// Orderstring for skipjack
$order_string = "";

// todays date
$date = date("ymd");

// todays time
$time = date("H:i");

// BUILD ORDER SUMMARY IN DATABASE
$order_sum = mysql_query("insert into ".$prefix."store_order_sum set
cart_order_id = '$cart_order_id',
name = '$name',
add_1 = '$add_1',
add_2 = '$add_2',
town = '$town',
county = '$county',
postcode = '$postcode',
country = '$country',
name_d = '$name_d',
add_1_d = '$add_1_d',
add_2_d = '$add_2_d',
town_d = '$town_d',
county_d = '$county_d',
postcode_d = '$postcode_d',
country_d = '$country_d',
phone = '$phone',
prod_total = '$payable',
total_tax = '$total_tax',
total_ship = '$total_ship',
ip = '$ip', date = '$date',
time = '$time',
email = '$email',
customercomments = '$customercomments',
subtotal = '$cart_total',
user_discount = '$user_discount'
");

if( $routine !== "authorize" )
	echo "<br><br><p align=\"center\">".$lng[171]." </p>";

// BUILD ORDER INVENTORY
if( $ShoppingCart )
	$session = $ShoppingCart;
  
$total_per_item = 0;
$max_per_ship = 0;
$contents = $cart->display_contents($prefix,$session,$sale);
$cat_discount=$cart->get_quantity_discounts($prefix, $session);	

if( $contents['product'][0] !=  "" ) {
			
	$x = 0;
	while($x != $cart->num_items($prefix,$session))
	{
		$product = $contents['product'][$x];
		$atributes = $contents['atributes'][$x];
		$quantity = $contents['quantity'][$x];
		$price = $contents['price'][$x];
		$title = $contents['title'][$x];
		$cat_discount = 100*$cat_discount[$contents['cat_id'][$x]];
		$x ++;
		$title = addslashes($title);
		$order_inv = mysql_query("insert into ".$prefix."store_order_inv (product, title, quantity, price, cart_order_id, atributes, amount_discount) ".
			"values ('$product', '$title', '$quantity', '$price', '$cart_order_id', '$atributes','$cat_discount')");

		if( !$order_inv || !$order_sum ) {
			
			print "<br><br><p align=\"center\">".$lng[172]."</p>";
			include_once("footer.php");
			exit;
		}
	
	// Form skipjack order string
	
	if( $routine == "skipjack" ) {
	
		$item_number = str_replace ( array ( "\"", "~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "-", "+", "=" ) , "", $product );
		$item_description = str_replace ( array ( "\"", "~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "-", "+", "=" ) , "", $title );
		$item_cost = str_replace ( array ( "\"", "~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "-", "+", "=" ) , "", $price );
		$item_quantity = str_replace ( array ( "\"", "~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "-", "+", "=" ) , "", $quantity );
		
		$order_string .= $item_number . "~" . $item_description . "~" . $item_cost . "~" . $item_quantity . "~" . "N" . "||";
		
	}
	
	}// ends loop for each product
}

// 2checkout.com

if( $routine == "2checkout" ) {
	
	echo "<form target=\"_self\" action=\"https://www.2checkout.com/cgi-bin/sbuyers/cartpurchase.2c\" name=\"SecureForm\" method=\"post\">
	<input type=\"hidden\" name=\"sid\" value=\"$acc\">
	<input type=\"hidden\" name=\"cart_order_id\" value=\"$cart_order_id\">
	<input type=\"hidden\" name=\"total\" value=\"$payable\">
	<input type=\"hidden\" name=\"card_holder_name\" value=\"$name\">
	<input type=\"hidden\" name=\"street_address\" value=\"$add_1 $add_2\">
	<input type=\"hidden\" name=\"city\" value=\"$town\">
	<input type=\"hidden\" name=\"state\" value=\"$county\">
	<input type=\"hidden\" name=\"country\" value=\"$country\">
	<input type=\"hidden\" name=\"zip\" value=\"$postcode\">
	<input type=\"hidden\" name=\"phone\" value=\"$phone\">
	<input type=\"hidden\" name=\"email\" value=\"$email\">
	<input type=\"hidden\" name=\"demo\" value=\"$test\">
	</form>";

}

// Paypal

if( $routine == "paypal" ) {
	
	if( $site_currency == "usd" )
		$site_currency= "USD";
	if( $site_currency == "aud" )
	{
		echo $lng[837];
		include_once("footer.php");
		exit;
	}
	if( $site_currency == "cad" )
		$site_currency= "CAD";
	if( $site_currency == "gbp" )
		$site_currency= "GBP";
	if( $site_currency == "jpy" )
		$site_currency= "JPY";
	if( $site_currency == "eur" )
		$site_currency= "EUR";
	
	echo "<form target=\"_self\" action=\"https://www.paypal.com/cgi-bin/webscr\" name=\"SecureForm\" method=\"post\">
	<input type=\"hidden\" name=\"cmd\" value=\"_xclick\">
	<input type=\"hidden\" name=\"business\" value=\"$acc\">
	<input type=\"hidden\" name=\"cart_order_id\" value=\"$cart_order_id\">
	<input type=\"hidden\" name=\"item_name\" value=\"".$lng[838]."$cart_order_id\">
	<input type=\"hidden\" name=\"item_number\" value=\"$cart_order_id\">
	<input type=\"hidden\" name=\"amount\" value=\"$payable\">
	<input type=\"hidden\" name=\"total\" value=\"$payable\">
	<input type=\"hidden\" name=\"first_name\" value=\"\">
	<input type=\"hidden\" name=\"currency_code\" value=\"$site_currency\">
	<input type=\"hidden\" name=\"last_name\" value=\"$name\">
	<input type=\"hidden\" name=\"address1\" value=\"$add_1\">
	<input type=\"hidden\" name=\"address2\" value=\"$add_2\">
	<input type=\"hidden\" name=\"city\" value=\"$town\">
	<input type=\"hidden\" name=\"state\" value=\"$county\">
	<input type=\"hidden\" name=\"zip\" value=\"$postcode\">
	<input type=\"hidden\" name=\"day_phone_a\" value=\"$phone\">
	<input type=\"hidden\" name=\"cn\" value=\"".$lng[839]."\">
	<input type=\"hidden\" name=\"add\" value=\"1\">
	<input type=\"hidden\" name=\"upload\" value=\"1\">
	<input type=\"hidden\" name=\"return\" value=\"$site_url/finish.php?total=$payable&cart_order_id=$cart_order_id\">
	<input type=\"hidden\" name=\"cancel_return\" value=\"$site_url/finish.php?total=$payable&cart_order_id=$cart_order_id&fail=fail\">
	</form>";
}

// SkipJack

if( $routine == "skipjack" ) {

	if ( $test == "Y" )
		$process_adress = "https://developer.skipjackic.com/scripts/EvolvCC.dll?Authorize";
	else	$process_adress = "https://www.skipjackic.com/scripts/EvolvCC.dll?Authorize";
	
echo <<<FORM

<br>
<form action="$process_adress" method="post">
<input type="hidden" name="sjname" value="$name">
<input type="hidden" name="Email" value="$email">
<input type="hidden" name="Streetaddress" value="$add_1 $add_2">
<input type="hidden" name="City" value="$town">
<input type="hidden" name="State" value="$county">
<input type="hidden" name="Zipcode" value="$postcode">
<input type="hidden" name="Ordernumber" value="$cart_order_id">
<input type="hidden" name="Serialnumber" value="$acc">
<input type="hidden" name="Transactionamount" value="$payable">
<input type="hidden" name="Orderstring" value="$order_string">
<input type="hidden" name="Shiptophone" value="$phone">
<input type="hidden" name="routine" value="skipjack">
<input type="hidden" name="cart_order_id" value="$cart_order_id">
<input type="hidden" name="session_id" value="$session">

<table align="center"><tr>
<td>Credit Card Number:</td><td><input type="text" name="Accountnumber" value="" ></td></tr><tr>
<td>Expiration Month: (mm)</td><td><input type="text" name="Month" value=""></td></tr><tr>
<td>Expiration Year: (yy)</td><td><input type="text" name="Year" value=""></td></tr><tr>
<td></td><td align="left"><input type="Submit" name="Submit" value="Submit"></td></tr>
</table>

</form>

FORM;
	
}

// Email

if( $routine == "other" ){
	
	echo "<form action=\"finish.php\" name=\"SecureForm\" method=\"POST\" target=_self>
		<input type=\"hidden\" name=\"cart_order_id\" value=\"$cart_order_id\">
		<input type=\"hidden\" name=\"total\" value=\"$payable\">
		</form>";
}	

// Send e-mail purchase confirmatino and notifications

$headers = "From: \"$name\" <$email>";

// select order items
$sql_select = mysql_query( "select * from ".$prefix."store_order_inv where cart_order_id='$cart_order_id'");
$totalrows = mysql_num_rows($sql_select);

while( $row = mysql_fetch_array($sql_select) ) {
	
	$title = $row["title"]; 
	$atributes = str_replace ( "\n", "\n\t", $row["atributes"] ); 
	$quantity = $row["quantity"]; 
	$price = $row["price"];
	$product = $row["product"];
	$totalprice = $price*$quantity;
	$totalprice = sprintf("%.2f", $totalprice);
	$price = sprintf("%.2f", $price);
	$title = stripslashes($title);
	
	eval('$str_order_content .= "'.$lng[846].'";');
}

eval('$message = "'.$lng[845].'";');
eval('$subject="'.$lng[847].'";');

// Send purchase notification to the site administrator

mail( $site_email, $subject, $message, $headers );

// Send purchase confirmation to the customer

$headers = "From: $site_email";
mail( "\"$name\" <$email>", $subject, $message, $headers );

// Footer

include_once("footer.php");


if ( $routine != "skipjack" ) {

echo <<<SUBMIT
<script language="javascript">
document.SecureForm.submit();
</script>
SUBMIT;

}


?>