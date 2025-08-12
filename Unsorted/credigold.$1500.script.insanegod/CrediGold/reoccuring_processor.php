<?

/*---------[      Reoccuring Payment Processor for crediGold.com (PHP)      ]--------*/

/*                                                                                   */

/*   This PHP4 script program is written by Infinity Interactive. It could not be,   */

/*  copied, modified and/or reproduced in any form let it be private or public       */

/*  without the appropriate permission of its authors.                               */

/*                                                                                   */

/*  Date    : 05/23/2002                                                             */

/*  Version : 1.0                                                                    */

/*  Authors : Svetlin Staev (svetlin@developer.bg), Kiril Angov (kirokomara@yahoo.com) */

/*                                                                                   */

/*              Copyright(c)2002 Infinity Interactive. All rights reserved.          */

/*-----------------------------------------------------------------------------------*/

include("prepend.php3");



function randomID($length = 20)

   {

      // all the chars we want to use

      $all = explode( " ", "1 2 3 4 5 6 7 8 9");



      for($i=0;$i<$length;$i++)

         {

            srand((double)microtime()*700000000);

            $randy = rand(0, 9);

            $pass .= $all[$randy];

         }

      return $pass;

   }





function transferFee($sum) {

   global $_Config;



   if ($_Config['fee_type'] == "fixed") {

      $ret = $_Config['fee_money'];

   } else {

      $per = ($_Config['fee_money']/100)*$sum;

      if ($_Config['fee_limit'] > 0) {

         if ($per > $_Config['fee_limit'])

            $ret = $_Config['fee_limit'];

         else

            $ret = $per;

      } else

         $ret = $per;

   } // end if/else

 return $ret;

} // end func



function referalFee($sum) {

   global $_Config;



   if ($_Config['affiliate_fee_type'] == "fixed") {

      $ret = $_Config['affiliate_fee_money'];

   } elseif ($_Config['affiliate_fee_type'] == "percent") {

      $per = ($_Config['affiliate_fee_money']/$sum)*100;

      if ($_Config['affiliate_fee_limit'] > 0) {

         if ($per > $_Config['affiliate_fee_limit'])

            $ret = $_Config['affiliate_fee_limit'];

         else

            $ret = $per;

      } else $ret = $per;

   } else {

      for ($i=0;$i<count($_Config['affiliate_fee_range']);$i++) {

         $range = split("-", $_Config['affiliate_fee_range'][$i][0]);

         if ($sum >= $range[0] && $sum <= $range[1]) {

            $ret = $_Config['affiliate_fee_range'][0][1];

            break;

         } // end if

      } // end for

   } // end if/else

 return $ret;

} // end func



$dc->query("SELECT ".$_Config["database_payments"].".*, ".$_Config["database_details"].".crediGold, ".$_Config["database_auth"].".referrer, ".$_Config["database_auth"].".real_name, ".$_Config["database_index"].".`index`

            FROM ".$_Config["database_payments"].", ".$_Config["database_details"].", ".$_Config["database_auth"].", ".$_Config["database_index"]."

            WHERE ".$_Config["database_payments"].".start_interval='$day' AND ".$_Config["database_details"].".user_number=".$_Config["database_payments"].".user_id  AND ".$_Config["database_auth"].".user_number=".$_Config["database_payments"].".recepient_id AND ".$_Config["database_payments"].".status='Running';");



for ($i=0;$i<$dc->num_rows();$i++)

   {

      $dc->next_record();



      $dollarAmount = $dc->get("index");

      $fee          = transferFee($dollarAmount);

      $totalFees    = ($dollarAmount*$dc->get("amount")) + $fee;

      $totalGold    = sprintf("%.2f", $totalFees/$dollarAmount);

      $ref_fee = referalFee($dc->get("amount")*$dollarAmount);

      // Referral Fees Estimate



      if ($dc->get("crediGold") > $totalGold)

         {

            $trans_ID = randomID();

            $ref_ID   = randomID();

            $now      = date("YmdHis", time());

            $rc->query("UPDATE ".$_Config["database_details"]." SET crediGold=crediGold-".$totalGold." WHERE user_number='".$dc->get("user_id")."';");

            $rc->query("UPDATE ".$_Config["database_details"]." SET crediGold=crediGold+".$dc->get("amount")." WHERE user_number='".$dc->get("recepient_id")."';");

            $rc->query("INSERT INTO ".$_Config["database_transactions"]." SET transaction_id='$trans_ID', amount='".$dc->get("amount")."', sender_id='".$dc->get("user_id")."', recepient_id='".$dc->get("recepient_id")."', memo='".$dc->get("memo")."', transaction_date='$now', transaction_ip='0.0.0.0', transaction_fee='$fee';");

            $rc->query("UPDATE ".$_Config["database_payments"]." SET ran=ran+1 WHERE id='".$dc->get("id")."';");

            if ( ($dc->get("referrer") || $dc->get("referrer") != "") && $ref_fee>0 )

               {

                  $rc->query("INSERT INTO ".$_Config["database_transactions"]." SET transaction_id='$ref_ID', amount='', sender_id='".$dc->get("recepient_id")."', recepient_id='".$dc->get("referrer")."', memo='Referral Payment By ".$dc->get("real_name")."', transaction_date='$now', transaction_ip='0.0.0.0', transaction_fee='0.0', referral_payment='Y', referrra_amount='".$ref_fee."';");

                  $rc->query("UPDATE ".$_Config["database_details"]." SET crediGold=crediGold+".$ref_fee." WHERE user_number='".$dc->get("referrer")."';");

               }

         } // end if

   } // end for



exit;

?>

