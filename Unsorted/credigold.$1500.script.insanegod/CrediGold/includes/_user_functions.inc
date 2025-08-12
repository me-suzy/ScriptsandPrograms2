<?php

/*----------------[			crediGold User Functions	      ]---------------*/

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



DEFINE(NO_SUCH_DB_ENTRY, -50);

DEFINE(INVALID_USERNAME_PASSWORD, -51);

DEFINE(WRONG_ACCESS_CODE, -52);

DEFINE(NOT_ALLOWED_TO_USE_ACCOUNT, -53);

DEFINE(BANNED_FROM_SYSTEM, -54);



class Credigold_Auth extends Auth {



  var $classname        = "Credigold_Auth";

  var $lifetime         =  15;



  var $magic            = "FlashGet_1.1";  ## Challenge seed

  var $database_class   = "DB_Credigold";

  var $database_table   = "user_auth";

  var $database_details = "user_details";



  var $mode = "log";



###

#	Login Form Function

###################################

function define_error($code) {



   switch($code) {

     case NO_SUCH_DB_ENTRY:

       set_session("login_error", "No such user number in our DB!");

       set_session("sub_login_error", "Your user number must be 7 digits number and no letters!");

     break;

     case INVALID_USERNAME_PASSWORD:

       set_session("login_error", "Invalid Username or Password!");

       set_session("sub_login_error", "You must provide a valid user name (your email) and  password!");

     break;

     case WRONG_ACCESS_CODE:

       set_session("login_error", "Wrong Access Code entered!");

       set_session("sub_login_error", "Please, copy the Access Code as it is in the access code image!");

     break;

     case NOT_ALLOWED_TO_USE_ACCOUNT:

       set_session("login_error", "You're not allowed to use this account!");

       set_session("sub_login_error", "Your <b>IP</b> has been denied access by the owner of this account!");

     break;

     case BANNED_FROM_SYSTEM:

       set_session("login_error", "You're banned from the system!");

       set_session("sub_login_error", "Your <b>IP</b> has been denied access globaly for the site!");

     break;

     default:

       session_unregister("login_error");

       session_unregister("sub_login_error");

     break;



   } // end switch

} // end function

###

#	Login Form Function

###################################

function auth_loginform() {

    global $sess, $_PHPLIB;

    global $challenge, $_Config;



   if ( get_param("merchantAccount") )

      {

         $this->shop["merchantAccount"] = get_param("merchantAccount");

         $this->shop["companyName"] = get_param("companyName");

         $this->shop["companyLogo"] = get_param("companyLogo");

         $this->shop["companyText"] = get_param("companyText");

         $this->shop["amount"] = get_param("amount");

         $this->shop["memo"] = get_param("memo");

         $this->shop["cartImage"] = get_param("cartImage");

      }



    if (!$challenge) $challenge = md5(uniqid($this->magic));

    $sess->register("challenge");



    include("ihtml/loginform.ihtml");

  }



###

#	Validate Login

###################################

function auth_validatelogin() {

    global $username, $password, $challenge, $response, $access_codea, $userNumber, $dc, $sess;

	global $_Config;



    (get_param("username"))?$this->auth["uname"]=get_param("username"):"";        ## This provides access for "loginform.ihtml"

    (get_param("userNumber"))?$this->auth["unumber"] = get_param("userNumber"):

                              $this->auth["unumber"] = get_session("user_number");	 ## This provides access for "loginform.ihtml"



   $now = date("YmdHis", time());

   $this->db->query("UPDATE access_codes SET input_code='".$access_codea."', "

                   ."changed='".$now."' WHERE user_id='".session_id()."'");



   $this->db->query(sprintf("select user_id, username, real_name, perms, password, active, referrer ".

                            "from %s where user_number = '%s'",

                            $this->database_table,

                            $this->auth["unumber"] ));



    while($this->db->next_record()) {

      $uid   = $this->db->f("user_id");

      $perm  = $this->db->f("perms");

      $pass  = $this->db->f("password");   ## Password is stored as a md5 hash

      $uName = $this->auth["username"] = $this->db->f("username");

      $this->auth["real_name"] = $this->db->get("real_name");

      $this->auth["userNumber"] = $userNumber;

      $this->auth["referrer"] = $this->db->get("referrer");

    }



    $dc->query(sprintf("select real_name ".

                       "from %s where user_number = '%s'",

                       $this->database_table,

                       $this->auth["referrer"]));



    $dc->next_record();

    $this->auth["referrer_name"] = $dc->get("real_name");



   if ($this->db->nf() == 0)

      {

         set_session("error_code", NO_SUCH_DB_ENTRY);

      return false;

      }

   else {



    $exspected_response = md5($uName.":$pass:".get_param("challenge"));



    ## True when JS is disabled

    if ($response == "") {

      if (md5($password) != $pass || get_param("username") != $uName) {       ## md5 hash for non-JavaScript browsers



         set_session("error_code", INVALID_USERNAME_PASSWORD);



        return false;

      } else {



        $this->auth["perm"] = $perm;

        return $uid;

      }

    }// if





    ## Response is set, JS is enabled

    if ($exspected_response != get_param("response")) {



         set_session("error_code", INVALID_USERNAME_PASSWORD);



      return false;

    } else {



      $this->auth["perm"] = $perm;

      return $uid;

    } // else



   } // else



  }



###

#	Second Stage Login Checks

###################################

function stage2login(){

global $account, $dc, $sess, $_Config, $_PHPLIB, $auth, $PHP_SELF;



$this->db->query("SELECT a.input_code, a.access_code, u.active, u.ip_blocking "

                  ."FROM access_codes a, $this->database_table u "

                  ."WHERE a.user_id='".session_id()."' AND u.user_id='".$this->auth["uid"]."'");



$this->db->next_record();

$dc->query("SELECT ip_blocking FROM $this->database_table WHERE username='ip_blocking@credigold.com'");

$dc->next_record();



      if ( strtolower($this->db->f("input_code")) != strtolower($this->db->f("access_code")) )

         {

            $this->logout();



            set_session("error_code", WRONG_ACCESS_CODE);

            set_session("user_number", $this->auth["unumber"]);

            $_SESSION["kupo"] = "blabla";



            header("Location: ".$this->url()."");

            exit();

         } // end if

      if ($this->db->f("active") == N) { $this->activate_account();	}

      if ($this->db->f("ip_blocking") || $dc->get("ip_blocking") )

         {

            global $sess;



            $user_ip = split("\.", getIP());

            $all_ips = split("::", $this->db->f("ip_blocking"));

            $all_ips_global = split("::", $dc->get("ip_blocking"));

            for ($i=0;$i<count($all_ips);$i++)

               {

                  $temp_ips = split("\.", $all_ips[$i]);

                  if ($temp_ips[0] == $user_ip[0] || $temp_ips[0] == "*")

                     {

                        if 	($temp_ips[1] == $user_ip[1] || $temp_ips[1] == "*")

                           {

                              if 	($temp_ips[2] == $user_ip[2] || $temp_ips[2] == "*")

                                 {

                                    if 	($temp_ips[3] == $user_ip[3] || $temp_ips[3] == "*")

                                       {

                                          $this->logout();

                                          set_session("error_code", NOT_ALLOWED_TO_USE_ACCOUNT);

                                          header("Location: ".$this->url()."");

                                       } // if

                                 } // if

                           } // if

                     } // if

               } // for



            for ($i=0;$i<count($all_ips_global);$i++)

               {

                  $temp_ips = split("\.", $all_ips_global[$i]);

                  if ($temp_ips[0] == $user_ip[0] || $temp_ips[0] == "*")

                     {

                        if 	($temp_ips[1] == $user_ip[1] || $temp_ips[1] == "*")

                           {

                              if 	($temp_ips[2] == $user_ip[2] || $temp_ips[2] == "*")

                                 {

                                    if 	($temp_ips[3] == $user_ip[3] || $temp_ips[3] == "*")

                                       {

                                          $this->logout();

                                          set_session("error_code", BANNED_FROM_SYSTEM);

                                          header("Location: ".$this->url()."");

                                       } // if

                                 } // if

                           } // if

                     } // if

               } // for

         } // if



   if ( !get_session("logging") ) {

      include($_PHPLIB["moddir"]."lib.codes.php");



      $now = date("YmdHis", time());



      $buffer = "";

      if(!$sock = @fsockopen($_Config["WhoIsServer"], 43, &$num, &$error, 10))	unset($sock);

      else 

		  {

	         fputs($sock, getIP()."\n");

		     while (!feof($sock)) $buffer .= fgets($sock, 10240);

			 fclose($sock);



			  $pos = strpos ($msg, "country:"); // Country Extract

			  if ($pos>0)	$rest = substr ($msg, $pos+44, 2);

			  $rest = str_replace(" ", "", $rest);



			  $pos1 = strpos ($msg, "descr:"); // ISP Extract

			  if ($pos1>0)	$rest1 = substr ($msg, $pos1+54, 100);

			  $rest2 = split("\n", $rest1);

			  $rests = eregi_replace("<BR />","",$rest2[0]);



			 $rests = (!eregi("The whole IPv4 address space", $rests) && $rests != "")?$rests:"Unknown ISP";

			 $rest  = (!$rest || (eregi("The whole IPv4 address space", $rest)))?"US":$rest;



			 $this->db->query("SELECT user_number FROM ".$_Config["database_logging"]."

							   WHERE sid='".session_id()."';");



			 if ( $this->db->nf() > 0 ) {

				$this->db->free();

			 } else {

				$this->db->free();

				$this->db->query("INSERT INTO ".$_Config["database_logging"]." SET

								  sid='".session_id()."', `ip`='".getIP()."',

								  start_time='".$now."', isp='".$rests."', country_code='".$rest."',

								  country='".$codes[$rest]."', user_number='".$this->auth["userNumber"]."';");

			 }

      } // end else



	if ($_Config["track_online"])

		{

			$this->db->query("DELETE FROM ".$_Config["database_online"]." WHERE IP='".getIP()."' AND account='Guest';");

			$this->db->query("INSERT INTO ".$_Config["database_online"]." SET account='".$this->auth["userNumber"]."', IP='".getIP()."', last='".time()."', url='".getURL()."';");		

		}

     set_session("logging", "true");

   } // end if



} // end func



###

#	Logout Function

###################################

function logout_success()

   {

	  global $_Config;

      initPage();

      include("ihtml/logout_success.ihtml");

      endPage();

      $this->logout();

      page_close();



   exit;

   }



###

#	Register User Form

###################################

function auth_registerform()

   {

	   global $_Config;

       include("ihtml/regform.ihtml");

   }



###

#	Do Register Funtion

###################################

function auth_doregister()

   {

      $mailBody = "";

      $hash_secret = "DreamCatcher...";

      $u_id = md5(uniqid($hash_secret));

      $act_code = md5(uniqid($hash_secret));

      unset($this->auth["errora"]);



      global $_Config;



   if (get_param("submission")) {



      if (!get_param("hashpass")) {

        if(get_param("passwd") && get_param("repasswd")) {

         $password = md5(get_param("passwd"));

         $repassword = md5(get_param("repasswd"));

        } else {

         $password = "";

         $repassword = "";

        }

      } else {

        $password = get_param("hashpass");

        $repassword = get_param("repass");

      }



   $this->db->query(sprintf("SELECT user_number FROM %s WHERE user_number='%s'", $this->database_table, get_param("referrer")));

   if ($this->db->nf() == 0) {	$referrer_error = true;	 }	 else {	$referrer_error = false;	}



   $this->db->query(sprintf("SELECT username FROM %s WHERE username='%s' OR user_id='%s'",

                              $this->database_table,

                              get_param("email"),

                              $u_id));

   $this->db->next_record();



   if ($this->db->nf())

      {

         $this->auth["errora"] = "email::";

         set_session("reg_error", "The username ".get_param("email")." already exists!");

         set_session("sub_reg_error", "The username must be unique for each user on the site!");

      return false;

      }

   elseif ($password != $repassword)

      {

         $this->auth["errora"] = "password::";

         set_session("reg_error", "The two passwords do not match!");

         set_session("sub_reg_error", "You must enter a password and to re-enter it for verification!");

      return false;

      }

   elseif ($password == "" || $repassword == "" )

      {

         $this->auth["errora"] = "password::";

         set_session("reg_error", "Password Error! Please, enter a valid password.");

         set_session("sub_reg_error", "You must enter a pasword at leart 6 chars long! (dont use \" and ')");

      return false;

      }

   elseif (!get_param("secret_answer") || get_param("secret_answer") == "" || strlen(get_param("secret_answer")) < 5)

      {

         $this->auth["errora"] = "secret::";

         set_session("reg_error", "Invalid secret answer entered!");

         set_session("sub_reg_error", "The secret question and answer is used for various admin account features!");

       return false;

      }

   elseif (!get_param("realname") || get_param("realname") == "")

      {

         $this->auth["errora"] = "realname::";

         set_session("reg_error", "Registration Error! Please, review the items in red.");

         set_session("sub_reg_error", "All fields with a red circle must be filled correctly!");

      return false;

      }

   elseif (

            (!get_param("location1") || get_param("location1") == "") ||

            (!get_param("city") || get_param("city") == "") ||

            (!get_param("zip_code") || get_param("zip_code") == "") ||

            (!get_param("country") || get_param("country") == "") ||

            ( get_param("icq") && !is_number(get_param("icq")) ) ||

            ( get_param("referrer") && (!is_number(get_param("referrer")) || $referrer_error) )

                                                                           )

      {

         set_session("reg_error", "Registration Error! Please, review the items in red.");

         set_session("sub_reg_error", "All fields with a red circle must be filled correctly!");

         if (!get_param("location1") || get_param("location1") == "") $this->auth["errora"] .= "location::";

         if (!get_param("city") || get_param("city") == "") $this->auth["errora"] .= "city::";

         if (!get_param("zip_code") || get_param("zip_code") == "") $this->auth["errora"] .= "zip_code::";

         if (!get_param("country") || get_param("country") == "") $this->auth["errora"] .= "country::";

         if ( get_param("icq") && !is_number(get_param("icq")) ) $this->auth["errora"] .= "icq::";

         if ( get_param("referrer") && (!is_number(get_param("referrer")) || $referrer_error) ) $this->auth["errora"] .= "referrer::";



      return false;

      }

   else

      {

         $this->db->query("SELECT user_number FROM $this->database_table");

         while($this->db->next_record()) { $new_user_number = $this->db->f("user_number") + 1; }

         $lenght = strlen($new_user_number);

         for ($i=0; $i<(7 - $lenght); $i++) { $new_user_number = "0".$new_user_number; }



         $now = date("YmdHis", time());

         $this->db->query(

            sprintf("INSERT INTO %s SET user_number='%s', user_id='%s', username='%s',

            password='%s', unmd5='%s', perms='user', real_name='%s', active='N',

            act_code='%s', ip_blocking='', ip_access='', `lock`='N', referrer='%s', dateIt='%s'",

            $this->database_table,

            $new_user_number,

            $u_id,

            get_param("email"),

            $password,

            get_param("repasswd"),

            get_param("realname"),

            $act_code,

            get_param("referrer"),

            $now )

         );



         $this->db->query(sprintf("INSERT INTO %s SET user_id='%s', user_number='%s', question='%s', answer='%s', sp_made='%s', sp_received='%s'"

                                 .", address1='%s', address2='%s', city='%s', zip='%s', state='%s', country='%s', phone='%s', fax='%s'"

                                 .", site='%s', yahoo='%s', icq='%s', aim='%s', msn='%s', newsletter='%s'",

                                 $this->database_details, $u_id, $new_user_number, get_param("secret_question"), get_param("secret_answer"),

                                 get_param("sp_made"), get_param("sp_received"), get_param("location1"), get_param("location2"),

                                 get_param("city"), get_param("zip_code"), get_param("state"), get_param("country"), get_param("phone"),

                                 get_param("fax"), get_param("site"), get_param("yahoo"), get_param("icq"), get_param("aim"),

                                 get_param("msn"), get_param("newsletter") ));



         $this->db->query("SELECT body, subject FROM ".$_Config["database_emails"]." WHERE id='1';");

         $this->db->next_record();

         $email_body = $this->db->f("body");

         $email_subj = $this->db->f("subject");



         $newBodyExtract = eregi_replace("%name%", get_param("realname"), $email_body);

         $newBodyExtract = eregi_replace("%userName%", get_param("email"), $newBodyExtract);

         $newBodyExtract = eregi_replace("%accountNumber%", $new_user_number, $newBodyExtract);

         $newBodyExtract = eregi_replace("%activation_code%", $act_code, $newBodyExtract);

         $newBodyExtract = eregi_replace("%siteName%", $_Config["masterRef"], $newBodyExtract);



         mailTO(get_param("email"), $email_subj, $newBodyExtract);



      return true;

      } // end else



     } // if

   } // auth_doregister() ends here



###

#	Account Successfully Registered

###################################

function auth_reg_success()

   {

	  global $_Config;

      initPage();

      include("ihtml/reg_success.ihtml");

      endPage();

      page_close();



   exit;

   }



###

#	User Login/Logout Functions

###################################

function who_is()

   {

      global $_PHPLIB;

      if ( strlen($this->auth["uid"]) > 30 )

         {

            $func = "Active user is: <font color=#0050A0><b>".$this->auth["username"]."</b>&nbsp;</font>";

         } // if

      else

         {

            $func =  "You are not logged in. Login From <a href='".$_PHPLIB["http_path"]."/transfer.php'>Here</a>.&nbsp;";

         } // else

      return $func;

   } // if



function login_logout()

   {

   global  $_PHPLIB;



      if ( strlen($this->auth["uid"]) > 30 )

         {

            $func  = "Account Number: <font color=#0050A0><b>".$this->auth["userNumber"]."</b>&nbsp;</font>\n";

            $func .= "(<a href=".$_PHPLIB["http_path"]."/account.php?cmd=edit onMouseOver=\"getStatus('Edit your account!');return true\" onMouseOut=\"getStatus('');return true\">Edit Profile</a> / \n";

            $func .= "<a href=".$_PHPLIB["http_path"]."/account.php?cmd=logout onMouseOver=\"getStatus('Logout from your account!');return true\" onMouseOut=\"getStatus('');return true\">Logout</a>)&nbsp;";

         }

      return $func;

   }



###

#	Locked Account Warning

###################################

function locked_account_warning()

   {

		global $_PHPLIB;

		global $_Config;

      $this->db->query(sprintf("SELECT `lock` FROM %s WHERE user_id='%s'", $this->database_table, $this->auth["uid"]));

      $this->db->next_record();

      if ($this->db->f("lock") == Y)

         {

             include("ihtml/locked_account_warning.ihtml");

            endPage();

            page_close();



         exit;

         }

   return true;

   }



###

#	Activate Account

###################################

function activate_account()

   {

	global $_Config;

   if ( get_param("act_code") )

      {

         $this->db->query(sprintf("SELECT act_code FROM %s WHERE user_id='%s'" ,

                                    $this->database_table,

                                    $this->auth["uid"]));

         $this->db->next_record();



         if ( $this->db->f("act_code") == get_param("act_code") )

            {

               set_session("act_success", "Account successfully activated!");

               set_session("sub_act_success", "Your account is now fully enabled/verified and ready for use!");

               $this->db->query(sprintf("UPDATE %s SET active='Y' WHERE user_id='%s'" ,

                                    $this->database_table,

                                    $this->auth["uid"]));

            }

         else

            {

               set_session("act_error", "Invalid \"Activation Code\" entered!");

               set_session("sub_act_error", "The activation code must have been sent to your email upon registration!");

            }

      }

      initPage("", 3);

      include("ihtml/activate_account.ihtml");

      endPage();

      page_close();



   exit;

   }



} // Credigold_Auth Class End

?>