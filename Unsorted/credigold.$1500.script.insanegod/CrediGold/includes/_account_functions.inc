<?php

/*----------------[			crediGold Account Functions	      ]---------------*/

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



class Credigold_Account {





###

#	Edit User Account

###################################

function edit_account()

   {

   global $auth, $_Config;



   if (get_param("submission"))

      {

      if (!get_param("secret_answer") || get_param("secret_answer") == "" || strlen(get_param("secret_answer")) < 5)

         {

            $auth->auth["edit_errora"] = "secret::";

            set_session("edit_error", "Invalid secret answer entered!");

            set_session("sub_edit_error", "The secret answer is used for various admin account features!");

         }

      elseif (!get_param("realname") || get_param("realname") == "")

         {

            $auth->auth["edit_errora"] = "realname::";

            set_session("edit_error", "Registration Error! Please, review the items in red.");

            set_session("sub_edit_error", "All fields with a red circle must be filled correctly!");

         }

      elseif (

               (!get_param("location1") || get_param("location1") == "") ||

               (!get_param("city") || get_param("city") == "") ||

               (!get_param("zip_code") || get_param("zip_code") == "") ||

               (!get_param("country") || get_param("country") == "") ||

               ( get_param("icq") && !is_number(get_param("icq")) ) ||

               ( get_param("referrer") && !is_number(get_param("referrer")) )

                                                                              )

         {

            set_session("edit_error", "Registration Error! Please, review the items in red.");

            set_session("sub_edit_error", "All fields with a red circle must be filled correctly!");

            if (!get_param("location1") || get_param("location1") == "") $auth->auth["edit_errora"] .= "location::";

            if (!get_param("city") || get_param("city") == "") $auth->auth["edit_errora"] .= "city::";

            if (!get_param("zip_code") || get_param("zip_code") == "") $auth->auth["edit_errora"] .= "zip_code::";

            if (!get_param("country") || get_param("country") == "") $auth->auth["edit_errora"] .= "country";

            if ( get_param("icq") && !is_number(get_param("icq")) ) $auth->auth["edit_errora"] .= "icq::";

            if ( get_param("referrer") && !is_number(get_param("referrer"))) $auth->auth["edit_errora"] .= "referrer";

         }

      else

         {

            $auth->db->query(sprintf("UPDATE %s SET real_name='%s' WHERE user_id='%s'",

                                    $auth->database_table,

                                    get_param("realname"),

                                    $auth->auth["uid"] ));



            $auth->db->query(sprintf("UPDATE %s SET question='%s', answer='%s', sp_made='%s', sp_received='%s'"

                                    .", address1='%s', address2='%s', city='%s', zip='%s', state='%s', country='%s', phone='%s', fax='%s'"

                                    .", site='%s', yahoo='%s', icq='%s', aim='%s', msn='%s', newsletter='%s' WHERE user_id='%s'",

                                    $auth->database_details, get_param("secret_question"), get_param("secret_answer"),

                                    get_param("sp_made"), get_param("sp_received"), get_param("location1"), get_param("location2"),

                                    get_param("city"), get_param("zip_code"), get_param("state"), get_param("country"), get_param("phone"),

                                    get_param("fax"), get_param("site"), get_param("yahoo"), get_param("icq"), get_param("aim"),

                                    get_param("msn"), get_param("newsletter"), $auth->auth["uid"] ));



      set_session("edit_success", "Account Edited Successfully!");

      set_session("sub_edit_success", "All changes you have made to your account have been saved!");

         }

      }



      $auth->db->query(sprintf("SELECT username, referrer, real_name FROM %s WHERE user_id='%s'",

                                 $auth->database_table,

                                 $auth->auth["uid"]));



      $auth->db->next_record();

      $usernamea = $auth->db->f("username");

      $referrera = $auth->db->f("referrer");

      $realnamea = $auth->db->f("real_name");



      $auth->db->query(sprintf("SELECT real_name FROM %s WHERE user_number='%s'", $auth->database_table, $referrera));

      $auth->db->next_record();

      $referrer_name = $auth->db->f("real_name");



      $auth->db->query(sprintf("SELECT * FROM %s WHERE user_id='%s'",

                                 $auth->database_details,

                                 $auth->auth["uid"]));

      $auth->db->next_record();



       include("ihtml/edit_account.ihtml");

      unset($auth->auth["edit_errora"]);

   }



###

#	Account IP Blocking

###################################

function ip_blocking()

   {

   global $auth, $_Config;



   $old_ips = "";



      if (get_param("submission"))

         {

            if (get_param("delete_ip") || get_param("delete_ip") == "0")

               {

                  $ips = split("::", get_param("all_ips"));

                  for ($i=0;$i<count($ips);$i++)

                     {

                        if ($i != get_param("delete_ip") && $ips[$i]) $new_string .= $ips[$i]."::";

                     }

                  $auth->db->query(sprintf("UPDATE %s SET ip_blocking='%s' WHERE user_id='%s'",

                                       $auth->database_table,

                                       $new_string,

                                       $auth->auth["uid"] ));

               }

            else if (

                     (get_param("new1") || get_param("new1") == "0") &&

                     (get_param("new2") || get_param("new2") == "0") &&

                     (get_param("new3") || get_param("new3") == "0") &&

                     (get_param("new4") || get_param("new4") == "0")

                    )

               {

               if ( !is_number(get_param("new1")) && !eregi("\*", get_param("new1")) ) { $errora .= "new1::"; }

               if ( !is_number(get_param("new2")) && !eregi("\*", get_param("new2")) ) { $errora .= "new2::"; }

               if ( !is_number(get_param("new3")) && !eregi("\*", get_param("new3")) ) { $errora .= "new3::"; }

               if ( !is_number(get_param("new4")) && !eregi("\*", get_param("new4")) ) { $errora .= "new4::"; }

               if (!$errora)

                  {

                     $new_ip = get_param("new1").".".get_param("new2").".".get_param("new3").".".get_param("new4");

                     for ($i=0;$i<get_param("ip_count");$i++)

                        {

                           for ($k=0;$k<4;$k++)

                              {

                                 if ( !is_number(get_param("ip".$i.$k)) && !eregi("\*", get_param("ip".$i.$k)) ) { $errora .= "ip".$i.$k."::"; }

                                 $old_ips .= get_param("ip".$i.$k);

                                 if ($k != 3) $old_ips .= ".";

                              }

                           $old_ips .= "::";

                        }

                     $new_string = $old_ips.$new_ip."::";

                     if (!$errora)

                        {

                           $auth->db->query(sprintf("UPDATE %s SET ip_blocking='%s' WHERE user_id='%s'",

                                          $auth->database_table,

                                          $new_string,

                                          $auth->auth["uid"] ));

                           set_session("ip_success", "IP List Updated Successfully!");

                           set_session("sub_ip_success", "The new IP has been recorded or the old list is updated successfully!");

                        }

                  }

               }

            else

               {

                     for ($i=0;$i<get_param("ip_count");$i++)

                        {

                           for ($k=0;$k<4;$k++)

                              {

                                 if ( !is_number(get_param("ip".$i.$k)) && !eregi("\*", get_param("ip".$i.$k)) ) { $errora .= "ip".$i.$k."::"; }

                                 $old_ips .= get_param("ip".$i.$k);

                                 if ($k != 3) $old_ips .= ".";

                              }

                           $old_ips .= "::";

                        }

                     if (!$errora)

                        {

                           $auth->db->query(sprintf("UPDATE %s SET ip_blocking='%s' WHERE user_id='%s'",

                                          $auth->database_table,

                                          $old_ips,

                                          $auth->auth["uid"] ));

                           set_session("ip_success", "IP List Updated Successfully!");

                           set_session("sub_ip_success", "The new IP has been recorded or the old list is updated successfully!");

                        }

               }

         }



      $ip_html = "";

      $ips = "";



      $auth->db->query(sprintf("SELECT ip_blocking FROM %s WHERE user_id='%s'", $auth->database_table, $auth->auth["uid"]));

      $auth->db->next_record();

      $p_all_ips =  $auth->db->f("ip_blocking");



      if ($auth->db->nf() == 0) { $ip_html = "<font color=red>No IPs restricted yet!</font>"; }

      else {

         $ips = split("::", $auth->db->f("ip_blocking"));

         for ($i=0;$i<(count($ips) -1);$i++)

            {

               $ip_html .= "<tr>\n"

                           ."<td width=7% bgColor=F9F9F9 align=center class=text>".($i + 1).".</td>\n"

                           ."<td width=86% class=text bgColor=white align=center>\n";



               $temp_ips = split("\.", $ips[$i]);

               for ($k=0;$k<count($temp_ips);$k++)

                  {

                     $valueto = ($errora)?get_param("ip".$i.$k):$temp_ips[$k];

                     $ip_html .= '<input type="text" name="ip'.$i.$k.'" class="note" size="3" maxlength="3" value="'.$valueto.'" style="text-align:center;';

                     if (eregi("ip".$i.$k, $errora)) { $ip_html .= 'color:red;">'; } else { $ip_html .= '">'; }

                     if ($k != (count($temp_ips) - 1)) $ip_html .= " . \n";

                  }

               $ip_html .= "<td width=7% bgColor=F9F9F9 align=center class=text><a href=\"javascript:delete_ip('".$i."')\" title=\"Delete this ip!\">&nbsp;X&nbsp;</a></td>\n"

                           ."<tr>\n"

                           ."<td colspan=3 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>\n"

                           ."</tr>\n";

            }

      }

      if ($errora)

         {

            set_session("ip_error", "Error Occurred! Please, review the items in red!");

            set_session("sub_ip_error", "IPs must be only numbers and \"*\". No letters are allowed!");

         }

       include("ihtml/ip_blocking.ihtml");

      unset($errora);

   }



###

#	New Account Password

###################################

function new_password()

