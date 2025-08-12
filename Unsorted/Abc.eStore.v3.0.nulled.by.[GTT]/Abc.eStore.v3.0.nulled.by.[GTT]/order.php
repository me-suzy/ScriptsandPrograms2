<?php

include_once("header.php");

// Processing templates

$tmpl = new Template ( "html/order.html" );

$lng['err'] = "";

foreach ( $_GET as $k=>$v )
	$lng[$k] = $v;

foreach ( $_POST as $k=>$v )
	$lng[$k] = $v;
	

// if register from customer.php

if( $register ) {

	// make sure relevant data was entered
	if( empty($name) || empty($add_1) || empty($town) || empty ($county)
		|| empty($postcode) || empty($country) || empty($phone)
		|| empty($password1) || empty($password2))
	{
		$lng['err'] .= $lng[126] . "<br>";
		$again = 1;
	}
	
	$country_id = $country;
	
	// make sure relevant data was entered
	if( !eregi("[0-9]",$phone) )
	{
		$lng['err'] .= $lng[127] . "<br>";
		$again = 1;
	}

	// check passwords match
	if( $password1 != $password2 )
	{
		$lng['err'] .= $lng[128] . "<br>";
		$again = 1;
	}

    // check password length 
	if( strlen($password1) < 6 || strlen($password1) > 20
		&& strlen($password2) < 6 || strlen($password2) >20 )
	{
		$lng['err'] .= $lng[129] . "<br>";
		$again = 1;
	}

	// if error provide link back
	if( $again == 1 )
		$lng['again'] = 1;
	else	$lng['again'] = "";

	if ( !$again ) {

		// make sure email is not a duplicate
	    	$dupe_email = mysql_query ("select * from ".$prefix."store_customer where email = '$email'");
	    	if( mysql_num_rows($dupe_email) == 0 ) {
	    			    		
			$passwd=md5($password1);
			$date=date("Ymd");
			$time=date("H:i");
			$ip= $_SERVER["REMOTE_ADDR"];
			
			if( empty($perm) )
				$perm = "Y";
				
			$sql_insert = "insert into ".$prefix."store_customer (email, password, name, add_1, add_2, town, county, postcode, country, phone, date, time, ip, perm) values ('$email', '$passwd', '$name', '$add_1', '$add_2', '$town', '$county', '$postcode', '$country', '$phone','$date','$time','$ip','$perm')";
			if ( $result = mysql_query( $sql_insert ) ) {
			
				$_SESSION["valid_user"] = $email;
				$_SESSION['customer_id'] = mysql_insert_id();
						
				// Email user
				$subject = $lng[131];
												
				eval('$message="'.$lng[132].'";');
											 
				mail($email, $subject, $message, "From: $site_email");
			}
			else	echo "SQL error!";
		}
	}
	

}// end ($register)

if( $add == "new_add" && !$lng['err'] ) {
	
	$sql_select = mysql_query( "select * from ".$prefix."store_customer where email='$email'");

	while( $row = mysql_fetch_array($sql_select) ) 	{
			
		$email = $row["email"]; 
		$name = $row["name"];
		$add_1 = $row["add_1"]; 
		$add_2 = $row["add_2"];
		$town = $row["town"];
		$county = $row["county"];
		$postcode = $row["postcode"];
		$country = $row["country"];
		$country_id = $row["country"];
		$phone = $row["phone"];
		$customer_id = $row["customer_id"];
		
		$parent_id = GetNameById ( 'parent_id', 'country_id', 'store_countries', $row['country'] );
		$row["country"] = GetNameById ( 'country', 'country_id', 'store_countries', $row['country'] );
		$parent = GetNameById ( 'country', 'country_id', 'store_countries', $parent_id );
		if ( !empty ( $parent ) )
			$row['country'] = $row['country'] . ", " . $parent;
			
		foreach( $row as $k=>$v )
			$lng[$k] = $v;
	
	}
			
	if( empty($country_d) )
		$country_d = $site_country;
	if( empty($country_id_d) )
		$country_id_d = $site_country;
	
	$lng['countries'] = GetRegions( 1 );
	
	$tagsel = new TagSelect ( $lng['countries'], "country_d");
	$tagsel->SetName('name');
	$tagsel->SetValue('id');
	$tagsel->SetSelected( $country_id_d );
	$tmpl->tag( $tagsel );
	
	$lng['new_add'] = 1;
	
}
else	$lng['new_add'] = "";

