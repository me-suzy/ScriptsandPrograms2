<?php

include_once("header.php");
include_once("left.php");

// Navigation and number of items on page

if ( isset ( $_POST['sn'] ) ) {
	$_SESSION['sn'] = $_POST['sn'];
	$_GET['pg'] = 1;
}
	
if ( isset ( $_GET['sn'] ) )
	$_SESSION['sn'] = $_GET['sn'];
	
if ( !isset ( $_SESSION['sn'] ) )
	$_SESSION['sn'] = 5;
	
//

// make sure a search was made
if( !empty($search) ) {

	$lng['search'] = 1;

//	// Navigation
	
	
	$query_count = "select product from ".$prefix."store_inventory where (description like '%$search%' or title like '%$search%')"; 
	$result_count = mysql_query($query_count); 
	$cnt_navigator = $lng['totalrows'] = mysql_num_rows($result_count);
	$lng['norows'] = !$lng['totalrows'];
		
	if ( !isset ( $_GET['pg'] ) )
		$_GET['pg'] = 1;
	
	$tagNav = new TagNavigator( $cnt_navigator, $_SESSION['sn'], 'nav' );
	$tagNav->SetPage( $_GET['pg'] );
	$tagNav->SetPassthroughParam( 'sn', $_SESSION['sn'] );
	$tagNav->SetPassthroughParam( 'search', $search );
								
	$pages = ($_GET['pg']-1) * $_SESSION['sn'];
			
//
	$query = "select * from ".$prefix."store_inventory where (description like '%$search%' or title like '%$search%') LIMIT  $pages, $_SESSION[sn] "; 
	$result = mysql_query($query) or die("Error: " . mysql_error()); 
	$count_result = mysql_num_rows($result);

	// Display links at the top to indicate current page and number of pages displayed
	$numofpages = ceil($totalrows / $limit);
	
	$from=$limit*$page-$limit+1;
	$to=$from + $count_result-1;
	
	$lng['search'] = $search;
		
	// display products if there are any
	
	while( $row = mysql_fetch_array($result) ) {
		
		$row['title'] = stripslashes($row['title']);
		$row['description'] = stripslashes( strip_tags($row['description']) );
		
		$row[33] = $lng[33];
		
		if ( !is_file ( "images/product/" . $row['small_image'] ) )
			$row['small_image'] = "nophoto_small.gif";
		
		
		if( strlen($row['description']) >= 200 ) {
			$row['description'] = substr($row['description'],0,200);
			$row['description'] .= "..";
		}
		
		if( empty($row['description']) )
			$row['description'] = "";
		
		if( $sale == "Y" && $row['sale_price'] !== "0.00" )
		{
			$row['new_price'] = "<s><b>$currency$row[price]</b></s>&nbsp;&nbsp;&nbsp;<font class=\"saleColor\">$currency$row[sale_price]</font>";
		}
			
		if( $sale == "Y" && $row['sale_price'] == "0.00" )
			$row['new_price'] = "<b>$currency$row[price]</b>";
			
		if( $sale == "N" )
			$row['new_price'] = "<b>$currency$row[price]</b>";
	
	$row['search'] = $search;
	$row['page'] = $page;
	
	$lng['products'][] = $row;
	
	}
		
		$lng['nosearch'] = "";
	
}
else	{
	$lng['nosearch'] = 1;
	$lng['search'] = "";
}

// Processing templates

$tmpl = new Template ( "html/search.html" );

$lng['cnt_navigator'] = $cnt_navigator;
$lng['search'] = $search;

$tmpl -> tag ($tagNav);

// Number of items on page
		
$numbers[] = array ( 'n'=>'5' );
$numbers[] = array ( 'n'=>'10' );
$numbers[] = array ( 'n'=>'20' );
$numbers[] = array ( 'n'=>'50' );
$numbers[] = array ( 'n'=>'100' );
$numbers[] = array ( 'n'=>'500' );

$tagsel = new TagSelect ($numbers, "sn", "OnChange='javascript:document.select_kol_form.submit();'");
$tagsel->SetName('n');
$tagsel->SetValue('n');
$tagsel->SetSelected( $_SESSION['sn'] );
$tmpl->tag( $tagsel );

$tmpl -> param ( 'lng', array ( $lng ) );
$tmpl -> param ( 'design_dir', "design/" . $design_directory . "/" );

echo $tmpl -> parse();


// Footer

include_once("right.php");
include_once("footer.php");

?>