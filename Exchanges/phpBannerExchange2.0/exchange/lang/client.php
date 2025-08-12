<?
$file_rev="041305";
$file_lang="en";
// If you translate this file, *PLEASE* send it to me
// at darkrose@eschew.net

// Many of the variables contained in this file are used
// as common variables throughout the script. I have tried
// my best to include these variables in the "generic"
// section. I know many languages use different suffixes
// and what-not when used in context, so I have included
// the context in which some variables are used in the
// comments.
//
// Mail templates are located in the /templates/mail directory
// Error messages are located in the /lang/errors.php file

//Common messages
//menu:
$LANG_menu_nav="Navigation";
$LANG_menu_home="Home";
$LANG_menu_logout="Logout";

$LANG_menu_stats="Statistics";
$LANG_menu_emstats="E-mail Stats";

$LANG_menu_site="Your Site";
$LANG_menu_commerce="Buy Credits";
$LANG_menu_banners="Your Banners";
$LANG_menu_cat="Change Category";
$LANG_menu_htmlcode="Get HTML Code";

$LANG_menu_info="Your Info";
$LANG_menu_changeem="Change E-mail";
$LANG_menu_changepass="Change Password";
$LANG_coupon_menuitem="Enter Promo Code";

//Common stuff
$LANG_reset="Reset";

// Stats Page (client/stats.php)
$LANG_stats_title="Control Panel for";
  //Stats Window stuff
$LANG_stats_startdate="Start Date";
$LANG_stats_siteexpos="Banners Shown on Your Site";
$LANG_stats_siteclicks="Clicks From Your Site";
$LANG_stats_percent="Percentage";
$LANG_stats_ratio="Ratio";
$LANG_stats_exposures="Exposures";
$LANG_stats_avgexp="Average Exposures/Day";
$LANG_stats_clicks="Clicks To Your Site";
$LANG_commerce_credits="Credits";
  // Explanation of stats Window Stuff. These next 2 groups
  // are separate even though they say roughly the same thing
  // to keep them easier to manage.
  // 
  // This first one is for a normal x:1 ratio site..Full message
  // reads: "To date, you have displayed [x] banners on your site,
  // and generated [y] clicks from those exposures. You have earned
  // [z] credits for your own banner on other sites (you earn [n]
  // exposure(s) for each banner you display)."
$LANG_stats_exp_normal="<br>To date, you have displayed";
$LANG_stats_exp_normal1="banners on your site, and generated";
$LANG_stats_exp_normal2="clicks from those exposures.  You have earned";
$LANG_stats_exp_normal3="credits for your own banner on other sites (You earn"; 
$LANG_stats_exp_normal4="exposure(s) for each banner you display).";
  // This one is for "odd" ratio sites like 5:4 ..Full message
  // reads: "To date, you have displayed [x] banners on your site,
  // and generated [y] clicks from those exposures. You have earned
  // [z] credits for your own banner on other sites (you earn [n]
  // exposure(s) for each [n] displays)."
$LANG_stats_exp_weird="<br>To date, you have displayed";
$LANG_stats_exp_weird1="banners on your site, and generated";
$LANG_stats_exp_weird2="clicks from those exposures.  You have earned";
$LANG_stats_exp_weird3="credits for your own banner on other sites.  (You earn"; 
$LANG_stats_exp_weird4="exposures for each <b>$banexp</b> displays.)";
  // Approved or not approoved messages.
$LANG_stats_approved="Your account is currently approved and in the exchange rotation.";
$LANG_stats_unapproved="Your account is awaiting administrative approval.  Once approved, it will be added into the rotation, and any earned credits will be redeemed for exposures.";
$LANG_stats_bannercount="Total Banners:";
 // Referral messages. Reads: "You have earned [x] credits by
 // referring [y] account(s) to the exchange. Currently, there
 // are [z] accounts referred by you awaiting validation..(etc)".
$LANG_stats_referral1="You have earned";
$LANG_stats_referral2="credits by referring";
$LANG_stats_referral3="account(s) to the exchange. Currently, there are";
$LANG_stats_referral4="accounts referred by you awaiting validation. Referral bounties are only paid out when the account is approved by the Exchange Administrator.";
  // Tips
$LANG_tip_startdate="The date your account was created.";
$LANG_tip_siteexposure="The number of banners that have been shown on your site.";
$LANG_tip_clickfrom="The number of clicks generated from banners shown on your site.";
$LANG_tip_percentout="The percentage of viewers of your site who click on a banner to another site in the exchange.";
$LANG_tip_ratioout="The ratio of viewers of your site who click on a banner to another site in the exchange.";
$LANG_tip_exposures="The number of times your banner has been shown on other sites in the exchange.";
$LANG_tip_avgexp="The average number of exposures per day for your banners on other sites in the exchange.";
$LANG_tip_clicks="The number of times your banners have been clicked on when displayed on another site in the exchange";
$LANG_tip_percentin="The percentage of viewers of other sites in the exchange that clicked your banner.";
$LANG_tip_ratioin="The ratio of viewers of other sites in the exchange that clicked your banner.";
$LANG_tip_credits="The amount of available, unredeemed credits your account has accumulated through displaying banners on your site.";