   {

   global $challenge, $auth, $_Config, $sess;



      $auth->db->query(sprintf("SELECT u.password, u.lock, u.username, d.question, d.answer ".

                                    "FROM %s u, %s d WHERE u.user_id = '%s' AND d.user_id='%s' ",

                                     $auth->database_table,

                                     $auth->database_details,

                                     $auth->auth["uid"],

                                     $auth->auth["uid"]));



      while($auth->db->next_record()) {

        $pass  = $auth->db->f("password");   ## Password is stored as a md5 hash

        $state = $auth->db->f("lock");

        $s_question = $auth->db->f("question");

        $s_answer = $auth->db->f("answer");

        $emaila = $auth->db->f("username");

      }



   if (get_param("change_pass_process"))

      {

         if (!isset($challenge)) $challenge = md5(uniqid($auth->magic));



         $expected_response = md5("$s_question:$s_answer:$pass:$challenge");



         ## True when JS is disabled

         if (get_param("response") == "") {

           if (md5(get_param("password")) != $pass && get_param("secret_answer") != $s_answer && get_param("secret_question") != $s_question) {       ## md5 hash for non-JavaScript browsers

          set_session("passwd_error", "Account Information Incorrect!");

          set_session("sub_passwd_error", "Some of the requested fields Password or Secret Question/Answer is incorrect!");

           } else {



           if (get_param("response2") == "") {

            if(get_param("new_passwd") && get_param("re_new_passwd")) {

               $passworda = md5(get_param("passwd"));

               $repassword = md5(get_param("repasswd"));

              }

           else {

               $passworda = "";

               $repassword = "";

              }

            }

         else {

              $hashpass = split("::", get_param("response2"));

              $passworda = $hashpass[0];

              $repassword = $hashpass[1];

            }



         if ($passworda != $repassword)

            {

               set_session("passwd_error", "The two passwords do not match!");

               set_session("sub_passwd_error", "The new password should be repeated as in the first field for verification!");

            }

         else if ($passworda == "" || $repassword == "")

            {

               set_session("passwd_error", "Password Error! Please, enter a valid password.");

               set_session("sub_passwd_error", "Enter a valid new password at least 6 chars long!");

            }

         else {

                  $auth->db->query(sprintf("UPDATE %s SET password='%s' WHERE user_id='%s' ",

                                                $auth->database_table,

                                                $passworda,

                                                $auth->auth["uid"] ));

                  set_session("passwd_success", "Password Successfully Changed!");

                  set_session("sub_passwd_success", "From now on you should use the new password you just enterd!");



               // The Mail Function goes here

                  $auth->db->query("SELECT body, subject FROM ".$_Config["database_emails"]." WHERE id='3'");

                  $auth->db->next_record();

                  $email_body = $auth->db->f("body");

                  $email_subj = $auth->db->f("subject");



                  $newBodyExtract = eregi_replace("%name%", $auth->auth["real_name"], $email_body);

                  $newBodyExtract = eregi_replace("%password%", get_param("re_new_passwd"), $newBodyExtract);

                  $newBodyExtract = eregi_replace("%siteName%", $_Config["masterRef"], $newBodyExtract);



                  mailTO($emaila, $email_subj, $newBodyExtract);



               // The Mail Function ends here



               } // else



           }

         }// if



         ## Response is set, JS is enabled

         if ($expected_response != get_param("response")) {

          set_session("passwd_error", "Account Information Incorrect!");

          set_session("sub_passwd_error", "Some of the requested fields Password or Secret Question/Answer is incorrect!");

         } else {



            if (!get_param("response2")) {

              if(get_param("new_passwd") && get_param("re_new_passwd")) {

               $passworda = md5(get_param("passwd"));

               $repassword = md5(get_param("repasswd"));

              } else {

               $passworda = "";

               $repassword = "";

              }

            } else {

               $hashpass = split("::", get_param("response2"));

              $passworda = $hashpass[0];

              $repassword = $hashpass[1];

            }



         if ($passworda != $repassword)

            {

               set_session("passwd_error", "The two passwords do not match!");

               set_session("sub_passwd_error", "The new password should be repeated as in the first field for verification!");

            }

         else if ($passworda == "" || $repassword == "")

            {

               set_session("passwd_error", "Password Error! Please, enter a valid password.");

               set_session("sub_passwd_error", "Enter a valid new password at least 6 chars long!");

            }

         else {

                  $auth->db->query(sprintf("UPDATE %s SET password='%s', unmd5='%s' WHERE user_id='%s' ",

                                                $auth->database_table,

                                                $passworda,

                                                get_param("re_new_passwd"),

                                                $auth->auth["uid"] ));

                  set_session("passwd_success", "Password Successfully Changed!");

                  set_session("sub_passwd_success", "From now on you should use the new password you just enterd!");





               // The Mail Function goes here

                  $auth->db->query("SELECT body, subject FROM ".$_Config["database_emails"]." WHERE id='3'");

                  $auth->db->next_record();

                  $email_body = $auth->db->f("body");

                  $email_subj = $auth->db->f("subject");



                  $newBodyExtract = eregi_replace("%name%", $auth->auth["real_name"], $email_body);

                  $newBodyExtract = eregi_replace("%password%", get_param("re_new_passwd"), $newBodyExtract);

                  $newBodyExtract = eregi_replace("%siteName%", $_Config["masterRef"], $newBodyExtract);



                  mailTO($emaila, $email_subj, $newBodyExtract);



               // The Mail Function ends here



               } // else



         } // else

      }



       include("ihtml/new_password.ihtml");

   }



###

#	Lock User Account

###################################

function lock_account()

