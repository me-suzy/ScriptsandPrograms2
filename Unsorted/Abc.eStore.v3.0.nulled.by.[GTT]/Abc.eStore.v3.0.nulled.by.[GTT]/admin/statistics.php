<?php

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------

session_start();

include ("config.php");
include ("settings.inc.php");
include_once ("header.inc.php");

//
// if session is not registered go to login page

if( !isset( $_SESSION["admin"] ) && !isset( $_SESSION["demo"] ) )
	abcPageExit("<Script language=\"javascript\">window.location=\"login.php\"</script>");

echo "<h2>".$lng[795]."</h2>";

$sql_count = "select * from ".$prefix."store_category";
$result_count = mysql_query ($sql_count);
$total_categories = mysql_num_rows($result_count);
	
$limit = 12; 
$query_count = " SELECT * FROM ".$prefix."store_stats"; 
$result_count = mysql_query($query_count); 
$totalrows = mysql_num_rows($result_count); 
if( $totalrows == 0 ) 
	$totalrows = 1;
if( empty($page) )
	$page = 1;
$limitvalue = $page * $limit - ($limit); 
$query = "SELECT * from ".$prefix."store_stats order by date asc LIMIT $limitvalue, $limit "; 
$result = mysql_query($query) or die("Error: " . mysql_error()); 
$count_result = mysql_num_rows($result);

// Top stats
// total hits
$hits_total = mysql_query("SELECT sum(hits) as hits_sum FROM ".$prefix."store_stats");
$row_hits = mysql_fetch_array($hits_total);
$quan_hits = $row_hits['hits_sum'];
$quan_form_hits = number_format($quan_hits);

$hits_total = mysql_query("SELECT avg(hits) as a FROM ".$prefix."store_stats");
$row = mysql_fetch_array($hits_total);
$ave_hits = $row['a'];
$ave_hits = sprintf("%.0f", $ave_hits);
$ave_hits = number_format($ave_hits);

// total sales
$sales_total = "SELECT sum(prod_total) as sales FROM ".$prefix."store_order_sum where status='2'"; 
$sales_total_result = mysql_query($sales_total) or die(mysql_error()); 
while( $sales_total_row = mysql_fetch_array($sales_total_result) )
{ 
	$quan_sales = $sales_total_row['sales']; 
	$quan_sales_form = number_format($quan_sales, 2);

	$avg_sales = $quan_sales / $totalrows;
	$avg_sales = number_format($avg_sales, 2);
}
	
	
// total customers
$customers_total = "SELECT count(*) as customers FROM ".$prefix."store_customer"; 
$customers_total_result = mysql_query($customers_total) or die(mysql_error()); 
while( $customers_total_row = mysql_fetch_array($customers_total_result) )
	$quan_customers = $customers_total_row['customers'];
$ave_customers =  $quan_customers / $totalrows; 
$ave_customers = number_format($ave_customers);
$quan_customers_form = number_format($quan_customers);

echo "
<table width=\"90%\" border=\"1\" bordercolor=\"#e6e6e6\" cellspacing=\"0\" cellpadding=\"4\">
<tr bgcolor=\"#e6e6e6\">
<td>&nbsp;</td>
<td align=\"center\"><b>".$lng[796]."</b></td>
<td align=\"center\"><b>".$lng[797]."</b></td>
<td align=\"center\"><b>".$lng[798]."</b></td>
</tr>
<tr>
<td><b>".$lng[799].":</b></td>
<td align=\"center\">$quan_form_hits</td>
<td align=\"center\">$currency$quan_sales_form</td>
<td align=\"center\">$quan_customers_form</td>
</tr>
<tr>
<td><b>".$lng[800].":</b></td>
<td align=\"center\">$ave_hits</td>
<td align=\"center\">$currency$avg_sales</td>
<td align=\"center\">$ave_customers</td>
</tr>
</table><br>";

// end of top stats

