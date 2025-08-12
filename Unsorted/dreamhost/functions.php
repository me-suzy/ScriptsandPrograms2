<?

/* This software is developed & licensed by Dreamcost.com.
Unauthorized distribution, sales, or use of any of the code, in part or in whole, is
strictly prohibited and will be prosecuted to the full extent of the law.                         */

// ATTEMPT TO BILL THE CARD (AT CHECKOUT)
function card_checkout($account_id,$setup_amount,$amount,$cc_num,$cc_exp,$name,$address,$zip,$session_ip,$sess,$attr,$name,$value) {
        $rt = card($account_id,$name,$address,$zip,$cc_num,$cc_exp,$amount);

        if($rt[result]=="3") { $ret="member_order_error.html";  }
        if($rt[result]=="2") { $ret="member_order_declined.html";  }
        if (((($rt[result]=="1") || ($rt[result]=="5")  || ($rt[result]=="4") || ($rt[result]=="25")))) {
	        $billed_amount = $rt[amount];
	    
                $order_id = next_order_id();
                if ($rt[result] != 25) { create_billed_record_2($account_id,$order_id,$billed_amount); }
                $billing_id = create_billing_record($account_id,$cc_num,$cc_exp,$order_id);
                create_order_record($order_id,$account_id,$billing_id,$amount,$setup_amount,$sess,$attr,$name,$value);
                create_domain_record("Y",$order_id,$account_id,$billing_id,$session_ip);
                delete_sessions($session_ip);
                
                
                // SEND THE ADMIN NEW ORDER EMAIL
                if (setup("email_2") == Y) {
	                send_mail("2",$account_id,"",$order_id,"");
                }
                

                // SEND THE CUSTOMER NEW ORDER EMAIL
                if (setup("email_12") == Y) {
	                send_mail("12",$account_id,"",$order_id,"");
                }
                
                // SEND THE REGISTRAR THE NEW DOMAINS EMAIL
                if (setup("email_22") == Y) {
	                send_mail("22",$account_id,"",$order_id,"");
                }

                $ret="member_order_approved.html";
                }
return $ret;
}





// ATTEMPT TO BILL THE A CARD AT CHECKOUT (PREVIOUSLY STORED)
function card_s_checkout($account_id,$setup_amount,$amount,$billing_id,$name,$address,$zip,$session_ip,$sess,$attr,$name,$value) {
        $db = new ps_DB;
        $q  = "SELECT billing_cc_num,billing_cc_exp FROM billing WHERE billing_id='$billing_id' AND billing_account_id='$account_id'";
        $db->query($q);
        $db->next_record();
                $cc_num=$db->f("billing_cc_num");
                $cc_num=RC4($cc_num,"de");
                $cc_exp=$db->f("billing_cc_exp");

        $rt = card($account_id,$name,$address,$zip,$cc_num,$cc_exp,$amount);

        if($rt[result]=="3") { $ret="member_order_error.html";  }
        if($rt[result]=="2") { $ret="member_order_declined.html";  }
        if (((($rt[result]=="1") || ($rt[result]=="5")  || ($rt[result]=="4") || ($rt[result]=="25")))) {
	        $billed_amount = $rt[amount];

                $order_id = next_order_id();
                if ($rt[result] != 25) { create_billed_record_2($account_id,$order_id,$billed_amount); }
                create_order_record($order_id,$account_id,$billing_id,$amount,$setup_amount,$sess,$attr,$name,$value);
                create_domain_record("Y",$order_id,$account_id,$billing_id,$session_ip);
                delete_sessions($session_ip);
                
                
                // SEND THE ADMIN NEW ORDER EMAIL
                if (setup("email_2") == Y) {
	                send_mail("2",$account_id,"",$order_id,"");
                }
                
                // SEND THE CUSTOMER NEW ORDER EMAIL
                if (setup("email_12") == Y) {
	                send_mail("12",$account_id,"",$order_id,"");
                }                
                
                // SEND THE REGISTRAR THE NEW DOMAINS EMAIL
                if (setup("email_22") == Y) {
	                send_mail("22",$account_id,"",$order_id,"");
                }

                $ret="member_order_approved.html";
                }
return $ret;
}

// ATTEMPT TO BILL & RENEW A DOMAIN
function renew_domain($account_id,$domain_id,$new_term,$billing_id) {
        $db = new ps_DB;
        $q  = "SELECT billing_cc_num,billing_cc_exp FROM billing WHERE billing_id='$billing_id' AND billing_account_id='$account_id'";
        $db->query($q);
        $db->next_record();
        
        $cc_num=$db->f("billing_cc_num");
        $cc_num=RC4($cc_num,"de");        

        $dbf = new ps_DB;
        $q  = "SELECT * FROM account WHERE account_id='$account_id'";
        $dbf->query($q);
        $dbf->next_record();

        $dbd = new ps_DB;
        $q  = "SELECT domain_name FROM domains WHERE domain_id='$domain_id'";
        $dbd->query($q);
        $dbd->next_record();

        $domain = $dbd->f("domain_name");
        $amount = get_renewal_cost($domain,$new_term);

        $rt = card($account_id,$dbf->f("account_name"),$dbf->f("account_address"),$dbf->f("account_zip"),$cc_num,$db->f("billing_cc_exp"),$amount);
        
        if($rt[result]=="3") { echo "A error caused your renewal to be canceled. Please try again later.";  }
        if($rt[result]=="2") { echo "The credit card you have supplied for this transaction declined.";  }
        if (((($rt[result]=="1") || ($rt[result]=="5")  || ($rt[result]=="4") || ($rt[result]=="25")))) {
	        
	        $billed_amount = $rt[amount];        

                if ($rt[$result] != 25) { create_billed_record($account_id,$domain_id,$billed_amount); }
                renew_domain_record($account_id,$domain_id,$new_term);

                echo "Your card was successfully charged the fees for the domain renewal. <BR>Thank you!";
                } 
return $ret;
}


// UPDATE ORDER STATUS
function update_order_status($order_id,$order_status) {
	
	if (((($order_status==0) || ($order_status==2) || ($order_status==3) || ($order_status==4)))) {
		delete_affiliate_payout($order_id);
	} elseif ($order_status==1) {
		create_affiliate_payout($order_id);
	}
	
        $db = new ps_DB;
        $q  = "UPDATE orders SET
                order_status='$order_status'
                WHERE order_id='$order_id'";
        $db->query($q);
return true;
}


// DELETE AN AFFILIATE PAYOUT AFTER CHANGING ORDER STATUS
function delete_affiliate_payout($order_id) {
        $db = new ps_DB;
        $q  = "DELETE FROM credit WHERE credit_order_id='$order_id'";
        $db->query($q);	
}


// DELETE AN AFFILIATE PAYOUT AFTER CHANGING ORDER STATUS
function create_affiliate_payout($order_id) {
        $db = new ps_DB;
        $q  = "SELECT order_amount,order_affiliate_id FROM orders WHERE order_id='$order_id'";
        $db->query($q);	
        $db->next_record();
        $affiliate_id=$db->f("order_affiliate_id");
        $order_amount=$db->f("order_amount");
        
        $db = new ps_DB;
        $q  = "SELECT credit_id FROM credit WHERE credit_order_id='$order_id'";
        $db->query($q);	
        $db->next_record();
        
        if ($db->f("credit_id") != "") {
        } else {
	        affiliate_payout($affiliate_id, $order_id, $order_amount);
        }
}


// CREATE A CREDIT RECORD FOR AN AFFILIATE ORDER REFERRAL
function affiliate_payout($affiliate_id, $order_id, $order_amount) {
	
       $today = date("Y-m-d");
       
       $db = new ps_DB;
       $q = "SELECT affiliate_account_id, affiliate_type FROM affiliate WHERE affiliate_id='$affiliate_id'";
       $db->query($q);
       $db->next_record();
       
       $account_id = $db->f("affiliate_account_id");
       $type        	= $db->f("affiliate_type");
       
       
       if ($type==1) {
	       $sel = setup("aff_pay_1"); 
	       if ($sel == 0) {
		       $amount = setup("aff_pay_1a") * $order_amount;
	       } else {
		       $amount = setup("aff_pay_1a");
	       }
       }
	       
       elseif ($type==2) {
	       $sel = setup("aff_pay_2");  
	       if ($sel == 0) {
		          $amount = setup("aff_pay_2a") * $order_amount;
	       } else {
		       	$amount = setup("aff_pay_2a");       
	       }
       }
       
	   $db = new ps_DB;
       $q = "INSERT INTO credit SET 
       		credit_order_id	=	'$order_id',
       		credit_account_id	=	'$account_id',
       		credit_type		=	'2',
       		credit_amount	= 	'$amount',
       		credit_status		=	'0', 
       		credit_date_added=	'$today'";
       $db->query($q);
       
      
        // EMAIL AFFILIATE
        if (setup("email_9") == Y) {
	    		send_mail("9","","",$order_id,$affiliate_id);    
        }
       
}



// GET THE TOTAL AMOUNT OWED TO AN AFFILIATE
function get_affiliate_payout($affiliate_id) {
       $db = new ps_DB;
       $q = "SELECT affiliate_account_id, affiliate_type FROM affiliate WHERE affiliate_id='$affiliate_id'";
       $db->query($q);
       $db->next_record();
       
       $account_id  = $db->f("affiliate_account_id");
       $type          = $db->f("affiliate_type");
      
       $ret = 0;
       
       if ($type==2) {
	     $db = new ps_DB;
     	$q = "SELECT * FROM credit WHERE credit_account_id='$account_id' AND credit_status='0' AND credit_type='2'";
       	$db->query($q);
       	  while ($db->next_record()) {
	       $amount = $db->f("credit_amount");
	        $ret = $ret + $amount; 	
       	}
        }
return $ret;
}


// UPDATE ALL AFFILIATE PAYOUTS
function affiliate_payed() {
	  $today = date("Y-m-d");  
       $db = new ps_DB;
       $q = "SELECT affiliate_account_id FROM affiliate WHERE affiliate_type='2'";
       $db->query($q);
       $db->next_record();
       $account_id  = $db->f("affiliate_account_id");
      
	  $db = new ps_DB;
       $q = "SELECT credit_id,credit_amount FROM credit WHERE credit_account_id='$account_id' AND credit_status='0' AND credit_type='2'";
       $db->query($q);
	       	while ($db->next_record()) {
		      
		     $credit_id = $db->f("credit_id");
		     $amount = $db->f("credit_amount");
		     update_credit_payed($credit_id,$amount);	
       	}
       	
       $db = new ps_DB;
       $q = "UPDATE setup SET setup_aff_pay_2c='$today' WHERE setup_id='1'";
       $db->query($q);       	     
}

// CHECK IF NON-AFFILIATE CREDIT EXSISTS FOR A CUSTOMER


// DETERMINE IF A CREDIT ID IS TO BE MAILED OR IF IT CAN BE CREDITED TO THE ACCOUNT.
function is_affiliate_mail($account_id) {
	$db = new ps_DB;
	$q = "SELECT affiliate_type FROM affiliate WHERE affiliate_account_id='$account_id'";
	$db->query($q);
	
	if ($db->f("affiliate_type") == 2) {
		$ret = "Y";
	} else { 
		$ret = "N";
	}
return $ret;
	
}

// UPDATE CREDIT TO PAYED
function update_credit_payed($credit_id,$amount) {
     $today = date("Y-m-d");  
	$db = new ps_DB;
       $q = "UPDATE credit SET
       		credit_date_applied		=	'$today', 
       		credit_amount_applied	=	'$amount',
       		credit_status		=   '1'";
       $db->query($q);
}


       
// UPDATE CREDIT TO PAYED COMPLETE

function useall($credit_id) {
     $today = date("Y-m-d");  
	$dbf = new ps_DB;
     $z = "UPDATE credit SET 
     		credit_status='1', 
     		credit_date_applied='$today',
     		credit_amount_applied = '0' 
     		WHERE credit_id = '$credit_id'";
     $dbf->query($z);
}  

// UPDATE CREDIT TO PARTIALLY PAID
function credit_usesome($credit_id, $amount) {
     $today = date("Y-m-d");  
     
	$db = new ps_DB;
     $q = "SELECT credit_status,credit_amount,credit_amount_applied FROM credit WHERE credit_id='$credit_id'";
     $db->query($q);
     $db->next_record();
			
	if ($db->f("credit_status") == 2) {
		$prev = $db->f("credit_amount_applied"); 
		$amount = $amount + $prev;
	}     
     
	$db = new ps_DB;
     $q = "UPDATE credit SET 
     		credit_status ='2', 
     		credit_amount_applied = '$amount',  
     		credit_date_applied = '$today' 
     		WHERE credit_id = '$credit_id'";
     $db->query($q);
} 
     

// DETERMINE IF A CREDIT ID IS TO BE MAILED OR IF IT CAN BE CREDITED TO THE ACCOUNT.
function is_mail_only($account_id) {
	
	$db = new ps_DB;
	$q = "SELECT affiliate_type FROM affiliate WHERE affiliate_account_id='$account_id'";
	$db->query($q);
	$db->next_record();
	if ($db->f("affiliate_type") != "2") {
		$ret = "N";
	} else { 
		$ret = "Y";
	}
return $ret;
	
}
    

// UPDATE CREDIT TO PAYED
function credit_used_1($account_id,$amount) {
     $today = date("Y-m-d");  
     $prev = 0;
     $i=1;
     $amount1 = $amount;
	$db = new ps_DB;
     $q = "SELECT * FROM credit WHERE credit_account_id='$account_id' AND credit_status='0' OR credit_status='2' ORDER BY credit_id ASC";
     $db->query($q);
       while (($db->next_record()) && ($amount > 0)) {
			$credit_id = $db->f("credit_id"); 
			$account_id = $db->f("credit_account_id");
			
			// DETERMINE IF THIS IS A MAIL ONLY AFFILIATE PAYMENT...
			if (is_mail_only($account_id) != Y) {
			
			if ($db->f("credit_status") == 0) {
					$credit = $db->f("credit_amount"); 
			} elseif ($db->f("credit_status") == 2) {
					$prev = $db->f("credit_amount_applied"); 
					$credit = $db->f("credit_amount") - $prev;
			}
			
			
			if (($amount < $credit) && ($amount > 0)) {
			
				//use the difference
				credit_usesome($credit_id, $amount);
				$amount = "0";
				return true;

			} elseif (($amount >= $credit) && ($amount > 0)) {
				//use all the credit
				$amount2 = $amount - $credit;
				$amount = $amount2;
				$i++;
				useall($credit_id);
			}
   	  }
   }
}

// FIND OUT HOW MUCH CREDIT AN ACCOUNT HAS
function credit_exsist($account_id) {
	$ret = 0;
	     $db = new ps_DB;
     	$q = "SELECT * FROM credit WHERE credit_account_id='$account_id' AND credit_status='0' OR credit_status='2'";
       	$db->query($q);
       	
      	
       	  while ($db->next_record()) {
	       	  $status = $db->f("credit_status");
	       	  $type    = $db->f("credit_type");
	       	  
	       	  if (($type == "2") && (is_mail_only($account_id) == Y)) {
		       	  // DO NOTHING, THIS IS A MAIL ONLY ACCT
		       	  } else { 
	       	   
	       		if ($status == "2") {
			       	$amount = $db->f("credit_amount") - $db->f("credit_amount_applied");
		       		$ret = $ret + $amount;
	       		} elseif ($status == "0") {
		       		$amount = $db->f("credit_amount");
	        			$ret = $ret + $amount; 	
        			}
        			
         		}
         		
        }
return $ret;
}



// ADD A CREDIT
function add_credit($f) {
     $today = date("Y-m-d");  
	$db = new ps_DB;
       $q = "INSERT INTO credit SET
       		credit_date_added		=	'$today', 
       		credit_amount			=	'$f[amount]',
       		credit_account_id			=	'$f[account_id]',
       		credit_domain_id			=	'$f[domain_id]',
       		credit_type				=	'$f[type]',
       		credit_notes				=	'$f[notes]',
       		credit_status				=   '0'";
       $db->query($q);
       
       // EMAIL THE CUSTOMER
       if (setup("email_15")==Y) {
	       send_mail("15",$f[account_id],"","","");
       }
}


// UPDATE CREDIT TO PAYED
function delete_credit($id) {
	  $db = new ps_DB;
       $q = "DELETE FROM credit WHERE credit_id='$id'";
       $db->query($q);
}


// GET TIME TILL AFFILIATE PAYOUTS ARE DUE
function get_payout_date() {
        $today=date("Y-m-d");
        $today_date=strtotime($today);
        $last_date=strtotime(setup("aff_pay_2c"));
        $date_diff =(($today_date-$last_date)/86400);
        
        $period = setup("aff_pay_2b");
        
        $ret = $period - $date_diff;
        
return $ret;
}



// DELETE AND UNLINK AN AFFILIATE
function delete_affiliate($id) {
	  $db = new ps_DB;
       $q = "DELETE FROM affiliate WHERE affiliate_id='$id'";
       $db->query($q);
return "Affiliate ID $id Was Removed!";
}



// DELETE AND UNLINK A DOMAIN
function delete_domain($id) {
	  $db = new ps_DB;
       $q = "DELETE FROM domains WHERE domain_id='$id'";
       $db->query($q);
return "Domain ID $id Was Removed!";
}



// DELETE AND UNLINK AN ORDER
function delete_order($id) {
	  $db = new ps_DB;
       $q = "DELETE FROM domains WHERE domain_order_id='$id'";
       $db->query($q);

	  $db = new ps_DB;
       $q = "DELETE FROM billed WHERE billed_order_id='$id'";
       $db->query($q);
       
	  $db = new ps_DB;
       $q = "DELETE FROM credit WHERE credit_order_id='$id'";
       $db->query($q);
       
	  $db = new ps_DB;
       $q = "DELETE FROM orders WHERE order_id='$id'";
       $db->query($q);

        return "Order ID $id Was Removed!";
}


// DELETE AND UNLINK AN ACCOUNT
function delete_account_1($id) {
	  $db = new ps_DB;
       $q = "DELETE FROM domains WHERE domain_account_id='$id'";
       $db->query($q);

	  $db = new ps_DB;
       $q = "DELETE FROM affiliate WHERE affiliate_account_id='$id'";
       $db->query($q);

	  $db = new ps_DB;
       $q = "DELETE FROM billed WHERE billed_account_id='$id'";
       $db->query($q);
       
	  $db = new ps_DB;
       $q = "DELETE FROM credit WHERE credit_account_id='$id'";
       $db->query($q);
       
	  $db = new ps_DB;
       $q = "DELETE FROM orders WHERE order_account_id='$id'";
       $db->query($q);
       
	  $db = new ps_DB;
       $q = "DELETE FROM billing WHERE billing_account_id='$id'";
       $db->query($q);
       
	  $db = new ps_DB;
       $q = "DELETE FROM login WHERE login_member_id='$id'";
       $db->query($q);

	  $db = new ps_DB;
       $q = "DELETE FROM account WHERE account_id='$id'";
       $db->query($q);

       
       return "Account ID $id Was Removed!";
}