   {

      global $challenge, $auth, $_Config;



      if (!isset($challenge)) $challenge = md5(uniqid($auth->magic));



      $auth->db->query(sprintf("SELECT u.password, u.lock, u.username, d.question, d.answer ".

                                    "FROM %s u, %s d WHERE u.user_id = '%s' AND d.user_id='%s' ",

                                     $auth->database_table,

                                     $auth->database_details,

                                     $auth->auth["uid"],

                                        $auth->auth["uid"]));



      while($auth->db->next_record()) {

        $pass  = $auth->db->f("password");   ## Password is stored as a md5 hash

        $state = $auth->db->f("lock");

        $s_question = $auth->db->f("question");

        $s_answer = $auth->db->f("answer");

        $emaila = $auth->db->f("username");

      }



   if (get_param("lock_it_process"))

      {



         $expected_response = md5("$s_question:$s_answer:$pass:$challenge");



         ## True when JS is disabled

         if (get_param("response") == "") {

           if (md5(get_param("password")) != $pass && get_param("secret_answer") != $s_answer && get_param("secret_question") != $s_question) {       ## md5 hash for non-JavaScript browsers

          set_session("lock_error", "Account Information Incorrect!");

          set_session("sub_lock_error", "Some of the requested fields Password or Secret Question/Answer is incorrect!");

           } else {

          set_session("lock_success", "Account Successfully Locked!");

          set_session("sub_lock_success", "Now your account is highly restricted from any transfer actions!");

         $auth->db->query(sprintf("UPDATE %s SET `lock`='Y' WHERE user_id='%s'" ,

                                       $auth->database_table,

                                       $auth->auth["uid"]));

         $state = "Y";



      // The Mail Function goes here



         $dc->query("SELECT body, subject FROM ".$_Config["database_emails"]." WHERE id='4';");

         $dc->next_record();

         $email_body = $dc->get("body");

         $email_subj = $dc->get("subject");



         $email_body = eregi_replace("%name%", $auth->auth["real_name"], $email_body);

         $email_body = eregi_replace("%siteName%", $_Config["masterRef"], $email_body);





         mailTO($emaila, $email_subj, $email_body);



      // The Mail Function ends here



           } // else

         }// if



         ## Response is set, JS is enabled

         if ($expected_response != get_param("response")) {

          set_session("lock_error", "Account Information Incorrect!");

          set_session("sub_lock_error", "Some of the requested fields Password or Secret Question/Answer is incorrect!");

         } else {

          set_session("lock_success", "Account Successfully Locked!");

          set_session("sub_lock_success", "Now your account is highly restricted from any transfer actions!");

         $auth->db->query(sprintf("UPDATE %s SET `lock`='Y' WHERE user_id='%s'" ,

                                       $auth->database_table,

                                       $auth->auth["uid"]));

         $state = "Y";



      // The Mail Function goes here



         $dc->query("SELECT body, subject FROM ".$_Config["database_emails"]." WHERE id='4';");

         $dc->next_record();

         $email_body = $dc->get("body");

         $email_subj = $dc->get("subject");



         $email_body = eregi_replace("%name%", $auth->auth["real_name"], $email_body);

         $email_body = eregi_replace("%siteName%", $_Config["masterRef"], $email_body);





         mailTO($emaila, $email_subj, $email_body);



      // The Mail Function ends here



         } // else

      }



   if (get_param("unlock_it_process"))

      {



         $expected_response = md5("$s_question:$s_answer:$pass:$challenge");



         ## True when JS is disabled

         if (get_param("response") == "") {

           if (md5(get_param("password")) != $pass && get_param("secret_answer") != $s_answer && get_param("secret_question") != $s_question) {       ## md5 hash for non-JavaScript browsers

          set_session("lock_error", "Account Information Incorrect!");

          set_session("sub_lock_error", "Some of the requested fields Password or Secret Question/Answer is incorrect!");

           } else {

          set_session("lock_success", "Account Successfully UnLocked!");

          set_session("sub_lock_success", "As your account is unlocked you can use all of its rich features!");

         $auth->db->query(sprintf("UPDATE %s SET `lock`='N' WHERE user_id='%s'" ,

                                       $auth->database_table,

                                       $auth->auth["uid"]));

         $state = "N";





      // The Mail Function goes here



         $dc->query("SELECT body, subject FROM ".$_Config["database_emails"]." WHERE id='5';");

         $dc->next_record();

         $email_body = $dc->get("body");

         $email_subj = $dc->get("subject");



         $email_body = eregi_replace("%name%", $auth->auth["real_name"], $email_body);

         $email_body = eregi_replace("%siteName%", $_Config["masterRef"], $email_body);





         mailTO($emaila, $email_subj, $email_body);



      // The Mail Function ends here



           }

         }// if



         ## Response is set, JS is enabled

         if ($expected_response != get_param("response")) {

          set_session("lock_error", "Account Information Incorrect!");

          set_session("sub_lock_error", "Some of the requested fields Password or Secret Question/Answer is incorrect!");

         } else {

          set_session("lock_success", "Account Successfully UnLocked!");

          set_session("sub_lock_success", "As your account is unlocked you can use all of its rich features!");

         $auth->db->query(sprintf("UPDATE %s SET `lock`='N' WHERE user_id='%s'" ,

                                       $auth->database_table,

                                       $auth->auth["uid"]));

         $state = "N";



      // The Mail Function goes here



         $dc->query("SELECT body, subject FROM ".$_Config["database_emails"]." WHERE id='5';");

         $dc->next_record();

         $email_body = $dc->get("body");

         $email_subj = $dc->get("subject");



         $email_body = eregi_replace("%name%", $auth->auth["real_name"], $email_body);

         $email_body = eregi_replace("%siteName%", $_Config["masterRef"], $email_body);





         mailTO($emaila, $email_subj, $email_body);



      // The Mail Function ends here



         } // else

      }



       include("ihtml/lock_account.ihtml");

   }





###

#	Global IP Blocking

###################################

function global_ip_blocking()

   {

   global $auth, $_Config;



   $old_ips = "";



      if (get_param("submission"))

         {

            if (get_param("delete_ip") || get_param("delete_ip") == "0")

               {

                  $ips = split("::", get_param("all_ips"));

                  for ($i=0;$i<count($ips);$i++)

                     {

                        if ($i != get_param("delete_ip") && $ips[$i]) $new_string .= $ips[$i]."::";

                     }

                  $auth->db->query(sprintf("UPDATE %s SET ip_blocking='%s' WHERE username='ip_blocking@credigold.com'",

                                       $auth->database_table,

                                       $new_string ));

               }

            else if (

                     (get_param("new1") || get_param("new1") == "0") &&

                     (get_param("new2") || get_param("new2") == "0") &&

                     (get_param("new3") || get_param("new3") == "0") &&

                     (get_param("new4") || get_param("new4") == "0")

                    )

               {

               if ( !is_number(get_param("new1")) && !eregi("\*", get_param("new1")) ) { $errora .= "new1::"; }

               if ( !is_number(get_param("new2")) && !eregi("\*", get_param("new2")) ) { $errora .= "new2::"; }

               if ( !is_number(get_param("new3")) && !eregi("\*", get_param("new3")) ) { $errora .= "new3::"; }

               if ( !is_number(get_param("new4")) && !eregi("\*", get_param("new4")) ) { $errora .= "new4::"; }

               if (!$errora)

                  {

                     $new_ip = get_param("new1").".".get_param("new2").".".get_param("new3").".".get_param("new4");

                     for ($i=0;$i<get_param("ip_count");$i++)

                        {

                           for ($k=0;$k<4;$k++)

                              {

                                 if ( !is_number(get_param("ip".$i.$k)) && !eregi("\*", get_param("ip".$i.$k)) ) { $errora .= "ip".$i.$k."::"; }

                                 $old_ips .= get_param("ip".$i.$k);

                                 if ($k != 3) $old_ips .= ".";

                              }

                           $old_ips .= "::";

                        }

                     $new_string = $old_ips.$new_ip."::";

                     if (!$errora)

                        {

                           $auth->db->query(sprintf("UPDATE %s SET ip_blocking='%s' WHERE username='ip_blocking@credigold.com'",

                                          $auth->database_table,

                                          $new_string ));



                           set_session("ip_success", "IP List Updated Successfully!");

                           set_session("sub_ip_success", "The new IP has been recorded or the old list is updated successfully!");

                        }

                  }

               }

            else

               {

                     for ($i=0;$i<get_param("ip_count");$i++)

                        {

                           for ($k=0;$k<4;$k++)

                              {

                                 if ( !is_number(get_param("ip".$i.$k)) && !eregi("\*", get_param("ip".$i.$k)) ) { $errora .= "ip".$i.$k."::"; }

                                 $old_ips .= get_param("ip".$i.$k);

                                 if ($k != 3) $old_ips .= ".";

                              }

                           $old_ips .= "::";

                        }

                     if (!$errora)

                        {

                           $auth->db->query(sprintf("UPDATE %s SET ip_blocking='%s' WHERE username='ip_blocking@credigold.com'",

                                          $auth->database_table,

                                          $old_ips ));

                           set_session("ip_success", "IP List Updated Successfully!");

                           set_session("sub_ip_success", "The new IP has been recorded or the old list is updated successfully!");

                        }

               }

         }



      $ip_html = "";

      $ips = "";



      $auth->db->query(sprintf("SELECT ip_blocking FROM %s WHERE username='ip_blocking@credigold.com'", $auth->database_table));

      $auth->db->next_record();

      $p_all_ips =  $auth->db->f("ip_blocking");



      if ($auth->db->nf() == 0) { $ip_html = "<font color=red>No IPs restricted yet!</font>"; }

      else {

         $ips = split("::", $auth->db->f("ip_blocking"));

         for ($i=0;$i<(count($ips) -1);$i++)

            {

               $ip_html .= "<tr>\n"

                           ."<td width=7% bgColor=F9F9F9 align=center class=text>".($i + 1).".</td>\n"

                           ."<td width=86% class=text bgColor=white align=center>\n";



               $temp_ips = split("\.", $ips[$i]);

               for ($k=0;$k<count($temp_ips);$k++)

                  {

                     $valueto = ($errora)?get_param("ip".$i.$k):$temp_ips[$k];

                     $ip_html .= '<input type="text" name="ip'.$i.$k.'" class="note" size="3" maxlength="3" value="'.$valueto.'" style="text-align:center;';

                     if (eregi("ip".$i.$k, $errora)) { $ip_html .= 'color:red;">'; } else { $ip_html .= '">'; }

                     if ($k != (count($temp_ips) - 1)) $ip_html .= " . \n";

                  }

               $ip_html .= "<td width=7% bgColor=F9F9F9 align=center class=text><a href=\"javascript:delete_ip('".$i."')\" title=\"Delete this ip!\">&nbsp;X&nbsp;</a></td>\n"

                           ."<tr>\n"

                           ."<td colspan=3 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>\n"

                           ."</tr>\n";

            } // end for

      } // end else

      if ($errora)

         {

            set_session("ip_error", "Error Occurred! Please, review the items in red!");

            set_session("sub_ip_error", "IPs must be only numbers and \"*\". No letters are allowed!");

         }

       include("ihtml/global_ip_blocking.ihtml");

      unset($errora);

   }



###

#	Admin View Profile

###################################



function view_profile()

