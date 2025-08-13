<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  User Login System (user commands with out having to login with uid/pass)

 */



 function write_lostpass(){global $centercol, $tpl, $users;

  $centercol = "user_login_pass.tpl";



  if(isset($_POST["submit"])){

   //check uname and uemail

   $user = &$users->find_user($_POST["uname"]);



   if($user->id > 0)

   if(!strcasecmp($user->get("email"), $_POST["uemail"])){

    $user->email("login");



    $centercol = "user_login_pass_conf.tpl"; return;

  }}



  $tpl->assign(array(

    ERRORS            => isset($submit)?$tpl->fetchfile("user_login_pass_err.tpl"):'',

    FIELD_NAME_VALUE  => htmlchars($_POST["uname"]),

    FIELD_NAME        => "uname",

    FIELD_EMAIL_VALUE => htmlchars($_POST["uemail"]),

    FIELD_EMAIL       => "uemail",

    FIELD_SUBMIT      => "submit"

   ));

 }



 //easy 1 click login via key  //$uid, $key, $timestamp,

 function write_login(){global $centercol, $tpl, $users, $login_timeout, $user;

  if($user->id > 0){

   //turn off login system

   $user->gen_login_lpkey(true);



   write_refresh("?page=playercontrol&cmd=profile");

   return;

  }



  if(isset($_GET["key"]) && isset($_GET["uid"])){

   $user = &$users->user($_GET["uid"]);



   if($user->id > 0) //valid user

   if($user->get("login_key") == $_GET["key"]) //valid key

   if(($user->get("login_time") + $login_timeout) > time()){ //within timeout

    $centercol = "user_login_login.tpl";



    $tpl->assign(array(

      "TIMESTAMP"        => "user",

      "TIMESTAMP_VALUE"  => htmlchars($user->id),

      "SESSIONKEY"       => "sessionkey",

      "SESSIONKEY_VALUE" => htmlchars($user->gen_sessionkey()),

     ));



    return;

  }}



  //wrong key or something

  $centercol = "user_login_login_err.tpl";

 }



 function write_email(){global $centercol, $users, $tpl;

  $centercol = "user_login_email.tpl";



  $user = &$users->user($_GET["uid"]);



  //check if its valid

  if($user->get("authemailkey") != $_GET["key"] || time() > ($user->get("authemailtime") + $GLOBALS["login_timeout"]) ){

   $centercol = "user_login_email_err.tpl";

   return;

  }



  $user->save_email_auth();



  $tpl->assign("EMAIL", $user->get("email"));

 }



 switch($cmd){

  case "lostpass":

   write_lostpass();

   break;

  case "login":

   write_login();

   break;

  case "email": //email verify

   write_email();

   break;

 }



?>