<?php
	if (!eregi("index.php", $PHP_SELF)) 
	{
    	die ("You can't access this file directly...");
	}
	FFileRead("template.pay.htm",$content);
	$content=str_replace("{paypal_email}",get_setting("pay_pal_email"),$content);
	$content=str_replace("{sid}",get_setting("sid"),$content);
	$content=str_replace("{product_id}",get_setting("product_id"),$content);
?>