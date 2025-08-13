<? /*************************************************************************************************
Copyright (c) 2003, 2004 Nathan 'Random' Rini
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*************************************************************************************************/ if(!defined('CONFIG')) die;
 /*
  Qualifing Module Class
 */

 class module_qualifing extends db_tourny_module {
  function module_qualifing($id){
   parent::db_tourny_module($id);
  }

  //Calculates Min matchs required for all teams
  function match_count_min($team_count, $tpm){
   if($team_count < 1) return 0; //dont waste cpu for no teams

   //force atleast 2 tpm
   $tpm = $tpm >= 2 ? ceil($tpm) : 2;

   //Number of matchs per team count (usually a float)
    //ceil it to make sure there are no partial matchs
   return floor($team_count / $tpm);
  }

  function generate_score(&$matchs){
   //team list array
   $teamlst = array();

   //Grab the counts for each team's status from every match
   if(is_array($matchs) && !empty($matchs)) //valid list
   foreach($matchs as $match) if($match->id > 0) //valid match
    for($i = 0;$i < $this->tpm();$i++){  //run through each team
     //grab team's id
     $teamid = (INT) $match->get_team($i);

     if($teamid > 0){ //valid team
      //grab status
      $status = (INT) $match->get_team($i, "status");

      //save stat count
      $teamlst[$teamid][$status] += 1;

      //calc score
      switch($status){
       case $GLOBALS["MATCH_STATUS"]["Winner"]:
        $teamlst[$teamid]["score"] += (INT) $this->get_config("points_win");
        break;
       case $GLOBALS["MATCH_STATUS"]["Loser"]:
        $teamlst[$teamid]["score"] += (INT) $this->get_config("points_lose");
        break;
       case $GLOBALS["MATCH_STATUS"]["Tie"]:
        $teamlst[$teamid]["score"] += (INT) $this->get_config("points_tie");
        break;
       case $GLOBALS["MATCH_STATUS"]["Forfeit"]:
        $teamlst[$teamid]["score"] += (INT) $this->get_config("points_forfeit");
        break;
    }}}

   return $teamlst;
  }

  //Creates tpl cache for Qualifing
  function generate_tpl($queryid = TRUE){global $tpl, $tourny;
   $tpl->splice("QUALS", "module_qual_list.tpl");

   //Grab all the Matchs
   $matchs =& $this->matchs(-1, FALSE, TRUE, $queryid);
   $matchs =& $matchs["ref"]; //grab list only

   //grab the teams by score
   $teamlst = $this->generate_score(&$matchs);

   if(is_array($teamlst) && !empty($teamlst) && is_array($tourny->teams()))
   foreach($tourny->teams() as $teamid) if($teamid > 0) //ignore any nulls
    //grab team's score
    $tpl->parse("QUALS->TEAMS", "QUALS->TEAM", true, array(
     "NAME"  => "{team_".$teamid."}",
     "WINS"  => (INT) $teamlst[$teamid][$GLOBALS["MATCH_STATUS"]["Winner"]],
     "LOSES" => (INT) $teamlst[$teamid][$GLOBALS["MATCH_STATUS"]["Loser"]],
     "TIES"  => (INT) $teamlst[$teamid][$GLOBALS["MATCH_STATUS"]["Tie"]],
     "SCORE" => (INT) $teamlst[$teamid]["score"]
    ));

   if($tpl->fetch("QUALS->TEAMS") == '') //null check
    $tpl->parse("QUALS->TEAMS", "QUALS->NONE");

   //parse and save
   $tpl->save($this->get("tpl"), $tpl->parse("QUALS", "QUALS"));
  }

  //converts status to route type
   //route codes are diff than whats shown to pub
  function convert_route_type($route_types, $type){
   //no route types for quals
   return FALSE;
  }

  //team list should be order from best players (ie more byes) to loser players
  function seed($teams){global $tourny;
   set_time_limit(600); //6 min max -- special override for generating only

   $this->del_matchs(); //clear old matchs

   $this->set(array(
     "rounds"    => $this->get_config("rounds"),  //round count
     "round"     => 1,  //current round
     "generated" => 1,  //save that we made bracket
     "tpl"       => "module_" . $this->id . "_tpl.tpl" //unique id
    ));

   $tpm    = $this->tpm();
   $rounds = $this->get_config("rounds");
   $matchc = $this->match_count_min(count($teams), $tpm);

   //grab all teams listed for each round
   $teamlst = $this->create_team_lists($teams, $rounds, $matchc);

   for($r=1;$r <= $rounds;$r++)
    for($m=0;$m < $matchc;$m++){
     $match =& $tourny->match(0,1);

     //save round data
     $match->set(array(
       "moduleid" => $this->id,
       "round"    => $r
      ));

     for($t=0;$t < $tpm;$t++)
      $match->set_team($t, "id", $teamlst[$r][$m][$t]);

     unset($match);
    }

   $this->assign_servers_round(1, true);
   $this->generate_tpl(true);
  }

  //Creates list of teams for seeding rounds
  function create_team_lists($teams, $rounds = 1, $matchc){
   if(!$rounds > 0) return array(); //must have atleast 1 round
   if(!$matchc > 0) return array(); //must have atleast 1 match

   //remove any null values in teams
   $teams = remove_nulls($teams);

   if(!count($teams) > 1) return array(); //must have atleast 2 teams

   for($r=1;$r <= $rounds;$r++)
    $this->create_team_list($teams, $r, &$teamlst, $matchc);

   return $teamlst;
  }

  //Creates list of teams for seeding a single round - checks for any reps
  function create_team_list($teams, $round, &$teamlst, $matchc){
   $teamc = count($teams);

   for($ii = 0;$ii < 999;$ii++){ //safety limit of 999
    //grab a random team
    $teamid   = $this->get_random_team(&$teams, &$teamc);

    //check if random team is already in a match in this round
    if(!$this->check_seed_team_repeat($teamid, &$mteams, &$teamlst, $round)){
     //check if random team is valid
     if($this->check_seed_team_repeat_past($teamid, &$mteams, &$vteams))
      //add team to match team list
      $mteams[] = $teamid;

     if(count($mteams) == $this->tpm()){//enough teams in a match
      //Save which teams have played against each other
      foreach($mteams as $mteamid)
       foreach($mteams as $mteamid2)
        if($mteamid != $mteamid2) //dont add same team
         $vteams[$mteamid][] = $mteamid;

      //add match teams to list of matchs
      $teamlst[$round][] = $mteams;

      unset($mteams); //clear match teams

      //return if we hit match count
      if($matchc == count($teamlst[$round])) return true;
   }}}

   die("Not Enough Teams to Seed All Rounds");
  }

  //grabs a random team - use refs -- no need to copy arrays every time its called
  function get_random_team(&$teams, &$teamc){
   return $teams[rand(0, $teamc - 1)];
  }

  //Checks that a Team isnt Already in a Match in this Round
  function check_seed_team_repeat($teamid, &$mteams, &$teamlst, $round){
   //Are they in this Match Already?
   if(is_array($mteams))
    if(in_array($teamid, $mteams))
     return true;
   //Are they in any other Matchs this round?
   if(is_array($teamlst[$round]))
    foreach($teamlst[$round] as $match) //each match
     if(is_array($match))
      if(in_array($teamid, $match))
       return true;
  }

  //Checks that a team is not in the same match
  // with the same team in a previous match
  function check_seed_team_repeat_past($teamid, &$mteams, &$vteams){
   if(is_array($mteams))
   foreach($mteams as $mteamid)
    if(is_array($vteams[$teamid]))
    if(in_array($mteamid, $vteams[$teamid]))
     $fail = true;

   return !$fail;
  }

  //Shows public form of Module
  function show(){global $tourny, $apanel, $tpl, $teams, $users;
   //run through and assign each team name
   foreach($tourny->teams() as $teamid) if($teamid > 0){
    if($tourny->type() == $GLOBALS["tourny_type_single"])
     $team =& $users->user($teamid);
    if($tourny->type() == $GLOBALS["tourny_type_team"])
     $team =& $teams->team($teamid);

    if($team->id > 0)
     $tpl->assign("team_".$team->id, $team->get("name"));
   }


   $tpl->parsefile("SCORES", $GLOBALS["loc_cache"]. $this->get("tpl"));

   $tpl->parsefile("CONTENT", "module_qual_show.tpl", array(
     "MODULE_NAME" => $this->get("name")
    ));
  }

  function write_match_status(&$match, $edit){global $tourny, $tpl, $users, $teams;
   $tpl->splice("STATUS", "module_qual_match_status.tpl");

   //grab team count
   $tpm = $this->get("teamspermatch");

   for($i=0;$i < $tpm;$i++){
    if($tourny->type() == $GLOBALS["tourny_type_single"])
     $team =& $users->user($match->get_team($i));
    if($tourny->type() == $GLOBALS["tourny_type_team"])
     $team =& $teams->team($match->get_team($i));

    if($team->id > 0) //valid team
     $tpl->assign("STATUS->STATUS_TEAM_NAME", $team->get("name"));
    else //null team
     $tpl->parse("STATUS->STATUS_TEAM_NAME", "STATUS->STATUS_TEAM_NAME");

    //null out all the checks
    $tpl->assign(array(
      "FIELD_WIN_CHK_0"   => '',
      "FIELD_WIN_CHK_1"   => '',
      "FIELD_WIN_CHK_2"   => '',
      "FIELD_WIN_CHK_3"   => '',
      "FIELD_WIN_CHK_4"   => ''
     ));

    //assign checked value
    if($match->get_team($i, "status") >= $GLOBALS["MATCH_STATUS"]["LBOUND"] && $match->get_team($i, "status") <= $GLOBALS["MATCH_STATUS"]["HBOUND"])
     $tpl->assign("FIELD_WIN_CHK_" . $match->get_team($i, "status"), " checked ");
    else //make sure something is selected
     $tpl->assign("FIELD_WIN_CHK_0", " checked ");

    $tpl->parse("STATUS->STATUS_TEAM_LIST", "STATUS->STATUS_TEAM_LIST", true, array(
      "TEAM_LINK" => "?page=profile&type=".$tourny->type()."&id=".$team->id,
      "FIELD_WIN_NAME"    => "STATUS_".$i,
      "FIELD_WIN_VALUE_4" => $GLOBALS["MATCH_STATUS"]["Winner"],
      "FIELD_WIN_VALUE_3" => $GLOBALS["MATCH_STATUS"]["Loser"],
      "FIELD_WIN_VALUE_2" => $GLOBALS["MATCH_STATUS"]["Tie"],
      "FIELD_WIN_VALUE_1" => $GLOBALS["MATCH_STATUS"]["Forfeit"],
      "FIELD_WIN_VALUE_0" => $GLOBALS["MATCH_STATUS"]["Undecided"],
      "FIELD_WIN_DIS_4"   => $edit ? "" : "disabled",
      "FIELD_WIN_DIS_3"   => $edit ? "" : "disabled",
      "FIELD_WIN_DIS_2"   => $edit ? "" : "disabled",
      "FIELD_WIN_DIS_1"   => $edit ? "" : "disabled",
      "FIELD_WIN_DIS_0"   => $edit ? "" : "disabled"
     ));
   }

   $tpl->parse("MATCH->STATUS_EDIT", "STATUS");
  }

  //check that given status is a valid solution
   //defeat - check that they are giving up
  function validate_match_status(&$match, $status, $score, $defeat = false){global $teams, $tourny, $user;
   //all fake teams are forfeits -- force override
   for($t=0;$t < $this->tpm();$t++)
    if(!$match->get_team($t) > 0) $status[$t] = $GLOBALS["MATCH_STATUS"]["Undecided"];

   if($defeat) //The defeated must admit defeat - So if they dont - they forfeit
   for($t=0;$t < $this->tpm();$t++){
    //Single Player
    if($tourny->type() == $GLOBALS["tourny_type_single"]) //force them to lose
     if($match->get_team($t) == $user->id) //make sure they are loser or forfieting
      if(!($status[$t] == $GLOBALS["MATCH_STATUS"]["Loser"] || $status[$t] == $GLOBALS["MATCH_STATUS"]["Forfeit"]))
       $cheat[] = $t; //dont let it unForfeit them

    //Team
    if($tourny->type() == $GLOBALS["tourny_type_team"]){
     //grab team for match spot
     $team =& $teams->team($match->get_team($t));

     if($team->is_user($user->id)){ //make sure they are loser or forfieting
      if(!($status[$t] == $GLOBALS["MATCH_STATUS"]["Loser"] || $status[$t] == $GLOBALS["MATCH_STATUS"]["Forfeit"]))
       $cheat[] = $t; //dont let it unForfeit them
    }}

    if(is_array($cheat)) //void match for evil cheaters
     for($t=0;$t < $this->tpm();$t++){ //make everyone undecided but cheater(s)
      if(!in_array($t, $cheat)) $status[$t] = $GLOBALS["MATCH_STATUS"]["Undecided"];
      else $status[$t] = $GLOBALS["MATCH_STATUS"]["Forfeit"]; //evil cheater
     }
   }

   //make sure there is atleast 1 winner
   for($t=0;$t < $this->tpm();$t++)
    if($status[$t] == $GLOBALS["MATCH_STATUS"]["Winner"])
     $win_set = true;

   if($defeat){ //admins can fail everyone
    if($win_set){ //if valid status = those who dont play forfiet
     for($t=0;$t < $this->tpm();$t++)
      if($status[$t] == $GLOBALS["MATCH_STATUS"]["Undecided"])
       $status[$t] = $GLOBALS["MATCH_STATUS"]["Forfeit"];
    }else //no winner set - void match
     if(!is_array($cheat)) //make sure not to cancel cheaters matchs
      for($t=0;$t < $this->tpm();$t++) //make everyone undecided
       $status[$t] = $GLOBALS["MATCH_STATUS"]["Undecided"];
   }

   //save scores and status
   for($t=0;$t < $this->tpm();$t++)
    if($status[$t] >= $GLOBALS["MATCH_STATUS"]["LBOUND"] && $status[$t] <= $GLOBALS["MATCH_STATUS"]["HBOUND"]){//valid status
     $match->set_team($t, "status", $status[$t]); //team

     for($m=0;$m < $this->get("mapspermatch");$m++) //map
      $match->set_score($t, $m, $score[$t][$m]);
    }

   //verify that it is decided
   $match->check_decided();

   //recalc score sheet
   $this->generate_tpl(false);
  }

  //setup page in admin console for bracket
  function write_setup(){global $tourny, $apanel, $tpl, $teams, $users;
   $tpl->assign("MODULE_ID", $this->id);

   if($_GET["cmdtype"] > 0){
    switch($_GET["cmdtype"]){
     case "1": //seed
      if(!isset($_GET["destmod"])){
       $tourny->refresh_module_select_teams("?page=admin&cmd=tourny&cmdd=modules&tournyid=".$tourny->id."&module=".$this->id."&cmdtype=".$_GET["cmdtype"]."&destmod=1");

       return;
      }

      $this->seed($this->teams());
      break;
     case "2": //clear
      $this->seed(array());
      break;
    }

    return write_refresh("?page=admin&cmd=tourny&cmdd=modules&tournyid=".$tourny->id."&module=".$this->id);
   }

   $tpl->parsefile("CONTENT", "module_qual_setup.tpl");

   //run through and assign each team name
   foreach($tourny->teams() as $teamid) if($teamid > 0){
    if($tourny->type() == $GLOBALS["tourny_type_single"])
     $team =& $users->user($teamid);
    if($tourny->type() == $GLOBALS["tourny_type_team"])
     $team =& $teams->team($teamid);

    if($team->id > 0)
     $tpl->assign("team_".$team->id, $team->get("name"));
   }

   $tpl->parsefile("ROUND_LIST", $GLOBALS["loc_cache"]. $this->get("tpl"));
  }

  function create_module_teams(){global $tourny;
   //Grab all the Matchs
   $matchs =& $this->matchs(-1, FALSE, TRUE, $queryid);
   $matchs =& $matchs["ref"]; //grab list only

   //number of teams qualifing per division
   $qual = $this->get_config("qualifing");

   //team list array
   $teamlst = $this->generate_score(&$matchs);

   //check that teams can pass on
   if(!$qual > 0){ //everyone wins
    if(is_array($teamlst) && !empty($teamlst) && is_array($tourny->teams()))
    foreach($tourny->teams() as $teamid)
    if($teamid > 0 && isset($teamlst[$teamid])) //ignore any nulls
     //Save teams for sorting
     $teams_id  = (INT) $teamid;

    $this->set_teams($teams_id);

    return;
   }

   if(is_array($teamlst) && !empty($teamlst) && is_array($tourny->teams()))
   foreach($tourny->teams() as $teamid)
    if($teamid > 0 && isset($teamlst[$teamid])){ //ignore any nulls
     //Save Scores and teams for sorting
     $teams_id[]    = (INT) $teamid;
     $teams_score[] = (INT) $teamlst[$teamid]["score"];

     $div_teams[$teamid] = (INT) $teamlst[$teamid]["score"];
    }

   //make sure its not empty
   if(!is_array($teams_id) && empty($teams_id)) return;

   //sort each team according to score
   array_multisort($teams_id, SORT_DESC,  $teams_score);

   //grab the qualifing teams
   for($t=0;$t < $qual;$t++)
    if($teams_id[$t] > 0){ //valid team
     //used to sort
     $teams_out_score[] = $div_teams[$teams_id[$t]];
     $teams_out_id[]    = $teams_id[$t];
    }

   //reset the arrays
   unset($teams_score); unset($teams_id); unset($div_teams);

   //sort each team according to score
   array_multisort($teams_out_id, SORT_DESC, $teams_out_score);

   //save the sorted teams
   $this->set_teams($teams_out_id);
  }
 }

 //presetup of bracket before generation
 function write_presetup_module_qualifing(){global $tourny, $apanel, $tpl;
  $tpl->splice("SETUP", "module_qual_presetup.tpl");

  if($_POST["submit"]){
   if( //check values
    ($_POST["tpm"] >= 2 && $_POST["tpm"] <= $tourny->get("maxteamspermatch"))
     &&
    ($_POST["mpm"] >= 1 && $_POST["mpm"] <= 999)
     &&
    (strlen($_POST["name"]) >= 5 && strlen($_POST["name"]) <= 125)
     &&
    ($_POST["point_win"] >= -999 && $_POST["point_win"] <= 999)
     &&
    ($_POST["point_lose"] >= -999 && $_POST["point_lose"] <= 999)
     &&
    ($_POST["point_tie"] >= -999 && $_POST["point_tie"] <= 999)
     &&
    ($_POST["point_forfeit"] >= -999 && $_POST["point_forfeit"] <= 999)
     &&
    ($_POST["rounds"] >= 1 && $_POST["rounds"] <= 999)
     &&
    ($_POST["qualifing"] >= 0 && $_POST["qualifing"] <= 999)
   ){
    $module = &$tourny->module(0, 1, $_GET["create"]);

    $module->set(array(
      "name"          => $_POST["name"],
      "teamspermatch" => $_POST["tpm"],
      "mapspermatch"  => $_POST["mpm"]
     ));

    //save config only associated with this module type
    $module->set_config(array(
      "qualifing"      => $_POST["qualifing"],
      "rounds"         => $_POST["rounds"],
      "points_win"     => $_POST["point_win"],
      "points_lose"    => $_POST["point_lose"],
      "points_tie"     => $_POST["point_tie"],
      "points_forfeit" => $_POST["point_forfeit"]
     ));

    //create blank round list
    $module->seed(array());

    return write_refresh("?page=admin&cmd=tourny&cmdd=modules&tournyid=".$tourny->id);
   }else //not valid - error out
    $tpl->parse("SETUP->ERROR", "SETUP->ERROR");
  }else $tpl->assign("SETUP->ERROR", ''); //hide error tpl

  $tpl->assign(array(
    "TPM_MAX"          => $tourny->get("maxteamspermatch"),
    "FIELD_NAME_NAME"  => "name",
    "FIELD_NAME_VALUE" => (strlen($_POST["name"]) >= 5 && strlen($_POST["name"]) <= 125) ? $_POST["name"] : ($_POST["name"] == '' ? "Qualifing Rounds" : substr($_POST["name"], 0, 125) ),
    "FIELD_TPM_NAME"   => "tpm",
    "FIELD_TPM_VALUE"  => $_POST["tpm"] > 2 && $_POST["tpm"] <= $tourny->get("maxteamspermatch") ? $_POST["tpm"] : 2,
    "FIELD_MPM_NAME"   => "mpm",
    "FIELD_MPM_VALUE"  => $_POST["mpm"] > 1 && $_POST["mpm"] < 1000 ? $_POST["mpm"] : 1,

    "FIELD_ROUNDS_NAME"  => "rounds",
    "FIELD_ROUNDS_VALUE" => $_POST["rounds"] > 0 && $_POST["rounds"] < 1000 ? $_POST["rounds"] : 3,
    "FIELD_QUAL_NAME"  => "qualifing",
    "FIELD_QUAL_VALUE" => $_POST["qualifing"] >= 0 && $_POST["qualifing"] < 1000 && $_POST["qualifing"] != '' ? $_POST["qualifing"] : rand(5,30),

    "FIELD_WIN_NAME"   => "point_win",
    "FIELD_WIN_VALUE"  => $_POST["point_win"] > -1000 && $_POST["point_win"] < 1000 ? $_POST["point_win"] : 100,
    "FIELD_LOSE_NAME"  => "point_lose",
    "FIELD_LOSE_VALUE" => $_POST["point_lose"] > -1000 && $_POST["point_lose"] < 1000 ? $_POST["point_lose"] : 0,
    "FIELD_TIE_NAME"   => "point_tie",
    "FIELD_TIE_VALUE"  => $_POST["point_tie"] > -1000 && $_POST["point_tie"] < 1000 ? $_POST["point_tie"] : 50,
    "FIELD_FORFEIT_NAME"   => "point_forfeit",
    "FIELD_FORFEIT_VALUE"  => $_POST["point_forfeit"] > -1000 && $_POST["point_forfeit"] < 1000 ? $_POST["point_forfeit"] : -50,

    "FIELD_SUBMIT_NAME" => "submit"
   ));

  $apanel->set_cnt("SETUP", 1);
 }

?>