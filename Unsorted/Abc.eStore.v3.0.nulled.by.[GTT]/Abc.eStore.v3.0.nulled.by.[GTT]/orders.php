<?php

include_once("header.php");
include_once("left.php");

// if login is attempted
if( $email && $password ) {
	
	// If the user has just tried to log in
	$passwd = md5($password);
	$query = "select * from ".$prefix."store_customer where email='$email' and password=('$passwd')";
	$result = mysql_query($query);
	$res = mysql_fetch_array ($result);
  
	if( mysql_num_rows($result) > 0 )
	{
		// if they are in the database register the user for the session
		$valid_user = $email;
		$_SESSION["valid_user"] = $email;
		$_SESSION['customer_id'] = $res['customer_id'];
	}
	else
		echo "<p><div align='center'><font style=\"errorMessage\"><b>".$lng[235]."</b></font></p>";
}// end if ($email && $password)

if( isset( $_SESSION["valid_user"] ) ) {
	
	$valid_user = $_SESSION["valid_user"];
	
	// find all order records from database
	$sql_select = mysql_query( "select * from ".$prefix."store_order_sum where email='$valid_user' and sec_order_id <>'' order by date");
	$totalrows = mysql_num_rows($sql_select);

	if( $totalrows != 0 ) {
		
		$lng['noorders'] = "";
	
		while( $row = mysql_fetch_array($sql_select) ) {
			
			$order_id = $row["cart_order_id"]; 
			$status = $row["status"]; 
			$time = $row["time"];
			$year = substr($row["date"],0,2);
			$month = substr($row["date"],2,2);
			$day = substr($row["date"],4,2);
			$prod_total = $row["prod_total"];
			
			switch( $date_style )
			{
			case "0":	// US date format
				$date="$month/$day/$year";
				break;
			
			case "1":	// EU date format
				$date = "$day/$month/$year";
				break;
			}
			
			switch( $status )
			{
			case 0: $status = $lng[242]; break;
			case 1: $status = $lng[243]; break;
			case 2: $status = $lng[244]; break;
			case 3: $status = $lng[245]; break;
			}
			
			$lng['orders'][] = array (
			'order_id'=>$order_id,
			'status'=>$status,
			'datetime'=>$date . "-" . $time,
			'prodtotal'=>$currency . $prod_total,
			'246'=>$lng[246]
			);
			
		} // end while
	
	}
	else	{
	
		$lng['noorders'] = 1;
		$lng['orders'] = "";
		
	}

	$lng['nologin'] = "";

}
else {
	
	// if session is not registered display contents

	$lng['nologin'] = 1;
	
} //end if session is not registered 


// Processing templates

$tmpl = new Template ( "html/orders.html" );

$tmpl -> param ( 'lng', array ( $lng ) );
$tmpl -> param ( 'design_dir', "design/" . $design_directory . "/" );

echo $tmpl -> parse();


// Footer

include_once("right.php");
include_once("footer.php");


?>