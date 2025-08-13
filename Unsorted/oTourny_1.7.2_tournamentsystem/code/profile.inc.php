<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  Profile Page

 */



 function write_profile_team($teamid){global $centercol, $tpl, $teams, $teamstatus, $tournys;

  $centercol = "profile_team.tpl";

  $team      = &$teams->team($teamid);

  $status    = $teamstatus->get();



  if(is_array($team->tournys()))

  foreach($team->tournys() as $tournyid)

   if($tournyid > 0){

     $tourny =& $tournys->tourny($tournyid);



     $tourny_list .= $tpl->fetchfile("profile_user_row_tourny.tpl", array(

       "PROFILE_TOURNY_LINK" => "?page=tourny&tournyid=".$tourny->id,

       "PROFILE_TOURNY_NAME" => $tourny->get("name"),

      ));

   }



  $tpl->assign(array(

    "TEAMNAME"    => htmlchars($team->get("name")),

    "TEAMTAG"     => htmlchars($team->get("tag")),

    "EMAIL"       => htmlchars($team->get("email")),

    "STATUS"      => htmlchars($status[$team->get("status")]),

    "WEBSITE"     => $team->get("website"),

    "IRCSERVER"   => htmlchars($team->get("ircserv")),

    "IRCCHANNEL"  => htmlchars($team->get("irc")),

    "DESCRIPTION" => htmlchars($team->get("description")),

    "USER_TOURNAMENTS" => $tourny_list,

    "MEMBERS"     => $team->player_list()

   ));

 }



 function write_profile_player($userid){global $centercol, $tpl, $users, $teams, $tournys;

  $centercol = "profile_user.tpl";

  $user      = &$users->user($userid); //create user



  //create tourny list

  foreach($user->tournys() as $tournyid)

   if($tournyid > 0){

     $tourny =& $tournys->tourny($tournyid);



     $tourny_list .= $tpl->fetchfile("profile_team_row_tourny.tpl", array(

       "PROFILE_TOURNY_LINK" => "?page=tourny&tournyid=".$tourny->id,

       "PROFILE_TOURNY_NAME" => $tourny->get("name"),

      ));

   }

  //create team list

  foreach($user->teams() as $teamid){

   $team = &$teams->team($teamid);



   $team_list .= $tpl->fetchfile("profile_team_row_team.tpl", array(

     "PROFILE_TEAM_LINK" => $team->get_alink_profile()

    ));



   unset($team);

  }



  $tpl->assign(array(

    "NAME"        => htmlchars($user->tagname()),

    "RNAME"       => htmlchars($user->get("realname")),

    "EMAIL"       => $user->get("showemail") ? htmlchars($user->get("email")):'',

    "WEBSITE"     => $user->get("webpage"),

    "AFFILIATION" => htmlchars($user->get("affialtion")),

    "ICQ"         => $user->get("showicq")   ? htmlchars($user->get("icq")):'',

    "AIM"         => $user->get("showaim")   ? htmlchars($user->get("aim")):'',

    "MSN"         => $user->get("showmsn")   ? htmlchars( $user->get("msn")):'',

    "USER_TOURNAMENTS" => $tourny_list,

    "TEAMS"       => $team_list

   ));

 }



 switch($type){

  case "1": //User

   write_profile_player($id);

   break;

  case "2": //Team

   write_profile_team($id);

   break;

 }

?>