<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  Team Protocol

 */



 class db_teams extends db_table {

  var $teams; //team array - holds reference to the teams



  function db_teams(){

   //notify parent of db names and class

   parent::db_table("teams", "teamid", "db_team");



   //reference class list

   $this->teams =& $this->objs;

  }



  //retrieve a team

  function &team($id = 0, $create = 0){

   return parent::obj($id, $create);

  }



  //find a team

  function &find_team($name){

   $query = new db_cmd("select", "teams", "teamid", "name LIKE '".$name."'", 1);



   return $this->team($query->data[0]["teamid"]);

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Verification Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //checks a proposed property to team

  function check($type, $item=''){global $tpl, $user;

   $tpl->splice("ERROR", "team_change_err.tpl");



   switch($type){

    case "name":

     //check for name from teams

     $query = new db_cmd("SELECT", "teams", "teamid", "name LIKE '".$item."'", 1);

     if(!empty($query->data[0])) $errchk .= write_error_row($tpl->parse("ERROR->NAME_TEAM_DUB", "ERROR->NAME_TEAM_DUB"));

     unset($query);



     //check for name from user auth - cant have repitition

     $query = new db_cmd("SELECT", "usersauth", "userid", "name LIKE '".$item."'", 1);

     if(!empty($query->data[0])) $errchk .= write_error_row($tpl->parse("ERROR->NAME_AUSER_DUB", "ERROR->NAME_AUSER_DUB"));

     unset($query);



     //check for name from users - cant have repitition

     $query = new db_cmd("SELECT", "users", "userid", "name LIKE '".$item."'", 1);

     if(!empty($query->data[0])) $errchk .= write_error_row($tpl->parse("ERROR->NAME_USER_DUB", "ERROR->NAME_USER_DUB"));

     unset($query);



     //check for min lengths

     if(strlen($item) < 3  || strlen($item) > 50) $errchk .= write_error_row($tpl->parse("ERROR->NAME_BOUNDS", "ERROR->NAME_BOUNDS"));

     break;

    case "tag":

     if(strlen($item) == 0) return ''; //no tag



     //check for tag from teams

     $query = new db_cmd("SELECT", "teams", "teamid", "tag LIKE '".$item."'", 1);

     if(!empty($query->data[0])) $errchk .= write_error_row($tpl->parse("ERROR->TAG_DUB", "ERROR->TAG_DUB"));

     unset($query);



     //check for name from teams

     $query = new db_cmd("SELECT", "teams", "teamid", "name LIKE '".$item."'", 1);

     if(!empty($query->data[0])) $errchk .= write_error_row($tpl->parse("ERROR->TAG_TEAM_DUB", "ERROR->TAG_TEAM_DUB"));

     unset($query);



     //check for name from user auth - cant have repitition

     $query = new db_cmd("SELECT", "usersauth", "userid", "name LIKE '".$item."'", 1);

     if(!empty($query->data[0])) $errchk .= write_error_row($tpl->parse("ERROR->TAG_AUSER_DUB", "ERROR->TAG_AUSER_DUB"));

     unset($query);



     //check for name from users - cant have repitition

     $query = new db_cmd("SELECT", "users", "userid", "name LIKE '".$item."'", 1);

     if(!empty($query->data[0])) $errchk .= write_error_row($tpl->parse("ERROR->TAG_USER_DUB", "ERROR->TAG_USER_DUB"));

     unset($query);



     //check for min lengths

     if(strlen($item) > 7) $errchk .= write_error_row($tpl->parse("ERROR->TAG_MAX", "ERROR->TAG_MAX"));

     break;

    case "email":

     if(!checkemail($item))  $errchk .= write_error_row($tpl->parse("ERROR->EMAIL_INVALID", "ERROR->EMAIL_INVALID"));



     //check email if its already in use, cant use someone elses email

     $query = new db_cmd("SELECT", "users", "userid", "email LIKE '".$item."' && userid != ".$user->id, 1);

     if(!empty($query->data[0])) $errchk .= write_error_row($tpl->parse("ERROR->EMAIL_USER_DUB", "ERROR->EMAIL_USER_DUB"));

     unset($query);



     //check email if its already in use, cant use someone elses email

     $query = new db_cmd("SELECT", "usersauth", "userid", "email LIKE '".$item."'", 1);

     if(!empty($query->data[0])) $errchk .= write_error_row($tpl->parse("ERROR->EMAIL_USER_DUB", "ERROR->EMAIL_USER_DUB"));

     unset($query);



     //check for copy emails

     $query = new db_cmd("SELECT", "teams", "teamid", "email LIKE '".$item."'", 1);

     if(!empty($query->data[0])) $errchk .= write_error_row($tpl->parse("ERROR->EMAIL_TEAM_DUB", "ERROR->EMAIL_TEAM_DUB"));

     unset($query);



     //check for min lengths

     if(strlen($item) < 4 || strlen($item) > 100)  $errchk .= write_error_row($tpl->parse("ERROR->EMAIL_BOUNDS", "ERROR->EMAIL_BOUNDS"));



     break;

    case "pass":

     if(strlen($item) == 0) return ''; //no pass



     //check for min lengths

     if(strlen($item) < 5 || strlen($item) > 50)  $errchk .= write_error_row($tpl->parse("ERROR->PASS_BOUNDS", "ERROR->PASS_BOUNDS"));



     //check for stupid peoples

     if(strtolower($item) == "password") $errchk .= write_error_row($tpl->parse("ERROR->PASS_BAD", "ERROR->PASS_BAD"));



     //check for teams

     $query = new db_cmd("SELECT", "teams", "teamid", "tag LIKE '".$item."' OR name LIKE '".$item."' OR email LIKE '".$item."'", 1);

     if(!empty($query->data[0])) $errchk .= write_error_row($tpl->parse("ERROR->PASS_DUB", "ERROR->PASS_DUB"));

     unset($query);



     //pass can userauths

     $query = new db_cmd("SELECT", "usersauth", "userid", "name LIKE '".$item."' OR email LIKE '".$item."'", 1);

     if(!empty($query->data[0])) $errchk .= write_error_row($tpl->parse("ERROR->PASS_DUB", "ERROR->PASS_DUB"));

     unset($query);



     //check users

     $query = new db_cmd("SELECT", "users", "userid", "name LIKE '".$item."' OR email LIKE '".$item."'", 1);

     if(!empty($query->data[0])) $errchk .= write_error_row($tpl->parse("ERROR->PASS_DUB", "ERROR->PASS_DUB"));

     unset($query);



     break;

    case "web":

     if(strlen($item) == 0) return ''; //none



     //check for min lengths

     if(strlen($item) < 5 || strlen($item) > 100) $errchk .= write_error_row($tpl->parse("ERROR->SITE_BOUND", "ERROR->SITE_BOUND"));



     //check for teams

     $query = new db_cmd("SELECT", "teams", "teamid", "website LIKE '".$item."'", 1);

     if(!empty($query->data[0])) $errchk .= write_error_row($tpl->parse("ERROR->SITE_TEAM_DUB", "ERROR->SITE_TEAM_DUB"));

     unset($query);



     if(strtolower(substr($item, 0, 7)) == "http://")

      $errchk .= write_error_row($tpl->parse("ERROR->SITE_TYPE", "ERROR->SITE_TYPE"));



     break;

    case "ircs"://irc server

     if(strlen($item) == 0) return ''; //none



     //check for min lengths

     if(strlen($item) > 100) $errchk .= write_error_row($tpl->parse("ERROR->IRC_SRV_MAX", "ERROR->IRC_SRV_MAX"));



     break;

    case "ircc"://irc channel

     if(strlen($item) == 0) return ''; //none



     //check for min lengths

     if(strlen($item) > 100) $errchk .= write_error_row($tpl->parse("ERROR->IRC_MAX", "ERROR->IRC_MAX"));



     break;

    case "desc"://team descripotion

     if(strlen($item) == 0) return ''; //none



     //check for min lengths

     if(strlen($item) > 500) $errchk .= write_error_row($tpl->parse("ERROR->DESC_MAX", "ERROR->DESC_MAX"));



     break;

   }



   return $errchk;

  }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

 }



 class db_team extends db_obj {

  var $users;         //array of team's users

  var $ranks;         //array of user ranks in team

  var $tournys;       //array of tournys team is in

  var $alink_profile; //link for team profile



  function db_team ($id, &$data, &$container){

   parent::db_obj($id, &$data, &$container);

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Profile Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //returns link to a team profile

  function get_alink_profile(){global $tpl;

   if(!empty($this->alink_profile)) return $this->alink_profile;



   return $this->alink_profile = $tpl->fetchfile("link_teamprofile.tpl", array(

     "A_LINK_TEAM_HREF" => $this->get_link_profile_loc(),

     "A_LINK_TEAM_NAME" => $this->get("name")

    ));

  }



  //return raw location for team profile

  function get_link_profile_loc(){

   return "?page=profile&type=2&id=".$this->id;

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// User Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //get array of team's users

  function users(){

   if(empty($this->users)) return $this->users = explode("!", $this->get("players"));

   else return $this->users;

  }



  //is user a team member

  function is_user($userid){

   if(empty($this->users)) $this->users(); //create user array if not created



   return in_array($userid, $this->users); //return if they are a user - too easy

  }



  //add user to team

  function add_user($userid){//always call with $user->add_team($teamid)

   //check if on team

   if($this->is_user($userid) || !($userid > 0)) return;



   //add user

   $this->users[] = $userid;



   //save users

   $this->set("players", implode('!', $this->users ));



   //set rank as player

   $this->set_rank($userid, $GLOBALS["level_player"]);

  }



  //remove player from team

  function del_user($userid){//always call with $user->del_team($teamid)

   if(!$this->is_user($userid)) return;



   //set user rank

   $this->set_rank($userid, $GLOBALS["level_nplayer"]);



   //del team

   unset($this->users[array_search($userid, $this->users)]);



   //save teams

   $this->set("players", implode('!', $this->users ) );

  }



  //writes team player list

  function player_list(){global $users, $tpl;

   $team_users = $this->users();//copy user array



   //grab ranks

   // - cant cheat using ranks() since some members arent in there

   foreach($team_users as $userid)

    $ranks[] = $this->user_rank($userid);



   //sort by users by rank

   array_multisort($ranks, SORT_DESC, $team_users);



   for($i=0;$i < count($team_users); $i++)

   if($team_users[$i] > 0)

   {

    $user = &$users->user($team_users[$i]);



    $team_lst .= $tpl->fetchfile("team_list_row.tpl", array(

      "CLASS"              => ($i%2) ? "row" : "rowoff",

      "TEAM_MEMBER_NAME"   => $user->get_alink_profile(),

      "TEAM_MEMBER_STATUS" => $this->user_rank_text(0, $ranks[$i])

     ));

   }



   return $tpl->fetchfile("team_list_table.tpl", array(

      "TEAM_ROWS" => $team_lst,

     ));

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// User Rank Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //sets user rank

  function set_rank($userid, $rank = 0){

   if(!($userid > 0) || !$this->is_user($userid)) return; //only set valid users

   if(empty($this->ranks)) $this->ranks(); //make sure rank array is existant



   //find user and replace rank

   if(is_array($this->ranks))

   foreach($this->ranks as $r_userid => $r_rank)

   if($r_userid > 0) //valid id

   if($this->is_user($r_userid)) //team user

   {

    if($r_userid == $userid){

     $r_rank = $rank; //set new rank

     $user_found = 1;

    }



    $ranks[] .= $r_userid . "=" . $r_rank; //recombine user

   }



   //add user if not in array

   if(!$user_found) $ranks[] .= $userid . "=" . $rank;



   $this->set("ranks", implode('!', $ranks)); //save

   $this->ranks(1); //reload ranks

  }



  //gets user rank

  function user_rank($userid){

   if(!($userid > 0)) return $GLOBALS["level_nplayer"];

   if(empty($this->ranks)) $this->ranks(); //make sure rank array is existant



   //if rank is set, return it

   if(isset($this->ranks[$userid])) return $this->ranks[$userid];



   if($this->is_user($userid)) return $GLOBALS["level_player"]; //team member with out a rank

   else return $GLOBALS["level_nplayer"]; //not a player

  }



  //creates and retieves array of user ranks

  function ranks($reload = 0){

   if(!empty($this->ranks) && !$reload) return $this->ranks;



   $split_ranks = explode('!', $this->get("ranks"));

   for($i=0;$i < count($split_ranks); $i++){

    list($userid, $rank) = explode('=', $split_ranks[$i]);



    if($userid > 0 && $userid != '') $this->ranks[$userid] = $rank;

   }



   return $this->ranks;

  }



  //Returns user status in text

  function user_rank_text($user, $rank = ''){global $tpl;

   switch( empty($rank) ? $this->user_rank( (is_object($user)?$user->id:$user) ) : $rank ){ //smart chk for quick cmds

    case $GLOBALS["level_founder"]://founder

     return $tpl->fetchfile("team_rank_founder.tpl");

     break;

    case $GLOBALS["level_captain"]://capt

     return $tpl->fetchfile("team_rank_captain.tpl");

     break;

    case $GLOBALS["level_player"]://user

     return $tpl->fetchfile("team_rank_player.tpl");

     break;

    case $GLOBALS["level_sub"]://sub

     return $tpl->fetchfile("team_rank_sub.tpl");

    break;

    default:

     return "Check-".(empty($rank) ? $this->user_rank( (is_object($user)?$user->id:$user) ) : $rank );

   }

  }



  //set the founder

  function set_founder($userid){

   $this->set_rank($userid, $GLOBALS["level_founder"]);

   $this->set_rank($this->get("leader"), $GLOBALS["level_captain"]);



   $this->set(array(

     "leader"     => $userid,

     "lastleader" => $this->get("leader")

    ));

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Tourny Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //get array of user tournys

  function tournys(){

   if(empty($this->tournys)) return $this->tournys = remove_nulls(explode("!", $this->get("tournaments")));

   else return $this->tournys;

  }



  //check if team is in tourny

  function is_tourny($tournyid){

   return in_array($tournyid, $this->tournys());

  }



  //check if team is in tourny

  //alias for is_tourny($tournyid)

  function on_tourny($tournyid){

   return $this->is_tourny($tournyid);

  }



  //add tourny to team

  function add_tourny($tournyid){//call $tourny->add_team($teamid) - not this

   //check if on tourny

   if($this->on_tourny($tournyid) || !($tournyid > 0)) return;



   //add tourny

   $this->tournys[] = $tournyid;



   //save tournys

   $this->set("tournaments", implode('!', $this->tournys ));

  }



  //remove team from tourny

  function del_tourny($tournyid){//call $tourny->del_team($teamid) - not this

   if(!$this->on_tourny($tournyid)) return;



   //del team

   unset($this->tournys[array_search($tournyid, $this->tournys)]);



   //save teams

   $this->set("tournaments", implode('!', $this->tournys ) );

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

 }



?>