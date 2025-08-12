<?php

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------

if ( !isset ( $session ) )
	$session = "";

if( isset( $_COOKIE['ShoppingCart'] ) )
	$ShoppingCart = $_COOKIE['ShoppingCart'];

if( !$session && !$ShoppingCart )  //make sure this hasn't already been established 
{
	$session = md5(uniqid(rand()));   //creates a random session value 

	// sets a cookie with the value of session.  
	// if it exists that is used

	// delete sessions more than 2 days old
	$today_date = date("Ymd");
	$expired_date = $today_date - 2;
	mysql_query("DELETE FROM ".$prefix."store_shopping WHERE date >= '$expired_date'");

	//set the cookie to remain for 2 days   
	SetCookie( "ShoppingCart", "$session", time() + 86400 * 2 );
}
     
/////////////////////////////////////////////////////
// Cart class
/////////////////////////////////////////////////////

class Cart
{  

	function get_quantity_discounts($prefix, $session)
	{
		$cats_res=mysql_query("SELECT  cat.cat_id, sum(shop.quantity) as items_num FROM ".$prefix."store_inventory inv, ".$prefix."store_category cat, ".$prefix."store_shopping shop WHERE shop.product = inv.product AND inv.cat_id = cat.cat_id and shop.session = '".$session."'group by inv.cat_id, shop.session") or die(mysql_error());
		$cat_discount=array();
		while($item=mysql_fetch_assoc($cats_res))
		{
			$discount_res=mysql_query("SELECT *, discount/100 as discounted_price from ".$prefix."store_price_discounts where category_id='".$item['cat_id']."' and min_amount<='".$item['items_num']."' order by min_amount desc limit 1") or die(mysql_error());
			$disc_item=mysql_fetch_assoc($discount_res);
			$cat_discount[$item['cat_id']]=$disc_item['discounted_price'];
		}
		return $cat_discount;
	}


	// add item to shopping database
	function add_item( $prefix, $session, $product, $quantity, $atributes="" )
	{
		// see if item is already in shopping list 
		$in_list =  "SELECT * FROM ".$prefix."store_shopping WHERE session='$session' AND product='$product' AND atributes='$atributes'";
		$result = mysql_query( "$in_list");
		$num_rows = mysql_num_rows($result);
		
		// if not add it 
		if( $num_rows == 0 )
		{
			$date= date("Ymd");
			$sql =  "INSERT INTO ".$prefix."store_shopping (session,product,quantity,date,atributes) VALUES ('$session','$product','$quantity','$date','$atributes')";
			mysql_query( $sql );
		}
		/*
		else
		{
			// if they already have it modify quantity
			$row = mysql_fetch_array($result);
			$quantity = $quantity + $row[quantity];
			$sql =  "UPDATE ".$prefix."store_shopping SET quantity='$quantity' WHERE session='$session' AND product='$product' AND atributes='$atributes'";
			mysql_query( $sql );
		}
		*/
	}
    
	// delete a specific item 
	function delete_item( $prefix, $session, $id )
	{
		mysql_query( "DELETE FROM ".$prefix."store_shopping WHERE session='$session' AND id='$id'");
	}
    
	// modifies a quantity of an item 
	function modify_quantity( $prefix, $session, $id, $quantity, $id )
	{	
		if ( (int) $quantity == 0 )
			$quantity = 1;
		
		$sql =  "UPDATE ".$prefix."store_shopping SET quantity='$quantity' WHERE session='$session' AND id='$id'";
		mysql_query( $sql );
	}
    
	// clear all content in their cart 
	function clear_cart( $prefix, $session )
	{
		mysql_query( "DELETE FROM ".$prefix."store_shopping WHERE session='$session'");
	}
	
	//add up the shopping cart total 
	function cart_total( $prefix, $session, $sale)
	{
		
		$total = 0;
		
		$discounts=$this->get_quantity_discounts($prefix, $session);

		$result = mysql_query( "SELECT * FROM ".$prefix."store_shopping WHERE session='$session'");
		
		if( mysql_num_rows($result) > 0 )
		{
			while($row = mysql_fetch_array($result))
			{
				// look up the item in inventory 
				$price_from_inventory =  "SELECT sale_price, price, cat_id FROM ".$prefix."store_inventory WHERE product = '$row[product]'";
				$result_inventory = mysql_query("$price_from_inventory");
				$row_price = mysql_fetch_array($result_inventory);

				//calculate the total depending whether product has sale price or not and sale is on or off
				if( $sale == "Y" && $row_price['sale_price'] == "0.00" )
					$result_price="price";
				if( $sale == "Y" && $row_price['sale_price'] !== "0.00" )
					$result_price="sale_price";
				if( $sale == "N" )
					$result_price="price";
				
				$row_price[$result_price]*=(1-$discounts[$row_price['cat_id']]);
				
				// add total
				$total = $total + ($row_price[$result_price]*$row['quantity']);
			}
		}
		
		// format total
		return sprintf("%.2f", $total);
	}
     