// ATTEMPT TO BILL & RENEW A DOMAIN
function renew_domain_2($account_id,$domain_id,$new_term,$billing_id) {

                $db = new ps_DB;
        $q  = "SELECT billing_cc_num,billing_cc_exp FROM billing WHERE billing_id='$billing_id'";
        $db->query($q);
        $db->next_record();
                $cc_num=$db->f("billing_cc_num");
                $cc_num=RC4($cc_num,"de");
                
                $dbf = new ps_DB;
        $q  = "SELECT * FROM account WHERE account_id='$account_id'";
        $dbf->query($q);
        $dbf->next_record();

                $dbd = new ps_DB;
        $q  = "SELECT domain_name FROM domains WHERE domain_id='$domain_id'";
        $dbd->query($q);
        $dbd->next_record();
                $domain = $dbd->f("domain_name");

                $amount = get_renewal_cost($domain,$new_term);

        $rt = card($account_id,$dbf->f("account_name"),$dbf->f("account_address"),$dbf->f("account_zip"),$cc_num,$db->f("billing_cc_exp"),$amount);

        if($rt[result]=="3") { $ret="A error caused your renewal to be canceled. Please try again later.";  }
        if($rt[result]=="2") { $ret="The credit card you have supplied for this transaction declined.";  }
        if (((($rt[result]=="1") || ($rt[result]=="5")  || ($rt[result]=="4")   || ($rt[result]=="25")))) {
	        $billed_amount = $rt[amount];        

                if ($rt[$result] != 25) { create_billed_record($account_id,$domain_id,$billed_amount); }
                renew_domain_record($account_id,$domain_id,$new_term);

                $ret="Your card was successfully charged the fees for the domain renewal. <BR>Thank you!";
                }
return $ret;
}


// BILL A SPECIFIED DOMAIN ACCOUNT
function quick_bill($billing_id,$domain_id,$host_id,$account_id) {
        $db = new ps_DB;
        $q  = "SELECT * FROM account WHERE account_id = '$account_id'";
        $db->query($q);
        $db->next_record();
                $name         =        $db->f("account_name");
                $address      =        $db->f("account_address");
                $city             =        $db->f("account_city");
                $state          =        $db->f("account_state");
                $zip             =        $db->f("account_zip");
                $phone        =        $db->f("account_phone");
                $fax            =        $db->f("account_fax");
                $email        =        $db->f("account_email");

        $db = new ps_DB;
        $q  = "SELECT * FROM billing WHERE billing_id = '$billing_id'";
        $db->query($q);
        $db->next_record();
                $cc_num=$db->f("billing_cc_num");
                $cc_num=RC4($cc_num,"de");
                $cc_exp=$db->f("billing_cc_exp");

        $db = new ps_DB;
        $q  = "SELECT membership_price,membership_name FROM membership WHERE membership_id = '$host_id'";
        $db->query($q);
                $db->next_record();
                $amount=$db->f("membership_price");
                $desc=$db->f("membership_name");


            $rt = card($account_id,$name,$address,$zip,$cc_num,$cc_exp,$amount);
 
        if (((($rt[result]=="1") || ($rt[result]=="4")  || ($rt[result]=="5")   || ($rt[result]=="25")))) {
	        $billed_amount = $rt[amount];        
	              	create_billed_record_3($account_id,$domain_id,$billed_amount);
                }
                
                $ret = $rt[result];

return $ret;
}




// BILL ALL OVERDUE ACCOUNTS NOW!
function bill_all_now() {
        $db = new ps_DB;
        $q  = "SELECT * FROM domains";
        $db->query($q);
                $host_id=$db->f("domain_host_id");

        $ret="";
            while ($db->next_record()) {
                        $status = get_billing_status_3($db->f("domain_host_id"),$db->f("domain_host_periods"),$db->f("domain_host_last_billed"),$db->f("domain_host_last_billed"));
                    if ($status=="Y") {
                            $domain=$db->f("domain_name");
                            $ret.= "Attempting to bill charges for ";
                            $ret.= $domain;
                            $billed= quick_bill($db->f("domain_billing_id"),$db->f("domain_id"),$db->f("domain_host_id"),$db->f("domain_account_id"));

                                if ((($billed=="1") || ($billed=="4") || ($billed=="5"))) {
                                        update_billed_domain($db->f("domain_id"),$db->f("domain_host_periods"));
                                        $ret.= " --  Billed & Updated -- <BR>";
                                        
                                        			// EMAIL THE CUSTOMER
											if (setup("email_17")==Y) {
											send_mail("17",$db->f("domain_account_id"),$db->f("domain_id"),"","");
											}

                                } elseif ($billed=="2") {
                                        $ret.= "  -- Charge declined! -- <BR>";
                                        
                                        			// EMAIL THE CUSTOMER
											if (setup("email_19")==Y) {
											send_mail("19",$db->f("domain_account_id"),$db->f("domain_id"),"","");
											}

                                        
                                } elseif ($billed=="3") {
                                        $ret.=  " -- CURL ERROR or NO RESPONSE --<BR>";
                                } else { 
	                                	$ret.= "-- An unkown error occurred<BR>!";
	                                	}
                }
        }
return $ret;
}


// CRON JOB TO BILL ALL OVERDUE ACCOUNTS NOW!
function cron_bill() {
        $db = new ps_DB;
        $q  = "SELECT * FROM domains";
        $db->query($q);
        $host_id=$db->f("domain_host_id");
            while ($db->next_record()) {
                        $status = get_billing_status_3($db->f("domain_host_id"),$db->f("domain_host_periods"),$db->f("domain_host_last_billed"),$db->f("domain_host_last_billed"));
                    if ($status=="Y") {
                            $domain=$db->f("domain_name");
                            $billed= quick_bill($db->f("domain_billing_id"),$db->f("domain_id"),$db->f("domain_host_id"),$db->f("domain_account_id"));

                                if ($billed=="1") {
                                        update_billed_domain($db->f("domain_id"),$db->f("domain_host_periods"));

                                } elseif ($billed=="2") {
                                } elseif ($billed=="3") {
                                } else {  }
                }
        }
return true;
}


// BILL A DOMAIN MANUALLY
function bill_domain_manual($account_id,$domain_id,$amount) {

   $db = new ps_DB;
   $q = "SELECT domain_host_periods FROM domains WHERE domain_id='$domain_id'";
   $db->query($q);
   $db->next_record();
 

	update_billed_domain($domain_id,$db->f("domain_host_periods"));
	create_billed_record_3($account_id,$domain_id,$amount);
}


// UPDATE A NEWLY BILLED DOMAIN
function update_billed_domain($domain_id,$periods) {
        $today=date("Y-m-d");
          $periods++;
        $db = new ps_DB;
        $q  = "UPDATE domains SET
                   domain_host_periods='$periods',
                   domain_host_last_billed='$today'
                   WHERE domain_id='$domain_id'";
        $db->query($q);

}

// GET THE TOTAL NUMBER OF TIMES AN ACCOUNT HAS BEEN BILLED (FOR ACCOUNT PAGE)
function count_billing($id) {
        $db = new ps_DB;
        $q  = "SELECT billed_account_id FROM billed WHERE billed_account_id='$id'";
        $db->query($q);
		$ret=$db->num_rows();
return $ret;
}

// GET TOTAL NUMBER OF OVERDUE DOMAINS (FOR STATISTICS)
function get_total_overdue_domains() {
        $db = new ps_DB;
        $q  = "SELECT domain_id,domain_host_id FROM domains";
        $db->query($q);
        $i=0;
        $cost=0;
        while ($db->next_record()) {
                $status = quick_billing_status($db->f("domain_id"));
                if($status=="Y") {
                        $i++;
                        $price = get_hosting_cost($db->f("domain_host_id"));
                        $cost=$cost+$price;
                }
        }
        $num = "<B>";
        $num.= $i;
        $num.= "</B> (" . setup("currency");
        $num.= $cost;
        $num.= ")";
return $num;
}


// PRINT DOMAIN BILLING STATUS (FOR DOMAIN PAGE)
function quick_billing_status($domain_id) {
        $db = new ps_DB;
        $q  = "SELECT domain_start_date,domain_host_id,domain_host_periods,domain_host_last_billed FROM domains WHERE domain_id='$domain_id'";
        $db->query($q);
        while ($db->next_record()) {
                if ($db->f("domain_host_periods") == 0) {
                        $ret.= "N";
                } elseif($db->f("domain_host_id") == 0) {
                        $ret.= "N";
                } else {
                        $status = get_billing_status_2($db->f("domain_host_id"),$db->f("domain_host_periods"),$db->f("domain_host_last_billed"));
                        if ($status==N) {
                                $ret="N";
                        } elseif($status==Y) {
                                $ret="Y";
                        } elseif($status < 0) {
                                $ret="Y";
                        } elseif ($status > 0) {
                                $ret="N";
                        } else {
                        }
                }
        }
return $ret;
}




// PRINT DOMAIN BILLING STATUS (FOR DOMAIN PAGE)
function get_billing_status($domain_id) {
        $db = new ps_DB;
        $q  = "SELECT domain_start_date,domain_host_id,domain_host_periods,domain_host_last_billed FROM domains WHERE domain_id='$domain_id'";
        $db->query($q);
        while ($db->next_record()) {
                if ($db->f("domain_host_periods") == 0) {
                        $ret.= "Invoice Not Billed";
                } elseif($db->f("domain_host_id") == 0) {
                        $ret.= " - ";
                } else {
                        $status = get_billing_status_2($db->f("domain_host_id"),$db->f("domain_host_periods"),$db->f("domain_host_last_billed"));
                        if ($status==N) {
                                $ret=" -- DONE --";
                        } elseif($status==Y) {
                                $ret="<b>DUE TODAY!</b>";
                        } elseif($status < 0) {
                                $diff=1000+$status;
                                $od = 1000-$diff;
                                $ret="<b>Due $od days ago!</b>";
                        } elseif ($status > 0) {
                                $ret="Due in $status days";
                        } else {
                        }
                }
        }
return $ret;
}

// GET DOMAIN BILLING STATUS PART 2
function get_billing_status_2($membership_id,$current_periods,$last_billed) {
        $db = new ps_DB;
        $q  = "SELECT membership_recurring,membership_frequency,membership_periods FROM membership WHERE membership_id='$membership_id'";
        $db->query($q);
        $db->next_record();

        $today=date("Y-m-d");
            $today_date=strtotime($today);
        $db_date=strtotime($last_billed);
        //$date_diff=$today_date-$db_date;
        $date_diff =(($today_date-$db_date)/86400);
        $total_periods=$db->f("membership_periods");
        $frequency=$db->f("membership_frequency");
        $recurring=$db->f("membership_recurring");
        $periods_diff=$total_periods - $current_periods;

        if($recurring==Y) {
        // THIS IS A RECURRING CHARGE. WE WILL CONTINUE...

                if($periods_diff >= 1) {
                // THERE ARE REMANING BILLING PERIODS. WE WILL CONTINUE...

                        if($frequency == $date_diff) {
                        // THIS CHARGE IS DUE TODAY!
                        $ret = "Y";

                        } elseif($frequency < $date_diff) {
                        // THIS CHARGE WAS DUE BEFORE TODAY!
                        $ret = $frequency - $date_diff;

                        } elseif($frequency > $date_diff) {
                        // THIS CHARGE IS NOTE DUE YET
                        $ret = $frequency - $date_diff;
                                } else {
                        }
                }


        } else {
        // THIS IS NOT A RECURRING CHARGE. THIS MUST BE BILLED FROM THE INVOICE AREA
        $ret = "N";
        }
return $ret;
}



// GET DOMAIN BILLING STATUS PART #3
function get_billing_status_3($membership_id,$current_periods,$last_billed) {
        $db = new ps_DB;
        $q  = "SELECT membership_recurring,membership_frequency,membership_periods FROM membership WHERE membership_id='$membership_id'";
        $db->query($q);
        $db->next_record();

        $today=date("Y-m-d");
            $today_date=strtotime($today);
        $db_date=strtotime($last_billed);
        //$date_diff=$today_date-$db_date;
        $date_diff =(($today_date-$db_date)/86400);
        $total_periods=$db->f("membership_periods");
        $frequency=$db->f("membership_frequency");
        $recurring=$db->f("membership_recurring");
        $periods_diff=$total_periods - $current_periods;

        if($recurring==Y) {
        // THIS IS A RECURRING CHARGE. WE WILL CONTINUE...

                if($periods_diff >= 1) {
                // THERE ARE REMANING BILLING PERIODS. WE WILL CONTINUE...

                        if($frequency <= $date_diff) {
                        // THIS CHARGE IS DUE TODAY!
                        $ret = "Y";

                        } elseif($frequency > $date_diff) {
                        // THIS CHARGE IS NOTE DUE YET
                        $ret = "N";
                                } else {
                        }
                }


        } else {
        // THIS IS NOT A RECURRING CHARGE. THIS MUST BE BILLED FROM THE INVOICE AREA
        $ret = "N";
        }
return $ret;
}






// GET TOTAL NUMBER OF SESSIONS (FOR STATISTICS)
function get_total_sessions() {
        $db = new ps_DB;
        $q  = "SELECT session_id FROM sessions";
        $db->query($q);
        $num = $db->num_rows();
return $num;
}



// GET TOTAL NUMBER OF LOGINS (FOR STATISTICS)
function get_total_logins() {
        $db = new ps_DB;
        $q  = "SELECT login_id FROM login";
        $db->query($q);
        $num = $db->num_rows();
return $num;
}



// GET TOTAL NUMBER OF DOMAINS (FOR STATISTICS)
function get_total_domains() {
        $db = new ps_DB;
        $q  = "SELECT domain_id FROM domains";
        $db->query($q);
        $num = $db->num_rows();
return $num;
}


// GET TOTAL NUMBER OF NEW DOMAINS (FOR STATISTICS)
function get_total_domains_today() {
        $today=date("Y-m-d");
        $db = new ps_DB;
        $q  = "SELECT domain_id FROM domains WHERE domain_start_date='$today'";
        $db->query($q);
        $num = $db->num_rows();
return $num;
}



// GET TOTAL NUMBER OF ACCOUNTS (FOR STATISTICS)
function get_total_accounts() {
        $db = new ps_DB;
        $q  = "SELECT account_id FROM account";
        $db->query($q);
        $num = $db->num_rows();
return $num;
}


// GET TOTAL NUMBER OF PENDING ORDERS (FOR STATISTICS)
function get_total_pending_orders() {
        $db = new ps_DB;
        $q  = "SELECT order_id,order_amount FROM orders WHERE order_status='0'";
        $db->query($q);
        $amount="";
        $num = $db->num_rows();
                while($db->next_record()) {
                $total = $db->f("order_amount");
                $amount = $total+$amount;
                }
        $ret - "<B>";
        $ret .= $num;
        $ret.= " </B>(";
        $ret.= setup("currency");
        $ret.= $amount;
        $ret.= ") ";
return $ret;
}



// GET TOTAL NUMBER OF COMPLETE ORDERS (FOR STATISTICS)
function get_total_complete_orders() {
        $db = new ps_DB;
        $q  = "SELECT order_id,order_amount FROM orders WHERE order_status='1'";
        $db->query($q);
        $amount="";
        $num = $db->num_rows();
                while($db->next_record()) {
                $total = $db->f("order_amount");
                $amount = $total+$amount;
                }
        $ret - "<B>";
        $ret .= $num;
        $ret.= " </B>(";
        $ret.= setup("currency");
        $ret.= $amount;
        $ret.= ") ";
return $ret;
}




// GET TOTAL NUMBER OF ORDERS (FOR STATISTICS)
function get_total_orders() {
        $db = new ps_DB;
        $q  = "SELECT order_id,order_amount FROM orders";
        $db->query($q);
        $amount="";
        $num = $db->num_rows();
                while($db->next_record()) {
                $total = $db->f("order_amount");
                $amount = $total+$amount;
                }
        $ret - "<B>";
        $ret .= $num;
        $ret.= " </B>(";
        $ret.= setup("currency");
        $ret.= $amount;
        $ret.= ") ";
return $ret;
}



// GET TOTAL NUMBER OF ORDERS TODAY (FOR STATISTICS)
function get_total_orders_today() {
        $today=date("Y-m-d");
        $db = new ps_DB;
        $q  = "SELECT order_id,order_amount FROM orders WHERE order_date='$today'";
        $db->query($q);
        $amount="";
        $num = $db->num_rows();
                while($db->next_record()) {
                $total = $db->f("order_amount");
                $amount = $total+$amount;
                }
        $ret - "<B>";
        $ret .= $num;
        $ret.= " </B>(";
        $ret.= setup("currency");
        $ret.= $amount;
        $ret.= ") ";
return $ret;
}



// SHOW SELECTED HOSTING NAME & PRICE
function show_hosting_option($membership_id) {
        
        $db = new ps_DB;
        $q = "SELECT membership_name,membership_price FROM membership WHERE membership_id ='$membership_id'";
        $db->query($q);
        $db->next_record();
        $name = $db->f("membership_name");
        $price= $db->f("membership_price");
        $ret = $name;
        $ret.= " - (";
        $ret.= setup("currency");
        $ret.= $price;
        $ret.= ") ";
return $ret;
}




// SHOW CLIENTS NAME
function show_client_name($account_id) {
        $db = new ps_DB;
        $q  = "SELECT account_name FROM account WHERE account_id ='$account_id'";
        $db->query($q);
        $db->next_record();
        $name = $db->f("account_name");
return $name;
}




// SHOW CLIENTS DOMAINS
function show_client_domains($client_id) {
        $db = new ps_DB;
        $q  = "SELECT domain_id FROM domains WHERE domain_account_id ='$client_id'";
        $db->query($q);
        $num = $db->num_rows();
return $num;
}


// SHOW CLIENTS ORDERS
function show_client_orders($client_id) {
        $db = new ps_DB;
        $q  = "SELECT order_id FROM orders WHERE order_account_id ='$client_id'";
        $db->query($q);
        $num = $db->num_rows();
return $num;
}



// DELETE THE USERS CART SESSION
function delete_sessions($session_ip) {
        $db = new ps_DB;
        $q  = "DELETE FROM sessions WHERE session_ip ='$session_ip'";
        $db->query($q);
}


// CREATE A DOMAIN RECORD FOR EACH DOMAIN IN CART
function create_domain_record($billed,$order_id,$account_id,$billing_id,$session_ip) {
        $db = new ps_DB;
        $q  = "SELECT * FROM sessions WHERE session_ip='$session_ip'";
        $db->query($q);
        while ($db->next_record()) {
                $domain = $db->f("session_domain");
                $years  = $db->f("session_1");
                $host_id  = $db->f("session_2");
                create_domain_record_1($billed,$account_id,$order_id,$billing_id,$domain,$years,$host_id);
                }
return true;
}

// CREATE TLD (DOMAIN_TYPE) RECORD
function add_domain_type($tld_id,$name,$extension,$status,$url,$response,$auto,$p1,$p2,$p3,$p4,$p5,$p6,$p7,$p8,$p9,$p10) {
        $db = new ps_DB;
        $q = "INSERT INTO domain_type SET
                domain_type_id        =        '$tld_id',
                domain_type_name        =        '$name',
                domain_type_extension        =        '$extension',
                domain_type_status        =        '$status',
                domain_type_url        =        '$url',
                domain_type_response        =        '$response',
                domain_type_auto        =        '$auto',
                domain_type_p1        =        '$p1',
                domain_type_p2        =        '$p2',
                domain_type_p3        =        '$p3',
                domain_type_p4        =        '$p4',
                domain_type_p5        =        '$p5',
                domain_type_p6        =        '$p6',
                domain_type_p7        =        '$p7',
                domain_type_p8        =        '$p8',
                domain_type_p9        =        '$p9',
                domain_type_p10        =        '$p10'";
        $db->query($q);
return $true;
}


// DELETE TLD (DOMAIN_TYPE) RECORD
function tld_delete($domain_type_id) {
        $db = new ps_DB;
        $q = "DELETE FROM domain_type WHERE domain_type_id='$domain_type_id'";
        $db->query($q);
return $true;
}


