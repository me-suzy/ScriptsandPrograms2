<?
################################
# MySQL Variables - Must be configured first!
$sqlhost = "";
$sqllogin = "";
$sqlpass = "";
$sqldb = "";
$table = "bannerexchange";
################################

$bx_url = "http://www.nukedweb.com/bx/"; // FULL URL where MyBannerExchange is installed. ALWAYS end with a trailing slash!
$bx_title = "NukedWeb"; // VERY short title, one word if possible
$hours_must_be_active = "12"; // number of hours an account is inactive before it's removed from the banner cycle.
$adminpass = "theadmin"; // Password for administration area


$headerfile = "./head.php";
$footerfile = "./foot.php";
$tablewidth = "90%";
$bordersize = "1";
$cellspacing = "0";
$cellpadding = "2";
$tablebgcolor = "#507ca0";
$tabletextcolor = "#ffffff";
$tablefontname = "Verdana, Arial, Helvetica, sans-serif";
$tablefontsize = "-1";
$signup_button_text = "Create My Account";
$login_button_text = "Log In";
$updateinfo_button_text = "Update Info";
$create_page_title = "Create Account";
$login_page_start = "Edit Your Banner Exchange Account";
$login_page_done = "Account Information";
$login_page_error = "Error: Invalid Login Information";


function getcategoriesascombo($selectcat){
	$domsarray = file("./categories.txt");
	$domcnt = count($domsarray);
	for ($x=0;$x<$domcnt;$x++){
		$domsarray[$x] = str_replace("\r", "", $domsarray[$x]);
		$domsarray[$x] = str_replace("\n", "", $domsarray[$x]);
		$selected = "";
		if ($domsarray[$x]==$selectcat) $selected = " selected";
		$cmbcats .= "<option value='$domsarray[$x]'$selected>$domsarray[$x]</option>\n";
	}
	return $cmbcats;
}

$db = mysql_connect($sqlhost, $sqllogin, $sqlpass) or die("OOps!");
mysql_select_db($sqldb, $db);
?>