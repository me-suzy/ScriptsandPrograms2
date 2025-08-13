<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 //write links to join tourny by team or by user

 function write_tourny_join(){global $user, $tourny, $tpl, $users, $teams;

  $tpl->splice("TOURNY", "tourny_join.tpl");



  if(!$user->id > 0) //invalid user

   $tpl->parse("TOURNY->JOIN", "TOURNY->LOGIN");

  else{ //valid user

   if($tourny->type() == $GLOBALS["tourny_type_single"])

    write_refresh("?page=playercontrol&cmd=tourny"); //send them to tourny signup page



   if($tourny->type() == $GLOBALS["tourny_type_team"]){

    if(is_array($user->teams())){

     foreach($user->teams() as $teamid) if($teamid > 0){

      $team =& $teams->team($teamid);



      if($team->id > 0)//valid team

       if($team->user_rank($user->id) >= $GLOBALS["level_captain"] && !$team->get("draft")) //make sure they can make team join

        $tpl->parse("TOURNY->TEAMS_TEAM", "TOURNY->TEAMS_TEAM", true, array(

          "TEAM_NAME" => $team->get("name"),

          "TEAM_ID"   => $team->id

         ));

     }



    //only parse to join tpl if there are teams

    if($tpl->fetch("TOURNY->TEAMS_TEAM") != ''){

     if($tourny->get("draft")) //check for draft, give link for all

      $tpl->parse("TOURNY->TEAMS_DRAFT", "TOURNY->TEAMS_DRAFT");

     else //hide draft link

      $tpl->assign("TOURNY->TEAMS_DRAFT", "");



     $tpl->parse("TOURNY->JOIN", "TOURNY->TEAMS");

    }

   }



   if($tpl->fetch("TOURNY->TEAMS_TEAM") == ''){ //not on a valid team

    if($tourny->get("draft")) //show join draft link

     $tpl->parse("TOURNY->NEED_TEAM_DRAFT", "TOURNY->NEED_TEAM_DRAFT");

    else //hide draft text

     $tpl->parse("TOURNY->NEED_TEAM_DRAFT", "");



    $tpl->parse("TOURNY->JOIN", "TOURNY->NEED_TEAM");

  }}



  }



  $tpl->parse("CONTENT", "TOURNY");

 }



 //captain config of their prospective team

 function write_tourny_draft_team(){global $user, $tourny, $tpl, $users, $teams;

  $tpl->splice("TOURNY", "tourny_draft_team.tpl");



  //grab team profile

  $teamprofile = $tourny->get_draft_capt_profile($user->id);



  if(isset($_POST["team_name"])) //save changes

   if( ($errchk = ///check everything

      ($teamprofile["name"] ==$_POST["team_name"]  && $_POST["team_name"]  != ''?'':$teams->check("name",  $_POST["team_name"]))  .

      ($teamprofile["tag"]  ==$_POST["team_tags"]  && $_POST["team_tags"]  != ''?'':$teams->check("tag",   $_POST["team_tags"]))  .

      ($teamprofile["email"]==$_POST["team_email"] && $_POST["team_email"] != ''?'':$teams->check("email", $_POST["team_email"]))

     ) == ''){



    //save rank array

    if(is_array($tourny->draft_users())){

     //preload list

     $user->user($tourny->draft_users());



     foreach($tourny->draft_users() as $userid)

      if($userid > 0) //valid user

       $ranks[$userid] = $_POST["rank_".$userid] > 0 && $_POST["rank_".$userid] < 11 ? $_POST["rank_".$userid] : 10;

    }



    //save profile changes

    $teamprofile = array(

      "name"  => htmlchars($_POST["team_name"]),

      "tag"   => htmlchars($_POST["team_tags"]),

      "side"  => (BOOL) $_POST["team_side"],

      "email" => $_POST["team_email"],

      "ranks" => $ranks

     );



    //save profile

    $tourny->set_draft_capt_profile($user->id, $teamprofile);



    write_refresh("?page=tourny&tournyid=".$tourny->id."&cmd=draftteam"); return;

   }



  if(is_array($tourny->draft_users())){

   //preload list

   $user->user($tourny->draft_users());



  foreach($tourny->draft_users() as $userid) if($userid > 0){ //possibly valid user

   $duser =& $users->user($userid);



   if($duser->id > 0){ //valid user

    if(isset($_POST["rank_".$duser->id])) //show their values - force correct range

     $rank = $_POST["rank_".$duser->id] > 0 && $_POST["rank_".$duser->id] < 11 ? $_POST["rank_".$duser->id] : 10;

    else //load profile's current value

     $rank = (INT) $teamprofile["ranks"][$duser->id] == 0 ? 10 : $teamprofile["ranks"][$duser->id];



    $tpl->parse("TOURNY->PLAYER_LIST", "TOURNY->PLAYER", true, array(

      "CLASS"              => $c++ % 2 ? "row" : "rowoff",

      "PLAYER_NAME"        => $duser->get("name"),

      "FIELD_PLAYER_NAME"  => "rank_".$duser->id,

      "FIELD_PLAYER_VALUE" => $rank

     ));

  }}}



  //hide if no players

  if($tpl->fetch("TOURNY->PLAYER_LIST") == '')

   $tpl->parse("TOURNY->PLAYER_LIST", "TOURNY->PLAYER_NONE");



  $tpl->parse("CONTENT", "TOURNY", array(

    "ERRORS"                => $errchk == ''?'':write_error_common($errchk),



    "FIELD_TEAM_NAME"       => "team_name",

    "FIELD_TEAM_NAME_VALUE" => isset($_POST["team_name"]) ? $_POST["team_name"] : $teamprofile["name"],



    "FIELD_TEAM_TAGS"       => "team_tags",

    "FIELD_TEAM_TAGS_VALUE" => isset($_POST["team_tags"]) ? $_POST["team_tags"] : $teamprofile["tag"],



    "FIELD_TEAM_SIDE"       => "team_side",

    "FIELD_TEAM_SIDE_VALUE" => true,

    "FIELD_TEAM_SIDE_CHK"   => isset($_POST["team_side"]) ? numtobool($_POST["team_side"]) : numtobool($teamprofile["side"]),



    "FIELD_TEAM_EMAIL"       => "team_email",

    "FIELD_TEAM_EMAIL_VALUE" => isset($_POST["team_email"]) ? $_POST["team_email"] : $teamprofile["email"]

   ));

 }



 //show draft list

 function write_tourny_draft(){global $user, $tourny, $tpl, $users, $teams;

  $tpl->splice("TOURNY", "tourny_draft.tpl");



  //preload Teams

  $teams->team($tourny->teams());



  if(is_array($tourny->draft_capts())){

   //preload list

   $users->user($tourny->draft_capts());



  foreach($tourny->draft_capts() as $userid) if($userid > 0){ //possibly valid user

   $duser =& $users->user($userid);



   if($duser->id > 0){ //valid user

    $team = $teams->team($duser->get_draft_team($tourny->id));

    if($team->id > 0) //drafted

     $tpl->assign("TOURNY->PLAYER_TEAM", $team->get("name"));

    else //not drafted

     $tpl->parse("TOURNY->PLAYER_TEAM", "TOURNY->PLAYER_TEAM");



    //show player type

    $tpl->parse("TOURNY->PLAYER_TYPE", "TOURNY->PLAYER_TYPE_CAPT");



    $tpl->parse("TOURNY->PLAYER_LIST", "TOURNY->PLAYER", true, array(

      "TOURNY->USER_TEAM" => '',

      "USER_NAME"         => $duser->get("name"),

     ));

  }}}



  if(is_array($tourny->draft_users())){

   //preload list

   $users->user($tourny->draft_users());



  foreach($tourny->draft_users() as $userid) if($userid > 0){ //possibly valid user

   $duser =& $users->user($userid);



   if($duser->id > 0){ //valid user

    $team = $teams->team($duser->get_draft_team($tourny->id));

    if($team->id > 0) //drafted

     $tpl->assign("TOURNY->PLAYER_TEAM", $team->get("name"));

    else //not drafted

     $tpl->parse("TOURNY->PLAYER_TEAM", "TOURNY->PLAYER_TEAM");



    //show player type

    $tpl->parse("TOURNY->PLAYER_TYPE", "TOURNY->PLAYER_TYPE_USER");



    $tpl->parse("TOURNY->PLAYER_LIST", "TOURNY->PLAYER", true, array(

      "TOURNY->USER_TEAM" => '',

      "USER_NAME"         => $duser->get("name"),

     ));

  }}}



  //Check for no teams

  if($tpl->fetch("TOURNY->PLAYER_LIST") == '')

   $tpl->parse("TOURNY->PLAYER_LIST", "TOURNY->PLAYER_NONE");



  //user options

  if($tourny->is_draft_capt($user->id) && $tourny->stage() == $GLOBALS["tourny_stage_signup_close"])

   $tpl->parse("TOURNY->CAPTAIN", "TOURNY->CAPTAIN", array(

     "LINK_CAPT_SETUP" => "?page=tourny&tournyid=".$tourny->id."&cmd=draftteam"

    ));

  else //hide captain cmds

   $tpl->assign("TOURNY->CAPTAIN", '');



  $tpl->parse("CONTENT", "TOURNY");

 }



 function write_tourny_module(){global $user, $tourny, $tpl, $servers, $users, $teams;

  $module =& $tourny->module((INT) $_GET["module"]);



  //tell bracket to show itself

  if($module->id > 0) $module->show();

 }



 function write_tourny_match(){global $user, $tourny, $tpl, $servers, $users, $teams;

  $tpl->splice("MATCH", "tourny_match.tpl");



  //grab match

  $match =& $tourny->match((INT) $_GET["matchid"]);



  //invalid match

  if(!$match->id > 0) return;



  //grab parent module

  $module =& $match->module();



//Permissions----------------------------------------------------------------------------------------

  if($user->id > 0){ //valid user

   //site admin or founder

   if($tourny->get("creator") == $user->id || $user->get("admin") >= $GLOBALS["level_tourny"] || $tourny->is_admin($user->id)){

    $change["save"]   = true;

    $change["server"] = true;

    $change["map"]    = true;

    $change["side"]   = true;

    $change["time"]   = true;

    $change["score"]  = true;

    $change["servertype"] = 3;

    $change["defeat"] = false;

    $change["team"]   = true;

   } else { //non founder/admin

    $change["server"] = false;

    $change["map"]    = false;

    $change["side"]   = false;

    $change["time"]   = false;

    $change["score"]  = false;

    $change["defeat"] = true;

    $change["save"]   = false;

    $change["team"]   = false;



    //team member

    foreach($match->teams() as $teamid){

     if($tourny->type() == $GLOBALS["tourny_type_single"]){

      $team =& $users->user($teamid);



      if($user->id == $team->id){

       $change["servertype"] = 2; //captain view

       if(!(BOOL) $match->get("decided")) $change["score"] = true; //admit defeat

       $change["defeat"]     = true; //admit defeat

       $change["save"]       = true; //can save

      }

     }

     if($tourny->type() == $GLOBALS["tourny_type_team"]){

      $team =& $teams->team($teamid);



      //valid team user

      if($team->is_user($user->id)){

       if($team->user_rank($user->id) >= $GLOBALS["level_captain"]){ //is captain

        $change["servertype"] = 2;

        if(!(BOOL) $match->get("decided")) $change["score"] = true; //admit defeat

        $change["defeat"]     = true; //admit defeat

        $change["save"]       = true; //can save

       }else //common player

        $change["servertype"] = 1;

      }else $change["servertype"] = 0; //vistor

     }

    }



   }

  } else { //vistors can only view

   $change["team"]   = false;

   $change["save"]   = false;

   $change["server"] = false;

   $change["map"]    = false;

   $change["side"]   = false;

   $change["time"]   = false;

   $change["score"]  = false;

   $change["servertype"] = 0; //vistor

  }

//Save----------------------------------------------------------------------------------------

  if($change["save"]) $tpl->parse("MATCH->SAVE", "MATCH->SAVE");

  else $tpl->assign("MATCH->SAVE", "");

//MODULE/ROUND/ID----------------------------------------------------------------------------------------

  $tpl->assign(array(

    "MODULE_NAME" => $module->get("name"),

    "MODULE_LINK" => "?page=tourny&tournyid=".$tourny->id."&cmd=module&module=".$module->id,

    "ROUND"    => $match->get("round"),

    "MATCH_ID" => $match->id

   ));

//Server----------------------------------------------------------------------------------------

  $server =& $servers->server($match->get("server"));



  if($change["server"]) //allow changing server

   $tpl->parse("MATCH->SERVER_SELECT", "MATCH->SERVER_SELECT", array(

     "HREF_SEL_SERVER" => "?page=tourny&tournyid=".$tourny->id."&cmd=selserver&matchid=".$match->id

    ));

  else //dont allow changing server

   $tpl->assign("MATCH->SERVER_SELECT", "");



  //show server

  if($server->id > 0)

   $tpl->assign("MATCH->SERVER_INFO", $server->server_template($change["servertype"]));

  else //no server

   $tpl->parse("MATCH->SERVER_INFO", "MATCH->SERVER_NONE");



  $tpl->parse("MATCH->SERVER", "MATCH->SERVER");

//MAPS----------------------------------------------------------------------------------------

  $mpm = $module->get("mapspermatch");



  if($change["map"]){

   if(isset($_POST["MAP_0"])) //save maps

    for($i=0;$i < $mpm;$i++)

     $match->set_map($i, $_POST["MAP_".$i], true);



   for($i=0;$i < $mpm;$i++)

    $tpl->parse("MATCH->MAPS_EDIT_MAP", "MATCH->MAPS_EDIT_MAP", true, array(

      "MAP_ID"          => ($i + 1),

      "FIELD_MAP_NAME"  => "MAP_". $i,

      "FIELD_MAP_VALUE" => $match->get_map($i),

     ));



   $tpl->parse("MATCH->MAPS_LIST", "MATCH->MAPS_EDIT_TABLE");

  } else { //show list of maps

   for($i=0;$i < $mpm;$i++){

    if(strlen($match->get_map($i)) > 1)

     $tpl->assign("MAP_NAME", $match->get_map($i));

    else //invalid map name

     $tpl->parse("MAP_NAME", "MATCH->MAPS_MAP_NONE");



    $tpl->parse("MATCH->MAPS_MAP", "MATCH->MAPS_MAP", true, array(

      "MAP_ID"   => ($i + 1)

     ));

   }



   $tpl->parse("MATCH->MAPS_LIST", "MATCH->MAPS_TABLE");

  }



  $tpl->parse("MATCH->MAPS", "MATCH->MAPS");

//Sides----------------------------------------------------------------------------------------

  $mteams  = $match->teams(); //grab team array



  if($change["side"]){ //edit sides

   if(isset($_POST["SIDE_0"])) //save sides

    for($i=0;$i < $module->tpm();$i++)

     $match->set_team($i, "side", $_POST["SIDE_".$i], true);



   for($i=0;$i < $module->tpm();$i++){

    if($tourny->type() == $GLOBALS["tourny_type_single"])

     $team =& $users->user($mteams[$i]);

    if($tourny->type() == $GLOBALS["tourny_type_team"])

     $team =& $teams->team($mteams[$i]);



    if($team->id > 0) //valid team - so show name

     $tpl->assign("TEAM_NAME", $team->get("name"));

    else  //invalid team

     $tpl->parse("TEAM_NAME", "MATCH->SIDES_SIDE_TEAM_NONE", false, array(

       "TEAM_ID" => ($i + 1) //pub doesnt know we start counting at 0

      ));



    $tpl->parse("MATCH->SIDES_EDIT_SIDE", "MATCH->SIDES_EDIT_SIDE", true, array(

     "FIELD_SIDE_NAME"  => "SIDE_". $i,

     "FIELD_SIDE_VALUE" => $match->get_team($i, "side")

    ));



    unset($team);

   }



   $tpl->parse("MATCH->SIDES_LIST", "MATCH->SIDES_EDIT_TABLE");

  } else { //show list of sides

   for($i=0;$i < $module->tpm();$i++){

    if($tourny->type() == $GLOBALS["tourny_type_single"])

     $team =& $users->user($mteams[$i]);

    if($tourny->type() == $GLOBALS["tourny_type_team"])

     $team =& $teams->team($mteams[$i]);



    if($team->id > 0) //valid team - so show name

     $tpl->assign("TEAM_NAME", $team->get("name"));

    else  //invalid team

     $tpl->parse("TEAM_NAME", "MATCH->SIDES_SIDE_TEAM_NONE", false, array(

       "TEAM_ID" => ($i + 1) //pub doesnt know we start counting at 0

      ));



    if(strlen($match->get_team($i, "side")) > 1)

     $tpl->assign("SIDE_NAME", $match->get_team($i, "side"));

    else //invalid side name

     $tpl->parse("SIDE_NAME", "MATCH->SIDES_SIDE_NONE");



    $tpl->parse("MATCH->SIDES_SIDE", "MATCH->SIDES_SIDE", true, array(

      "TEAM_ID"   => ($i + 1)

     ));

   }



   $tpl->parse("MATCH->SIDES_LIST", "MATCH->SIDES_TABLE");

  }



  $tpl->parse("MATCH->SIDES", "MATCH->SIDES");

//TIME----------------------------------------------------------------------------------------

  $matchtime = new time($match->get("time"));



  if($change["time"]){//edit time

   if($matchtime->get_formated(false) != $_POST["MATCH_TIME"]) //They didnt change it

   if(isset($_POST["MATCH_TIME"])){

    $time = new entry_time($_POST["MATCH_TIME"]);



    if($time !== -1) //valid time

     $match->set("time", $time->get(), false);

   }



   $tpl->parse("MATCH->TIME_LIST", "MATCH->TIME_EDIT", false, array(

     "FIELD_TIME_NAME"  => "MATCH_TIME",

     "FIELD_TIME_VALUE" => $matchtime->get_formated(false)

    ));

  }else{ //show time

   if($match->get("time") > 1)

    $tpl->parse("MATCH->TIME_LIST", "MATCH->TIME_DISPLAY", array(

      "TIME" => $matchtime->get_formated()

     ));

   else

    $tpl->parse("MATCH->TIME_LIST", "MATCH->TIME_NONE");

  }



  $tpl->parse("MATCH->TIME", "MATCH->TIME");

//SCORE----------------------------------------------------------------------------------------

  $mpm = $module->get("mapspermatch");

  $tpm = $module->get("teamspermatch");



  if($change["score"])

   if(isset($_POST["SCORE_0_0"]) && isset($_POST["STATUS_0"])){ //save scores

    for($t=0;$t < $tpm;$t++){ //grab array of status to verify

     for($m=0;$m < $mpm;$m++) //grab map list

      $score[$t][$m] = (INT) $_POST["SCORE_".$t."_".$m];



     //grab status list

     $status[$t] = (INT) $_POST["STATUS_".$t];

    }



    //have module deal with it

    $module->validate_match_status($match, $status, $score, $change["defeat"]);

    $module->check_round(); //check for round++
   }



  //map header

  for($m=0;$m < $mpm;$m++){

   if(strlen($match->get_map($m)) > 1) //valid map

    $tpl->assign("MATCH->MAP_NAME", $match->get_map($m));

   else //map not named

    $tpl->parse("MATCH->MAP_NAME", "MATCH->MAP_NAME", false, array(

      "MAP_ID" => ($m + 1)

     ));



   $tpl->parse("MATCH->HEADER_MAP", "MATCH->HEADER_MAP", true);

  }



  for($t=0;$t < $tpm;$t++){

   for($m=0;$m < $mpm;$m++)

    if($change["score"]) //edit mode

     $tpl->parse("MATCH->TEAM_LIST_MAP", "MATCH->TEAM_LIST_MAP_EDIT", true, array(

       "FIELD_SCORE_NAME"  => "SCORE_".$t."_".$m,

       "FIELD_SCORE_VALUE" => $match->get_score($t, $m),

      ));

    else //only view

     $tpl->parse("MATCH->TEAM_LIST_MAP", "MATCH->TEAM_LIST_MAP_VIEW", true, array(

       "SCORE" => $match->get_score($t, $m)

      ));



   //grab team

   if($tourny->type() == $GLOBALS["tourny_type_single"])

    $team =& $users->user($match->get_team($t));

   if($tourny->type() == $GLOBALS["tourny_type_team"])

    $team =& $teams->team($match->get_team($t));



   //show team profile or location to change team

   if($change["team"]) //change team

    $tpl->assign("A_SET_TEAM", "?page=tourny&tournyid=".$tourny->id."&cmd=selteam&matchid=".$match->id."&team=".$t);

   else //team profile

    $tpl->assign("A_SET_TEAM", "?page=profile&type=".$tourny->type()."&id=".$team->id);



   if(strlen($team->get("name")) > 1) //valid team

    $tpl->assign(array(

      "MATCH->TEAM_LIST_NAME" => $team->get("name"),

     ));

   else //invalid team

    $tpl->parse("MATCH->TEAM_LIST_NAME", "MATCH->TEAM_LIST_NAME", array(

      "TEAM_ID"   => ($t + 1)

     ));



   $tpl->parse("MATCH->TEAM_LIST", "MATCH->TEAM_LIST", true);



   $tpl->clear("MATCH->TEAM_LIST_MAP");

  }



  //notify admin they can change teams

  if($change["team"])

   $tpl->parse("MATCH->SET_TEAM_NOTICE", "MATCH->SET_TEAM_NOTICE");

  else //hide it

   $tpl->assign("MATCH->SET_TEAM_NOTICE", "");



  $tpl->parse("MATCH->SCORE", "MATCH->SCORE");

//STATUS----------------------------------------------------------------------------------------

  $module->write_match_status(&$match, $change["score"]);

//----------------------------------------------------------------------------------------



  $tpl->parse("CONTENT", "MATCH");



  if(isset($_POST["SCORE_0_0"])) //gotta refresh or it wont look right

   write_refresh("?page=tourny&tournyid=".$tourny->id."&cmd=match&matchid=".$match->id);

 }



 function write_tourny_matchs(){global $tourny, $tpl, $servers, $users, $teams, $user;

  $tpl->splice("MATCHS", "tourny_matchs.tpl");



  $modules = $tourny->modules(true);



  //admin's special listing

  if($user->id > 0)

   if($tourny->is_admin($user->id))

    $tpl->parse("MATCHS->LIST_ADMIN", "MATCHS->LIST_ADMIN");

  //hide admin list if not admin

  if($tpl->fetch("MATCHS->LIST_ADMIN") == '') $tpl->assign("MATCHS->LIST_ADMIN", '');



  if(is_array($modules)){

   foreach($modules as $moduleid){

    $module =& $tourny->module($moduleid);



    if(strlen($module->get("name")) > 1)

     $tpl->parse("MATCHS->MODULE_LIST", "MATCHS->MODULE_LIST", true, array(

       "MODULE_ID"   => $module->id,

       "MODULE_NAME" => $module->get("name"),

       "MODULE_LINK" => "?page=tourny&tournyid=".$tourny->id."&cmd=module&module=".$module->id,

       "MODULE_ROUND" => $module->get("round")

      ));

   }



   if($tpl->fetch("MATCHS->MODULE_LIST") == '')

    $tpl->assign("MATCHS->MODULE_LIST", '');



   $tpl->parse("MATCHS->MODULES", "MATCHS->MODULES");

  } else $tpl->assign("MATCHS->MODULES", '');



  //hide err sections

  $tpl->assign(array(

    "MATCHS->MATCHS_NONE" => "",

    "MATCHS->MODULE_NONE" => ""

   ));



  switch($_GET["show"]){

   case "admin": //list all matchs user is admining

    $matchs = $tourny->find_admin_matchs($user);



    if(is_array($matchs))

     foreach($matchs as $matchid){

      $match  = $matcharr[]  =& $tourny->match($matchid);

      $module = $modulearr[] =& $match->module();



      if($module->id > 0 && $match->id > 0){

       $tpl->assign(array(

         "MODULE_NAME" => $module->get("name"),

         "MODULE_LINK" => "?page=tourny&tournyid=".$tourny->id."&cmd=module&module=".$module->id,

         "MODULE_ROUND" => $module->get("round")

        ));



       write_tourny_matchs_match($module, $match);



       if($module->id != $modulearr[count($modulearr) - 2]->id){

        $tpl->clear("MATCHS->MATCH");

        $tpl->parse("MATCHS->TABLE", "MATCHS->TABLE");

        $tpl->parse("MATCHS->MODULE", "MATCHS->MODULE", true);

      }}

     }



    break;

   case "module": //list all matchs by module

    $module =& $tourny->module((INT) $_GET["module"]);



    if($module->id > 0){

     if(isset($_GET["round"])) //specific round

      $round = (INT) $_GET["round"];

     else $round = -1; //all rounds



     //grab all matchs

     $matchs = $tourny->find_matchs($module->id, $round, TRUE, false, true);



     //run through each match

     if(is_array($matchs)){

      foreach($matchs as $matchid){

       $match =& $tourny->match($matchid);



       write_tourny_matchs_match($module, $match);

      }

     } //no matchs

      else $tpl->parse("MATCHS->MATCH", "MATCHS->MATCHS_NONE");



     $tpl->clear("MATCHS->MATCH");

     $tpl->parse("MATCHS->TABLE", "MATCHS->TABLE");

     $tpl->parse("MATCHS->MODULE", "MATCHS->MODULE", true, array(

       "MODULE_NAME" => $module->get("name"),

       "MODULE_LINK" => "?page=tourny&tournyid=".$tourny->id."&cmd=module&module=".$module->id,

       "MODULE_ROUND" => $module->get("round")

      ));

    }

    break;

   case "listall": //list all matchs

    if(is_array($modules))

    foreach($modules as $moduleid){

     $module =& $tourny->module($moduleid);



     if($module->id > 0){//valid module

      $tpl->assign(array(

        "MODULE_NAME" => $module->get("name"),

        "MODULE_LINK" => "?page=tourny&tournyid=".$tourny->id."&cmd=module&module=".$module->id,

       "MODULE_ROUND" => $module->get("round")

       ));



      //grab all matchs

      $matchs = $tourny->find_matchs($module->id, -1, TRUE, false, true);



      //run through each match

      if(is_array($matchs)) foreach($matchs as $matchid){

       $match =& $tourny->match($matchid);



       write_tourny_matchs_match($module, $match);

      } else $tpl->parse("MATCHS->MATCH", "MATCHS->MATCHS_NONE"); //no matchs



      $tpl->clear("MATCHS->MATCH");

      $tpl->parse("MATCHS->TABLE", "MATCHS->TABLE");

      $tpl->parse("MATCHS->MODULE", "MATCHS->MODULE", true);

    }}

    break;

   default: //list My Matchs

    if($tourny->type() == $GLOBALS["tourny_type_single"])

     if($user->id > 0) //make sure they are real

      $matchs = $tourny->find_team_matchs($user->id);



    if($tourny->type() == $GLOBALS["tourny_type_team"])

     if($user->id > 0) //make sure they are real

      $matchs = $tourny->find_team_matchs($user->teams());



    if(is_array($matchs))

     foreach($matchs as $matchid){

      $match  = $matcharr[]  =& $tourny->match($matchid);

      $module = $modulearr[] =& $match->module();



      if($module->id > 0 && $match->id > 0){

       $tpl->assign(array(

         "MODULE_NAME" => $module->get("name"),

         "MODULE_LINK" => "?page=tourny&tournyid=".$tourny->id."&cmd=module&module=".$module->id,

         "MODULE_ROUND" => $module->get("round")

        ));



       write_tourny_matchs_match($module, $match);



       if($module->id != $modulearr[count($modulearr) - 2]->id){

        $tpl->clear("MATCHS->MATCH");

        $tpl->parse("MATCHS->TABLE", "MATCHS->TABLE");

        $tpl->parse("MATCHS->MODULE", "MATCHS->MODULE", true);

      }}

     }

    break;

  }



  //catch for any blank modules

  if($tpl->fetch("MATCHS->MODULE") == '')

   $tpl->parse("MATCHS->MODULE", "MATCHS->MODULE_NONE");



  $tpl->parse("CONTENT", "MATCHS");

 }



 //write a single match entry to the match list

 function write_tourny_matchs_match(&$module, &$match){global $tourny, $tpl, $servers, $users, $teams;

  if(!$module->id > 0) return; //invalid module

  if(!$match->id > 0)  return; //invalid match



//TEAMS----------------------------------------------------------------------------------------

  //clear out old parses

  $tpl->clear("MATCHS->TEAM");



  $mteams  = $match->teams(); //grab team array



  //grab count of teams

  $teamc = count($mteams);



  if(is_array($mteams) && !empty($mteams)){

   //run through each team

   for($i=0;$i < $teamc;$i++){

    //grab correct obj

    if($tourny->type() == $GLOBALS["tourny_type_single"])

     $team =& $users->user($mteams[$i]);

    if($tourny->type() == $GLOBALS["tourny_type_team"])

     $team =& $teams->team($mteams[$i]);



    $tpl->assign("TEAM_NAME", $team->get("name"));



    //select correct parse

    if($i != $teamc - 1) $tpl->parse("MATCHS->TEAM", "MATCHS->TEAM_VS", true);

    else $tpl->parse("MATCHS->TEAM", "MATCHS->TEAM_END", true);

   }

  }



  //no teams

  if(!$teamc > 0)

   $tpl->parse("MATCHS->TEAM", "MATCHS->TEAM_NONE");



//SERVER----------------------------------------------------------------------------------------

  $server =& $servers->server($match->get("server"));



  if($server->id > 0)

   $tpl->parse("MATCHS->SERVER","MATCHS->SERVER_VALID", array(

     "SERVER_NAME" => $server->get("name"),

     "SERVER_ID"   => $server->id

    ));

  else //bad server

   $tpl->parse("MATCHS->SERVER","MATCHS->SERVER_NONE");

//Time----------------------------------------------------------------------------------------

  if(strlen($match->get("time")) > 1){

   $time = new time($match->get("time"));

   $tpl->assign("MATCHS->TIME", $time->get_formated());

   unset($time);

  }else //invalid time

   $tpl->parse("MATCHS->TIME","MATCHS->TIME");

//Status----------------------------------------------------------------------------------------

  //check to see if first team has a status

  if((BOOL) $match->get("decided"))

   $tpl->parse("MATCHS->STATUS","MATCHS->STATUS_DECIDED");

  else //match decided

   $tpl->parse("MATCHS->STATUS","MATCHS->STATUS_PENDING");

//Parse----------------------------------------------------------------------------------------

  $tpl->parse("MATCHS->MATCH", "MATCHS->MATCH", true, array(

    "ROUND"      => $match->get("round"),

    "MATCH_LINK" => "?page=tourny&tournyid=".$tourny->id."&cmd=match&matchid=".$match->id

   ));

 }



 function write_tourny_selteam(){global $tourny, $tpl, $teams, $users;

  //grab match

  $match =& $tourny->match((INT) $_GET["matchid"]);



  //invalid match

  if(!$match->id > 0) return;



  if(isset($_GET["selteam"])){ //save team

   $match->set_team((INT) $_GET["team"], "id", (INT) $_GET["selteam"]);



   return write_refresh("?page=tourny&tournyid=".$tourny->id."&cmd=match&matchid=".$match->id);

  }



  //make link to save teams

  $link = "?page=tourny&tournyid=".$tourny->id."&cmd=selteam&team=".((INT) $_GET["team"])."&matchid=".$match->id."&selteam=";



  $tpl->parsefile("CONTENT", "tourny_selteam.tpl", array(

    "LINK_RETURN" => "?page=tourny&tournyid=".$tourny->id."&cmd=match&matchid=".$match->id,

    "LINK_NULL"   => $link . "0",

    "TEAMS"       => $tourny->get_list_teams($link)

   ));

 }



 function write_tourny_selserver(){global $tourny, $tpl, $servers;

  //grab match

  $match =& $tourny->match((INT) $_GET["matchid"]);



  //invalid match

  if(!$match->id > 0) return;



  if(isset($_GET["selserver"])){ //save server

   $match->set("server", (INT) $_GET["selserver"]);



   write_refresh("?page=tourny&tournyid=".$tourny->id."&cmd=match&matchid=".$match->id);

  }



  //make link to save servers

  $link = "?page=tourny&tournyid=".$tourny->id."&cmd=selserver&matchid=".$match->id."&selserver=";



  $tpl->parsefile("CONTENT", "tourny_selserver.tpl", array(

    "LINK_RETURN" => "?page=tourny&tournyid=".$tourny->id."&cmd=match&matchid=".$match->id,

    "LINK_NULL"   => $link . "0",

    "SERVERS"     => $tourny->get_list_servers($link)

   ));

 }



 function write_tourny_servers(){global $tourny, $tpl, $servers;

  $tpl->splice("TOURNY", "tourny_servers.tpl");



  foreach($tourny->servers() as $serverid){

   $server = &$servers->server($serverid);



   if($server->id > 0)

    $tpl->parse("TOURNY->SERVER_ROWS", "TOURNY->SERVER_ROWS", 1, array(

      "ROW_CLASS"   => (++$i % 2)?"row":"rowoff",

      "SERVER_NAME" => $server->get('name'),

      "SERVER_IP"   => $server->get('ip')

     ));

  }



  if($tpl->fetch("TOURNY->SERVER_ROWS") != '') $tpl->parse("TOURNY->SERVERS", "TOURNY->SERVERS");

  else $tpl->assign("TOURNY->SERVERS", '');



  $tpl->parse("CONTENT", "TOURNY", array(

    "TOURNY->SERVER_REQS" => $tpl->assignc($tourny->get("serverrequirments"), "SERVER_REQS", "TOURNY->SERVER_REQS")

   ));

 }



 function write_tourny_summary(){global $tourny, $tpl, $users, $teams;

  $tpl->splice("TOURNY", "tourny_summary.tpl");



  //parse admins

  if(count($tourny->admins()) > 0){

   $users->user($tourny->admins());



   foreach($tourny->admins() as $admin_id){

    $admin = &$users->user($admin_id);



    if($admin->id > 0){

     $tpl->assign("ADMIN_NAME", $admin->get_alink_profile() );

     $tpl->parse("TOURNY->ADMIN_COLS", "TOURNY->ADMIN_COLS", 1);



     if(++$i % 3 == 0){

      $tpl->parse("TOURNY->ADMIN_ROWS","TOURNY->ADMIN_ROWS", 1);

      $tpl->clear("TOURNY->ADMIN_COLS");

   }}}



   if($i % 3 != 0) $tpl->parse("TOURNY->ADMIN_ROWS","TOURNY->ADMIN_ROWS", 1);

   $tpl->parse("TOURNY->ADMINS", "TOURNY->ADMINS");

  }

  if($tpl->fetch("TOURNY->ADMIN_ROWS") == '') $tpl->assign("TOURNY->ADMINS", '');



  //parse teams

  if(count($tourny->teams()) > 0){

   $teams->team($tourny->teams());



   foreach($tourny->teams() as $teamid){

    $team = &$teams->team($teamid);



    if($team->id > 0){

      $tpl->parse("TOURNY->TEAMS_COLS", "TOURNY->TEAMS_COLS", 1, array(

        "TEAM_NAME" => $team->get_alink_profile()

       ));



     if(++$ii % 3 == 0){

      $tpl->parse("TOURNY->TEAMS_ROWS", "TOURNY->TEAMS_ROWS", 1);

      $tpl->clear("TOURNY->TEAMS_COLS");

   }}}



   if($ii % 3 != 0) $tpl->parse("TOURNY->TEAMS_ROWS", "TOURNY->TEAMS_ROWS", 1);

   $tpl->parse("TOURNY->TEAMS", "TOURNY->TEAMS");

  }

  if($tpl->fetch("TOURNY->TEAMS_ROWS") == '') $tpl->assign("TOURNY->TEAMS", '');



  $tpl->parse("CONTENT", "TOURNY", array(

    "TOURNY->GAME_NAME" => $tpl->assignc(getgame($tourny->get("game")), "CONTENT", "TOURNY->GAME_NAME"),

    "TOURNY->GAME_TYPE" => $tpl->assignc($tourny->get("gametype"), "CONTENT", "TOURNY->GAME_TYPE"),

    "TOURNY->GAME_MOD"  => $tpl->assignc($tourny->get("mod"), "CONTENT", "TOURNY->GAME_MOD"),

   "TOURNY->GAME"  => $tpl->assignc($tourny->get("mod") . $tourny->get("gametype") . $tourny->get("mod"), '', "TOURNY->GAME"),



   "TOURNY->NEWS"     => $tpl->assignc($tourny->get("news"), "CONTENT", "TOURNY->NEWS"),

   "TOURNY->SPONS"    => $tpl->assignc($tourny->get("sponsers"), "CONTENT", "TOURNY->SPONS"),

   "TOURNY->PRIZES"   => $tpl->assignc($tourny->get("prizes"), "CONTENT", "TOURNY->PRIZES"),

   "TOURNY->DESC"     => $tpl->assignc($tourny->get("details"), "CONTENT", "TOURNY->DESC"),

   "TOURNY->SCHEDULE" => $tpl->assignc($tourny->get("schedule"), "CONTENT", "TOURNY->SCHEDULE"),

   "TOURNY->MAPS"     => $tpl->assignc($tourny->get("maps"), "CONTENT", "TOURNY->MAPS"),

   "TOURNY->RULES"    => $tpl->assignc($tourny->get("rules"), "CONTENT", "TOURNY->RULES")

  ));

 }



 //write tournament page header - same for every page

 function write_tourny_header(){global $tpl, $tourny, $users, $user;

  $tpl->splice("TOURNY_HEAD", "tourny_header.tpl");



  //check if they can join tournament

  if($tourny->stage() == $GLOBALS["tourny_stage_signup_open"]){

   if($tourny->type() == $GLOBALS["tourny_type_single"])

    $showjoin = $tourny->is_team($user->id);

   if($tourny->type() == $GLOBALS["tourny_type_team"])

    if($user->id > 0)

    if(is_array($user->teams()))

    foreach($user->teams() as $teamid)

     if(!$tourny->is_team($teamid)) $showjoin = true;

  }



  //show new users to signup or login

  if(!$user->id > 0) $showjoin = true;



  //grab date obj

  $date = new time($tourny->get("time"));



  $tpl->assign(array(

    "TOURNY_HEAD->JOIN"   => $tpl->assignc($showjoin, "", "TOURNY_HEAD->JOIN"),

    "TOURNY_HEAD->BANNER" => $tpl->assignc($tourny->get("banner"), "BANNER_ID", "TOURNY_HEAD->BANNER"),

    "TOURNY_HEAD->DATE"   => $tpl->assignc($date->get_formated(), "TOURNY_DATE", "TOURNY_HEAD->DATE"),

    "TOURNY_HEAD->MAX"    => $tpl->assignc($tourny->get("maxjoin"), "TOURNY_JOIN", "TOURNY_HEAD->MAX"),

    "TOURNY_ID"   => $tourny->id,

    "TOURNY_NAME" => $tourny->get("name"),

    "TYPE_NAME"   => $tourny->get_type_name(),

    "TYPE"        => $tourny->get_type_name()

  ));



  $tpl->parse("CENTER", "TOURNY_HEAD");

 }



 //make sure the commands arent called outside of the correct stage

 $tourny =& $tournys->tourny($tournyid);



 if($tourny->id > 0){ //valid tourny

  write_tourny_header();



  switch($cmd){

  case "servers":

   write_tourny_servers();

   break;

  case "selserver":

   if($tourny->get("creator") == $user->id || $user->get("admin") >= $GLOBALS["level_tourny"]) //safety check

    write_tourny_selserver();

   break;

  case "selteam":

   if($tourny->get("creator") == $user->id || $user->get("admin") >= $GLOBALS["level_tourny"]) //safety check

    write_tourny_selteam();

   break;

  case "module":

   write_tourny_module();

   break;

  case "matchs":

   write_tourny_matchs();

   break;

  case "match":

   write_tourny_match();

   break;

  case "draft":

   write_tourny_draft();

   break;

  case "draftteam":

   write_tourny_draft_team();

   break;

  case "join":

   write_tourny_join();

   break;

  default:

   write_tourny_summary();

   break;

 }}



?>