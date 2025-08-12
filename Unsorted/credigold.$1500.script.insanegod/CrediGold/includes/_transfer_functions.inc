<?php

/*----------------[			crediGold Transfer Functions	      ]---------------*/

/*                                                                                   */

/*   This PHP4 script program is written by Infinity Interactive. It could not be,   */

/*  copied, modified and/or reproduced in any form let it be private or public       */

/*  without the appropriate permission of its authors.                               */

/*                                                                                   */

/*  Date    : 14.5.2002                                                          */

/*  Authors : Svetlin Staev (svetlin@developer.bg), Kiril Angov (kirokomara@yahoo.com) */

/*                                                                                   */

/*              Copyright(c)2002 Infinity Interactive. All rights reserved.          */

/*-----------------------------------------------------------------------------------*/



class Credigold_Transfer {



  var $classname      = "Credigold_Transfer";

  var $magic          = "theWebMonster...";  ## Challenge seed



  var $persistent_slots = array("transfer", "user_ids");

  var $transfer = array();            ## Data array

  var $user_ids = array();

  var $user_details = array();



###

#	Transaction ID Function

###################################

function randomID($length = 20) {



   // all the chars we want to use

   $all = explode( " ", "1 2 3 4 5 6 7 8 9");



   for($i=0;$i<$length;$i++) {

      srand((double)microtime()*700000000);

      $randy = rand(0, 9);

      $pass .= $all[$randy];

   } // end for



 return $pass;

} // end func



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





###

#	Send crediGold Function

###################################

function send_gold()