   {

   global $auth, $_Config;

      if ( get_param("submission") )

         {

            $auth->db->query("SELECT * FROM $auth->database_table u, $auth->database_details c WHERE u.user_number='".get_param("user_number")."' AND c.user_id=u.user_id;");

            if ( $auth->db->nf()<=0 )

               {

                  $errora = "<center><div align=center class=text style=\"width:400px;background-color:F9F9F9;padding:5px;margin:2px;color:black;border:1px solid D0D0D0\"><font class=head color=red>Invalid Account Number!</font><br>The account you selected does not exist on the system.</div>";

               } // if

            else

               {

                  $auth->db->next_record();

               } // else

         } // if



   include("ihtml/profiles.ihtml");

   } // func view_profile()

function renderProfiles()

  	{

		global $dc, $rc, $_Config, $auth, $str;

		if (!get_param("submission"))

			{

				$auth->db->query("SELECT * FROM $auth->database_table u, $auth->database_details c WHERE c.user_id=u.user_id;");

				$num       = $auth->db->nf();

				$str       = (!$str)?0:$str;

				$per_page  = (!$_Config["paginate"])?10:$_Config["paginate"];

				$auth->db->query("SELECT * FROM $auth->database_table u, $auth->database_details c WHERE c.user_id=u.user_id LIMIT $str, $per_page;");

				?>

				<table border=0 width=500 cellspacing=0 cellpadding=0 align=center>

				<tr><td bgcolor=#D0D0D0><table border=0 width=100% cellspacing=1 cellpadding=0>

				<tr><td colspan=4 class=text align=center  height=22  bgcolor=FAFAFA><b style='color:gray'>Current Fund Options</b></td></tr>

				<tr><td colspan=4 bgcolor=white><img src=images/dot.gif width=1 height=1></td></tr>

				<tr>

				<td width=35% align=center height=23 bgcolor=white class=text><b>Name</b></td>

				<td width=15% align=center bgcolor=white class=text><b>Account</b></td>

				<td width=20% align=center bgcolor=white class=text><b>Money</b></td>

				<td width=30% align=center bgcolor=white class=text><b>Registered</b></td>

				</tr>

				<tr><td colspan=4  bgcolor=white><img src=images/dot.gif width=1 height=1></td></tr>				

				<?

				while($auth->db->next_record())

					{

						$r = ($auth->db->f("perms") == "admin")?"<b>":"";

				?>

				<tr>

				<td align=left	 height=25 bgcolor=white class=little>&nbsp;<?=$r?><a href="mailto:<?=$auth->db->f("username")?>"><?=$auth->db->f("real_name")?></a> <a href="index.php?cmd=profiles&submission=true&user_number=<?=$auth->db->f("user_number")?>">(View)</a></td>

				<td align=center bgcolor=white class=little><?=$auth->db->f("user_number")?></td>

				<td align=center bgcolor=white class=little><b style=color:orange><?=$auth->db->f("crediGold")?></b><img src=../images/gold.gif></td>

				<td align=center bgcolor=white class=little><?=convertDate("-m/-d/-y at -h:-t", $auth->db->f("dateIt"))?></td>

				</tr>

				<? 

					}

				?>

				</table>

				</td>

				</tr>

				</table>

				<?

				renderButs($num, "index.php?cmd=profiles", 500);

			}

  	}

function pages_editor()

   {

      global $auth, $dc, $sess;

      global $_PHPLIB, $PHP_SELF, $_Config;

      include($_PHPLIB["libdir"]."lib.editor.php");



      if (get_param("cont") && get_param("tempID") && get_param("set"))

         {

            $dc->query("UPDATE ".$_Config["database_pages"]." SET `explain`='".get_param("nameIt")."', `permissions`='".get_param("permIt")."', `html`='".eregi_replace("<P", "<p style=width:500px", addslashes(get_param("cont")))."' WHERE id='".get_param("tempID")."';");

            $messages = "<font class=head color=green>Page Editted Successfully!</font>";

         }

      if (get_param("cont") && get_param("act") == "create")

         {

            $dc->query("INSERT INTO ".$_Config["database_pages"]." SET id='', `explain`='".get_param("nameIt")."', `permissions`='".get_param("permIt")."', `html`='".eregi_replace("<P", "<p style=width:500px", addslashes(get_param("cont")))."';");

            $messages = "<font class=head color=green>Page Created Successfully!</font>";

         }

      include("ihtml/page_editor.ihtml");

   }

function emails_editor()

   {

      global $dc, $_Config;

      if (get_param("cont") && get_param("tempID"))

         {

            $dc->query("UPDATE ".$_Config["database_emails"]." SET `subject`='".get_param("nameIt")."', `body`='".addslashes(get_param("cont"))."' WHERE id='".get_param("tempID")."';");

            $messages = "<font class=head color=green>Email Editted Successfully!</font>";

         }

      include($_PHPLIB["libdir"]."lib.editor.php");

      include("ihtml/emails_editor.ihtml");

   } // end func

function people_online()

	{

	  global $dc, $rc, $_Config, $_OPTIONS;

	  if ($_OPTIONS["online"])

			{

			  if ($_Config["track_online"])

				{

				  $dc->query("SELECT * FROM ".$_Config["database_online"].";");

				  $online = $dc->num_rows();

				  $guests = array();

				  $users  = array();

				  $m      = 0;

				  $g      = 0;

				  for ($i=0;$i<$dc->num_rows();$i++)

					{

						$dc->next_record();

						if ($dc->get("account") == "Guest")

							{

								$guests[$m][IP]   = $dc->get("IP");

								$guests[$m][last] = $dc->get("last");

								$guests[$m][URL]  = $dc->get("url");

								$m++;

							}

						else

							{

								$rc->query("SELECT real_name, username FROM ".$_Config["database_auth"]." WHERE user_number='".$dc->get("account")."';");

								$rc->next_record();

								$testTime         = round(time()-$dc->get("last"));

								$times            = ($testTime >= 60)?(round($testTime/60)." min"):($testTime." sec");

								$users[$g][name]  = $rc->get("real_name");

								$users[$g][email] = $rc->get("username");

								$users[$g][IP]    = $dc->get("IP");

								$users[$g][last]  = $times;

								$users[$g][URL]   = $this->decodeURL($dc->get("url"));

								$g++;

							}

					}

				  include("ihtml/people_online.ihtml");

				}

			else

				{

			?>

			<div align=center class=head><font color=orange><?=$_Config["masterRef"]?></font> Online Users Is Disabled</div>

			<p align=justify style=width:520px;padding:10px>

			&nbsp;&nbsp; This feature is currently disabled by the administrator. To enable it use the config.php file and change the line <b>$_Config["track_online"]  = false;</b> to <b>$_Config["track_online"]  = true;</b>. This will enable the tracking of members and guests on the site and you will be able to use this module of the admin panel.

			</p>	

			<?

				}

		}

	else

		{

			include("ihtml/option_not_installed.ihtml");				

		}

	}

function decodeURL($URL)

