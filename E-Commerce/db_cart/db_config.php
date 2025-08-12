<?php 
// db connection
if (!defined("DB_SERVER")) define("DB_SERVER", "localhost");
if (!defined("DB_NAME")) define("DB_NAME", "db");
if (!defined("DB_USER")) define ("DB_USER", "user");
if (!defined("DB_PASSWORD")) define ("DB_PASSWORD", "pw");

// db tables (change the names if you need)
define("ORDERS", "db_cart_orders");
define("ORDER_ROWS", "db_cart_rows");
define("SHIP_ADDRESS", "db_cart_shipment");

// cart "globals"
define("CURRENCY", "£"); // use "", "$", "£" or "¥"
define("INCL_VAT", true);
define("VAT_VALUE", 19); // the standard VAT is used by methods if the vat value is not filled
define("SITE_MASTER", "Your shop"); // the contact information for the order confirmation
define("SITE_MASTER_MAIL", "webmaster@shop.com");
define("MAIL_ENCODING", "iso-8859-1"); // change is if you need...
define("DATE_FORMAT", "d-m-Y");
define("RECOVER_ORDER", false); // if this value is true an old order is available for old orders from customers, use "false" to remove the old order while the next access
define("VALID_UNTIL", 7 * 86400); // the value of seconds how long an old order is valid (default 7 days) and will be recoverd

// some filename constants, you have to change this!
// use different names for the stock examples
$use_stock = true; // switch between true and false to use the variabels for the stock examples or not
if ($use_stock) {
	$catalog = "db_cart_stock_example.php";
	$checkput = "db_cart_checkout_stock_example.php";
	$confirm = "db_cart_stock_confirm.php";
} else {
	$catalog = "db_cart_example.php";
	$checkput = "db_cart_checkout_example.php";
	$confirm = "db_cart_confirm.php";
}
define("PROD_IDX", $catalog); 
define("CHECKOUT", $checkput);
define("CONFIRM", $confirm); 
?>