// UPDATE TLD (DOMAIN_TYPE) RECORD
function update_domain_type($tld_id,$name,$extension,$status,$url,$response,$auto,$p1,$p2,$p3,$p4,$p5,$p6,$p7,$p8,$p9,$p10) {
        $db = new ps_DB;
        $q = "UPDATE domain_type SET
                domain_type_id        =        '$tld_id',
                domain_type_name        =        '$name',
                domain_type_extension        =        '$extension',
                domain_type_status        =        '$status',
                domain_type_url        =        '$url',
                domain_type_response        =        '$response',
                domain_type_auto        =        '$auto',
                domain_type_p1        =        '$p1',
                domain_type_p2        =        '$p2',
                domain_type_p3        =        '$p3',
                domain_type_p4        =        '$p4',
                domain_type_p5        =        '$p5',
                domain_type_p6        =        '$p6',
                domain_type_p7        =        '$p7',
                domain_type_p8        =        '$p8',
                domain_type_p9        =        '$p9',
                domain_type_p10        =        '$p10'
                WHERE domain_type_id        =        '$tld_id'";
        $db->query($q);
return $true;
}

// CREATE ACTUAL DOMAIN RECORD
function create_domain_record_1($billed,$account_id,$order_id,$billing_id,$domain,$years,$host_id) {
        $db = new ps_DB;
        $today=date("Y-m-d");
        $tld                 = determine_domain_tld($domain);
        $domain_type= determine_domain_type($tld);
        $q = "INSERT INTO domains SET
                domain_account_id                =        '$account_id',
                domain_order_id                =        '$order_id',
                domain_billing_id                =        '$billing_id',
                domain_type_id                =        '$domain_type',
                domain_name                        =        '$domain',
                domain_start_date                =        '$today',
                domain_years                =        '$years',
                domain_host_id                =        '$host_id',";
        if($billed=="Y") {
                $q.= "domain_host_periods        =        '1',
                domain_host_last_billed        =        '$today'";
        } else {

                $q.= "domain_host_periods        =        '0',
                domain_host_last_billed        =        ''";
        }
                $db->query($q);
return $true;
}


// DETERMINE DOMAIN TYPE
function determine_domain_type($tld) {
        $db = new ps_DB;
        $q = "SELECT domain_type_id FROM domain_type WHERE domain_type_extension = '$tld'";
        $db->query($q);
        $db->next_record();
return $db->f("domain_type_id");
}



// CREATE AN ORDER RECORD
function create_order_record($order_id,$account_id,$billing_id,$amount,$setup,$sess,$attr,$name,$value) {
        $today=date("Y-m-d");
        $aid = get_aid("$sess");

        
        $db = new ps_DB;
        $q = "INSERT INTO orders SET
                order_id='$order_id',
                order_account_id='$account_id',
                order_billing_id='$billing_id',
                order_affiliate_id='$aid', 
                order_amount='$amount',
			 order_setup='$setup',
                order_date='$today',
                order_attr_name_1	=	'$name[0]', 
                order_attr_value_1	=	'$value[0]',
                order_attr_name_2	=	'$name[1]',
                order_attr_value_2	=	'$value[1]',
                order_attr_name_3	=	'$name[2]',
                order_attr_value_3	=	'$value[2]',
                order_attr_name_4	=	'$name[3]',
                order_attr_value_4	=	'$value[3]',
                order_attr_name_5	=	'$name[4]',
                order_attr_value_5	=	'$value[4]',
                order_attr_name_6	=	'$name[5]',
                order_attr_value_6	=	'$value[5]',
                order_attr_name_7	=	'$name[6]',
                order_attr_value_7	=	'$value[6]',
                order_attr_name_8	=	'$name[7]',
                order_attr_value_8	=	'$value[7]',
                order_attr_name_9	=	'$name[8]',
                order_attr_value_9	=	'$value[8]',
                order_attr_name_10	=	'$name[9]',
                order_attr_value_10	=	'$value[9]'";
               $db->query($q);
return true;
}


// CREATE A BILLING RECORD FOR THE CREDIT CARD INFORMATION ENTERED
function create_billing_record($account_id,$cc_num,$cc_exp,$order_id) {
	$cc_num=RC4($cc_num,"en");
        $db = new ps_DB;
        $q = "INSERT INTO billing SET
                billing_account_id='$account_id',
                billing_cc_num='$cc_num',
                billing_cc_exp='$cc_exp',
                billing_order_id='$order_id'";
        $db->query($q);


        $db = new ps_DB;
        $q = "SELECT billing_id FROM billing WHERE billing_order_id='$order_id'";
        $db->query($q);
        while ($db->next_record()) {
                $ret = $db->f("billing_id");
                }
return $ret;

}



// GENERATE THE NEXT ORDER ID
function next_order_id() {
        $db = new ps_DB;
        $q = "SELECT order_id FROM orders";
        $db->query($q);
        while ($db->next_record()) {
        		$last=$db->f("order_id");
        }
        
        $ret = $last+2;
return $ret;
}





// UPDATE MEMBER ACCOUNT INFO
function update_member_account($name,$company,$address,$city,$state,$zip,$phone,$fax,$email,$password) {
        $db = new ps_DB;
        $q = "UPDATE account SET
                account_email ='$email',
                account_password ='$password',
                account_name ='$name',
                account_company ='$company',
                account_address ='$address',
                account_city ='$city',
                account_state ='$state',
                account_zip ='$zip',
                account_country ='$country',
                account_phone ='$phone',
                account_fax ='$fax'
                WHERE account_email ='$email'";
        $db->query($q);
        
        // EMAIL CUSTOMER
        if(setup("email_13")==Y) {
       	 $db = new ps_DB;
        	 $q = "SELECT account_id FROM account WHERE account_email='$email'";
        	 $db->query($q);
        	 $db->next_record();
        	 
        	 $account_id=$db->f("account_id");
        	 
        	 // EMAIL THE CUSTOMER        	 
        	 send_mail("13",$account_id,"","","");
	        
        }
        
        
        return true;
}



// SEND LOST PASSWORD
function send_password($email) {
        $db = new ps_DB;
        $q = "SELECT account_name,account_password,account_email FROM account WHERE account_email='$email'";
        $db->query($q);
        if ($db->next_record()) {
                $name =$db->f("account_name");
                $ret="$name, your password has been sent to $email";
                email_pw($db->f("account_password"),$email,$name);
                } else {
                $ret="The email address $email was not found in our database";
                }
return $ret;
}



// CHECK FOR DUPLICATE USER ACCOUNT
function validate_email_unused($email) {
        $db = new ps_DB;
        $q = "SELECT account_email FROM account WHERE account_email='$email'";
        $db->query($q);
        if ($db->num_rows()==0) {
                $ret="Y";
        } else {
                $ret="N";
        }
return $ret;
}


// ADD NEW USER ACCOUNT
function add_new_user_account($sess,$email,$password,$name,$company,$address,$city,$state,$zip,$country,$phone,$fax) {
	   $aid = get_aid($sess);
        $db = new ps_DB;
        $q = "INSERT INTO account SET
        		account_affiliate_id='$aid', 
                account_email ='$email',
                account_password ='$password',
                account_name ='$name',
                account_company ='$company',
                account_address ='$address',
                account_city ='$city',
                account_state ='$state',
                account_zip ='$zip',
                account_country ='$country',
                account_phone ='$phone',
                account_fax ='$fax'";
        $db->query($q);

	
        if(setup("email_1") == Y) {
	    		$db = new ps_DB;
        		$q = "SELECT account_id FROM account WHERE account_email = '$email'";
        		$db->query($q);
        		$db->next_record();
        send_mail("1",$db->f("account_id"),"","","");
        }

        if(setup("email_11") == Y) {
	    		$db = new ps_DB;
        		$q = "SELECT account_id FROM account WHERE account_email = '$email'";
        		$db->query($q);
        		$db->next_record();
        send_mail("11",$db->f("account_id"),"","","");
        }return true;
        
        
}


// GET THE AFFILIATE ID FROM THE LOGIN RECORD...
function get_aid($sess) {
	    $db = new ps_DB;
        $q = "SELECT login_affiliate_id FROM login WHERE login_id='$sess'";
        $db->query($q);
        $db->next_record();
        $ret = $db->f("login_affiliate_id");
return $ret;
}



// SHOW LIST OF COUNTRIES
function show_country_menu() {
        $default = "840";
        $db = new ps_DB;
        $q = "SELECT country_id,country_name FROM country ORDER BY 'country_name'";
        $db->query($q);
        echo "<SELECT NAME=\"country\">";
        while ($db->next_record()) {
                $dbn  = $db->f("country_name");
                $dbi  = $db->f("country_id");
                $title = $dbn;
                echo "<OPTION VALUE=\"$dbi\"";
                        if ($default == $dbi) echo " selected";
                echo ">$title</OPTION>";
                        }

echo "</SELECT>";
}



//VALIDATE EMAIL
function validate_email($email) {
        if(ereg("^.+@.+\\..+$", $email)) {
                $ret="Y";
        } else {
                $ret="N";
        }
return $ret;
}

// SHOW ACCEPTABLE TLD'S
function show_tld_list() {
        $q = "SELECT domain_type_extension FROM domain_type WHERE domain_type_status = 'Y' ORDER BY 'domain_type_extension'";
        $db = new ps_DB;
        $db->query($q);
                $ret="";
                while ($db->next_record()){
                        $ret.=" .";
                        $ret.= $db->f("domain_type_extension");
                        $ret.="<BR>";
                        }
        return $ret;
}



// GET LOGGED IN USERS ACCOUNT ID
function get_account_id($session_id) {
        $db = new ps_DB;
        $q = "SELECT login_member_id FROM login WHERE login_id = '$session_id'";
        $db->query($q);
        $db->next_record();
        $ret = $db->f("login_member_id");
return $ret;
}



// ATTEMPT TO LOG THE USER IN
function login_user($email,$password,$session_id) {
        $today=date("Y-m-d");
        $aid = get_aid($session_id);
	
        $db = new ps_DB;
        $q = "SELECT account_id,account_email,account_password FROM account WHERE account_email = '$email'";
        $db->query($q);
        $db->next_record();
        $account_id = $db->f("account_id");
        
        $account_email = $db->f("account_email");
        $account_pw     = $db->f("account_password");
        
        if (($email == $account_email) && ($password == $account_pw)) {
        
        delete_old_logins($account_id,$session_id);       

        if ($db->num_rows() >= 1) {
                $db = new ps_DB;
                $q = "INSERT INTO login SET 
					login_id = '$session_id', 
					login_date='$today',  
					login_logged='Y', 
					login_affiliate_id='$aid',  
					login_member_id='$account_id'";
                $db->query($q);
        }
       }

return true;
}


// DELETE OLD LOGIN SESSIONS
function delete_old_logins($account_id,$session_id) {
    $db = new ps_DB;
    $q = "DELETE FROM login WHERE login_member_id='$account_id' OR login_id='$session_id'";
    $db->query($q);
return true;
}

// SEE IF THE CURRENT SESSION IS LOGGED IN
function is_logged($session_id) {
        $db = new ps_DB;
        $q = "SELECT * FROM login WHERE login_id = '$session_id'";
        $db->query($q);
        $db->next_record();
        $ret = $db->f("login_logged");
return $ret;
}


// GENERATE A NEW SESSION
function generate_session_id ($aid)  {
		$pass_len = 16;
        $today=date("Y-m-d");
        $nps = "";
        mt_srand ((double) microtime() * 1000000);
                while (strlen($nps)<$pass_len)
                { $c = chr(mt_rand (0,255));
        if (eregi("^[a-z0-9]$", $c)) $nps = $nps.$c; };

        $db = new ps_DB;
        $q = "INSERT INTO login SET
                login_id 			= '$nps',
                login_logged 		= 'N',
                login_affiliate_id 	= '$aid',
                login_date 			= '$today'";
        $db->query($q);

return ($nps);
}

// SHOW HOSTING OPTIONS & PRICES MENU
function show_hosting_menu($domain,$count,$id) {
        $db = new ps_DB;
        $q = "SELECT session_2 FROM sessions WHERE session_id = '$id'";
        $db->query($q);
        $db->next_record();
        $default = $db->f("session_2");


        $db = new ps_DB;
        $q = "SELECT membership_setup,membership_name,membership_price,membership_id FROM membership WHERE membership_active='Y' ORDER BY 'membership_id'";
        $db->query($q);

        $style="style= \"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9px; font-weight: normal; color: #000000; background-color: #F5F5F5\"";

        echo "<SELECT NAME=\"H[$count]\"" . $style . ">";
        while ($db->next_record()) {
                $dbn  = $db->f("membership_name");
                $dbp  = $db->f("membership_price");
                $dbi  = $db->f("membership_id");
                $dbs  = $db->f("membership_setup");
                $title = $dbn;
                $title.= " (";
                $title.= setup("currency");
                $title.= $dbp;
                $title.= ")";
                if ($dbs>0) {
                    $title .= " + (" . setup("currency") . "" . $dbs . " setup)";
                }

                echo "<OPTION VALUE=\"$dbi\"";
                        if ($default == $dbi) echo " selected";
                echo ">$title</OPTION>";
                        }

echo "</SELECT>";
return $ret;
}


// SHOW DOMAIN OPTIONS & PRICES MENU
function show_domain_menu($domain,$count,$id) {
        $db = new ps_DB;
        $q = "SELECT * FROM sessions WHERE session_id = '$id'";
        $db->query($q);
        $db->next_record();
        $default = $db->f("session_1");

        $tld = determine_domain_tld($domain);
        $db = new ps_DB;
        $q = "SELECT * FROM domain_type WHERE domain_type_extension = '$tld'";
        $db->query($q);
        $db->next_record();
        $i=1;

         $style="style= \"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9px; font-weight: bold; color: #000066; background-color: #CCCCCC\"";

        echo "<SELECT NAME=\"L[$count]\"" . $style . ">";

        while ($i <= 10) {

                $d        = "domain_type_p";
                $d   .= $i;
                $dbf  = $db->f("$d");
                $title = $i;
                        if ($i=="1") {
                        $title.= " year  (";
                        } else {
                        $title.= " years (";
                        }
                $title.= setup("currency");
                $title.= $dbf;
                $title.= ")";

                if ($dbf <> "") {
                        echo "<OPTION VALUE=\"$i\"";
                                if ($default == $i) echo " selected";
                        echo ">$title</OPTION>";
                        }

                $i++;
        }
echo "</SELECT>";
return $ret;
}


// SHOW DOMAIN OPTIONS & PRICES MENU
function show_domain_menu_2($domain) {
        $db = new ps_DB;
        $q = "SELECT * FROM sessions WHERE session_id = '$id'";
        $db->query($q);
        $db->next_record();
        $default = $db->f("session_1");

        $tld = determine_domain_tld($domain);
        $db = new ps_DB;
        $q = "SELECT * FROM domain_type WHERE domain_type_extension = '$tld'";
        $db->query($q);
        $db->next_record();
        $i=1;


        echo "<SELECT NAME=\"new_term\">";

        while ($i <= 10) {

                $d        = "domain_type_p";
                $d   .= $i;
                $dbf  = $db->f("$d");
                $title = $i;
                        if ($i=="1") {
                        $title.= " year  (";
                        } else {
                        $title.= " years (";
                        }
                $title.= setup("currency");
                $title.= $dbf;
                $title.= ")";

                if ($dbf <> "") {
                        echo "<OPTION VALUE=\"$i\"";
                                if ($default == $i) echo " selected";
                        echo ">$title</OPTION>";
                        }

                $i++;
        }
echo "</SELECT>";
return $ret;
}

// GET TOTAL SETUP FEES
function show_setup_cost($ip) {
        $db = new ps_DB;
        $q = "SELECT session_2 FROM sessions WHERE session_ip = '$ip'";
        $db->query($q);
        $total=0;
        while ($db->next_record()) {
                $membership_id = $db->f("session_2");
                $cost  = get_setup_cost($membership_id);
                $total = $cost+$total;
        }
return $total;
}


// GET TOTAL SETUP FEES PART 2
function get_setup_cost($membership_id) {
        $db = new ps_DB;
        $q = "SELECT membership_setup FROM membership WHERE membership_id = '$membership_id'";
        $db->query($q);
        $db->next_record();
        $ret = $db->f("membership_setup");
return $ret;
}











// GET TOTAL HOSTING COST
function show_hosting_cost($ip) {
        $db = new ps_DB;
        $q = "SELECT session_2 FROM sessions WHERE session_ip = '$ip'";
        $db->query($q);
        $total=0;
        while ($db->next_record()) {
                 $membership_id = $db->f("session_2");
                $cost  = get_hosting_cost($membership_id);
                $total = $cost+$total;
        }
return $total;
}

// GET TOTAL HOSTING COST
function get_hosting_cost($membership_id) {
        $db = new ps_DB;
        $q = "SELECT membership_price FROM membership WHERE membership_id = '$membership_id'";
        $db->query($q);
        $db->next_record();
        $ret = $db->f("membership_price");
return $ret;
}


// GET TOTAL REGISTRATION COST
function show_registration_cost($ip) {
        $db = new ps_DB;
        $q = "SELECT * FROM sessions WHERE session_ip = '$ip'";
        $db->query($q);
        $total=0;
        while ($db->next_record()) {
                 $years = $db->f("session_1");

                if ($years == "0") {
                        $cost = "0";

                        } else {
                $tld   = determine_domain_tld($db->f("session_domain"));
                $cost  = get_yearly_cost($years,$tld);
                }
                $total = $cost*$years+$total;
        }
return $total;
}

// GET YEARLY REGISTRATION COST
function get_yearly_cost($years,$tld) {
        $db = new ps_DB;
        $q = "SELECT domain_type_p$years FROM domain_type WHERE domain_type_extension = '$tld'";
        $db->query($q);
        $db->next_record();
        $ret = $db->f("domain_type_p$years");
return $ret;
}




// UPDATE THE CART & CART OPTIONS
function cart_update_domain($id,$length,$hosting) {
        $db = new ps_DB;
        $q = "UPDATE sessions SET
                session_1 = '$length',
                session_2 = '$hosting'
                WHERE session_id = '$id'";
        $db->query($q);
return true;
}



// REMOVE A DOMAIN FROM THE CART
function cart_remove_domain($id) {
        $db = new ps_DB;
        $q = "DELETE FROM sessions WHERE session_id='$id'";
        $db->query($q);
}


// SETUP A NEW SESSION BASED ON USERS IP
function cart_add($ip,$domain,$length,$hosting) {

        $db = new ps_DB;
        $q = "SELECT session_domain,session_id FROM sessions WHERE session_ip='$ip' AND session_domain ='$domain'";
        $db->query($q);
        $db->next_record();
        $db_domain = $db->f("session_domain");
                if ($db_domain == $domain) {
                        return "$domain is already in your cart.";
                        } else {

        $db = new ps_DB;
        $q = "INSERT INTO sessions SET
                session_ip                 = '$ip',
                session_domain        = '$domain',
                session_1                = '$length',
                session_2                = '$hosting'";
        $db->query($q);
        return true;
        }

}

// DETERMINE IF CART IS EMPTY OR NOT
function cart_mini($ip) {
        $db = new ps_DB;
        $q = "SELECT session_id FROM sessions WHERE session_ip='$ip'";
        $db->query($q);
        $num = $db->num_rows();
        $ret = $num;
return $ret;
}




// SHOW THE CURRENT WHOIS INFORMATION FOR A DOMAIN
function return_domain_owner($domain) {
        $tld = determine_domain_tld($domain);
        $q = "SELECT domain_type_url,domain_type_response FROM domain_type WHERE domain_type_extension = '$tld' AND domain_type_status = 'Y'";
        $db = new ps_DB;
        $db->query($q);
                while ($db->next_record()) {
                        $server = $db->f("domain_type_url");
                        $respon = $db->f("domain_type_response");
                        $data = get_whois_status($server,$domain);
                        if ($data=="") {
                        $ret= "No information avalable from WHOIS server at this time.";
                        } else {
                        $ret= "<pre>$data</pre>";
                        }
                }         return $ret;
}


