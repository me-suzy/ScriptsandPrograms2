<?

include("prepend.php3");

page_open(array("sess" => "User_Session", "auth" => "Credigold_Auth", "transfer" => "Credigold_Transfer"));

initPage();



global $dc, $rc, $ac, $_Config;



   if ( get_param("merchantAccount") )

      {

         unset($auth->shop);

         $auth->shop["merchantAccount"] = get_param("merchantAccount");

         $auth->shop["companyName"] = get_param("companyName");

         $auth->shop["companyLogo"] = get_param("companyLogo");

         $auth->shop["companyText"] = get_param("companyText");

         $auth->shop["item_name"] = get_param("item_name");

         $auth->shop["item_id"] = get_param("item_id");

         $auth->shop["amount"] = get_param("amount");

         $auth->shop["memo"] = get_param("memo");

         $auth->shop["cartImage"] = get_param("cartImage");

      }





   $dc->query(sprintf("select u.user_id, u.user_number, u.username, u.real_name, u.active, u.referrer, c.sp_received ".

                        "from %s u, %s c where u.user_number = '%s' AND u.user_number = '%s';",

                        $auth->database_table,

                        $auth->database_details,

                        $auth->shop["merchantAccount"],

                        $auth->shop["merchantAccount"] ));



   $dc->next_record();



      $rc->query("SELECT ".$_Config["database_auth"].".real_name, ".$_Config["database_auth"].".user_number, ".$_Config["database_auth"].".user_id, ".$_Config["database_auth"].".referrer, ".$_Config["database_details"].".crediGold, ".$_Config["database_details"].".sp_made, ".$_Config["database_index"].".`index`

                  FROM ".$_Config["database_auth"].", ".$_Config["database_details"].", ".$_Config["database_index"]."

                  WHERE ".$_Config["database_auth"].".user_id='".$auth->auth["uid"]."' AND ".$_Config["database_details"].".user_id='".$auth->auth["uid"]."';");

      $rc->next_record();



                  $dollarAmount = $rc->get("index");

                  $getDollars   = $auth->shop["amount"]*$dollarAmount;

                  $fee          = $transfer->transferFee($getDollars);

                  $totalFees    = ($dollarAmount*$auth->shop["amount"]) + $fee;

                  $totalGold    = sprintf("%.2f", $totalFees/$dollarAmount);







if ( get_param("confirm") )

   {

      if ($rc->get("crediGold") <= $totalGold)

         {

?>

            <div align=center><img src=images/logos/cart.jpg width=199 height=44 border=0 alt="Shopping Cart"></div>

            <br>

            <br>

            <p align=center class=head style=color:red>Warning! Insufficient Funds In Account.<br>

            <p align=justify style="width:520px;padding:10px" class=text>It seems that you do not have enough FavoX on your account to complete this transaction. Please, fund your account before you continue.</p>

            </p>

         <script>

         <!--

            function redirect()

            {

               window.location.replace("transfer.php?cmd=fund");

            }

            setTimeout("redirect();", 5550);

         //-->

         </script>

<?

         endPage();

         page_close();

         exit;

         }// if

      else

         {

            $trans_ID  = $transfer->randomID();

            $ref_ID    = $transfer->randomID();

            $makeDate  = date("YmdHis", time());

            $ac->query("UPDATE ".$_Config["database_details"]." SET crediGold=crediGold-".$totalGold." WHERE user_id='".$auth->auth["uid"]."';");

            $ac->query("UPDATE ".$_Config["database_details"]." SET crediGold=crediGold+".$auth->shop["amount"]." WHERE user_id='".$auth->shop["merchantAccount"]."';");

            $ac->query("INSERT INTO ".$_Config["database_transactions"]." SET transaction_id='$trans_ID', amount='".$auth->shop["amount"]."', recepient_id='".$auth->shop["merchantAccount"]."', sender_id='".$auth->auth["userNumber"]."', memo='<div align=center><b>Shopping Cart Transaction:</b></div>\\n\\r<b>Item ID:</b>".$auth->shop["item_id"]."\\n\\r<b>Item Name:</b>".$auth->shop["item_name"]."\\n\\r<b>Notes:</b>".urldecode($auth->shop["memo"])."', transaction_date='$makeDate', transaction_ip='".getIP()."', transaction_fee='$fee';");



                  if ( $rc->get("sp_made") == "Y" )

                     {

                        // The Mail Function goes here



                           $dc->query("SELECT body, subject FROM ".$_Config["database_emails"]." WHERE id='8';");

                           $dc->next_record();

                           $email_body = $dc->get("body");

                           $email_subj = $dc->get("subject");



                           $email_body = eregi_replace("%name%", $rc->get("real_name"), $email_body);

                           $email_body = eregi_replace("%siteName%", $_Config["masterRef"], $email_body);

                           $email_body = eregi_replace("%amount%", $auth->shop["amount"], $email_body);

                           $email_body = eregi_replace("%account%", $rc->get("user_numer"), $email_body);

                           $email_body = eregi_replace("%item_name%", $auth->shop["item_name"], $email_body);

                           $email_body = eregi_replace("%date%", convertDate("-D of -M, -y", $makeDate), $email_body);



                           mailTO($auth->auth["uname"], $email_subj, $email_body);

                           $dc->free();

                        // The Mail Function ends here

                     } // if



//******************************************************************* Tup SUM

   $dc->query(sprintf("select u.user_id, u.user_number, u.username, u.real_name, u.active, u.referrer, c.sp_received ".

                        "from %s u, %s c where u.user_number = '%s' AND u.user_number = '%s';",

                        $auth->database_table,

                        $auth->database_details,

                        $auth->shop["merchantAccount"],

                        $auth->shop["merchantAccount"] ));



   $dc->next_record();

//******************************************************************* Tup SUM



                  if ( $dc->get("sp_received") == "Y" )

                     {

                        // The Mail Function goes here



                           $dc->query("SELECT body, subject FROM ".$_Config["database_emails"]." WHERE id='9';");

                           $dc->next_record();

                           $email_body = $dc->get("body");

                           $email_subj = $dc->get("subject");

//******************************************************************* Tup SUM

   $dc->query(sprintf("select u.user_id, u.user_number, u.username, u.real_name, u.active, u.referrer, c.sp_received ".

                        "from %s u, %s c where u.user_number = '%s' AND u.user_number = '%s';",

                        $auth->database_table,

                        $auth->database_details,

                        $auth->shop["merchantAccount"],

                        $auth->shop["merchantAccount"] ));



   $dc->next_record();

//******************************************************************* Tup SUM

                           $email_body = eregi_replace("%name%", $dc->get("real_name"), $email_body);

                           $email_body = eregi_replace("%siteName%", $_Config["masterRef"], $email_body);

                           $email_body = eregi_replace("%amount%", $auth->shop["amount"], $email_body);

                           $email_body = eregi_replace("%account%", $dc->get("user_numer"), $email_body);

                           $email_body = eregi_replace("%item_name%", $auth->shop["item_name"], $email_body);

                           $email_body = eregi_replace("%date%", convertDate("-D of -M, -y", $makeDate), $email_body);



                           mailTO($dc->get("username"), $email_subj, $email_body);



                        // The Mail Function ends here

                     } // if



            // Referral Fees Estimate

            /*

            $temp = $getDollars;

            if ($temp > 5 && $temp < 19.99) { $ref_fee = "0.02"; }

            else if ($temp > 20 && $temp < 34.99) { $ref_fee = "0.04"; }

            else if ($temp > 35 && $temp < 49.99) { $ref_fee = "0.06"; }

            else if ($temp > 50) { $ref_fee = "0.10"; }

            else { $ref_fee = "0"; }

            */

            $ref_fee = $transfer->referalFee($getDollars);

            // Referral Fees Estimate



         if ( ($rc->get("referrer") || $rc->get("referrer") != "") && $ref_fee>0 )

            {

               $ac->query("INSERT INTO ".$_Config["database_transactions"]." SET transaction_id='$ref_ID', amount='', sender_id='".$dc->get("user_number")."', recepient_id='".$dc->get("referrer")."', memo='Referral Payment By ".$dc->get("real_name")."', transaction_date='$makeDate', transaction_ip='".getIP()."', transaction_fee='0.0', referral_payment='Y', referrra_amount='".$ref_fee."';");

               $ac->query("UPDATE ".$_Config["database_details"]." SET crediGold=crediGold+".$ref_fee." WHERE user_number='".$dc->get("referrer")."';");

            }// if

?>

         <div align=center><img src=images/logos/cart.jpg width=199 height=44 border=0 alt="Shopping Cart"></div>

         <br>

         <br>

         <p align=center class=head style=color:green>Transfer Completed Successfully!<br>

         <p align=justify style="width:520px;padding:10px" class=text>Your transfer of <font color=orange><?=$auth->shop["amount"];?></font><img src=images/gold.gif> was successfully sent to <?print "".$dc->get("real_name")."</b>&nbsp;&nbsp;(Account Number: ".$auth->shop["merchantAccount"].")";?>. In just a few seconds you will be redirected to see the transaction details on your "Transfer Funds" screen in <?=$_Config["masterRef"]?>.</p>

         </p>

         <script>

         <!--

            function redirect()

            {

               window.location.replace("transfer.php");

            }

            setTimeout("redirect();", 8000);

         //-->

         </script>

<?

         }// else

   }// if

else {



   $logoCheck = fsockopen($auth->shop["companyLogo"], 80, &$errno, &$errstr, 30);



?>

<body bgcolor="white">

<link rel=stylesheet href=modules/styles.css>

<script language=JavaScript src=modules/mod_gen.js></script>

<table border=0 width=550 cellspacing=3 cellpadding=0 align=center>

<tr>

<td colspan=2 bgcolor=D0D0D0><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td colspan=2 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td colspan=2 align=center class=head height=40><b>

<?=($auth->shop["companyLogo"])?"&nbsp;<img src=\"".((!$logoCheck)?"images/crediGold.gif":$auth->shop["companyLogo"])."\" vspace=5 hspace=5>":"&nbsp;<img src=\"images/crediGold.gif\">";?>

<?=($auth->shop["companyName"])?"<br>".$auth->shop["companyName"]."'s Shopping Cart":"<br><font color=red>Error! No Company Used.</font>";?>

</td>

</tr>

<tr>

<td colspan=2 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td colspan=2 bgcolor=D0D0D0><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td colspan=2 align=center class=text>

<?if ($auth->shop["companyText"]) print "<div align=center><p align=justify style=padding:5px;width:500px><script>document.write(unescape('".$auth->shop["companyText"]."'));</script><br></p>";?>

</td>

</tr>

<tr>

<td align=right class=little height=20 width=30%>Shopping Cart Holder:&nbsp;</td>

<td align=left class=little width=70%>&nbsp;<b><?=($dc->get("real_name"))?"".$dc->get("real_name")."</b>&nbsp;&nbsp;(Account Number: ".$auth->shop["merchantAccount"].")":"N/A";?></td>

</tr>

<tr>

<td colspan=2 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=right class=little height=20>Account Status:&nbsp;</td>

<td align=left class=little>&nbsp;<?=($dc->get("active") == Y)?"<b style=color:green>Verified</b>":"<b style=color:red>NOT Verified</b>";?></td>

</tr>

<tr>

<td colspan=2 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td colspan=2 bgcolor=D0D0D0><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=right class=little height=20>Pay for:&nbsp;</td>

<?=($auth->shop["item_name"])?"<td align=left class=little>&nbsp;<b>".$auth->shop["item_name"]."</b> </td></tr>":"<td align=left class=little>&nbsp;<b>N/A</b></td>";?>

</tr>

<tr>

<td colspan=2 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=right class=little height=20>Transfer Amount:&nbsp;</td>

<?=($auth->shop["amount"])?"<td align=left class=little>&nbsp;<b style=color:orange>".$auth->shop["amount"]."</b> <img src=images/gold.gif></td></tr>":"<td align=left class=little>&nbsp;<b>N/A</b></td>";?>

</tr>

<tr>

<td colspan=2 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=right class=little height=20>Transfer Fee:&nbsp;</td>

<?=($auth->shop["amount"])?"<td align=left class=little>&nbsp;<b>$".$fee."</b> </td></tr>":"<td align=left class=little>&nbsp;<b>N/A</b></td>";?>

</tr>

<tr>

<td colspan=2 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=right class=little valign=top height=20>Transfer Memo:&nbsp;</td>

<?=($auth->shop["memo"])?"<td align=left class=little>&nbsp;<script>document.write(unescape('".$auth->shop["memo"]."'));</script></td>":"<td align=left class=little>&nbsp;<b>N/A</b></td>";?>

</tr>

<tr>

<td colspan=2 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td colspan=2 bgcolor=white><img src=images/dot.gif width=1 height=10></td>

</tr>

<tr>

<td colspan=2 class=text align=center>

<form name="shop" action="shopping.php" method="post">

<input type=submit name=confirm value="Confirm Shopping" <?=($dc->get("real_name"))?"":"disabled"?>><input type=button name=cencel value="Cancel" <?=($dc->get("real_name"))?"":"disabled"?>>

</form>

</td>

</tr>

</table>

<?

}

endPage();

page_close();

exit;