<?php

include_once("header.php");
include_once("left.php");

extract( $_POST );

$lng['err'] = "";
$lng['succ'] = "";

// if customer has clicked submit reset
if( $reset ) {
	
	// Generate random password
	$new_pass = abcGenPassword();

	$stop = 0;

	// Check e-mail address is valid
	if( !ereg("^[a-zA-Z0-9_]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$", $email ) )
	{
		$stop = 1;
		$lng['err'] .= $lng[112]."<br>";
	}
	
	// Check email address is held in database
	$result = mysql_query ("select email from ".$prefix."store_customer where email = '$email'");
	$count_results = mysql_num_rows( $result );

	if( $count_results == 0 && !$lng['err'] ) {
 		$stop = 1;
 		$lng['err'] .= "<b>$email</b> ".$lng[114]."<br>";
	}
		
	if ( $stop != 1 ) {
	
		// If all is ok encrypt password in database and send new password to the users email address
		
		$passwd = md5($new_pass);
						
			$sendto = $email;
			$from = $site_email;
			$subject = $lng[110];
			eval ('$message="'.$lng[117].'";');
			$headers = "From: $site_email\r\n";
			
			// send e-mail
									
			if ( !mail( $sendto, $subject, $message, $headers ) ) {
				
				$lng['err'] = $lng[48] . "<br>";
			
			}
			else	{
			
				$res = mysql_query("update ".$prefix."store_customer set password='$passwd' where email = '$email'");
				
				if( $res ) {
		
					$lng['succ'] .= "<br><br>".$lng[115]." <b>$email</b>"
					. "<br>".$lng[116]."</p>"
					. "<br><br>";
				}
				else	$lng['err'] = $lng[48] . "<br>";
								
			
			}
			
			$lng['nosubmit'] = "";	
			
	} 
}
else {
	
	$lng['nosubmit'] = "1";	

}

// Processing templates

$tmpl = new Template ( "html/password.html" );

$tmpl -> param ( 'lng', array ( $lng ) );
$tmpl -> param ( 'design_dir', "design/" . $design_directory . "/" );

echo $tmpl -> parse();


// Footer

include_once("right.php");
include_once("footer.php");

?>