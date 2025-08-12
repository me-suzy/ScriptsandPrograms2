<?php

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------

session_start();

include ("config.php");
include ("settings.inc.php");
$url = "index";
include_once ("header.inc.php");

//
// if session is not registered go to login page

if( !isset( $_SESSION["admin"] ) && !isset( $_SESSION["demo"] ) )
	abcPageExit("<Script language=\"javascript\">window.location=\"login.php\"</script>");

if( $_SERVER["REQUEST_METHOD"] == "POST" )
	extract( $_POST );
elseif( $_SERVER["REQUEST_METHOD"] == "GET" )
	extract( $_GET );

echo "<h2>".$lng[752]."</h2>";

// To delete product
if( $del == 1 && !$_SESSION['demo'])
{
	if( !empty($image) && $image !== "nophoto.gif" )
	{
		// make sure image is not in use twice+
		$image_dupe = mysql_query("select * from ".$prefix."store_inventory where image = '$image'"); 
		$image_total = mysql_num_rows($image_dupe);
		if( $image_total == 1 )
			abcDelProductImage( $image );
	}
	
	$result = "delete FROM ".$prefix."store_inventory WHERE product='$product'";
	$row = mysql_query($result);
	
	// Delete stributes link
	
	mysql_query ("DELETE FROM ".$prefix."store_atributes_link WHERE product_id='$product'");
	
}

// Count product inventory
$sql_count = "select * from ".$prefix."store_inventory";
$result_count = mysql_query ($sql_count);
$total = mysql_num_rows($result_count);

if( $total == 0 )
	abcPageExit("<br><p>".$lng[753]."</p>");

echo "<p><b>".$lng[754].": $total</b></p>";

if( empty($list_cat) || $list_cat == "all" )
{
	$list_cat = -1;
	$list_cat_name = "All";
}
else
{
	$sql_cat = "select category from ".$prefix."store_category where cat_id=$list_cat";
	$cat = mysql_query ($sql_cat);
	if( $row = mysql_fetch_array( $cat ) )
		$list_cat_name = $row["category"];
	else
	{
		$list_cat = -1;
		$list_cat_name = "All";
	}
}

if( empty($ddlimit) )
	$ddlimit = 10;

echo "
<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
<form name=\"filter\" action=\"products.php\" method=\"get\">
<tr>
<td width=\"25%\">".$lng[755].":</td>
<td>";

// Drop down menu of full_cat_name!
echo "<select name=\"list_cat\" onchange=\"document.filter.submit();\">\n\t\t<option value=\"all\" ";
if( $list_cat == -1 )
	echo "selected";
echo ">".$lng[756]."</option>";

$cats_unsorted = abcFetchCategoryList( false );
$cats_sorted = $cats_unsorted;
abcSortCategoryList( $cats_sorted );

foreach( $cats_sorted as $cat )
{							
	$catname = $cat["path"];
	$cat_id_dd = $cat["cat_id"];
	if( $list_cat == $cat_id_dd )
		echo "<option value=\"$cat_id_dd\" selected>$catname</option>\n";
	else
		echo "<option value=\"$cat_id_dd\">$catname</option>\n";
}

echo "</select></td>\n</tr>";

echo "
<tr>
<td>".$lng[757].":</td><td>
<select name=\"order\" onchange=\"document.filter.submit();\">
	<option value=\"product\" ";if($order==product){echo "selected";}echo">".$lng[758]."</option>
	<option value=\"cat_id\" ";if($order==cat_id){echo "selected";}echo">".$lng[759]."</option>
	<option value=\"title\" ";if($order==title){echo "selected";}echo">".$lng[760]."</option>
	<option value=\"quantity\" ";if($order==quantity){echo "selected";}echo">".$lng[761]."</option>
	<option value=\"price\" ";if($order==price){echo "selected";}echo">".$lng[762]."</option>
	<option value=\"sale_price\" ";if($order==sale_price){echo "selected";}echo">".$lng[763]."</option>
</select>&nbsp;&nbsp;

<select name=\"direction\" onchange=\"document.filter.submit();\">
	<option value=\"asc\" ";if($direction==asc){echo "selected";}echo">".$lng[764]."</option>
	<option value=\"desc\"";if($direction==desc){echo "selected";}echo">".$lng[765]."</option>