echo "
<br>
<table border=\"1\" bordercolor=\"#e0e0e0\" cellpadding=\"4\" cellspacing=\"0\" width=\"90%\">
<tr bgcolor=\"#e6e6e6\">
<td align=\"center\" height=\"25\" colspan=\"2\"><b>".$lng[801]." $page<b></td></tr>";
	
while ($row = mysql_fetch_array($result))
{ 
	$date = $row['date']; 
	$hits = $row['hits'];
	
	$order_sum_query = "SELECT sum(prod_total) as monthly_sales FROM ".$prefix."store_order_sum where (date like '$date%' and status='2')";
	$order_sum_result = mysql_query($order_sum_query) or die( mysql_error() );
	while( $row_sum_result = mysql_fetch_array($order_sum_result) )
		$sales = $row_sum_result['monthly_sales']; 
	
	$customers_count_query = "SELECT count(*) as monthly_customers FROM ".$prefix."store_customer where (date like '20$date%')"; 
	$customers_count_result = mysql_query($customers_count_query) or die(mysql_error()); 
	while( $customers_count_row = mysql_fetch_array($customers_count_result) )
		$customers = $customers_count_row['monthly_customers']; 
		
	if( $quan_sales == 0 )
		$quan_sales = 1;
	if( $quan_hits == 0 )
		$quan_hits = 1;
	if( $quan_customers == 0 )
		$quan_customers = 1;
		
	$sales_per = 100*($sales / $quan_sales);
	$hits_per = 100*($hits / $quan_hits);
	$customers_per = 100*($customers / $quan_customers);
	$sales = number_format($sales, 2);
	$hits = number_format($hits);
	$customers = number_format($customers);
	$sales_per = sprintf("%.2f", $sales_per);
	$hits_per = sprintf("%.2f", $hits_per);
	$customers_per = sprintf("%.2f", $customers_per);
	$month = substr($date,2,2);
	
	if( $month == "01" )
		$month_name = "Jan";
	if( $month == "02" )
		$month_name = "Feb";
	if( $month == "03" )
		$month_name = "Mar";
	if( $month == "04" )
		$month_name = "Apr";
	if( $month == "05" )
		$month_name = "May";
	if( $month == "06" )
		$month_name = "Jun";
	if( $month == "07" )
		$month_name = "Jul";
	if( $month == "08" )
		$month_name = "Aug";
	if( $month == "09" )
		$month_name = "Sep";
	if( $month == "10" )
		$month_name = "Oct";
	if( $month == "11" )
		$month_name = "Nov";
	if( $month == "12" )
		$month_name = "Dec";
	
	$year = substr( $date, 0, 2 );
	$real_date = "$month_name '$year";

	echo "<tr><td colspan=\"2\"><b>$real_date</b></td></tr>";
	echo "<tr><td>".$lng[796].":</td>";
	echo "<td>";
	
	if( $hits_per !== "0.00" )
		echo "<img src=\"images/stat_blue.gif\" height=\"6\" width=\"$hits_per%\"><br>";

	echo "$hits ".$lng[796]." ($hits_per%)</td>";
	echo "</tr>";

	echo "<tr><td width=\"20%\">".$lng[797].":</td>";
	echo "<td>";

	if( $sales_per !== "0.00" )
		echo "<img src=\"images/stat_red.gif\" height=\"6\" width=\"$sales_per%\"><br>";
	
	echo"$currency$sales ($sales_per%)</td>";
	echo "</tr>";
	
	echo "<tr><td>".$lng[798].":</td>";
	echo "<td>";

	if( $customer_per !== "0.00" )
		echo "<img src=\"images/stat_green.gif\" height=\"6\" width=\"$customers_per%\"><br>";

	echo "$customers ($customers_per%)</td>";
} 
echo "</tr></table>";

// Display links at the bottom to indicate current page and number of pages displayed
$numofpages = ceil($totalrows / $limit);
echo "<p>";

