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

include("prepend.php3");

initPage();



if (!isset($challenge)) $challenge = md5(uniqid("KupoKoMaPa"));



if ($cmd == "forgot")

{



      $dc->query(sprintf("SELECT u.password, u.unmd5, u.real_name, u.lock, u.username, d.question, d.answer ".

                           "FROM ".$_Config["database_auth"]." u, ".$_Config["database_details"]." d WHERE u.user_number = '%s' AND d.user_number='%s' ",

                            get_param("userNumber"),

                            get_param("userNumber") ));

      $dc->next_record();

      $usernumbera = get_param("userNumber");

      $usernamea   = $dc->get("username");

      $secreta     = $dc->get("question");

      $answera     = $dc->get("answer");

      $realnamea   = $dc->get("real_name");

      $unmd5       = $dc->get("unmd5");



      if ( $dc->num_rows()>0 )

         {

            $expected_response = MD5("$usernumbera:$usernamea:$secreta:$answera:$challenge");

            if ( $expected_response != get_param("response") )

               {

                  set_session("passwd_error", "Account Information Incorrect!");

                  set_session("sub_passwd_error", "One of the requested fields Username or Secret Question/Answer is incorrect!");

               } // if

            else

               {

                  $dc->query("SELECT body, subject FROM ".$_Config["database_emails"]." WHERE id='2';");

                  $dc->next_record();

                  $email_body = $dc->get("body");

                  $email_subj = $dc->get("subject");



                  $newBodyExtract = eregi_replace("%name%", $realnamea, $email_body);

                  $newBodyExtract = eregi_replace("%password%", $unmd5, $newBodyExtract);

                  $newBodyExtract = eregi_replace("%siteName%", $_Config["masterRef"], $newBodyExtract);

                  mailTO($usernamea, $email_subj, $newBodyExtract);



                  include("ihtml/forgot_success.ihtml");

                  endPage();

                  page_close();

                  exit;

               } // else

         } // if

      else

         {

            set_session("passwd_error", "Invalid Account Number!");

            set_session("sub_passwd_error", "Your account number must be 7 digits and a valid one.");

         } // else

} // if



include("ihtml/forgot.ihtml");

endPage();

page_close();

exit;

?>