   {

   global $auth, $sess, $HTTP_REFERER, $s, $user_ids, $_Config, $dc, $indexPrice;



   $this->transfer["recipients"] = array();



      $auth->db->query(sprintf("SELECT u.crediGold, u.sp_made, c.`index` FROM %s u, %s c WHERE u.user_id='%s';",

                                 $auth->database_details,

                                 $_Config["database_index"],

                                 $auth->auth["uid"] ));



      $auth->db->next_record();

      $this->transfer["sp_made"] = $auth->db->get("sp_made");

      $this->transfer["balance"] = sprintf("%.2f", $auth->db->get("crediGold"));

      $this->transfer["dollars"] = $auth->db->get("index");



      if ( get_param("process_payment") && !get_param("record_payment") )

         {

            $sess->unregister("s");

            $this->transfer["recipients"]["username"] = split(",", get_param("to"));



            for ($i=0;$i<count($this->transfer["recipients"]["username"]);$i++)

               {

                  if ( strlen(trim($this->transfer["recipients"]["username"][$i])) == 7 && is_number(trim($this->transfer["recipients"]["username"][$i])) )

                     {

                        $rules .= ((strlen($rules) == 0)?"":"OR")." ( u.user_number='".trim($this->transfer["recipients"]["username"][$i])."' AND u.user_id=c.user_id ) ";

                     }

                  else

                     {

                        $rules .= ((strlen($rules) == 0)?"":"OR")." ( u.username='".trim($this->transfer["recipients"]["username"][$i])."' AND u.user_id=c.user_id ) ";

                     }

               } // end for

            $auth->db->query(sprintf("SELECT u.user_id, u.username, u.real_name, u.user_number FROM %s u, %s c WHERE  %s",

                                             $auth->database_table,

                                             $auth->database_details,

                                             $rules ));

            $k=0;

            while( $auth->db->next_record() )

               {

                  $this->user_names[$k] = $auth->db->get("username");

                  $this->user_ids[$k] = $auth->db->get("user_number");

                  $this->transfer["recipients"]["real_name"][$k] = $auth->db->get("real_name");



               $k++;

               } // while



            for($i=0;$i<count($this->transfer["recipients"]["username"]);$i++)

               {

                  if (trim($this->transfer["recipients"]["username"][$i]) == $auth->auth["username"] || trim($this->transfer["recipients"]["username"][$i]) == $auth->auth["userNumber"] )

                        {

                           set_session("send_error", "Invalid recipient(s) entered!"); $this->transfer["errora"] = "to_field::";

                           $this->transfer["sub_error"] = "You cannot send money to your current account: <b>".$this->transfer["recipients"]["username"][$i]."</b>";

                        }

               $true[$i] = false;

               for($k=0;$k<count($this->transfer["recipients"]["username"]);$k++)

                  {

                  if (

                     trim($this->transfer["recipients"]["username"][$i]) == $this->user_names[$k]

                     || trim($this->transfer["recipients"]["username"][$i]) == $this->user_ids[$k]

                     ) $true[$i] = true;

                  } // end for

               if (!$true[$i]) $unknown_recepients .= $this->transfer["recipients"]["username"][$i]." :: ";

               } // end for



			$estimateCosts = (get_param("amount")*count($this->transfer["recipients"]["username"])) + ($this->transferFee($this->transfer["dollarAmount"])*count($this->transfer["recipients"]["username"])/$indexPrice);



            if ( $unknown_recepients || $this->transfer["errora"] == "to_field::" )	{	set_session("send_error", "Invalid recipient(s) entered!"); if (!$this->transfer["sub_error"]) $this->transfer["sub_error"] = $unknown_recepients;	}

            else if ( $this->transfer["balance"] <= $estimateCosts )

               {

                  $this->transfer["errora"] = "amount::";

                  set_session("send_error", "Insufficient funds to make the payment!");

                  $this->transfer["sub_error"] = "To send ".get_param("amount")." you will need to fund your account with sufficient funds!";

               }

            else if (!get_param("memo") || trim(get_param("memo")) == "" )

               {

                  $this->transfer["errora"] = "memo::";

                  set_session("send_error", "No memo value entered!");

                  $this->transfer["sub_error"] = "You must include a small desription of the transfer!";

               }

            else { $second_stage = true; }



         } // if



      // This is the Confirmation Section

      if ( $second_stage )

         {



            $this->transfer["amount"] = get_param("amount");

            $this->transfer["memo"] = get_param("memo");

            $this->transfer["newAmount"]    = ( count($this->transfer["recipients"]["username"]) > 1 )?( get_param("amount")*count($this->transfer["recipients"]["username"]) ):get_param("amount");

            $this->transfer["dollarAmount"] = ($this->transfer["dollars"]*$this->transfer["newAmount"]);

            $this->transfer["realAmount"]   = " <img src=images/gold.gif> </b><font color=gray>(~ $".sprintf("%.2f",$this->transfer["dollarAmount"]).")</font>";



            for ($i=0;$i<count($this->transfer["recipients"]["username"]);$i++)

	            {

		           $toResolved   .= "<b>".$this->user_ids[$i]."</b> (".$this->transfer["recipients"]["real_name"][$i].")<br>";

			    }

            $this->transfer["fee"] = $this->transferFee($this->transfer["dollarAmount"]);

            $this->transfer["fee"] *= count($this->transfer["recipients"]["username"]);

            $totalFees = $this->transfer["dollarAmount"] + $this->transfer["fee"];

            $this->transfer["totalInGold"] = sprintf("%.2f", $totalFees/$this->transfer["dollars"]);

         } // if



      if ( get_param("record_payment") )

         {

            if (!isset($s)) { $s=0; } else { $this->send_refresh_warning(); exit; };

            $sess->register("s");



            for ($i=0;$i<count($this->user_ids);$i++)

               {

                  $rules .= ((strlen($rules) == 0)?"":"OR")." ( u.user_number='".$this->user_ids[$i]."' AND  u.user_id=c.user_id ) ";

               }



            $auth->db->query(sprintf("SELECT u.user_id, u.username, u.real_name, u.user_number, u.referrer, c.sp_received FROM %s u, %s c WHERE  %s",

                                             $auth->database_table,

                                             $auth->database_details,

                                             $rules ));



            $i=0;

            while($auth->db->next_record())

               {

                  $this->user_details["email"][$i] = $auth->db->get("username");

                  $this->user_details["name"][$i] = $auth->db->get("real_name");

                  $this->user_details["referrer"][$i] = $auth->db->get("referrer");

                  $this->user_details["sp_received"][$auth->db->get("user_number")] = $auth->db->get("sp_received");



               $i++;

               } // while



	         $ref_fee = $this->referalFee($this->transfer["dollarAmount"]);



			// Referral Fees Estimate

            for ($i=0;$i<count($this->user_ids);$i++)

               {

                  $challengea = $this->randomID();

                  $ref_ID        = $this->randomID();

                  $now = date("YmdHis", time());



                  $auth->db->query(sprintf("INSERT INTO %s SET transaction_id='%s', amount='%s', sender_id='%s', recepient_id='%s',"

                                             ." memo='%s', transaction_date='%s', transaction_ip='%s', transaction_fee='%s';",

                                          $_Config["database_transactions"],

                                          $challengea,

                                          $this->transfer["amount"],

                                          $auth->auth["userNumber"],

                                          $this->user_ids[$i],

                                          $this->transfer["memo"],

                                          $now,

                                          getIP(),

                                          ($this->transfer["fee"]/count($this->user_ids)) ));



                   $auth->db->query("UPDATE ".$auth->database_details." SET crediGold=crediGold+".$this->transfer["amount"]." WHERE user_number='".$this->user_ids[$i]."';");



                  if ( ($this->user_details["referrer"][$i] != "" || $this->user_details["referrer"][$i]) && $ref_fee>0 )

                     {

                        $auth->db->query("UPDATE ".$_Config["database_details"]." SET crediGold=crediGold+".$ref_fee." WHERE user_number='".$this->user_details["referrer"][$i]."';");

                        $auth->db->query("INSERT INTO ".$_Config["database_transactions"]." SET transaction_id='$ref_ID', amount='', sender_id='".$this->user_ids[$i]."', recepient_id='".$this->user_details["referrer"][$i]."', memo='Referral Payment By ".$this->user_details["name"][$i]."', transaction_date='$now', transaction_ip='".getIP()."', transaction_fee='0.0', referral_payment='Y', referrra_amount='".$ref_fee."';");

                     } // if



                  if ( $this->user_details["sp_received"][$this->user_ids[$i]] == "Y" )

                     {

                        // The Mail Function goes here



                           $dc->query("SELECT body, subject FROM ".$_Config["database_emails"]." WHERE id='6';");

                           $dc->next_record();

                           $email_body = $dc->get("body");

                           $email_subj = $dc->get("subject");



                           $email_body = eregi_replace("%name%", $this->user_details["name"][$i], $email_body);

                           $email_body = eregi_replace("%siteName%", $_Config["masterRef"], $email_body);

                           $email_body = eregi_replace("%amount%", $this->transfer["amount"], $email_body);

                           $email_body = eregi_replace("%account%", $this->user_ids[$i], $email_body);

                           $email_body = eregi_replace("%view_online%", $Config["http_path"]."/transfer.php?cmd=history&tid=".$challengea, $email_body);

                           $email_body = eregi_replace("%sender_name%", $auth->auth["real_name"], $email_body);

                           $email_body = eregi_replace("%transaction_id%", $challengea, $email_body);

                           $email_body = eregi_replace("%memo%", $this->transfer["memo"], $email_body);

                           $email_body = eregi_replace("%date%", convertDate("-D of -M, -y at -h:-t:-s", $now), $email_body);





                           mailTO($this->user_details["email"][$i], $email_subj, $email_body);



                        // The Mail Function ends here

                     } // if



               }



            $auth->db->query("UPDATE ".$auth->database_details." SET crediGold=crediGold-".$this->transfer["totalInGold"]." WHERE user_id='".$auth->auth["uid"]."';");



            if ($this->transfer["sp_made"] == 'Y')

               {

                  // The Mail Function goes here



                     $dc->query("SELECT body, subject FROM ".$_Config["database_emails"]." WHERE id='7';");

                     $dc->next_record();

                     $email_body = $dc->get("body");

                     $email_subj = $dc->get("subject");



                     $email_body = eregi_replace("%name%", $auth->auth["real_name"], $email_body);

                     $email_body = eregi_replace("%siteName%", $_Config["masterRef"], $email_body);

                     $email_body = eregi_replace("%amount%", $this->transfer["amount"], $email_body);

                     $email_body = eregi_replace("%view_online%", $Config["http_path"]."/transfer.php?cmd=history&tid=".$challengea, $email_body);

                     $email_body = eregi_replace("%transaction_id%", $challengea, $email_body);

                     $email_body = eregi_replace("%memo%", $this->transfer["memo"], $email_body);

                     $email_body = eregi_replace("%date%", convertDate("-D of -M, -y at -h:-t:-s", $now), $email_body);



                     mailTO($auth->auth["username"], $email_subj, $email_body);



                  // The Mail Function ends here

               } // if





         include("ihtml/send_gold_success.ihtml");

            unset($this->transfer);

            unset($this->user_ids);

         page_close();

         endPage();

         exit;

         }



       include("ihtml/send_gold.ihtml");

   unset($this->transfer["errora"]);

   unset($this->transfer["sub_error"]);

   }



###

#	Send Refresh Warning

###################################

function send_refresh_warning()

