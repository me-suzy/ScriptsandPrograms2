<?php

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------

session_start();

include ("config.php");
include( "settings.inc.php");
$url="index";
include_once ("header.inc.php");

//
// if session is not registered go to login page

if( !isset( $_SESSION["admin"] ) && !isset( $_SESSION["demo"] ) )
	abcPageExit("<Script language=\"javascript\">window.location=\"login.php\"</script>");

echo"<h2>".$lng[383]."</h2>";

if ( $_SESSION['demo'] )
echo "<p><font color='red'>".$lng[384]."</font></p>";

extract( $_POST );
extract( $_GET );

if( $task == "order" )
{
	echo "<p>".$lng[385].": $order_id</p>";
	echo "<p><a href=\"javascript:decision('".$lng[386]."','dim_orders.php?task=order&save=save&order_id=$order_id')\"><b>".$lng[387]."</b></a> | <a href=\"javascript:decision('".$lng[388]."','dim_orders.php?task=order&delete=delete&order_id=$order_id')\"><b>".$lng[389]."</b></a> | <a href=\"javascript:history.back()\"><b>".$lng[390]."</b></a></p>";
	if( $save && !$_SESSION["demo"] )
	{
		mysql_query("update ".$prefix."store_order_sum set sec_order_id='Resolved' where cart_order_id='$order_id'");
		echo "<Script language=\"javascript\">window.location=\"dim_orders.php\"</script>";
	}
	
	if( $delete && !$_SESSION["demo"] )
	{
		mysql_query("delete from ".$prefix."store_order_sum where cart_order_id='$order_id'");
		mysql_query("delete from ".$prefix."store_order_inv where cart_order_id='$order_id'");
		echo "<Script language=\"javascript\">window.location=\"dim_orders.php\"</script>";
	}

	// view order content
	// find all order records from database
	$sql_select = mysql_query("select * from ".$prefix."store_order_sum where cart_order_id='$order_id'");
	$totalrows = mysql_num_rows($sql_select);

	if( empty($order_id) )
		abcPageExit( "<br><p>".$lng[391]."
			 <br><br>".$lng[392]."</p>" );

	if( $totalrows == 0 )
	{
		eval('$_msg="'.$lng[393].'";');
		abcPageExit( "<br><p>".$_msg."
			<br><br>".$lng[394]."</p>" );
	}

	$row = mysql_fetch_array($sql_select);

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
	$comments = $row["comments"];

	switch( $date_style )
	{
	case "0":	// US date format
		$date="$month/$day/$year";
		break;
	
	case "1":	// EU date format
		$date = "$day/$month/$year";
		break;
	}

	if ($status==0) {$txtstatus=$lng[395];$pay_status=$lng[396];}
	if ($status==1) {$txtstatus=$lng[397];$pay_status=$lng[398];}
	if ($status==2) {$txtstatus=$lng[399];$pay_status=$lng[400];}
	if ($status==3) {$txtstatus=$lng[401];$pay_status=$lng[402];}

	echo "
<br>
<table border=\"1\" bordercolor=\"#e0e0e0\" cellpadding=\"4\" cellspacing=\"0\" width=\"95%\">
<tr bgcolor=\"#e6e6e6\">
<td colspan=\"2\"><b>".$lng[403]." - $order_id</b></td>
</tr>";

	echo "
<tr>
<td valign=\"top\" width=\"33%\">
<b>".$lng[404].":</b><br>
$name_d<br>
$add_1_d<br>";

if (!empty($add_2_d))
{
	echo "$add_2_d<br>";
}

echo "$town_d<br>
$county_d<br>
$postcode_d<br>
$country_d<br><br>
<b>".$lng[405].":</b><br>
$txtstatus<br><br>
<b>".$lng[406].":</b><br>
$date at $time<br><br>
<b>".$lng[407].":</b><br>
$ip<br></td>

<td valign=\"top\">";

echo "
<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
<tr>
<td valign=\"top\"><b>".$lng[408].":</b><br><br></td>
<td valign=\"top\"><b>".$lng[409].":</b></td>
</tr>";
	    
    // select order items
	$sql_select = mysql_query("select * from ".$prefix."store_order_inv where cart_order_id='$order_id'");
    $totalrows = mysql_num_rows($sql_select);
	
	while( $row = mysql_fetch_array($sql_select) )
	{
		$title = $row["title"]; 
		$quantity = $row["quantity"]; 
		$price = $row["price"];
		$product = $row["product"];
	    $totalprice = $price*$quantity;
		$totalprice = sprintf("%.2f", $totalprice);
		$title = stripslashes($title);
	
	echo "
<tr>
<td valign=\"top\">
<ul>
<li><a href=\"../view_product.php?product=$product\" target=\"_blank\">$title</a> (".$lng[410].": $quantity) [".$lng[411]." = $product]</li>
</ul>
</td>
<td valign=\"top\">$currency$totalprice</td>
</tr>";
	}

echo "</table>
</td>
</tr>";

	echo "</table>";


	//payment info
	echo "<br>
<table border=\"1\" bordercolor=\"#e0e0e0\" cellpadding=\"4\" cellspacing=\"0\" width=\"95%\">
<tr bgcolor=\"#e6e6e6\">
<td colspan=\"2\"><b>".$lng[412].":</b></td></tr>";
			
	echo "
<tr>
<td valign=\"top\" width=\"33%\">
<b>".$lng[413].":</b><br>
$name<br>
$add_1<br>";

if (!empty($add_2))
{
	echo "$add_2<br>";
}

echo "$town<br>
$county<br>
$postcode<br>
$country<br>
Tel: $phone<br><br>
<b>Payment:</b><br>
$pay_status</td>

<td valign=\"top\">

<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"60%\">
<tr>
<td><b>".$lng[414].":</b></td>
<td>$currency$subtotal</td>
</tr>

<tr>
<td><b>".$lng[415].":&nbsp;&nbsp;</b></td>
<td>$currency$total_ship</td>
</tr>

<tr>
<td><b>".$lng[416]." ($site_tax%):</b></td>
<td>$currency$total_tax</td>
</tr>

<tr>
<td>&nbsp;</td>
<td>_______________</td>
</tr>

<tr>
<td><b>".$lng[417].":</b></td>
<td><b>$currency$prod_total</b></td>
<tr>
</table>

</td></tr>";
					
	echo "</table><br>";

    echo "<br>\n
<p><a href=\"javascript:decision('".$lng[386]."',
		'dim_orders.php?task=order&save=save&order_id=$order_id')\"><b>".$lng[387]."</b></a> | <a href=\"javascript:decision('".$lng[388]."',
		'dim_orders.php?task=order&delete=delete&order_id=$order_id')\"><b>".$lng[389]."</b></a> | <a href=\"javascript:history.back()\"><b>".$lng[390]."</b></a></p>";
	
	echo "<br>";
} // end order content

