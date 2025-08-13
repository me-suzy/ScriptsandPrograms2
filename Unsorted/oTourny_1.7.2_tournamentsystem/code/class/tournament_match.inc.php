<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  Tournament Match Class

 */



 //MATCH status List

 $GLOBALS["MATCH_STATUS"]["Winner"]    = 4;

 $GLOBALS["MATCH_STATUS"]["Loser"]     = 3;

 $GLOBALS["MATCH_STATUS"]["Forfeit"]   = 2;

 $GLOBALS["MATCH_STATUS"]["Tie"]       = 1;

 $GLOBALS["MATCH_STATUS"]["Undecided"] = 0;



 //special bounds check for status

 $GLOBALS["MATCH_STATUS"]["HBOUND"] = 4;

 $GLOBALS["MATCH_STATUS"]["LBOUND"] = 0;



 /*

  is reference to match for results of a match

  reserved use: within tourny match class for routing

 */

 class db_tourny_match_result_pos {

  var $match  = 0; //match id

  var $team   = 0; //team position



  var $or     = 0; //override to put in only one of the $or positions



  function db_tourny_match_result_pos($match, $team, $or = FALSE){

   $this->match = $match;

   $this->team  = $team;

   $this->or    = $or;

  }

 }



 class db_tourny_match {

  var $id       = 0;  //match id

  var $table    = ''; //table name

  var $data;          //data

  var $data_mod;      //data modified

  var $module;        //owner module reference



  var $routes   = array(); //routes array

  var $maps;               //map array

  var $sides;              //side array

  var $tpm;                //teams per map

  var $mpm;                //maps per match

  var $team_stats;         //Number of Wins/Loses/Ties for each team



  var $route_types = array( //list of possible match outcomes for each team

    "Winner"    => 0,

    "Loser"     => 1,

    "Tie"       => 2,

    "Forfeit"   => 3,

    "Cancelled" => 4

   );



  function db_tourny_match($table, $matchid, $data = FALSE){

   $this->id       = $matchid;

   $this->table    = $table;



   if(!$this->id > 0) return; //not valid



   if($data === FALSE){ //normal db querys

    $query = new db_cmd("select", $table, "*", "id='".$this->id."'", 1);

    $this->data = & $query->query->db_data[0]; //reference all match info

   }else //override db query

    $this->data = $data;



   $this->id = $this->get("id");

  }



  function get($name){

   return $this->data[$name];

  }



  function set($name, $value = '', $filter = false){

   if(is_array($name)) //loop any arrays

    foreach($name as $key => $data)

     $this->set($key, $data, $filter);

   else{

    if($filter){//control the input

     if(strlen($val) > 250) $val = substr($val, 0, 250);



     //remove annoying new lines

     $val = str_replace(array("\n", "<br>", "\r"), '', $val);

    }



    if($name != '') $this->data_mod[$name] = $this->data[$name] = $value;

   }

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Info Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  function tpm(){

   if($this->tpm > 0) return $this->tpm;



   $module =& $this->module();



   $this->tpm = $module->tpm();



   unset($module);



   return $this->tpm;

  }



  function mpm(){

   if($this->mpm > 0) return $this->mpm;



   $module =& $this->module();



   $this->mpm = $module->get("mapspermatch");



   unset($module);



   return $this->mpm;

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Module Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //refernce owner module

  function &module(){global $tourny;

   if(isset($module)) return $this->module;

   else return $this->module =& $tourny->module($this->get("moduleid"));

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Routes Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //load all the team routes or just one

  function &routes(){

   if(!empty($this->routes)) return $this->routes;



   //create blank template of the route types

   for($r=0;$r < count($this->route_types);$r++)

    $template[$r] = array();



   //call to reference owner module

   $this->module();



   //run through each team

   for($t=0;$t < $this->module->get("teamspermatch");$t++){

    //grab routes from table

    $team_routes = unserialize($this->get_team($t, "routes"));



    //fill with route types if not set

    if(empty($team_routes))

     $team_routes = $template;



    //add to master array

    $this->routes[$t] = $team_routes;

   }



   return $this->routes;

  }



  //save route array for db update

  function save_routes(){

   //call to reference owner module

   $this->module();



   if(!empty($this->routes)) //dont waste on blanks

    for($t=0;$t < $this->module->get("teamspermatch");$t++) //run through each team

     if(!empty($this->routes[$t])) //dont waste on blanks

      $this->set_team($t, "routes", serialize($this->routes[$t]));

  }



  //set/add match routes for action types

  function set_route($type, $team, $matchs){

   $this->routes(); //make sure its loaded



   if(is_array($matchs)) //run through array

    foreach($matchs as $match) $this->set_route($type, $team, $match);

   else //single match

    if(!$this->get_route($type, $team, $matchs)) //make sure there are no dubs

     $this->routes[$team][$type][] = $matchs;

  }



  //retrieve route array or find if route is in array

  function get_route($type, $team, $match = FALSE){

   $this->routes(); //make sure its loaded



   if($match){ //check if match is already declared in array

    if(is_array($this->routes[$team][$type]) && !empty($this->routes[$team][$type]))

     //return in_array($match, $this->routes[$team][$type]);

     for($i=0;$i < count($this->routes[$team][$type]);$i++)

      if($this->routes[$team][$type][$i] == $match) return TRUE;

    //default false

    return FALSE;

   }



   return $this->routes[$team][$type];

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Teams Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //special set function to set items for each

  function set_team($team, $item, $val = '', $filter = false){

   if(is_array($item)){ //save array values

    foreach($item as $key => $val)

     $this->set_team($team, $key, $val);



    return;

   }



   if($filter){//control the input

    if(strlen($val) > 250) $val = substr($val, 0, 250);



    //remove annoying new lines

    $val = str_replace(array("\n", "<br>", "\r"), '', $val);

   }



   if($item == 'id' || $item == '') //override for id

    $this->set("team_".$team, $val);

   else //save all sub items

    $this->set("team_".$team."_".$item, $val);

  }



  //special get function to get data for a team

  function get_team($team, $item = ''){

   if($item == '' || $item == "id") return $this->get("team_".$team);

   else return $this->get("team_".$team."_".$item);

  }



  //grab array of all the teams

  function teams(){

   //grab tpm

   $this->tpm();



   for($i=0;$i < $this->tpm;$i++)

    if($this->get_team($i) > 0) $teams[] = $this->get_team($i);



   return $teams;

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Maps Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  //get map array

  function maps(){

   if(!empty($this->maps)) return $this->maps;



   $this->maps = unserialize($this->get("maps"));



   //create map array if empty

   if(empty($this->maps) && !is_array($this->maps)){

    $this->mpm();



    for($i=0;$i < $this->mpm;$i++)

     $this->maps[$i] = '';

   }



   return $this->maps;

  }



  //save map array

  function save_maps(){

   $this->set("maps", serialize($this->maps()));

  }



  function get_map($id){

   $this->maps(); //make sure maps are loaded

   $this->mpm();



   if($id >= 0 && $id < $this->mpm) //make sure its valid

    return $this->maps[$id];

  }



  function set_map($id, $val = FALSE, $filter = FALSE){

   if(is_array($id)){ //handle any arrays

    foreach($id as $key => $val)

     $this->set_map($key, $val);



    return;

   }



   $this->maps(); //make sure maps are loaded

   $this->mpm();



   if($filter){//control the input

    if(strlen($val) > 250) $val = substr($val, 0, 250);



    //remove annoying new lines

    $val = str_replace(array("\n", "<br>", "\r"), '', $val);

   }



   if($id >= 0 && $id < $this->mpm) //make sure its valid

    $this->maps[$id] = $val;

  }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Score Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  //get score array

  function scores(){

   if(!empty($this->scores)) return $this->scores;



   $this->tpm();

   $this->mpm();



   //grab all the scores

   for($t=0;$t < $this->tpm;$t++)

    $this->scores[$t] = unserialize($this->get_team($t, "score"));



   //create map array if empty

   if(empty($this->scores) && !is_array($this->scores))

    for($t=0;$t < $this->tpm;$t++)

     for($m=0;$m < $this->mpm;$m++)

      $this->scores[$t][$m] = 0;



   return $this->scores;

  }



  //save score array

  function save_scores(){

   $this->tpm();



   for($t=0;$t < $this->tpm;$t++) //save each score score

    $this->set_team($t, "score", serialize($this->scores[$t]));

  }



  function get_score($id, $map){

   $this->scores(); //make sure scores are loaded



   $this->tpm();

   $this->mpm();



   if($id >= 0 && $id < $this->tpm) //make sure its valid

    if($map >= 0 && $map < $this->mpm) //valid map

     return (INT) $this->scores[$id][$map];

  }



  function set_score($id, $map, $val){

   $this->scores(); //make sure scores are loaded



   $this->tpm();

   $this->mpm();



   if($id >= 0 && $id < $this->tpm) //valid team

    if($map >= 0 && $map < $this->mpm) //assign single by map

     $this->scores[$id][$map] = (INT) $val;

  }



  //Retrieves Win/Loss Summary for specified team

  function get_score_summary($id = FALSE){

   if(is_array($this->team_stats)){

    if($id === FALSE) //Return all

     return $this->team_stats;

    else //just return value for team

     return $this->team_stats[$id];

   }



   $this->scores(); //make sure scores are loaded



   $this->tpm();

   $this->mpm();



   $team_stat = array();



   //Run through and find every team's stats

   for($m=0;$m < $this->mpm;$m++){

    for($t=0;$t < $this->tpm;$t++){

     $scores[$t]  = $this->get_score($t, $m);

     $teamlst[$t] = $t;



     if($scores[$t] > 0) //make sure they actually scored

      $valid = true;



     //check for TIEs

     if($t > 0) //dont check nonexistant scores

      if($scores[$t] != $scores[$t - 1])

       $tie = false;

    }



    if($valid){ //someone scored

     //Sort it to Find out who is winner

     array_multisort($scores, SORT_DESC, $teamlst);



     for($t=0;$t < $this->tpm;$t++){

      if($tie) $team_stat[$t]["TIE"]++;

      else{//Winner/Losses

       if($t == 0) //winner

        $team_stat[$teamlst[$t]]["WIN"]++;

       else //loser

        $team_stat[$teamlst[$t]]["LOSE"]++;

    }}}



    //reset valid

    $valid = false;

    //reset as tied

    $tie   = true;

   }



   //save stats

   $this->team_stats = $team_stat;



   //recall self to give them value

   return $this->get_score_summary($id);

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Decided Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //Checks if Match is Decided

  function check_decided(){

   $this->tpm();



   for($t=0;$t < $this->tpm;$t++)

    if((INT) $this->get_team($t, "status") != $GLOBALS["MATCH_STATUS"]["Undecided"])

     return $this->set("decided", 1); //if any arent undecided - its decided



   //All are undecided

   $this->set("decided", 0);

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Eof

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



 }



?>