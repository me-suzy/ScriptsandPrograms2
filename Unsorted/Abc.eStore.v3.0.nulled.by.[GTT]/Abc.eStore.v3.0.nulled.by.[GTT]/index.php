<?php

include_once("header.php");
include_once("left.php");

// if no category is selected display Top_Level categories

$catnav = array ();

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

If( empty( $cat_id ) ) {
			
	// get categories
			
	if ( $main_page == 0 || $main_page == 2 ) {
	
		$i = 0; // Counter for column quantity
		
		$sql_select = mysql_query( "SELECT * FROM ".$prefix."store_category where cat_father_id = 0 order by priority desc");
		
		while( $row = mysql_fetch_array( $sql_select ) ) {
			
			$i++;
							
			$cat_image = $row["cat_image"];
			$cat_id_list = $row["cat_id"];
			$category = $row["category"];
			
			if ( !is_file ( "images/category/$cat_image" ) )
				$row['cat_image'] = "nophoto.gif";
			
			if ( $i == $cat_num ) {
				$row['tr'] = "</tr><tr>"; $i = 0;
			}
			else	$row['tr'] = "";
			
			$lng['empty_cat'][] = $row;
		
		}
	
	}
	else	$lng['empty_cat'] = "";	
	
			
	// get specials
	
	if ( $main_page == 1 || $main_page == 2 ) {
	
		$sql_select = mysql_query( "SELECT * FROM ".$prefix."store_inventory where special='1' order by rand() limit $special_num");
		
		$i = 0; // Counter for column quantity
		
		while( $row = mysql_fetch_array( $sql_select ) ) {
			
			$i++;
							
			$small_image = $row["small_image"];
					
			if ( !is_file ( "images/product/$small_image" ) )
				$row['small_image'] = "nophoto.gif";
			
			if ( $i == $cat_num ) {
				$row['tr'] = "</tr><tr>"; $i = 0;
			}
			else	$row['tr'] = "";
			
			if( strlen($row['description']) >= 100 ) {
				$row['description'] = substr($row['description'],0,100);
				$row['description'] .= "..";
			}
			
			if ( $row['sale_price'] != 0 )
				$row['price'] = $row['sale_price'];
			
			$lng['specials'][] = $row;
		
		}
	
	}
	else	$lng['specials'] = "";	
	
}
else	{
	$lng['empty_cat'] = "";	
	$lng['specials'] = "";	
}

// display if category has been selected

If( !empty($cat_id) ) {
	
	$lng['catname'] = GetCatName($cat_id);
	
	$lng['category'] = 1;
	
	// Navigation
	
	GetCategoryPath ( $cat_id, &$catnav );
	$catnav = array_reverse ( $catnav );
	
	//
	
	$sql_select = mysql_query( "SELECT * FROM ".$prefix."store_category where cat_id='$cat_id' order by priority desc");
	
	while ( $row = mysql_fetch_array($sql_select) ) {
		
		$cat_image = $row['cat_image'];
		
		if ( !is_file ( "images/category/$cat_image" ) )
			$row['cat_image'] = "nophoto.gif";
				
		$lng['catinfo'][] = $row;
						
	}
			
	$sql_select = mysql_query( "SELECT * FROM ".$prefix."store_category where cat_father_id = $cat_id order by priority desc");
	$more_cat = mysql_num_rows($sql_select);	
	
	$i = 0; // Counter for column quantity
			
	while( $row = mysql_fetch_array($sql_select) ) {
		
		$i++;
		
		$cat_image = $row["cat_image"];
		$cat_id_list = $row["cat_id"];
		$category = $row["category"];
						
		if ( !is_file ( "images/category/$row[cat_image]" ) )
			$row['cat_image'] = "nophoto.gif";
						
		if( $totalprod !== 0 )
			$row['totalprod'] = " ($totalprod)";
				
		if ( $i == $cat_num ) { 
			$row['tr'] = "</tr><tr>"; $i = 0;
		}
		else	$row['tr'] = "";
		
		$lng['subcategories'][] = $row;
	
	}
	
//		// Navigation
		
		$navQuery = "select product from ".$prefix."store_inventory where cat_id='$cat_id'";
		$result = mysql_query($navQuery);
		$cnt_navigator = mysql_num_rows($result);
								
		if ( !isset ( $_GET['pg'] ) )
			$_GET['pg'] = 1;
		
		$tagNav = new TagNavigator( $cnt_navigator, $_SESSION['sn'], 'nav' );
		$tagNav->SetPage( $_GET['pg'] );
		$tagNav->SetPassthroughParam( 'sn', $_SESSION['sn'] );
		$tagNav->SetPassthroughParam( 'cat_id', $cat_id );
								
		$pages = ($_GET['pg']-1) * $_SESSION['sn'];
				
//	

	$query = "SELECT * FROM ".$prefix."store_inventory where cat_id='$cat_id' LIMIT $pages, $_SESSION[sn] ";
	$result = mysql_query($query) or die($lng[6] . mysql_error());
	$count_result = mysql_num_rows($result);

	if( mysql_num_rows($result) == 0 && $more_cat == 0 )
		$lng['empty_category'] = "1";
	else	$lng['empty_category'] = "";
		
	// display products
	
	while( $row = mysql_fetch_array($result) )
	{
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
	
	$lng['products'][] = $row;
	
	}
		
}
else $lng['category'] = "";

$lng['catnav'] = $catnav;
$lng['cnt_navigator'] = $cnt_navigator;
$lng['cat_id'] = $cat_id;

// Processing templates

$tmpl = new Template ( "html/index.html" );

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
$tmpl -> param ( 'cat_width', floor ( 100 / $cat_num ) );

echo $tmpl -> parse();


// Footer

include_once("right.php");
include_once("footer.php");

?>