else
{
	// default page content

	$sql_count = "select * from ".$prefix."store_order_sum where sec_order_id =''";
	$result_count = mysql_query ($sql_count);
	$total = mysql_num_rows($result_count);


	if( $total == 0 )
		abcPageExit( "<p>".$lng[418]."</p>" );
	
	if( empty($ddlimit) )
		$ddlimit = 25;
		
	echo "<p><b>".$lng[419].": $total</b></p>";
	echo "
<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"95%\">
<tr>
<form action=\"dim_orders.php\" method=\"post\" name=\"filter\">
<input type=\"hidden\" value=\"$searchStr\" name=\"searchStr\">

<td valign=\"top\">
".$lng[420]." <select name=\"ddlimit\" onchange=\"document.filter.submit();\">
						<option value=\"5\" ";  if($ddlimit==5){echo "selected";}     echo ">5</option>
						<option value=\"10\" ";  if($ddlimit==10){echo "selected";}   echo ">10</option>
						<option value=\"25\" ";  if($ddlimit==25){echo "selected";}   echo ">25</option>
						<option value=\"50\" ";  if($ddlimit==50){echo "selected";}   echo ">50</option>
						<option value=\"100\" ";  if($ddlimit==100){echo "selected";} echo ">100</option>
					</select> ".$lng[421]."
<input class=\"submit\" type=\"submit\" value=\"".$lng[422]."\">
</td>
</form>

<form action=\"orders.php?search=yes&ddlimit=$ddlimit\" method=\"post\">
<td align=\"right\">
".$lng[423].": <input class=\"textbox\" type=\"text\" name=\"searchStr\">&nbsp;<input type=\"submit\" class=\"submit\" value=\""
.$lng[424]."\">
</td>
</form>
</tr>
</table>";

	// set limit value for number of records to be shown per page 
	// query database to find total number of records to display 
	$limit = $ddlimit; 
	$query_count = " SELECT * FROM ".$prefix."store_order_sum where (cart_order_id like '%$searchStr%') and sec_order_id =''"; 
	$result_count = mysql_query($query_count);
	$totalrows = mysql_num_rows($result_count);

	if( empty($page) )
		$page = 1;
		
	$limitvalue = $page * $limit - ($limit); 
	if( $search !== "yes" )
		$query = "SELECT * FROM ".$prefix."store_order_sum where sec_order_id ='' order by date LIMIT $limitvalue, $limit";
	else
		$query = "SELECT * FROM ".$prefix."store_order_sum where (cart_order_id like '%$searchStr%') and sec_order_id ='' order by date LIMIT $limitvalue, $limit";

	$result = mysql_query($query) or die("Error: " . mysql_error()); 
	$count_result = mysql_num_rows($result);

	if( mysql_num_rows($result) == 0)
		if( $search !== "yes" )
			echo "<p><br>".$lng[425]."</p><br>";
		if( $search == "yes" )
			echo "<p><br>".$lng[426]." \"<b>$searchStr</b>\"!<br><br><a href=\"orders.php\" target=\"_self\">Reset Listings</a></p>";

	if( mysql_num_rows($result) > 0 && $search == "yes" )
		echo "<p><br>".$lng[427]." \"<b>$searchStr</b>\"!</p><br>";

	abcPrintNavigationBar( $totalrows, $count_result, $limit, $page, $searchStr );

	if( $count_result > 0 )
		echo "
<table border=\"1\" bordercolor=\"#e0e0e0\" cellpadding=\"4\" cellspacing=\"0\" width=\"95%\">
<tr bgcolor=\"e6e6e6\" height=\"25\">
<td align=\"center\" height=\"25\"><b>".$lng[428]."</b></td>
<td align=\"center\"><b>".$lng[429]."</b></td>
<td align=\"center\"><b>".$lng[430]."</b></td>
<td align=\"center\"><b>".$lng[432]."</b></td>
<td align=\"center\"><b>".$lng[433]."</b></td>
<td align=\"center\"><b>".$lng[434]."</b></td>
</tr>";
	
//	$bgcolour = $colour_3;

	while( $row = mysql_fetch_array($result))
	{
		$order_id = $row["cart_order_id"]; 
		$email = $row["email"];
		$status = $row["status"]; 
		$time = $row["time"];
		$year = substr($row["date"],0,2);
		$month = substr($row["date"],2,2);
		$day = substr($row["date"],4,2);
		$prod_total = $row["prod_total"];
		$ip = $row["ip"];
			
		switch( $date_style )
		{
		case "0":	// US date format
			$date="$month/$day/$year";
			break;
		
		case "1":	// EU date format
			$date = "$day/$month/$year";
			break;
		}			
			
		if( $bgcolour == $colour_3 )
			$bgcolour = $colour_4;
		elseif( $bgcolour == $colour_4 )
			$bgcolour =$colour_3;
					
	    if( $status == 0 ) {$status=$lng[435];}
		if( $status == 1 ) {$status=$lng[436];}
		if( $status == 2 ) {$status=$lng[437];}
		if( $status == 3 ) {$status=$lng[438];}
		
		$query = mysql_query ("select name from ".$prefix."store_customer where email='$email'");
		while( $row = mysql_fetch_array($query) )
			$name=$row["name"];
		
		echo "
<tr>
<td align=\"center\"><a href=\"dim_orders.php?task=order&order_id=$order_id\" target=\"_self\">$order_id</a></td>
<td align=\"center\">$status</td>
<td align=\"center\">$date $time</td>
<td align=\"center\">$name<br><a href=\"mailto:$email\">$email</a></td>
<td align=\"center\">$ip</td>
<td align=\"center\">$currency$prod_total</td>
</tr>";
	}
	
	echo "</table><br>\n";

	abcPrintNavigationBar( $totalrows, $count_result, $limit, $page, $searchStr );

	echo "<br>\n";
}// end if task !==order

include_once ("footer.inc.php");

?>