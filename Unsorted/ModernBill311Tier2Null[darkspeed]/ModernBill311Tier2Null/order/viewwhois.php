<?
/* ModernBill .:. Client Billing System
** Copyright © 2001,2002 ModernBill. All Rights Reserved.
**
** Vortech Signup v3.0
** http://www.vortechhosting.com
**
**    *************** WARNING ***************
**
** This script may not be used in whole or in part,
** without a valid software license from ModernBill.
**
**          http://www.modernbill.com
**
**    *************** WARNING ***************
*/
include("config.php");

include($DIR."include/misc/validate_form_input.inc.php");
if(!is_valid_email("x@".strtolower(trim(strip_tags($domain.".".$ext))))) { header("Location: $script_url_non_secure?error=1&domain=".strtolower(trim(strip_tags($domain)))); exit; }

print str_repeat(" ", 300) . "\n";
flush();
?>
<html>
<head><title><?=NETWORKINGTOOLS?></title></head>
<body>
         <?=LFH?><b><?=RESULTS?>:</b><?=EF?> <?=SFB?><?=$domain.".".$ext?><?=EF?></font></span>
         <br>
         <ul>
            <?
            echo "<pre>" . basic_whois(strtolower($domain),strtolower($ext)) ."</pre>";
            ?>
         </ul>
</body>
</html>