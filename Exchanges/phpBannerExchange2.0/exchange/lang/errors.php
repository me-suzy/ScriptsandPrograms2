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

// Error title and headers..
$LANG_error="ERROR";
$LANG_error_header="The following errors were encountered when processing your request:";
$LANG_back="Back";
$LANG_tryagain="Please <a href=\"javascript:history.go(-1)\">go back</a> and try again";

// Login error stuff
$LANG_login_error="The login information you entered was incorrect or not found in the database! Please <a href=\"index.php\">go back</a> and try again!";
// Client link is different..so we have to repeat this.
$LANG_login_error_client=$LANG_login_error="The information you entered was incorrect or not found in the database! Please <a href=\"../index.php\">go back</a> and try again!";

// mysql connect error (used in both login and processing)
$LANG_error_mysqlconnect="We were unable to connect to the Database with the information provided. The database returned the following error result:";

// ADMIN: add account error: We only check the username
// because we expect the admin to know what he/she is doing...
$LANG_addacct_error="The username exists! Please <a href=\"javascript:history.go(-1)\">go back</a> and try again";


// ADMIN: add admin errors..
$LANG_adminconf_login_long="The information you submitted in the Login field is invalid. (should be less than 20 characters in length)";
$LANG_adminconf_login_short="The information you submitted in the Login field is invalid. (should be at least 2 characters in length)";
$LANG_adminconf_login_inuse="The login ID $newlogin is already in use";
$LANG_adminconf_pw_mismatch="Your passwords do not match";
$LANG_adminconf_pw_short="The information you submitted in the password field is invalid. (should be at least 4 characters in length)";
$LANG_adminconf_goback="Please <a href=\"javascript:history.go(-1)\">go back</a> and try again";
$LANG_adminconf_added="The account has been added";

// ADMIN: Category admin errors...
$LANG_addcat_tooshort="The category name you submitted should be at least 2 characters long";
$LANG_addcat_toolong="The Category name you submitted should be no more than 50 characters long";
$LANG_addcat_exists="The Category name you chose already exists";
$LANG_cats_nocats="There are no available categories! You should have at least 1 category!";
$LANG_delcat_default="You can not delete the default category!";

// ADMIN/CLIENT: Change pw errors...
$LANG_pwconfirm_err_mismatch="Your passwords do not match!";
$LANG_pwconfirm_err_short="The information you submitted in the password field is invalid. (should be at least 4 characters in length).";
$LANG_pwconfirm_err_intro="The following errors were encountered when processing your request";

// ADMIN/CLIENT: Upload/banner Errors...
$LANG_upload_blank="You did not enter the path to a valid image. Please go back and try again";
$LANG_upload_not="Your image was not successfully uploaded. Please go back and try again";
$LANG_err_badimage="The system was unable to locate your banner image with the URL you provided. This is because it's either not an image, or does not exist at the URL you provided (<b>$bannerurl</b>) if you are uploading this image, there could have been a problem uploading the file.  Please check the URL and try again.  Please note that if you are on a free host such as Geocities or Angelfire, you might need to include your banner somewhere on your own page in order for it to be allowed to be remotely linked. Some services don't allow remote linking at all.";
$LANG_err_badwidth="Your banner is invalid because it is too wide. Banners for this exchange should be $bannerwidth pixels wide.";
$LANG_err_badheight="Your banner is invalid because it is too high. Banners for this exchange should be $bannerheight pixels high.";
$LANG_err_filesize="Your banner's file size exceeded the maximum file size allowed in the exchange. Banner should be no larger than $max_filesize bytes.";

// Add admin error..
$LANG_adminconf_loginexist="The login ID specified is already in use!";

// edit templates error..
$LANG_editcsstemplate_errornofile="The file specified does not exist! This usually means you have changed the filename or tried to pass a variable with a incorrect filename. For security, this cannot be done. Please go back and try again.";
$LANG_editcsstemplate_cannotwrite="phpBannerExchange is unable to write to the css.php file. This can be caused by incorrect permissions on the file (should be 755 or 777) or you do not have access to write to this file. Check the file permissions and access rights on the file in question and try again.";

