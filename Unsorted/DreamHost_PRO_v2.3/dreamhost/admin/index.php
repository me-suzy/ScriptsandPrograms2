<?

/* This software is developed & licensed by Dreamcost.com.
Unauthorized distribution, sales, or use of any of the code, in part or in whole, is
strictly prohibited and will be prosecuted to the full extent of the law. */

// PATH TO SETUP.PHP, FUNCTIONS.PHP, AND DB.CONF FILES:
$path = "/path/to/dreamhost/";

require($path . "setup.php");
define("DB_HOST", "$host");
define("DB_NAME", "$database");
define("DB_USER", "$user");
define("DB_PWD", "$pass");
require($path . "functions.php");
require("db.conf");

$login = setup("login");
$pass  = setup("password");

        $form = $HTTP_POST_VARS;
        $max_results = setup("max_results");


if (!isset($PHP_AUTH_USER)) {
        header('WWW-Authenticate: Basic realm="DreamHost"');
    	header('HTTP/1.0 401 Unauthorized');
        echo 'Authorization Required.';
        exit;

} else if (isset($PHP_AUTH_USER)) {
        if (($PHP_AUTH_USER != "$login") || ($PHP_AUTH_PW != "$pass")) {
        header('WWW-Authenticate: Basic realm="DreamHost"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Incorrect Password Entered.';
        exit;

} else {
	
	
        if (isset($action)) {
        if ($action=="add_account") { $message = add_account($account_password,$account_email,$account_name,$account_company,$account_address,$account_city,$account_state,$account_zip,$account_country,$account_fax,$account_phone,$account_membership_id,$account_status, $account_pmt_type,$account_acct_no,$account_acct_exp,$account_check_no,$account_check_rt); }
        if ($action=="client_update") { $message = update_account($account_password,$account_email,$account_name,$account_company,$account_address,$account_city,$account_state,$account_zip,$account_country,$account_fax,$account_phone,$account_membership_id,$account_status, $account_pmt_type,$account_acct_no,$account_acct_exp,$account_check_no,$account_check_rt,$account_id); }
        if ($action=="client_delete") { $message = delete_account($account_id); }
        if ($action=="add_membership") { $message = add_membership($membership_setup,$membership_url,$membership_name,$membership_desc,$membership_price,$membership_recurring,$membership_frequency,$membership_approval,$membership_periods,$membership_active); }
        if ($action=="update_membership") { $message = update_membership($membership_setup,$membership_url,$membership_id,$membership_name,$membership_desc,$membership_price,$membership_recurring,$membership_frequency,$membership_approval,$membership_periods,$membership_active); }
        if ($action=="membership_delete") { $message = membership_delete($membership_id); }
        if ($action=="bill_all_now") { $message = bill_all_now();  }
        if ($action=="delete_affiliate")    { $message = delete_affiliate($affiliate_id); }
        if ($action=="delete_domain")    { $message = delete_domain($domain_id); }
        if ($action=="delete_order")       { $message = delete_order($order_id); }
        if ($action=="delete_account")    { $message = delete_account_1($account_id); }
	   if ($action==attribute_add)        { $message = add_attribute($form);  }
	   if ($action==attribute_update)    { $message = update_attribute($form);  }
	   if ($action==note_control)    	    { $message = note_control($form);  }
	   }
        

        if ($page=="") {
                $page="main";
                }
        include("template.html");
        }
}


?>