if( $add == "same_add" ) {
				
	if( $diff ) {
						
		if( empty($name_d) || empty($add_1_d) || empty($town_d) || empty($county_d) || empty($postcode_d) || empty($country_d) ) {
			
			$lng['err'] .= $lng[126]."<br>";
			$again=1;
				
		} //must be filled elements
		$country_id_d = $country_d;
		
		$parent_id = GetNameById ( 'parent_id', 'country_id', 'store_countries', $country_id_d );
		$country_d = GetNameById ( 'country', 'country_id', 'store_countries', $country_id_d );
		$parent = GetNameById ( 'country', 'country_id', 'store_countries', $parent_id );
		if ( !empty ( $parent ) )
			$country_d = $country_d . ", " . $parent;		
			
		if( $again == 1 )
			$lng['again2'] = 1;
		else	$lng['again2'] = "";
		
		
	}
	else {
		$name_d = $name;
		$add_1_d = $add_1;
		$add_2_d = $add_2;
		$town_d = $town;
		$county_d = $county;
		$postcode_d = $postcode;
		$country_id_d = $country_id;
				
		$parent_id = GetNameById ( 'parent_id', 'country_id', 'store_countries', $country_id_d );
		$country_d = GetNameById ( 'country', 'country_id', 'store_countries', $country_id_d );
		$parent = GetNameById ( 'country', 'country_id', 'store_countries', $parent_id );
		if ( !empty ( $parent ) )
			$country = $country_d = $country_d . ", " . $parent;
		
		
	}
	
	$lng['country'] = $country;
	
	$lng['name_d'] = $name_d;
	$lng['add_1_d'] = $add_1_d;
	$lng['add_2_d'] = $add_2_d;
	$lng['town_d'] = $town_d;
	$lng['county_d'] = $county_d;
	$lng['postcode_d'] = $postcode_d;
	$lng['country_d'] = $country_d;
	$lng['country_id_d'] = $country_id_d;

	if ( !$lng['err'] )
		$lng['secure'] = 1;
	else	$lng['secure'] = "";
		

//var_dump($cat_discount);

	$total_per_item = 0;
	$max_per_ship = 0;
	$total_ship = 0;
	$contents = $cart->display_contents($prefix,$session,$sale);
	$cat_discount=$cart->get_quantity_discounts($prefix, $session);	
	$total_tax = 0;
	
	if( $contents[product][0] !=  "" ) {
		
		$lng['noproducts'] = "";
		
	    	$x = 0;
	    	
		while($x != $cart->num_items($prefix,$session)) {
			
			
			$lng['products'][] = array ( 	
            'title_strip'=>stripslashes($contents[title][$x]),
						'currency'=>$currency,
						'price'=>$contents['price'][$x],
						'price_no_discount'=>$contents['price_no_discount'][$x],
						'product'=>$contents['product'][$x],
						'atributes'=>nl2br ($contents['atributes'][$x]),
						'id'=>$contents['id'][$x],
						'quantity'=>$contents['quantity'][$x],
						'cat_discount'=>(100*$cat_discount[$contents['cat_id'][$x]]),
						'prod_total'=>$contents['total'][$x],
						'form_prod_total'=>sprintf("%.2f", $contents[total][$x]),
						'remove'=>urlencode($contents['id'][$x]),
						'198'=>$lng['198'],
						'total'=>$contents[total][$x]
						
			);
			
			// Get prices for shipping and taxes
			
			$sel_cat = "select cat_id from ".$prefix."store_inventory where product = '".$contents['product'][$x]."'";
			if ( $result_sel_cat = mysql_query($sel_cat) )
				if ( $row_cat = mysql_fetch_array($result_sel_cat) ) {
														
					$cat_id = $row_cat['cat_id'];
					$par_id = GetNameById ( 'parent_id', 'country_id', 'store_countries', $country_id_d );
														
					$row_ship = array ();
					
					// Get category path
										
					$categories = GetCategoryPathArray( $cat_id );
			
										
					// Get prices for shipping by category and region (fixed)
					
					foreach ( $categories as $ct ) {
						$sel_ship = "select price, item_price from ".$prefix."store_delivery where category_id = '$ct' and country_id='$country_id_d'";
							if ( $result_sel_ship = mysql_query($sel_ship) )
								if ( $row_ship = mysql_fetch_array($result_sel_ship) )
									break;
					}
						
					
															
						if ( $row_ship ) {
					
							$per_ship = $row_ship['price'];
							$per_it = $row_ship['item_price'];
							
							// select highest per_ship or per_int_ship and multiply item_ship or item_int_ship
							$total_per_item = ( $per_it * $contents['quantity'][$x] ) + $total_per_item;
							
							if( $per_ship > $max_per_ship )
								$max_per_ship = $per_ship;
						
						}
						else { // If no deliveries finded for region - check country
							
							if ( $par_id ) {
																						
								foreach ( $categories as $ct ) {
								
									$sel_ship = "select price, item_price from ".$prefix."store_delivery where category_id = '$ct' and country_id='$par_id'";
										if ( $result_sel_ship = mysql_query($sel_ship) )
											if ( $row_ship = mysql_fetch_array($result_sel_ship) )
												break;
								
								}
								
								
								if ( $row_ship ) {
							
									$per_ship = $row_ship['price'];
									$per_it = $row_ship['item_price'];
									
									// select highest per_ship or per_int_ship and multiply item_ship or item_int_ship
									$total_per_item = ( $per_it * $contents['quantity'][$x] ) + $total_per_item;
									
									if( $per_ship > $max_per_ship )
										$max_per_ship = $per_ship;
								
								}
							
							}
							
						}
										
					// Get taxes
					
					foreach ( $categories as $ct ) {
						
						$sel_tax = "select tax from ".$prefix."store_tax where category_id = '$ct' and country_id='$country_id'";
							if ( $result_sel_tax = mysql_query($sel_tax) )
								if ( $row_tax = mysql_fetch_array($result_sel_tax) )
									break;
						
					}
					
					if ( $row_tax ) {
				
						$tax = $row_tax['tax'];
												
						$total_tax = ( $contents['price'][$x] * $contents['quantity'][$x] * $tax / 100 ) + $total_tax;
													
					}
					else	{ // If no tax finded for region - check country
						
						if ( $par_id ) {
							
							foreach ( $categories as $ct ) {
								
								$sel_tax = "select tax from ".$prefix."store_tax where category_id = '$ct' and country_id='$par_id'";
									if ( $result_sel_tax = mysql_query($sel_tax) )
										if ( $row_tax = mysql_fetch_array($result_sel_tax) )
											break;
							}
							
							
							if ( $row_tax ) {
						
								$tax = $row_tax['tax'];
														
								$total_tax = ( $contents['price'][$x] * $contents['quantity'][$x] * $tax / 100 ) + $total_tax;
															
							}
						}
						
					}
								
				}
			
			
			
		$x ++;
		
		} // ends loop for each product
		
		$cart_total_undiscounted = $lng['cart_total_undiscounted']=$cart->cart_total($prefix,$session,$sale);
		
		// Get prices for shipping by country/region (dynamic)
								
		$sel_ship = "select price from ".$prefix."store_countries_deliveries where country_id='$country_id_d' AND sum<='$cart_total_undiscounted' order by sum desc limit 1";
		if ( $result_sel_ship = mysql_query($sel_ship) )
			if ( $row_ship = mysql_fetch_assoc($result_sel_ship) ) {
				
				$total_ship += $row_ship['price'];
				
			}
			else if ( $parent_id ) {
			
				$sel_ship = "select price from ".$prefix."store_countries_deliveries where country_id='$parent_id' AND sum<='$cart_total_undiscounted' order by sum desc limit 1";
				if ( $result_sel_ship = mysql_query($sel_ship) )
					if ( $row_ship = mysql_fetch_assoc($result_sel_ship) ) {
						
						$total_ship += $row_ship['price'];
				
			}
		
				
			}
		
		//
		
		$_discount = array();
						
		$_user_discount_category=mysql_fetch_assoc(mysql_query("select id from ".$prefix."store_customer_discounts, ".$prefix."store_customer_discount_categories where customer_id='".$_SESSION['customer_id']."' and ".$prefix."store_customer_discount_categories.discount_id=".$prefix."store_customer_discounts.id"));
		$_user_discount_category=$_user_discount_category['id'];
		
		if($_user_discount_category!='') {
		
			$_discount=mysql_fetch_assoc(mysql_query("select * from ".$prefix."store_customer_discounts where id='".$_user_discount_category."'"));
			$lng['pers_discount'] = $_discount['discount'];
		}
		else	$lng['pers_discount'] = 0;
				
		$cart_total = (100-$_discount['discount'])/100*$cart->cart_total($prefix,$session,$sale);
		$total_ship += $lng['total_ship'] = $total_per_item + $max_per_ship;
		$lng['total_tax'] = $total_tax;
		$payable = $lng['payable'] = $cart_total + $total_ship + $total_tax;

		$total_ship = $lng['total_ship'] = sprintf("%.2f", $total_ship);
		$total_tax = $lng['total_tax'] = sprintf("%.2f", $total_tax);
		$payable = $lng['payable'] = sprintf("%.2f", $payable);
	    	$cart_total = $lng['cart_total'] = sprintf("%.2f", $cart_total);
	    	
		$lng['cart_order_id'] = date("ymd-His-") . rand(1000,9999);
		
	        
		
	}     
	else { 
		
    	$lng['err'] .= $lng[159] . "<br>";
	$lng['noproducts'] = 1;
	$lng['products'] = "";
	
    }

	   
}

$lng['session'] = $session;

$lng['noerr'] = !$lng['err'];

$tmpl -> param ( 'lng', array ( $lng ) );
$tmpl -> param ( 'design_dir', "design/" . $design_directory . "/" );

echo $tmpl -> parse();


// Footer

include_once("footer.php");

?>
