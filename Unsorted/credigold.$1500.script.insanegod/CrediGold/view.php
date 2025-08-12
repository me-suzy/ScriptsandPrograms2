<?

/*------------[      View Request Module for crediGold.com (PHP)      ]--------------*/

/*                                                                                   */

/*   This PHP4 script program is written by Infinity Interactive. It could not be,   */

/*  copied, modified and/or reproduced in any form let it be private or public       */

/*  without the appropriate permission of its authors.                               */

/*                                                                                   */

/*  Date    : 05/18/2002                                                             */

/*  Version : 1.0                                                                    */

/*  Authors : Svetlin Staev (svetlin@developer.bg), Kiril Angov (kirokomara@yahoo.com) */

/*                                                                                   */

/*              Copyright(c)2002 Infinity Interactive. All rights reserved.          */

/*-----------------------------------------------------------------------------------*/

include("prepend.php3");

page_open(array("sess" => "User_Session",  "user" => "Credigold_Users", "auth" => "Credigold_Auth"));

global $dc, $rc, $id, $view_id, $transact, $_Config;

$id = (!$id)?$view_id:$id;

$dc->query("SELECT ".$_Config["database_requests"].".*, ".$_Config["database_auth"].".real_name, ".$_Config["database_auth"].".user_id, ".$_Config["database_auth"].".referrer, ".$_Config["database_details"].".crediGold, ".$_Config["database_index"].".`index`

            FROM  ".$_Config["database_requests"].", ".$_Config["database_auth"].", ".$_Config["database_details"].", ".$_Config["database_index"]."

            WHERE ".$_Config["database_requests"].".id='$id' AND ".$_Config["database_auth"].".user_number=".$_Config["database_requests"].".request_id AND ".$_Config["database_details"].".user_id='".$auth->auth["uid"]."';");

$dc->next_record();

if ($dc->get("target_id") == $auth->auth["userNumber"])

   {

      if (empty($accept))

         {

            set_session("view_id",$id);

            set_session("name",$dc->get("real_name"));

            set_session("conf","true");

?>

<link rel=stylesheet href=modules/styles.css>

<script language=JavaScript src=modules/mod_gen.js></script>

<body bgcolor=white leftmargin=10 topmargin=10 marginwidth=0 marginheight=10>



<div align=center class=head>View <?=$_Config["masterSign"]?> Request by <?=$dc->get("real_name");?>

<br>

<p align=justify class=text style=width:520px>&nbsp;&nbsp;<?=$auth->auth["real_name"]?>, below is the full transcript of the request made to you by <?=$dc->get("real_name");?>. Please review it and decide whether you want to accept the request and transfer <font color=orange><?=$dc->get("amount")?><img src=images/gold.gif></font> from your account to this of <?=$dc->get("real_name");?> or deny it and thus remove it from your "<?=$_Config["masterSign"]?> Requests" list.

<br><br>&nbsp;&nbsp;If you have any questions regarding this transaction operation, please, feel free to contact us from <a href=javascript:contactUs()>here</a>.</p>

</div>

<form name=view action=view.php method=POST>

<table border=0 width=530 cellspacing=3 cellpadding=0 align=center>

<tr>

<td colspan=2 bgcolor=DFDFDF><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=left class=text colspan=2 bgColor=F9F9F9>&nbsp;<img src=images/point.gif width=9 height=9>&nbsp;<b><?=$_Config["masterSign"]?> Request Transcript</b></td>

</tr>

<tr>

<td colspan=2 bgcolor=DFDFDF><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=right class=little bgColor=FCFCFC width=30% height=20>Request ID:&nbsp;</td>

<td align=left class=little bgColor=FCFCFC width=70%><?=$dc->get("id")?></font></td>

</tr>

<tr>

<td colspan=2 bgcolor=F6F6F6><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td colspan=2 bgcolor=F6F6F6><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=right class=little bgColor=FCFCFC height=20>Request Account:&nbsp;</td>

<td align=left class=little bgColor=FCFCFC>&nbsp;<b><?=$dc->get("real_name")?></b> (Account Number: <font color=gray><?=$dc->get("request_id")?></font>)</td>

</tr>

<tr>

<td colspan=2 bgcolor=F6F6F6><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=right class=little bgColor=FCFCFC height=20>Target Account:&nbsp;</td>

<td align=left class=little bgColor=FCFCFC>&nbsp;<b><?=$auth->auth["real_name"]?></b> (Account Number: <font color=gray><?=$dc->get("target_id")?></font>)</td>

</tr>

<tr>

<td colspan=2 bgcolor=F6F6F6><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=right class=little bgColor=FCFCFC height=20><?=$_Config["masterSign"]?> Requested:&nbsp;</td>

<td align=left class=little bgColor=FCFCFC>&nbsp;<font color=orange><?=$dc->get("amount")?> <img src=images/gold.gif></font></td>

</tr>

<tr>

<td colspan=2 bgcolor=F6F6F6><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=right class=little bgColor=FCFCFC height=20>Request Memo:&nbsp;</td>

<td align=left class=little bgColor=FCFCFC>&nbsp;<?=nl2br($dc->get("memo"));?></td>

</tr>

<tr>

<td colspan=2 bgcolor=F6F6F6><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=right class=little bgColor=FCFCFC height=20>Request Date:&nbsp;</td>

<td align=left class=little bgColor=FCFCFC>&nbsp;<?=convertDate("-D of -M, -y at -h:-t:-s", $dc->get("request_date"));?></td>

</tr>

<tr>

<td colspan=2 bgcolor=F6F6F6><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=right class=little bgColor=FCFCFC height=20>Status:&nbsp;</td>

<td align=left class=little bgColor=FCFCFC>&nbsp;<font color=green><?=$dc->get("status")?></font></td>

</tr>

<tr>

<td colspan=2 bgcolor=F6F6F6><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td colspan=2 height=26 align=center><input type=submit value="Accept Request" name=accept class=box> <input type=button name=close value="Close Window" class=box onClick="top.window.close();"></td>

</tr>

<tr>

<td colspan=2 bgcolor=F6F6F6><img src=images/dot.gif width=1 height=1></td>

</tr>

</table>

</form>

<script>

<!--

function contactUs()

   {

      parent.opener.location.href = "contactUs.php";

      top.window.close();

   }

//-->

</script>

<?

         }

      else if ($accept != "Confirm")

         {

            $dollarAmount = $dc->get("index");

            $setDollars   = $dc->get("amount")*$dollarAmount;

            $fee          = $transfer->transferFee($setDollars);

            $totalFees    = ($dollarAmount*$dc->get("amount")) + $fee;

            $totalGold    = sprintf("%.2f", $totalFees/$dollarAmount);



            if ($dc->get("crediGold") <= $totalGold)

               {

?>

<link rel=stylesheet href=modules/styles.css>

<script language=JavaScript src=modules/mod_gen.js></script>

<body bgcolor=white leftmargin=10 topmargin=10 marginwidth=10 marginheight=10>

<br>

<br>

<br>

<p align=center class=head style=color:red>Warning! Insufficient Funds In Account.<br>

<font class=text color=black>It seems that you do not have enought <?=$_Config["masterSign"]?> on your account to complete this transaction. Please fund your account before you continue.</font>

</p>

<script>

<!--

function close()

   {

      setTimeout("parent.opener.location.href='transfer.php?cmd=fund';top.window.close()",5000);

   }

window.onload = close;

//-->

</script>

<?

               }

            else

               {

                  set_session("transact", "true");

                  set_session("getTotal", $totalGold);

                  set_session("getDollars", $setDollars);

                  set_session("trans_fee", $fee);

?>

<link rel=stylesheet href=modules/styles.css>

<script language=JavaScript src=modules/mod_gen.js></script>

<body bgcolor=white leftmargin=10 topmargin=10 marginwidth=0 marginheight=10>



<div align=center class=head>Transfer <?=$_Config["masterSign"]?> to <?=$dc->get("real_name");?>

<br>

<p align=justify class=text style=width:520px>&nbsp;&nbsp;<?=$auth->auth["real_name"]?>, through this page you will transfer <font color=orange><?=$dc->get("amount")?><img src=images/gold.gif></font> to the account of <?=$dc->get("real_name");?>. To confirm this transaction please click on the button "Confirm" which is located at the bottom of the page.

</div>

<form name=view action=view.php method=POST>

<table border=0 width=530 cellspacing=3 cellpadding=0 align=center>

<tr>

<td colspan=2 bgcolor=DFDFDF><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=left class=text colspan=2 bgColor=F9F9F9>&nbsp;<img src=images/point.gif width=9 height=9>&nbsp;<b><?=$_Config["masterSign"]?> Transfer</b></td>

</tr>

<tr>

<td colspan=2 bgcolor=DFDFDF><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=right class=little bgColor=FCFCFC height=20>Payment From:&nbsp;</td>

<td align=left class=little bgColor=FCFCFC>&nbsp;<b><?=$auth->auth["uname"]?></b></td>

</tr>

<tr>

<td colspan=2 bgcolor=F6F6F6><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=right class=little bgColor=FCFCFC height=20>Send To:&nbsp;</td>

<td align=left class=little bgColor=FCFCFC>&nbsp;<b><?=$dc->get("real_name")?></b> (Account Number: <font color=gray><?=$dc->get("request_id")?></font>)</td>

</tr>

<tr>

<td colspan=2 bgcolor=F6F6F6><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=right class=little bgColor=FCFCFC height=20>Memo:&nbsp;</td>

<td align=left class=little bgColor=FCFCFC>&nbsp;<?=nl2br($dc->get("memo"));?></td>

</tr>

<tr>

<td colspan=2 bgcolor=F6F6F6><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=right class=little bgColor=FCFCFC height=20>Amount:&nbsp;</td>

<td align=left class=little bgColor=FCFCFC>&nbsp;<font color=orange><?=$dc->get("amount")?> <img src=images/gold.gif></font></td>

</tr>

<tr>

<td align=right class=little bgColor=FCFCFC height=20>Transaction Fee:&nbsp;</td>

<td align=left class=little bgColor=FCFCFC>&nbsp;$<?=$fee?></td>

</tr>

<tr>

<td colspan=2 bgcolor=D0D0D0><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=right class=little height=22><b>Total:&nbsp;</b></td>

<td align=left class=little><b style=color:orange>&nbsp;<?=$totalGold."</b> <img src=images/gold.gif> <font color=gray> (~ ".sprintf("$%.2f",$totalFees).")</font>"; ?></td>

</tr>

<tr>

<td colspan=2 bgcolor=F6F6F6><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td colspan=2 height=26 align=center><input type=submit name=accept value="Confirm" class=box> <input type=button name=closeFinal value="Close Window" class=box onClick="top.window.close();"></td>

</tr>

<tr>

<td colspan=2 bgcolor=F6F6F6><img src=images/dot.gif width=1 height=1></td>

</tr>

</table>

</form>

<script>

<!--

function contactUs()

   {

      parent.opener.location.href = "contactUs.php";

      top.window.close();

   }

//-->

</script>

<?

               }

         }

      else if (get_session("transact"))

         {

            $trans_ID = $transfer->randomID();

            $ref_ID     = $transfer->randomID();

            $makeDate = date("YmdHis", time());

            $rc->query("UPDATE ".$_Config["database_details"]." SET crediGold=crediGold-".get_session("getTotal")." WHERE user_id='".$auth->auth["uid"]."';");

            $rc->query("UPDATE ".$_Config["database_details"]." SET crediGold=crediGold+".$dc->get("amount")." WHERE user_id='".$dc->get("user_id")."';");

            $rc->query("INSERT INTO ".$_Config["database_transactions"]." SET transaction_id='$trans_ID', amount='".$dc->get("amount")."', sender_id='".$auth->auth["userNumber"]."', recepient_id='".$dc->get("request_id")."', memo='".$dc->get("memo")."', transaction_date='$makeDate', transaction_ip='".getIP()."', transaction_fee='$trans_fee';");

            $rc->query("DELETE FROM ".$_Config["database_requests"]." WHERE id='$view_id';");



         $ref_fee = $transfer->referalFee(get_session("getDollars"));

         // Referral Fees Estimate



         if ( ($dc->get("referrer") || $dc->get("referrer") != "") && $ref_fee>0 )

            {

               $rc->query("INSERT INTO ".$_Config["database_transactions"]." SET transaction_id='$ref_ID', amount='', sender_id='".$dc->get("request_id")."', recepient_id='".$dc->get("referrer")."', memo='Referral Payment By ".$dc->get("real_name")."', transaction_date='$makeDate', transaction_ip='".getIP()."', transaction_fee='0.0', referral_payment='Y', referrra_amount='".$ref_fee."';");

               $rc->query("UPDATE ".$_Config["database_details"]." SET crediGold=crediGold+".$ref_fee." WHERE user_number='".$dc->get("referrer")."';");

            }

?>

<link rel=stylesheet href=modules/styles.css>

<script language=JavaScript src=modules/mod_gen.js></script>

<body bgcolor=white leftmargin=10 topmargin=10 marginwidth=10 marginheight=10>

<br>

<br>

<br>

<p align=center class=head style=color:green>Transfer Completed Successfully!<br>

<font class=text color=black>Your transfer of <font color=orange><?=get_session("getTotal")?></font><img src=images/gold.gif> was successfully sent to <?=$dc->get("real_name")?>.</font>

</p>

<script>

<!--

function close()

   {

      setTimeout("top.window.close()",5000);

   }

window.onload = close;

//-->

</script>

<?

         }

      else

         {

?>

<link rel=stylesheet href=modules/styles.css>

<script language=JavaScript src=modules/mod_gen.js></script>

<body bgcolor=white leftmargin=10 topmargin=10 marginwidth=10 marginheight=10>

<br>

<br>

<br>

<p align=center class=head style=color:red>Warning! You are not authorized to have access!<br>

<font class=text color=black>This <?=$_Config["masterSign"]?> Request has been binded to a different account.</font>

</p>

<script>

<!--

function close()

   {

      setTimeout("top.window.close()",5000);

   }

window.onload = close;

//-->

</script>

<?

         }

   }

else

   {

?>

<link rel=stylesheet href=modules/styles.css>

<script language=JavaScript src=modules/mod_gen.js></script>

<body bgcolor=white leftmargin=10 topmargin=10 marginwidth=10 marginheight=10>

<br>

<br>

<br>

<p align=center class=head style=color:red>Warning! You are not authorized to have access!<br>

<font class=text color=black>This <?=$_Config["masterSign"]?> Request has been binded to a different account.</font>

</p>

<script>

<!--

function close()

   {

      setTimeout("top.window.close()",5000);

   }

window.onload = close;

//-->

</script>

<?

   }

?>