<?
function process_template(&$template,$orderid,$order_date,$order_status,$orderflag,$uname,$fname,$lname,$b_address,$b_city,$b_state,$b_country,$b_zipcode,$s_address,$s_city,$s_state,$s_country,$s_zipcode,$phone,$email,$card_type,$card_name,$card_num,$expires,$discount,$disc_type,$disc_discount,$shipping,$total,$giftissuer,$products) {
	global $bonus_points;
	$orderfeature = "";
	if ($orderflag == "Gift") $orderfeature = "Order was made with gift certificate.\n";
	elseif ($orderflag == "Reward") $orderfeature = "Order was made with $bonus_points.\n";
	$template = eregi_replace("%ORDERID","$orderid",
	eregi_replace("%ORDERFEATURE",$orderfeature,
	eregi_replace("%UNAME","$uname",
	eregi_replace("%FNAME","$fname",
	eregi_replace("%LNAME","$lname",
	eregi_replace("%B_ADDRESS","$b_address",
	eregi_replace("%B_CITY","$b_city",
	eregi_replace("%B_STATE","$b_state",
	eregi_replace("%B_COUNTRY","$b_country",
	eregi_replace("%B_ZIPCODE","$b_zipcode",
	eregi_replace("%S_ADDRESS","$s_address",
	eregi_replace("%S_CITY","$s_city",
	eregi_replace("%S_STATE","$s_state",
	eregi_replace("%S_COUNTRY","$s_country",
	eregi_replace("%S_ZIPCODE","$s_zipcode",
	eregi_replace("%PHONE","$phone",
	eregi_replace("%EMAIL","$email",
	eregi_replace("%CARDTYPE","$card_type",
	eregi_replace("%CARDNAME","$card_name",
	eregi_replace("%CARDNUMBER","$card_num",
	eregi_replace("%EXPDATE","$expires",
	eregi_replace("%DISCOUNT",($discount > 0 ? "Discount: \$$discount\n" : ""),
	eregi_replace("%SHIPPING","Shipping cost: \$$shipping",
	eregi_replace("%DISC_COUPON",
($disc_type=="Fixed" ? "Discount coupon: \$$disc_discount\n" : "").
($disc_type=="Percent" ? "Discount coupon: ".(float)$disc_discount."%\n" : ""),
	eregi_replace("%TOTAL","Total: \$$total",
	eregi_replace("%O_STATUS",$order_status,
	eregi_replace("%O_DATE",$order_date,
	eregi_replace("%GIFTISSUER","$giftissuer",
	eregi_replace("%PRODUCTS","$products",
	$template
	)))))))))))))))))))))))))))));
}
function make_products_line(&$line,$template,$productid,$amount,$price,$product) {
	$productid_ = sprintf("%-5s",$productid);
	$amount_ = sprintf("%-6s",$amount);
	$price_ = sprintf("%-8s",$price);
	$line .= eregi_replace("%PRODUCTID","$productid_",
	eregi_replace("%AMOUNT","$amount_",
	eregi_replace("%PRICE","$price_",
	eregi_replace("%PRODUCTNAME","$product",
	$template
	))));
	$line .= "\n";
}
function process_template_gift(&$template,$purchaser,$recipient,$gamount,$message,$remail,$cert) {
	$template = eregi_replace("%PURCHASER","$purchaser",
	eregi_replace("%RECIPIENT","$recipient",
	eregi_replace("%GIFTAMOUNT","$gamount",
	eregi_replace("%MESSAGE","$message",
	eregi_replace("%REMAIL","$remail",
	eregi_replace("%GIFTCERT","$cert",
	$template
	))))));
}
function process_template_retrieve_password(&$template, $login, $password, $firstname, $lastname) {
	$template = eregi_replace("%USERNAME", "$login",
	eregi_replace("%PASSWORD", "$password",
	eregi_replace("%FNAME", "$firstname",
	eregi_replace("%LNAME", "$lastname", $template))));
}
function process_template_coupon(&$template,$coupon,$recipient,$disc_discount,$disc_type,$disc_count,$day,$month,$year) {
	switch($disc_type) {
	case "Fixed":
		$discount = "\$$disc_discount";
		break;
	case "Percent":
		$disc_discount = (float)$disc_discount;
		$discount = "$disc_discount%";
		break;
	default:
		$discount = "";
	}
	$purchases = ($disc_count==1 ? "purchase" : "$disc_count purchases");
	$expdate = date("jS of F, Y",mktime(0,0,0,$month,$day,$year));
	$template = eregi_replace("%COUPON","$coupon",
	eregi_replace("%RECIPIENT","$recipient",
	eregi_replace("%DISCOUNT","$discount",
	eregi_replace("%PURCHASES","$purchases",
	eregi_replace("%EXPDATE","$expdate",
	$template
	)))));
}
?>
