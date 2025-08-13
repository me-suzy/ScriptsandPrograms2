<? /*************************************************************************************************
Copyright (c) 2003, 2004 Nathan 'Random' Rini
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*************************************************************************************************/ if(!defined('CONFIG')) die;
 /*
  Round Robin Module Class
 */

 class module_robin extends db_tourny_module {
  function module_robin($id){
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

  //converts status to route type
   //route codes are diff than whats shown to pub
  function convert_route_type($route_types, $type){
   //no route types for robin
   return FALSE;
  }

  //team list should be order from best players (ie more byes) to loser players
  function seed($teams){global $tourny;
   $this->del_matchs(); //clear old matchs

   $tpm   = $this->tpm();
   $divs  = (INT) $this->get_config("divisions");
   $teamc = count($teams) - 1;

   //divide the teams into divisions
   $divteams = $this->create_team_divisions($teams, $tpm, $divs, $teamc + 1);

   //check to make sure there are division and teams
   if(is_array($divteams))
   if(!empty($divteams))
   foreach($divteams as $teamlst){
    $div_teams[] = $matchs = $this->create_div_matchs($teamlst, $tpm);

    if(is_array($matchs))
    foreach($matchs as $round => $mteams){
     //grab the highest round number
     if($round + 1 > $hrounds) $hrounds = $round + 1;

     //create a match
     $match =& $tourny->match(0,1);

     //save round data
     $match->set(array(
       "moduleid" => $this->id,
       "round"    => $round + 1
      ));

     for($t=0;$t < $tpm;$t++)
      $match->set_team($t, "id", $mteams[$t]);

     unset($match);
   }}

   //save which team is in which division
   $this->set_config("division_teams", $divteams);

   $this->set(array(
     "rounds"    => $hrounds,  //round count
     "round"     => 1,  //current round
     "generated" => 1,  //save that we made bracket
     "tpl"       => "module_" . $this->id . "_tpl.tpl" //unique id
    ));

   $this->assign_servers_round(1, true);
   $this->generate_tpl(true);
  }

  //creates an array of all the teams in their divisions
  function create_team_divisions($teams, $tpm, $divs, $teamc){
   //grab the number of teams per divsions
   $teamsperdiv     = floor($teamc / $divs);
   //grab the number of teams left out
   $teamsperdiv_rem = ceil($teamc % $divs);

   $t = 0; //start team key at 0
   for($d=1;$d <= $divs;$d++){
    if($teamsperdiv_rem > 0)
     $tpd = $teamsperdiv + 1;
    else
     $tpd = $teamsperdiv;

    for($i = 0;$i < $tpd;$i++)
     $divteams[$d][$i] = $teams[$t++];

    if($teamsperdiv_rem > 0)
     $teamsperdiv_rem--;
   }

   return $divteams;
  }

  //run throught and create all the matchs for a division
  function &create_div_matchs($teamlst, $tpm){
   //make sure there are teams
   if(!is_array($teamlst)) return;
   if(empty($teamlst))     return;

   $teamc  = count($teamlst);
   $matchs = array(); //create blank match array

   for($t=0;$t < $teamc;$t++){
    //copy team list
    $vteams = $teamlst;
    //remove current team
    unset($vteams[$t]);
    //fix keys
    sort($vteams);
    //run through each team
    for($v=0;$v < $teamc - 1;$v++){
     for($g=0;$g < $tpm - 1;$g++)
      //add team the required teams
      $match[] = $vteams[$v + $g];

     //add current team
     $match[] = $teamlst[$t];

     //check if match is a repeat
     if(!$this->check_match_repeat(&$matchs, $match))
      //add match to matchs
      $matchs[] = $match;

     //clear match teams
     unset($match);
   }}

   return $matchs;
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
      //calc score
      if($this->get("pointsbymap")){
       $team_stat = $match->get_score_summary($i);

       //Save New Aggregate Score
       $teamlst[$teamid]["score"] += ((INT) $this->get_config("points_win"))  * ((INT) $team_stat["WIN"]);
       $teamlst[$teamid]["score"] += ((INT) $this->get_config("points_lose")) * ((INT) $team_stat["LOSE"]);
       $teamlst[$teamid]["score"] += ((INT) $this->get_config("points_tie"))  * ((INT) $team_stat["TIE"]);

       //Save New Status Counts
       $teamlst[$teamid][$GLOBALS["MATCH_STATUS"]["Winner"]] += (INT) $team_stat["WIN"];
       $teamlst[$teamid][$GLOBALS["MATCH_STATUS"]["Loser"]]  += (INT) $team_stat["LOSE"];
       $teamlst[$teamid][$GLOBALS["MATCH_STATUS"]["Tie"]]    += (INT) $team_stat["TIE"];;
      }else{ //points by map
       //grab status
       $status = (INT) $match->get_team($i, "status");

       //save stat count
       $teamlst[$teamid][$status] += 1;

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
    }}}}

   return $teamlst;
  }

  //Creates tpl cache for robinifing
  function generate_tpl($queryid = TRUE){global $tpl, $tourny;
   $tpl->splice("ROBIN", "module_robin_list.tpl");

   //Grab all the Matchs
   $matchs =& $this->matchs(-1, FALSE, TRUE, $queryid);
   $matchs =& $matchs["ref"]; //grab list only

   //team list array
   $teamlst = &$this->generate_score($matchs);

   //division teams
   $dteams = $this->get_config("division_teams");
   if(is_array($dteams))
   foreach($dteams as $div => $teams){
    if(is_array($teams))
    foreach($teams as $teamid){
     $tpl->parse("ROBIN->TEAMS", "ROBIN->TEAM", true, array(
      "NAME"  => "{team_".$teamid."}",
      "WINS"  => (INT) $teamlst[$teamid][$GLOBALS["MATCH_STATUS"]["Winner"]],
      "LOSES" => (INT) $teamlst[$teamid][$GLOBALS["MATCH_STATUS"]["Loser"]],
      "TIES"  => (INT) $teamlst[$teamid][$GLOBALS["MATCH_STATUS"]["Tie"]],
      "SCORE" => (INT) $teamlst[$teamid]["score"]
     ));
    }

    if($tpl->fetch("ROBIN->TEAMS") == '') //null check
     $tpl->parse("ROBIN->TEAMS", "ROBIN->NONE");

    $tpl->parse("ROBIN_LIST", "ROBIN->DIVISION", true, array(
      "DIVISION_ID" => $div
     ));

    $tpl->clear("ROBIN->TEAMS");
   }

   if($tpl->fetch("ROBIN_LIST") == '') //null check
     $tpl->parse("ROBIN_LIST", "ROBIN->DIVISION_NONE");

   //parse and save
   $tpl->save($this->get("tpl"), $tpl->fetch("ROBIN_LIST"));
  }

  //gotta check match (ie group of teams) is already existant
  function check_match_repeat(&$matchs, $match){
   if(is_array($matchs))
   foreach($matchs as $vmatch)
    if(is_array($vmatch)){
     $diff = array_diff($match, $vmatch);

     if(empty($diff)) //dub
      return true;
    }
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

   $tpl->parsefile("CONTENT", "module_robin_show.tpl", array(
     "MODULE_NAME" => $this->get("name")
    ));
  }

  function write_match_status(&$match, $edit){global $tourny, $tpl, $users, $teams;
   $tpl->splice("STATUS", "module_robin_match_status.tpl");

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

   //recreate score sheet
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

   $tpl->parsefile("CONTENT", "module_robin_setup.tpl");

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

  function create_module_teams(){
   //Grab all the Matchs
   $matchs =& $this->matchs(-1, FALSE, TRUE, $queryid);
   $matchs =& $matchs["ref"]; //grab list only

   //number of teams qualifing per division
   $qual = $this->get_config("qualifing");

   //check that teams can pass on
   if(!$qual > 0){ //everyone wins
    $dteams = $this->get_config("division_teams");

    if(is_array($dteams))
    foreach($dteams as $div => $teams)
     if(is_array($teams))
      foreach($teams as $teamid)
       if($teamid > 0) //valid team
        $teams_out[] = $teamid;

    $this->set_teams($teams_out);

    return;
   }

   //team list array
   $teamlst = $this->generate_score($matchs);

   //division teams
   $dteams = $this->get_config("division_teams");

   if(is_array($dteams))
   foreach($dteams as $div => $teams)
   if(is_array($teams)){
    foreach($teams as $teamid){
     //Save Scores and teams for sorting
     $teams_id[]    = (INT) $teamid;
     $teams_score[] = (INT) $teamlst[$teamid]["score"];

     $div_teams[$teamid] = (INT) $teamlst[$teamid]["score"];
    }

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
   }

   //sort each team according to score
   array_multisort($teams_out_id, SORT_DESC, $teams_out_score);

   //save the sorted teams
   $this->set_teams($teams_out_id);
  }
 }

 //presetup of bracket before generation
 function write_presetup_module_robin(){global $tourny, $apanel, $tpl;
  $tpl->splice("SETUP", "module_robin_presetup.tpl");

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
    ($_POST["divisions"] >= 1 && $_POST["divisions"] <= 999)
     &&
    ($_POST["qualperdiv"] >= 0 && $_POST["qualperdiv"] <= 999)
   ){
    $module = &$tourny->module(0, 1, $_GET["create"]);

    switch($_POST["point_type"]){
     case "match":
      $type = 0;
      break;
     case "map":
      $type = 1;
      break;
     default: //default to points by match
      $type = 0;
    }

    $module->set(array(
      "name"          => $_POST["name"],
      "teamspermatch" => $_POST["tpm"],
      "mapspermatch"  => $_POST["mpm"],
      "pointsbymap"   => $type
     ));

    //save config only associated with this module type
    $module->set_config(array(
      "divisions"      => $_POST["divisions"],
      "qualifing"      => $_POST["qualperdiv"],
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
    "FIELD_NAME_VALUE" => (strlen($_POST["name"]) >= 5 && strlen($_POST["name"]) <= 125) ? $_POST["name"] : ($_POST["name"] == '' ? "Round Robin" : substr($_POST["name"], 0, 125) ),
    "FIELD_TPM_NAME"   => "tpm",
    "FIELD_TPM_VALUE"  => $_POST["tpm"] > 2 && $_POST["tpm"] <= $tourny->get("maxteamspermatch") ? $_POST["tpm"] : 2,
    "FIELD_MPM_NAME"   => "mpm",
    "FIELD_MPM_VALUE"  => $_POST["mpm"] > 1 && $_POST["mpm"] < 1000 ? $_POST["mpm"] : 1,

    "FIELD_DIV_NAME"  => "divisions",
    "FIELD_DIV_VALUE" => $_POST["divisions"] > 0 && $_POST["divisions"] < 1000 ? $_POST["divisions"] : 3,
    "FIELD_QUAL_NAME"  => "qualperdiv",
    "FIELD_QUAL_VALUE" => $_POST["qualperdiv"] >= 0 && $_POST["qualperdiv"] < 1000 && $_POST["qualperdiv"] != '' ? $_POST["qualperdiv"] : 3,

    "FIELD_WIN_NAME"   => "point_win",
    "FIELD_WIN_VALUE"  => $_POST["point_win"] > -1000 && $_POST["point_win"] < 1000 ? $_POST["point_win"] : 100,
    "FIELD_LOSE_NAME"  => "point_lose",
    "FIELD_LOSE_VALUE" => $_POST["point_lose"] > -1000 && $_POST["point_lose"] < 1000 ? $_POST["point_lose"] : 0,
    "FIELD_TIE_NAME"   => "point_tie",
    "FIELD_TIE_VALUE"  => $_POST["point_tie"] > -1000 && $_POST["point_tie"] < 1000 ? $_POST["point_tie"] : 50,
    "FIELD_FORFEIT_NAME"   => "point_forfeit",
    "FIELD_FORFEIT_VALUE"  => $_POST["point_forfeit"] > -1000 && $_POST["point_forfeit"] < 1000 ? $_POST["point_forfeit"] : -50,

    "FIELD_POINTS"       => "point_type",
    "FIELD_POINTS_MAP"   => "map",
    "FIELD_POINTS_MATCH" => "match",

    "FIELD_SUBMIT_NAME" => "submit"
   ));

  $apanel->set_cnt("SETUP", 1);
 }

?>