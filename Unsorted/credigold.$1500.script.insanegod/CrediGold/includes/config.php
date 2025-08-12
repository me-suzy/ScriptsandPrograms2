<?

/*-------------------------------------------------------------*/

//                        MAIN SETTINGS                        //

/*-------------------------------------------------------------*/

$_Config["masterSign"]    = "crediGold";              // The site currency unit

$_Config["masterRef"]     = "crediGold.com";          // Master name of the site

$_Config["masterSlogan"]  = "Discover your profit!";  // The slogan of the site

$_Config["construction"]  = "no";                     // Set the site as under construction or unavailable while making maintainance (options: yes/no)



$_Config["masterEmail"]   = "noreply@credigold.com";  // The main email of your site - for Admin purposes

$_Config["errorEmail"]    = "errors@crediGold.com";   // The email to receive problematic emails sent via the SMTP server



$_Config["WhoIsServer"]   = "whois.ripe.net";         // Name of the WhoIs server for IP locating of the users on the site



/*-------------------------------------------------------------*/



/*-------------------------------------------------------------*/

//                       DATABASE SETTINGS                     //

/*-------------------------------------------------------------*/

$db_database   = "urunity";

$db_user       = "urunity";

$db_password   = "2113";

$db_host       = "localhost";

/*-------------------------------------------------------------*/



/*-------------------------------------------------------------*/

//                   FEES & AFFILIATES SECTION                 //

/*-------------------------------------------------------------*/

// 1. Transfer Fees

   $_Config['fee_type']  = "percent";              // Options (fixed, percent)

   $_Config['fee_money'] = 20;                     // When fee_type set to fixed this represent dollar amount, when set to percent it represents % value

   $_Config['fee_limit'] = 10;                     // Fix the fee to a specific amount if the estimated fee is too large (when 0 no fee limit applies)



// 2. Affiliate Program Fees

   $_Config['affiliate_fee_type']     = "dynamic"; // Options (fixed, percent, dynamic*)

   $_Config['affiliate_fee_money']    = 20;        // Same as for transfers fees

   $_Config['affiliate_fee_limit']    = 0;         // Same as for transfers fees



   $_Config['affiliate_fee_range'][] = array('0-100', 9.99);

   $_Config['affiliate_fee_range'][] = array('101-999', 10.1);



/*---------------------------------------------------------------*/

// DYNAMIC AFFILIATE FEES EXPLANATION:                           //

// They are applied on the principle of dollar ranges. For each  //

// dollar range the fees is different. An example range could be //

// found below:                                                  //

//                                                               //

// $_Config['affiliate_fee_range'][] = array('0-100', 10);       //

// $_Config['affiliate_fee_range'][] = array('101-999', 15);     //

//                                                               //

// This translates as if the sume is between 0 and 100 the fee's //

// 10, if between 101 and 999 it fee is 15. When the sum is more //

// than the largest number in the last range the fee is equal to //

// the fee of the last range.                                    //

/*---------------------------------------------------------------*/



/*-------------------------------------------------------------*/

//                    MAIN BALANCE PAGE LOOK                   //

/*-------------------------------------------------------------*/

   // Balance Page Colors

   $_Config["balance_css"]            = "background-color:#FFF7E6; border: 1px solid #FFCF9F; margin:4px; width:170px";

   $_Config["balance_digit_color"]    = "orange";

   $_Config["balance_request_color"]  = "orange";

   $_Config["balance_author_email"]   = "gray";

   $_Config["balance_header_color"]   = "gray";



   // Legend Table Look

   $_Config["balance_legend_bgcolor"] = "white";

   $_Config["balance_legend_hdcolor"] = "#FAFAFA";

   $_Config["balance_legend_border"]  = "#EFEFEF";

   $_Config["balance_legend_width"]   = "450";



   // General Legend Colors

   $_Config["received_payment_color"] = "#F2F9FF";

   $_Config["send_payment_color"]     = "#FFF7E6";

   $_Config["ref_payment_color"]      = "#EFF8F2";



   // Recent Transactions

   $_Config["balance_rows_backcolor"]   = "white";

   $_Config["balance_rows_midborder"]   = "#F6F6F6";

   $_Config["recent_trans_head_bgr"]    = "#F9F9F9";

   $_Config["recent_trans_head_border"] = "#F0F0F0";

   $_Config["recent_trans_out_borders"] = "#DFDFDF";

   $_Config["recent_table_width"]       = "530";



   // Texts on Page

   $_Config["recent_transactions"]      = "Recent Transactions";

   $_Config["no_recent_trans"]          = "No recent transaction activity!";

   $_Config["add_to_address"]           = "Add Account to Address Book!";

   $_Config["view_history_trans"]       = "View History";

   $_Config["view_history_trans_over"]  = "View a history of all your transactions";



/*-------------------------------------------------------------*/



/*-------------------------------------------------------------*/

//                    DATABASE TABLES SECTION                  //

/*-------------------------------------------------------------*/

  $_Config["database_index"]        = "credigold_index";

  $_Config["database_transactions"] = "credigold_transactions";

  $_Config["database_payments"]     = "credigold_payments";

  $_Config["database_requests"]     = "credigold_requests";

  $_Config["database_meta"]         = "credigold_meta";

  $_Config["database_addressbook"]  = "credigold_addressbook";

  $_Config["database_banners"]      = "credigold_banners";

  $_Config["database_emails"]       = "credigold_emails";

  $_Config["database_pages"]        = "credigold_pages";

  $_Config["database_online"]       = "credigold_online";

  $_Config["database_wmethods"]     = "credigold_withdrawal_methods";

  $_Config["database_withdrawals"]  = "credigold_withdrawals";

  $_Config["database_fund"]         = "credigold_fund";

  $_Config["database_fund_records"] = "credigold_fund_records";

  $_Config["database_logging"]      = "user_logging";

  $_Config["database_auth"]         = "user_auth";

  $_Config["database_details"]      = "user_details";

/*-------------------------------------------------------------*/



/*-------------------------------------------------------------*/

//                    MISCELLANEOUS SETTINGS                   //

/*-------------------------------------------------------------*/

$_Config["track_online"]  = false;                                   // Enable Track Online Visitors (true for enable, false for disable)

$_Config["cartImage"]     = "http://www.test.com/test.jpg";          // Default Site's Shopping Cart Image if user does not use his own

$_Config["cartURL"]       = "http://www.crediGold.com/shopping.php"; // The URL of the cart payment processor

$_Config["paginate"]      = 5;                                       // Number of Results Per Page Setting

?>