   {

	global $_Config;

      include("ihtml/send_refresh_warning.ihtml");

      endPage();

      page_close();



   exit;

   }



###

#	Request crediGold Function

###################################

function request_gold()

   {

   global $auth, $sess, $HTTP_REFERER, $s, $user_ids, $_Config;



      $this->transfer["recipients"] = array();



      $auth->db->query(sprintf("SELECT u.crediGold, c.`index` FROM %s u, %s c WHERE u.user_id='%s';",

                                 $auth->database_details,

                                 $_Config["database_index"],

                                 $auth->auth["uid"] ));



      $auth->db->next_record();

      $this->transfer["balance"] = sprintf("%.2f", $auth->db->get("crediGold"));

      $this->transfer["dollars"] = $auth->db->get("index");



      if ( get_param("process_request") && !get_param("record_request") )

         {

            $sess->unregister("s");

            $this->transfer["recipients"]["username"] = split(",", get_param("to"));



            for ($i=0;$i<count($this->transfer["recipients"]["username"]);$i++)

               {

                  if ( strlen(trim($this->transfer["recipients"]["username"][$i])) == 7 && is_number(trim($this->transfer["recipients"]["username"][$i])) )

                     {

                        $rules .= ((strlen($rules) == 0)?"":"OR")." ( u.user_number='".trim($this->transfer["recipients"]["username"][$i])."' AND u.user_id=c.user_id ) ";

                     }

                  else

                     {

                        $rules .= ((strlen($rules) == 0)?"":"OR")." ( u.username='".trim($this->transfer["recipients"]["username"][$i])."' AND u.user_id=c.user_id ) ";

                     }

               }

            $auth->db->query(sprintf("SELECT u.user_id, u.username, u.real_name, u.user_number FROM %s u, %s c WHERE  %s",

                                             $auth->database_table,

                                             $auth->database_details,

                                             $rules ));

            $k=0;

            while( $auth->db->next_record() )

               {

                  $this->user_names[$k] = $auth->db->get("username");

                  $this->user_ids[$k] = $auth->db->get("user_number");

                  $this->transfer["recipients"]["real_name"][$k] = $auth->db->get("real_name");



               $k++;

               } // while



            for($i=0;$i<count($this->transfer["recipients"]["username"]);$i++)

               {

                  if (trim($this->transfer["recipients"]["username"][$i]) == $auth->auth["username"] || trim($this->transfer["recipients"]["username"][$i]) == $auth->auth["userNumber"] )

                        {

                           set_session("request_error", "Invalid recipient(s) entered!"); $this->transfer["errora"] = "to_field::";

                           $this->transfer["sub_error"] = "You cannot send money to your current account: <b>".$this->transfer["recipients"]["username"][$i]."</b>";

                        }

               $true[$i] = false;

               for($k=0;$k<count($this->transfer["recipients"]["username"]);$k++)

                  {

                  if (

                     trim($this->transfer["recipients"]["username"][$i]) == $this->user_names[$k]

                     || trim($this->transfer["recipients"]["username"][$i]) == $this->user_ids[$k]

                     ) $true[$i] = true;

                  } // for

               if (!$true[$i]) $unknown_recepients .= $this->transfer["recipients"]["username"][$i]." :: ";

               } // for



            if ( $unknown_recepients || $this->transfer["errora"] == "to_field::" )	{	set_session("request_error", "Invalid recipient(s) entered!"); if (!$this->transfer["sub_error"]) $this->transfer["sub_error"] = $unknown_recepients;	}

            else if (!get_param("memo") || trim(get_param("memo")) == "" )

               {

                  $this->transfer["errora"] = "memo::";

                  set_session("request_error", "No memo value entered!");

                  $this->transfer["sub_error"] = "You must include a small desription of the request!";

               }

            else { $second_stage = true; }



         } // if



      // This is the Confirmation Section

      if ( $second_stage )

         {

            $this->transfer["amount"] = get_param("amount");

            $this->transfer["memo"] = get_param("memo");



            $this->transfer["newAmount"]    = ( count($this->transfer["recipients"]["username"]) > 1 )?( get_param("amount")*count($this->transfer["recipients"]["username"]) ):get_param("amount");

            $this->transfer["dollarAmount"] = ($this->transfer["dollars"]*$this->transfer["newAmount"]);

            $this->transfer["realAmount"]   = " <img src=images/gold.gif> </b><font color=gray>(~ $".sprintf("%.2f",$this->transfer["dollarAmount"]).")</font>";



            for ($i=0;$i<count($this->transfer["recipients"]["username"]);$i++)

            {

               $toResolved   .= "&nbsp;<b>".$this->user_ids[$i]."</b> (".$this->transfer["recipients"]["real_name"][$i].")<br>";

            }



            $this->transfer["totalInGold"] = $this->transfer["dollars"];

         } // if



      if ( get_param("record_request") )

         {

            if (!isset($s)) { $s=0; } else { $this->request_refresh_warning(); exit; };

            $sess->register("s");



            for ($i=0;$i<count($this->user_ids);$i++)

               {

                  $challengea = $this->randomID();

                  $now = date("YmdHis", time());

                  $this->user_ids[$i] = (substr($this->user_ids[$i],0,1) == " ")?ltrim($this->user_ids[$i]):$this->user_ids[$i];



                  $auth->db->query(sprintf("INSERT INTO %s SET request_id='%s', target_id='%s', amount='%s', memo='%s', status='Pending', request_date='%s', request_ip='%s';",

                                          $_Config["database_requests"],

                                          $auth->auth["userNumber"],

                                          $this->user_ids[$i],

                                          $this->transfer["amount"],

                                          $this->transfer["memo"],

                                          $now,

                                          getIP() ));

               }

		

         include("ihtml/request_gold_success.ihtml");

         unset($this->transfer);

         unset($this->user_ids);

         page_close();

         endPage();

         exit;

         }



   include("ihtml/request_gold.ihtml");

   unset($this->transfer["errora"]);

   unset($this->transfer["sub_error"]);

   }



###

#	Send Refresh Warning

###################################

function request_refresh_warning()

