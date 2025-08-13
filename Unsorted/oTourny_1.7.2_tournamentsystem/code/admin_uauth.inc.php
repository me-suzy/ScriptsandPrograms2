<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;



 function write_uauth_list(){global $apanel, $tpl, $usersauth;

  $tpl->splice("TOURNY", "ap_uauth.tpl");



  //hide activation section

  $tpl->assign("TOURNY->ACT", '');



  if(isset($_GET["cmdd"]))

  switch($_GET["cmdd"]){

   case "1": //del

    $user = &$usersauth->user((INT) $_GET["id"]);

    $user->delete();

    unset($user);



    write_refresh("?page=admin&cmd=uauth"); return;

    break;

   case "2": //act

    $user = &$usersauth->user((INT) $_GET["id"]);



    $tpl->parse("TOURNY->ACT", "TOURNY->ACT", array(

      "PLAYER" => $user->get("name"),

      "LINK"   => "?page=playersignup&sessionid=". $user->get("session")

     ));

    unset($user);

    break;

   case "3": //email

    $user = &$usersauth->user((INT) $_GET["id"]);

    $user->email();

    unset($user);



    write_refresh("?page=admin&cmd=uauth"); return;

    break;

   case "4": //del all

    new db_cmd("DELETE", "usersauth");



    write_refresh("?page=admin&cmd=uauth"); return;

    break;

   case "5": //email all

    $query = new db_cmd("select", "usersauth", "userid");

    if(!empty($query->data))

     foreach($query->data as $userid){

      $user = &$usersauth->user($userid);

      if($user->id > 0) $user->email();

      unset($user);

     }



    write_refresh("?page=admin&cmd=uauth"); return;

    break;

  }



  $query = new db_cmd("select", "usersauth", "userid");

  if(!empty($query->data))

   foreach($query->data as $userid)

   if($userid > 0)

   {

    $user = &$usersauth->user($userid["userid"]);



    $tpl->parse("TOURNY->USERS", "TOURNY->USER_ROW", true,array(

      "CLASS"    => ($i%2)?"row":"rowoff",

      "PLAYER"   => $user->get("name"),

      "EMAIL"    => $user->get("email"),

      "LINK_DEL" => "?page=admin&cmd=uauth&cmdd=1&id=".$user->id,

      "LINK_ACT" => "?page=admin&cmd=uauth&cmdd=2&id=".$user->id,

      "LINK_RES" => "?page=admin&cmd=uauth&cmdd=3&id=".$user->id

     ));



    unset($user);

   }



  //check for nulls

  if($tpl->fetch("TOURNY->USERS") == '')

   $tpl->parse("TOURNY->USERS", "TOURNY->USER_NONE");



  $tpl->parse("CONTENT", "TOURNY");

 }



 write_uauth_list();



?>