// DETERMINE IF THE CHOSEN DOMAIN IS AVAILABLE
function return_domain_status($domain,$session_id) {
	
        $ret="";
        $tld = determine_domain_tld($domain);
        
        if ($tld != "") { 
	          $del_tld = ".";
	          $del_tld .= $tld; 
	          $split = split("\.", $domain);
        		$dmn = check_domain_validity($split[0]);
        } else {
	        	$dmn = check_domain_validity($domain);
        	}

        if ($tld != "") {
        $q = "SELECT domain_type_url,domain_type_response,domain_type_extension FROM domain_type WHERE domain_type_extension = '$tld' AND domain_type_status = 'Y'";
        $period=".";      
        } else {
        $q = "SELECT domain_type_url,domain_type_response,domain_type_extension FROM domain_type WHERE domain_type_status = 'Y' AND domain_type_auto = 'Y'  ORDER BY 'domain_id'";
        $period=".";
        }

        $db = new ps_DB;
        $db->query($q);
        if ($db->num_rows() <= "0") {

                if ($tld != "") {
                $ret = "<BR><BR><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">
                                We currently do not register <B> . " . $tld . "</B> domains</font>";
                } else {
                $ret = "<BR><BR><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">
                                There was an problem with the domain you searched for. Please enter a valid domain, and try again.</font>";
                }

        } else {

                $i=0;
                while ($db->next_record()) {
                        $server         = $db->f("domain_type_url");
                        $respon        = $db->f("domain_type_response");
                        $db_domain  = $dmn;
                        $db_domain .= $period;
                        $db_domain .= $db->f("domain_type_extension");

                        $data = get_whois_status($server,$db_domain);
                        if(strstr($data, $respon) != "") {

                        $ret .= "<table width=\"450\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\" bgcolor=\"#F4F4F4\">
                                  <tr><td width=\"25\"><input type=\"checkbox\" name=\"D_Y[$i]\" value=\"Y\" checked>
                                  <input type=\"hidden\" name=\"D_C[$i]\" value=\"$db_domain\"></td>
                                  <td width=\"200\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#990033\"><b>
                                   $db_domain
                                  </td><td width=\"275\">
                                  </b><font face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#990033\" size=\"2\">
                                  <b> DOMAIN AVAILABLE! </b>
                                  </font></font>
                                </td></tr></table>
                                  ";
                                  $i++;
                                  $submit ="Y";
                                  } else {

                        $ret .= "<table width=\"500\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\">
                                  <tr><td width=\"25\"></td>
                                  <td width=\"200\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#999999\"><b>
                                   $db_domain
                                  </td><td width=\"275\">
                                  </b><font face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#999999\" size=\"2\"><a href=\"?page=whois_info&domain=" . "$db_domain" . "&session_id=" . $session_id . "\">Domain reserved</a>
                                  </font></font>
                                </td></tr></table>
                                  ";

                                }
                }
        }
        if ($submit=="Y") {
        $ret.= "<table width=\"450\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\" bgcolor=\"#F4F4F4\">
                    <tr><td width=\"85%\"> <font face=\"Arial, Helvetica, sans-serif\" size=\"2\" color=\"#990033\">
                Click <B>continue</B> to add the selected domains to your cart...
                </font></td><td width=\"15%\">
                <input type=\"image\" border=\"0\" name=\"submit22\" value=\"submit\" src=\"images/button_continue.gif\" width=\"80\" height=\"23\">
              </td></tr></table>";
        }
return $ret;
}








// DETERMINE THE CHOSEN DOMAIN TYPE
function determine_domain_tld($domain) {
$ret =" ";

$split = split("\.", $domain);

$count = count ($split);
// $result == 3

if ($count == 2) {
	$tld = $split[1];
} elseif ($count == 3) {
	$tld = $split[1] . "." . $split[2];
} elseif ($count == 4) {
	$tld = $split[2] . "." . $split[3];
} elseif ($count == 5) {
	$tld = $split[3] . "." . $split[4];
}
	
return $tld;
}

// DETERMINE THE CHOSEN DOMAIN NAME
function determine_domain_name($domain,$tld) {
        $arr = (ereg_replace($tld,"",$domain));
        $arr = (ereg_replace("\.","",$arr));
        return $arr;
}

// REQUEST WHOIS INFO FROM THE CORRECT WHOIS SERVER
function get_whois_status($server,$query) {
        $data = " ";
        $fp = fsockopen($server, 43);
        if($fp) {
                fputs($fp, $query."\r\n");
                while(!feof($fp)) {
                        $data .= fread($fp, 1000);
                }
                fclose($fp);
        } else { $data="Unable to contact the WHOIS database"; }
        return $data;
}





// DETERMINE THE VALIDITY OF THE DOMAIN & REMOVE ILLEGAL CHARACTERS.
function check_domain_validity($domain) {
$domain = ereg_replace("\.","",$domain);
$domain = ereg_replace(" ","",$domain);
$domain = ereg_replace("http://","",$domain);
$domain = ereg_replace("https://","",$domain);
$domain = ereg_replace("ftp://","",$domain);
$domain = ereg_replace("http://www.","",$domain);
$domain = ereg_replace("https://www.","",$domain);
$domain = ereg_replace("http://","",$domain);
$domain = ereg_replace("\~","",$domain);
$domain = ereg_replace("!","",$domain);
$domain = ereg_replace("@","",$domain);
$domain = ereg_replace("#","",$domain);
$domain = ereg_replace("\\\$","",$domain);
$domain = ereg_replace("%","",$domain);
$domain = ereg_replace("\/","",$domain);
$domain = ereg_replace(",","",$domain);
$domain = ereg_replace("`","",$domain);
$domain = ereg_replace("\^","",$domain);
$domain = ereg_replace("&","",$domain);
$domain = ereg_replace("\*","",$domain);
$domain = ereg_replace("\(","",$domain);
$domain = ereg_replace(")","",$domain);
$domain = ereg_replace("_","-",$domain);
$domain = ereg_replace("\+","",$domain);
$domain = ereg_replace("\=","",$domain);
$domain = ereg_replace("<","",$domain);
$domain = ereg_replace(">","",$domain);
$domain = ereg_replace("\?","",$domain);
$domain = ereg_replace("\|","",$domain);
$domain = ereg_replace("\}","",$domain);
$domain = ereg_replace("\{","",$domain);
$domain = ereg_replace("\[","",$domain);
$domain = ereg_replace("\]","",$domain);
$domain = ereg_replace("\:","",$domain);
$domain = ereg_replace("\;","",$domain);
$domain = ereg_replace("'","",$domain);
$domain = ereg_replace("\"","",$domain);
$domain = ereg_replace("\\\\","",$domain);
return $domain;

}






// SHOW A LIST OF CHECK BOXES
function show_input_checkbox($hidden_v,$q,$text_1,$text_2) {
        $db = new ps_DB;
        $db->query($q);
        $count = $db->num_rows();
        $ret="";

                for ($i=0; $i < $count; ++$i) {
                $db->next_record();
                $t1 = $db->f("$text_1");
                $t2 = $db->f("$text_2");
                $hv = $db->f("$hidden_v");
                $ret .= "<table width=\"75%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
                         <tr><td width=\"5%\">
                         <input type=\"checkbox\" name=\"check[$i]\" value=\"Y\">
                         <input type=\"hidden\"   name=\"hidden[$i]\" value=\"$hv\">
                           </td><td width=\"50%\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\">
                         $t1
                         </font></td><td width =\"45\">
                       <font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"(<font color=\"#666666\">
                          $t2
                         </font></font></td></tr><table>";
                        }
return $ret;
}


// SHOW A TEXT FIELD
function show_input_text($name,$length,$default,$db_default) {
        if (($default == "NONE")  && ($db_default == "NONE")) {
        $ret =  "<INPUT TYPE=\"text\" NAME=\"$name\" LENGTH=\"$length\" >";
        }

        if (($default <> "NONE") && ($db_default == "NONE")) {
        $ret =  "<INPUT TYPE=\"text\"
                NAME=\"$name\" LENGTH=\"$length\" VALUE=\"$default\" >";
        }

        if (($default == "NONE") && ($db_default <> "NONE")) {
        $ret =  "<INPUT TYPE=\"text\"
                NAME=\"$name\" MAXLENGTH=\"$length\" VALUE=\"$default\" >";
        }

return $ret;
}



//SHOW A STANDARD DROP-DOWN MENU
function show_menu_standard($name,$values,$titles,$default) {
        $ret="<SELECT NAME=\"$name\">";
        $num_values = count($values);
                for($i=0; $i < $num_values; ++$i) {
                $ret.="<OPTION VALUE=\"$values[$i]\"";
                        if($values[$i] == $default) {
                        $ret.=" selected";
                        }
                $ret.=">$titles[$i]</OPTION>";
                }
        $ret.="</SELECT>";
return $ret;
}


// SHOW A DATABASE WITH ALL THE AVAILABLE DOMAIN TYPES
function show_domain_types() {
        $db = new ps_DB;
        $q  = "SELECT * FROM domain_type WHERE domain_type_status='Y'";
        $db->query($q);
        echo "<SELECT NAME=\"domain_type_id\">";
                while ($db->next_record()) {

                $id = $db->f("domain_type_id");
                $name = $db->f("domain_type_extension");

                echo "<OPTION VALUE=\"$id\"";
                echo ">.";
                echo $name;
                echo "</OPTION>";
                }
        echo  "</SELECT>";
}





//ADD ACCOUNT
function add_account($account_password,$account_email,$account_name,$account_company,$account_address,$account_city,$account_state,$account_zip,$account_country,$account_fax,$account_phone,$account_membership_id,$account_status, $account_pmt_type,$account_acct_no,$account_acct_exp,$account_check_no,$account_check_rt) {
    if ($account_status=="1") {
        $today=date("Y-m-d");
       } else {
           $today="";
       }
       
    $db = new ps_DB;
    $q = "SELECT account_email FROM account WHERE account_email='$account_email'";
		  $db->query($q);
		  
		  
    
    $email=$db->f("account_email");
    if ($email <> "$account_email") {
    $db = new ps_DB;
    $q = "INSERT INTO account SET
            account_password='$account_password',
            account_email='$account_email',
                        account_name='$account_name',
                        account_company='$account_company',
                        account_address='$account_address',
                        account_city='$account_city',
                        account_state='$account_state',
                        account_zip='$account_zip',
                        account_country='$account_country',
                        account_phone='$account_phone',
                        account_fax='$account_fax'";
                 $db->query($q);
		  
		  
       return "Account was added to the database!";
} else {
    return "An account with the email address $account_email already exsists in the database!  ";
    }
}



//UPDATE ACCOUNT
function update_account($account_password,$account_email,$account_name,$account_company,$account_address,$account_city,$account_state,$account_zip,$account_country,$account_fax,$account_phone,$account_membership_id,$account_status, $account_pmt_type,$account_acct_no,$account_acct_exp,$account_check_no,$account_check_rt,$account_id) {
    $db = new ps_DB;
    $q = "UPDATE account SET
            account_password='$account_password',
            account_email='$account_email',
            account_name='$account_name',
            account_company='$account_company',
            account_address='$account_address',
            account_city='$account_city',
            account_state='$account_state',
            account_zip='$account_zip',
            account_country='$account_country',
            account_phone='$account_phone',
            account_fax='$account_fax' 
            WHERE account_id='$account_id'";
		  $db->query($q);
		  
		  
}



//MEMBER AREA ACCOUNT UPDATE
function member_update_account($path,$account_password,$account_email,$account_name,$account_company,$account_address,$account_city,$account_state,$account_zip,$account_country,$account_fax,$account_phone,$account_membership_id,$account_status, $account_pmt_type,$account_acct_no,$account_acct_exp,$account_check_no,$account_check_rt,$account_id) {
    $db = new ps_DB;
    $q = "UPDATE account SET
            account_password='$account_password',
            account_email='$account_email',
                        account_name='$account_name',
                        account_company='$account_company',
                        account_address='$account_address',
                        account_city='$account_city',
                        account_state='$account_state',
                        account_zip='$account_zip',
                        account_country='$account_country',
                           account_phone='$account_phone',
                        account_fax='$account_fax' 
            WHERE account_email='$account_email'";
		$db->query($q);
       return "Thank you, your account has been updated!";
}








//DELETE ACCOUNT
function delete_account($account_id) {
    $db = new ps_DB;
    $q = "DELETE FROM account WHERE account_id='$account_id'";
    $db->query($q);
    return "Deleted Account No. $account_id";
}




//ADD MEMBERSHIP
function add_membership($membership_setup,$membership_url,$membership_name,$membership_desc,$membership_price,$membership_recurring,$membership_frequency,$membership_approval,$membership_periods,$membership_active) {
    $db = new ps_DB;
    $q = "INSERT INTO membership SET
                        membership_name='$membership_name',
                        membership_desc='$membership_desc',
                        membership_price='$membership_price',
                        membership_recurring='$membership_recurring',
                        membership_frequency='$membership_frequency',
                        membership_approval='$membership_approval',
                        membership_url='$membership_url',
                        membership_active='$membership_active',
                        membership_setup='$membership_setup',
                        membership_periods='$membership_periods'";
					$db->query($q);
      
}

//UPDATE MEMBERSHIP
function update_membership($membership_setup,$membership_url,$membership_id,$membership_name,$membership_desc,$membership_price,$membership_recurring,$membership_frequency,$membership_approval,$membership_periods,$membership_active) {
    $db = new ps_DB;
    $q = "UPDATE membership SET
                        membership_name='$membership_name',
                        membership_desc='$membership_desc',
                        membership_price='$membership_price',
                        membership_recurring='$membership_recurring',
                        membership_frequency='$membership_frequency',
                        membership_approval='$membership_approval',
                        membership_periods='$membership_periods',
                        membership_url='$membership_url',
                        membership_setup='$membership_setup',
                        membership_active='$membership_active'
                        WHERE membership_id='$membership_id'";
				$db->query($q);
	 return "Account was updated!";
}


//DELETE MEMBERSHIP
function membership_delete($membership_id) {
    $db = new ps_DB;
    $q = "DELETE FROM membership WHERE membership_id='$membership_id'";
    $db->query($q);
}

// ADD USER ACCOUNT
function add_user_account($account_password,$account_email,$account_name,$account_company,$account_address,$account_city,$account_state,$account_zip,$account_country,$account_fax,$account_phone,$account_membership_id,$account_status, $account_pmt_type,$account_acct_no,$account_acct_exp,$account_check_no,$account_check_rt) {
    $db = new ps_DB;
    $q = "SELECT * FROM membership WHERE membership_id='$account_membership_id'";
	$db->query($q);	   
	
	 $account_status=$db->f("membership_approval");

    if ($account_status=="2") {
        $today=date("Y-m-d");
           $account_status="1";
       } else {
           $today="";
           $account_status="0";
       }

           $db = new ps_DB;
    $q = "SELECT account_email FROM account WHERE account_email='$account_email'";
    $db->query($q);
    
    $email=$db->f("account_email");
    if ($email <> "$account_email") {
    $db = new ps_DB;
    $q = "INSERT INTO account SET
                        account_password='$account_password',
                        account_email='$account_email',
                        account_name='$account_name',
                        account_company='$account_company',
                        account_address='$account_address',
                        account_city='$account_city',
                        account_state='$account_state',
                        account_zip='$account_zip',
                        account_country='$account_country',
                        account_phone='$account_phone',
                        account_fax='$account_fax'";
				$db->query($q);
       return "signup_success";

} else {
    return "signup_email_exsists";
    }
}




// UPDATE ADMINISTRATION SETUP OPTIONS
function update_administration($f) {
	
	$supe = $f[setup_superuser];
	$date = setup("aff_pay_2c");
	
	if ($date == "") {
		$date = date("Y-m-d");
	}
	
	if ($f[superuser] == setup("superuser")) {	
		if ($f[setup_superuser] == "") { $supe = setup("superuser"); }
	    $db = new ps_DB;
        $q = "UPDATE setup SET
        setup_login				=	'$f[setup_login]', 
        setup_password			=	'$f[setup_password]', 
        setup_superuser			=	'$supe', 
        setup_path				=	'$f[setup_path]', 
        setup_url				=	'$f[setup_url]', 
        setup_email				=	'$f[setup_email]', 
        setup_company			=	'$f[setup_company]', 
        setup_email_signup		=	'$f[setup_email_signup]', 
        setup_email_admin		=	'$f[setup_email_admin]', 
        setup_currency			=	'$f[setup_currency]', 
        setup_tax				=	'$f[setup_tax]', 
        setup_tax_rate			=	'$f[setup_tax_rate]', 
        setup_max_results		=	'$f[setup_max_results]', 
        setup_domain_suggest	=	'$f[setup_domain_suggest]', 
        setup_affiliate			=	'$f[setup_affiliate]',
        setup_aff_type			=	'$f[setup_aff_type]',
        setup_aff_pay_1			=	'$f[setup_aff_pay_1]',
        setup_aff_pay_1a		=	'$f[setup_aff_pay_1a]',
        setup_aff_pay_2			=	'$f[setup_aff_pay_2]',
        setup_aff_pay_2a		=	'$f[setup_aff_pay_2a]',  
        setup_aff_pay_2b		=	'$f[setup_aff_pay_2b]', 
        setup_aff_pay_2c		=	'$date',
        setup_gateway			=	'$f[gateway]', 
        setup_curl				=	'$f[setup_path2]', 
        setup_gw_userid		=	'$f[userid]', 
        setup_gw_password	=	'$f[password]',
        setup_gw_1				=	'$f[gw_1]', 
        setup_gw_2				=	'$f[gw_2]', 
        setup_header             =   '$f[header]', 
        setup_footer              =   '$f[footer]',
        setup_faq                  =   '$f[faq]',
        setup_company_info   =   '$f[company_info]',
        setup_contact_info      =   '$f[contact_info]',
        setup_acceptable_use =   '$f[acceptable_use]',
        setup_privacy_policy   =   '$f[privacy_policy]', 
        setup_topmenu_bg	=	'$f[topmenu_bg]', 
        setup_topmenu_font	=	'$f[topmenu_font]', 
        setup_leftmenu_bg	=	'$f[leftmenu_bg]',
        setup_leftmenu_font	=	'$f[leftmenu_font]',
        setup_leftmenu_search=	'$f[leftmenu_search]', 
        setup_leftmenu_cart	=	'$f[leftmenu_cart]', 
        setup_leftmenu_width	=	'$f[leftmenu_width]', 
        setup_registrar			=	'$f[registrar]', 
        setup_email_1			=	'$f[1]', 
        setup_email_2			=	'$f[2]', 
        setup_email_3			=	'$f[3]', 
        setup_email_4			=	'$f[4]', 
        setup_email_5			=	'$f[5]', 
        setup_email_6			=	'$f[6]', 
        setup_email_7			=	'$f[7]', 
        setup_email_8			=	'$f[8]', 
        setup_email_9			=	'$f[9]', 
        setup_email_10		=	'$f[10]', 
        setup_email_11		=	'$f[11]', 
        setup_email_12		=	'$f[12]', 
        setup_email_13		=	'$f[13]', 
        setup_email_14		=	'$f[14]', 
        setup_email_15		=	'$f[15]', 
        setup_email_16		=	'$f[16]', 
        setup_email_17		=	'$f[17]', 
        setup_email_18		=	'$f[18]', 
        setup_email_19		=	'$f[19]', 
        setup_email_20		=	'$f[20]', 
        setup_email_21		=	'$f[21]', 
        setup_email_22		=	'$f[22]', 
        setup_email_23		=	'$f[23]' 
        WHERE setup_id='1'";
        $db->query($q);
        return "The administration options were updated!";
    } else {
	    return "You did not provide the correct superuser password. The administration options have not been updated.";
}
}




