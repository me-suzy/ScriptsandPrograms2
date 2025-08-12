<?php

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------

session_start();

////////////////////////////////////////////////////////////////
// Final message about category has been deleted
////////////////////////////////////////////////////////////////

function abcPageSuccess()
{
	abcPageExit("<br><h4>".$lng[338]."</h4><p><a href=\"categories.php\">".$lng[339]."</a></p>");
}

////////////////////////////////////////////////////////////////
// Determine is this category empty
////////////////////////////////////////////////////////////////

function abcCategoryIsEmpty( $id )
{
	return 	abcCategoryChildCount( $id ) == 0 &&
			abcCategoryProductsCount( $id ) == 0;
}

////////////////////////////////////////////////////////////////
// Get count of category children subcategories
////////////////////////////////////////////////////////////////

function abcCategoryChildCount( $id )
{
	global $prefix;
	
	$sql_count = "select count(*) from ".$prefix."store_category where cat_father_id='$id'";
	$result_count = mysql_query( $sql_count );
	$row = mysql_fetch_row( $result_count );
	return $row[0];
}

////////////////////////////////////////////////////////////////
// Get count of category products
////////////////////////////////////////////////////////////////

function abcCategoryProductsCount( $id )
{
	global $prefix;
	
	$sql_count = "select count(*) from ".$prefix."store_inventory where cat_id='$id'";
	$result_count = mysql_query ($sql_count);
	$row = mysql_fetch_row($result_count);
	return $row[0];
}

////////////////////////////////////////////////////////////////
// Delete catgeory, all its children and all its
//	subcategories recursively
////////////////////////////////////////////////////////////////

function abcDeleteCategory( $id )
{
	global $prefix;
	
	//
	// Delete category products from database

	$total = abcCategoryProductsCount( $id );	
	if( $total > 0 )
	{
		$sql_select = "select image, product from ".$prefix."store_inventory where cat_id = $id";   
		$result = mysql_query ($sql_select);
		
		while( $row = mysql_fetch_array($result) )
		{
			$prod_image = $row["image"];
			$product = $row["product"];
			
			if( $prod_image !== "nophoto.gif" )
			{
				// del image if exists
				// make sure image is not in use twice+
				$image_dupe = mysql_query("select * from ".$prefix."store_inventory where image = '$prod_image'"); 
				$image_total = mysql_num_rows($image_dupe);
				if( $image_total == 1 )
					abcDelProductImage( $prod_image );
			}
			$result_del = "delete from ".$prefix."store_inventory where product='$product'";
			$row_del = mysql_query($result_del);
		}
	}

	//	
	// Delete category childs
	
	$total = abcCategoryChildCount( $id );
	if( $total > 0 )
	{
		$sql_select = "select cat_id from ".$prefix."store_category where cat_father_id = $id";
		$result = mysql_query ($sql_select);
		
		while( $row = mysql_fetch_array($result) )
		{
			$child_id = $row["cat_id"];
			abcDeleteCategory( $child_id );
		}
	}
	
	//
	// Delete category itself
	
		// Unlink image (if needed)
	
	$sql_cat_img = "select cat_image from ".$prefix."store_category where cat_id='$id'";
	$result_cat_img = mysql_query($sql_cat_img);
	$cat_img = mysql_fetch_row($result_cat_img);
	$image = $cat_img[0];
	
	// make sure image is not used twice
	if( !empty($image) && $image !== "cat_nophoto.gif" )
	{
		$image_dupe = mysql_query("select * from ".$prefix."store_category where cat_image = '$image'"); 
		$image_total = mysql_num_rows($image_dupe);
		if( $image_total == 1 )
			abcDelCategoryImage( $image );
	}
	
		// Delete category entry
		
	$result_cat = "delete FROM ".$prefix."store_category WHERE cat_id='$id'";
	$row_cat = mysql_query($result_cat);
}

