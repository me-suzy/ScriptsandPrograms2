<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  Tournament Auth Class

 */



 class db_tourny_auths extends db_table {

  var $tournys; //tourny array - holds reference to tourny



  function db_tourny_auths(){

   //notify parent of db names and class

   parent::db_table("tournaments_auth", "id", "db_tourny_auth");



   //reference class list

   $this->tournys =& $this->objs;

  }



  //retrieve a tourny

  function &tourny($id = 0, $create = 0){

   return parent::obj($id, $create);

  }



  //find tourny

  function &find_tourny($name){

   $query = new db_cmd("select", "tournaments_auth", "id", "name LIKE '".$name."'", 1);



   return $this->tourny($query->data[0]["id"]);

  }



  //check all entries

  function check($name, $type, $purpose){global $tpl;

   //check name

    //check for dubs

    $query = new db_cmd("SELECT", "tournaments", "tournamentid", "name LIKE '".$name."'", 1);

    if(!empty($query->data[0])) $errchk .= write_error_row($tpl->parse("TOURNY->ERRORS_NAME", "TOURNY->ERRORS_NAME"));

    unset($query);



    //check for dubs

    $query = new db_cmd("SELECT", "tournaments_auth", "id", "name LIKE '".$name."'", 1);

    if(!empty($query->data[0])) $errchk .= write_error_row($tpl->parse("TOURNY->ERRORS_NAME", "TOURNY->ERRORS_NAME"));

    unset($query);



    if(strlen($name) > 150 || strlen($name) < 10) $errchk .= write_error_row($tpl->parse("TOURNY->ERRORS_NAME_LEN", "TOURNY->ERRORS_NAME_LEN"));

   //check type

    if($type != 1 && $type != 2)

     $errchk .= write_error_row($tpl->parse("TOURNY->ERRORS_TYPE", "TOURNY->ERRORS_TYPE"));

   //check purpose

    if(strlen($purpose) > 1500) $errchk .= write_error_row($tpl->parse("TOURNY->ERRORS_PURPOSE_LEN", "TOURNY->ERRORS_PURPOSE_LEN"));



   return $errchk;

  }



  //retrieve first tourny

  function first(){

   $query = new db_cmd("select", "tournaments_auth", "id", '', 1);



   return $this->tourny($query->data[0]["id"]);

  }



 }



 class db_tourny_auth extends db_obj {

  function db_tourny_auth($id, &$data, &$container){

   parent::db_obj($id, &$data, &$container);

  }



  //notify users of approval or denial

  function email($approve, $reason){global $users, $tpl;

   $user = &$users->user($this->get("creator"));



   if($approve){//approved

    mail($user->get("email"), $tpl->fetchfile("email_tourny_approve_title.tpl"),

     $tpl->fetchfile("email_tourny_approve_content.tpl", array(

       "USER_NAME"   => $user->get("name"),

       "TOURNY_NAME" => $this->get("name"),

       "REASON"      => $reason

      )), $tpl->fetchfile("email_tourny_approve_xtra.tpl"));

   }else{//deny

    mail($user->get("email"), $tpl->fetchfile("email_tourny_deny_title.tpl"),

     $tpl->fetchfile("email_tourny_deny_content.tpl", array(

       "USER_NAME"   => $user->get("name"),

       "TOURNY_NAME" => $this->get("name"),

       "REASON"      => $reason

      )), $tpl->fetchfile("email_tourny_deny_xtra.tpl"));

   }

  }

 }

?>