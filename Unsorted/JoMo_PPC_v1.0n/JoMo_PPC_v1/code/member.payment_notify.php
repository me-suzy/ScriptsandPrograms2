<?
/*
###############################
#
# JoMo Easy Pay-Per-Click Search Engine v1.0
#
#
###############################
#
# Date                 : September 16, 2002
# supplied by          : CyKuH [WTN]
# nullified by         : CyKuH [WTN]
#
#################
#
# This script is copyright L 2002-2012 by Rodney Hobart (JoMo Media Group),
All Rights Reserved.
#
# The use of this script constitutes acceptance of any terms or conditions,
#
# Conditions:
#  -> Do NOT remove any of the copyright notices in the script.
#  -> This script can not be distributed or resold by anyone else than the
author, unless special permisson is given.
#
# The author is not responsible if this script causes any damage to your
server or computers.
#
#################################

*/
?>
<?PHP	

    include("config.php");
    include(__CFG_PATH_CODE . "loader.php");

// ---- PayPal IPN pocessing ------------

    include(__CFG_PATH_LIBS."paypal/class.paypal_ipn.php");
    
    //require_once("log_payment.php");		

/**
input:
$custom, $amount
*/    
/*
	if (__CFG_PAYMENT_DEBUG == 1){
		// only for debug
		
		$memberID = $custom;
		dprint("member=$memberID, amount=$amount");
    	if ($memberID>0){
    		changeAccountBalance("member",$memberID, $amount, "deposit", 1);
    		
		}
		exit();
	}
*/	
//	MailDump();
	
	$paypal_info=$_POST;    
        
    $paypal_ipn = new paypal_ipn($paypal_info, __CFG_ADMIN_EMAIL, __CFG_SITE_TITLE);       	
    //$paypal_ipn->request_for_confirmation();
    $paypal_ipn->send_response();
    
    if (!isset($receiver_email)){
    	$paypal_ipn->error_out("Fraud attempt was detected. (PayPal's receiver email is not set)");
    	exit;
    }
    
    if(strtolower($receiver_email) != strtolower(__CFG_PAYPAL_ACCOUNT)) 
    {
        $paypal_ipn->error_out("Fraud attempt was detected. (PayPal's receiver email is not equal to attempting's receiver email: $receiver_email)");
        if (__CFG_PAYMENT_DEBUG == 1){
        	die ("error");
        }
    }
    elseif($paypal_ipn->is_verified())
    { 
    	if (strtolower($paypal_ipn->get_payment_status()) != 'completed')
    		exit;
		
		/**
		*/    		
    	
    	$memberID = $custom;
    	// calc amount
    	$paypalFee = getOption("paypalFee");
    	if ($paypalFee==1)
    		$amount = $payment_gross - $payment_fee;
    	else
    		$amount = $payment_gross;
    	
    	
    	if ($memberID>0){
    		changeAccountBalance("member",$memberID, $amount, "deposit", 1);
		}
		else{
			$paypal_ipn->error_out("Fraud attempt was detected. (PayPal return wrong period id: $item_number)");
			if (__CFG_PAYMENT_DEBUG == 1){
        		die ("error");
        	}
		}
    }
    else{ 
    	$paypal_ipn->error_out("Fraud attempt was detected. (PayPal didn't validate request data)");
    	if (__CFG_PAYMENT_DEBUG == 1){
        	die ("error");
        }
    }
    
    exit;
    // ---- end of PayPal IPN pocessing -----
?>