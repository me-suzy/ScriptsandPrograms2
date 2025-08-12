<?php

include_once("header.php");
include_once("left.php");

$lng['err'] = "";


 $lng['order_id'] = $order_id;

// contents if session is registered
if( isset( $_SESSION["valid_user"] ) )
{
	// find all order records from database
	$sql_select = mysql_query( "select * from ".$prefix."store_order_sum where cart_order_id='$order_id'");
	$totalrows = mysql_num_rows($sql_select);

	if( empty($order_id) )
		$lng['err'] .= $lng[203];

	if( $totalrows == 0 )
		$lng['err'] .= $lng[205] . " $order_id " . $lng[206];

	// get orders
	if( $totalrows !== 0 ) {
		
		while( $row = mysql_fetch_array($sql_select) ) {
			
		      	if ( $row['add_2_d'] != "" )
		      		$row['add_2_d'] = "<br>" . $row['add_2_d'];
		      	
		      	$row['year'] = substr($row["date"],0,2);
		        $row['month'] = substr($row["date"],2,2);
		        $row['day'] = substr($row["date"],4,2);
		                		        		
        		switch( $date_style ) {
        			
			case "0":	// US date format
				$row['date']="$row[month]/$row[day]/$row[year]";
				break;
			
			case "1":	// EU date format
				$row['date'] = "$day/$month/$year";
				break;
			}
						
			switch( $row['status'] )
			{
			case 0: $row['status'] = "Order Pending";
					$row['pay_status']="Awaiting Confirmation";
					break;
			case 1: $row['status'] = "Awaiting Shipping";
					$row['pay_status']="Confirmed";
					break;
			case 2: $row['status'] = "Order Shipped";
					$row['pay_status']="Confirmed";
					break;
			case 3: $row['status'] = "Order Declined";
					$row['pay_status']="Not received";
					break;
			}
        		
        		
        		$row[153] = $lng[153];
        		$row[196] = $lng[196];
        		$row[207] = $lng[207];
        		$row[208] = $lng[208];
        		$row[209] = $lng[209];
        		$row[210] = $lng[210];
        		$row[211] = $lng[211];
        		$row[212] = $lng[212];
        		$row[213] = $lng[213];
        		$row[214] = $lng[214];
        		$row[215] = $lng[215];
        		$row[216] = $lng[216];
        		$row[217] = $lng[217];
        		$row[218] = $lng[218];
        		$row[219] = $lng[219];
        		$row[220] = $lng[220];
        		$row[694] = $lng[694];
        		
        		$row['currency'] = $currency;
        		
        		$lng['order'][0] = $row;
        
        } // end while

	    // select order items
		$sql_select = mysql_query( "select * from ".$prefix."store_order_inv where cart_order_id='$order_id'");
        	$totalrows = mysql_num_rows($sql_select);
    
		while( $row = mysql_fetch_array($sql_select) )
		{
			$row['totalprice'] = $row['price']*$row['quantity'];
			$row['totalprice'] = sprintf("%.2f", $row['totalprice']);
			$row['price'] = sprintf("%.2f", $row['price']);
			$row['title'] = stripslashes($row['title']);
			
			$row[213] = $lng[213];
			
			$row['currency'] = $currency;
			
			$lng['order'][0]['products'][] = $row;
			
		} // end while
				
		
		// display payment info

		
	} //end if no rows
} //end if session is registered  
else {
	// if session is not registered
	$lng['err'] .= $lng[221] . "<br>";   
	
}//end if session is not registered 

// Processing templates

$tmpl = new Template ( "html/view_order.html" );

$lng['noerr'] = !$lng['err'];

$tmpl -> param ( 'lng', array ( $lng ) );
$tmpl -> param ( 'design_dir', "design/" . $design_directory . "/" );

echo $tmpl -> parse();


// Footer

include_once("right.php");
include_once("footer.php");


?>