// ADD & ACTIVATE A NEW AFFILIATE RECORD
function add_affiliate($f) {
       $today=date("Y-m-d");
       $account_id  = get_account_id($f[session_id]);
       $db = new ps_DB;
       $q = "INSERT INTO affiliate SET 
			affiliate_account_id=	'$account_id',
			affiliate_date		=	'$today',
			affiliate_type		=	'$f[affiliate_type]',
			affiliate_name		=	'$f[name]',
			affiliate_address	=	'$f[address]',
			affiliate_city		=	'$f[city]',
			affiliate_state		=	'$f[state]',
			affiliate_zip		=	'$f[zip]',
			affiliate_country	=	'$f[country]'";
       $db->query($q);
       
       // MAIL THE ADMINISTRATOR
       if(setup("email_3") == Y) {
	   send_mail("3",$account_id,"","","");    
       }
       
       // MAIL THE AFFILIATE
       if(setup("email_8") == Y) {
	   send_mail("8",$account_id,"","","");    
       }
 }



// GET AN AFFILIATE ID BY THE CUSTOMERS ACCOUNT ID
function get_affiliate_id($acct) {
       $db = new ps_DB;
       $q = "SELECT affiliate_id FROM affiliate WHERE affiliate_account_id='$acct'";
       $db->query($q);
       $db->next_record();
       $ret = $db->f("affiliate_id");
return $ret;
}


// SHOW ALL REFERRALS FOR AN AFFILIATE
function show_affiliate_referrals($affiliate_id) {
       $db = new ps_DB;
       $q = "SELECT login_id FROM login WHERE login_affiliate_id='$affiliate_id'";
       $db->query($q);
       $ret = $db->num_rows();
return $ret;	
}


// SHOW ALL ORDERS FOR AN AFFILIATE
function show_affiliate_orders($affiliate_id) {
       $db = new ps_DB;
       $q = "SELECT order_id FROM orders WHERE order_affiliate_id='$affiliate_id'";
       $db->query($q);
       $ret = $db->num_rows();
return $ret;	
}



// CHECK IF THE SELECTED ACCOUNT IS SET UP AS AN AFFILIATE
function affiliate_exist($account_id) {
       $db = new ps_DB;
       $q = "SELECT affiliate_id FROM affiliate WHERE affiliate_account_id='$account_id'";
       $db->query($q);
       if ($db->num_rows() < 1) { $ret = "N"; } else { $ret = "Y"; }
return $ret;
}



// UPDATE EMAIL TEMPLATE
function update_email_template($f) {
       $db = new ps_DB;
       $q = "UPDATE email SET 
       		email_name	     =	'$f[name]', 
       		email_template   =	'$f[template]' 
       		WHERE email_id  =	'$f[id]'";
       $db->query($q);
}



// PARSE A MAIL RECORD AND SEND IT OUT...
function send_mail($email_id,$account_id,$domain_id,$order_id,$affiliate_id) {

	// IF ACCOUNT ID EXSISTS, GET THE ACCOUNT INFO..
	if($account_id !="") {
		
       $db = new ps_DB;
       $q = "SELECT * FROM account WHERE account_id='$account_id'";
       $db->query($q);
       $db->next_record();
       
       $name = $db->f("account_name");
       $address = $db->f("account_address");
       $city = $db->f("account_city");
       $state = $db->f("account_state");
       $zip = $db->f("account_zip");
       $email = $db->f("account_email");
       $pw = $db->f("account_password");
       
       
	}	
	
	// IF DOMAIN ID EXSISTS, GET THE ACCOUNT INFO..
	if($domain_id !="") {
       $db = new ps_DB;
       $q = "SELECT * FROM domains WHERE domain_id='$domain_id'";
       $db->query($q);
       $db->next_record();
       
       $domain = $db->f("domain_name");
       $plan = $db->f("domain_host_id");
       
              	$db = new ps_DB;
      		$q = "SELECT membership_name FROM membership WHERE membership_id='$plan'";
       		$db->query($q);
       		$db->next_record();
       $planname = $db->f("membership_name");
       }	
	
	// IF ORDER ID IS SET
       if($order_id !="") {
       $db = new ps_DB;
       $q = "SELECT order_id, order_amount FROM orders WHERE order_id='$order_id'";
       $db->query($q);
       $db->next_record();
       
       $id = $db->f("order_id");
       $amount = $db->f("order_amount");
       
	  	  	$new_domains ="";
	  	  	$tran_domains ="";
	  	  	
	  	  	$db = new ps_DB;
       		$q = "SELECT * FROM domains WHERE domain_order_id='$order_id'";
       		$db->query($q);

       		$new_count = 0;
	       	$tran_count = 0;
	       		
	       		while ($db->next_record()) {
	       		$years = $db->f("domain_years");

	       		
	       		if ($years == 0) {
		       		$tran_domains .=	"Domain Name:		" .$db->f("domain_name") . "\n";
		       		$tran_domains .=	"Hosting Plan:		ID " .$db->f("domain_host_id") . ".\n";
		       		$tran_domains .=	"---------------------------------------------- \n";
		       		$tran_count++;
	       		} elseif ($years >= 1) {
		       		$new_domains .=		"Domain Name:		" .$db->f("domain_name") . "\n";
		       		$new_domains .=		"Hosting Plan:		ID " .$db->f("domain_host_id") . ".\n";
		       		$new_domains .=		"Registration Term:	" .$years . " years.\n";
		       		$new_domains .=		"---------------------------------------------- \n";
		       		$new_count++;
	       		}
	       		
	       		if ($new_domains != "") {
		       		$n_domains	=	"NEW DOMAIN(S) ORDERED: \n";   
		       		$n_domains	.=	"---------------------------------------------- \n";	
		       		$n_domains	.=	$new_domains;
	       		} 
	       		
	       		if ($tran_domains != "") {
		       		$t_domains	=	"HOSTING ONLY DOMAINS ORDERED: \n";   
		       		$t_domains	.=	"---------------------------------------------- \n";	
		       		$t_domains	.=	$tran_domains;
	       		} 
       		}
       
	}
	
	// IF ACCOUNT ID EXSISTS, GET THE ACCOUNT INFO..
	if($affiliate_id !="") {
       $db = new ps_DB;
       $q = "SELECT * FROM affiliate WHERE affiliate_id='$affiliate_id'";
       $db->query($q);
       $db->next_record();
       
       $account_id = $db->f("affiliate_account_id");
       $name = $db->f("affiliate_name");
       $address = $db->f("affiliate_address");
       $city = $db->f("affiliate_city");
       $state = $db->f("affiliate_state");
       $zip = $db->f("affiliate_zip");
 	     $db = new ps_DB;
       	$q = "SELECT account_email FROM account WHERE account_id='$account_id'";
       	$db->query($q);
       	$db->next_record();

	$email = $db->f("account_email");
	}
	
	
	
	  // GET THE EMAIL TEMPLATE INFO FROM THE DATABASE
       $db = new ps_DB;
       $q = "SELECT * FROM email WHERE email_id='$email_id'";
       $db->query($q);
       $db->next_record();
	
	  $subject = $db->f("email_name");
	  $template = $db->f("email_template");
	  
	  $company_email = setup("email");
	  
	  // DETERMINE WHO TO SEND THIS TO
	  if ($email_id <= "7")  { 
		  $to_email = $company_email;
	  } elseif (($email_id >= 8) && ($email_id <= 10)) {
		  $to_email = $email;
	  } elseif (($email_id >= 11) && ($email_id <= 21)) {
		  $to_email = $email;
  	  } elseif (($email_id >= 22) && ($email_id <= 23)) {
	  	  $to_email = setup("registrar");
	  	  
	  	  if (($email_id == 22) && ($new_count == 0)) {
		  	  return true;
	  	  }
	  	  
	  	  
  	  }	     

	  	  
	  	  
 	  
  	  $today = date("Y-m-d");
  	  
  	  // START THE FILTERING PROCCESS (COMMON SHORTCUTS):
$pat="
";
	 $template = ereg_replace("$pat", "\n", $template);  
  	 $template = ereg_replace("<BR>", "\n", $template); 	
  	   
  	 $template = ereg_replace("<company>", setup("company"), $template);
  	 $template = ereg_replace("<email>", setup("email"), $template);
  	 $template = ereg_replace("<currency>", setup("currency"), $template);
  	 $template = ereg_replace("<date>", $today, $template);
  	 $template = ereg_replace("<url>", setup("url"), $template);
  	 $template = ereg_replace("<NAME>", $name, $template);
  	 $template = ereg_replace("<ADDRESS>", $address, $template);
  	 $template = ereg_replace("<CITY>", $city, $template);
  	 $template = ereg_replace("<STATE>", $state, $template);
  	 $template = ereg_replace("<ZIP>", $zip, $template);
  	 $template = ereg_replace("<EMAIL>", $email, $template);
  	 $template = ereg_replace("<PW>", $pw, $template);
  	 $template = ereg_replace("<DOMAIN>", $domain, $template);
  	 $template = ereg_replace("<PLANNAME>", $planname, $template);
  	 $template = ereg_replace("<ID>", $id, $template);
  	 $template = ereg_replace("<AMOUNT>", $amount, $template);
  	 $template = ereg_replace("<NEWDOMAINS>", $n_domains, $template);
  	 $template = ereg_replace("<TRANSFERDOMAINS>", $t_domains, $template);

    	 $subject = ereg_replace("<company>", setup("company"), $subject);
  	 $subject = ereg_replace("<email>", setup("email"), $subject);
  	 $subject = ereg_replace("<currency>", setup("currency"), $subject);
  	 $subject = ereg_replace("<date>", $today, $subject);
  	 $subject = ereg_replace("<url>", setup("url"), $subject);
  	 $subject = ereg_replace("<NAME>", $name, $subject);
  	 $subject = ereg_replace("<ADDRESS>", $address, $subject);
  	 $subject = ereg_replace("<CITY>", $city, $subject);
  	 $subject = ereg_replace("<STATE>", $state, $subject);
  	 $subject = ereg_replace("<ZIP>", $zip, $subject);
  	 $subject = ereg_replace("<EMAIL>", $email, $subject);
  	 $subject = ereg_replace("<PW>", $pw, $subject);
  	 $subject = ereg_replace("<DOMAIN>", $domain, $subject);
  	 $subject = ereg_replace("<PLANNAME>", $planname, $subject);
  	 $subject = ereg_replace("<ID>", $id, $subject);
  	 $subject = ereg_replace("<AMOUNT>", $amount, $subject);
  	 $message = $template;
  	 
			
              // CREATE THE HEADERS
              $headers = "From: " . setup("company") . " <" . $company_email . ">\n";
              $headers .= "X-Sender: <" . $company_email . ">\n";
              $headers .= "Return-Path: <" . $company_email . ">\n";

               mail($to_email, $subject, $message, $headers);		
	
	
}







// PARSE THE REGISTRAR NOTICE OF A DOMAIN RENEWAL
function send_renewal_email($account_id,$domain_id,$domain_name,$years) {

	// IF ACCOUNT ID EXSISTS, GET THE ACCOUNT INFO..
       $db = new ps_DB;
       $q = "SELECT * FROM account WHERE account_id='$account_id'";
       $db->query($q);
       $db->next_record();
       
       $name = $db->f("account_name");
       $address = $db->f("account_address");
       $city = $db->f("account_city");
       $state = $db->f("account_state");
       $zip = $db->f("account_zip");
       $email = $db->f("account_email");
	  
       $tran_domain =	"--------------------------------------- \n";
       $tran_domain .=	"Domain Name:	" . $domain_name . "\n";
	  $tran_domain .="Renewal Length:	" . $years . " year(s)\n";
	  $tran_domain .=	"--------------------------------------- \n";
	
	  // GET THE EMAIL TEMPLATE INFO FROM THE DATABASE
       $db = new ps_DB;
       $q = "SELECT * FROM email WHERE email_id='23'";
       $db->query($q);
       $db->next_record();
	
	  $subject = $db->f("email_name");
	  $template = $db->f("email_template");
  	  $to_email = setup("registrar");
  	  $today = date("Y-m-d");
  	  
  	  // START THE FILTERING PROCCESS (COMMON SHORTCUTS):
$pat="
";
	 $template = ereg_replace("$pat", "\n", $template);  
  	 $template = ereg_replace("<BR>", "\n", $template); 	
  	 $template = ereg_replace("<company>", setup("company"), $template);
  	 $template = ereg_replace("<email>", setup("email"), $template);
  	 $template = ereg_replace("<currency>", setup("currency"), $template);
  	 $template = ereg_replace("<date>", $today, $template);
  	 $template = ereg_replace("<url>", setup("url"), $template);
  	 $template = ereg_replace("<NAME>", $name, $template);
  	 $template = ereg_replace("<ADDRESS>", $address, $template);
  	 $template = ereg_replace("<CITY>", $city, $template);
  	 $template = ereg_replace("<STATE>", $state, $template);
  	 $template = ereg_replace("<ZIP>", $zip, $template);
  	 $template = ereg_replace("<EMAIL>", $email, $template);
  	 $template = ereg_replace("<DOMAINDETAILS>", $tran_domain, $template);

    	 $subject = ereg_replace("<company>", setup("company"), $subject);
  	 $subject = ereg_replace("<email>", setup("email"), $subject);
  	 $subject = ereg_replace("<currency>", setup("currency"), $subject);
  	 $subject = ereg_replace("<date>", $today, $subject);
  	 $subject = ereg_replace("<url>", setup("url"), $subject);
  	 $subject = ereg_replace("<NAME>", $name, $subject);
  	 $subject = ereg_replace("<ADDRESS>", $address, $subject);
  	 $subject = ereg_replace("<CITY>", $city, $subject);
  	 $subject = ereg_replace("<STATE>", $state, $subject);
  	 $subject = ereg_replace("<ZIP>", $zip, $subject);
  	 $subject = ereg_replace("<EMAIL>", $email, $subject);
  	 $message = $template;
  	 
		
              // CREATE THE HEADERS
              $headers = "From: " . setup("company") . " <" . $company_email . ">\n";
              $headers .= "X-Sender: <" . $company_email . ">\n";
              $headers .= "Return-Path: <" . $company_email . ">\n";

               mail($to_email, $subject, $message, $headers);		
	
	
}




//SEND USER LOST PASSWORD  EMAIL
function email_pw($account_password,$account_email,$account_name) {

	$company_email = setup("email");
    $company_name  = setup("company");
    
              $subject =  $company_name;
              $subject .= " Password Reminder";
              $message = "Hello $account_name, your $company_name login and password are below.\n";
              $message .="Account Login:    $account_email \n";
              $message .="Account Password: $account_password \n\n";
              $message .="Thank you,\n Customer Service \n $company_name";
              $headers = "From: $company_name <$company_email>\n";
              $headers .= "X-Sender: <$company_email>\n";
              $headers .= "Return-Path: <$company_email>\n";
              mail($account_email, $subject, $message, $headers);
}







// SEND A SUPPORT TICKET TO THE ADMIN
function add_ticket($account_id,$domain_id,$type,$title,$mess) {
    $date=date("Y-m-d");
    $company_email = setup("email");
    $company_name  = setup("company");

        $db = new ps_DB;
        $q = "SELECT account_name,account_email FROM account WHERE account_id='$account_id'";
        $db->query($q);
        $db->next_record();

        $name=$db->f("account_name");
        $cust_email=$db->f("account_email");

        $dbf = new ps_DB;
        $q = "SELECT domain_name FROM domains WHERE domain_id='$domain_id'";
        $dbf->query($q);
        $dbf->next_record();
		
		if ($type=="0") $type="Other";
		if ($type=="1") $type="Billing Question";
		if ($type=="2") $type="Sales Inquiry";
		if ($type=="3") $type="Tech Support";
		

              $subject = "Customer Support Request: ";
              $subject .= $type;
              $message = $name;
              $message .= " has just submitted a customer service request via DreamHost.\n Below is a copy of the customers message.\n\n";
		    $message .= "Date Submitted:   " . $date . "\n";
              $message .= "Customers Domain: " . $dbf->f("domain_name") . " \n";
		    $message .= "Inquiry:          " . $title . "\n";
		    $message .= "Full Message:     " . $mess . "\n\n";
              $message .="This message was generated by DreamHost.\n";
              $headers = "From: $name <$cust_email>\n";
              $headers .= "X-Sender: <$cust_email>\n";
              $headers .= "Return-Path: <$cust_email>\n";

               mail($company_email, $subject, $message, $headers);
}




// SHOW LAST FEW DIGITS OF CREDIT CARD NUMBER...
function show_hidden_cc_1($cc_num) {
	$cc_num=RC4($cc_num,"de");
        $cc        =        ereg_replace("^............","",$cc_num) ;
        $ret =        "XXXX-XXXX-XXXX-" . $cc;
return $ret;
}

// SHOW LAST FEW DIGITS OF CREDIT CARD NUMBER...
function show_hidden_cc($cc_num) {
        $cc        =        ereg_replace("^............","",$cc_num) ;
        $ret =        "XXXX-XXXX-XXXX-" . $cc;
return $ret;
}


//SHOW LIST OF AVAILABLE CREDIT CARDS ON FILE
function show_cc_list($account_id,$billing_id) {
        $db = new ps_DB;
        $q = "SELECT * FROM billing WHERE billing_account_id='$account_id'";
        $db->query($q);
                $ret = "<select name=\"billing_id\">";
        while($db->next_record()) {
                        if ($billing_id==$db->f("billing_id")) { $sel=" selected"; } else { $sel=""; }
                			$cc_num=$db->f("billing_cc_num");
                			$cc_num=RC4($cc_num,"de");                        
                        $cc_type = identify($cc_num);
                      $ret .= "<option value=\"" . $db->f("billing_id") . "\"" . $sel . ">" . $cc_type . " No: " . show_hidden_cc($cc_num) . "  Expires: " . $db->f("billing_cc_exp") . "</option>";
                }
        $ret.="</select>";
        return $ret;
}


// UPDATE CREDIT CARD INFORMATION
function update_billing_account($account_id,$billing_id,$cc_num,$cc_exp) {
	$cc_num=RC4($cc_num,"en");	
        $db = new ps_DB;
        $q = "UPDATE billing SET
                        billing_cc_num='$cc_num',
                        billing_cc_exp='$cc_exp'
                        WHERE billing_account_id='$account_id'
                        AND billing_id='$billing_id'";
        $db->query($q);
        
        //EMAIL THE CUSTOMER
        if(setup("email_14") == Y) {
	    		send_mail("14",$account_id,"","","");    
        }
}


// UPDATE CREDIT CARD INFORMATION
function update_billing_account_2($account_id,$billing_id,$cc_num,$cc_exp) {
	$cc_num=RC4($cc_num,"en");
	    $db = new ps_DB;
        $q = "UPDATE billing SET
                        billing_cc_num='$cc_num',
                        billing_cc_exp='$cc_exp'
                        WHERE billing_id='$billing_id'";
        $db->query($q);
        
        //EMAIL THE CUSTOMER
        if(setup("email_14") == Y) {
	    		send_mail("14",$account_id,"","","");    
        }
}


//CHECK TO SEE IF A CREDIT CARD EXSISTS ON FILE OR NOT...
function check_cc_list($account_id) {
        $db = new ps_DB;
        $q = "SELECT billing_id FROM billing WHERE billing_account_id='$account_id'";
        $db->query($q);
return $db->num_rows();
}


//CHECK TO SEE IF CUSTOMER HAS ANY REGISTERED DOMAINS OR NOT...
function check_domain_list($account_id) {
            $db = new ps_DB;
        $q = "SELECT domain_id FROM domains WHERE domain_account_id='$account_id'";
        $db->query($q);
return $db->num_rows();
}


// ADD AN ATTRIBUTE
function add_attribute($f) {
        $db = new ps_DB;
        $q = "INSERT INTO attributes SET
        		attribute_name	=	'$f[name]', 
        		attribute_desc		=	'$f[desc]', 
        		attribute_active	= 	'$f[active]', 
        		attribute_value	=	'$f[value]',
        		attribute_type		=	'$f[type]'";
        $db->query($q);
 }
	