</select>&nbsp;</td></tr>
<tr>
<td>".$lng[766].":</td><td>
<select name=\"ddlimit\" onchange=\"document.filter.submit();\">
	<option value=\"5\" ";if($ddlimit==5){echo "selected";}echo">5</option>
	<option value=\"10\" ";if($ddlimit==10){echo "selected";}echo">10</option>
	<option value=\"25\" ";if($ddlimit==25){echo "selected";}echo">25</option>
	<option value=\"50\" ";if($ddlimit==50){echo "selected";}echo">50</option>
	<option value=\"100\" ";if($ddlimit==100){echo "selected";}echo">100</option>
</select>&nbsp;".$lng[767]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" class=\"submit\" value=\"".$lng[768]."\">
</td>
</tr>
</form>
</table>
";

// get results
if( empty($order) )
{
	$order="product";
	$direction="asc";
}

$limit = $ddlimit; 
 
if( $list_cat != -1 )
	$query_count = " SELECT * FROM ".$prefix."store_inventory where cat_id=$list_cat";
else
	$query_count = " SELECT * FROM ".$prefix."store_inventory";
	
$result_count = mysql_query($query_count); 
$totalrows = mysql_num_rows($result_count); 
if(empty($page))
	$page = 1;
$limitvalue = $page * $limit - ($limit); 
	
if( $list_cat != -1 )
	$query = "SELECT * FROM ".$prefix."store_inventory where cat_id=$list_cat order by $order $direction LIMIT $limitvalue, $limit"; 
else
	$query = "SELECT * FROM ".$prefix."store_inventory order by $order $direction LIMIT $limitvalue, $limit"; 
	
$result = mysql_query($query) or die("Error: " . mysql_error());
$count_result = mysql_num_rows($result);

     
// Display links at the bottom to indicate current page and number of pages displayed
$numofpages = ceil( $totalrows / $limit );
$from = $limit * $page - $limit + 1;
$to = $from + $count_result - 1;
echo "
<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
<tr>
<td width=\"50%\">";if($numofpages>1){echo $lng[769].": $from - $to</td>
<td width=\"50%\" align=\"right\"><b>".$lng[770].":</b> ";} 
 
if( $page != 1 )
{
	$pageprev = $page - 1; 
	echo ("<a href=\"$PHP_SELF?page=$pageprev&cat_id=$cat_id&order=$order&direction=$direction&list_cat=$list_cat&ddlimit=$ddlimit\"><< </a>&nbsp;"); 
}

for($i = 1; $i <= $numofpages; $i++)
{ 
	if( $numofpages > 1 )
	{
		if($i == $page)
			echo("&nbsp;".$i."&nbsp;"); 
		else
			echo("&nbsp;<a href=\"$PHP_SELF?page=$i&cat_id=$cat_id&order=$order&direction=$direction&list_cat=$list_cat&ddlimit=$ddlimit\">$i</a>&nbsp;");
	}
}

if( ( $totalrows - ( $limit * $page ) ) > 0 )
{ 
	$pagenext = $page + 1;
	echo("<a href=\"$PHP_SELF?page=$pagenext&cat_id=$cat_id&order=$order&direction=$direction&list_cat=$list_cat&ddlimit=$ddlimit\"> >></a>");
}

echo "</tr></td></table><br>";      

$bgcolour = $colour_3;

if( $numofpages != 0 )
{
	echo "
<table border=\"1\" bordercolor=\"#e6e6e6\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\">
<tr bgcolor=\"#e0e0e0\">
<td align=\"center\" height=\"25\" nowrap><b>".$lng[758]."</b></td>
<td align=\"center\" nowrap><b>".$lng[759]."</b></td>
<td align=\"center\" nowrap><b>".$lng[760]."</b></td>
<td align=\"center\" nowrap><b>".$lng[771]."</b></td>
<td align=\"center\" nowrap><b>".$lng[772]."</b></td>
<td align=\"center\" nowrap><b>".$lng[761]."</b></td>
<td align=\"center\" nowrap><b>".$lng[762]."</b></td>
<td align=\"center\" nowrap><b>".$lng[763]."</b></td>
<td align=\"center\" nowrap><b>".$lng[939]."</b></td>
<td align=\"center\" nowrap><b>".$lng[773]."</b></td>
<td align=\"center\" nowrap><b>".$lng[774]."</b></td>
<td align=\"center\" nowrap><b>".$lng[775]."</b></td>
</tr>";
}

