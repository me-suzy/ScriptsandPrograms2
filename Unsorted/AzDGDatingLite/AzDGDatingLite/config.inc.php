<?php
##############################################################################
# \-\-\-\-\-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/-/-/-/-/-/ #
##############################################################################
# AzDGDatingLite                Version 1.1.0                                 #
# Writed by                     AzDG (support@azdg.com)                      #
# Created 25/05/02              Last Modified 12/09/02                       #
# Scripts Home:                 http://www.azdg.com                          #
##############################################################################
### Url were AzDGDatingLite has been installed, not "/" in end!!!
# ex : $url = "http://www.azdg.com/scripts/AzDGDatingLite";
$url = "http://www.azdg.com/scripts/AzDGDatingLite";

### Internal path to AzDGDatingPro directory
$int_path = "/home/azdg/html/scripts/AzDGDatingLite";

### Main url, not "/" in end!
$main_url = "http://www.azdg.com";

### Site Name
$sname = "AzDGDatingLite 1.01";

### Admin Data 
$adminlogin = "admin"; // Admin login, Please change this for secure reasons
$adminpass = "dating"; // Admin password, Please change this for secure reasons
$adminmail = "your_mail@your_site.com"; //Admin email

### MySQL data
$mysql_host = "localhost"; // MySQL host name (usually:localhost)
$mysql_user = "user"; // MySQL username
$mysql_pass = "pass"; // MySQL password
$mysql_base = "database"; // MySQL database

$mysql_table = "AzDGLmembers"; // MySQL table name for members data


### Other variables
$allow_remove_profile = "1"; // Allow to users remove own profile - 1, no - 0
$from_mail = "noreply@your_site.com"; // Email from - to user forgotted your password

### Some other data
$date_diff = "2"; // Difference time from your server
$use_unic_mail = "1"; // use unical mail for each user, 1 -yes, 0 - no
$cpage = 7; // Search page links list number, ex. 1 ... 6 7 8 [9] 10 11 12 ... 25 - if $cpage = 7

### Username largest and smaller length
$username_l = "16"; // no bigger than 16
$username_s = "1";

### Password largest and smaller length
$password_l = "16"; // no bigger than 16
$password_s = "3";

### Email largest length - set no more 64 chars!!!
$email_l = "64";

### City largest length - set no more 32 chars!!!
$city_l = "32";

### Hobby largest word size and length - set no more 255 chars!!!
$hobby_w = "40";
$hobby_l = "255";

### Description largest word size and length
$desc_w = "40";
$desc_l = "2000";// max 65535

### Peoples Weight and Height values - max value 255!!!
$max_weight = "200";
$max_height = "220";
$min_weight = "40";
$min_height = "120";
$between = "5"; // between means of height and weight, ex. 40,45,50,55,60,65 - if =5

### Age smaller and bigger - max=255
$age_s = "14"; // Smaller age in dating - 14 years old
$age_b = "60"; // Bigger age in dating - 60 years old
$age_between = "2"; // In the search form, from 14,16,18... to 14,16,18... - between years


### Max image size (kb)
$MaxSize = "100";

###### Templates
### Error and succesfull messages templates
$err_mes_top = "<br><br><br><br><Table Border=1 CellSpacing=0 CellPadding=4 bordercolor=black width=500><Tr><Td Width=500 align=center class=head bgcolor=#FEA9DC><br><br>";

$err_mes_bottom = "<br><br><input class=input type=button OnClick='history.back()' value=<<<<<><br></Td></Tr></table><br><br><br><br>";

$suc_mes_bottom = "<br><br></Td></Tr></table><br><br><br><br>";

### Colors
$color1 = "#FFBBFD"; // 1st color for search results
$color2 = "#FFCAFE"; // 2nd color for search results
$color3 = "#FEC0ED"; // 3nd color for add form, search, email and other
$color4 = "#FEA9DC"; // 4nd color for background of cells with head message (like Welcome, top man, top woman and other)

################################################################
### Don`t change anything there
### Íè÷åãî íå ìåíÿéòå íèæå
### Ashaqda hechne deyishmeyin!
################################################################
$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_pass);

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
$ss = htmlspecialchars(stripslashes($ss));
$ss = str_replace("\r\n","<br>","$ss");
$ss = str_replace("#","","$ss");
$ss = str_replace("\\","","$ss");
$ss = str_replace("$","","$ss");
$ss = str_replace("?","","$ss");
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

?>