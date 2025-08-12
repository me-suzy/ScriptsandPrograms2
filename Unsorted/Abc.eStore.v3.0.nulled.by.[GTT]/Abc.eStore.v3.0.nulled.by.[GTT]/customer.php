<?php

include_once("header.php");

// Processing templates

$tmpl = new Template ( "html/customer.html" );

foreach ( $_POST as $k=>$v )
	$lng[$k] = $v;

foreach ( $_GET as $k=>$v )
	$lng[$k] = $v;

$lng['err'] = "";   

if( isset( $_SESSION["valid_user"] ) ) {
	
	$do = "return";
	$email = $_SESSION["valid_user"];
}
else {
	// contents if session is not registered
	
	// display login / register screen
	if( !$submit && !$register )
		$lng['nologined'] = 1;
	else	$lng['nologined'] = "";
	
}

// if radio button was not selected
if( empty($do) && $submit )
	$lng['noradio'] = 1;
else	$lng['noradio'] = "";


// if new registration is attempted
if( $do == "new" ) {
		
	// make sure email is not already in use
	$dupe_email = mysql_query ("select * from ".$prefix."store_customer where email = '$email'");
	if (mysql_num_rows($dupe_email)>0) {
		
		$lng['err'] .= $lng[88] . "<br>";
		$again = 1;
	}
	
	// make sure email was entered or valid
	if ((!ereg("^[a-zA-Z0-9_.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$", $email))or(empty($email))) {
		
		$lng['err'] .= $lng[89] . "<br>";
		$again=1;
	}
	
	// display link back if error was made 
	if( $again == 1 )
		$lng['again'] = 1;
	else	$lng['again'] = "";

	if ( !$lng['again'] )
		$lng['new'] = 1;
	else	$lng['new'] = "";
	
	if ( !isset ( $country ) )
		$country = $site_country;
	
	$lng['countries'] = GetRegions( 1 );
	
	$tagsel = new TagSelect ( $lng['countries'], "country");
	$tagsel->SetName('name');
	$tagsel->SetValue('id');
	$tagsel->SetSelected( $country );
	$tmpl->tag( $tagsel );
		
	
}// end if $do="new"

// if returning customer wishes to login
if( $do == "return" ) {
	
	// check email was entered or valid
	if( !ereg("^[a-zA-Z0-9_.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$", $email) || empty($email) )
	{
		$lng['err'] .= $lng[89] . "<br>";
		$again=1;
	}//end validate email

	// link back if error occurred
	if( $again == 1 )
		$lng['again'] = 1;
	else	$lng['again'] = "";


	// attempt to login
	if( $email && $password ) {
		
		unset ( $_SESSION["valid_user"] );
		
		// If the user has just tried to log in
		$passwd = md5($password);
		$query = "select * from ".$prefix."store_customer where email='$email' and password=('$passwd')";
		$result = mysql_query($query);
		$res = mysql_fetch_array ($result);
	
		$user=mysql_fetch_assoc($result);
	
		if( mysql_num_rows($result) > 0 ) {
						
			// if they are in the database register the user for the session
			$valid_user = $email;
			$_SESSION["valid_user"] = $email;
			$_SESSION['customer_id'] = $res['customer_id'];
			
		} // end if (mysql_num_rows($result) >0 )
	}// end if ($email && $password)
	else	if ( !isset ( $_SESSION['valid_user'] ) ) {
		
		$lng['err'] .= $lng[18] . "<br>";
		$lng['again'] = 1;	
		
	}
	
// user has successfully logged in display their info
	if( isset( $_SESSION["valid_user"] ) ) {
		
		$sql_select = mysql_query( "select * from ".$prefix."store_customer where email='$email'");

    		while( $row = mysql_fetch_assoc($sql_select) ) {
    		
			$email=$row["email"]; 
			$name= $row["name"];
			$add_1=$row["add_1"]; 
			$add_2=$row["add_2"];
			$town=$row["town"];
			$county=$row["county"];
			$row['country_id']=$row["country"];	
			$postcode=$row["postcode"];
			$country=$row["country"];
			$phone=$row["phone"];
			$customer_id=$row["customer_id"];
						
			$parent_id = GetNameById ( 'parent_id', 'country_id', 'store_countries', $row['country'] );
			$row["country"] = GetNameById ( 'country', 'country_id', 'store_countries', $row['country'] );
 			$parent = GetNameById ( 'country', 'country_id', 'store_countries', $parent_id );
			if ( !empty ( $parent ) )
				$row["country"] = $row['country'] . ", " . $parent;
			
			foreach( $row as $k=>$v )
				$lng[$k] = $v;
			
			$lng['old'] = 1;			

		}// end while
	
	} // end isset( $_SESSION["valid_user"] )
	else
	{
		$lng['old'] = "";
		
		// If session not registered display error messages
		
		// they have not tried to log in yet or have logged out
		$lng['err'] .= $lng[18] . "<br>";
		
		$lng['again'] = 1;
		
  	} // end else
}// end if $do="return"

$lng['session'] = $session;

$tmpl -> param ( 'lng', array ( $lng ) );
$tmpl -> param ( 'design_dir', "design/" . $design_directory . "/" );

echo $tmpl -> parse();


// Footer

include_once("footer.php");

?>