if( $numofpages == 0 )
	echo "<font color=\"99000\"><br><br><b><div align=\"center\">".$lng[776]."<br>".$lng[777]."</font></b><p></div>";

while( $row = mysql_fetch_array($result) )
{
	$product = $row["product"];
	$image = $row["image"];
	$title = $row["title"];
	$quantity = $row["quantity"];
	$description = ($row["description"]);
	$price = ($row["price"]);
	$sale_price = ($row["sale_price"]);
	$cat_id = $row["cat_id"];
	$description=substr($description,0,150);
	$special = $row["special"];
	
	if ( $special )
		$special = "+";
	else	$special = "-";
	
	if( $bgcolour == $colour_3 )
		$bgcolour = $colour_4;
	elseif( $bgcolour == $colour_4 )
		$bgcolour =$colour_3;
	
	$cat_path = $cats_unsorted[$cat_id]["path"]; 
	
	echo "<tr>";
	echo "<td align='center'>$product</td>";
	echo "<td>$cat_path</td>";
	echo "<td>$title</td>";
	
	if ($image!=="nophoto.gif" && !empty ($image) )
		echo "<td align='center'><a href=\"#\"><img src='images/img.gif' onClick=\"MM_openBrWindow('image.php?image=$image&dir=product','image','width=300,height=300,scrollbars=yes,resizable=yes')\" border=\"0\"></a></td>";
	else
		echo "<td align='center'>n/a</td>";
	  
	echo "<td>$description</td>";

	if( $quantity >= 0 )
		echo "<td align='center'>$quantity</td>";
	else
		echo "<td align='center'>".$lng[778]."</td>";

	echo "<td align='center'>$price</td>";
	echo "<td align='center'>";
	if($sale_price=="0.00")
		echo "n/a</td>";
	if($sale_price!=="0.00")
		echo "<font color=\"#cc0000\">$sale_price</font></td>";
	
	echo "<td align='center'>$special</td>";
	echo "<td align='center'><a href=\"view_product.php?product=$product\">".$lng[773]."</a></td>";
	echo "<td align='center'><a href='edit_product.php?product=$product'>".$lng[774]."</a></font></td>";
	echo "<td align='center'><a href=\"javascript:decision('".$lng[779]."',
		'products.php?product=$product&del=1&image=$image&page=$page')\">".$lng[775]."</a></td>";
}

echo "</tr>\n";
echo "</table>\n";

// Display links at the bottom to indicate current page and number of pages displayed
echo "<br><table align=\"center\" width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tr><td bgcolor=\"$bg_colour\" align=\"right\">";if($numofpages>1){echo"<b>".$lng[770].":</b> ";
}
 
if( $page != 1 )
{
	$pageprev = $page - 1; 
	echo "<a href=\"$PHP_SELF?page=$pageprev&cat_id=$cat_id&order=$order&direction=$direction&list_cat=$list_cat&ddlimit=$ddlimit\"><< </a>&nbsp;";
}

$numofpages = ceil($totalrows / $limit); 
for($i = 1; $i <= $numofpages; $i++)
	if( $numofpages > 1 )
		if($i == $page)
			echo("&nbsp;".$i."&nbsp;"); 
		else
			echo "&nbsp;<a href=\"$PHP_SELF?page=$i&cat_id=$cat_id&order=$order&direction=$direction&list_cat=$list_cat&ddlimit=$ddlimit\">$i</a>&nbsp;";

if( ($totalrows - ($limit * $page)) > 0 )
{
	$pagenext = $page + 1;
	echo "<a href=\"$PHP_SELF?page=$pagenext&cat_id=$cat_id&order=$order&direction=$direction&list_cat=$list_cat&ddlimit=$ddlimit\"> >></a>";
}

mysql_free_result($result);
	
echo"<br><br></tr></td></table>";

include_once ("footer.inc.php");

?>