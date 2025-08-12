<?php 
	include_once("$DOCUMENT_ROOT/library/db.php");

/*
***************************************************************************************************************************
*****************************************COPYRIGHT 2005 YOU MAY NOT USE THIS WITHOUT PERMISSION****************************

HJB IS PROVIDED "As Is" FOR USE ON WEBSITES WHERE A LICENSE FOR SUCH USE WAS PURCHASED.  IT MAY ONLY BE USED ON ONE SITE PER LICENSING
FEE.  IN ORDER TO USE ON ADDITIONAL SITES, ADDITIONAL LICENSES MUST BE PURCHASED.  


THE PHP SCRIPTS MAY BE ALTERED, AS LONG AS THE CREDIT LINE AND LINKS AT THE BOTTOM OF EACH PAGE REMAIN. THE FLASH MAY NOT IN ANY
WAY BE CHANGED OR ALTERED.  ANY VIOLATION OF THESE TERMS WILL RESULT IN THE FORFEITING OF YOUR RIGHT TO USE THIS SOFTWARE.

NationWideShelving.com does not guarantee this software in anyway.  You use this at your own risk.  NationWideShelving or any of its
employees or subsidiaries are not responsible for any damage, and / or loss of business, reputation, or other damages of any kind
which are caused whether actual or not, by the use of this product.  By using this product you agree to hold NationWideShelving, its
employees, and all subsidiaries harmless for any and all reasons associated with your use of this product.

Your installation of this software consititues an agreement to these terms.

****************************************************************************************************************************
	*/

	//connect to database
	$connect=mysql_connect($host_default, $login_default, $pw_default);
	$select_db=mysql_select_db($db_default);
	
		$firstName=$_POST['firstName'];
		$lastName=$_POST['lastName'];
		$phone=$_POST['phone'];
		$email=$_POST['email'];
		$shippingAddress=$_POST['shippingAddress'];
		$shippingCity=$_POST['shippingCity'];
		$shippingState=$_POST['shippingState'];
		$shippingZip=$_POST['shippingZip'];
		$billingFirstName=$_POST['billingFirstName'];
		$billingLastName=$_POST['billingLastName'];
		$creditCardNumber=$_POST['creditCardNumber'];
		$expDate=$_POST['expDate'];
		$billingAddress=$_POST['billingAddress'];
		$billingCity=$_POST['billingCity'];
		$billingState=$_POST['billingState'];
		$billingZip=$_POST['billingZip'];
		$totalPrice=$_POST['totalPrice'];
		$taxPrice=$_POST['taxPrice'];
		$FinalPrice=$_POST['FinalPrice'];
		$totalCartItems=$_POST['totalCartItems'];
		$CheckOutProcessType=$_POST['CheckOutProcessType'];
		
		
		//now we need to create the text for cart contents
		$i=0;
		while ($i<=($totalCartItems-1)){
			$itemNum=$i+1;
			$ItemName="Item".$i;
			$ItemQTY="QTY".$i;
			$cartContentsText.="
	Item # ".$itemNum.": ".$_POST[$ItemName]." QTY: ".$_POST[$ItemQTY]." \r
								";
			$i++;
		}
		$body="	
Shopping Cart Purchase \r
Date: ".date("Y-m-d")."\n

First Name: $firstName \r
Last Name:  $lastName \r
Phone:  $phone \r
Email:  $email \n

Shipping Address:  $shippingAddress \r
Shipping City:  $shippingCity \r
Shipping State:  $shippingState \r
Shipping Zip:  $shippingZip \n

Billing First Name:  $billingFirstName \r
Billing Last Name:  $billingLastName \r
Credit Card Number:  $creditCardNumber \r
Expiration Date: $expDate \n

Billing Address: $billingAddress \r
Billing City: $billingCity \r
Billing State: $billingState \r
Billing Zip:  $billingZip \n

Sub-Total:  $totalPrice \r
Tax:  $taxPrice \r
Total:  $FinalPrice \n

Shopping Cart Contents:
$cartContentsText
				";
				