   {

      include("ihtml/request_refresh_warning.ihtml");

      endPage();

      page_close();



   exit;

   }



###

#	Fund/Withdraw Account Function

###################################

function fund()

   {

		global $auth, $sess, $HTTP_REFERER, $s, $user_ids, $_Config, $dc, $rc, $id, $indexPrice;

		$auth->db->query(sprintf("SELECT u.crediGold, u.sp_made, c.`index` FROM %s u, %s c WHERE u.user_id='%s';",

						 $auth->database_details,

						 $_Config["database_index"],

						 $auth->auth["uid"] ));



		$auth->db->next_record();

		$this->transfer["sp_made"] = $auth->db->get("sp_made");

		$this->transfer["balance"] = sprintf("%.2f", $auth->db->get("crediGold"));

		$this->transfer["dollars"] = $auth->db->get("index");



		if (get_param("id"))

			{

				$dc->query("SELECT * FROM ".$_Config["database_fund"]." WHERE id='".get_param("id")."';");

				$dc->next_record();

				$funding[name]     = $dc->get("name");

				$funding[addition] = $dc->get("addition");



				$m = 0;

				preg_match_all ("/\[\"(.*?)\", \"(.*?)\", \"(.*?)\"\]/i", $dc->get("add_names"), $fields_match);

				for ($i=0;$i<count($fields_match[0]);$i++)

					{

						$funding[extras][$m][name]   = $fields_match[1][$i];

						$funding[extras][$m][value]  = $fields_match[2][$i];

						$funding[extras][$m][desc]   = $fields_match[3][$i];

						$m++;

					}



				for ($i=0;$i<count($funding[extras]);$i++)

					{

						$opt .= "<tr>";

						$opt .= "<td align=right height=23 class=little width=27%>".$funding[extras][$i][desc].":&nbsp;</td>\n";

						$opt .= "<td align=left class=little width=73%>&nbsp;<input type=text name=\"".$funding[extras][$i][name]."\" value=\"".$this->changeValue($funding[extras][$i][value])."\" size=30 class=note></td>\n";

						$opt .= "</tr>\n";

						$opt .= "<tr>\n";

						$opt .= "<td colspan=2 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>\n";

						$opt .= "</tr>\n";

					}

				$funding[extraFields] = $opt;



			    include("ihtml/fund_processor.ihtml");			

			}		

		else

			{

				$funding = array();

				$m       = 0;

				$dc->query("SELECT * FROM ".$_Config["database_fund"].";");

				for ($i=0;$i<$dc->num_rows();$i++)

					{

						$dc->next_record();

						$funding[$m][fee]         = ($dc->get("paid") == "Y")?$dc->get("fee"):"<font color=green><b>FREE</b></font>";

						$funding[$m][actions]     = ($dc->get("image"))?"<a href='transfer.php?cmd=fund&id=".$dc->get("id")."'><img src='".$dc->get("image")."' border=0 alt=\"Pay Using ".$dc->get("name")."\"></a>":("<input type=button name=button".$i." value='Fund With ".$dc->get("name")."' onClick=\"window.location.href='transfer.php?cmd=fund&id=".$dc->get("id")."'\" class=note>");

						$funding[$m][description] = "<b>".$dc->get("name")."</b>".(($dc->get("description"))?("<br>".$dc->get("description")):"");

						$m++;

					}

			   include("ihtml/fund.ihtml");

			}

   }

function changeValue($which)

