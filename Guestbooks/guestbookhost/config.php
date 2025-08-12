<?
################################
# MySQL Variables - Must be configured first!
$sqlhost = "";
$sqllogin = "";
$sqlpass = "";
$sqldb = "";
$table = "gb_owners"; //Table where guestbook accounts are stored
$msgstable = "gb_msgs"; //Table where guestbook messgaes are stored
$adstable = "gb_ads"; //Table where banner ads are stored
################################

#These settings control the functionality of GuestBookHost
$adminpass = "mypass"; //Password for Administration Area
$signup_filename = "signup.php";
$edit_filename = "edit.php";
$guestbook_filename = "gb.php";
$gbtitle = "GuestBookHost"; //Use one or two words for your site's title. It's used in many different places.
$gburl = "http://www.nukedweb.com/guestbook/";  //ALWAYS end with a trailing slash!! It could be hazardous to your health, err I mean script, if it's not there. :)
$purgedays = "30"; //Number of days an account can go with no guestbook entries before it's erased. Leave it blank or set to 0 to disable.
$gb_popwin_width = "530";
$gb_popwin_height = "330";
$allowedhtmltags = "<a><b><i><u>";

#These variables set the appearance of certain pages, and the design of the site overall.
$headerfile = "";
$footerfile = "";
$tablewidth = "90%";
$bordersize = "1";
$cellspacing = "0";
$cellpadding = "2";
$tablebgcolor = "#507ca0";
$tabletextcolor = "#FFFFFF";
$tablefontname = "Verdana, Arial, Helvetica, sans-serif";
$tablefontsize = "-1";
$signup_button_text = "Create My Account";
$login_button_text = "Log In";
$updateinfo_button_text = "Update Info";
$create_page_title = "Create Account";
$create_page_done = "Account has been created!";
$login_page_start = "Log In";
$login_page_done = "Account Information";
$login_page_error = "Error: Invalid Login Information";

function verifylogin($email,$password){
	global $table;
	$sql = "select password from $table where email='$email' and password='$password'";
	$result = mysql_query($sql);
	$numrows = mysql_num_rows($result);
	if ($numrows!=0) $xzx = "1";
	return $xzx;
}

$db = mysql_connect($sqlhost, $sqllogin, $sqlpass) or die("OOps!");
mysql_select_db($sqldb, $db);
?>