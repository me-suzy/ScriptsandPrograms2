<?php

include_once("header.php");
include_once("left.php");

$lng['err'] = "";
$lng['succ'] = "";

// login

if ( isset ( $submit_login_account ) ) {

	if( $email && $password ) {
		
		// If the user has just tried to log in
		$passwd = md5($password);
		$query = "select * from ".$prefix."store_customer where email='$email' and password=('$passwd')";
		$result = mysql_query($query);
		$res = mysql_fetch_array ($result);
		
		if( mysql_num_rows($result) > 0 ) {
			
			// if they are in the database register the user for the session
			$valid_user = $email;
			$_SESSION["valid_user"] = $email;
			$_SESSION['customer_id'] = $res['customer_id'];
			
		} 
		else 	{
			// they have not tried to log in yet or have logged out
			$lng['err'] = 1;
		}
	
	}
	else	$lng['err'] = 1;
	
}

// content if session is registered
if( isset( $_SESSION["valid_user"] ) ) {
	
	$valid_user = $_SESSION["valid_user"];
	
	// modify password
	if( $task == "pass" ) {
		
		if( $new_pass && $old_pass ) {
			
			$old_pass = md5($old_pass);
			$query = "select * from ".$prefix."store_customer where email='$valid_user' and password = '$old_pass'";
			$result = mysql_query($query);
			if( mysql_num_rows($result) == 0 )
			{
				$lng['err'] = $lng[6]." ".$lng[43]."<br>";
				$stop = 1;
			}
			else
				$stop = 0;
		}
	
		// Check passwords match and are between 6 and 20 characters long
		if( $new_pass != $conf_new_pass )
		{
			$lng['err'] .= $lng[44]."<br>";
			$stop = 1;
		}
		
		if( strlen($new_pass) < 6 || strlen( $conf_new_pass ) > 20 )
		{
			$lng['err'] .= $lng[45]."<br>";
			$stop = 1;
		}
		
		// If all is ok update password into database
		if( $stop <> 1 )
		{
			$new_pass_enc = md5($new_pass);
			$res = mysql_query ("update ".$prefix."store_customer set password = '$new_pass_enc' where email = '$email'");
			if( $res ) {
				
				$lng['succ'] .= $lng[47] . "<br>";
					
				// send confirmation of new password
				$subject = $lng[842];
				eval('$message = "'.$lng[841].'";');
				
				mail($email, $subject, $message, "From: $site_email");
			}
			else
				$lng['err'] = $lng[48] . "<br>";
		}	
	}//end task=pass
	
	// edit user account details
	if( $task == "edit" ) {
		
		// check relevant fields have been filled
		if( empty($name) || empty($add_1) || empty($town) ||
			empty($county) || empty($postcode) || empty($country) ||
			empty($phone) )
		{
			$lng['err'] .= $lng[49] . "<br>";
			$noupdate = 1;
		}
		
		if( !eregi("[0-9]",$phone) )
		{
			$lng['err'] .= $lng[50]."<br>";
			$again = 1;
		}
		
		// make sure no other customer is using the email
		if( $email !== $valid_user )
		{
			$dupe_email = mysql_query( "select * from ".$prefix."store_customer where email = '$email' and name<>'$name'" );
			if( mysql_num_rows($dupe_email) > 0 )
			{
				$err .= $lng[51] . "<br>";
				$noupdate = 1;
			}
		}
						
		// if ok update database
		if( $noupdate !== 1 ) {
			
			unset( $_SESSION["valid_user"] );
			$sqlupdate = mysql_query("update ".$prefix."store_customer set email='$email', name='$name', add_1='$add_1', add_2='$add_2', town='$town', county='$county', postcode='$postcode', country='$country', phone='$phone', perm='$perm' where email='$valid_user'");
			$lng['succ'] .= $lng[52]."<br>";
			$valid_user = $email;
			$_SESSION["valid_user"] = $email;
		}
	}//end task edit
	
	// display user account details  
	$sql_select = mysql_query( "select * from ".$prefix."store_customer where email='$valid_user'");
	$row = mysql_fetch_assoc( $sql_select );
	if( $row ) {
		
		$email=$row["email"]; 
		$name= $row["name"];
		$add_1=$row["add_1"]; 
		$add_2=$row["add_2"];
		$town=$row["town"];
		$county=$row["county"];
		$postcode=$row["postcode"];
		$country=$row["country"];
		$phone=$row["phone"];
		$customer_id=$row["customer_id"];
		$perm=$row["perm"];
		
		foreach( $row as $k=>$v ) $lng[$k] = $v;
	}

	$lng['loginform'] = "";	
	
}	//end if session is registered
else {
			
	$lng['loginform'] = 1;
	
}//end if session is not registered

$lng['session'] = $session;

// Processing templates

$tmpl = new Template ( "html/account.html" );

// Countries

if ( !isset ( $country ) )
	$country = $site_country;

$lng['countries'] = GetRegions( 1 );
	
$tagsel = new TagSelect ( $lng['countries'], "country");
$tagsel->SetName('name');
$tagsel->SetValue('id');
$tagsel->SetSelected( $country );
$tmpl->tag( $tagsel );	

//

$tmpl -> param ( 'lng', array ( $lng ) );
$tmpl -> param ( 'design_dir', "design/" . $design_directory . "/" );

echo $tmpl -> parse();

// Footer

include_once("right.php");
include_once("footer.php");

?>
