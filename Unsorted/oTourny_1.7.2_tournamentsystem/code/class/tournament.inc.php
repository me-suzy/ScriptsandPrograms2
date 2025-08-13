<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  Tournament Protocol

 */



 //global declartions of stage #

 $GLOBALS["tourny_stage_setup"]        = 1; //setup stage

 $GLOBALS["tourny_stage_signup_open"]  = 2; //players/teams can join/part

 $GLOBALS["tourny_stage_signup_close"] = 3; //players/teams can join/part

 $GLOBALS["tourny_stage_active"]       = 4; //tourny started, brackets can be viewed

 $GLOBALS["tourny_stage_end"]          = 5; //tourny end, prize give-away



 //global declartions of types

 $GLOBALS["tourny_type_single"] = 1; //Single Player

 $GLOBALS["tourny_type_team"]   = 2; //Single Player



 class db_tournys extends db_table {

  var $tournys; //tourny array - holds reference to tourny



  function db_tournys(){

   //notify parent of db names and class

   parent::db_table("tournaments", "tournamentid", "db_tourny");



   //reference class list

   $this->tournys =& $this->objs;

  }



  //retrieve a tourny

  function &tourny($id = 0, $create = 0){

   return parent::obj($id, $create);

  }



  //save all changes to db

  function update_db(){

   if(!empty($this->tournys))

    foreach($this->tournys as $tourny)

     if($tourny->id > 0){

      $tourny->update_db_modules(); //update brackets

      $tourny->update_db_matchs();   //update matchs

      $tourny->save_draft_capt_profiles(); //save all capt team profiles



      if(!empty($tourny->data_mod))

       new db_cmd("UPDATE", "tournaments", $tourny->data_mod, "tournamentid='".$tourny->id."'", 1);

     }

  }

 }



 class db_tourny extends db_obj {

  var $type_name; //type name

  var $servers;   //server array

  var $list_servers; //server list

  var $admins;    //admin array

  var $teams;     //team array

  var $list_teams; //team list



  function db_tourny($id, &$data, &$container){

   parent::db_obj($id, &$data, &$container);

  }



  function delete(){global $users, $images, $teams;

   //remove banner

   $image = $images->image($this->get("banner"));

   if($image->id > 0) $image->delete();

   unset($image);



   //remove servers

   foreach($this->servers() as $serverid)

    if($serverid > 0) $this->del_server($serverid);



   //remove all the draft links

   if($this->get("draft")){

    //remove all draft captains

    if(is_array($this->draft_capts()))

    foreach($this->draft_capts() as $captid) if($captid > 0){ //possibly valid id

     $capt =& $users->user($captid);



     if($capt->id > 0){ //valid id

      //remove player from draft team

      $capt->del_team($capt->get_draft_team($this->id));

      //remove draft tourny link

      $capt->del_draft_team($this->id);

      //remove status of being in draft

      $capt->del_draft_tourny($this->id);

      //remove from tourny

      $capt->del_tourny($this->id);

    }}

    //remove all draft users

    if(is_array($this->draft_users()))

    foreach($this->draft_users() as $draftid) if($draftid > 0){ //possibly valid id

     $draft =& $users->user($draftid);



     if($draft->id > 0){ //valid id

      //remove player from draft team

      $draft->del_team($draft->get_draft_team($this->id));

      //remove draft tourny link

      $draft->del_draft_team($this->id);

      //remove status of being in draft

      $draft->del_draft_tourny($this->id);

      //remove from tourny

      $draft->del_tourny($this->id);

    }}



    //delete all draft teams

    if(is_array($this->draft_teams()))

    foreach($this->draft_teams() as $teamid) if($teamid > 0){//possible valid id

     $team =& $teams->team($teamid);



     if($team->id > 0) //valid team

      $team->delete(); //remove empty team from db

    }

   }



   //remove teams

   if(is_array($this->teams()))

   foreach($this->teams() as $teamid)

    if($teamid > 0) $this->del_team($teamid);



   //remove admins

   if(is_array($this->admins()))

   foreach($this->admins() as $adminid)

    if($adminid > 0) $this->del_admin($adminid);



   //tell founder, they are no longer founder

   $cuser = &$users->user($this->get("creator"));

   $cuser->rem_tourny_founder($tourny->id);



   //clear modules

   foreach($this->modules(true) as $moduleid)

    if($moduleid > 0) $this->del_module($moduleid);



   //drop match table

   $this->drop_table_matchs();



   //call parent to clear table

   parent::delete();

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Founder Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //check if user is founder

  function founder($userid = NULL){global $user, $users;

   //null means current user

   if($userid === NULL) $userid = $user->id;



   //make it just an id

   if(is_object($userid)) $userid = $userid->id;



   return $this->get("creator") == $userid;

  }





/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Type Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //retrives team or player according to tourny type

  function get_type_name(){global $tpl;

   if($this->type_name != '') return $this->type_name;



   if($this->get("type") == 1) return $this->type_name = $tpl->fetchfile("tourny_type_1.tpl");

   else return $this->type_name = $tpl->fetchfile("tourny_type_2.tpl");

  }



  //returns type - 1 (single) or 2 (team)

  function type(){

   return $this->get("type");

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Stage/Status Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //get the current stage

  function stage(){

   return $this->get("status");

  }





  //is signup open?

  function is_signup_open(){

   return $this->stage() == $GLOBALS["tourny_stage_signup_open"];

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Server Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //get array of tourny servers

  function servers(){

   if(!empty($this->servers)) return $this->servers;



   return $this->servers = remove_nulls(explode('!', $this->get("servers")));

  }



  //is server already assigned to tourny

  function is_server($id){

   //grap id

   $id = is_object($id) ? $id->id : $id;



   //make sure server group exists

   $this->servers();



   //check for server

   return in_array($id, $this->servers);

  }



  //add tourny server

  function add_server($server){global $servers;

   if(!is_object($server)) $server = &$servers->server($server);//grap id



   //check if on tourny

   if($this->is_server($server)) return;



   if(!($server->id > 0)) return; //check for valid server



   //add server

   $this->servers[] = $server->id;



   //save servers

   $this->set("servers", implode('!', $this->servers));



   //tell server you are its main tourny

    //server is made seperately but linked here

   $server->set("tournyid", $tourny->id);

  }



  //remove tourny server

  function del_server($server){global $servers;

   if(!is_object($server)) $server = &$servers->server($server);//grap id



   //check if on tourny

   if(!$this->is_server($server)) return;



   if(!($server->id > 0)) return; //check for valid server



   //del admin

   unset($this->servers[array_search($server->id, $this->servers)]);



   //save admins

   $this->set("servers", implode('!', $this->servers));



   //delete server, as they are unique to each tourny

   $server->delete(); //del server

  }



  function get_list_servers($link){global $servers, $tpl;

   if($this->list_servers != '') return $this->list_servers;



   $tpl->splice("SRVLST", "tourny_list_server.tpl");



   $i = 0; //declare loop integer



   //clear old for repeated cmds

   $tpl->clear("SRVLST->COL");

   $tpl->clear("SRVLST->ROW");



   //loop through each server

   foreach($this->servers() as $serverid){

    $server = &$servers->server($serverid);



    if($server->id > 0){//valid server

     $tpl->parse("SRVLST->COL", "SRVLST->COL", 1, array(

       "SERVER_LINK" => $link . $server->id,

       "SERVER_NAME" => $server->get('name')

      ));



    if(++$i % 3 == 0){//every 3rd parse row

     $tpl->parse("SRVLST->ROW", "SRVLST->ROW", 1);



     $tpl->clear("SRVLST->COL");

   }}}

   if($i % 3 != 0) $tpl->parse("SRVLST->ROW", "SRVLST->ROW", 1);



   if($tpl->fetch("SRVLST->ROW") == '') $tpl->assign("SRVLST->ROW", '');



   return $tpl->parse("SRVLST", "SRVLST");

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Admin Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //get array of tourny admins

  function admins(){

   if(!empty($this->admins)) return $this->admins;



   return $this->admins = remove_nulls(explode('!', $this->get("admins")));

  }



  //check if admin is tourny admin

  function is_admin($id){

   //grap id

   $id = is_object($id) ? $id->id : $id;

   //make sure admin group exists

   $this->admins();

   //check for admin

   return in_array($id, $this->admins);

  }



  //add admin to tourny

  function add_admin($admin){global $users;

   if(!is_object($admin)) $admin = &$users->user($admin);//grap id



   //check if on tourny

   if($this->is_admin($admin)) return;



   if(!($admin->id > 0)) return; //check for valid admin



   //add admin

   $this->admins[] = $admin->id;



   //save admins

   $this->set("admins", implode('!', $this->admins));



   //tell user to add tourny as admin

   $admin->add_tourny_admin($this->id);

  }



  //remove admin from tourny

  function del_admin($admin){global $users;

   if(!is_object($admin)) $admin = &$users->user($admin);//grap id



   //check if on tourny

   if(!$this->is_admin($admin)) return;



   if(!($admin->id > 0)) return; //check for valid admin



   //del admin

   unset($this->admins[array_search($admin->id, $this->admins)]);



   //save admins

   $this->set("admins", implode('!', $this->admins));



   //tell user to del tourny as admin

   $admin->rem_tourny_admin($this->id);

  }



  //retrieves array of servers user is admining

  function get_admin_servers(&$suser){global $users, $servers;

   if(!is_object($suser)) $suser =& $users->user($suser); //grab user obj



   if(!$suser->id > 0) return; //bad user



   //grab server list

   $serverlst = $this->servers();



   if(!is_array($serverlst)) return; //no servers - no matchs



   foreach($serverlst as $serverid){ //grab list of servers admin is admining

    $server =& $servers->server($serverid);



    if($server->is_admin($suser->id)) $asrvs[] = $server->id;

   }



   return $asrvs;

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Teams Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //get array of tourny teams

   //random - force a random array

  function teams($random = FALSE){

   if(!empty($this->teams) && !$random) return $this->teams;



   //grab array

   if(empty($this->teams)) $this->teams = remove_nulls(explode('!', $this->get("teams")));



   if($random){

    $team_hbound = count($this->teams);



    //remove any nulls

    for($i=0;$i < $team_hbound;$i++)

     if(!$this->teams[$i] > 0) unset($this->teams[$i]);



    //fix all keys

    sort($this->teams);



    $team_hbound = count($this->teams) - 1;

    $rteams = array();



    //run through each member of the team

    for($i=0;$i <= $team_hbound;$i++){

     $team = $this->teams[rand(0, $team_hbound)];

     //add to rteams if not already in there

     if(!in_array($team, $rteams)) $rteams[] = $team;

     else $i--; //dont skip

    }



    return $rteams;

   }



   return $this->teams;

  }



  //return count of teams

  function team_count(){

   return count($this->teams());

  }



  //check if team is in tourny

  function is_team($teamid){

   return in_array($teamid, $this->teams());

  }



  //Check if team can join

   //only call when team is joining, not join via admin

  function add_team_valid($teamid, $draft = false){global $teams;

   //Signup Open?

   if(!$this->is_signup_open()) return false;



   //In tourny already?

   if($draft){//draft join

    if($this->is_draft_user($teamid)) return false;

    if($this->is_draft_capt($teamid)) return false;

   }else //non draft

    if($this->is_team($teamid)) return false;



   //Special Check for team count min/max

   if($this->get("type") == $GLOBALS["tourny_type_team"]){

    $iteam =& $teams->team($teamid); //grab team obj



    if($iteam->id > 0){ //valid team

     $c = count($iteam->users()); //grab count of their users



     //grab tourny values

     $max = (INT) $this->get("playermin");

     $min = (INT) $this->get("playermin");



     //check if they set max

     if($max == 0) $max = 99999; //some insane number



     //check player counts

     if($c > $this->get("playermin") && $this->get("playermin") < $c)

      return true; //valid player count

     else return false; //invalid player count

    } else return false; //invalid team

   }

   return true; //valid

  }



  //add team to tourny

  function add_team($teamid){global $teams, $users;

   //check if on tourny

   if($this->is_team($teamid)) return;



   //grab the correct object

   if($this->get("type") == 1) $team = &$users->user($teamid);

   else $team = &$teams->team($teamid);



   if(!($team->id > 0)) return; //check for valid team



   //add team

   $this->teams[] = $team->id;



   //save teams

   $this->set("teams", implode('!', $this->teams ));



   //join them up

   $team->add_tourny($this->id);

  }



  //Check if team can leave

   //only call when team is joining, not join via admin

  function del_team_valid($teamid, $draft = false){

   //Signup Open?

   if(!$this->is_signup_open()) return false;



   //In tourny already?

   if($draft){//draft join

    if(!$this->is_draft_user($teamid)) return true;

    if(!$this->is_draft_capt($teamid)) return true;

   }else //non draft

    if(!$this->is_team($teamid)) return true;

  }



  //remove team from tourny

  function del_team($teamid){global $teams, $users;

   if(!$this->is_team($teamid)) return;



   //del team

   unset($this->teams[array_search($teamid, $this->teams)]);



   //save teams

   $this->set("teams", implode('!', $this->teams ) );



   //grab the correct object

   if($this->get("type") == 1) $team = &$users->user($teamid);

   else $team = &$teams->team($teamid);



   //join them up

   $team->del_tourny($this->id);

  }



  function get_list_teams($link){global $tpl, $teams, $users;

   if($this->list_teams != '') return $this->list_teams;



   $tpl->splice("TEAMLST", "tourny_list_team.tpl");



   //clear old for repeated cmds

   $tpl->clear("SRVLST->COL");

   $tpl->clear("SRVLST->ROW");



   $i = 0; //declare loop integer



   //run through each team

   foreach($this->teams() as $id){

    if($this->get("type") == 1) $team = &$users->user($id);

    else $team = &$teams->team($id);



    if($team->id > 0){//valid team

     $tpl->parse("TEAMLST->COL", "TEAMLST->COL", 1, array(

       "TEAM_LINK" => $link . $id,

       "TEAM_NAME" => $team->get('name')

      ));



    if(++$i % 3 == 0){//every 3rd parse row

     $tpl->parse("TEAMLST->ROW", "TEAMLST->ROW", 1);



     $tpl->clear("TEAMLST->COL");

   }}}

   if($i % 3 != 0) $tpl->parse("TEAMLST->ROW", "TEAMLST->ROW", 1);



   //check nulls

   if($tpl->fetch("TEAMLST->ROW") == '') $tpl->assign("TEAMLST->ROW", '');



   return $tpl->parse("TEAMLST", "TEAMLST");

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Matches Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //retrives tourny's match table name

  function get_match_table(){

   return "tournament_". $this->id ."_matchs";

  }



  //drop table

  function drop_table_matchs(){

   new db_cmd("drop", $this->get_match_table());

  }



  //create the match table

  function create_table_matchs(){global $querys, $tpl;

   $tpl->splice("SQL", "sql_table_tourny_match.tpl");



   //remove dubs

   $this->drop_table_matchs();



   //determine team count per match

   $teamcount = $this->get("maxteamspermatch");



   //do the max required # of teams

   for($i=0; $i < $teamcount; $i++)

    $tpl->parse("SQL->TEAM", "SQL->TEAM", 1, array("TEAM_ID" => $i));



   $query = &$querys->query($tpl->parse("SQL", "SQL", array(

     "TABLE_NAME" => $this->get_match_table()

    )));

  }



  //save all changes to match table

  function update_db_matchs(){

   if(!empty($this->matchs))

    foreach($this->matchs as $match)

    if($match->id > 0)

     {

      if(!empty($match->routes)) $match->save_routes();

      if(!empty($match->maps))   $match->save_maps();

      if(!empty($match->scores)) $match->save_scores();



      if(!empty($match->data_mod))

       new db_cmd("UPDATE", $this->get_match_table(), $match->data_mod, "id='".$match->id."'", 1);

     }

  }



  //retrieves a match

  function &match($id = 0, $create = FALSE, $data = FALSE){

   if(!empty($this->matchs[$id])) return $this->matchs[$id];



   if($id == 0 && $create) $id = $this->create_match();



   return $this->matchs[$id] = new db_tourny_match($this->get_match_table(), $id, $data);

  }



  //find matchs to a user is admining

  function find_admin_matchs(&$suser){

   $asrvs = $this->get_admin_servers($suser);



   if(!is_array($asrvs)) return; //must be a server admin



   foreach($asrvs as $serverid){ //make array of admin's servers

    if(strlen($where) > 0) $where .= " OR "; //add OR when needed

    $where .= "server = '".$serverid."'";

   }



   //query match ids

   $query = new db_cmd("SELECT", $this->get_match_table(), "id", "(".$where.")", '', "`moduleid` ASC");



   //grab matchs

   if(is_array($query->data))

    foreach($query->data as $data)

     $match[] = $data["id"];



   return $match;

  }



  //find matchs to a team/user

  function find_team_matchs($team){

   //grab player max for all of tourny

   $tpm = $this->get("maxteamspermatch");



   if(is_array($team))

    foreach($team as $teamid)

     for($i=0;$i < $tpm;$i++){ //make array of possible teams

      if(strlen($where) > 0) $where .= " OR "; //add OR when needed

      $where .= "team_".$i." = '".$teamid."'";

     }

   else //one team

    for($i=0;$i < $tpm;$i++){ //make array of possible teams

      if(strlen($where) > 0) $where .= " OR "; //add OR when needed

      $where .= "team_".$i." = '".$team."'";

     }



   //query match ids

   $query = new db_cmd("SELECT", $this->get_match_table(), "id", "(". $where .")", '', "`moduleid` ASC");



   //grab matchs

   if(is_array($query->data))

    foreach($query->data as $data)

     $match[] = $data["id"];



   return $match;

  }



  //finds matchs to a round

   //$moduleid = selct round - blank for all

   //$round = selected round - blank for all

   //$loaddata = load all data at once into local array

   //$searcharray = use only data in local array

   //$orderrounds = order matchs by rounds

  function find_matchs($moduleid = -1, $round = -1, $loaddata = FALSE, $searcharray = FALSE, $orderrounds = FALSE){

   if($searcharray){

    if(is_array($this->matchs))

    foreach($this->matchs as $match)

     if($match->id > 0)//valid match

      if(($match->get("round") == $round || $round == -1) && ($match->get("moduleid") == $moduleid || $moduleid == -1)) //correct round and module

       $ids[] = (INT) $match->id;



    return $ids;

   }else{//query data

    //check to load everything

    if($moduleid == -1){//all modules

     if($round == -1) //all rounds

      $where = ""; //no stipends

     else //select by round

      $where = "round='".$round."'";

    }else{ //specified module

     if($round == -1) //all rounds

      $where = "moduleid='".$moduleid."'";

     else //select by round

      $where = "moduleid='".$moduleid."' AND round='".$round."'";

    }



    //order by round

    if($orderrounds) $order = "`round` ASC";

    else $order = '';



    $items = $loaddata ? "*" : "id";

    $query = new db_cmd("SELECT", $this->get_match_table(), $items, $where, '',$order);



    //make sure its valid

    if(!is_array($query->data)) return '';



    if($loaddata){ //save data into self

     for($i=0;$i < count($query->data);$i++){

      $id = (INT) $query->data[$i]["id"];



      //create match obj with data

      if($id > 0){

       $this->match($id, FALSE, $query->data[$i]);



       //add id to return array

       $ids[] = (INT) $id;

      }

     }



     return $ids;

    } else { //grab ids only

     for($i=0;$i < count($query->data);$i++){

      $id = (INT) $query->data[$i]["id"];



      if($id > 0) //add id to return array

       $ids[] = (INT) $id;

     }



     return $ids;

    }

   }

 }



  //create new match and then query it

  function create_match(){

   //grab next id

   $id = $GLOBALS["querys"]->nextid($this->get_match_table(), "id");



   //create entry

   new db_cmd("insert", $this->get_match_table(), "id = ".$id);



   return $id;

  }



  //delete match

  function delete_match($id){

   $match =& $this->match($id);



   if(!$match->id > 0) return; //invalid match



   //remove from db

   new db_cmd("delete", $this->get_match_table(), "", "id = ".$match->id);



   //remove local reference

   unset($this->matchs[$match->id]);

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Modules Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //retrieves array of modules

   //key - retrieve array of ids

  function modules($key = FALSE){

   if(!empty($this->modules) && !$key) return $this->modules;



   $modules = remove_nulls(explode('!', $this->get("modules")));



   $this->modules = array(); //cast as array



   //fill array

   if(is_array($modules))

   foreach($modules as $moduletxt){

    list($id, $type) = explode('=', $moduletxt);



    if($id > 0 && $type > 0){ //only save valid modules

     if($key){

      $keys[] = $id;

     }else{ //other info

      $this->modules[$id]["type"]  = $type;

      $this->modules[$id]["valid"] = 1; //load module class later

   }}}



   if($key) return $keys;

   else return $this->modules;

  }



  //retrieves a module

  function &module($id = 0, $create = 0, $type = 0){

   if(empty($this->modules)) $this->modules(); //make sure modules are loaded



   //check if it exists

   if($this->modules[$id]["valid"] != 1 && !$create) return ''; //not valid module



   //if it is already iniatied - return it

   if(is_object($this->modules[$id]["obj"])) return $this->modules[$id]["obj"];



   //create it, if need be

   if($create && $type > 0) $id = $this->add_module($type);



   //load the proper class for the module according to type

   return $this->modules[$id]["obj"] = new $GLOBALS["tourny_module"][$this->modules[$id]["type"]]["class"]($id);

  }



  //create module in module table

  function add_module_to_table($type){

   //grab next id

   $id = $GLOBALS["querys"]->nextid("tournaments_module", "id");



   //create entry

   new db_cmd("insert", "tournaments_module", array("tournyid" => $this->id, "type" => $type, "id" => $id));



   return $id;

  }



  //create new module in local array and table

  function add_module($type = 1){

   //create and retrieve id

   $id = $this->add_module_to_table($type);



   //add module

   $this->modules[$id]["valid"] = 1;

   $this->modules[$id]["type"]  = $type;



   //save changes

   $this->save_local_modules();



   return $id;

  }



  //internall function to save modules array

  function save_local_modules(){

   if(empty($this->modules)) $this->set("modules", '');



   //make module txt for saving

   foreach($this->modules as $id => $module)

    if($id > 0 && $module["type"] > 0)

     $modulelst[] = $id . "=" . $module["type"];



   if(empty($modulelst)) $this->set("modules", '');

   else //save teams

    $this->set("modules", implode('!', $modulelst ));

  }



  //remove module from tourny

  function del_module($id){global $tpl;

   $this->modules();



   if($this->modules[$id]["valid"] != 1) return;



   //ref module

   $module =& $this->module($id);



   //clear template

   if($module->get("tpl") != '')

    $tpl->save($module->get("tpl"), '');



   //remove module id - so no one uses it

   $module->id = 0;



   unset($module);



   //remove from table

   new db_cmd("delete", "tournaments_module", "", "id = ".$id);



   //del module

   unset($this->modules[$id]);



   //save changes

   $this->save_local_modules();

  }



  //save all changes to db from modules

  function update_db_modules(){

   if(!empty($this->modules))

    foreach($this->modules as $module)

     if($module["valid"] == 1 && is_object($module["obj"]))

      if(!empty($module["obj"]->data_mod) && $module["obj"]->id > 0){

       if(!empty($module["obj"]->config)) $module["obj"]->save_config();



       new db_cmd("UPDATE", "tournaments_module", $module["obj"]->data_mod, "id='".$module["obj"]->id."'", 1);

      }

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Module Team Selection Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //Called by Modules to refresh to the Module Team Selection Page

   //Link - Return Link when user is done

  function refresh_module_select_teams($link){

   //relink to correct page with their return link

   write_refresh("?page=admin&cmd=tourny&cmdd=module_team&module=".$_GET["module"]."&tournyid=".$this->id."&returnlink=". urlencode($link));

  }



  //Called when user wants to seed a module

   //will list all modules for them to choose which team list

  function write_module_select_teams(){global $tpl;

   //They selected a Module

   if(isset($_GET["selmod"]))

    return $this->write_module_select_teams_modify((INT) $_GET["selmod"], $_GET["returnlink"]);





   $returnlink = "&returnlink=". urlencode($_GET["returnlink"]);

   $link = "?page=admin&cmd=tourny&cmdd=module_team&module=".$_GET["module"]."&tournyid=".$this->id. $returnlink . "&selmod=";



   $tpl->splice("SETUP", "module_select_teams.tpl");



   foreach($this->modules(true) as $moduleid) if($moduleid > 0){

    $module =& $this->module($moduleid);



    if($module->id > 0){ //valid module

     $i++;



     $tpl->parse("SETUP->COL", "SETUP->COL", true, array(

       "NAME" => $module->get("name"),

       "LINK" => $link . $module->id

      ));



     if($i % 3 == 0){ //new row

      $tpl->parse("SETUP->LIST", "SETUP->ROW", true);

      $tpl->clear("SETUP->COL");

   }}}



   //catch any missing rows

   if($i % 3 != 0 && $tpl->fetch("SETUP->COL") != '')

    $tpl->parse("SETUP->LIST", "SETUP->ROW", true);



   //check if there are no modules

   if($tpl->fetch("SETUP->LIST") == '')

    $tpl->parse("SETUP->LIST", "SETUP->NONE");



   $tpl->parse("CONTENT", "SETUP", array(

     "LINK_DEFAULT" => $link . "0"

    ));

  }



  //Private

  //Called when user wants to seed a module

   //will list all modules for them to choose which team list

  function write_module_select_teams_modify($selmod, $returnlink){global $tpl, $teams, $users;

   $tpl->splice("SETUP", "module_select_teams_modify.tpl");



   //Save Teamlist and Ranks

   if(isset($_POST["submit"])){

    $teamlst = array();



    if(is_array($_POST["team_chk"]))

    foreach($_POST["team_chk"] as $teamid => $status)

     if($status == 1){ //They are in tourny

      $teamlst[] = $teamid;

      $ranks[$teamid] = $_POST["team_rank"];

     }



    //sort teams using the ranks

    if(is_array($teamlst) && is_array($ranks))

    array_multisort($ranks, $teamlst);



    //Save Team list

    $module =& $this->module($_GET["module"]);

    $module->set_teams($teamlst);

    unset($module);



    return write_refresh($returnlink);

   }



   //Grab Module's Selected Teams

   $selteams = $this->get_module_teams($selmod);



   if(is_array($this->teams()))

   foreach($this->teams() as $teamid) if($teamid > 0){

    if($this->type() == $GLOBALS["tourny_type_single"])

     $team =& $users->user((INT) $teamid);

    if($this->type() == $GLOBALS["tourny_type_team"])

     $team =& $teams->team((INT) $teamid);



    if($team->id > 0) //valid team

     $key_search = array_search($team->id, $selteams);

     if($key_search !== FALSE && $key_search !== NULL){

      //Real Key found

      $key = $key_search + 1; //start at 1

      $vis = true;

     } else { //not found

      $key = 999;

      $vis = false;

     }



     $tpl->parse("SETUP->LIST", "SETUP->ROW", true, array(

       "CLASS"            => $c++ % 2 ? "row" : "rowoff",

       "FIELD_CHK"        => "team_chk[".$team->id."]",

       "FIELD_CHK_VALUE"  => "1",

       "FIELD_CHK_CHK"    => numtobool($vis),

       "FIELD_RANK"       => "team_rank[".$team->id."]",

       "FIELD_RANK_VALUE" => $key,

       "NAME"             => $team->get("name")

      ));

   }



   //check if there are no modules

   if($tpl->fetch("SETUP->LIST") == '')

    $tpl->parse("SETUP->LIST", "SETUP->NONE");



   $tpl->parse("CONTENT", "SETUP", array(

     "FIELD_SUBMIT" => "submit"

    ));

  }



  //Private

  //retrieves module teams or tourny teams

   // 0 - for tourny teams

  function get_module_teams($moduleid){

   if($moduleid > 0){ //load preselected module

    $module =& $this->module((INT) $moduleid);



    if($module->id > 0) //valid module

     return $module->teams_qualifing();

   }



   //default return tourny teams

   return $this->teams();

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Draft User Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //retrieves array of all draft players

  function draft_users(){

   if(isset($this->draft_users)) return $this->draft_users;



   //grab list

   $this->draft_users = unserialize($this->get("draft_users"));



   //make sure its an array

   if(!is_array($this->draft_users))

    $this->draft_users = array();



   return $this->draft_users;

  }



  //is user a draft tourny player

  function is_draft_user($userid){

   if(is_object($userid)) $userid = $userid->id; //grab user id only



   return in_array($userid, $this->draft_users()); //return if they are a user

  }



  //retrieves draft user obj

  function add_draft_user($user){global $users;

   //need user obj

   if(!is_object($user)) $user =& $users->user($user);



   //check if they are already a draft user

   if($this->is_draft_user($user->id)) return; //already on tourny

   if($this->is_draft_capt($user->id)) return; //already on tourny



   //add user

   $this->draft_users[] = $user->id;



   //notify user of joining

   $user->add_draft_tourny($this->id);



   //save tournys

   $this->set("draft_users", serialize($this->draft_users));

  }



  //remove user from draft

  function del_draft_user($user){global $users;

   //need user obj

   if(!is_object($user)) $user =& $users->user($user);



   //check if they are already a draft user

   if(!$this->is_draft_user($user->id)) return; //dont delete twice



   //del user

   unset($this->draft_users[array_search($user->id, $this->draft_users)]);



   //fix key order

   sort($this->draft_users);



   //notify user of leaving

   $user->del_draft_tourny($this->id);



   //save tournys

   $this->set("draft_users", serialize($this->draft_users));

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Draft Captain Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //retrieves array of all draft captains

  function draft_capts(){

   if(isset($this->draft_capts)) return $this->draft_capts;

   else return $this->draft_capts = unserialize($this->get("draft_capts"));

  }



  //is user a draft tourny captain

  function is_draft_capt($userid){

   if(is_object($userid)) $userid = $userid->id; //grab user id only



   if(is_array($this->draft_capts()))

    return in_array($userid, $this->draft_capts()); //return if they are a captain

  }



  //retrieves draft capt user obj

  function add_draft_capt($user){global $users;

   //need user obj

   if(!is_object($user)) $user =& $users->user($user);



   //check if they are already a draft user

   if($this->is_draft_capt($user->id)) return; //already on tourny

   if($this->is_draft_user($user->id)) return; //already on tourny



   //add user

   $this->draft_capts[] = $user->id;



   //notify user of joining

   $user->add_draft_tourny($this->id);



   //save tournys

   $this->set("draft_capts", serialize($this->draft_capts));

  }



  //remove capt from draft

  function del_draft_capt($user){global $users;

   //need user obj

   if(!is_object($user)) $user =& $users->user($user);



   //check if they are already a draft capt

   if(!$this->is_draft_capt($user->id)) return; //dont delete twice



   //del user

   unset($this->draft_capts[array_search($user->id, $this->draft_capts)]);



   //fix key order

   sort($this->draft_capts);



   //notify user of leaving

   $user->del_draft_tourny($this->id);



   //save tournys

   $this->set("draft_capts", serialize($this->draft_capts));

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Draft Captain's Team Profile Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  /* Profile of Capt's team -- Done before start by captains

   format= array(

     "name"  => $name,

     "tag"   => $tag,

     "side"  => $side,

     "email" => $side,

     "ranks" => array(

       $user->id => $rank,

      )

    )

  */

  function set_draft_capt_profile($captid, $profile){

   $this->draft_capt_profiles(); //make sure profiles are loaded



   //save team profile

   $this->draft_capt_profiles[$captid] = $profile;



   $this->save_draft_capt_profiles();

  }



  //retrieve team profile

  function get_draft_capt_profile($captid){

   $this->draft_capt_profiles(); //make sure profiles are loaded



   //retrieve team profile

   if(!empty($this->draft_capt_profiles[$captid])) return $this->draft_capt_profiles[$captid];

   else return false;

  }



  //retrieves array of all draft capt profiles

  function draft_capt_profiles(){

   if(isset($this->draft_capt_profiles)) return $this->draft_capt_profiles;



   //grab array

   $this->draft_capt_profiles = unserialize($this->get("draft_data"));



   if(!is_array($this->draft_capt_profiles))

    $this->draft_capt_profiles = array();



   return $this->draft_capt_profiles;

  }



  //save all the capt team profiles

  function save_draft_capt_profiles(){

   if(!empty($this->draft_capt_profiles)) //only save if it exists

    $this->set("draft_data", serialize($this->draft_capt_profiles));

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Draft Team Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //retrieves array of all draft teams

  function draft_teams(){

   if(isset($this->draft_teams)) return $this->draft_teams;



   //grab team list

   $this->draft_teams = unserialize($this->get("draft_teams"));



   //make sure its an array

   if(!is_array($this->draft_teams)) $this->draft_teams = array();



   return $this->draft_teams;

  }



  //is user a draft tourny team

  function is_draft_team($teamid){

   if(is_object($teamid)) $teamid = $teamid->id; //grab team id only



   return in_array($teamid, $this->draft_teams()); //return if they are a team

  }



  //adds draft team to draft teams array

  function add_draft_team($team){global $teams;

   //need team obj

   if(!is_object($team)) $team =& $teams->team($team);



   //check if they are already a draft team

   if($this->is_draft_team($team->id)) return; //already on tourny



   //add team

   $this->draft_teams[] = $team->id;



   //save teams

   $this->set("draft_teams", serialize($this->draft_teams));

  }



  //Create a whole team using draft profile

  function create_draft_team($captid, $profile){global $teams, $users;

   $team =& $teams->team(0, 1); //create team

   $capt =& $users->user($captid); //grab capt obj



   //save profile data

   $team->set(array(

     "name"    => $profile["name"],

     "tag"     => $profile["tag"],

     "tagside" => $profile["side"],

     "email"   => $profile["email"],

     "draft"   => true,

     "status"  => 1

    ));



   //give them rank as captain, not founder

   $team->add_user($capt->id);

   $capt->add_team($team->id);

   $capt->set_draft_team($this->id, $team->id);

   $team->set_rank($capt->id, $GLOBALS["level_captain"]);



   //run through and add each player

   foreach($profile["ranks"] as $userid)

    if($userid > 0){ //valid user id

     $duser =& $users->user($userid); //grab user obj



     if($duser->id > 0){//valid user

      $team->add_user($duser->id);       //add player to team

      $duser->add_team($team->id);       //notify user of team

      $duser->set_draft_team($this->id, $team->id); //notify user of draft team

    }}



   //add team to tournament

   $this->add_team($team->id);



   //add team as draft team

   $this->add_draft_team($team->id);

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Draft Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //Draft out all the players to draft teams

  function draft(){global $users;

   //grab all data needed into local arrays

   $profiles = $this->draft_capt_profiles();

   $capts    = $this->draft_capts();

   $players  = $this->draft_users();



   if(count($players) < count($capts)) return; //must have more players than captains

   if(count($capts) < 3) return; //must have atleast 2 teams



   //go through each capt and force out the correct team profile

    //grab array of capts user picks in order

   foreach($capts as $captid) if($captid > 0){ //possibly valid id

    $capt =& $users->user($captid);



    if($capt->id > 0){ //valid id

     if(!is_array($profiles[$capt->id])) //capt didnt do job

      $profiles[$capt->id] = array( //just make their team for them

        "name"  => "Team " . $capt->get("name"),

        "tag"   => "",

        "side"  => false,

        "email" => $capt->get("email"),

       );



     //give a sort order rank to the capt

     $order[$capt->id] = rand(1, 500);



     //grab captain's ranks

     $cranks = $profiles[$captid]["ranks"];

     //default a blank ranks array

     $ranks  = NULL;

     //null the ranks - overwrite later

     $profiles[$captid]["ranks"] = NULL;



     //run throught and force out the proper ranks to each player

     foreach($players as $userid) if($userid > 0){ //probably valid user

      if($cranks[$userid] > 0 && $cranks[$userid] < 11) //make sure its valid rank

       $ranks[$userid] = $cranks[$userid];

      else //default as unwanted

       $ranks[$userid] = 10;

     }



     //grab users from array

     $userlst = array_keys($ranks);



     //sort ranks using the user list

     array_multisort($ranks, $userlst);



     //save array in pick list for assignment

     $picks[$capt->id] = $userlst;



     unset($capt);

   }}



   //grab capts from array

   $captlst = array_keys($order);



   //sort capts using the random order

   array_multisort($order, $captlst);



   //run throught each capt giving them the player they want

   for(;!empty($picks[$captlst[0]]);) //run until players is empty

   foreach($captlst as $captid) if(!empty($picks[$captlst[0]])){ //make sure not run on nulls

    //reset to grab highest entry

    reset($picks[$captid]);



    //grab player id

    $player = current($picks[$captid]);



    if($player > 0){//make sure its a valid player

     //assign to team

     $profiles[$captid]["ranks"][] = $player;



     foreach($captlst as $captidid) //remove player from each capts list too

      unset($picks[$captidid][array_search($player, $picks[$captidid])]);

   }}



   //run through and create each team

   foreach($captlst as $captid)

    $this->create_draft_team($captid, $profiles[$captid]);

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

 }



?>