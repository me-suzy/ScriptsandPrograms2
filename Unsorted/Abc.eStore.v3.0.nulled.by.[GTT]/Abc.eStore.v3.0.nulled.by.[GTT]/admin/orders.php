<?php

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------

session_start();

include ("config.php");
include ("settings.inc.php");
$url="index";
include_once ("header.inc.php");

//
// if session is not registered go to login page

if( !isset( $_SESSION["admin"] ) && !isset( $_SESSION["demo"] ) )
	abcPageExit("<Script language=\"javascript\">window.location=\"login.php\"</script>");

echo "<h2>".$lng[669]."</h2>";

extract( $_GET );
extract( $_POST );

if( $task == "order" )
{
	echo "<p><a href=\"javascript:history.back()\">".$lng[670]."</a></p>";

	if( $save && !$_SESSION['demo'])
	{
		mysql_query("update ".$prefix."store_order_sum set comments='$comments', status='$status', ship_date='$ship_date' where cart_order_id='$order_id'");
		echo "<p>".$lng[671]."</p>";
		abcPageExit( "<Script language=\"javascript\">window.location=\"orders.php\"</script>" );
	}
	
	if( $save && $_SESSION['demo'])
	{
		
		echo ("<font color='red'>".$lng[672]."</font><br><br>
		<a href='orders.php'>".$lng[673]."</a>");exit;
		
	}
	
	// view order content
	// find all order records from database
	
	$sql = "select * from ".$prefix."store_order_sum where cart_order_id='$order_id'";
	$sql_select = mysql_query( $sql );
	$totalrows = mysql_num_rows( $sql_select );
	
	if( empty( $order_id ) )
		echo "<p>".$lng[674]."<br><br><a href=\"orders.php\">".$lng[675]."</a></p>";
	
	if( $totalrows == 0 )
		echo "<p>".$lng[676]." $order_id ".$lng[677]."<br><br>
			<a href=\"orders.php\">".$lng[675]."</a></p>";
	
	if( $totalrows !== 0 )
	{
		while( $row = mysql_fetch_array( $sql_select ) )
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
			$comments = $row["comments"];
			$customercomments = htmlspecialchars($row['customercomments']);
			$user_discount = $row["user_discount"];
			
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
		
		switch( $status )
		{
		case 0: $txtstatus = $lng[395];//"Order Pending";
				$pay_status = $lng[396];//"Awaiting Confirmation";
				break;
		case 1: $txtstatus = $lng[397];//"Awaiting Shipping";
				$pay_status = $lng[398];//"Confirmed";
				break;
		case 2: $txtstatus = $lng[399];//"Order Shipped";
				$pay_status = $lng[400];//"Confirmed";
				break;
		case 3: $txtstatus = $lng[401];//"Order Declined";
				$pay_status = $lng[402];//"Not received";
				break;
		}
				
		echo "
<table border=\"1\" bordercolor=\"#e6e6e6\" cellspacing=\"0\" cellpadding=\"4\" width=\"95%\">
<tr bgcolor=\"#e0e0e0\" height=\"25\">
<td colspan=\"2\"><b>".$lng[678]." - $order_id</b></td>
</tr>";
				
echo "
<tr>
<td valign=\"top\" width=\"33%\"><b>".$lng[679].":</b><br>
$name_d<br>
$add_1_d<br>";

if(!empty($add_2_d))
{
	echo "$add_2_d<br>";
}
echo "
$town_d<br>
$county_d<br>
$postcode_d<br>
$country_d<br><br>
<b>".$lng[680].":</b><br>
$txtstatus<br><br>
<b>".$lng[681].":</b><br>
$date at $time<br><br>
<b>".$lng[682].":</b><br>$ip<br></td>
<td valign=\"top\">";
				
echo "
<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
<tr>
<td valign=\"top\"><b>".$lng[683].":</b><br><br></td>
<td valign=\"top\"><b>".$lng[196].":</b><br></td>
<td valign=\"top\"><b>".$lng[684].":</b><br></td>
</tr>";
				
		// select order items
		$sql_select = mysql_query( "select * from ".$prefix."store_order_inv where cart_order_id='$order_id'");
		$totalrows = mysql_num_rows($sql_select);
	    
		while( $row = mysql_fetch_array( $sql_select ) )
		{
			$title = $row["title"]; 
			$quantity = $row["quantity"]; 
			$price = $row["price"];
			$product = $row["product"];
			$totalprice = $price*$quantity;
			$title = stripslashes($title);
			$totalprice = sprintf("%.2f", $totalprice);
			$atributes = nl2br($row["atributes"]);
			$amount_discount = $row["amount_discount"];
			echo "
<tr>
<td valign=\"top\">
<ul><li>
<a href=\"../view_product.php?product=$product\" target=\"_blank\">$title</a> (".$lng[685].": $quantity) [".$lng[686]." = $product]";
if ( !empty ($atributes) )
echo "<br><i>$atributes</i>";
echo "
</li>
</ul>
</td>
<td valign=\"top\">$amount_discount%</td>
<td valign=\"top\">$currency$totalprice</td>
</tr>

";
		} // end while
						
echo "</table></td></tr>";
		

//// payment info

echo "
<br>
<table border=\"1\" bordercolor=\"#e0e0e0\" cellpadding=\"4\" cellspacing=\"0\" width=\"95%\">
<tr bgcolor=\"#e6e6e6\" height=\"25\">
<td colspan=\"2\"><b>".$lng[687].":</b></td></tr>";
				
echo "
<tr>
<td valign=\"top\" width=\"33%\">
<b>".$lng[691].":</b><br>
$name<br>
$add_1<br>";

if (!empty($add_2))
{
	echo "$add_2<br>";
}
echo "
$town<br>
$county<br>
$postcode<br>
$country<br>
Tel: $phone<br><br>
<b>Payment:</b><br> $pay_status</td>

<td valign=\"top\">

<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"60%\">

<tr>
<td><b>".$lng[153].":</b></td>
<td>$user_discount%</td>
</tr>

<tr>
<td><b>".$lng[688].":</b></td>
<td>$currency$subtotal</td>
</tr>

<tr>
<td><b>".$lng[689]."&nbsp;&nbsp;</b></td>
<td>$currency$total_ship</td>
</tr>

<tr>
<td><b>".$lng[690].":</b></td>
<td>$currency$total_tax</td>
</tr>

<tr>
<td>&nbsp;</td>
<td>___________</td>
</tr>

<tr>
<td><b>".$lng[692]."l:</b></td>
<td><b>$currency$prod_total</b></td>
</tr>
</table>
</td></tr>";
echo "<tr bgcolor=\"#e6e6e6\" height=\"25\">
<td colspan=\"2\"><b>".$lng[693].":</b></td></tr><tr><td colspan=6>".$customercomments."</td></tr>";
echo "</table><br><br>";

//// shipping and comments
	
	    echo "<form method=\"post\" name=\"orders\" action=\"orders.php?task=order&order_id=$order_id&save=yes\">
		    <table width=\"60%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		    <tr>
	    	  <td valign=\"top\"><b>".$lng[694].":</b><br>";
	    	  
		if( $date_style == "0" )
			echo "(MM/DD/YYYY)";
		elseif( $date_style == "1" )
			echo"(DD/MM/YYYY)"; // EU date format
			
		echo "</td>
			      <td><input type=\"text\" name=\"ship_date\" class=\"textbox\" value=\"$ship_date\"><input type=\"checkbox\" name=\"shippedtoday\" value=\"Y\" onClick=\"javascript:setToToday();\">".$lng[695]."<br><br></td>
			    </tr>
				<tr>
			      <td><b>".$lng[937].":</b></td>
			      <td><select name=\"status\" >
			        <option value=\"0\"";if($status==0){echo "selected";}echo">".$lng[696]."</option>
					<option value=\"1\"";if($status==1){echo "selected";}echo">".$lng[697]."</option>
					<option value=\"2\"";if($status==2){echo "selected";}echo">".$lng[698]."</option>
					<option value=\"3\"";if($status==3){echo "selected";}echo">".$lng[699]."</option>
					</select><br><br></td>
			    </tr>
			    <tr>
			      <td valign=\"top\"><b>".$lng[700].":</b></td>
			      <td><textarea name=\"comments\" cols=\"50\" rows=\"4\" value=\"$comments\">$comments</textarea><br><br></td>
			    </tr>
			    <tr>
			      <td>&nbsp;</td>
			      <td>
				  <input type=\"submit\" class=\"submit\" value=\"".$lng[702]."\">&nbsp;&nbsp;<input type=\"submit\" class=\"submit\" name=\"note\" value=\"".$lng[701]."\" onClick=\"MM_openBrWindow('print_order.php?order_id=$order_id','image','width=550,height=400,scrollbars=1,resizable=1,menubar=1')\"></td>
			    </tr>
			  </table>
			";
	} //end if no rows
	
	echo "<br><p><a href=\"javascript:history.back()\">".$lng[703]."</a></p><br><br>";

}// end order content

// default page content
if( $task !== "order")
{
	$sql_count = "select * from ".$prefix."store_order_sum where sec_order_id <>''";
	$result_count = mysql_query ($sql_count);
	$total = mysql_num_rows($result_count);

	$sql_count_redundant = "select * from ".$prefix."store_order_sum where sec_order_id =''";
	$result_count_redundant = mysql_query ($sql_count_redundant);
	$total_redundant = mysql_num_rows($result_count_redundant);

	if( $total == 0 )
	{
		echo "<p>".$lng[704]."</p>";
		if( $total_redundant!==0 )
			echo $lng[705]." $site_url/finish.php!";

	}
	else
	{

	if( empty( $ddlimit ) )
		$ddlimit = 25;
	
	echo "<p><b>".$lng[706].": $total</b></p>";
	
	echo "
		<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\" width=\"95%\">
		<tr>
		<form action=\"orders.php\" method=\"post\" name=\"filter\">
		<input type=\"hidden\" value=\"$searchStr\" name=\"searchStr\">
		<td valign=\"top\">".$lng[707]." 
		<select name=\"ddlimit\" onchange=\"document.filter.submit();\">
			<option value=\"5\" ";if($ddlimit==5){echo "selected";}echo">5</option>
			<option value=\"10\" ";if($ddlimit==10){echo "selected";}echo">10</option>
			<option value=\"25\" ";if($ddlimit==25){echo "selected";}echo">25</option>
			<option value=\"50\" ";if($ddlimit==50){echo "selected";}echo">50</option>
			<option value=\"100\" ";if($ddlimit==100){echo "selected";}echo">100</option>
		</select> ".$lng[708]." <input type=\"submit\" class=\"submit\" value=\"".$lng[709]."\"></td>
		</form>
		
		<form action=\"orders.php?search=yes&ddlimit=$ddlimit\" method=\"post\">
		<td align=\"right\">".$lng[710].": <input type=\"text\" class=\"textbox\" name=\"searchStr\" value=\"$searchStr\">&nbsp;<input type=\"submit\" class=\"submit\" value=\"".$lng[711]."\"></td>
		</form>
		</tr></table>\n\n";

	// set limit value for number of records to be shown per page 
	// query database to find total number of records to display 
	
	$limit = $ddlimit; 
	$query_count = "SELECT * FROM ".$prefix."store_order_sum where (cart_order_id like '%$searchStr%') and sec_order_id <>''"; 
	$result_count = mysql_query($query_count); 
	$totalrows = mysql_num_rows($result_count); 

	if( empty($page) )
		$page = 1;
		
	$limitvalue = $page * $limit - ($limit); 
	if( $search !== "yes" )
		$query = "SELECT * FROM ".$prefix."store_order_sum where sec_order_id <>'' order by date LIMIT $limitvalue, $limit";
	else
		$query = "SELECT * FROM ".$prefix."store_order_sum where (cart_order_id like '%$searchStr%') and sec_order_id <>'' order by date LIMIT $limitvalue, $limit";
		
	$result = mysql_query($query) or die("Error: " . mysql_error()); 
	$count_result = mysql_num_rows($result);

	if( mysql_num_rows($result) == 0 )
		if( $search !== "yes" )
			echo "<p>".$lng[712]."</p>";
		else
			echo "<p>".$lng[713]." \"<b>$searchStr</b>\"!<br><br><a href=\"orders.php\" target=\"_self\">".$lng[714]."</a></p>";

	if( mysql_num_rows($result) > 0 && $search == "yes" )
		echo "<p>".$lng[715]." \"<b>$searchStr</b>\"!</p>";

	abcPrintNavigationBar( $totalrows, $count_result, $limit, $page, $searchStr );

	if( $count_result > 0 )
	{
		echo "
			<table border=\"1\" bordercolor=\"#e0e0e0\" cellpadding=\"4\" cellspacing=\"0\" width=\"95%\">
				<tr bgcolor=\"#e6e6e6\">
				<td align=\"center\" height=\"25\"><b>".$lng[716]."</b></td>
				<td align=\"center\"><b>".$lng[717]."</b></td>
				<td align=\"center\"><b>".$lng[718]."</b></td>
				<td align=\"center\"><b>".$lng[719]."</b></td>
				<td align=\"center\"><b>".$lng[720]."</b></td>
				<td align=\"center\"><b>".$lng[721]."</b></td>
				</tr>";
	}

	// $bgcolour=$colour_3;

	while( $row = mysql_fetch_array( $result ) )
	{
		$order_id = $row["cart_order_id"]; 
		$status = $row["status"];
		$email = $row["email"];
		$time = $row["time"];
  	    $year = substr($row["date"],0,2);
        $month = substr($row["date"],2,2);
        $day = substr($row["date"],4,2);
        $prod_total = $row["prod_total"];
		$ip = $row["ip"];
		
		switch( $date_style )
		{
		case "0":	// US date format
			$date = "$month/$day/$year";
			break;
		
		case "1":	// EU date format
			$date = "$day/$month/$year";
			break;
		}
		
		if( $bgcolour == $colour_3 )
			$bgcolour = $colour_4;
		elseif( $bgcolour == $colour_4 )
			$bgcolour =$colour_3;
				
	    if( $status == 0 )
	    	$status = $lng[435];
		if( $status == 1 )
			$status=$lng[436];
		if( $status == 2 )
			$status=$lng[437];
		if( $status == 3 )
			$status=$lng[438];
	
		$query = mysql_query ("select name from ".$prefix."store_customer where email='$email'");
		while( $row = mysql_fetch_array($query) )
			$name=$row["name"];
		
		echo "
			<tr>
			<td align=\"center\"><a href=\"orders.php?task=order&order_id=$order_id\" target=\"_self\">$order_id</a></td>
			<td align=\"center\">$status</td>
			<td align=\"center\">$date $time</td>
			<td align=\"center\">$name<br><a href=\"mailto:$email\">$email</a></td>
			<td align=\"center\">$ip</td>
			<td align=\"center\">$currency$prod_total</td>
			</tr>";
	}
	if( $count_result > 0 )
	echo "</table>\n\n";

	abcPrintNavigationBar( $totalrows, $count_result, $limit, $page, $searchStr );
	}
	if( $total_redundant > 0 )
	{
		eval('$_msg="'.$lng[722].'";');
		echo "<blockquote><p>".$_msg."</p>
				<b><a href=\"dim_orders.php\">".$lng[723]."</a>
			</b></blockquote>";
	}

}// end if task !==order

include_once ("footer.inc.php");

?>