	{

		global $_PHPLIB;

		if (eregi("index\.php$", $URL))						$dec = "<a href='".$URL."'>Home Page</a>";

		if (eregi("contactUs\.php$", $URL))					$dec = "<a href='".$URL."'>Contact Us Page</a>";

		if (eregi("page\.php", $URL))						$dec = "<a href='".$URL."'>Internal Page</a>";

		

		if (eregi("trnasfer\.php$", $URL))					$dec = "<a href='".$URL."'>Transfer Funds : Balance Page</a>";

		if (eregi("transfer\.php\?cmd=send$", $URL))		$dec = "<a href='".$URL."'>Transfer Funds : Send</a>";

		if (eregi("transfer\.php\?cmd=request$", $URL))		$dec = "<a href='".$URL."'>Transfer Funds : Request</a>";

		if (eregi("transfer\.php\?cmd=fund$", $URL))		$dec = "<a href='".$URL."'>Transfer Funds : Fund</a>";

		if (eregi("transfer\.php\?cmd=withdraw$", $URL))	$dec = "<a href='".$URL."'>Transfer Funds : Withdraw</a>";

		if (eregi("transfer\.php\?cmd=history$", $URL))		$dec = "<a href='".$URL."'>Transfer Funds : History</a>";



		if (eregi("merchant\.php$", $URL))					$dec = "<a href='".$URL."'>Merchant Tools</a>";

		if (eregi("merchant\.php\?cmd=cart$", $URL))		$dec = "<a href='".$URL."'>Merchant Tools : Cart</a>";

		if (eregi("merchant\.php\?cmd=referrals$", $URL))	$dec = "<a href='".$URL."'>Merchant Tools : Referrals</a>";

		if (eregi("merchant\.php\?cmd=reoccur$", $URL))		$dec = "<a href='".$URL."'>Merchant Tools : Reoccurings</a>";



		if (eregi("account\.php$", $URL))					$dec = "<a href='".$URL."'>Your Account</a>";

		if (eregi("account\.php\?cmd=edit", $URL))			$dec = "<a href='".$URL."'>Your Account : Edit Profile</a>";

		if (eregi("account\.php\?cmd=lock", $URL))			$dec = "<a href='".$URL."'>Your Account : Lock Account</a>";

		if (eregi("account\.php\?cmd=new_password", $URL))	$dec = "<a href='".$URL."'>Your Account : New Passowrd</a>";

		if (eregi("account\.php\?cmd=ip_blocking", $URL))	$dec = "<a href='".$URL."'>Your Account : IP Blocking</a>";

		if (eregi("account\.php\?cmd=register", $URL))		$dec = "<a href='".$URL."'>Your Account : New Account</a>";



		if (eregi("help\.php", $URL))						$dec = "<a href='".$URL."'>Help Pages</a>";

		if (eregi("faq_view\.php", $URL))					$dec = "<a href='".$URL."'>Help Pages</a>";

		if (eregi("faq_post\.php", $URL))					$dec = "<a href='".$URL."'>Help Pages : Post New Question</a>";

		if (eregi("fogot\.php", $URL))						$dec = "<a href='".$URL."'>Password Recovery</a>";



		if (eregi("admin/index", $URL))

			{

															$dec = "<a href='".$URL."'>Admin Panel</a>";

				if (eregi("cmd=faq", $URL))					$dec = "<a href='".$URL."'>Admin Panel : FAQ Admin</a>";

				if (eregi("cmd=banners", $URL))				$dec = "<a href='".$URL."'>Admin Panel : Banners Manager</a>";

				if (eregi("cmd=backup", $URL))				$dec = "<a href='".$URL."'>Admin Panel : BackUp</a>";

				if (eregi("cmd=meta", $URL))				$dec = "<a href='".$URL."'>Admin Panel : META Editor</a>";

				if (eregi("cmd=emails", $URL))				$dec = "<a href='".$URL."'>Admin Panel : Custom Emails</a>";

				if (eregi("cmd=pages", $URL))				$dec = "<a href='".$URL."'>Admin Panel : Custom Pages</a>";

				if (eregi("cmd=online", $URL))				$dec = "<a href='".$URL."'>Admin Panel : People Online</a>";

				if (eregi("cmd=newsletters", $URL))			$dec = "<a href='".$URL."'>Admin Panel : Newsletter</a>";

				if (eregi("cmd=ip_blocking", $URL))			$dec = "<a href='".$URL."'>Admin Panel : Global IP Blocking</a>";

				if (eregi("cmd=history", $URL))				$dec = "<a href='".$URL."'>Admin Panel : Transactions Viewer</a>";

				if (eregi("cmd=profiles", $URL))			$dec = "<a href='".$URL."'>Admin Panel : Users Profiles</a>";

				if (eregi("cmd=calendar", $URL))			$dec = "<a href='".$URL."'>Admin Panel : Calendar</a>";

				if (eregi("cmd=load", $URL))				$dec = "<a href='".$URL."'>Admin Panel : Units Loader</a>";

				if (eregi("cmd=credigold", $URL))			$dec = "<a href='".$URL."'>Admin Panel : Site's Unit Index</a>";

				if (eregi("cmd=fund", $URL))				$dec = "<a href='".$URL."'>Admin Panel : Fund Admin</a>";

				if (eregi("cmd=withdraw", $URL))			$dec = "<a href='".$URL."'>Admin Panel : WithDraw Admin</a>";

			}

		

		if (isset($dec))

			{

				return $dec;

			}

		else

			{

				$dec = "<a href='".$_PHPLIB["http_path"]."/index.php'>Home Page</a>";

				return $dec;

			}

	}

function calendar()

	{

		global $_PHPLIB, $_Config, $dc, $rc, $_OPTIONS;

		if ($_OPTIONS["calendar"])

			{

				include($_PHPLIB["moddir"]."class.calendar.php");

				include("ihtml/calendar.ihtml");

			}

		else

			{

				include("ihtml/option_not_installed.ihtml");				

			}

	}

function meta_editor()

   {

      global $dc, $_Config;

      if (get_param("set"))

         {

            $dc->query("UPDATE ".$_Config["database_meta"]." SET description='".get_param("description")."', keywords='".get_param("keywords")."', encoding='".get_param("encoding")."', custom='".get_param("custom")."';");

            $message = "<font class=head color=green>META Updated Successfully!</font>";

         }

      $dc->query("SELECT * FROM ".$_Config["database_meta"].";");

      $dc->next_record();

      include("ihtml/meta_editor.ihtml");

   } // end func



function fundAdmin()

	{

		global $_PHPLIB, $_Config, $dc, $rc, $sess, $auth;

		if (!get_param("action"))

			{

				if (get_param("set") == "disable" && get_param("id"))

					{

						$dc->query("UPDATE ".$_Config["database_fund"]." SET status='Off' WHERE id='".get_param("id")."';");

						$message = "<div class=head align=center style=color:green>Funding Option Disabled Successfully!</div><br>";

					}

				if (get_param("set") == "activate" && get_param("id"))

					{

						$dc->query("UPDATE ".$_Config["database_fund"]." SET status='On' WHERE id='".get_param("id")."';");

						$message = "<div class=head align=center style=color:green>Funding Option Activated Successfully!</div><br>";

					}

				if (get_param("set") == "add")

					{

						$t      = 0;

						$pay    = (get_param("fee"))?"Y":"N";

						for ($i=0;$i<get_param("y_v");$i++)

							{

								$j = $i + 1;

								$sargetVal = "hidden".$j;

								$sargetDef = "value".$j;

								global $$sargetVal, $$sargetDef;

								if ($$sargetVal && $$sargetDef)

									{

										$pvals    .= (($t == 0)?"":", ")."[\"".$$sargetVal."\", \"".$$sargetDef."\"]";

										$t++;

									}

							}

						$r = 0;

						for ($i=0;$i<get_param("x_v");$i++)

							{

								$j = $i + 1;

								$targetVal = "field".$j;

								$targetDef = "default".$j;

								$targetSiz = "size".$j;

								global $$targetVal, $$targetDef, $$targetSiz;

								if ($$targetVal)

									{

										$$targetSiz = (!$$targetSiz)?30:$$targetSiz;

										$anames    .= (($r == 0)?"":", ")."[\"".$$targetVal."\", \"".$$targetDef."\", \"".$$targetSiz."\"]";

										$r++;

									}

							}



						$dc->query("INSERT INTO ".$_Config["database_fund"]." SET id='', name='".get_param("name")."', description='".get_param("comments")."', fee='".get_param("fee")."', paid='".$pay."', target='".get_param("target")."', addition='".get_param("addition")."', pass_vars='$pvals', add_names='$anames', image='".get_param("img_button")."', thank_you='".get_param("thank_name")."~|~".get_param("thank_you")."', formula='".get_param("formula")."';");

						$message = "<div class=head align=center style=color:green>Funding Option Added Successfully!</div><br>";

					}

			}

		if (get_param("action") == "add") // Update Rows

			{

				if (get_param("set") == "edit" && get_param("id") && get_param("name"))

					{

						$t      = 0;

						$pay    = (get_param("fee"))?"Y":"N";

						for ($i=0;$i<get_param("y_v");$i++)

							{

								$j = $i + 1;

								$sargetVal = "hidden".$j;

								$sargetDef = "value".$j;

								global $$sargetVal, $$sargetDef;

								if ($$sargetVal && $$sargetDef)

									{

										$pvals    .= (($t == 0)?"":", ")."[\"".$$sargetVal."\", \"".$$sargetDef."\"]";

										$t++;

									}

							}

						$r = 0;

						for ($i=0;$i<get_param("x_v");$i++)

							{

								$j = $i + 1;

								$targetVal = "field".$j;

								$targetDef = "default".$j;

								$targetSiz = "size".$j;

								global $$targetVal, $$targetDef, $$targetSiz;

								if ($$targetVal)

									{

										$$targetSiz = (!$$targetSiz)?30:$$targetSiz;

										$anames    .= (($r == 0)?"":", ")."[\"".$$targetVal."\", \"".$$targetDef."\", \"".$$targetSiz."\"]";

										$r++;

									}

							}

						$dc->query("UPDATE ".$_Config["database_fund"]." SET name='".get_param("name")."', description='".get_param("comments")."', fee='".get_param("fee")."', paid='".$pay."', target='".get_param("target")."', addition='".get_param("addition")."', pass_vars='$pvals', add_names='$anames', image='".get_param("img_button")."', thank_you='".get_param("thank_name")."~|~".get_param("thank_you")."', formula='".get_param("formula")."' WHERE id='".get_param("id")."';");

						$message = "<div class=head align=center style=color:green>Funding Option Edited Successfully!</div><br>";

					}

			}

		if (get_param("action") == "add")

			{

				$types   = (get_param("set") == "edit")?"Edit This Option":"Add An Option";

				$add     = (get_param("set") == "edit")?"<input type=hidden name=set value=edit>\n":"<input type=hidden name=set value=add>\n";

				$add_id  = (get_param("set") == "edit")?"<input type=hidden name=id value='".get_param("id")."'>\n":"";

				if (get_param("set") == "edit")

					{

						$edits = array();

						$m       = 0;

						$dc->query("SELECT * FROM ".$_Config["database_fund"]." WHERE id='".get_param("id")."';");

						$dc->next_record();

						$temp               = split("~\|~", $dc->get("thank_you"));

						$edits[id]          = $dc->get("id");

						$edits[name]        = $dc->get("name");

						$edits[comments]    = $dc->get("description");

						$edits[target]      = $dc->get("target");

						$edits[addition]    = $dc->get("addition");

						$edits[img_butt]    = $dc->get("image");

						$edits[thank]       = $temp[0];

						$edits[thank_url]   = $temp[1];



						preg_match_all ("/\[\"(.*?)\", \"(.*?)\"\]/i", $dc->get("pass_vars"), $matches);

						for ($i=0;$i<count($matches[0]);$i++)

							{

								$edits[hidden][$m][name]   = $matches[1][$i];

								$edits[hidden][$m][value]  = $matches[2][$i];

								$edits[hidName] .= ", \"".$matches[1][$i]."\"";

								$edits[hidVals] .= ", \"".$matches[2][$i]."\"";

								$m++;

							}



						$m = 0;

						preg_match_all ("/\[\"(.*?)\", \"(.*?)\", \"(.*?)\"\]/i", $dc->get("add_names"), $fields_match);

						for ($i=0;$i<count($fields_match[0]);$i++)

							{

								$edits[vals][$m][name]   = $fields_match[1][$i];

								$edits[vals][$m][value]  = $fields_match[2][$i];

								$edits[vals][$m][size]   = $fields_match[3][$i];

								$edits[ordName] .= ", \"".$fields_match[1][$i]."\"";

								$edits[ordVals] .= ", \"".$fields_match[2][$i]."\"";

								$edits[ordSize] .= ", \"".$fields_match[3][$i]."\"";

								$m++;

							}

						include("ihtml/fund_edit.ihtml");					

					}

				else

					{

						include("ihtml/fund_add.ihtml");

					}

			}

		else

			{

				$methods = array();

				$m       = 0;

				$dc->query("SELECT * FROM ".$_Config["database_fund"].";");

				for ($i=0;$i<$dc->num_rows();$i++)

					{

						$dc->next_record();

						$methods[$m][id]          = $dc->get("id");

						$methods[$m][act]         = ($dc->get("status") == "On")?"<a href='index.php?cmd=fund&set=disable&id=".$dc->get("id")."'>Disable</a>":"<a href='index.php?cmd=fund&set=activate&id=".$dc->get("id")."'>Activate</a>";

						$methods[$m][fee]         = ($dc->get("paid") == "Y")?$dc->get("fee"):"<font color=green><b>FREE</b></font>";

						$methods[$m][status]      = ($dc->get("status") == "On")?"<font color=green>Active</font>":"<font color=darkred>Disabled</font>";

						$methods[$m][description] = "<b>".$dc->get("name")."</b>".(($dc->get("description"))?("<br>".$dc->get("description")):"");

						$m++;

					}

				include("ihtml/fund_admin.ihtml");

			}

	}

function withdrawAdmin()

