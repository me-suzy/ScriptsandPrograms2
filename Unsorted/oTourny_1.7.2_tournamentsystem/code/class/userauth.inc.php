<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  Pre Userauth System

 */



 $userauth_timeout = 60*60*60*5; //5 hours



 class db_users_auth extends db_table {

  var $users; //user array - holds reference to user



  function db_users_auth(){

   //notify parent of db names and class

   parent::db_table("usersauth", "userid", "db_user_auth");



   //reference class list

   $this->users =& $this->objs;

  }



  //retrieve a user

  function &user($id = 0, $create = 0){

   return parent::obj($id, $create);

  }



  //retrieve user via sessionkey

  function &user_sessionkey($sessionid){

   //find userid and use normal load command

   $query = new db_cmd("select", "usersauth", "userid", "session='".$sessionid."'", 1);

   if($query->query->db_data[0]["userid"] > 0) return $this->user($query->query->db_data[0]["userid"]);

  }



  //check all the entrys for a new user

  function check_entry($uname, $pword1, $pword2, $uemail, $coopa){global $tpl;

   $tpl->splice("ERROR", "user_signup_err.tpl");



   //check for username and email from users

   $query = new db_cmd("SELECT", "users", "userid", "name LIKE '".$uname."' OR email LIKE '".$uemail."'", 1);

   if(!empty($query->data[0])) $errchk .= write_error_row($tpl->parse("ERROR->1", "ERROR->1"));

   unset($query);



   //check for username and email from usersauth

   $query = new db_cmd("SELECT", "usersauth", "userid", "name LIKE '".$uname."' OR email LIKE '".$uemail."'", 1);

   if(!empty($query->data[0])) $errchk .= write_error_row($tpl->parse("ERROR->2", "ERROR->2"));

   unset($query);



   //check if email is valid format

   if(!checkemail($uemail)) $errchk .= write_error_row($tpl->parse("ERROR->4", "ERROR->4"));



   //check for min lengths

   if(strlen($pword1) < 5 || strlen($pword1) > 50)  $errchk .= write_error_row($tpl->parse("ERROR->5", "ERROR->5"));

   if(strlen($uname) < 3  || strlen($uname) > 50)   $errchk .= write_error_row($tpl->parse("ERROR->6", "ERROR->6"));

   if(strlen($uemail) < 3 || strlen($uemail) > 100) $errchk .= write_error_row($tpl->parse("ERROR->7", "ERROR->7"));



   //check for ambiguous entrys

   if($uname == $pword1)  $errchk .= write_error_row($tpl->parse("ERROR->8", "ERROR->8"));

   if($pword1 == $uemail) $errchk .= write_error_row($tpl->parse("ERROR->9", "ERROR->9"));



   //check for stupid peoples

   if(strtolower($pword1) == "password") $errchk .= write_error_row($tpl->parse("ERROR->10", "ERROR->10"));



   //make sure passwords are equal

   if($pword1 != $pword2) $errchk .= write_error_row($tpl->parse("ERROR->11", "ERROR->11"));



   //check if they are 13

   if(!$coopa) $errchk .= write_error_row($tpl->parse("ERROR->12", "ERROR->12"));



   return $errchk;

  }

 }



 class db_user_auth extends db_obj {

  function db_user_auth($id, &$data, &$container){

   parent::db_obj($id, &$data, &$container);

  }



  //email user sessionkey

  function email(){global $tpl;

   mail($this->get("email"), $tpl->fetchfile("email_userauth_title.tpl"),

    $tpl->fetchfile("email_userauth_content.tpl", array(

      "EMAIL_SESSION_KEY" => $this->get("session"),

      "EMAIL_NAME"        => $this->get("name"),

      "EMAIL_PASS"        => $this->get("password"),

     )), $tpl->fetchfile("email_userauth_xtra.tpl"));

  }

 }



?>