<?php
	include ("$DOCUMENT_ROOT/library/db.php");

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

	# 
	# Authorize.Net Connection
	# 

	$x_Delim_Data="TRUE";    // Delimited response from the gateway (or set in the Setting Menu) 
	$x_Delim_Char=",";       // Character that will be used to separate fields 
	$x_Encap_Char="";        // Character that will be used to encapsulate fields 

	$x_Type="AUTH_CAPTURE";  // Default transaction type 
	$x_Test_Request="FALSE";  // Make this a test transaction 

	# 
	# Customer Information 
	# 
	$x_Method="CC"; 
	$x_Amount=$FinalPrice; 
	$x_First_Name=$firstName; 
	$x_Last_Name=$lastName; 
	$x_Card_Num=$creditCardNumber; 
	$x_Exp_Date=$expDate; 
	$x_Address=$billingAddress;
	$x_City=$billingCity;
	$x_State=$billing_state;
	$x_Zip=$billingState; 

	# 
	# Build fields string to post 
	# 
	$fields="x_Version=3.1&x_Login=$x_Login&x_Delim_Data=$x_Delim_Data&x_Delim_Char=$x_Delim_Char&x_Encap_Char=$x_Encap_Char"; 
	$fields.="&x_Type=$x_Type&x_Test_Request=$x_Test_Request&x_Method=$x_Method&x_Amount=$x_Amount&x_First_Name=$x_First_Name"; 
	$fields.="&x_Last_Name=$x_Last_Name&x_Card_Num=$x_Card_Num&x_Exp_Date=$x_Exp_Date&x_Address=$x_Address&x_City=$x_City&x_State=$x_State&x_Zip=$x_Zip&x_Cust_ID=$x_Cust_ID&x_Invoice_Num=$x_Invoice_Num&x_Description=$x_Description&x_card_code=$x_card_code"; 
	if($x_Password!='') 
	{ 
  		$fields.="&x_Password=$x_Password"; 
	} 

	# 
	# Start CURL session 
	# 
	$ch=curl_init("https://secure.authorize.net/gateway/transact.dll"); 
	curl_setopt($ch, CURLOPT_HEADER, 0); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);  // set the fields to post 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    // make sure we get the response back
	curl_setopt ($ch, CURLOPT_POST, 1); 
 

	$buffer = curl_exec($ch);                       // execute the post 

	curl_close($ch);                                // close our session 

	$details=explode($x_Delim_Char,$buffer);        // create an array of the response values 
	
	
	//load into database
	if ($details[0]==1){ //checks to see if credit card was approved
		
		echo "CCapproved=true";
		mail ($notification_emails, "Shopping Cart Order", $body);
	}else{//if not approved, prints the error to the screen, and tells user to use back button.
		echo "CCapproved=false";
	}
	//**************************                           
	?>