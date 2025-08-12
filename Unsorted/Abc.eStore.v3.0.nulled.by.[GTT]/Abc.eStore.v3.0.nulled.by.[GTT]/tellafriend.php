<?php

include_once("header.php");
include_once("left.php");

$lng['err'] = "";
$lng['succ'] = "";

// variables

if ( !isset ( $recip_name ) )
	$recip_name = "";

if ( !isset ( $recip_email ) )
	$recip_email = "";

if ( !isset ( $from_name ) )
	$from_name = "";

if ( !isset ( $from_email ) )
	$from_email = "";

if ( !isset ( $message ) )
	$message = "";

$lng['submit'] = "";
$lng['nosubmit'] = 1;
	
//

$select = mysql_query("select * from ".$prefix."store_inventory where product='$product'");
$row = mysql_fetch_array($select);
$title = $row["title"];

// navigation
$lng['nav'] = "<a href=\"index.php\" target=\"_self\" class=\"menu\">".$lng[11]."</a> > <font class=\"BodyText03\">".$lng[173]."</font><br>";

// if tell a friend for has been submit
if( $submit ) {
		
	// validate recipient e-mail
	if( !ereg("^[a-zA-Z0-9_.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$", $recip_email) || empty($recip_email) )
	{
		$lng['err'] .= $lng[174]."<br>";
		$again = 1;
	}//end validate email
	
    // validate senders email
	if( !ereg("^[a-zA-Z0-9_.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$", $from_email) || empty($from_email) )
	{
		$lng['err'] .= $lng[175]."<br>";
		$again = 1;
	}//end validate email
	
    // ensure names have been entered
	if( empty($from_name) || empty($recip_name) )
	{
		$lng['err'] .= $lng[176]."<br>";
		$again = 1;
	}
	
	// build message
	if( $again != 1 ) {
		
		$lng['submit'] = 1;
		$lng['nosubmit'] = "";
		
		// if user did not enter a message
		if( empty($message) ) {
			
			eval('$message="'.$lng[178].'";');
		}
		
		eval('$subject="'.$lng[179].'";');
//		$subject = "Message from: $from_name";
		eval('$message.="'.$lng[180].'";');
		// send mail
		mail($recip_email, $subject, $message, "From: $from_email");
		// confirmation message
		eval('$msg="'.$lng[181].'";');
		$lng['succ'] .= $msg;
	}
}//end if submit
else {
	
	// display tell a friend form
	if( isset( $_SESSION["valid_user"] ) ) {
		
		$select = mysql_query ("select * from ".$prefix."store_customer where email='$_SESSION[valid_user] '");
	    	$row = mysql_fetch_array($select);
		$from_name = $row["name"];
		$from_email = $row["email"];
	}


}//end if no submit


// Processing templates

$tmpl = new Template ( "html/tellafriend.html" );

eval('$_msg="'.$lng[183].'";');
$lng['mes'] = $_msg;

$lng['session'] = $session;

$lng['recip_name'] = $recip_name;
$lng['recip_email'] = $recip_email;
$lng['from_name'] = $from_name;
$lng['from_email'] = $from_email;
$lng['message'] = $message;

$lng['product'] = $product;

$tmpl -> param ( 'lng', array ( $lng ) );
$tmpl -> param ( 'design_dir', "design/" . $design_directory . "/" );

echo $tmpl -> parse();


// Footer

include_once("right.php");
include_once("footer.php");

?>