if( $numofpages > 1 )
	echo "<b>".$lng[802].":</b> ";
 
if( $page != 1 )
{
	$pageprev = $page - 1; 
	echo "<a href=\"$PHP_SELF?page=$pageprev\"><< </a>&nbsp;";
} 
 
for( $i = 1; $i <= $numofpages; $i++ )
{
	if( $numofpages > 1 )
		if( $i == $page )
			echo "&nbsp;".$i."&nbsp;";
		else
			echo "&nbsp;<a href=\"$PHP_SELF?page=$i\">$i</a>&nbsp;";
}

if( $totalrows - ($limit * $page) > 0)
{
	$pagenext = $page + 1; 
	echo "<a href=\"$PHP_SELF?page=$pagenext\"> >></a>";
}
echo "</p>";

// Products popularity
$popularity_query = "SELECT sum(popularity) as pop_tot FROM ".$prefix."store_inventory"; 
$popularity_query_result = mysql_query($popularity_query) or die(mysql_error()); 

while( $popularity_query_row = mysql_fetch_array($popularity_query_result) )
	$total_popularity = $popularity_query_row['pop_tot']; 

if( isset( $_POST['pop_limit'] ) )
	$pop_limit = $_POST['pop_limit'];
else
	$pop_limit = "limit 10";

echo "
<br><form method=\"post\" action=\"statistics.php\" name=\"filter\">
<p>".$lng[803]." 
<select name=\"pop_limit\">
<option value=\"limit 5\" ";if($pop_limit=="limit 5"){echo "selected";}echo">5</option>
<option value=\"limit 10\" ";if($pop_limit=="limit 10"){echo "selected";}echo">10</option>
<option value=\"limit 25\" ";if($pop_limit=="limit 25"){echo "selected";}echo">25</option>
<option value=\"limit 50\" ";if($pop_limit=="limit 50"){echo "selected";}echo">50</option>
<option value=\"limit 100\" ";if($pop_limit=="limit 100"){echo "selected";}echo">100</option>
<option value=\"limit 999999\" ";if($pop_limit=="limit 999999"){echo "selected";}echo">All</option> 
</select>
 ".$lng[804]." 
<input name=\"submit\" class=\"submit\" type=\"submit\" value=\"".$lng[805]."\"></p>
</form>";

echo "
<table border=\"1\" bordercolor=\"#e0e0e0\" cellpadding=\"4\" cellspacing=\"0\" width=\"90%\">
<tr bgcolor=\"#e6e6e6\">
<td height=\"25\" align=\"center\" colspan=\"3\"><b>".$lng[806]."</b></td></tr>";
					
$query = "select * from ".$prefix."store_inventory order by popularity desc $pop_limit"; 
$result = mysql_query($query);
					
$no = 0;
while( $row = mysql_fetch_array( $result ) )
{ 
	$title = $row["title"];
	$product = $row["product"];
	$popularity = $row["popularity"];
	$cat_id = $row["cat_id"];
	$title = substr($title,0,20);
	$title = stripslashes($title);
	$no = $no + 1;
	$popularity_per = 100*($popularity / $total_popularity);
	$popularity_form = number_format($popularity);
	$pop_form_per = number_format($popularity_per, 2);
	echo "
		<tr>
		<td width=\"10%\" align=\"center\"><b>$no.</b></td>
		<td width=\"20%\"><a href=\"view_product.php?product=$product\" target=\"_blank\">$title";

	if( strlen( $title ) >= 20 )
		echo"..";
	echo"</a></td>
		<td>";

	if( $pop_form_per !== "0.00" )
		echo "<img src=\"images/stat_green.gif\" height=\"6\" width=\"$popularity_per%\"><br>";
	
	echo "$popularity_form ($pop_form_per%)</td></tr>";
}

echo "</table><br>\n\n";

include_once ("footer.inc.php");

?>
