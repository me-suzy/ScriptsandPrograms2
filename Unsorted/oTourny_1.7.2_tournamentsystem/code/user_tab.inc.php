<? /*************************************************************************************************
Copyright (c) 2003, 2004 Nathan 'Random' Rini
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*************************************************************************************************/ if(!defined('CONFIG')) die;
 //writes basic login window
 function write_wnd_login(){global $tpl;
  $tpl->parse("MAIN->WND_LOGIN_IMAGE", "MAIN->WND_LOGIN_IMAGE");

  $tpl->parse("MAIN->WND_LOGIN", "MAIN->WND_LOGIN", 0, array(
    "FIELD_TAB_NAME"       => "Username",
    "FIELD_TAB_NAME_VALUE" => $_COOKIE["namestamp"],
    "FIELD_TAB_PASS"       => "User_PW"
   ));
 }

  //writes basic login window
 function write_wnd_team(){global $tpl, $user, $teams;
  //preload
  $teams->team($user->teams());

  if(is_array($user->teams()))
  foreach($user->teams() as $teamid) if($teamid > 0){
   $team = &$teams->team($teamid);

   if($team->id > 0)
    $tpl->parse("MAIN->WND_TEAMS_ROW", "MAIN->WND_TEAMS_ROW", 1, array(
      "WND_TEAM_NAME" => $team->get_alink_profile()
     ));
  }

  $tpl->parse("MAIN->WND_TEAMS", "MAIN->WND_TEAMS", 0, array(
    "MAIN->WND_LINK_ACON"    => ($user->get("admin") >= $GLOBALS["level_console"]) ? $tpl->parse("MAIN->WND_LINK_ACON", "MAIN->WND_LINK_ACON") : '',
    "MAIN->WND_TEAMS_HEADER" => ($tpl->fetch("MAIN->WND_TEAMS_ROW") != '') ? $tpl->parse("MAIN->WND_TEAMS_HEADER", "MAIN->WND_TEAMS_HEADER") : '',
    "WND_USER_NAME"          => $user->tagname()
   ));
 }

 //writes a list of tournys that the player is admin of
 function write_wnd_tourny_admin(){global $user, $tpl, $tournys;
  //preload
  $tournys->tourny($user->tournys_founder());

  //founder
  if(is_array($user->tournys_founder()))
  foreach($user->tournys_founder() as $id) if($id > 0){
   $tourny = &$tournys->tourny($id);

   if($tourny->id > 0)
     $tpl->parse("MAIN->WND_ATOURNY_FOUNDER", "MAIN->WND_ATOURNY_FOUNDER", 1, array(
      "WND_ATOURNY_NAME" => $tourny->get("name"),
      "WND_TOURNY_ID"   => $tourny->id
     ));
  }
  if($tpl->fetch("MAIN->WND_ATOURNY_FOUNDER") == '') $tpl->assign("MAIN->WND_ATOURNY_FOUNDER",'');

  //preload
  $tournys->tourny($user->tournys_admin());

  //admin
  if(is_array($user->tournys_admin()))
  foreach($user->tournys_admin() as $id) if($id > 0){
   $tourny = &$tournys->tourny($id);

   if($tourny->id > 0)
    $tpl->parse("MAIN->WND_ATOURNY_ADMIN", "MAIN->WND_ATOURNY_ADMIN", 1, array(
      "WND_ATOURNY_NAME" => $tourny->get("name"),
      "WND_TOURNY_ID"   => $tourny->id
     ));
  }
  if($tpl->fetch("MAIN->WND_ATOURNY_ADMIN") == '') $tpl->assign("MAIN->WND_ATOURNY_ADMIN",'');

  if($tpl->fetch("MAIN->WND_ATOURNY_ADMIN") != '' || $tpl->fetch("MAIN->WND_ATOURNY_FOUNDER") != '')
   $tpl->parse("MAIN->WND_ATOURNY", "MAIN->WND_ATOURNY");
  else $tpl->assign("MAIN->WND_ATOURNY", "");
 }

 function write_wnd_tourny_player(){global $tpl, $user, $tournys, $teams;
  //preload
  $tournys->tourny($user->tournys());

  //single tournys
  foreach($user->tournys() as $id) if($id > 0){
   $tourny = &$tournys->tourny($id);

   if($tourny->id > 0)
    $tpl->parse("MAIN->WND_TOURNY_LIST", "MAIN->WND_TOURNY_LIST", true, array(
      "WND_TOURNY_NAME" => $tourny->get("name"),
      "WND_TOURNY_ID"   => $tourny->id
     ));
  }

  $shown = array(); //default to no tournys

  //preload
  $teams->team($user->teams());

  //team tournys
  foreach($user->teams() as $teamid) if($teamid > 0){
   $team = $teams->team($teamid);

   if($team->id > 0)
   if(is_array($team->tournys()))
   foreach($team->tournys() as $id)
    if($id > 0) //possibly valid tourny
     if(!in_array($id, $shown)) { //dont repeat tournaments
      $shown[] = $id;

      $tourny = &$tournys->tourny($id);

      if($tourny->id > 0 && empty($tourny->data_mod))
       $tpl->parse("MAIN->WND_TOURNY_LIST", "MAIN->WND_TOURNY_LIST", true, array(
        "WND_TOURNY_NAME" => $tourny->get("name"),
        "WND_TOURNY_ID"   => $tourny->id
       ));
  }}

  if($tpl->fetch("MAIN->WND_TOURNY_LIST") != '')
   $tpl->parse("MAIN->WND_TOURNY", "MAIN->WND_TOURNY");
  else $tpl->assign("MAIN->WND_TOURNY", "");
 }

 function write_user_tab() {global $tpl, $user, $users;
  if($user->id > 0){
   write_wnd_team();
   write_wnd_tourny_admin();
   write_wnd_tourny_player();

   $tpl->assign(array(
     "MAIN->WND_LOGIN"       => '',
     "MAIN->WND_LOGIN_IMAGE" => ''
    ));
  }else{
   write_wnd_login();

   $tpl->assign(array(
    "MAIN->WND_ATOURNY" => '',
    "MAIN->WND_TEAMS"   => '',
    "MAIN->WND_TOURNY"  => ''
   ));
  }

  //check if login failed
  if($users->login_fail) $tpl->parse("MAIN->WND_LOGIN_ERROR", "MAIN->WND_LOGIN_ERROR");
  else $tpl->assign("MAIN->WND_LOGIN_ERROR", "");
 }

?>