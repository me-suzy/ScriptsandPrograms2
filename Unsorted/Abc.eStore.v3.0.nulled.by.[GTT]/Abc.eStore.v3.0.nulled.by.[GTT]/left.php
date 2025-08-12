<?php

// Categories list

$sql_select = mysql_query( "SELECT * FROM ".$prefix."store_category where cat_father_id = 0 order by priority desc");
while ($row = mysql_fetch_array($sql_select)) {
	
	$lng['categories'][] = $row;
	
	$cat_id_list = $row["cat_id"];
	$category = $row["category"];
	
}

// Stats

// Count product inventory
$sql_count = "select * from ".$prefix."store_inventory";
$result_count = mysql_query ($sql_count);
$total_prod = mysql_num_rows($result_count);

// Count product categorys
$sql_count = "select * from ".$prefix."store_category";
$result_count = mysql_query ($sql_count);
$total_cat = mysql_num_rows($result_count);
$quan_hits = number_format($quan_hits);

$lng['total_prod'] = $total_prod;
$lng['total_cat'] = $total_cat;
$lng['quan_hits'] = $quan_hits;
$lng['currency_desc'] = $currency_desc;

// Status

if( $ShoppingCart )
	$session = $ShoppingCart;
	
// if session is unregistered an login is attempted

if ( isset ( $submit_login ) ) {

	if( $email && $password ) {
		
		// If the user has just tried to log in
		$passwd = md5($password);
		$query = "select * from ".$prefix."store_customer where email='$email' and password=('$passwd') LIMIT 1";
		$result = mysql_query($query);
	  	$res = mysql_fetch_array ($result);
	  	
		if( mysql_num_rows($result) > 0 ) {
			
			// if they are in the database register the user for the session
			$valid_user = $email;
			$_SESSION["valid_user"] = $email;
			$_SESSION['customer_id'] = $res['customer_id'];
			$lng['error'] = "";
		} else 	{
			
			$lng['error'] = "1";	
		}
	}
	else	$lng['error'] = 1;
	
}

// select suitable title depending on session state
if( isset( $_SESSION["valid_user"] ) ) {
	
	$lng['title'] = $lng[19];
	$lng['valid_user'] = $_SESSION["valid_user"];
	$lng['novalid_user'] = "";
}
else	{
	$lng['title'] = $lng[20];
	$lng['valid_user'] = "";
	$lng['novalid_user'] = "1";
}

// content
$sql_select = mysql_query( "select * from ".$prefix."store_customer where email='$lng[valid_user]'");
while( $row = mysql_fetch_array($sql_select) ) {
	
	$user_name = $lng['user_name'] = $row["name"];
	$user_email = $row["email"];
	
}

$lng['session'] = $session;

// Processing templates

$tmpl = new Template ( "html/left.html" );

$tmpl -> param ( 'lng', array ($lng) );
$tmpl -> param ( 'design_dir', "design/" . $design_directory . "/" );

echo $tmpl -> parse();

?>