	{

		global $_PHPLIB, $_Config, $dc, $rc, $sess, $auth, $indexPrice;

		if (get_param("action") == "methods")

			{

				if (get_param("set") == "disable" && get_param("id"))

					{

						$dc->query("UPDATE ".$_Config["database_wmethods"]." SET status='Off' WHERE id='".get_param("id")."';");

						$message = "<div class=head align=center style=color:green>Withdrawal Method Disabled Successfully!</div><br>";

					}

				if (get_param("set") == "activate" && get_param("id"))

					{

						$dc->query("UPDATE ".$_Config["database_wmethods"]." SET status='On' WHERE id='".get_param("id")."';");

						$message = "<div class=head align=center style=color:green>Withdrawal Method Activated Successfully!</div><br>";

					}

				if (get_param("set") == "add")

					{

						$pay = (get_param("fee"))?"Y":"N";

						$dc->query("INSERT INTO ".$_Config["database_wmethods"]." SET name='".get_param("name")."', comments='".get_param("comments")."', fee='".get_param("fee")."', paid='".$pay."';");

						$message = "<div class=head align=center style=color:green>Withdrawal Method Added Successfully!</div><br>";

					}

				if (get_param("set") == "edit" && get_param("id") && get_param("insert"))

					{

						$pay = (get_param("fee"))?"Y":"N";

						$dc->query("UPDATE ".$_Config["database_wmethods"]." SET name='".get_param("name")."', comments='".get_param("comments")."', fee='".get_param("fee")."', paid='".$pay."' WHERE id='".get_param("id")."';");

						$message = "<div class=head align=center style=color:green>Withdrawal Method Edited Successfully!</div><br>";

					}



				$types   = (get_param("set") == "edit")?"Edit This Method":"Add A Method";

				$add     = (get_param("set") == "edit")?"<input type=hidden name=set value=edit>":"<input type=hidden name=set value=add>";

				$add_id  = (get_param("set") == "edit")?"<input type=hidden name=id value='".get_param("id")."'>":"";

				$methods = array();

				$edits   = array();

				$m       = 0;

				$dc->query("SELECT * FROM ".$_Config["database_wmethods"].";");

				for ($i=0;$i<$dc->num_rows();$i++)

					{

						$dc->next_record();

						$methods[$m][id]          = $dc->get("id");

						$methods[$m][act]         = ($dc->get("status") == "On")?"<a href='index.php?cmd=withdraw&action=methods&set=disable&id=".$dc->get("id")."'>Disable</a>":"<a href='index.php?cmd=withdraw&action=methods&set=activate&id=".$dc->get("id")."'>Activate</a>";

						$methods[$m][fee]         = ($dc->get("paid") == "Y")?$dc->get("fee"):"<font color=green><b>FREE</b></font>";

						$methods[$m][status]      = ($dc->get("status") == "On")?"<font color=green>Active</font>":"<font color=darkred>Disabled</font>";

						$methods[$m][description] = "<b>".$dc->get("name")."</b>".(($dc->get("comments"))?("<br>".$dc->get("comments")):"");



						if (get_param("set") == "edit" && get_param("id") == $dc->get("id"))

							{

								$edits[name]     = $dc->get("name");

								$edits[fee]      = ($dc->get("paid") == "Y")?$dc->get("fee"):"";

								$edits[comments] = $dc->get("comments");

							}



						$m++;

					}

				include("ihtml/withdraw_methods.ihtml");			

			}

		else if (get_param("action") == "history")

			{

				if (get_param("post"))

					{

						$sDate = mktime(0, 0, 0, get_param("startMonth"), get_param("startDate"), get_param("startYear")); 

						$eDate = mktime(0, 0, 0, get_param("endMonth"), get_param("endDate"), get_param("endYear")); 

						$rules = $_Config["database_wmethods"].".id=".$_Config["database_withdrawals"].".method AND ".$_Config["database_details"].".user_number=".$_Config["database_withdrawals"].".user_number AND ".$_Config["database_auth"].".user_number=".$_Config["database_withdrawals"].".user_number AND (".$_Config["database_withdrawals"].".dateIt > $sDate AND ".$_Config["database_withdrawals"].".dateIt < $eDate)"; // Default Rules



						$rules .= (get_param("byAccount"))?(" AND ".$_Config["database_withdrawals"].".user_number='".get_param("byAccount")."'"):"";

						$rules .= (get_param("transFilter"))?(" AND ".$_Config["database_withdrawals"].".status='".get_param("transFilter")."'"):"";

						$rules .= (get_param("byGold"))?(" AND ".$_Config["database_withdrawals"].".amount".get_param("goldTerm").get_param("byGold")):"";



						$users = array();

						$m     = 0;

						$dc->query("SELECT ".$_Config["database_withdrawals"].".*, ".$_Config["database_wmethods"].".name, ".$_Config["database_auth"].".real_name, ".$_Config["database_auth"].".username, ".$_Config["database_details"].".* FROM ".$_Config["database_withdrawals"].", ".$_Config["database_auth"].", ".$_Config["database_details"].", ".$_Config["database_wmethods"]." WHERE $rules;");

						$num       = $dc->num_rows();

						$str       = (!$str)?0:$str;

						$per_page  = (!$_Config["paginate"])?10:$_Config["paginate"];

						$dc->query("SELECT ".$_Config["database_withdrawals"].".*, ".$_Config["database_wmethods"].".name, ".$_Config["database_auth"].".real_name, ".$_Config["database_auth"].".username, ".$_Config["database_details"].".* FROM ".$_Config["database_withdrawals"].", ".$_Config["database_auth"].", ".$_Config["database_details"].", ".$_Config["database_wmethods"]." WHERE $rules LIMIT $str, $per_page;");



						for ($i=0;$i<$dc->num_rows();$i++)

							{

								$dc->next_record();

								$users[$m][id]        = $dc->get("id");

								$users[$m][account]   = $dc->get("user_number");

								$users[$m][money]     = $dc->get("amount");

								$users[$m][dollars]   = $dc->get("amount")*$indexPrice;

								$users[$m][gold]      = $dc->get("crediGold");

								$users[$m][name]      = $dc->get("real_name");

								$users[$m][method]    = $dc->get("name");

								$users[$m][email]     = $dc->get("username");

								$users[$m][num]       = $dc->get("dateIt");

								$users[$m][action]    = ($dc->get("status") == "Active")?"<a href=\"index.php?cmd=withdraw&action=process&str=".get_param("str")."&id=".$dc->get("id")."\">[ PROCESS WITHDRAWAL ]</a> &nbsp;":"<a href=\"index.php?cmd=withdraw&action=complete&str=".get_param("str")."&id=".$dc->get("id")."\">[ COMPLETE WITHDRAWAL ]</a> &nbsp;";

								$users[$m][dateIt]    = strftime("%m/%d/%Y at %H:%M", $dc->get("dateIt"));

								$users[$m][phone]     = ($dc->get("phone"))?$dc->get("phone"):"-";

								$users[$m][address]   = $dc->get("address1")."<br>".$dc->get("city").", ".$dc->get("state")." ".$dc->get("zip")."<br>".$dc->get("country");

								$users[$m][memo]      = ($dc->get("memo"))?nl2br($dc->get("memo")):"User left no memo!";

								$users[$m][comment]   = ($dc->get("comment"))?"<tr><td width=15% align=right valign=top bgcolor=white class=little style=padding:5px><b>Your Comments:</b>&nbsp;</td><td align=left bgcolor=white class=little colspan=3>&nbsp;".$dc->get("comment")."</td></tr>":"";

								$users[$m][real_with] = ($dc->get("status") == "Pending")?"<form name='with".$dc->get("id")."' action=index.php method=POST><input type=hidden name=cmd value=".get_param("cmd")."><input type=hidden name=str value='".get_param("str")."'><input type=hidden name=user value='".$dc->get("user_number")."'><input type=hidden name=action value=money><tr><td width=15% align=right valign=top bgcolor=white class=little style=padding:5px><b>Withdraw:</b>&nbsp;</td><td align=left bgcolor=white class=little colspan=3>&nbsp;<input type=text name=sum value=\"".$dc->get("amount")."\" size=5> <input type=submit value='Withdraw Amount' class=box></td></tr></form>":"";

								$m++;

							}

					

					}

				include("ihtml/withdraw_history.ihtml");

			}

		else

			{

				if (get_param("action") == "cancel" && get_param("id"))

					{

                           $dc->query("SELECT ".$_Config["database_emails"].".body, ".$_Config["database_emails"].".subject, ".$_Config["database_withdrawals"].".*, ".$_Config["database_wmethods"].".name, ".$_Config["database_auth"].".real_name, ".$_Config["database_auth"].".username, ".$_Config["database_details"].".* FROM ".$_Config["database_withdrawals"].", ".$_Config["database_auth"].", ".$_Config["database_emails"].", ".$_Config["database_details"].", ".$_Config["database_wmethods"]." WHERE (".$_Config["database_withdrawals"].".status!='Completed' OR ".$_Config["database_withdrawals"].".status!='Cancelled') AND ".$_Config["database_wmethods"].".id=".$_Config["database_withdrawals"].".method AND ".$_Config["database_details"].".user_number=".$_Config["database_withdrawals"].".user_number AND ".$_Config["database_auth"].".user_number=".$_Config["database_withdrawals"].".user_number AND ".$_Config["database_emails"].".id='10';");

                           $dc->next_record();

                           $email_body = $dc->get("body");

                           $email_subj = $dc->get("subject");

                           $email_rect = $dc->get("username");



                           $email_body = eregi_replace("%name%", $dc->get("real_name"), $email_body);

                           $email_body = eregi_replace("%amount%", $dc->get("amount"), $email_body);

                           $email_body = eregi_replace("%account%", $dc->get("user_number"), $email_body);

                           $email_body = eregi_replace("%request_from%", strftime("%m/%d/%Y at %H:%M", $dc->get("dateIt")), $email_body);

                           $email_body = eregi_replace("%request_id%", $dc->get("dateIt"), $email_body);

                           $email_body = eregi_replace("%comments%", $dc->get("comment"), $email_body);

                           $email_body = eregi_replace("%method%", $dc->get("name"), $email_body);

                           $email_body = eregi_replace("%siteName%", $_Config["masterRef"], $email_body);

                           mailTO($email_rect, $email_subj, $email_body);



						$dc->query("UPDATE ".$_Config["database_withdrawals"]." SET status='Cancelled' WHERE id='".get_param("id")."';");

						$message = "<div class=head align=center style=color:green>Withdrawal Request Cancelled Successfully!</div><br>";

					}

				if (get_param("action") == "process" && get_param("id"))

					{

                           $dc->query("SELECT ".$_Config["database_emails"].".body, ".$_Config["database_emails"].".subject, ".$_Config["database_withdrawals"].".*, ".$_Config["database_wmethods"].".name, ".$_Config["database_auth"].".real_name, ".$_Config["database_auth"].".username, ".$_Config["database_details"].".* FROM ".$_Config["database_withdrawals"].", ".$_Config["database_auth"].", ".$_Config["database_emails"].", ".$_Config["database_details"].", ".$_Config["database_wmethods"]." WHERE (".$_Config["database_withdrawals"].".status!='Completed' OR ".$_Config["database_withdrawals"].".status!='Cancelled') AND ".$_Config["database_wmethods"].".id=".$_Config["database_withdrawals"].".method AND ".$_Config["database_details"].".user_number=".$_Config["database_withdrawals"].".user_number AND ".$_Config["database_auth"].".user_number=".$_Config["database_withdrawals"].".user_number AND ".$_Config["database_emails"].".id='8';");

                           $dc->next_record();

                           $email_body = $dc->get("body");

                           $email_subj = $dc->get("subject");

                           $email_rect = $dc->get("username");



                           $email_body = eregi_replace("%name%", $dc->get("real_name"), $email_body);

                           $email_body = eregi_replace("%amount%", $dc->get("amount"), $email_body);

                           $email_body = eregi_replace("%account%", $dc->get("user_number"), $email_body);

                           $email_body = eregi_replace("%request_from%", strftime("%m/%d/%Y at %H:%M", $dc->get("dateIt")), $email_body);

                           $email_body = eregi_replace("%request_id%", $dc->get("dateIt"), $email_body);

                           $email_body = eregi_replace("%comments%", $dc->get("comment"), $email_body);

                           $email_body = eregi_replace("%method%", $dc->get("name"), $email_body);

                           $email_body = eregi_replace("%siteName%", $_Config["masterRef"], $email_body);

                           mailTO($email_rect, $email_subj, $email_body);



						$dc->query("UPDATE ".$_Config["database_withdrawals"]." SET status='Pending' WHERE id='".get_param("id")."';");

						$message = "<div class=head align=center style=color:green>Withdrawal Request Processed Successfully!</div><br>";

					}

				if (get_param("action") == "complete" && get_param("id"))

					{

                           $dc->query("SELECT ".$_Config["database_emails"].".body, ".$_Config["database_emails"].".subject, ".$_Config["database_withdrawals"].".*, ".$_Config["database_wmethods"].".name, ".$_Config["database_auth"].".real_name, ".$_Config["database_auth"].".username, ".$_Config["database_details"].".* FROM ".$_Config["database_withdrawals"].", ".$_Config["database_auth"].", ".$_Config["database_emails"].", ".$_Config["database_details"].", ".$_Config["database_wmethods"]." WHERE (".$_Config["database_withdrawals"].".status!='Completed' OR ".$_Config["database_withdrawals"].".status!='Cancelled') AND ".$_Config["database_wmethods"].".id=".$_Config["database_withdrawals"].".method AND ".$_Config["database_details"].".user_number=".$_Config["database_withdrawals"].".user_number AND ".$_Config["database_auth"].".user_number=".$_Config["database_withdrawals"].".user_number AND ".$_Config["database_emails"].".id='9';");

                           $dc->next_record();

                           $email_body = $dc->get("body");

                           $email_subj = $dc->get("subject");

                           $email_rect = $dc->get("username");



                           $email_body = eregi_replace("%name%", $dc->get("real_name"), $email_body);

                           $email_body = eregi_replace("%amount%", $dc->get("amount"), $email_body);

                           $email_body = eregi_replace("%account%", $dc->get("user_number"), $email_body);

                           $email_body = eregi_replace("%request_from%", strftime("%m/%d/%Y at %H:%M", $dc->get("dateIt")), $email_body);

                           $email_body = eregi_replace("%request_id%", $dc->get("dateIt"), $email_body);

                           $email_body = eregi_replace("%comments%", $dc->get("comment"), $email_body);

                           $email_body = eregi_replace("%method%", $dc->get("name"), $email_body);

                           $email_body = eregi_replace("%siteName%", $_Config["masterRef"], $email_body);

                           mailTO($email_rect, $email_subj, $email_body);



						$dc->query("UPDATE ".$_Config["database_withdrawals"]." SET status='Completed' WHERE id='".get_param("id")."';");

						$message = "<div class=head align=center style=color:green>Withdrawal Request Completed Successfully!</div><br>";

					}

				if (get_param("action") == "money" && get_param("user"))

					{

						$dc->query("UPDATE ".$_Config["database_details"]." SET crediGold=crediGold-".get_param("sum")." WHERE user_number='".get_param("user")."';");

						$message = "<div class=head align=center style=color:green>Withdrawal Of Money Processed Successfully!</div><br>";

					}



				$users = array();

				$m     = 0;

				$dc->query("SELECT ".$_Config["database_withdrawals"].".*, ".$_Config["database_wmethods"].".name, ".$_Config["database_auth"].".real_name, ".$_Config["database_auth"].".username, ".$_Config["database_details"].".* FROM ".$_Config["database_withdrawals"].", ".$_Config["database_auth"].", ".$_Config["database_details"].", ".$_Config["database_wmethods"]." WHERE (".$_Config["database_withdrawals"].".status!='Completed' OR ".$_Config["database_withdrawals"].".status!='Cancelled') AND ".$_Config["database_wmethods"].".id=".$_Config["database_withdrawals"].".method AND ".$_Config["database_details"].".user_number=".$_Config["database_withdrawals"].".user_number AND ".$_Config["database_auth"].".user_number=".$_Config["database_withdrawals"].".user_number;");

				$num       = $dc->num_rows();

				$str       = (!$str)?0:$str;

				$per_page  = (!$_Config["paginate"])?10:$_Config["paginate"];

				$dc->query("SELECT ".$_Config["database_withdrawals"].".*, ".$_Config["database_wmethods"].".name, ".$_Config["database_auth"].".real_name, ".$_Config["database_auth"].".username, ".$_Config["database_details"].".* FROM ".$_Config["database_withdrawals"].", ".$_Config["database_auth"].", ".$_Config["database_details"].", ".$_Config["database_wmethods"]." WHERE (".$_Config["database_withdrawals"].".status!='Completed' OR ".$_Config["database_withdrawals"].".status!='Cancelled') AND ".$_Config["database_wmethods"].".id=".$_Config["database_withdrawals"].".method AND ".$_Config["database_details"].".user_number=".$_Config["database_withdrawals"].".user_number AND ".$_Config["database_auth"].".user_number=".$_Config["database_withdrawals"].".user_number LIMIT $str, $per_page;");



				for ($i=0;$i<$dc->num_rows();$i++)

					{

						$dc->next_record();

						$users[$m][id]        = $dc->get("id");

						$users[$m][account]   = $dc->get("user_number");

						$users[$m][money]     = $dc->get("amount");

						$users[$m][dollars]   = $dc->get("amount")*$indexPrice;

						$users[$m][gold]      = $dc->get("crediGold");

						$users[$m][name]      = $dc->get("real_name");

						$users[$m][method]    = $dc->get("name");

						$users[$m][email]     = $dc->get("username");

						$users[$m][num]       = $dc->get("dateIt");

						$users[$m][action]    = ($dc->get("status") == "Active")?"<a href=\"index.php?cmd=withdraw&action=process&str=".get_param("str")."&id=".$dc->get("id")."\">[ PROCESS WITHDRAWAL ]</a> &nbsp;":"<a href=\"index.php?cmd=withdraw&action=complete&str=".get_param("str")."&id=".$dc->get("id")."\">[ COMPLETE WITHDRAWAL ]</a> &nbsp;";

						$users[$m][dateIt]    = strftime("%m/%d/%Y at %H:%M", $dc->get("dateIt"));

						$users[$m][phone]     = ($dc->get("phone"))?$dc->get("phone"):"-";

						$users[$m][address]   = $dc->get("address1")."<br>".$dc->get("city").", ".$dc->get("state")." ".$dc->get("zip")."<br>".$dc->get("country");

						$users[$m][memo]      = ($dc->get("memo"))?nl2br($dc->get("memo")):"User left no memo!";

						$users[$m][comment]   = ($dc->get("comment"))?"<tr><td width=15% align=right valign=top bgcolor=white class=little style=padding:5px><b>Your Comments:</b>&nbsp;</td><td align=left bgcolor=white class=little colspan=3>&nbsp;".$dc->get("comment")."</td></tr>":"";

						$users[$m][real_with] = ($dc->get("status") == "Pending")?"<form name='with".$dc->get("id")."' action=index.php method=POST><input type=hidden name=cmd value=".get_param("cmd")."><input type=hidden name=str value='".get_param("str")."'><input type=hidden name=user value='".$dc->get("user_number")."'><input type=hidden name=action value=money><tr><td width=15% align=right valign=top bgcolor=white class=little style=padding:5px><b>Withdraw:</b>&nbsp;</td><td align=left bgcolor=white class=little colspan=3>&nbsp;<input type=text name=sum value=\"".$dc->get("amount")."\" size=5> <input type=submit value='Withdraw Amount' class=box></td></tr></form>":"";

						$m++;

					}

				include("ihtml/withdraw_admin.ihtml");

			}

	}



function banner_manager()

