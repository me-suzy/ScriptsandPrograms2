<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;



 function write_profile(){global $centercol, $tpl, $team, $teamstatus, $teams;

  if(isset($_POST["submit"]))

  if($teamstatus->check($_POST["teamstatus"])) //hack check - make sure its one of them

  if( ($errchk =

    ($team->get("name")==$_POST["team_name"]?'':$teams->check("name", $_POST["team_name"])) .

    ($team->get("password")==$_POST["team_pass"]?'':$teams->check("pass", $_POST["team_pass"])) .

    ($team->get("tag")==$_POST["team_tag"]?'':$teams->check("tag", $_POST["team_tag"])) .

    ($team->get("email")==$_POST["playeremail"]?'':$teams->check("email", $_POST["playeremail"])) .

    ($team->get("website")==$_POST["Webpage"]?'':$teams->check("web", $_POST["Webpage"])) .

    ($team->get("ircserv")==$_POST["ircserver"]?'':$teams->check("ircs", $_POST["ircserver"])) .

    ($team->get("irc")==$_POST["ircchan"]?'':$teams->check("ircc", $_POST["ircchan"])) .

    ($team->get("description")==$_POST["teamdescription"]?'':$teams->check("desc", $_POST["teamdescription"]))

    ) == ''){

   while(@list ($line_num, $line) = @each($_POST["Prefered_Location"]))

    $Prefered_Loc = addlistlocation($Prefered_Loc, $line);



   while(@list ($line_num, $line) = @each($_POST["Games_Played"]))

    $Games_Play = addlistgame($Games_Play, $line);



   $team->set(array(

     "name"         => htmlchars($_POST["team_name"]),

     "password"     => hash($_POST["team_pass"]),

     "tag"          => htmlchars($_POST["team_tag"]),

     "tagside"      => ($_POST["team_tagside"]==1) ? 1 : 0,

     "status"       => $teamstatus->get($_POST["teamstatus"]),

     "email"        => htmlchars($_POST["playeremail"]),

     "website"      => htmlchars($_POST["Webpage"]),

     "ircserv"      => htmlchars($_POST["ircserver"]),

     "irc"          => htmlchars($_POST["ircchan"]),

     "description"  => htmlchars(substr($_POST["teamdescription"], 0, 1000)),

     "servlocation" => $Prefered_Loc,

     "games"        => $Games_Play

    ));



   write_refresh($team->get_link_profile_loc());

   return;

  }



  $centercol = "team_panel_profile.tpl";



  //create status list

  foreach($teamstatus->get() as $key => $status)

   $statuslist .= write_option($status, $team->get("status") == $key);



  $tpl->assign(array(

    "ERRORS"                       => $errchk == ''?'':write_error_common($errchk),

    "TEAM_NAME"                    => $team->get("name"),

    "FIELD_TEAM_NAME"              => "team_name",

    "FIELD_TEAM_NAME_VALUE"        => isset($_POST["team_name"])? htmlchars($_POST["team_name"]) : htmlchars($team->get("name")),

    "FIELD_TEAM_PASS"              => "team_pass",

    "FIELD_TEAM_TAG"               => "team_tag",

    "FIELD_TEAM_TAG_VALUE"         => isset($_POST["team_tag"])? htmlchars($_POST["team_tag"]) : htmlchars($team->get("tag")),

    "FIELD_TEAM_TAG_SIDE"          => "team_tagside",

    "FIELD_TEAM_TAG_SIDE_VALUE"    => isset($_POST["team_tagside"])? htmlchars($_POST["team_tagside"]) : htmlchars($team->get("tagside")),

    "FIELD_TEAM_EMAIL"             => "playeremail",

    "FIELD_TEAM_EMAIL_VALUE"       => isset($_POST["playeremail"])? htmlchars($_POST["playeremail"]) : htmlchars($team->get("email")),

    "FIELD_TEAM_SRVLOCATION"       => "Prefered_Location[]",

    "FIELD_TEAM_SRVLOCATION_VALUE" => write_srvlocation_optlist($team->get("servlocation")),

    "FIELD_TEAM_GAMES"             => "Games_Played[]",

    "FIELD_TEAM_GAMES_VALUE"       => write_game_optlist($team->get("games")),

    "FIELD_TEAM_STATUS"            => "teamstatus",

    "FIELD_TEAM_STATUS_VALUE"      => $statuslist,

    "FIELD_TEAM_WEBPAGE"           => "Webpage",

    "FIELD_TEAM_WEBPAGE_VALUE"     => isset($_POST["Webpage"])? htmlchars($_POST["Webpage"]) : htmlchars($team->get("website")),

    "FIELD_TEAM_IRCSERV"           => "ircserver",

    "FIELD_TEAM_IRCSERV_VALUE"     => isset($_POST["ircserver"])? htmlchars($_POST["ircserver"]) : htmlchars($team->get("irc")),

    "FIELD_TEAM_IRCCHAN"           => "ircchan",

    "FIELD_TEAM_IRCCHAN_VALUE"     => isset($_POST["ircchan"])? htmlchars($_POST["ircchan"]) : htmlchars($team->get("ircserv")),

    "FIELD_TEAM_DESCRIPTION"       => "teamdescription",

    "FIELD_TEAM_DESCRIPTION_VALUE" => isset($_POST["teamdescription"])? htmlchars($_POST["teamdescription"]) : htmlchars($team->get("description"))

   ));

 }



 function write_dissolve(){global $centercol, $tpl, $team, $teams, $user, $users;

  if(isset($_POST["submit"]))

  if($_POST["dcheck"] == 1){ //last check

   $team->delete();



   write_refresh("?page=news");

   return;

  }



  $centercol = "team_panel_dissolve.tpl";



  $tpl->assign(array(

    "FIELD_TEAM_CHECK"       => "dcheck",

    "FIELD_TEAM_CHECK_VALUE" => 1,

    "FIELD_TEAM_DISSOLVE"    => "submit"

   ));

 }



 function write_tourny(){global $centercol, $tpl, $team, $teams, $user, $images, $tournys;

  $tpl->splice("TOURNYS", "team_panel_tourny.tpl");



  if(isset($_GET["ltournyid"]))

  if($_GET["ltournyid"] > 0){//leave

   $tourny = &$tournys->tourny($_GET["ltournyid"]);



   if($tourny->id > 0)

   if($tourny->del_team_valid($team->id)) //can leave?

    $tourny->del_team($team->id);



   unset($tourny);



   write_refresh("?page=teamcontrol&teamid=".$team->id."&cmd=tourny");

  }



  if(isset($_GET["jtournyid"]))

  if($_GET["jtournyid"] > 0){//join

   $tourny = &$tournys->tourny($_GET["jtournyid"]);



   if($tourny->id > 0)

   if($tourny->add_team_valid($team->id)) //can join?

    $tourny->add_team($team->id);



   unset($tourny);



   write_refresh("?page=teamcontrol&teamid=".$team->id."&cmd=tourny");

  }





  //FOUNDER/CAPTAIN Message

  if($team->user_rank($user->id) >= $GLOBALS["level_founder"]){

   $tpl->parse("TOURNYS->MSG_FOUNDER","TOURNYS->MSG_FOUNDER");

   $tpl->assign("TOURNYS->MSG_CAPTAIN","");

  }else{//captain

   $tpl->parse("TOURNYS->MSG_CAPTAIN","TOURNYS->MSG_CAPTAIN");

   $tpl->assign("TOURNYS->MSG_FOUNDER","");

  }



  //JOIN tournament list

  //select tournys that are open and teams

  $query = new db_cmd("SELECT", "tournaments", "tournamentid", "status=".$GLOBALS["tourny_stage_signup_open"]." AND type=2");

  foreach($query->data as $tournydata)

   if($tournydata['tournamentid'] > 0){ //possibly valid tourny

    $tourny =& $tournys->tourny((INT) $tournydata['tournamentid']);



    if($tourny->id > 0) //valid tourny

    if($tourny->add_team_valid($team->id)){ //can join

     $image = $images->image($tourny->get('banner'));



     if($image->id > 0)

      $tpl->parse("TOURNYS->JT_BANNER", "TOURNYS->JT_BANNER", 0, array(

        "JT_LINK"   => "?page=teamcontrol&teamid=".$team->id."&cmd=tourny&jtournyid=".$tourny->id,

        "JT_BANNER" => $image->image()

       ));

     else $tpl->assign("TOURNYS->JT_BANNER", '');



     $tpl->parse("TOURNYS->JT_ROW", "TOURNYS->JT_ROW", 1, array(

       "JT_NAME"   => $tourny->get("name"),

       "JT_LINK"   => "?page=teamcontrol&teamid=".$team->id."&cmd=tourny&jtournyid=".$tourny->id,

      ));

   }}



  //Error Msg

  if($tpl->fetch("TOURNYS->JT_ROW") != '')

   $tpl->assign("TOURNYS->JT_NONE", ''); //null out error msg

  else{//no tournys - error out

   $tpl->parse("TOURNYS->JT_NONE","TOURNYS->JT_NONE");

   $tpl->assign("TOURNYS->JT_ROW", '');

  }



  //LEAVE tournament list



  //only founders can part

  if($team->user_rank($user->id) >= $GLOBALS["level_founder"]){

   //current tournaments

   foreach($team->tournys() as $tournyid){

    $tourny = &$tournys->tourny($tournyid);



    if($tourny->id > 0)

    if($tourny->del_team_valid($team->id)){

     $image = $images->image($tourny->get("banner"));



     if($image->id > 0)

      $tpl->parse("TOURNYS->LT_BANNER", "TOURNYS->LT_BANNER", 0, array(

        "LT_LINK"   => "?page=teamcontrol&teamid=".$team->id."&cmd=tourny&ltournyid=".$tourny->id,

        "LT_BANNER" => $image->image()

       ));

     else $tpl->assign("TOURNYS->LT_BANNER", '');



     $tpl->parse("TOURNYS->LT_ROW", "TOURNYS->LT_ROW", 1, array(

       "LT_NAME" => $tourny->get("name"),

       "LT_LINK" => "?page=teamcontrol&teamid=".$team->id."&cmd=tourny&ltournyid=".$tourny->id

      ));

   }}



   //Error Msg

   if($tpl->fetch("TOURNYS->LT_ROW") != '')

    $tpl->assign("TOURNYS->LT_NONE", ''); //null out error msg

   else{//no tournys - error out

    $tpl->parse("TOURNYS->LT_NONE","TOURNYS->LT_NONE");

    $tpl->assign("TOURNYS->LT_ROW", '');

   }



   $tpl->parse("TOURNYS->LEAVE","TOURNYS->LEAVE");

  }else $tpl->assign("TOURNYS->LEAVE", '');



  $tpl->parse("CENTER","TOURNYS");

 }



 function write_members(){global $centercol, $tpl, $team, $users, $user, $teaminvites;

  $tpl->splice("PAGE", "team_panel_members.tpl");



  $tpl->parse("CENTER","PAGE", array(

    "TEAM_NAME" => $team->get("name"),

    "TEAM_ID"   => $team->id,

    "LEVEL_FOUNDER" => $GLOBALS["level_founder"],

    "LEVEL_CAPTAIN" => $GLOBALS["level_captain"],

    "LEVEL_PLAYER"  => $GLOBALS["level_player"],

    "LEVEL_SUB"     => $GLOBALS["level_sub"],

    "LEVEL_NPLAYER" => $GLOBALS["level_nplayer"],

   ));



  if(isset($_GET["destuserid"])){

   $tuser = &$users->user($_GET["destuserid"]);



   if($tuser->id == 0) return;



   if($_GET["type"] == "i"){ //invite player

    switch($_GET["set"]){

     case "1": //invite

      if(!$teaminvites->user_team_invite($tuser->id, $team->id)) //user already invited?

      if(!$team->is_user($tuser->id)){//member of team

        $invite = &$teaminvites->invite(0,1);



        $invite->set(array(

          "team"   => $team->id,

          "userid" => $tuser->id,

          "time"   => time()

         ));

       }

      break;

     case "0": //cancel invite

      $teaminvites->delete($team->id, $tuser->id);

      break;

    }



    write_refresh("?teamid=".$team->id."&page=teamcontrol&cmd=members");

    return;

   }else{ //normal players

    if($team->user_rank($tuser->id) == $GLOBALS["level_nplayer"])   return; //can only modify team users

    if($team->user_rank($tuser->id) >= $team->user_rank($user->id)) return; //thou shall not modify users higher than thou or thy equals



    switch($_GET["set"]){

     case $GLOBALS["level_founder"]:

      if(!$_GET["set_founder_conf"]){

       //confirm new founder

       $tpl->parsefile("CENTER", "team_panel_members_nfounder.tpl", array(

         "PLAYER_NFOUNDER_NAME" => $tuser->tagname(),

         "PLAYER_ID"            => $tuser->id

        ));



       //dont show members page or refresh

       return;

      }else if($team->user_rank($user->id) >= $GLOBALS["level_founder"])

       //switch over

       $team->set_founder($tuser->id);

      break;

     case $GLOBALS["level_captain"]:

      if($team->user_rank($user->id) >= $GLOBALS["level_founder"])

       $team->set_rank($tuser->id, $GLOBALS["level_captain"]);

      break;

     case $GLOBALS["level_player"]:

      $team->set_rank($tuser->id, $GLOBALS["level_player"]);

      break;

     case $GLOBALS["level_sub"]:

      $team->set_rank($tuser->id, $GLOBALS["level_sub"]);

      break;

     case $GLOBALS["level_nplayer"]:

      if($team->user_rank($user->id) >= $GLOBALS["level_founder"]){

       $team->del_user($tuser->id);

       $tuser->del_team($team->id);

      }

      break;

    }



    write_refresh("?teamid=".$team->id."&page=teamcontrol&cmd=members");

    return;

  }}



  //team players

  foreach($team->users() as $userid) if($userid > 0){

   $tuser = &$users->user($userid);



   if($tuser->id > 0){

    $tpl->assign("PLAYER_ID", $tuser->id);

    //set founder

    if(($team->user_rank($tuser->id) < $GLOBALS["level_founder"]) && ($team->user_rank($user->id) >= $GLOBALS["level_founder"]))

     $tpl->parse("PAGE->player_set_found", "PAGE->player_set_found");

    else $tpl->assign("PAGE->player_set_found", "");



    //set captain

    if(($team->user_rank($tuser->id) < $GLOBALS["level_founder"]) && ($team->user_rank($user->id) >= $GLOBALS["level_founder"]))

     $tpl->parse("PAGE->player_set_capt", "PAGE->player_set_capt");

    else $tpl->assign("PAGE->player_set_capt", "");



    //set player

    if(($team->user_rank($tuser->id) < $GLOBALS["level_founder"]) && ($team->user_rank($tuser->id) < $GLOBALS["level_captain"] || $team->user_rank($user->id) >= $GLOBALS["level_founder"]))

     $tpl->parse("PAGE->player_set_player", "PAGE->player_set_player");

    else $tpl->assign("PAGE->player_set_player", "");



    //set sub

    if(($team->user_rank($tuser->id) < $GLOBALS["level_founder"]) && ($team->user_rank($tuser->id) < $GLOBALS["level_captain"] || $team->user_rank($user->id) >= $GLOBALS["level_founder"]))

     $tpl->parse("PAGE->player_set_sub", "PAGE->player_set_sub");

    else $tpl->assign("PAGE->player_set_sub", "");



    //kick player

    if(($team->user_rank($tuser->id) < $GLOBALS["level_founder"]) && ($team->user_rank($user->id) >= $GLOBALS["level_founder"]))

     $tpl->parse("PAGE->player_set_kick", "PAGE->player_set_kick");

    else $tpl->assign("PAGE->player_set_kick", "");



    $tpl->parse("PAGE->player_row", "PAGE->player_row", 1, array(

     "PLAYER_NAME"   => $tuser->get_alink_profile(),

     "PLAYER_STATUS" => $team->user_rank_text($tuser->id),

     "PLAYER_TYPE"   => "p"

    ));

  }}



  //team invites

  foreach($teaminvites->team_invite($team->id) as $inviteid) if($inviteid > 0){

   $invite = &$teaminvites->invite($inviteid);



   if($invite->id > 0){

    $tuser = &$users->user($invite->get("userid"));



    if($tuser->id > 0){

     $tpl->assign(array(

       "PLAYER_ID"               => $tuser->id,

       "PAGE->player_set_found"  => '',

       "PAGE->player_set_capt"   => '',

       "PAGE->player_set_player" => '',

       "PAGE->player_set_sub"    => '',

       "PLAYER_TYPE"             => "i"

      ));



     //kick player

     if($team->user_rank($user->id) >= $GLOBALS["level_founder"])

      $tpl->parse("PAGE->player_set_kick", "PAGE->player_set_kick");

     else $tpl->assign("PAGE->player_set_kick", "");



     $tpl->parse("PAGE->player_row", "PAGE->player_row", 1, array(

      "PLAYER_NAME"   => $tuser->get_alink_profile(),

      "PLAYER_STATUS" => $tpl->fetchfile("team_rank_invite.tpl")

     ));

  }}}



 }



 //dont let founders leave, make them assign another

 function write_leave(){global $centercol, $tpl, $team, $teams, $user, $users;

  if(isset($_POST["submit"])){

   if($team->user_rank($user->id) >= $GLOBALS["level_founder"]){

    write_refresh("?teamid=".$team->id."&page=teamcontrol&cmd=members"); return;

   }else

    if($_POST["chkbox"]){

     $team->del_user($user->id);

     $user->del_team($team->id);



     write_refresh("?page=news"); return;

    }

  }



  $centercol = "team_panel_leave.tpl";



  if($team->user_rank($user->id) >= $GLOBALS["level_founder"])

   $tpl->parsefile("WARN_FOUNDER", "team_panel_leave_foundwarn.tpl",array(FIELD_SUBMIT_VALUE => "Assign New Founder"));

  else

   $tpl->assign(array(

     "WARN_FOUNDER"       => "",

     "FIELD_SUBMIT_VALUE" => "Leave Team"

    ));



  $tpl->assign(array(

    "FIELD_CHECK"       => "chkbox",

    "FIELD_CHECK_VALUE" => "1",

    "FIELD_SUBMIT"      => "submit",

    "TEAM_NAME"         => $team->get("name")

   ));

 }



 if($user->id > 0 && $teamid > 0){//valid user and team

  $team = &$teams->team($teamid);



  if($team->is_user($user->id) && $team->id > 0){



   if($team->user_rank($user->id) >= $GLOBALS["level_founder"]  && !$team->get("draft"))

    switch($cmd){//founder

     case "profile":

      write_profile();

      break;

     case "dissolve":

       write_dissolve();

      break;

    }



   if($team->user_rank($user->id) >= $GLOBALS["level_captain"] && !$team->get("draft"))

    switch($cmd){//captain

     case "tourny":

      write_tourny();

      break;

     case "members":

      if($_GET["invite"]){

       $search = new form_search("?page=teamcontrol&teamid=$teamid&cmd=members&type=i&set=1&destuserid=", 'user');

       $search->set_search_center();

      }else

       write_members();

      break;

    }



   if(!$team->get("draft"))

   switch($cmd){ //common user

    case "leave":

     write_leave();

     break;

   }

}}

?>