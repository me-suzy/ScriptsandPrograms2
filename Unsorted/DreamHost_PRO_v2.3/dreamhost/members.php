<?

/* This software is developed & licensed by Dreamcost.com.
Unauthorized distribution, sales, or use of any of the code, in part or in whole, is
strictly prohibited and will be prosecuted to the full extent of the law. */

require("setup.php");
define("DB_HOST", "$host");
define("DB_NAME", "$database");
define("DB_USER", "$user");
define("DB_PWD", "$pass");
require("functions.php");
require("db.conf");

$path = setup("path");
$currency   = setup("currency");

if (!isset($session_id)) {
	$session_id = generate_session_id($aid);
	} else {
		if ($session_id=="") {
		$session_id = generate_session_id($aid);
		}
	}

if (isset($D_C)) {
        $i=0;
        while ($D_C[$i]) {
                if ($D_Y[$i]=="Y") {
                        cart_add($REMOTE_ADDR,$D_C[$i],"1","1");
                        }
               		$i++;
                	}
        		}



if (isset($action)) {
        if ($action=="remove_domain") { cart_remove_domain($domain); }
        if ($action=="email_password") { $message = send_password($email); }
        if ($action=="transfer_domain") { cart_add($REMOTE_ADDR,$transfer_domain,"0","1"); }
        if ((($action=="login") && ($login_email != "") && ($login_password != ""))) { login_user($login_email,$login_password,$session_id); }
        if ($action=="add_account") { $page= add_user_account($account_password,$account_email,$account_name,$account_company,$account_address,$account_city,$account_state,$account_zip,$account_country,$account_fax,$account_phone,$account_membership_id,$account_status, $account_pmt_type,$account_acct_no,$account_acct_exp,$account_check_no,$account_check_rt); }
        if ($action=="update_account") { $message= add_user_account($account_password,$account_email,$account_name,$account_company,$account_address,$account_city,$account_state,$account_zip,$account_country,$account_fax,$account_phone,$account_membership_id,$account_status, $account_pmt_type,$account_acct_no,$account_acct_exp,$account_check_no,$account_check_rt); }
        if ($action=="update_domain") { $i=0; while($d_id[$i]) { cart_update_domain($d_id[$i],$L[$i],$H[$i]); $i++; }         }
}

$cart = cart_mini($REMOTE_ADDR);
$form = $HTTP_POST_VARS;

if ($page=="") {
        $page="main";
        }
        include($path . "member_template.html");
?>