<?php

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------

session_start();

include ("config.php");
include ("settings.inc.php");

//
// if session is not registered go to login page

if( !isset( $_SESSION["admin"] ) && !isset( $_SESSION["demo"] ) )
	abcPageExit("<Script language=\"javascript\">window.location=\"login.php\"</script>");

echo "
<HTML>
<head>
<title>".$lng[724]." - $order_id</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">

<link rel=stylesheet href=\"style.css\" type=\"text/css\">
</head>

<body bgcolor=\"#ffffff\">";

extract( $_GET );
extract( $_POST );

// view order content
// find all order records from database
$sql_select = mysql_query( "select * from ".$prefix."store_order_sum where cart_order_id='$order_id'");
$totalrows = mysql_num_rows($sql_select);

if( empty($order_id) )
	echo "<br><br><p align=\"center\">".$lng[725]."<br><br><a href=\"your_orders.php\">".$lng[726]."</a>.</p>";

if( $totalrows == 0 )
	echo "<br><br><p align=\"center\">".$lng[727]." $order_id ".$lng[728]."<br><br><a href=\"your_orders.php\">".$lng[729]."</a>.</p>";

if( $totalrows !== 0 )
{    
	while( $row = mysql_fetch_array($sql_select) )
	{
		$order_id = $row["cart_order_id"]; 
		$status = $row["status"]; 
		$time = $row["time"];
  	    $year = substr($row["date"],0,2);
        $month = substr($row["date"],2,2);
        $day = substr($row["date"],4,2);
        $prod_total = $row["prod_total"];
		$name_d = $row["name_d"];
		$add_1_d = $row["add_1_d"];
		$add_2_d = $row["add_2_d"];
		$town_d = $row["town_d"];
		$county_d = $row["county_d"];
		$postcode_d = $row["postcode_d"];
		$country_d = $row["country_d"];
		$name = $row["name"];
		$add_1 = $row["add_1"];
		$add_2 = $row["add_2"];
		$town = $row["town"];
		$county = $row["county"];
		$postcode = $row["postcode"];
		$country = $row["country"];
		$phone = $row["phone"];
		$total_tax = $row["total_tax"];
		$total_ship = $row["total_ship"];
		$prod_total = $row["prod_total"];
		$subtotal = $row["subtotal"];
		$ip = $row["ip"];
		$sec_order_id = $row["sec_order_id"];
		$ship_date = $row["ship_date"];
	} // end while
	
	switch( $date_style )
	{
	case "0":	// US date format
		$date="$month/$day/$year";
		break;
	
	case "1":	// EU date format
		$date = "$day/$month/$year";
		break;
	}
			
           
	if ($status==0){$status=$lng[730];}
	if ($status==1){$status=$lng[731];}
	if ($status==2){$status=$lng[732];}
	if ($status==3){$status=$lng[733];}
			
	$site_address = nl2br($site_address);
			
	echo "<table width=\"500\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			<tr>
			<td align=\"left\" width=\"350\" valign=\"top\"><b>$site_name<br>$site_url</b><br><br><b>".$lng[734].":</b> $order_id</td>
			<td align=\"left\" width=\"150\" valign=\"top\">$site_address<br>".$lng[735].": $site_phone<br>".$lng[736].": $site_fax<br>".$lng[737].": $site_email</td>
			</tr>
			</table><br><hr width=\"500\" size=\"1\"><br>";
	
	echo "<table align=\"center\" valign=\"top\" width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\" bordercolor=\"#cccccc\">
			<tr bgcolor=\"#ffffff\">
					<td><b>".$lng[738].":</b></td><td><b>".$lng[739].":</b></td>";
	
	echo "<tr bgcolor=\"$bg_colour\">
    <td valign=\"top\" width=\"250\" align=\"left\">$name_d<br>$add_1_d<br>";if(!empty($add_2_d)){echo"$add_2_d<br>";}echo"$town_d<br>$county_d<br>$postcode_d<br>$country_d</td>
    <td valign=\"top\" align=\"left\" width=\"250\">$name<br>$add_1<br>";if(!empty($add_2)){echo"$add_2<br>";}echo"$town<br>$county<br>$postcode<br>$country<br>Tel: $phone</td></tr></table><br>";

	echo "<table align=\"center\" valign=\"top\" width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\" bordercolor=\"#cccccc\">
			<tr bgcolor=\"#ffffff\">
				<td valign=\"top\" align=\"left\"><b>".$lng[740].":</b><br><br></td>
			    <td valign=\"top\" width=\"100\" align=\"left\"><b>".$lng[741].":</b><br><br></td>
			</tr>";
				    
    // select order items
	$sql_select = mysql_query( "select * from ".$prefix."store_order_inv where cart_order_id='$order_id'");
    $totalrows = mysql_num_rows($sql_select);
    
	while( $row = mysql_fetch_array($sql_select))
	{
		$title = $row["title"]; 
		$quantity = $row["quantity"]; 
		$price = $row["price"];
		$product = $row["product"];
	    $totalprice = $price*$quantity;
		$totalprice  =  sprintf("%.2f", $totalprice);
		$title  =  stripslashes($title);
		
		echo"<tr bgcolor=\"#ffffff\">
			<td valign=\"top\" align=\"left\"><li>$title (".$lng[742].": $quantity) [".$lng[743]." = $product]</li><br><br></td>
			<td valign=\"top\" align=\"right\">
			<table align=\"right\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			<tr bgcolor=\"#ffffff\">
				<td align=\"left\" width=\"100\">$currency$totalprice</td>
			</tr>
			</table></td>
				</tr>";
	} // end while
							
	echo "</table></td></tr>";
	echo"</table></tr></td></table><br>";
	
	//payment info
    echo "<table align=\"center\" valign=\"top\" width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\" bordercolor=\"#cccccc\">
					<tr bgcolor=\"#ffffff\">
							<td colspan=\"2\"><b>".$lng[744].":</b></td>";
			
	echo "<tr bgcolor=\"$bg_colour\">
            <td valign=\"top\" width=\"33%\" align=\"left\"><b>Status:</b><br>".$lng[745]."<br><b>".$lng[746].":</b><br>$date at $time<br></td>
            <td valign=\"top\" align=\"right\">
			<table align=\"right\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
				<tr>
					<td><b>".$lng[747].":</b></td>
					<td width=\"100\">$currency$subtotal</td>
				</tr>
				<tr>
				  <td><b>".$lng[748].":&nbsp;&nbsp;</b></td>
				  <td width=\"100\">$currency$total_ship</td>
				</tr>
				<tr>
				   <td><b>".$lng[749]." ($site_tax%):</b></td>
				   <td width=\"100\">$currency$total_tax</td>
				 </tr>
				 <tr>
				   <td></td>
				   <td width=\"100\">---------------</td>
				 </tr>
				 <tr>
				   <td><b>".$lng[750].":</b></td>
				  <td width=\"100\"><b>$currency$prod_total</b></td>
					<tr>
				   <td></td>
				   <td width=\"100\">---------------</td>
				 </tr>
				 </tr>
				</table>
				</td></tr>";
					
	echo "</table><br><hr width=\"500\" size=\"1\"><br><p align=\"center\">".$lng[751]."</p></body></html>";
}
?>