<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  Tournament Module Class

 */



 //Global declartions of module class types

 $GLOBALS["tourny_module"][1]["class"] = "module_elim_bracket_single";

  $GLOBALS["tourny_module"][1]["name"]  = "Single Elimination";

  $GLOBALS["tourny_module"][1]["func"]["presetup"]  = "write_presetup_module_single_elim";

  require_once('./code/class/modules/elimination/bracket_single.inc.php');



 $GLOBALS["tourny_module"][2]["class"] = "module_elim_bracket_double";

  $GLOBALS["tourny_module"][2]["name"]  = "Double Elimination";

  $GLOBALS["tourny_module"][2]["func"]["presetup"]  = "write_presetup_module_double_elim";

  require_once('./code/class/modules/elimination/bracket_double.inc.php');



 $GLOBALS["tourny_module"][3]["class"] = "module_qualifing";

  $GLOBALS["tourny_module"][3]["name"]  = "Qualifing";

  $GLOBALS["tourny_module"][3]["func"]["presetup"]  = "write_presetup_module_qualifing";

  require_once('./code/class/modules/qualifing.inc.php');



 $GLOBALS["tourny_module"][4]["class"] = "module_robin";

  $GLOBALS["tourny_module"][4]["name"]  = "Round Robin";

  $GLOBALS["tourny_module"][4]["func"]["presetup"]  = "write_presetup_module_robin";

  require_once('./code/class/modules/round_robin.inc.php');



 class db_tourny_module {

  var $id       = 0;  //module id

  var $data     = array(); //data



  var $matchs;  //array of all match ids

  var $config;  //array of config

  var $teams;   //array of teams

  var $teams_qualifing; //array of quaifing teams



  function db_tourny_module($id){

   $this->id = $id;



   if(!($this->id > 0)) return; //not valid



   $query = new db_cmd("select", "tournaments_module", "*", "id='".$this->id."'", 1);

   $this->data = & $query->query->db_data[0]; //reference all user info



   $this->id  = $this->get("id");

  }



  function set($name = '', $value = ''){

   if(is_array($name))

    foreach($name as $key => $data)

     $this->data_mod[$key] = $this->data[$key] = $data;

   else if($name != '')

    $this->data_mod[$name] = $this->data[$name] = $value;

  }



  function get($name){

   return $this->data[$name];

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Virtual Functions

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



 function write_match_status(&$match, $edit){

  /*   virtual

   Fills/Creates section in Match Edit Page

   This section will show user Status of Match (Score/Points)

  */

 }



 function validate_match_status(&$match, $status, $score, $defeat = false){

  /*   virtual

   Checks that all the scores entered are Valid and Saves Match Status

  */

 }



 function convert_route_type($route_types, $type){

  /*   virtual

   converts status to route type

    route codes are diff than whats shown to pub

  */

 }



 function show(){

  /*   virtual

   Shows public form of Module

  */

 }



 function write_setup(){

  /*   virtual

   setup page in admin console for module

  */

 }



 function create_module_teams(){

  /*   virtual - optional

   Called when all rounds have been completed

    Module will need to make a list of teams

    These teams will be used by other modules.

  */

 }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Quick Info Functions

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  function tpm(){

   return (INT) $this->get("teamspermatch");

  }



  function type(){

   return (INT) $this->get("type");

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Match Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //grab array of all matchs

   //$load - load data from sql?

   //$ref  - make reference array?

   //$queryid - query for ids of round or just use already loaded ones and search them

  function &matchs($round = -1, $load = TRUE, $ref = FALSE, $queryid = FALSE){global $tourny;

   if(isset($this->matchs[$round][$ref])) return $this->matchs[$round][$ref]; //no dubs



   $matchs = $tourny->find_matchs($this->id, $round, $load, $queryid);



   if(!empty($matchs) && is_array($matchs))

    if(is_array($matchs))

    foreach($matchs as $matchid)

     if($matchid > 0){

      $this->matchs[$round][$ref][$matchid]         =& $tourny->match($matchid);

      if($ref) $this->matchs[$round][$ref]["ref"][] =& $this->matchs[$round][$ref][$matchid];

     }



   return $this->matchs[$round][$ref];

  }



  //Undo moveing teams to next match

  function move_back_match(&$match){global $tourny;

   if(!is_object($match)) $match =& $tourny->match($match);



   //all team routes

   $routes = $match->routes();



   //teams per match

   $tpm = $this->tpm();



   for($t=0;$t < $tpm;$t++){ //run through each team

    //grab team route

    $route = $routes[$t];

    //grab correct route type

    $match->get_team($t, "status");

    //grab correct route

    $mroute = $route[$this->convert_route_type($match->route_types, $match->get_team($t, "status"))];



    if(is_array($mroute)) //valid route

     foreach($mroute as $routeobj) //run through each

      if(is_object($routeobj)) //should be valid

       if($routeobj->match > 0){ //valid next match

        $nmatch =& $tourny->match($routeobj->match);



        if($nmatch->id > 0){ //valid next match

         if($nmatch->get_team($routeobj->team) == $match->get_team($t)) //check if correct team is set

          $nmatch->set_team($routeobj->team, "id", 0);

       }}

   }

  }



  //Move to Teams to Next Round

  function move_next_match(&$match){global $tourny;

   if(!is_object($match)) $match =& $tourny->match($match);



   //all team routes

   $routes = $match->routes();



   //teams per match

   $tpm = $this->tpm();



   for($t=0;$t < $tpm;$t++){ //run through each team

    //grab team route

    $route = $routes[$t];

    //grab correct route type

    $match->get_team($t, "status");

    //grab correct route

    $mroute = $route[$this->convert_route_type($match->route_types, $match->get_team($t, "status"))];

    //default or used

    $or_used = false;



    if(is_array($mroute)) //valid route

     foreach($mroute as $routeobj) //run through each

      if(is_object($routeobj)) //should be valid

       if($routeobj->match > 0){ //valid next match

        $nmatch =& $tourny->match($routeobj->match);



        if($nmatch->id > 0){ //valid next match

         if($routeobj->or){

          if(!($nmatch->get_team($routeobj->team) > 0) && !$or_used){ //dont replace

           $nmatch->set_team($routeobj->team, "id", $match->get_team($t));

           $or_used = true;

          }

         }else //save to new position

          $nmatch->set_team($routeobj->team, "id", $match->get_team($t));

       }}

   }

  }



  //Deletes all matchs assigned to module

  function del_matchs(){global $tourny;

   //Grab Match List

   $oldmatchs = $tourny->find_matchs($this->id, -1, TRUE);



   if(is_array($oldmatchs) && !empty($oldmatchs) ) //valid match list

    foreach($oldmatchs as $matchid)

     if($matchid > 0)

      $tourny->delete_match($matchid);



   unset($oldmatchs);

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Round Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //Checks to determine if all matchs in current round have finished

   //if they are, it will up round and assign all servers

  function check_round(){

   $round = $this->get("round");



   //grab matchs

   $matchs =& $this->matchs($round, false, true, false);



   //grab ref array

   $matchs =& $matchs["ref"];

   $matchs_hbound = count($matchs);



   $same = false; //if all decided



   for($i=0;$i < $matchs_hbound;$i++)

    //check for any nondecided matchs

    if(!(BOOL) $matchs[$i]->get("decided")) $same = true;



   if(!$same && $matchs_hbound > 0){ //next round

    $this->set("round", $round + 1);

    $this->assign_servers_round($round + 1);



    //check if all rounds are complete

    if($this->get("round") > $this->get("rounds"))

     $this->create_module_teams();

   }

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Server Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //Assigns Servers to a whole round

   //$searchmatchs - use currently declared matchs in tourny obj

  function assign_servers_round($round, $searchmatchs = false){global $tourny, $servers;

   //grab tourny servers

   $tservers = $tourny->servers();



   $tservers_hbound = count($tservers);

   $s = 0; //server index



   //grab matchs

   $matchs =& $this->matchs($round, false, true, $searchmatchs);



   //grab ref array

   $matchs =& $matchs["ref"];

   $matchs_hbound = count($matchs);



   for($i=0;$i < $matchs_hbound ;$i++)

    if(!$matchs[$i]->get("server") > 0) //make sure not to overwrite

     if(!(BOOL) $matchs[$i]->get("decided")){ //dont curropt set matchs

      if($s == $tservers_hbound) $s = 0; //cycle through servers



      //assign server

      $matchs[$i]->set("server", $tservers[$s]);



      $s++;

     }

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Setup/Config Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //retrieves array of configured variables

  function config(){

   if(is_array($this->config)) return $this->config;



   $config = $this->get("config");



   //default blank array

   if($config == '') return array();



   $this->config = unserialize($config);



   //check that it worked

   if($this->config === FALSE) return array(); //return default array

  }



  //saves config array

  function save_config(){

   if(!is_array($this->config)) return; //cant save nulls



   $this->set("config", serialize($this->config));

  }



  //sets item in config array

  function set_config($item, $value = ''){

   $this->config(); //make sure its loaded



   //recursivly save array

   if(is_array($item)){

    if(!empty($item)) //dont waste time on nulls

     foreach($item as $i => $v)

      $this->set_config($i, $v);



    return; //dont save an array

   }



   if($item == '') return; //cant save to nothing



   $this->config[$item] = $value;

  }



  //retrieves item from config array

  function get_config($item){

   $this->config(); //make sure its loaded



   if($item == '') return; //cant save to nothing



   return $this->config[$item];

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Teams Code

///  Team lists can be generated by modules for other modules to use

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //retrieves array of teams

  function teams($qualifing = FALSE){

   if($qualifing) return $this->teams_qualifing();



   if(is_array($this->teams)) return $this->teams;



   //grab team list

   $this->teams = unserialize($this->get("teams"));



   //make sure its valid list

   if(!is_array($this->teams))

    $this->teams = array();



   return $this->teams;

  }



  //sets array of teams

  function set_teams($teamlst, $qualifing = FALSE){

   if($qualifing) return $this->set_teams_qualifing($teamlst);



   //has to be an array

   if(!is_array($teamlst)) return;



   //save locally

   $this->teams = $teamlst;



   //save to db

   $this->set("teams", serialize($this->teams));

  }



  //retrieves array of teams that qualified from module

  function teams_qualifing(){

   if(is_array($this->teams_qualifing)) return $this->teams_qualifing;



   //grab team list

   $this->teams = unserialize($this->get("teams_qualifing"));



   //make sure its valid list

   if(!is_array($this->teams_qualifing))

    $this->teams_qualifing = array();



   return $this->teams_qualifing;

  }



  //sets array of teams that qualified from module

  function set_teams_qualifing($teamlst){

   //has to be an array

   if(!is_array($teamlst)) return;



   //save locally

   $this->teams = $teamlst;



   //save to db

   $this->set("teams", serialize($this->teams));

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// EOF

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

 }

?>