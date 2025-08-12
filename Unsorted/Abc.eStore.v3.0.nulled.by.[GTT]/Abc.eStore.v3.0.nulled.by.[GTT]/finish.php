<?php

// this file may need customising to deal with the suitable parameters
// passed back from the secure payment provider.

// "cart_order_id" and "total" must be passed back from the secure payment provider.
// This enables the database to be updated to confirm payment has been made.
// If not all orders shall become redundant!

include_once ("header.php");

if ( isset ( $routine ) )
	if ( $routine == "skipjack" ) {
	
		if ( $szIsApproved == 1 )
			$fail = 0;
		else	$fail = 1;
		
		if ( isset ( $session_id ) )
			$session = $session_id;
			
		mkdir ($session);
		chmod( $session, 0777 );
	}
					

echo "<td width=\"100%\" valign=\"top\">";

if( $fail ) {
	
	echo "<p align=\"center\"><br><br>".$lng[74]."</p>";
	include_once ("footer.php");
	exit;
}

// if "cart_order_id" exists
if( isset( $cart_order_id ) ) {
	
	echo "<p align=\"center\"><br><br>".$lng[75]."</p>"
		. "<p align=\"center\"><b>".$lng[76].":</b> $cart_order_id"
		. "<p align=\"center\"><b>".$lng[77].":</b> $currency$total"
		. "<p align=\"center\">".$lng[78]."<br></p>";
		
	// update order
	mysql_query("update ".$prefix."store_order_sum SET sec_order_id='c' where cart_order_id='$cart_order_id'");
	
	// Process auto-shipment
	
	$contents = get_contents($cart_order_id, $sale, $prefix);
	
	$shipment = array();
	$ship_count = 0;
	
	$num_items = num_items($prefix,$cart_order_id);

	$x = 0;
	while( $x != $num_items ) {
		
		$package = $contents[ "package" ][ $x ];

		if( !empty( $package ) )
		{
			$shipment[ $ship_count ][ "title" ] = $contents[title][$x];
			$shipment[ $ship_count ][ "package" ] = $package;
			$shipment[ $ship_count ][ "basename" ] = basename( $package );
			$ship_count ++;
		}
		$x++;
	}
	
	if( $ship_count > 0 ) {
		
		// Create tmp directory and send mail with URLs
		$target_dir = "shipment/" . $session;
		mkdir( $target_dir );
		chmod( $target_dir, 0777  );
		
		foreach( $shipment as $product )
		{
			$target_file = $target_dir . '/' . $product["basename"];
			copy( $product["package"], $target_file );
			chmod( $target_file, 0777  );
			$user_urls .= $site_url . '/' . $target_file;
			$user_urls .= "\n";
		}
		
		$rc = mysql_query("select email, name from ".$prefix."store_order_sum where cart_order_id='$cart_order_id'");
		$row_customer = mysql_fetch_array( $rc );
		$cust_email = $row_customer["email"];
		$cust_name = $row_customer["name"];
		
		$to = "\"$cust_name\" <$cust_email>";
		
		eval('$subject = "'.$lng[843].'";');
		eval('$body = "'.$lng[844].'";');

		$headers = "From: $site_email\r\n";
		$headers .= "Reply-To: $site_email\r\n";
		mail($to, $subject, $body, $headers);
		
		$to_support = "$site_email";
		$headers = "From: $site_email\r\n";
		$headers .= "Reply-To: $to\r\n";
		mail($to_support, $subject, $message, $headers);
		
		// Update sessions file.
		
		$session_file = "shipment/sessions.rc";
		$session_expired = 3600 * 48;
		
		if( file_exists( $session_file ) )
			$sessions = parse_ini_file( $session_file );
		else
			$sessions = array();
		$sessions[ $session ] = time() + $session_expired;
		
		foreach( $sessions as $sid => $tm )
			$lines[] = "$sid=$tm";
			
		$fd = fopen( $session_file, 'wb+' );
		fwrite( $fd, implode( "\n", $lines ) );
		fclose( $fd );
		
		chmod( $session_file, 0777 );
		
		if( $date_style == "0" )
			$dt = date("m/d/Y");
		elseif( $date_style == "1" )
			$dt = date("d/m/Y"); // EU date format
					
		mysql_query("update ".$prefix."store_order_sum SET status = 2, ship_date = '$dt' where cart_order_id='$cart_order_id'");
	}
	
	// clear shopping cart
	mysql_query( "delete from ".$prefix."store_shopping WHERE session='$session'");
}
else
	echo "<br><br><p align=\"center\">".$lng[79]."<a href=\"mailto:$site_email\">$site_email</a>.".$lng[80]."</p>";


include_once ("footer.php");

?>