// UPDATE AN ATTRIBUTE
function update_attribute($f) {
        $db = new ps_DB;
        $q = "UPDATE attributes SET
        		attribute_name	=	'$f[name]', 
        		attribute_desc		=	'$f[desc]', 
        		attribute_type		=	'$f[type]',
        		attribute_active	= 	'$f[active]', 
        		attribute_value	=	'$f[value]', 
        		attribute_1		=	'$f[_1]',
        		attribute_2		=	'$f[_2]',
        		attribute_3		=	'$f[_3]',
        		attribute_4		=	'$f[_4]',
        		attribute_5		=	'$f[_5]',
        		attribute_6		=	'$f[_6]',
        		attribute_7		=	'$f[_7]',
        		attribute_8	     =	'$f[_8]',
        		attribute_9		=	'$f[_9]',
        		attribute_10    	=	'$f[_10]' 
        		WHERE attribute_id=  '$f[id]'";
        $db->query($q);
  }
  
//SHOW LIST OF REGISTERED DOMAINS & THEIR PLANS
function show_attribute_list($count,$id) {
	   $db = new ps_DB;
        $q = "SELECT * FROM attributes WHERE attribute_id='$id'";
        $db->query($q);
	   $db->next_record();
	   
	   $style = "style=\"border: 1 solid #666666; font-family: Arial, Helvetica, sans-serif; font-size: 11px; font-weight: bold\"";
	   echo "<select name=\"value[" . $count . "]\" " . $style . ">";
	   $i=1;
	   while($i <=10) {
		   $val = "attribute_" . $i; 
		   $ret = $db->f($val);
		   echo $val;
		   
		   if ($ret != "") {
			   echo "<option value=\"" . $ret . "\"";
			   if ($ret == $db->f("attribute_value")) {
				   echo " selected";
			   }
			   echo ">" . $ret . "</option>";
		   }
		   $i++;
	   }
	   echo "</select>";
   }
        



//SHOW LIST OF REGISTERED DOMAINS & THEIR PLANS
function show_domain_list($account_id,$domain_id) {
            $db = new ps_DB;
        $q = "SELECT domain_name,domain_id FROM domains WHERE domain_account_id='$account_id' ORDER BY domain_name";
        $db->query($q);
                $ret = "<select name=\"domain_id\">";
        while($db->next_record()) {
                        if ($domain_id==$db->f("domain_id")) { $sel=" selected"; } else { $sel=""; }
                        $ret .= "<option value=\"" . $db->f("domain_id") . "\"" . $sel . ">" . $db->f("domain_name") . "</option>";
                }
        $ret.="</select>";
        return $ret;
}


//SHOW LIST OF REGISTERED DOMAINS THAT ARE EXPIRING SOON
function show_domain_exp_list($account_id,$domain_id) {
        $i=0;
            $db = new ps_DB;
        $q = "SELECT domain_name,domain_id FROM domains WHERE domain_account_id='$account_id' ORDER BY domain_name";
        $db->query($q);
                $ret = "<select name=\"domain_id\">";
        while($db->next_record()) {
                        $days = check_domain_status($db->f("domain_id"));
                        if ($days<=30) {
                                if ($domain_id==$db->f("domain_id")) { $sel=" selected"; } else { $sel=""; }
                                $ret .= "<option value=\"" . $db->f("domain_id") . "\"" . $sel . ">Expires in " . $days . " days -> " . $db->f("domain_name") . "</option>";
                                $i++;
                        }
                }
                if ($i==0) {
                        $ret .= "<option value=\"\" selected>You have no domains due to expire within 30 days.</option>";
                }
        $ret.="</select>";
        return $ret;
}


// COUNT TOTAL DOMAINS EXPIRING IN A SET AMOUNT OF DAYS
function count_domains_expiring($i) {
		$count=0;
		$db = new ps_DB;
        $q = "SELECT domain_id FROM domains";
        $db->query($q);
        while ($db->next_record()) {
			$domain_id=$db->f("domain_id");
			$num = check_domain_status_2($i,$domain_id);
			$count = $count + $num;
			$ret = $count;
		}
	return $ret;
}



//DETERMINE IF A CERTIAN DOMAIN IS EXPIRING SOON....
function check_domain_status_2($i,$domain_id) {
            $db = new ps_DB;
        	$q = "SELECT domain_start_date,domain_years FROM domains WHERE domain_id='$domain_id'";
        	$db->query($q);
        	$db->next_record();

                $start_date     = $db->f("domain_start_date");
                $years          = $db->f("domain_years");
                $days = $years * 31449600;

                if($years=="0") { $ret="1111111"; } else {

                $today=date("Y-m-d");
                $today_date=strtotime($today);
                $db_date=strtotime($start_date)+$days;
                $date_diff =(($db_date-$today_date)/86400);
                $diff = $date_diff;
				}
				
	if ($diff <= $i) {
		$ret="1";
	} else {
		$ret="0";
	}	
return $ret;
}

//DETERMINE IF A CERTIAN DOMAIN IS EXPIRING SOON....
function check_domain_status($domain_id) {
            $db = new ps_DB;
        $q = "SELECT domain_start_date,domain_years FROM domains WHERE domain_id='$domain_id'";
        $db->query($q);
        $db->next_record();

                $start_date        = $db->f("domain_start_date");
                $years                = $db->f("domain_years");
                $days = $years * 31449600;

                if($years=="0") { $ret="1111111"; } else {

                $today=date("Y-m-d");
        $today_date=strtotime($today);
        $db_date=strtotime($start_date)+$days;
        $date_diff =(($db_date-$today_date)/86400);
                $ret = $date_diff;
                }


        return $ret;
}






//SHOW LIST CUSTOMERS ORDERS
function show_order_list($account_id,$order_id) {
        $db = new ps_DB;
        $q  = "SELECT order_id,order_amount,order_status FROM orders WHERE order_account_id='$account_id' ORDER BY order_date";
        $db->query($q);
                $ret = "<select name=\"order_id\">";
        while($db->next_record()) {
                        if ($order_id==$db->f("order_id")) { $sel=" selected"; } 
                        if ($db->f("order_status")==1) { $status="complete "; } 
                        if ($db->f("order_status")==0) { $status="pending "; } 
                        if ($db->f("order_status")==2) { $status="voided "; } 
                        if ($db->f("order_status")==3) { $status="cancelled "; } 
                        if ($db->f("order_status")==4) { $status="declined "; } 
                        $ret .= "<option value=\"" . $db->f("order_id") . "\"" . $sel . ">Order No. " . $db->f("order_id") . " -> " . $status . " -> " . setup("currency") . $db->f("order_amount") . "</option>";
                }
        $ret.="</select>";
        return $ret;
}


// SHOW A LIST OF BILLED CHARGES...
function show_billing_list($account_id,$billed_id) {
        $db = new ps_DB;
        $q  = "SELECT * FROM billed WHERE billed_account_id='$account_id' ORDER BY billed_date";
        $db->query($q);
                $ret = "<select name=\"billed_id\">";
        while($db->next_record()) {
	        $amount = $total = (number_format ($db->f("billed_amount"), 2, ".", ""));
	        if ($amount <= 0) {
		        $data = " Credit ";
	        } else {
		        $data = setup("currency") . "" . $amount;
	        }
                        if ($billed_id==$db->f("billed_id")) { $sel=" selected"; } else { $sel=""; }
                        $ret .= "<option value=\"" . $db->f("billed_id") . "\"" . $sel . ">Date. " . $db->f("billed_date") . " -> " . $data . "</option>";
                }
                if ($db->num_rows=="0") {
                        $ret .= "<option value=\"\">You have no charges billed to your account.</option>";
                }
        $ret.="</select>";
        return $ret;
}


// SHOW HOSTING OPTIONS & PRICES MENU FOR CUSTOMERS WISHING TO UPDATE THEIR HOSTING PLAN
function show_hosting_menu_2($domain_id,$membership_id) {
        $db = new ps_DB;
        $q = "SELECT membership_setup,membership_name,membership_price,membership_id FROM membership WHERE membership_active='Y' ORDER BY 'membership_name'";
        $db->query($q);
        echo "<SELECT NAME=\"membership_id\">";
        while ($db->next_record()) {
                $dbn  = $db->f("membership_name");
                $dbp  = $db->f("membership_price");
                $dbi  = $db->f("membership_id");
                $dbs  = $db->f("membership_setup");
                $title = $dbn;
                $title.= " (";
                $title.= setup("currency");
                $title.= $dbp;
                $title.= ")";
                if ($dbs>0) {
                    $title .= " + (" . setup("currency") . "" . $dbs . " setup)";
                }

                echo "<OPTION VALUE=\"$dbi\"";
                        if ($membership_id == $dbi) echo " selected";
                echo ">$title</OPTION>
                                ";
                        }

echo "</SELECT>";
return $ret;
}


// SHOW A LIST OF PAYOUT OPTIONS:
function show_payout_options() {
        $db = new ps_DB;
        $q  = "SELECT setup_aff_type FROM setup WHERE setup_id='1'";
        $db->query($q);
        $db->next_record();
        echo "<select name=\"affiliate_type\">";
      
	        if (($db->f("setup_aff_type") == 0) || ($db->f("setup_aff_type") == 1)) {
	        echo "<option value=\"1\">Credit Payout to my Account</option>";	    }
	        if (($db->f("setup_aff_type") == 0) || ($db->f("setup_aff_type") == 2)) {
	        echo "<option value=\"2\">Send me my Payout via Mail</option>";	    }
	        	        
        echo "</select>";
       // return $ret;
}


// SHOW A LIST OF ACCOUNTS...
function show_account_list($account_id) {
        $db = new ps_DB;
        $q  = "SELECT account_id,account_name FROM account";
        $db->query($q);
                $ret = "<select name=\"account_id\">";
        while($db->next_record()) {
                        if ($account_id==$db->f("account_id")) { $sel=" selected"; } else { $sel=""; }
                        $ret .= "<option value=\"" . $db->f("account_id") . "\"" . $sel . ">Acct No. " . $db->f("account_id") . " ->  " . $db->f("account_name") . "</option>";
                }
                if ($db->num_rows=="0") {
                        $ret .= "<option value=\"\">There are no active accounts.</option>";
                }
        $ret.="</select>";
        return $ret;
}


// SHOW A LIST OF ACCOUNTS...
function show_account_list_2($account_id) {
        $db = new ps_DB;
        $q  = "SELECT account_id,account_name FROM account ORDER BY account_name ASC";
        $db->query($q);
                $ret = "<select name=\"account_id\">";
        while($db->next_record()) {
                        $ret .= "<option value=\"" . $db->f("account_id") . "\"" . $sel . ">Acct No. " . $db->f("account_id") . " ->  " . $db->f("account_name") . "</option>";
                }
                if ($db->num_rows=="0") {
                        $ret .= "<option value=\"\">There are no active accounts.</option>";
                }
        $ret.="</select>";
        return $ret;
}


// SHOW A LIST OF AFFILIATE  ACCOUNTS...
function show_affiliate_list($account_id) {
        $db = new ps_DB;
        $q  = "SELECT affiliate_id,affiliate_name FROM affiliate ORDER BY affiliate_name ASC";
        $db->query($q);
                $ret = "<select name=\"affiliate_id\">";
        while($db->next_record()) {
                        $ret .= "<option value=\"" . $db->f("affiliate_id") . "\"" . $sel . ">Affiliate No. " . $db->f("affiliate_id") . " ->  " . $db->f("affiliate_name") . "</option>";
                }
                if ($db->num_rows=="0") {
                        $ret .= "<option value=\"\">There are no affiliate accounts.</option>";
                }
        $ret.="</select>";
        return $ret;
}


//UPDATE DOMAIN HOSTING PLAN.....
function update_domain($account_id,$domain_id,$membership_id) {
            $db = new ps_DB;
        $q = "UPDATE domains SET
                         domain_host_id='$membership_id'
                         WHERE domain_id='$domain_id'
                         AND domain_account_id='$account_id'";
        $db->query($q);
		
        // EMAIL ADMIN
        if (setup("email_5") == Y) {
	    		send_mail("5",$account_id,$domain_id,"","");    
        }
        
        // EMAIL CUSTOMER
        if (setup("email_21") == Y) {
	    		send_mail("21",$account_id,$domain_id,"","");    
        }
        
}


//UPDATE DOMAIN HOSTING PLAN.....
function update_domain_2($account_id,$domain_id,$membership_id) {
            $db = new ps_DB;
        $q = "UPDATE domains SET
                         domain_host_id='$membership_id'
                         WHERE domain_id='$domain_id'";
        $db->query($q);

        // EMAIL ADMIN
        if (setup("email_5") == Y) {
	    		send_mail("5",$account_id,$domain_id,"","");    
        }
        
        // EMAIL CUSTOMER
        if (setup("email_21") == Y) {
	    		send_mail("21",$account_id,$domain_id,"","");    
        }

        
}





// GET DOMAIN RENEWAL COST
function get_renewal_cost($domain,$new_term) {
        $tld = determine_domain_tld($domain);

        $db = new ps_DB;
        $q = "SELECT domain_type_p" . $new_term . " FROM domain_type WHERE domain_type_extension = '$tld'";
        $db->query($q);
        $db->next_record();
                $d        = "domain_type_p";
                $d   .= $new_term;
                $dbf  = $db->f("$d");
                                $ret = $dbf * $new_term;
return $ret;
}





// CREATE A BILLING RECORD FOR THIS TRANSACTION
function create_billed_record($account_id,$domain_id,$amount) {
           $today=date("Y-m-d");
       $db = new ps_DB;
       $q = "INSERT INTO billed SET
                           billed_account_id='$account_id',
                        billed_membership_id ='$domain_id',
                        billed_date='$today',
                        billed_amount='$amount',
                        billed_type='2'";
       $db->query($q);
}

// CREATE A BILLING RECORD FOR THIS ORDER
function create_billed_record_2($account_id,$order_id,$amount) {
           $today=date("Y-m-d");
       $db = new ps_DB;
       $q = "INSERT INTO billed SET
                           billed_account_id='$account_id',
                        billed_order_id ='$order_id',
                        billed_date='$today',
                        billed_amount='$amount',
                        billed_type='3'";
       $db->query($q);
}



// CREATE A BILLING RECORD FOR THIS RECURRING CHARGE
function create_billed_record_3($account_id,$domain_id,$amount) {
           $today=date("Y-m-d");
       $db = new ps_DB;
       $q = "INSERT INTO billed SET
                           billed_account_id='$account_id',
                        billed_membership_id ='$domain_id',
                        billed_date='$today',
                        billed_amount='$amount',
                        billed_type='1'";
       $db->query($q);
}



// RENEW THE DOMAIN RECORD
function renew_domain_record($account_id,$domain_id,$new_term) {
       $db = new ps_DB;
       $q = "SELECT domain_years,domain_name FROM domains WHERE domain_id='$domain_id'";
       $db->query($q);
       $db->next_record();
       $domain_name = $db->f("domain_name");
       $years = $db->f("domain_years") + $new_term;

       $db = new ps_DB;
       $q = "UPDATE domains SET
                        domain_years ='$years'
                        WHERE domain_id='$domain_id'
                        AND domain_account_id='$account_id'";
       $db->query($q);
       
       // EMAIL ADMIN
       if(setup("email_4") == Y) {
	   	send_mail("4",$account_id,$domain_id,"","");    
       }
       
	  // EMAIL CUSTOMER
       if(setup("email_20") == Y) {
	   	send_mail("20",$account_id,$domain_id,"","");    
       }
   
	  // EMAIL REGISTRAR
       if(setup("email_23") == Y) {
	   	send_renewal_email($account_id,$domain_id,$domain_name,$new_term);
       }
       
             
       
}


// PRINT DOMAIN NAME FROM ID ONLY...
function print_domain_name($id) {
           $db = new ps_DB;
       $q = "SELECT domain_name FROM domains WHERE domain_id='$id'";
       $db->query($q);
           $db->next_record();
           $ret=$db->f("domain_name");
return $ret;
}



// PRINT MONTHLY COST FROM DOMAIN ID ...
function print_hosting_cost($id) {
           $db = new ps_DB;
           $q = "SELECT domain_host_id FROM domains WHERE domain_id='$id'";
           $db->query($q);
           $db->next_record();
           $membership_id=$db->f("domain_host_id");
		   
           $db = new ps_DB;
           $q = "SELECT membership_price FROM membership WHERE membership_id='$membership_id'";
           $db->query($q);
           $db->next_record();
           $ret=$db->f("membership_price");		   
		   
return $ret;
}

// GET A SETTING FROM THE SETUP TABLE OF THE DATABASE...
function setup($field) {
        $db = new ps_DB;
        $q = "SELECT setup_$field FROM setup WHERE setup_id='1'";
        $db->query($q);
        $db->next_record();
        
        $ret = $db->f("setup_$field");
  	return $ret;

}


// SHOW THE APPROPRIATE NOTE FORM
function note_showform($note_type,$id) {
	
	if ($note_type==1) $field="account";
	if ($note_type==2) $field="order";
	if ($note_type==3) $field="domain";
	
	$db=new ps_DB;
	$q="SELECT note_message FROM notes WHERE note_" . $field . "_id='$id'";
	$db->query($q);
	$db->next_record();
	
	if ($db->f("note_message") == "") {
		// SHOW THE 'ADD NOTES' FORM
		echo "<form name=\"notes\" method=\"post\">
  		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
    		<tr> <td> <font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><b>
    		Notes:
    		 </b></font></td></tr><tr><td> 
          <textarea name=\"note_message\" cols=\"65\" style=\"border: 1 solid #666666\" rows=\"5\">" . $db->f("note_message") . "</textarea>
      	</td></tr><tr><td> 
        	<input type=\"hidden\" name=\"note_type\" value=\"" . $note_type . "\">
        	<input type=\"hidden\" name=\"action\" value=\"note_control\">
        	<input type=\"hidden\" name=\"note_id\" value=\"" . $id . "\">
        	<input type=\"hidden\" name=\"status\" value=\"new\">
        	<input type=\"submit\" name=\"Submit\" value=\"Add Notes >\">
    		</tr>
  		</table>
		</form>";
		
	} else {
		// SHOW THE 'UPDATE NOTES' FORM
		echo "<form name=\"notes\" method=\"post\">
  		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
    		<tr> <td> <font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><b>
    		Notes:
    		 </b></font></td></tr><tr><td> 
          <textarea name=\"note_message\" cols=\"65\" style=\"border: 1 solid #666666\" rows=\"5\">" . $db->f("note_message") . "</textarea>
      	</td></tr><tr><td> 
        	<input type=\"hidden\" name=\"note_type\" value=\"" . $note_type . "\">
        	<input type=\"hidden\" name=\"action\" value=\"note_control\">
        	<input type=\"hidden\" name=\"note_id\" value=\"" . $id . "\">
        	<input type=\"hidden\" name=\"status\" value=\"update\">
        	<input type=\"submit\" name=\"Submit\" value=\"Update Notes >\">
    		</tr>
  		</table>
		</form>";
	}
}




// FUNCTION TO ADD AND UPDATE NOTES
function note_control($f) {
	
	if ($f[note_type]==1) $field="account";
	if ($f[note_type]==2) $field="order";
	if ($f[note_type]==3) $field="domain";
	
	if ($f[status] == "new") {
		// RECORD DOES NOT EXSIST, WE WILL CREATE A NEW ONE...
		$dbf=new ps_DB;
		$q="INSERT INTO notes SET 
			  note_" . $field . "_id = '$f[note_id]', 
			  note_message		 = '$f[note_message]'";
	     $dbf->query($q);
	     
     } elseif($f[status] == "update") {
		$dbf=new ps_DB;
		$q="UPDATE notes SET 
			   note_message		 = '$f[note_message]' 
			   WHERE note_" . $field . "_id = '$f[note_id]'";
	     $dbf->query($q);
     }
}





