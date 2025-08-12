<?php

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

//Set Database Defaults

$host_default  = 'localhost';
$login_default = '';
$pw_default    = '';
$db_default    = '';


//set notification emails 
//This is where order notifications will be sent.  This should be an address you will check regularly.
$notification_emails='';

//Your Websites Name
$siteName="HJB Flash Cart";

//Paypal Info
$paypal_login='';  //enter paypal login here.  YOu only need to do this if you intend to use paypal.

//What type of order processing would you like to do.  You have three choices:
//1.  paypal
//		will send payment requests to paypal after your customer completes their order.

//2.  authorize
//		Will use authorize.net to charge credit cards.

//3.  email
//		Will not charge credit card.   Instead will send you an email notification with cc number and let you do whatever you want with it.
$CheckOutProcessType='paypal';

//Authorize.net Info
//you only need to complete this if you intend to use Authorizenet as a payment gateway.
	$x_Login="";     // Your authorize.net login 
	$x_Password="";          // Your authorize.net password (if Password-Required Mode is enabled) 
	$x_Tran_key="";  //Transaction Key
	
//Terms and agreements that your customer will be shown and asked to agree to.
$termsAndAgreements='I agree to the following:  <br>These are updated in the following script:  /library/db.php';

//Sales Tax Configuration
$ChargeSalesTax=true; //can be true or false
$salesTaxAmount=0.0625;
$stateAbrev='UT';  //which state should sales tax be charged on?

############## DO NOT EDIT BELOW THIS LINE ####################
$paypalUser=str_replace ("@", "%40", $paypal_login);


?>