// Log Out page (/client/logout.php)
$LANG_logout_title="Logged Out";
$LANG_logout_message="You have been successfully logged out!<p><a href=\"../index.php\">Click Here</a> to return to the login screen.";

// Email Stats (/client/emailstats.php)
$LANG_emailstats_title="Email Stats";
$LANG_emailstats_msg="We have sent an e-mail to $email with your account statistics. You should be receiving it shortly.";

// Commerce/Buy Credits (/client/commerce.php)
$LANG_commerce_noitems="There are currently no credits available for purchase";
$LANG_commerce_name="Product Name";
$LANG_commerce_price="Price";
  // "Buy now via [service]". In the future, phpBannerExchange
  // will support multiple payment services.
$LANG_commerce_buynow_button="Buy Now via";
$LANG_commerce_buynow="Buy Now";
$LANG_commerce_history="Your Purchase History";
$LANG_commerce_date="Date";
$LANG_commerce_item="Item";
$LANG_commerce_purchaseprice="Purchase Price";
$LANG_commerce_invoice="Invoice";
$LANG_commerce_nohist="There are no purchased found!";
$LANG_commerce_couponhead="Coupon";
$LANG_commerce_coupon_button="Apply Coupon";

// Banners (/client/banners.php)
$LANG_targeturl="Target URL";
$LANG_filename="Filename";
$LANG_views="Views";
$LANG_clicks="Clicks";
$LANG_bannerurl="Banner URL";
$LANG_menu_target="Change URL(s)";
$LANG_button_banner_del="Delete Banner";
$LANG_stats_hdr_add="Add a Banner";
$LANG_banner_instructions="To edit a banner's target URL or banner URL, alter the data in the appropriate field, then click the <b>Change URL(s)</b> button. To delete the banner, click the <b>Delete Banner</b> link. To visit the site specified in the Target URL, click the banner belonging to that target URL.";

// this variable displays at the bottom of the "Banners" page.
// eg: "4 banner(s) found for your account"
$LANG_banner_found="banner(s) found for your account";
$LANG_stats_nobanner="No banners found for your account!";

// Delete Banner (/client/deletebanner.php and /client/deleteconfirm.php)
$LANG_delban_title="Delete Banner";
$LANG_delban_warn="Are you sure you want to remove this banner? This is a procedure that cannot be undone.<br>";
$LANG_delban_button="Yes, Delete This Banner";
$LANG_delbanconf_verbage="The banner has been deleted!";
$LANG_delbanconf_success="The banner has been successfully removed from your account";

// Category page (/client/category.php and /client/categoryconfirm.php)
$LANG_cat_reval_warn="Changing your category will require re-approval by the administrator. (you will still earn credits while your account awaits approval).";
$LANG_cat_change_button="Change Category";
$LANG_cat_nocats="The Administrator has not defined any categories, so you can not change your category at this time.";
$LANG_catconf_message="Your category has been changed!";

// Get HTML (/client/gethtml.php)
$LANG_gethtml_title="Get HTML";
$LANG_gethtml_message="If you would like to use the category feature to display more targeted banners on your site, replace \"&cat=0\" with the appropriate category number from the table below (example: \"&cat=2\", \"&cat=3\", and so on). Make sure you use the same number for both codes above. This will insure you get the proper credit for your page views. Leaving this value as it is will show banners from all accounts regardless of selected category.";
$LANG_gethtml_catname="Category Name";
$LANG_gethtml_catid="Category ID";

// Change email address (/client/editinfo.php)
$LANG_email_title="Edit Your Account";
$LANG_email_address="Email Address";
$LANG_email_button="Change Email";

// Change email confirmation (/client/editconfirm.php)
 $LANG_infoconfirm_title="Edit Your Account";
 $LANG_infoconfirm_success="We have changed your email address to ";

// Change PW form (/client/editpass.php)
$LANG_pass_title="Change your Password";
$LANG_pass1_label="New Password";
$LANG_pass2_label="Again";
$LANG_pass_button="Change Password";
$LANG_pass_confirm="Your password has been changed! You will now need to <a href=\"logout.php\">log out</a> and back in with the new password.";

// Buy Credits page (/client/promo.php)
$LANG_coupon_menuitem="Enter Promo Code";
$LANG_coupon_instructions="Enter the promotion code received from the Administrator below.";
$LANG_submit="Submit";
$LANG_coupon_success="<b>The coupon has been redeemed!</b>";
$LANG_coupon_success2="$credits credits have been added to your account and are available for use immediately.";

// Click Log (/client/clicklog.php)
$LANG_clicklog="Click Log";
$LANG_clicklog_from="From your site";
$LANG_clicklog_to="To your site";
$LANG_clicklog_ip="IP Address";
$LANG_clicklog_date="Date/Time";
$LANG_noclicks="There are no clicks to display.";


//MAIL TEMPLATES ARE IN THE /template/mail DIRECTORY!!
?>