// ECHO CREDIT CARD CLASS
 class EchoPHP {
                var $order_type;
                var $transaction_type;
                var $merchant_echo_id;
                var $merchant_pin;
                var $isp_echo_id;
                var $isp_pin;
                var $authorization;
                var $billing_ip_address;
                var $billing_prefix;
                var $billing_name;
                var $billing_address1;
                var $billing_address2;
                var $billing_city;
                var $billing_state;
                var $billing_zip;
                var $billing_country;
                var $billing_phone;
                var $billing_fax;
                var $billing_email;
                var $cc_number;
                var $ccexp_month;
                var $ccexp_year;
                var $counter;
                var $debug;
                var $ec_account;
                var $ec_address1;
                var $ec_address2;
                var $ec_bank_name;
                var $ec_business_acct;
                var $ec_city;
                var $ec_email;
                var $ec_first_name;
                var $ec_id_country;
                var $ec_id_exp_mm;
                var $ec_id_exp_dd;
                var $ec_id_exp_yy;
                var $ec_id_number;
                var $ec_id_state;
                var $ec_id_type;
                var $ec_last_name;
                var $ec_license_number;
                var $ec_license_state;
                var $ec_merchant_ref;
                var $ec_nbds_code;
                var $ec_other_name;
                var $ec_payee;
                var $ec_rt;
                var $ec_serial_number;
                var $ec_state;
                var $ec_zip;
                var $grand_total;
                var $merchant_email;
                var $merchant_trace_nbr;
                var $original_amount;
                var $original_trandate_mm;
                var $original_trandate_dd;
                var $original_trandate_yyyy;
                var $original_reference;
                var $order_number;
                var $shipping_flag;
                var $status;
                var $shipping_prefix;
                var $shipping_name;
                var $shipping_address1;
                var $shipping_address2;
                var $shipping_city;
                var $shipping_state;
                var $shipping_zip;
                var $shipping_comments;
                var $shipping_country;
                var $shipping_phone;
                var $shipping_fax;
                var $shipper;
                var $shipper_tracking_nbr;
                var $track1;
                var $track2;
                var $EchoResponse;
                var $echotype1;
                var $echotype2;
                var $echotype3;
                var $openecho;
                var $athorization;
                var $reference;
                var $order_number;
                var $EchoSuccess;

                function Submit() {
                        if ($this->EchoServer) {
                                $URL = $this->EchoServer;
                        } else {
                                $URL = "https://wwws.echo-inc.com/scripts/INR200.EXE";
                        }

                        $data = $this->getURLData();
                        
                        $curl = setup("curl");

                        exec("$curl -d \"$data\" $URL", $return_message_array);

                        $this->EchoResponse = ""; // clear this
                        for ($i = 0; $i < count($return_message_array); $i++) {
                                $this->EchoResponse = $this->EchoResponse.$return_message_array[$i];
                        }

                        $startpos = strpos($this->EchoResponse, "<ECHOTYPE1>") + 11;
                        $endpos = strpos($this->EchoResponse, "</ECHOTYPE1>");
                        $this->echotype1 = substr($this->EchoResponse, $startpos, $endpos - $startpos);

                        $startpos = strpos($this->EchoResponse, "<ECHOTYPE2>") + 11;
                        $endpos = strpos($this->EchoResponse, "</ECHOTYPE2>");
                        $this->echotype2 = substr($this->EchoResponse, $startpos, $endpos - $startpos);

                        $startpos = strpos($this->EchoResponse, "<ECHOTYPE3>") + 11;
                        $endpos = strpos($this->EchoResponse, "</ECHOTYPE3>");
                        $this->echotype3 = substr($this->EchoResponse, $startpos, $endpos - $startpos);

                        if (strpos($this->EchoResponse, "<OPENECHO>")) {
                                $startpos = strpos($this->EchoResponse, "<OPENECHO>") + 10;
                                $endpos = strpos($this->EchoResponse, "</OPENECHO>");
                                $this->openecho = substr($this->EchoResponse, $startpos, $endpos - $startpos);
                        }

                        // Get all the metadata.
                        $this->GetAuthFromEcho();
                        $this->GetOrderNumberFromEcho();
                        $this->GetReferenceFromEcho();
            $this->status = $this->GetEchoProp($this->echotype3, "status");

            $this->EchoSuccess = !($this->status == "D");
                        //$this->EchoSuccess = !((stristr($this->echotype1, "DECLINED")) || (stristr($this->echotype1, "INVALID")));

                        // make sure we assign an integer to EchoSuccess
                        ($this->EchoSuccess == 1) ? ($this->EchoSuccess = 1) : ($this->EchoSuccess = 0);

                        return $this->EchoSuccess;



                } // function submit


                function getURLData() {
                        $s .=
                        "order_type="                                 . $this->order_type .
                        "&transaction_type="                 . $this->transaction_type .
                        "&merchant_echo_id="                 . $this->merchant_echo_id .
                        "&merchant_pin="                         . $this->merchant_pin .
                        "&isp_echo_id="                         . $this->isp_echo_id .
                        "&isp_pin="                                 . $this->isp_pin .
                        "&authorization="                         . $this->authorization .
                        "&billing_ip_address="                 . $this->billing_ip_address .
                        "&billing_prefix="                        . $this->billing_prefix .
                        "&billing_name="                        . $this->billing_name .
                        "&billing_address1="                . $this->billing_address1 .
                        "&billing_address2="                . $this->billing_address2 .
                        "&billing_city="                        . $this->billing_city .
                        "&billing_state="                        . $this->billing_state .
                        "&billing_zip="                                . $this->billing_zip .
                        "&billing_country="                        . $this->billing_country .
                        "&billing_phone="                        . $this->billing_phone .
                        "&billing_fax="                                . $this->billing_fax .
                        "&billing_email="                        . $this->billing_email .
                        "&cc_number="                                . $this->cc_number .
                        "&ccexp_month="                                . $this->ccexp_month .
                        "&ccexp_year="                                . $this->ccexp_year .
                        "&counter="                                        . $this->counter .
                        "&debug="                                        . $this->debug .
                        "&transaction_type="                . $this->transaction_type;

                        if (($this->transaction_type == "DD") || ($this->transaction_type == "DC")) {
                                $s .=
                                "&ec_account="                                . $this->ec_account .
                                "&ec_address1="                                . $this->ec_address1 .
                                "&ec_address2="                                . $this->ec_address2 .
                                "&ec_bank_name="                        . $this->ec_bank_name .
                                "&ec_business_acct="                . $this->ec_business_acct .
                                "&ec_city="                                        . $this->ec_city .
                                "&ec_email="                                . $this->ec_email .
                                "&ec_first_name="                        . $this->ec_first_name .
                                "&ec_id_country="                        . $this->ec_id_country .
                                "&ec_id_exp_mm="                        . $this->ec_id_exp_mm .
                                "&ec_id_exp_dd="                        . $this->ec_id_exp_dd .
                                "&ec_id_exp_yy="                        . $this->ec_id_exp_yy .
                                "&ec_id_number="                        . $this->ec_id_number .
                                "&ec_id_state="                                . $this->ec_id_state .
                                "&ec_id_type="                                . $this->ec_id_type .
                                "&ec_last_name="                        . $this->ec_last_name .
                                "&ec_license_number="                . $this->ec_license_number .
                                "&ec_license_state="                . $this->ec_license_state .
                                "&ec_merchant_ref="                        . $this->ec_merchant_ref .
                                "&ec_nbds_code="                        . $this->ec_nbds_code .
                                "&ec_other_name="                        . $this->ec_other_name .
                                "&ec_payee="                                . $this->ec_payee .
                                "&ec_rt="                                        . $this->ec_rt .
                                "&ec_serial_number="                . $this->ec_serial_number .
                                "&ec_state="                                . $this->ec_state .
                                "&ec_zip="                                        . $this->ec_zip;
                        }

                        $s .=
                        "&grand_total="                                . $this->grand_total .
                        "&merchant_email="                        . $this->merchant_email .
                        "&merchant_trace_nbr="                . $this->merchant_trace_nbr .
                        "&original_amount="                        . $this->original_amount .
                        "&original_trandate_mm="        . $this->original_trandate_mm .
                        "&original_trandate_dd="        . $this->original_trandate_dd .
                        "&original_trandate_yyyy="        . $this->original_trandate_yyyy .
                        "&original_reference="                . $this->original_reference .
                        "&order_number="                        . $this->order_number .
                        "&shipping_flag="                        . $this->shipping_flag .
                        "&shipping_prefix="                        . $this->shipping_prefix .
                        "&shipping_name="                        . $this->shipping_name .
                        "&shipping_address1="                . $this->shipping_address1 .
                        "&shipping_address2="                . $this->shipping_address2 .
                        "&shipping_city="                        . $this->shipping_city .
                        "&shipping_state="                        . $this->shipping_state .
                        "&shipping_zip="                        . $this->shipping_zip .
                        "&shipping_comments="                . $this->shipping_comments .
                        "&shipping_country="                . $this->shipping_country .
                        "&shipping_phone="                        . $this->shipping_phone .
                        "&shipping_fax="                        . $this->shipping_fax .
                        "&shipper="                                        . $this->shipper .
                        "&shipper_tracking_nbr="        . $this->shipper_tracking_nbr .
                        "&track1="                                        . $this->track1 .
                        "&track2="                                        . $this->track2;

                        return $s;

                } // end getURLData



                /**********************************************
                All the get/set methods for the echo properties
                ***********************************************/

                function set_order_type($value) {
                        $this->order_type = $value;
                }

                function get_order_type() {
                        return $this->order_type;
                }

                function set_transaction_type($value) {
                        $this->transaction_type = $value;
                }

                function get_transaction_type() {
                        return $this->transaction_type;
                }

                function set_merchant_echo_id($value) {
                        $this->merchant_echo_id = urlencode($value);
                }

                function get_merchant_echo_id() {
                        return $this->merchant_echo_id;
                }

                function set_merchant_pin($value) {
                        $this->merchant_pin = urlencode($value);
                }

                function get_merchant_pin() {
                        return $this->merchant_pin;
                }


                function set_isp_echo_id($value) {
                        $this->isp_echo_id = urlencode($value);
                }

                function get_isp_echo_id() {
                        return $this->isp_echo_id;
                }

                function set_isp_pin($value) {
                        $this->isp_pin = urlencode($value);
                }

                function get_isp_pin() {
                        return $this->isp_pin;
                }

                function set_authorization($value) {
                        $this->authorization = $value;
                }

                function get_authorization() {
                        return $this->authorization;
                }

                function set_billing_ip_address($value) {
                        $this->billing_ip_address = $value;
                }

                function get_billing_ip_address() {
                        return $this->billing_ip_address;
                }

                function set_billing_prefix($value) {
                        $this->billing_prefix = urlencode($value);
                }

                function get_billing_prefix() {
                        return $this->billing_prefix;
                }

                function set_billing_name($value) {
                        $this->billing_name = urlencode($value);
                }

                function get_billing_name() {
                        return $this->billing_name;
                }

                function set_billing_address1($value) {
                        $this->billing_address1 = urlencode($value);
                }

                function get_billing_address1() {
                        return $this->billing_address1;
                }

                function set_billing_address2($value) {
                        $this->billing_address2 = urlencode($value);
                }

                function get_billing_address2() {
                        return $this->billing_address2;
                }

                function set_billing_city($value) {
                        $this->billing_city = urlencode($value);
                }

                function get_billing_city() {
                        return $this->billing_city;
                }

                function set_billing_state($value) {
                        $this->billing_state = urlencode($value);
                }

                function get_billing_state() {
                        return $this->billing_state;
                }

                function set_billing_zip($value) {
                        $this->billing_zip = urlencode($value);
                }

                function get_billing_zip() {
                        return $this->billing_zip;
                }

                function set_billing_country($value) {
                        $this->billing_country = urlencode($value);
                }

                function get_billing_country() {
                        return $this->billing_country;
                }

                function set_billing_phone($value) {
                        $this->billing_phone = urlencode($value);
                }

                function get_billing_phone() {
                        return $this->billing_phone;
                }

                function set_billing_fax($value) {
                        $this->billing_fax = urlencode($value);
                }

                function get_billing_fax() {
                        return $this->billing_fax;
                }

                function set_billing_email($value) {
                        $this->billing_email = urlencode($value);
                }

                function get_billing_email() {
                        return $this->billing_email;
                }

                function set_cc_number($value) {
                        $this->cc_number = urlencode($value);
                }

                function get_cc_number() {
                        return $this->cc_number;
                }

                function set_ccexp_month($value) {
                        $this->ccexp_month = $value;
                }

                function get_ccexp_month() {
                        return $this->ccexp_month;
                }

                function set_ccexp_year($value) {
                        $this->ccexp_year = $value;
                }

                function get_ccexp_year() {
                        return $this->ccexp_year;
                }

                function set_counter($value) {
                        $this->counter = $value;
                }

                function get_counter() {
                        return $this->counter;
                }

                function set_debug($value) {
                        $this->debug = $value;
                }

                function get_debug() {
                        return $this->debug;
                }

                function set_ec_account($value) {
                        $this->ec_account = urlencode($value);
                }

                function get_ec_account() {
                        return $this->ec_account;
                }

                function set_ec_address1($value) {
                        $this->ec_address1 = urlencode($value);
                }

                function get_ec_address1() {
                        return $this->ec_address1;
                }

                function set_ec_address2($value) {
                        $this->ec_address2 = urlencode($value);
                }

                function get_ec_address2() {
                        return $this->ec_address2;
                }

                function set_ec_bank_name($value) {
                        $this->ec_bank_name = urlencode($value);
                }

                function get_ec_bank_name() {
                        return $this->ec_bank_name;
                }

                function set_ec_business_acct($value) {
                        $this->ec_business_acct = urlencode($value);
                }

                function get_ec_business_acct() {
                        return $this->ec_business_acct;
                }

                function set_ec_city($value) {
                        $this->ec_city = $value;
                }

                function get_ec_city() {
                        return $this->ec_city;
                }

                function set_ec_email($value) {
                        $this->ec_email = urlencode($value);
                }

                function get_ec_email() {
                        return $this->ec_email;
                }

                function set_ec_first_name($value) {
                        $this->ec_first_name = urlencode($value);
                }

                function get_ec_first_name() {
                        return $this->ec_first_name;
                }

                function set_ec_id_country($value) {
                        $this->ec_id_country = urlencode($value);
                }

                function get_ec_id_country() {
                        return $this->ec_id_country;
                }

                function set_ec_id_exp_mm($value) {
                        $this->ec_id_exp_mm = $value;
                }

                function get_ec_id_exp_mm() {
                        return $this->ec_id_exp_mm;
                }

                function set_ec_id_exp_dd($value) {
                        $this->ec_id_exp_dd = $value;
                }

                function get_ec_id_exp_dd() {
                        return $this->ec_id_exp_dd;
                }

                function set_ec_id_exp_yy($value) {
                        $this->ec_id_exp_yy = $value;
                }

                function get_ec_id_exp_yy() {
                        return $this->ec_id_exp_yy;
                }

                function set_ec_id_number($value) {
                        $this->ec_id_number = urlencode($value);
                }

                function get_ec_id_number() {
                        return $this->ec_id_number;
                }

                function set_ec_id_state($value) {
                        $this->ec_id_state = urlencode($value);
                }

                function get_ec_id_state() {
                        return $this->ec_id_state;
                }

                function set_ec_id_type($value) {
                        $this->ec_id_type = $value;
                }

                function get_ec_id_type() {
                        return $this->ec_id_type;
                }

                function set_ec_last_name($value) {
                        $this->ec_last_name = urlencode($value);
                }

                function get_ec_last_name() {
                        return $this->ec_last_name;
                }

                function set_ec_license_number($value) {
                        $this->ec_license_number = $value;
                }

                function get_ec_license_number() {
                        return $this->ec_license_number;
                }

                function set_ec_license_state($value) {
                        $this->ec_license_state = $value;
                }

                function get_ec_license_state() {
                        return $this->ec_license_state;
                }

                function set_ec_merchant_ref($value) {
                        $this->ec_merchant_ref = $value;
                }

                function get_ec_merchant_ref() {
                        return $this->ec_merchant_ref;
                }

                function set_ec_nbds_code($value) {
                        $this->ec_nbds_code = $value;
                }

                function get_ec_nbds_code() {
                        return $this->ec_nbds_code;
                }

                function set_ec_other_name($value) {
                        $this->ec_other_name = urlencode($value);
                }

                function get_ec_other_name() {
                        return $this->ec_other_name;
                }

                function set_ec_payee($value) {
                        $this->ec_payee = urlencode($value);
                }

                function get_ec_payee() {
                        return $this->ec_payee;
                }

                function set_ec_rt($value) {
                        $this->ec_rt = urlencode($value);
                }

                function get_ec_rt() {
                        return $this->ec_rt;
                }

                function set_ec_serial_number($value) {
                        $this->ec_serial_number = urlencode($value);
                }

                function get_ec_serial_number() {
                        return $this->ec_serial_number;
                }

                function set_ec_state($value) {
                        $this->ec_state = urlencode($value);
                }

                function get_ec_state() {
                        return $this->ec_state;
                }

                function set_ec_zip($value) {
                        $this->ec_zip = urlencode($value);
                }

                function get_ec_zip() {
                        return $this->ec_zip;
                }

                function set_grand_total($value) {
                        $this->grand_total = sprintf("%01.2f", $value);
                }

                function get_grand_total() {
                        return $this->grand_total;
                }

                function set_merchant_email($value) {
                        $this->merchant_email = urlencode($value);
                }

                function get_merchant_email() {
                        return $this->merchant_email;
                }

                function set_merchant_trace_nbr($value) {
                        $this->merchant_trace_nbr = $value;
                }

                function get_merchant_trace_nbr() {
                        return $this->merchant_trace_nbr;
                }

                function set_original_amount($value) {
                        $this->original_amount = sprintf("%01.2f", $value);
                }

                function get_original_amount() {
                        return $this->original_amount;
                }

                function set_original_trandate_mm($value) {
                        $this->original_trandate_mm = $value;
                }

                function get_original_trandate_mm() {
                        return $this->original_trandate_mm;
                }

                function set_original_trandate_dd($value) {
                        $this->original_trandate_dd = $value;
                }

                function get_original_trandate_dd() {
                        return $this->original_trandate_dd;
                }

                function set_original_trandate_yyyy($value) {
                        $this->original_trandate_yyyy = $value;
                }

                function get_original_trandate_yyyy() {
                        return $this->original_trandate_yyyy;
                }

                function set_original_reference($value) {
                        $this->original_reference = $value;
                }

                function get_original_reference() {
                        return $this->original_reference;
                }

                function set_order_number($value) {
                        $this->order_number = $value;
                }

                function get_order_number() {
                        return $this->order_number;
                }

                function set_shipping_flag($value) {
                        $this->shipping_flag = $value;
                }

                function get_shipping_flag() {
                        return $this->shipping_flag;
                }

                function set_shipping_prefix($value) {
                        $this->shipping_prefix = urlencode($value);
                }

                function get_shipping_prefix() {
                        return $this->shipping_prefix;
                }

                function set_shipping_name($value) {
                        $this->shipping_name = urlencode($value);
                }

                function get_shipping_name() {
                        return $this->shipping_name;
                }

                function set_shipping_address1($value) {
                        $this->shipping_address1 = urlencode($value);
                }

                function get_shipping_address1() {
                        return $this->shipping_address1;
                }

                function set_shipping_address2($value) {
                        $this->shipping_address2 = urlencode($value);
                }

                function get_shipping_address2() {
                        return $this->shipping_address2;
                }

                function set_shipping_city($value) {
                        $this->shipping_city = urlencode($value);
                }

                function get_shipping_city() {
                        return $this->shipping_city;
                }

                function set_shipping_state($value) {
                        $this->shipping_state = urlencode($value);
                }

                function get_shipping_state() {
                        return $this->shipping_state;
                }

                function set_shipping_zip($value) {
                        $this->shipping_zip = urlencode($value);
                }

                function get_shipping_zip() {
                        return $this->shipping_zip;
                }

                function set_shipping_comments($value) {
                        $this->shipping_comments = urlencode($value);
                }

                function get_shipping_comments() {
                        return $this->shipping_comments;
                }

                function set_shipping_country($value) {
                        $this->shipping_country = urlencode($value);
                }

                function get_shipping_country() {
                        return $this->shipping_country;
                }

                function set_shipping_phone($value) {
                        $this->shipping_phone = urlencode($value);
                }

                function get_shipping_phone() {
                        return $this->shipping_phone;
                }

                function set_shipping_fax($value) {
                        $this->shipping_fax = urlencode($value);
                }

                function get_shipping_fax() {
                        return $this->shipping_fax;
                }

                function set_shipper($value) {
                        $this->shipper = urlencode($value);
                }

                function get_shipper() {
                        return $this->shipper;
                }

                function set_shipper_tracking_nbr($value) {
                        $this->shipper_tracking_nbr = $value;
                }

                function get_shipper_tracking_nbr() {
                        return $this->shipper_tracking_nbr;
                }

                function set_track1($value) {
                        $this->track1 = urlencode($value);
                }

                function get_track1() {
                        return $this->track1;
                }

                function set_track2($value) {
                        $this->track2 = urlencode($value);
                }

                function get_track2() {
                        return $this->track2;
                }


                /************************************************
                                                Helper functions
                ************************************************/

                function getRandomCounter() {
                        mt_srand ((double) microtime() * 1000000);
                        Return mt_rand();
                }

                function get_EchoResponse() {
                        return $this->EchoResponse;
                }

                function get_echotype1() {
                        return $this->echotype1;
                }

                function get_echotype2() {
                        return $this->echotype2;
                }

                function get_echotype3() {
                        return $this->echotype3;
                }

                function get_openecho() {
                        return $this->openecho;
                }

                function set_EchoServer($value) {
                        $this->EchoServer = $value;
                }

                function get_authorization() {
                        return $this->authorization;
                }

                function get_reference() {
                        return $this->reference;
                }

                function get_order_number() {
                        return $this->order_number;
                }

                function get_EchoSuccess() {
                        return $this->EchoSuccess;
                }

        function get_status() {
            return $this->status;
        }

        function GetEchoProp($Haystack, $Prop) {
            // prepend garbage in case the property
            // starts at position 0
            $Haystack = "garbage" . $Haystack;

            if  ($StartPos = strpos($Haystack, "<$Prop>")) {
                $StartPos = strpos($Haystack, "<$Prop>") + strlen("<$Prop>");
                $EndPos = strpos($Haystack, "</$Prop");
                return substr($Haystack, $StartPos, $EndPos - $StartPos);
            } else {
                return "";
            }
        }

                function GetAuthFromEcho() {
                        if ($startpos = strpos($this->echotype3, "<auth_code>")) {
                                $startpos = strpos($this->echotype3, "<auth_code>") + 11;
                                $endpos = strpos($this->echotype3, "</auth_code>");
                                $this->authorization = substr($this->echotype3, $startpos, $endpos - $startpos);
                        }
                }

                function GetOrderNumberFromEcho() {
                        if ($startpos = strpos($this->echotype3, "<order_number>")) {
                                $startpos = strpos($this->echotype3, "<order_number>") + 14;
                                $endpos = strpos($this->echotype3, "</order_number>");
                                $this->order_number = substr($this->echotype3, $startpos, $endpos - $startpos);
                        }
                }

                function GetReferenceFromEcho() {
                        if ($startpos = strpos($this->echotype3, "<echo_reference>")) {
                                $startpos = strpos($this->echotype3, "<echo_reference>") + 16;
                                $endpos = strpos($this->echotype3, "</echo_reference>");
                                $this->reference = substr($this->echotype3, $startpos, $endpos - $startpos);
                        }
                }

        } // end of class