	// function to display contents
	function display_contents( $prefix, $session, $sale)
	{
		$discounts=$this->get_quantity_discounts($prefix, $session);
	
		$count = 0;
		$result = mysql_query("SELECT * FROM ".$prefix."store_shopping WHERE session='$session'");
		while($row = mysql_fetch_array($result))
		{
			$result_inv = mysql_query("SELECT * FROM ".$prefix."store_inventory WHERE product='$row[product]'");
			$row_inventory = mysql_fetch_array($result_inv);
			
			// select suitable price for product depending on whether it has a sale price and sale is on or off
			if( $sale == "Y" && $row_inventory['sale_price'] == "0.00" )
				$result_price = "price";
			if( $sale == "Y" && $row_inventory['sale_price'] !== "0.00" )
				$result_price = "sale_price";
			if( $sale == "N" )
				$result_price = "price";

			$contents[ "id"][$count] = $row['id'];
			$contents[ "cat_id"][$count] = $row_inventory['cat_id'];
			$contents[ "product"][$count] = $row_inventory['product'];
			$contents[ "image"][$count] = $row_inventory['image'];
			$contents[ "title"][$count] = $row_inventory['title'];
			$contents[ "description"][$count] = $row_inventory['description'];
			$contents[ "price_no_discount"][$count] = $row_inventory[$result_price];
			$contents['price'][$count]=$row_inventory[$result_price]*(float)((1-$discounts[$row_inventory['cat_id']]));
			$contents[ "quantity"][$count] = $row['quantity'];
			
			if ( $row_inventory['quantity'] < $row['quantity'] && $row_inventory['quantity'] != -1 )
				$contents[ "outofstock"][$count] = 1;
			else	$contents[ "outofstock"][$count] = 0;
			
			$contents[ "total"][$count] = ($contents['price'][$count] * $row['quantity']);
			$contents[ "package"][$count]  = $row_inventory["package"];
			$contents[ "atributes"][$count]  = $row["atributes"];
			$count ++;
		}
		
		$total = $this->cart_total($prefix,$session,$sale);
		$contents[ "final"] = $total;
		
		return $contents;
	}
     
	// count no items
	function num_items( $prefix, $session )
	{
		$result = mysql_query( "SELECT * FROM ".$prefix."store_shopping WHERE session='$session'");
		$num_rows = mysql_num_rows($result);
		return $num_rows;
	}
	
	// Get order contents for processing packages
	
	
}

// function to display contents
function get_contents( $cart_order_id, $sale, $prefix)
{

	$count = 0;
	$result = mysql_query("SELECT * FROM ".$prefix."store_order_inv WHERE cart_order_id='$cart_order_id'");
	while($row = mysql_fetch_array($result))
	{
		$result_inv = mysql_query("SELECT * FROM ".$prefix."store_inventory WHERE product='$row[product]'");
		$row_inventory = mysql_fetch_array($result_inv);
		
		// select suitable price for product depending on whether it has a sale price and sale is on or off
		if( $sale == "Y" && $row_inventory[sale_price] == "0.00" )
			$result_price = "price";
		if( $sale == "Y" && $row_inventory[sale_price] !== "0.00" )
			$result_price = "sale_price";
		if( $sale == "N" )
			$result_price = "price";
		
		$contents[ "id"][$count] = $row['id'];
		$contents[ "product"][$count] = $row_inventory['product'];
		$contents[ "image"][$count] = $row_inventory['image'];
		$contents[ "title"][$count] = $row_inventory[title];
		$contents[ "description"][$count] = $row_inventory['description'];

//		$contents[ "price_no_discount"][$count] = $row_inventory[$result_price];
		$contents['price'][$count]=$row_inventory[$result_price];//*(float)((1-$discounts[$row_inventory[cat_id]]));

		$contents[ "quantity"][$count] = $row['quantity'];
		$contents[ "total"][$count] = ($contents['price'][$count] * $row['quantity']);
		$contents[ "package"][$count]  = $row_inventory["package"];
		$contents[ "atributes"][$count]  = $row["atributes"];
		$count ++;
	}
	
	$total = $count;
	$contents[ "final"] = $total;
	
	return $contents;
}

	// count no items
	function num_items( $prefix, $cart_order_id )
	{
		$result = mysql_query( "SELECT * FROM ".$prefix."store_order_inv WHERE cart_order_id='$cart_order_id'");
		$num_rows = mysql_num_rows($result);
		return $num_rows;
	}
	

?>
