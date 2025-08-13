<?php
// +----------------------------------------------------------------------+
// | ModernBill [TM] .:. Client Billing System                            |
// +----------------------------------------------------------------------+
// | Copyright (c) 2001-2002 ModernGigabyte, LLC                          |
// +----------------------------------------------------------------------+
// | This source file is subject to the ModernBill End User License       |
// | Agreement (EULA), that is bundled with this package in the file      |
// | LICENSE, and is available at through the world-wide-web at           |
// | http://www.modernbill.com/extranet/LICENSE.txt                       |
// | If you did not receive a copy of the ModernBill license and are      |
// | unable to obtain it through the world-wide-web, please send a note   |
// | to license@modernbill.com so we can email you a copy immediately.    |
// +----------------------------------------------------------------------+
// | Authors: ModernGigabyte, LLC <info@moderngigabyte.com>               |
// | Support: http://www.modernsupport.com/modernbill/                    |
// +----------------------------------------------------------------------+
// | ModernGigabyte and ModernBill are trademarks of ModernGigabyte, LLC. |
// +----------------------------------------------------------------------+
##
## [DO NOT MODIFY/REMOVE BELOW]
##
if ($DIR && ($HTTP_COOKIE_VARS[DIR] || $HTTP_POST_VARS[DIR] || $HTTP_GET_VARS[DIR] || $_COOKIE[DIR] || $_POST[DIR] || $_GET[DIR])) {
    $ip   = $HTTP_SERVER_VARS[REMOTE_ADDR];
    $host = gethostbyaddr($ip);
    $url  = $HTTP_SERVER_VARS["HTTP_HOST"].$HTTP_SERVER_VARS["REQUEST_URI"];
    $admin= ($GLOBALS[SERVER_ADMIN]) ? $GLOBALS[SERVER_ADMIN] : "security@your.server.com";
    $body = "IP:\t$ip\nHOST:\t$host\nURL:\t$url\nVER:\t$version\nTIME:\t".date("Y/m/d: h:i:s")."\n";
    @mail($admin,"Possible breakin attempt.",$body,"From: $admin\r\n");
    print str_repeat(" ", 300)."\n";
    flush();
    ?>
    <html><head><body>
    <center><h3><tt><b><font color=RED>Security violation from: <?=$ip?> @ <?=$host?></font></b></tt></h3></center>
    <hr>
    <pre><? @system("traceroute ".escapeshellcmd($ip)." 2>&1"); ?></pre>
    <hr>
    <center><h2><tt><b><font color=RED>The admin has been alerted.</font></b></tt></h2></center>
    </body></html>
    <?
    exit;
}

$z = 0;
if (!($x_First_Name && $x_Last_Name))
{
   $error_msg .= "- ".FENAME."<br>";
   $z++;
}
if (!($x_Address && $x_City && $x_State && $x_Zip && $x_Country))
{
   $error_msg .= "- ".FEADDRESS."<br>";
   $z++;
}
if (!$x_Phone)
{
   $error_msg .= "- ".FEPHONE."<br>";
   $z++;
}
if (!is_valid_email($x_Email))
{
   $error_msg .= "- ".FEEMAIL."<br>";
   $z++;
}
if ($allow_domain_username&&!$username)
{
   $error_msg .= "- ".FEUSERNAME."<br>";
   $z++;
}
if ($allow_domain_password&&(!$password||!$pass_check||$password!=$pass_check))
{
   $error_msg .= "- ".FEPASSWORD."<br>";
   $z++;
}
if ($allow_domain_password&&(strlen(strval($password))<$password_length))
{
   $error_msg .= "- ".NEWPWSHORT."!<br>";
   $z++;
}
if ($pay_method == "")
{
   $error_msg .= "- ".FEPAYMENT."<br>";
   $z++;
}
if ($tos != 1)
{
   $error_msg .= "- ".FETERMS."<br>";
   $z++;
}
if ($pay_method == "creditcard")
{
   if (!($x_Card_Name && $x_Card_Bank && $x_Card_Num && $x_Exp_Month && $x_Exp_Year))
   {
       $error_msg .= "- ".FECCINFO."<br>";
       $z++;
   }
   if (!validate_cc_input($x_Card_Name,$client_id))
   {
       $error_msg .= "- ".FECCINVALID."<br>";
       $z++;
   }
   if ((date("Y") > $x_Exp_Year) || ((date("Y") == $x_Exp_Year) && (date("n") > $x_Exp_Month)))
   {
       $error_msg .= "- ".FEEXPDATE."<br>";
       $z++;
   }
}
if ($pay_method == "echeck")
{
   if (!($x_Bank_Name && $x_Bank_ABA_Code && $x_Bank_Acct_Num && $x_Drivers_License_Num && $x_Drivers_License_State && $x_Drivers_License_DOB))
   {
       $error_msg .= "- ".ECHECKINFO."<br>";
       $z++;
   }
}
include($DIR."include/config/config.client_extras.php");
for($ic=1;$ic<=10;$ic++)
{
   if (${"client_field_active_$ic"}   &&
       ${"client_field_vortech_$ic"}  &&
       ${"client_field_required_$ic"} &&
       !${"client_field_$ic"} )
   {
     $this_input_value = array("column"       => "client_field_$ic",
                               "required"      => ${"client_field_required_$ic"},
                               "title"         => ${"client_field_title_$ic"},
                               "type"          => ${"client_field_type_$ic"},
                               "size"          => ${"client_field_size_$ic"},
                               "maxlength"     => ${"client_field_maxlength_$ic"},
                               "admin_only"    => ${"client_field_admin_only_$ic"},
                               "append"        => ${"client_field_append_$ic"},
                               "default_value" => ${"client_field_append_$ic"});

       $error_msg .= "- ".$this_input_value[title]." ".REQUIRED."<br>";
       $z++;
   }
}
?>