	{

		global $auth, $_Config, $rc;

		$rc->query("SELECT address1, city, state, zip FROM ".$_Config["database_details"]." WHERE user_id='".$auth->auth["uid"]."';");

		$rc->next_record();

		$ch = eregi_replace("%email%", $auth->auth["uname"], $which);

		$ch = eregi_replace("%name%", $auth->auth["real_name"], $ch);

		$ch = eregi_replace("%address%", $rc->get("address1"), $ch);

		$ch = eregi_replace("%city%", $rc->get("city"), $ch);

		$ch = eregi_replace("%state%", $rc->get("state"), $ch);

		$ch = eregi_replace("%zip%", $rc->get("zip"), $ch);

		return $ch;

	}

function withdraw()

   {

		global $auth, $sess, $HTTP_REFERER, $s, $user_ids, $_Config, $dc, $rc;

		$auth->db->query(sprintf("SELECT u.crediGold, u.sp_made, c.`index` FROM %s u, %s c WHERE u.user_id='%s';",

						 $auth->database_details,

						 $_Config["database_index"],

						 $auth->auth["uid"] ));



		$auth->db->next_record();

		$this->transfer["sp_made"] = $auth->db->get("sp_made");

		$this->transfer["balance"] = sprintf("%.2f", $auth->db->get("crediGold"));

		$this->transfer["dollars"] = $auth->db->get("index");



		if (get_param("amount"))

	   		{

				$dc->query("INSERT INTO ".$_Config["database_withdrawals"]." SET id='', user_number='".$auth->auth["userNumber"]."', amount='".get_param("amount")."', method='".get_param("withdraw_type")."', memo='".get_param("memo")."', dateIt='".time()."';");

	   			include("ihtml/withdraw_success.ihtml");

			}

		else

			{

			   $withdrawTypes = "<select name=withdraw_type onChange=\"getNote(notes[this.options[this.selectedIndex].value])\">\n";

			   $dc->query("SELECT * FROM ".$_Config["database_wmethods"]." WHERE status='On';");

			   for ($i=0;$i<$dc->num_rows();$i++)

				{

				   $dc->next_record();

				   $rules          = ($dc->get("paid") == "Y")?("(Cost: ".$dc->get("fee").")"):"(Cost: FREE)";

				   $withdrawTypes .= ($i == 0)?"<option value='".$dc->get("id")."'>Select a method to withdraw your money</option>\n":"";

				   $withdrawTypes .= "<option value='".$dc->get("id")."'>".$dc->get("name")." $rules</option>\n";

				   $withdrawNotes .= ", ";

				   $withdrawNotes .= ($dc->get("comments"))?"'".addslashes(eregi_replace("\n", "<br />", eregi_replace("[\f\r\t]*", "", $dc->get("comments"))))."'":"'No instructions associated with this method!'";

				}

			   $withdrawTypes .= "</select>\n";



			   include("ihtml/withdraw.ihtml");

			}

   }

###

#	Transaction History Function

###################################

function history()

   {

	   global $auth, $sess, $HTTP_REFERER, $s, $user_ids, $_Config;

       include("ihtml/history.ihtml");

   }



function send_balance()

   {

	   global $auth, $sess, $HTTP_REFERER, $s, $user_ids, $_Config;

	   include("ihtml/transfer_main.ihtml");

   }

} // CrediGold_Transfer class ends here



?>