// Promo Manager errors..
$LANG_promo_noproduct="You did not enter a product name!";
$LANG_promo_badcode="You did not enter a code or the code is already in use!";
$LANG_promo_noval="You must enter a value in the \"value\" field in order to use this promo type.";
$LANG_promo_nocreds="You must enter a value in the \"credits\" field in order to use this promo type.";

// "COMMON"/PUBLIC SECTION ERRORS

// Lost Password error -- unable to locate account.
$LANG_lostpw_noacct="We were unable to locate an account for <b>{email}</b>. Please try again.";

// Signup Errors (/signupconfirm.php)
$LANG_err_nametooshort="The information you submitted in the <b>Real Name</b> field is invalid. (should be more than 2 characters--You entered <b>$_REQUEST[name]</b>)";
$LANG_err_nametoolong="The information you submitted in the <b>Real Name</b> field is invalid. (should be less than 100 characters--You entered <b>$_REQUEST[name]</b>)";
$LANG_err_loginshort="The information you submitted in the <b>Username</b> field is invalid. (should be less than 20 characters in length--You entered <b>$_REQUEST[login]</b>)";
$LANG_err_loginlong="The information you submitted in the <b>Username</b> field is invalid. (should be at least 2 characters in length--You entered <b>$_REQUEST[login]</b>)";
$LANG_err_logininuse="The Username $_REQUEST[login] is already in use";
$LANG_err_emailinuse="The <b>e-mail address</b> you specified, <b>$_REQUEST[email]</b>, is already in use. Only one account per e-mail address is allowed";
$LANG_err_invalidurl="Site URL is invalid. Please make sure you include the filename (index.html, for example) or a trailing slash (http://www.somesite.com/)! You entered <b>$_REQUEST[targeturl]</b>";
$LANG_err_badimage="The system was unable to locate your banner image with the URL you provided. This is because it's either not an image, or does not exist at the URL you provided (<b>$_REQUEST[bannerurl]</b>).  Please check the URL and try again.  Please note that if you are on a free host such as Geocities or Angelfire, you might need to include your banner somewhere on your own page in order for it to be allowed to be remotely linked. Some services don't allow remote linking at all.";
$LANG_err_badwidth="Your banner is invalid because it is <b>$imagewidth</b> pixels wide. Banners for this exchange should be <b>$bannerwidth</b> pixels wide.";
$LANG_err_badheight="Your banner is invalid because it is <b>$imageheight</b> pixels high. Banners for this exchange should be <b>$bannerheight</b> pixels high.";
$LANG_err_email="The system was unable to validate your email address because it contains special characters. Please contact the administrator for assistance. (You entered <b>$_REQUEST[email]</b>).";
$LANG_err_passmismatch="Your passwords do not match! <b>$_REQUEST[pass]</b> does not equal <b>$_REQUEST[pass2]</b>";
$LANG_err_passshort="The information you submitted in the <b>Password</b> field is invalid. (should be at least 4 characters in length-- You entered <b>$_REQUEST[pass]</b>).";
$LANG_err_nocoupon="The coupon code you entered is invalid or it is an invalid coupon type! Coupons are CaSe SeNsItIvE!";

// Client coupon errors...
$LANG_coupon_wrongtype="This type of coupon can not be applied in the online store!";
$LANG_coupon_nocoup="The coupon you entered is invalid! Coupons are CaSe SeNsItIvE!";

// Promo code errors..
$LANG_coupon_clntwrongtype="This type of coupon can only be applied in the online store!";
$LANG_coupon_noreuse="This coupon can not be reused!";
$LANG_coupon_userwrongtype="You are ineligible to use this coupon! This coupon can only be used at signup!";
$LANG_coupon_noreuseyet="You are not eligible to reuse this coupon at this time! This coupon can be reused on $date_placeholder";

// Banner delete error
$LANG_bannerdel_error="The banner could not be deleted because it has already been removed from the exchange! This could be caused by several things. Go back to the Banner display screen and insure that the banner still exists!";

// e-mail change error
 $LANG_infoconfirm_invalid="The system was unable to validate your email address because it contains special characters or is invalid. Please try again or contact the administrator for assistance.";

 // Password change errors
$LANG_err_nopassmatch="Your passwords do not match!";
$LANG_err_passtooshort="The information you submitted is invalid. (should be at least 4 characters in length.";
?>