   {

      global $dc, $action, $src, $planID, $sess, $_Config;

      if ($action == "insert")

         {

            $dc->query("INSERT INTO ".$_Config["database_banners"]." SET id='', banner_code='$src';");

            $mesg =  "<font class=head color=green>Banner added successfully!</font>";

         }

      else if ($action == "update")

         {

            $dc->query("UPDATE ".$_Config["database_banners"]." SET banner_code='$src' WHERE id='$planID';");

            $mesg = "<font class=head color=green>Banner updated successfully!</font>";

         }

      else if ($action == "delete")

         {

            $dc->query("DELETE FROM ".$_Config["database_banners"]." WHERE id='$planID';");

            $mesg = "<font class=head color=red>Banner deleted successfully!</font>";

         }

      include("ihtml/banner_manager.php");

   } // end func



function site_backup()

   {

      global $auth, $dc, $_Config;

      global $_PHPLIB, $PHP_SELF;

      if (get_param("act") == "backup")

         {

            $makeBackUp = getDatabase();

            $resource   = (!get_param("resourceBackUp"))?(strftime("%m-%d-%Y_%H-%M-%S",time()).".restore"):get_param("resourceBackUp");

            $fp         = fopen($_PHPLIB["libdir"]."backups/".$resource, "w");

            fputs($fp, $makeBackUp);

            fclose($fp);

            $messages = "<font class=head color=green>System backed up successfully!</font><br><br>";

         }

      if (get_param("act") == "restore")

         {

            $makeRestore = getRestoreFile($_PHPLIB["libdir"]."backups/".get_param("resourceRestore"));

            $x = split("\n", $makeRestore);

            for ($i=0;$i<count($x);$i++)

               {

                  $dc->query($x[$i]);

               }

            $messages = "<font class=head color=green>System restored successfully!</font><br><br>";

         }

      include("ihtml/site_backup.ihtml");

   } // end func



} // crediGold_Account class ends here