include ("config.php");
include ("settings.inc.php");
$url = "index";
include_once ("header.inc.php");

//
// if session is not registered go to login page

if( !isset( $_SESSION["admin"] ) && !isset( $_SESSION["demo"] ) )
	abcPageExit("<Script language=\"javascript\">window.location=\"login.php\"</script>");

echo "<h2>".$lng[340]."</h2>";

if( $_SERVER["REQUEST_METHOD"] == "GET" )
	extract( $_GET );

// To delete a category
if( $del == 1 && !$_SESSION['demo'] )
{
	//
	// The category is not empty
	
	if( !$confirm && !abcCategoryIsEmpty( $cat_id ) )
		abcPageExit("<blockquote>
			<b><p>".$lng[341]."</p></b>
			<p>
				<li><a href='categories.php'>&lt;&lt; ".$lng[342]."</a></li>
				<br><br>
				<li><a href='edit_category.php?cat_id=$cat_id'>".$lng[343]."</a></li>
				<br><br>
				<li><a href='categories.php?del=1&cat_id=$cat_id&confirm=confirm&image=$image'>".$lng[344]." &gt;&gt;</a></li>
			</p>
			</blockquote>");


	abcDeleteCategory( $cat_id );
	echo "<Script language=\"javascript\">window.location=\"categories.php\"</script>";
	exit;
}

//
// Page contents when delete has not been clicked

if( !$del || $_SESSION['demo'] )
{ 
	$result = array();
	$cats = abcFetchCategoryListPrior( 0 );
	$total = count( $cats );
	
	if( $total == 0 )
	{
		abcPageExit( "<h3>$lng[926]</h3><p><a href=\"add_category.php\">".$lng[345]."</a></p>" );
	}

	echo "
<p><b>Total categories: $total</b></p>
<table border=\"1\" bordercolor=\"#e6e6e6\" cellpadding=\"4\" cellspacing=\"0\" width=\"95%\">
<tr bgcolor=\"#e0e0e0\">
<td align=\"center\" width=\"50%\"><b>".$lng[346]."</b></td>
<td align=\"center\"><b>".$lng[347]."</b></td>
<td align=\"center\"><b>".$lng[348]."</b></td>
<td align=\"center\"><b>".$lng[349]."</b></td>
<td align=\"center\"><b>".$lng[350]."</b></td>
</tr>";

      
	$bgcolour=$colour_3;
	foreach( $cats as $cat )
	{
		$cat_full_name = $cat["cat_full_name"];
		$category = $cat["category"];
		$cat_id = $cat["cat_id"];
		$image = $cat["cat_image"];
		$catfatherid_1 = $cat["cat_father_id"];
		$priority = $cat["priority"];
		
		if ($bgcolour ==$colour_3)
			$bgcolour =$colour_4;
		elseif ($bgcolour ==$colour_4)
			$bgcolour =$colour_3;

		echo ("<tr>");
		echo ("<td>");
		echo ( $cat['path'] );
	  	echo ("</td>");
	  	
		// link to preview image
		if( $image !== "cat_nophoto.gif" && is_file ( "../images/category/$image" ) )
		{
			echo ("<td align='center'><a href=\"#\">
				<img src='images/img.gif' onClick=\"MM_openBrWindow('image.php?image=$image&dir=category','image','width=300,height=300,scrollbars=yes,resizable=yes')\" border=\"0\">
				</a></td>");
		}
		else
			echo "<td align='center'>n/a</td>";

		echo "<td align='center'>$priority</a></td>";
		echo "<td align='center'><a href='edit_category.php?cat_id=$cat_id'>".$lng[349]."</a></td>";
		echo "<td align='center'><a href=\"javascript:decision('".$lng[351]."',
			'categories.php?cat_id=$cat_id&del=1&image=$image&page=$page')\">".$lng[350]."</a></td>";
	}

		echo ("</tr>");
		echo ("</table></td></tr></table>");
}

include("footer.inc.php");
?>