// ENCODE/DECODE THE CREDIT CARD INFO USING RC4
function RC4($data, $case) {

	global $path;
	require($path . "setup.php");
	require($salt);
		
		if ($case == 'de') {
			$data = urldecode($data);
		}
		$key[] = "";
		$box[] = "";
		$temp_swap = "";
		$pwd_length = 0;
		$pwd_length = strlen($pwd);

		for ($i = 0; $i <= 255; $i++) {
			$key[$i] = ord(substr($pwd, ($i % $pwd_length), 1));
			$box[$i] = $i;
		}
		$x = 0;

		for ($i = 0; $i <= 255; $i++) {
			$x = ($x + $box[$i] + $key[$i]) % 256;
			$temp_swap = $box[$i];
			$box[$i] = $box[$x];
			$box[$x] = $temp_swap;
		}
		$temp = "";
		$k = "";
		$cipherby = "";
		$cipher = "";
		$a = 0;
		$j = 0;
		for ($i = 0; $i < strlen($data); $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$temp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $temp;
			$k = $box[(($box[$a] + $box[$j]) % 256)];
			$cipherby = ord(substr($data, $i, 1)) ^ $k;
			$cipher .= chr($cipherby);
		}
		
		if ($case == 'de') {
			$cipher = urldecode(urlencode($cipher));
		} else {
			$cipher = urlencode($cipher);
		}
		return $cipher;
	}
	
	
	
// DETERMINE CREDIT CARD TYPE
function identify($cc_no)     {
         $cc_no = ereg_replace ('[^0-9]+', '', $cc_no);

        // Get card type based on prefix and length of card number
        if (ereg ('^4(.{12}|.{15})$', $cc_no)) {
            return 'Visa';
        } elseif (ereg ('^5[1-5].{14}$', $cc_no)) {
            return 'MasterCard';
        } elseif (ereg ('^3[47].{13}$', $cc_no)) {
            return 'Amex';
        } elseif (ereg ('^3(0[0-5].{11}|[68].{12})$', $cc_no)) {
            return 'Diners';
        } elseif (ereg ('^6011.{12}$', $cc_no)) {
            return 'Discover';
        } elseif (ereg ('^(3.{15}|(2131|1800).{11})$', $cc_no)) {
            return 'JCB';
        } elseif (ereg ('^2(014|149).{11})$', $cc_no)) {
            return 'enRoute'; 
       } else {
 		 return "N";
       }
}




function clean_exp($exp) {
     $exp = ereg_replace (" ", "", $exp);
         $exp = ereg_replace ("-", "", $exp);
         $exp = ereg_replace ("\/", "", $exp);
         $exp = ereg_replace ("\\\\", "", $exp);
         $exp = ereg_replace ("\|", "", $exp);
         $exp = ereg_replace ("\.", "", $exp);
return $exp;
}


function identify_month($exp)             {
        if(ereg('^10', $exp)) { return "10";  }
        if(ereg('^11', $exp)) { return "11";  }
        if(ereg('^12', $exp)) { return "12";  }
        $exp = ereg_replace ("0", "", $exp);
        if(ereg('^1',  $exp))  { return "01"; }
        if(ereg('^2',  $exp))  { return "02"; }
        if(ereg('^3',  $exp))  { return "03"; }
        if(ereg('^4',  $exp))  { return "04"; }
        if(ereg('^5',  $exp))  { return "05"; }
        if(ereg('^6',  $exp))  { return "06"; }
        if(ereg('^7',  $exp))  { return "07"; }
        if(ereg('^8',  $exp))  { return "08"; }
        if(ereg('^9',  $exp))  { return "09"; }
        else {return "No Match";
        }
}


function identify_year($exp) {
        if(ereg('2000$',  $exp))  { return "00";  }
        if(ereg('2001$',  $exp))  { return "01";  }
        if(ereg('2002$',  $exp))  { return "02";  }
        if(ereg('2003$',  $exp))  { return "03"; }
        if(ereg('2004$',  $exp))  { return "04"; }
        if(ereg('2005$',  $exp))  { return "05"; }
        if(ereg('2006$',  $exp))  { return "06"; }
        if(ereg('2007$',  $exp))  { return "07"; }
        if(ereg('2008$',  $exp))  { return "08"; }
        if(ereg('2009$',  $exp))  { return "09"; }
        if(ereg('2010$',  $exp))  { return "10"; }
        if(ereg('2011$',  $exp))  { return "11"; }
        if(ereg('2012$',  $exp))  { return "12"; }
        if(ereg('2013$',  $exp))  { return "13"; }
        if(ereg('2014$',  $exp))  { return "14"; }
        if(ereg('2015$',  $exp))  { return "15"; }
        else { return "No Match"; }
}

function card($account_id,$name,$address,$zip,$cc_num,$cc_exp,$amount) {

	// CHECK IF CREDIT EXSISTS OR NOT
	$credit = credit_exsist($account_id);
	if ($credit > 0) {	
		
		// IF SO, CHECK IF THE CREDIT IS LARGER THAN THE AMOUNT
		if($credit >= $amount) {
			
			// IF SO: -> DONT BILL THE CARD -> UPDATE THE CREDIT RECORD -> RETURN APPROVAL!
			credit_used_1($account_id,$amount);
			
			echo "You have a credit totaling <B>" . setup("currency");
			printf("%.2f",$credit);
			echo " </B> in your account.<BR><BR>";
			echo setup("currency") . "" . $amount . " of your credit has applied to this transaction.<BR>";
			
			$result		=	"4";
			$amount	= 	"0";	
			        

		// OTHERWISE, SEND THE DIFFERENCE TO THE CORRECT PROCCESSOR
		} elseif ($credit < $amount) {
			$charge_amount = $amount - $credit;
			$rt = card_1($account_id,$name,$address,$zip,$cc_num,$cc_exp,$charge_amount);
			
			// IF APPROVED:   -> UPDATE THE CREDIT RECORD -> RETURN RESULTS
			if ($rt=="1")   credit_used_1($account_id,$credit);
			$result		=	$rt;
			$amount	= 	$charge_amount;

			echo "You have a credit totaling <B>" . setup("currency");
			printf("%.2f",$credit);
			echo " </B> in your account.<BR><BR>";
			echo "All of your credit has applied to this transaction.<BR>";
			
		
		}		
			
		// IF NO CREDIT EXSISTS, SEND THE AMOUNT TO THE CORRECT PROCESSOR
		} else {
			$rt = card_1($account_id,$name,$address,$zip,$cc_num,$cc_exp,$amount);
			
			// IF APPROVED:   -> RETURN RESULTS
			$result		=	$rt;
			$amount	= 	$amount;			
			
	}
	
	
return  array ("result"   	=> $result,
               	"amount" 	=> $amount);
}


// DETERMINE THE SELECTED PAYMENT PROCESSOR AND SEND THE INFO THERE AND GET RESULTS...
function card_1($account_id,$name,$address,$zip,$cc_num,$cc_exp,$amount) {
	$gateway = setup("gateway");
	//TEST MODE
	if ($gateway ==0) {
		$ret = "1";
	//COLLECT ONLY
	} elseif ($gateway==25) {
		$ret = "25";		
	} elseif ((((($gateway ==1) || ($gateway ==1) || ($gateway ==1) || ($gateway ==1) || ($gateway ==1))))) {
		//  (Authorize.net) (ECX) (Netbilling) (PlanetPayment) (RTWare) 
		$ret = charge_five($account_id,$name,$address,$zip,$cc_num,$cc_exp,$amount,$gateway);
		
	} elseif ($gateway ==2) {
		//  (ECHO)
		$ret = charge_echo($account_id,$name,$address,$zip,$cc_num,$cc_exp,$amount,$gateway);
		
	} elseif ($gateway ==4) {
		//  (IBill)
		$ret = charge_ibill($account_id,$name,$address,$zip,$cc_num,$cc_exp,$amount,$gateway);

	} elseif ($gateway ==8) {
		//  (Verisign Payflow Pro)
		$ret = charge_verisign($account_id,$name,$address,$zip,$cc_num,$cc_exp,$amount,$gateway);
	}
return $ret;
}


//  CHARGE CARDS USING (Authorize.net) (ECX) (Netbilling) (PlanetPayment) (RTWare) 
function charge_five($account_id,$name,$address,$zip,$cc_num,$cc_exp,$amount,$gateway) {
        $pat             = " ";         $name          = (split($pat,$name));         $f_name       = $name[0];         $l_name       = $name[1];
        $type=AUTH_CAPTURE;

        $month         =        identify_month($cc_exp);
        $year            =        identify_year($cc_exp);
        $cc_exp         =        $month . "" . $year;
        
        $url[1] = "https://secure.authorize.net/gateway/transact.dll";
        $url[3] = "https://www.quickcommerce.net/scripts/qc25/WLDoTrans.asp";
        $url[5] = "https://secure.authorize.net/gateway/transact.dll";
        $url[6] = "https://secure.planetpayment.com/gateway/transact.dll";
        $url[7] = "https://secure.RTWare.net/gateway/transact.dll";
        
        $id	=	setup("gw_userid");
        
        $data   = "x_Login=" . $id;
        $data  .= "&x_Amount=" . $amount;
        $data  .= "&x_Card_Num=" . $cc_num;
        $data  .= "&x_Exp_Date=" . $cc_exp;
        $data  .= "&x_Address=" . urlencode($address);
        $data  .= "&x_Zip=" . $zip;
        $data  .= "&x_First_Name=" . $f_name;
        $data  .= "&x_Last_Name=" . $l_name;
        $data  .= "&x_ADC_URL=FALSE";
        $data  .= "&x_ADC_Delim_Data=TRUE";
        $data  .= "&x_Version=3.0";
        
        $curl    =  setup("curl");
        
       exec("$curl -d '$data' $url[$gateway]", $authorize, $ret);

        $return = split("\,", $authorize[0]);
                for ($i = 0; $i < 39; ++$i) {
                       // DEBUGGING INFO BELOW TURNED OFF!
                       // if ($return[$i]=="") { } else {
                        //echo "Code".$pos.":  ".$return[$i]."<BR>";
                        //}
                $pos = $i+1;
         		  }

                if ($return[0]=="") {  $ret = "3"; }
                elseif ($return[0]=="1") { $ret = "1"; }
                elseif ($return[0]=="2") { $ret = "2"; }
                elseif ($return[0]=="3") { $ret =  "3"; }
                else { $ret = "3"; }
return $ret;
}




//  CHARGE CARDS USING (ECHO) 
function charge_echo($account_id,$name,$address,$zip,$cc_num,$cc_exp,$amount) {
    $db = new ps_DB;
    $q = "SELECT * FROM account WHERE account_id='$account_id'";
    $db->query($q);
    $db->next_record();

    $month     =    identify_month($cc_exp);
    $year        =    identify_year($cc_exp);
    
    $echoid	=	setup("gw_userid");
    $echopin	=	setup("gw_password");

    $echoPHP = new EchoPHP;
    $echoPHP->set_EchoServer("https://wwws.echo-inc.com/scripts/INR200.EXE");
    $echoPHP->set_transaction_type("EV");
    $echoPHP->set_order_type("S");
    $echoPHP->set_merchant_echo_id($echoid);
    $echoPHP->set_merchant_pin($echopin);
    $echoPHP->set_billing_ip_address($REMOTE_ADDR);
    $echoPHP->set_billing_name(identify("cc_num"));
    $echoPHP->set_billing_address1($address);
    $echoPHP->set_billing_city($db->f("account_city"));
    $echoPHP->set_billing_state($db->f("account_state"));
    $echoPHP->set_billing_zip($zip);
     // $echoPHP->set_billing_country("USA");
    $echoPHP->set_billing_phone($db->f("account_phone"));
    $echoPHP->set_billing_fax($db->f("account_fax"));
    $echoPHP->set_billing_email($db->f("account_email"));
	//$echoPHP->set_debug("T");

    $echoPHP->set_cc_number($cc_num);
    $echoPHP->set_grand_total($amount);
    $echoPHP->set_ccexp_month($month);
    $echoPHP->set_ccexp_year($year);

    $echoPHP->set_counter($echoPHP->getRandomCounter());


    if ($echoPHP->Submit()) {
       //   print("Charge Sucessfull! ");
        $return="1";
    } else {
       //   print("Charge Declined. ");
         $return="2";
    } 
    //print($echoPHP->get_echotype2()) ;
    
return $return;
}



// CHARGE THE CARD USING IBILL
function charge_ibill($account_id,$name,$address,$zip,$cc_num,$cc_exp,$amount) {
        
        $orderid          = "DH-" . $account_id * $amount;
        $month         	= identify_month($cc_exp);
        $year         	= identify_year($cc_exp);
        $cc_exp 		= $month . "" . $year;

       $URL = "https://secure.ibill.com/cgi-win/ccard/tpcard.exe";
       
       $ibill_account	= setup("gw_userid");
       $password		= setup("gw_password");
       $curl				= setup("curl");

       $data = "reqtype=authorize";
       $data.= "&account=$ibill_account";
       $data.= "&password=$password";
       $data.= "&cardnum=$cc_num";
       $data.= "&cardexp=$cc_exp";
       $data.= "&noc=" . urlencode($name);
       $data.= "&address1=" . urlencode($address);
       $data.= "&zipcode=$zip";
       $data.= "&saletype=sale";
       $data.= "&amount=$amount";
       $data.= "&crefnum=$desc";

       $return = `$curl -d '$data' $URL`;

            if (eregi("authorized", $return)) {
              		$response = "1";
              } elseif (eregi("declined", $return)) {
              		$response = "2";
              } else {
              		$response = "3";
              }
              
             // echo $return;
return $response;
}


// CHARGE THE CARD USING VERISIGN
function charge_verisign($account_id,$name,$address,$zip,$cc_num,$cc_exp,$amount) {

	 $month        =        identify_month($cc_exp);
      $year           =        identify_year($cc_exp);
      $cc_exp        = 		$month . "" . $year;
      
     $CERT_PATH = setup("gw_1");
     $PATH = setup("curl");
	$USER=setup("gw_userid");
	$VENDOR=$USER;
	$PARTNER=setup("gw_2");
	$PWD = setup("gw_password");
	$AMT = $amount;
	$ACCT = $cc_num;
	$EXPDATE = $cc_exp;
	$ADDRESS = $address;
	$ZIP = $zip;
	$TRXTYPE = "S";
	$TENDER = "C";

	$ret = `export PFPRO_CERT_PATH=$CERT_PATH; $PATH payflow.verisign.com 443 'USER=$USER&VENDOR=$VENDOR&PARTNER=$PARTNER&PWD=$PWD&TRXTYPE=$TRXTYPE&TENDER=$TENDER&ACCT=$ACCT&EXPDATE=$EXPDATE&AMT=$AMT&ADDRESS=$ADDRESS&ZIP=$ZIP' 15`;
		
	$pat = "&";
	$arr = split($pat, $ret);
	$pat = "=";
			$arr0 = split($pat, $arr[0]);
			$arr1 = split($pat, $arr[1]);
			$arr2 = split($pat, $arr[2]);
			$arr3 = split($pat, $arr[3]);
			$arr4 = split($pat, $arr[4]);
			$arr5 = split($pat, $arr[5]);
			$arr6 = split($pat, $arr[6]);

		if ($arr0[1]=="") {
		return "3"; }
		elseif ($arr0[1]=="0") { return "1"; }
		elseif ((($arr0[1]=="12") || ($arr0[1]=="23") || ($arr0[1]=="24")))  { return "2"; }
		elseif ($arr0[1]=="1")  { return "2"; }
		else { return "3"; }

}



?>