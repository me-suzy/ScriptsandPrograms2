<?php
##############################################################################
# \-\-\-\-\-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/-/-/-/-/-/ #
##############################################################################
# AzDGDatingGold                Version 3.0.5                                 #
# Writed by                     AzDG (support@azdg.com)                      #
# Created 25/05/02              Last Modified 25/05/02                       #
# Scripts Home:                 http://www.azdg.com                          #
##############################################################################
### Url were AzDGDatingGold has been installed, not "/" in end!!!
$url = "http://www.test.net/AzDGDatingGold";

### Internal path to AzDGDatingGold directory
$int_path="Z:/home/www.test.net/www/AzDGDatingGold";
### Main url, not "/" in end!
$main_url = "http://www.azdg.com";

### Flags directory - must be under $int_path
$flag_path = "images/flags";

### Site Name
$sname = "AzDGDatingGold 3.0.5";

### Admin Data 
$adminlogin = "admin"; // Admin login, Please change this for secure reasons
$adminpass = "dating"; // Admin password, Please change this for secure reasons
$adminmail = "your_mail@your_site.com"; //Admin email

### MySQL data
$mysql_host = "localhost"; // MySQL host name (usually:localhost)
$mysql_user = "user"; // MySQL username
$mysql_pass = "password"; // MySQL password
$mysql_base = "database"; // MySQL database

$mysql_table = "AzDGPmember2";
$mysql_hits = "AzDGPhits2";
$mysql_admin = "AzDGPadmin2";
$mysql_messages = "AzDGPmes2";
$mysql_faq = "AzDGPfaq2";
$mysql_online = "AzDGOnline";
### Other variables
$template_name = "default"; // name of template-directory
$last_reg = "10"; // Last XX registered on main page, if 0 - don`t show last registered
$allow_remove_profile = "1"; // Allow user remove own profile - 1, no - 0
$from_mail = "noreply@your_site.com"; // Email from - to user forgotted your password
### Some other data
$date_diff = "2"; // Difference time from your server
$use_unic_mail = "1"; // use unical mail for each user, 1 -yes, 0 - no (recommend use value 1, because in admin maillist you must send 1 mail to 1 user)
$cpage = 7; // Search page links list number, ex. 1 ... 6 7 8 [9] 10 11 12 ... 25 - if $cpage = 7

$for_all_ip = "0"; // Its simple popularity checking
//1 - allow view (simple view profile) with same ip several times,
//0 - don`t allow - write last view IP in to database, and checking for writing hits in hits database!

$popcheck = "2"; // Popularity checking:
// 0 - without popularity - (not recommended in this version) 
// 1 - when profile viewing by user 
// 2 - when message has been sended to user - (realy popularity - recomended) 

$show_lf = "0"; // Show language flags after select language - 1, don`t show language after user selected own language - 0

$up_prof = "1"; // Send mail to admin when registered user upload photo or new photo? This required if you want be carefull with porno pics...

### Username largest and smaller length
$username_l = "16";
$username_s = "1";

### Password largest and smaller length
$password_l = "16";
$password_s = "3";

### Email largest length
$email_l = "64";

### City largest length
$city_l = "32";

### Hobby largest word size and length
$hobby_w = "40";
$hobby_l = "500";

### Description largest word size and length
$desc_w = "40";
$desc_l = "2000";

### Peoples Weight and Height values
$max_weight = "200";
$max_height = "220";
$min_weight = "40";
$min_height = "120";
$between = "2"; // between means of height and weight, ex. 40,45,50,55,60,65 - if =5

### Age smaller and bigger - max=255
$age_s = "14"; // Smaller age in dating - 14 years old
$age_b = "60"; // Bigger age in dating - 60 years old
$age_between = "2"; // In the search form, from 14,16,18... to 14,16,18... - between years

### Graphic length
define('C_WIDTH',"200");

### Max image size (kb)
$MaxSize = "100";

###### Templates
### Error and succesfull messages templates
### For admin area
$err_mes_top = "<br><br><br><br><Table Border=1 CellSpacing=0 CellPadding=4 bordercolor=black width=500><Tr><Td Width=500 align=center class=head bgcolor=#F0E8A0><br><br>";

$err_mes_bottom = "<br><br><input class=input type=button OnClick='history.back()' value=<<<<<><br></Td></Tr></table><br><br><br><br>";

$suc_mes_bottom = "<br><br></Td></Tr></table><br><br><br><br>";

### Colors
$color1 = "#F0E8A0"; // 1st color for search results
$color2 = "#F0F0B0"; // 2nd color for search results
$color3 = "#F0E8A0"; // 3nd color for add form, search, email and other
$color4 = "#E8D870"; // 4nd color for background of cells with head message (like Welcome, top man, top woman and other)

################################################################
### Don`t change anything there
### Íè÷åãî íå ìåíÿéòå íèæå
### Ashaqda hechne deyishmeyin!
################################################################
@mysql_connect($mysql_host, $mysql_user, $mysql_pass);
@mysql_select_db($mysql_base);

if (isset($HTTP_POST_VARS))
{
while(list($name,$value) = each($HTTP_POST_VARS))
{
$$name = $value;
};
}; 

if (isset($HTTP_GET_VARS))
{
while(list($name,$value) = each($HTTP_GET_VARS))
{
$$name = $value;
};
}; 

function check_bad_chars ($ss) 
{
$ss = str_replace("\r\n","<br>","$ss");
$ss = str_replace("&","","$ss");
$ss = str_replace("%","","$ss");
$ss = str_replace("?","","$ss");
$ss = htmlspecialchars(stripslashes($ss));
$ss = trim($ss);
return $ss;
}

function check_email_addr($email) {
	if (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'. '@'.'[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.'[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email)) {
		return 1;
	}else{
		return 0;
	}
}

function ip() 
{ 
if (getenv(HTTP_CLIENT_IP)) 
    { 
    $ip = getenv(HTTP_CLIENT_IP); 
    } 
elseif(getenv(HTTP_X_FORWARDED_FOR)) 
    { 
    $ip = getenv(HTTP_X_FORWARDED_FOR); 
    } 
else 
    { 
    $ip = getenv(REMOTE_ADDR); 
    } 
return $ip; 
} 

function port() 
{ 
if (getenv(REMOTE_PORT)) 
    { 
    $port = getenv(REMOTE_PORT); 
    } 
else 
    { 
    $port = rand(1,1000); 
    } 
return $port; 
} 

?>