// Support Functions



function parseSize($file)

   {

      $bytes = filesize($file);

      if($bytes >= pow(1024,3))

         {

            $bytes  = round($bytes/1073741824*100)/100;

            $bytes .= "GB";

         }

      elseif($bytes >= pow(1024,2))

         {

            $bytes  = round($bytes/1048576*100)/100;

            $bytes .= "MB";

         }

      elseif($bytes >= 1024)

         {

            $bytes  = round($bytes/1024*100)/100;

            $bytes .= "K";

         }

      else $bytes .= " bytes";

      return $bytes;

   } // end func



function getDatabase()

   {

		global $dc, $sess, $_Config;

		$dc->query("SHOW TABLES");

		for ($i=0;$i<$dc->num_rows();$i++)

			{

				$dc->next_record();

				$table_name[] = $dc->get(0);

			}

	    for ($s=0;$s<count($table_name);$s++)

         {

            $dc->query("SELECT * FROM ".$table_name[$s].";");

            $dump .= "DELETE FROM ".$table_name[$s].";\n";

            for ($i=0;$i<$dc->num_rows();$i++)

               {

                  $dc->next_record();

                  $rules = "";

                  for ($j=0;$j<$dc->num_fields();$j++)

                     {

                        $rules .= (($j == 0)?"":", ")."\"".eregi_replace("(\r|\t|\n)", "<br>", addslashes($dc->get($j)))."\"";

                     }

                  $dump .= "INSERT INTO ".$table_name[$s]." VALUES ($rules);\n";

               } // end for

         } // end for

      return $dump;

   } // end func



function getRestoreFile($resource)

   {

      $fp = fopen($resource, "r");

      flock($fp, 1);

      $restore = fread($fp, filesize($resource));

      return $restore;

   } // end func



function getRestoreFiles()

   {

      global $_PHPLIB;

      $dir = opendir($_PHPLIB["libdir"]."backups/");

      while ($file = readdir($dir))

         {

            if(!is_dir($file))

               {

?>

<option value="<?=$file?>"><?=$file?> @ <?=parseSize($_PHPLIB["libdir"]."backups/".$file)?></option>

<?

               }

         } // end while

   } // end func

?>

