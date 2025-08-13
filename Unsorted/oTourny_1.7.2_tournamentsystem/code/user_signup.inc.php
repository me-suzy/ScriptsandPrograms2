<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  User Signup System

 */



 function write_usersignup(){global $centercol, $tpl, $usersauth, $users;

  //$activate, $sessionid, $coopa,



  if(isset($_POST["sessionid"])){

   $stage = 3; //set default stage



   //find user by sessionkey

   $user = &$usersauth->user_sessionkey($_POST["sessionid"]);



   //if real go on

   if(is_object($user))

   if($user->id > 0){

    $stage = 4; //set stage



    //create real user

    $new_user = &$users->user(0,1);

    $new_user->set(array(

      "name"     => $user->get("name"),

      "password" => hash($user->get("password")),

      "email"    => $user->get("email")

     ));



    //save to forum

    $GLOBALS["forum"]->add_user($new_user, 1);



    //clear pre user

    $user->delete();

  }}else{

   if(isset($_GET["sessionid"])) $stage = 3; //first part of activation

   else{

    $stage = 1;



    if(isset($_POST["uname"])){

     //trim all entrys

     $_POST["uname"]  = trim($_POST["uname"]);

     $_POST["pword1"] = trim($_POST["pword1"]);

     $_POST["pword2"] = trim($_POST["pword2"]);

     $_POST["uemail"] = trim($_POST["uemail"]);



     //check the entrys

     $errchk = $usersauth->check_entry($_POST["uname"], $_POST["pword1"], $_POST["pword2"], $_POST["uemail"], $_POST["coopa"]);



     if($errchk == ''){//its good

      $stage = 2;



      $user = &$usersauth->user(0, 1); //create pre user

      $user->set(array(

        "name"        => $_POST["uname"],

        "password"    => $_POST["pword1"],

        "email"       => $_POST["uemail"],

        "session"     => $user->id . gen_rand_str(50),

        "requestime"  => time(),

       ));

      $user->email(); //email sessionkey

  }}}}



  switch($stage){

   case "1":

    $centercol = "user_signup_stage_1.tpl";

    $tpl->assign(array(

      ERRORS                => $errchk==''?'':write_error_common($errchk),

      FIELD_NAME            => "uname",

      FIELD_NAME_VALUE      => htmlchars($_POST["uname"]),

      FIELD_PASS1           => "pword1",

      FIELD_PASS2           => "pword2",

      FIELD_EMAIL           => "uemail",

      FIELD_EMAIL_VALUE     => htmlchars($_POST["uemail"]),

      FIELD_COOPA           => "coopa",

      FIELD_COOPA_VALUE     => "1",

      USER_SIGNUP_AGREEMENT => $tpl->fetchfile("policy_signup.tpl")

     ));

    break;

   case "2":

    $centercol = "user_signup_stage_2.tpl";

    break;

   case "3":

    $centercol = "user_signup_stage_3.tpl";

    $tpl->assign(array(

      FIELD_SESSIONID_VALUE => htmlchars($_GET["sessionid"]),

      FIELD_SESSIONID       => "sessionid",

     ));

    break;

   case "4":

    $centercol = "user_signup_stage_4.tpl";



    $new_user->set("ip", clientip());



    $tpl->assign(array(

      FIELD_SESSIONID_VALUE => htmlchars($new_user->gen_sessionkey()),

      FIELD_SESSIONID       => "sessionkey",

      FIELD_TIMESTAMP_VALUE => $new_user->id,

      FIELD_TIMESTAMP       => "user",

     ));

    break;

  }

 }



 write_usersignup();

?>