if ($CheckOutProcessType=="paypal"){

	mail ($notification_emails, "Shopping Cart Order - PAYPAL", "A SHOPPING CART ORDER WAS SENT TO PAYPAL, YOU SHOULD LOGIN TO PAYPAL AND INSURE THAT THE PAYMENT WAS COMPLETED \r".$body);
	$query="INSERT INTO orders (firstName, lastName, phone, email, shippingAddress, shippingCity, shippingState, shippingZip, billingFirstName, billingLastName, creditCardNumber, expDate, billingAddress, billingCity, billingState, billingZip, totalPrice, taxPrice, finalPrice, totalCartItems, CheckOutProcessType, cartContents) VALUES ('".$_POST['firstName']."', '".$_POST['lastName']."', '".$_POST['phone']."', '".$_POST['email']."', '".$_POST['shippingAddress']."', '".$_POST['shippingCity']."', '".$_POST['shippingState']."', '".$_POST['shippingZip']."', '".$_POST['billingFirstName']."', '".$_POST['billingLastName']."', '".$_POST['creditCardNumber']."', '".$_POST['expDate']."', '".$_POST['billingAddress']."', '".$_POST['billingCity']."', '".$_POST['billingState']."', '".$_POST['billingZip']."', '".$_POST['totalPrice']."', '".$_POST['taxPrice']."', '".$_POST['finalPrice']."', '".$_POST['totalCartItems']."', 'paypal', '".$cartContentsText."')";
	$result=mysql_query ($query);
	header ("location: https://www.paypal.com/xclick/business=".$paypalUser."&item_name=Shopping+Cart+Purchase&amount=".$FinalPrice."&no_shipping=0&no_note=1&currency_code=USD");
	die ();
}else if ($CheckOutProcessType=="email"){
	echo "CCapproved=true";
	mail ($notification_emails, "Shopping Cart Order", $body);
	$query="INSERT INTO orders (firstName, lastName, phone, email, shippingAddress, shippingCity, shippingState, shippingZip, billingFirstName, billingLastName, creditCardNumber, expDate, billingAddress, billingCity, billingState, billingZip, totalPrice, taxPrice, finalPrice, totalCartItems, CheckOutProcessType, cartContents) VALUES ('".$_POST['firstName']."', '".$_POST['lastName']."', '".$_POST['phone']."', '".$_POST['email']."', '".$_POST['shippingAddress']."', '".$_POST['shippingCity']."', '".$_POST['shippingState']."', '".$_POST['shippingZip']."', '".$_POST['billingFirstName']."', '".$_POST['billingLastName']."', '".$_POST['creditCardNumber']."', '".$_POST['expDate']."', '".$_POST['billingAddress']."', '".$_POST['billingCity']."', '".$_POST['billingState']."', '".$_POST['billingZip']."', '".$_POST['totalPrice']."', '".$_POST['taxPrice']."', '".$_POST['finalPrice']."', '".$_POST['totalCartItems']."', 'email', '".$cartContentsText."')";
	$result=mysql_query ($query);
}else if ($CheckOutProcessType=="authorize"){
	include ("$DOCUMENT_ROOT/update/shopping_cart/cart_authorizenet.php");
	$query="INSERT INTO orders (firstName, lastName, phone, email, shippingAddress, shippingCity, shippingState, shippingZip, billingFirstName, billingLastName, creditCardNumber, expDate, billingAddress, billingCity, billingState, billingZip, totalPrice, taxPrice, finalPrice, totalCartItems, CheckOutProcessType, cartContents) VALUES ('".$_POST['firstName']."', '".$_POST['lastName']."', '".$_POST['phone']."', '".$_POST['email']."', '".$_POST['shippingAddress']."', '".$_POST['shippingCity']."', '".$_POST['shippingState']."', '".$_POST['shippingZip']."', '".$_POST['billingFirstName']."', '".$_POST['billingLastName']."', '".$_POST['creditCardNumber']."', '".$_POST['expDate']."', '".$_POST['billingAddress']."', '".$_POST['billingCity']."', '".$_POST['billingState']."', '".$_POST['billingZip']."', '".$_POST['totalPrice']."', '".$_POST['taxPrice']."', '".$_POST['finalPrice']."', '".$_POST['totalCartItems']."', 'Authorize Net', '".$cartContentsText."')";
	$result=mysql_query ($query);
}



?>