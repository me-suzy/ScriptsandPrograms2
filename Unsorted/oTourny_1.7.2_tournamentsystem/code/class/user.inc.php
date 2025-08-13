<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  Standerd User System

 */



 //login timeout for lost pass

 $login_timeout = 60*60*60*24*3; //3 days



 //Admin Access Levels

 $level_news       = 1;

 $level_pictures   = 2;

 $level_userauth   = 3;

 $level_email      = 3;

 $level_games      = 2;

 $level_location   = 2;

 $level_server     = 3;

 $level_allservers = 3;

 $level_console    = 1;

 $level_user       = 3;

 $level_team       = 2;

 $level_user_admin = 3;

 $level_tourny     = 2;

 $level_search     = 1;

 $level_easysql    = 3;



 //Team Access Levels

 $level_founder = 4;

 $level_captain = 3;

 $level_player  = 2;

 $level_sub     = 1;

 $level_nplayer = 0; //not player



 class db_users extends db_table {

  var $users; //user array - holds reference to user

  var $user;  //user currently logged in

  var $login_fail; //user login failed



  function db_users(){

   //notify parent of db names and class

   parent::db_table("users", "userid", "db_user");



   //reference class list

   $this->users =& $this->objs;

  }



  //retrieve a user

  function &user($id = 0, $create = 0){

   return parent::obj($id, $create);

  }



  //find user

  function &find_user($name){

   $query = new db_cmd("select", "users", "userid", "name LIKE '".$name."'", 1);



   return $this->user($query->data[0]["userid"]);

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Login Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //login user

  function &login(){

   if(isset($_POST["Username"])){//login by UID/PASS

    $user =& $this->login_user();

    $generate_key = true;

    $report_fail  = true;

    $send_cookies = true;

   }else

   if(isset($_POST["sessionkey"])){//login by generated key

    $user =& $this->login_key();

    $generate_key = false;

    $send_cookies = true;

    $report_fail  = false;

   }else

   if(isset($_COOKIE["sessionkey"])){//login by cookies

    $user =& $this->login_cookie();

    $generate_key = false;

    $send_cookies = false;

    $report_fail  = false;

   }



   if(!$user->id > 0){ //bad login

    $this->logout();



    if($report_fail) //only show failure

     $this->login_fail = 1;



    return false;

   }



   //reference user as logged in user

   $this->user =& $user;



   //check that they are allowed to login

   if($this->user->get("loginlock")){

    $this->logout();

    return false;

   }



   //update last time they logged in

   $this->user->set(array(

     "lastlogin" => time(),

     "ip"        => clientip()

    ));



   if($generate_key) //generate sessionkey

    $this->user->gen_sessionkey();



   if($send_cookies){ //send cookies

    $expire = time()+time();



    setcookie("sessionkey", $this->user->get("sessionkey"), $expire);

    setcookie("user",       $this->user->id               , $expire);

    setcookie("namestamp",  $this->user->get("name")      , $expire);

   }



   return $this->user;

  }



  //login by generated key

  function &login_key(){

   $user = & $this->user((INT) $_POST["user"]);



   //no users found

   if(!$user->id > 0) return false;



   //check their ip //clientip() == $user->get("ip") &&

   if($user->get("sessionkey") == $_POST["sessionkey"])

    return $user; //valid user

   else //invalid timestamp

    return false;

  }



  //login by cookies

  function &login_cookie(){

   $user = & $this->user((INT) $_COOKIE["user"]);



   //no users found

   if(!$user->id > 0) return false;



   //check login key

   if(clientip() == $user->get("ip") && $_COOKIE["sessionkey"] == $user->get("sessionkey"))

    return $user; //valid user

   else //invalid timestamp

    return false;

  }



  //login by UID/PASS

  function &login_user(){

   if(isset($_POST["Username"]) && isset($_POST["User_PW"]))

    $query = new db_cmd("SELECT", "users", "userid", "name LIKE '".htmlchars($_POST["Username"])."' AND password = '" .hash($_POST["User_PW"]). "'");



   //load user

   if(isset($query) && !empty($query->data))

    if($query->data[0]["userid"] > 0)

     $user = & $this->user($query->data[0]["userid"]);



   //no users found

   if(!$user->id > 0) return false;

   else return $user; //valid user

  }



  //log the out

  function logout(){

   if($this->user->id > 0){

    //make a new key - in effect a forceable logging them out

    $this->user->gen_sessionkey();



    //unreference global ref to user

    if(isset($GLOBALS["user"]))

     unset($GLOBALS["user"]);



    //unreference user

    unset($this->user);

   }



   //clear cookies

   setcookie("sessionkey", "");

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

 }



 class db_user extends db_obj {

  var $tagname;  //user name with tag

  var $teams;    //array of teams user is member of

  var $tournys;  //array of tournys user is in

  var $tournys_founder; //array of tournys user founder of

  var $tournys_admin;   //array of tournys user admin of

  var $alink_profile;   //link for user profile



  function db_user($id, &$data, &$container){

   parent::db_obj($id, &$data, &$container);

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// STD Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  //retrieves admin user level

  function level_name(){global $tpl;

   $tpl->splice("USER_LEVEL", "user_lvl_admin.tpl");



   $admin = (INT) $this->get("admin");



   if($admin > -1 && $admin < 4) return $tpl->parse("USER_LEVEL->".$admin, "USER_LEVEL->".$admin);

   if($admin > 3) return $tpl->parse("USER_LEVEL->3", "USER_LEVEL->3");

   if($admin < 0) return $tpl->parse("USER_LEVEL->0", "USER_LEVEL->0");

  }



  //returns link to a team profile

  function get_alink_profile(){global $tpl;

   if(!empty($this->alink_profile)) return $this->alink_profile;



   return $this->alink_profile = $tpl->fetchfile("link_userprofile.tpl", array(

     "A_LINK_USER_HREF" => $this->get_link_profile_loc(),

     "A_LINK_USER_NAME" => $this->get("name")

    ));

  }



  //return raw location for team profile

  function get_link_profile_loc(){

   return "?page=profile&type=1&id=".$this->id;

  }



  //retrieve user name with tag

  function tagname(){global $teams;

   if(!empty($this->tagname)) return $this->tagname;



   $team = &$teams->team($this->get("primaryteam"));

   if($team->id > 0){

    if($team->get("tagside") == 1) //right side

     return $this->get("name") . $team->get("tag");

    else

     return $team->get("tag") . $this->get("name");

   }else

     return $this->get("name");

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Team Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //get array of user teams

  function teams(){

   if(empty($this->teams)) return $this->teams = explode("!", $this->get("teams"));

   else return $this->teams;

  }



  function add_team($teamid){//always call with $team->add_user($userid)

   //check if on team

   if($this->on_team($teamid)) return;



   //add team

   $this->teams[] = $teamid;



   //save teams

   $this->set("teams", implode('!', $this->teams ) );

  }



  //remove team from team list

  function del_team($teamid){//always call with $team->del_user($userid)

   //check if on team

   if(!$this->on_team($teamid)) return;



   //check primary team

   if($this->get("primaryteam") == $teamid) $this->set("primaryteam", 0);



   //del team

   unset($this->teams[array_search($teamid, $this->teams)]);



   //save teams

   $this->set("teams", implode('!', $this->teams ) );

  }



  //is user a team member

  function on_team($teamid){

   return in_array($teamid, $this->teams()); //return if they are a user - too easy

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Draft Tourny Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //get array of user draft tournys

  function draft_tournys(){

   if(isset($this->draft_tournys)) return $this->draft_tournys;



   //grab list

   $this->draft_tournys = unserialize($this->get("draft_tournaments"));



   //make sure its an array

   if(!is_array($this->draft_tournys))

    $this->draft_tournys = array();



   return $this->draft_tournys;

  }



  //is user a draft tourny player

  function on_draft_tourny($tournyid){

   return in_array($tournyid, $this->draft_tournys()); //return if they are a user

  }



  //add draft tourny to player

  function add_draft_tourny($tournyid){//call $tourny->draft_user($this->id, true) - not this

   //check if on tourny

   if($this->on_draft_tourny($tournyid) || !($tournyid > 0)) return;



   //add tourny

   $this->draft_tournys[] = $tournyid;



   //save tournys

   $this->set("draft_tournaments",  serialize($this->draft_tournys));

  }



  //remove player from tourny

  function del_draft_tourny($tournyid){//call $tourny->del_draft_user($userid) - not this

   if(!$this->on_draft_tourny($tournyid)) return;



   //del tourny

   unset($this->draft_tournys[array_search($tournyid, $this->draft_tournys)]);



   //fix key order

   sort($this->draft_tournys);



   //save draft tounrys

   $this->set("draft_tournaments", serialize($this->tournys));

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



  //retrieve draft team for a tourny

  function get_draft_team($tournyid){

   //need ids only

   if(is_object($tournyid)) $tournyid = $tournyid->id;



   $this->draft_teams();



   return $this->draft_teams[$tournyid];

  }



  //sets draft team to draft team

  function set_draft_team($tournyid, $teamid){

   //need ids only

   if(is_object($teamid))   $teamid   = $teamid->id;

   if(is_object($tournyid)) $tournyid = $tournyid->id;



   //add team

   $this->draft_teams[$tournyid] = $teamid;



   //save teams

   $this->set("draft_teams", serialize($this->draft_teams));

  }



  //remove team/tourny from draft

  function del_draft_team($tournyid){

   //need tounry id

   if(is_object($tournyid)) $tournyid = $tournyid->id; //grab tourny id only



   //remove tourny

   unset($this->draft_teams[$tournyid]);



   //save teams

   $this->set("draft_teams", serialize($this->draft_teams));

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Tourny Player Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //get array of user tournys

  function tournys(){

   if(empty($this->tournys)) return $this->tournys = explode("!", $this->get("tournaments"));

   else return $this->tournys;

  }



  //is user a tourny player

  function on_tourny($tournyid){

   return in_array($tournyid, $this->tournys()); //return if they are a user - too easy

  }



   //add tourny to player

  function add_tourny($tournyid){//call $tourny->add_team($teamid) - not this

   //check if on tourny

   if($this->on_tourny($tournyid) || !($tournyid > 0)) return;



   //add tourny

   $this->tournys[] = $tournyid;



   //save tournys

   $this->set("tournaments", implode('!', $this->tournys ));

  }



  //remove team from player

  function del_tourny($tournyid){//call $tourny->del_team($teamid) - not this

   if(!$this->on_tourny($tournyid)) return;



   //del team

   unset($this->tournys[array_search($tournyid, $this->tournys)]);



   //save teams

   $this->set("tournaments", implode('!', $this->tournys ) );

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Tourny Founder Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //get array of user that is tourny founder

  function tournys_founder(){

   if(empty($this->tournys_founder)) return $this->tournys_founder = explode("!", $this->get("tournyfounder"));

   else return $this->tournys_founder;

  }



  //add tourny founder

  function add_tourny_founder($id){

   if(in_array($id, $this->tournys_founder())) return;



   $this->tournys_founder[] = $id;



   $this->set("tournyfounder", implode('!', $this->tournys_founder));

  }



  //remove tourny founder

  function rem_tourny_founder($id){

   if(!in_array($id, $this->tournys_founder())) return;



   unset($this->tournys_founder[array_search($id, $this->tournys_founder)]);



   $this->set("tournyfounder", implode('!', $this->tournys_founder));

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Tourny Admin Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //get array of user that is tourny admin

  function tournys_admin(){

   if(empty($this->tournys_admin)) return $this->tournys_admin = explode("!", $this->get("tournyadmin"));

   else return $this->tournys_admin;

  }



  //add tourny admin -- dont call directly use $tourny->add_admin($admin)

  function add_tourny_admin($id){

   if(in_array($id, $this->tournys_admin())) return;



   $this->tournys_admin[] = $id;



   $this->set("tournyadmin", implode('!', $this->tournys_admin));

  }



  //remove tourny admin -- dont call directly use $tourny->del_admin($admin)

  function rem_tourny_admin($id){

   if(!in_array($id, $this->tournys_admin())) return;



   unset($this->tournys_admin[array_search($id, $this->tournys_admin)]);



   $this->set("tournyadmin", implode('!', $this->tournys_admin));

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Login Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //generate session key

  function gen_sessionkey(){

   $this->set("sessionkey", gen_rand_str(25));

   return $this->get("sessionkey");

  }



  //generate login key for lost pass

  function gen_login_lpkey($clear = false){

   if($clear){

    $this->set("login_key",  "0");

    $this->set("login_time", "0");



    return;

   }



   $this->set("login_key",  gen_rand_str(50));

   $this->set("login_time", time());



   return $this->get("login_key");

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Verification Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //checks a proposed property to user

  function check($type, $item=''){global $tpl;

   $tpl->splice("ERROR", "user_change_err.tpl");



   switch($type){

    case "name":

     //check for username and email from users

     $query = new db_cmd("SELECT", "users", "userid", "name LIKE '".$item."'", 1);

     if(!empty($query->data[0])) $errchk .= write_error_row($tpl->parse("ERROR->NAME_DUB_USER", "ERROR->NAME_DUB_USER"));

     unset($query);



     //check for username and email from usersauth

     $query = new db_cmd("SELECT", "usersauth", "userid", "name LIKE '".$item."'", 1);

     if(!empty($query->data[0])) $errchk .= write_error_row($tpl->parse("ERROR->NAME_DUB_AUSER", "ERROR->NAME_DUB_AUSER"));

     unset($query);



     //check for min lengths

     if(strlen($item) < 3  || strlen($item) > 50) $errchk .= write_error_row($tpl->parse("ERROR->NAME_BOUNDS", "ERROR->NAME_BOUNDS"));



     //check for ambiguous entrys

     if(hash($item) == $this->get("password"))  $errchk .= write_error_row($tpl->parse("ERROR->NAME_PASS", "ERROR->NAME_PASS"));

     break;

    case "password":

     //check for min lengths

     if(strlen($item) < 5 || strlen($item) > 50)  $errchk .= write_error_row($tpl->parse("ERROR->PASS_BOUNDS", "ERROR->PASS_BOUNDS"));



     //check for stupid peoples

     if(strtolower($item) == "password") $errchk .= write_error_row($tpl->parse("ERROR->PASS_PASS", "ERROR->PASS_PASS"));



     //check for ambiguous entrys

     if($item == $this->get("email")) $errchk .= write_error_row($tpl->parse("ERROR->PASS_EMAIL", "ERROR->PASS_EMAIL"));

     if($item == $this->get("name"))  $errchk .= write_error_row($tpl->parse("ERROR->PASS_NAME", "ERROR->PASS_NAME"));

     break;

    case "email":

     if(!checkemail($item))  $errchk .= write_error_row($tpl->parse("ERROR->EMAIL_FORMAT", "ERROR->EMAIL_FORMAT"));



     //check for username and email from users

     $query = new db_cmd("SELECT", "users", "userid", "email LIKE '".$item."' OR ( authemail LIKE '".$item."' AND authemailtime < ".(time() + $GLOBALS["login_timeout"])." )", 1);

     if(!empty($query->data[0])) $errchk .= write_error_row($tpl->parse("ERROR->EMAIL_DUB_AUSER", "ERROR->EMAIL_DUB_AUSER"));

     unset($query);



     //check for username and email from usersauth

     $query = new db_cmd("SELECT", "usersauth", "userid", "email LIKE '".$item."'", 1);

     if(!empty($query->data[0])) $errchk .= write_error_row($tpl->parse("ERROR->EMAIL_DUB_AUSER", "ERROR->EMAIL_DUB_AUSER"));

     unset($query);



     break;

    case "realname":

     //check for min lengths

     if(strlen($item) > 50)  $errchk .= write_error_row($tpl->parse("ERROR->REAL_MAX", "ERROR->REAL_MAX"));

     break;

    case "Location":

     //check for min lengths

     if(strlen($item) > 50)  $errchk .= write_error_row($tpl->parse("ERROR->LOC_MAX", "ERROR->LOC_MAX"));

     break;

    case "affiliation":

     //check for min lengths

     if(strlen($item) > 50)  $errchk .= write_error_row($tpl->parse("ERROR->AFF_MAX", "ERROR->AFF_MAX"));

     break;

    case "webpage":

     //check for min lengths

     if(strlen($item) > 150)  $errchk .= write_error_row($tpl->parse("ERROR->WEB_MAX", "ERROR->WEB_MAX"));

     break;

    case "icq":

     //check for min lengths

     if(strlen($item) > 20)  $errchk .= write_error_row($tpl->parse("ERROR->ICQ_MAX", "ERROR->ICQ_MAX"));

     break;

    case "msn":

     //check for min lengths

     if(strlen($item) > 150)  $errchk .= write_error_row($tpl->parse("ERROR->MSN_MAX", "ERROR->MSN_MAX"));

     break;

    case "aim":

     //check for min lengths

     if(strlen($item) > 50)  $errchk .= write_error_row($tpl->parse("ERROR->AIM_MAX", "ERROR->AIM_MAX"));

     break;

    case "time_format":

     //check for min lengths

     if(strlen($item) > 150)  $errchk .= write_error_row($tpl->parse("ERROR->TIME_MAX", "ERROR->TIME_MAX"));

     break;

    case "time":

     //check for min lengths

     if($item === -1)  $errchk .= write_error_row($tpl->parse("ERROR->TIME_BAD", "ERROR->TIME_BAD"));

     break;

   }



   return $errchk;

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// Email Code

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  //emails user of change in user propertys

  function email($type, $item = ''){global $tpl;

   switch($type){

    case "name":

     mail($this->get("email"), $tpl->fetchfile("email_user_change_name_title.tpl"),

      $tpl->fetchfile("email_user_change_name_content.tpl", array(

        "EMAIL_NAME" => $this->get("name")

       )), $tpl->fetchfile("email_user_change_name_xtra.tpl"));

     break;

    case "password":

     mail($this->get("email"), $tpl->fetchfile("email_user_change_pass_title.tpl"),

      $tpl->fetchfile("email_user_change_pass_content.tpl", array(

        "EMAIL_NAME" => $this->get("name"),

        "EMAIL_PASS" => $item

       )), $tpl->fetchfile("email_user_change_pass_xtra.tpl"));

     break;

    case "email":

     mail($this->get("authemail"), $tpl->fetchfile("email_user_change_email_title.tpl"),

      $tpl->fetchfile("email_user_change_email_content.tpl", array(

        "EMAIL_NAME" => $this->get("name"),

        "EMAIL_UID"  => $this->id,

        "EMAIL_KEY"  => $this->get("authemailkey")

       )), $tpl->fetchfile("email_user_change_email_xtra.tpl"));

     break;

    case "login":

     mail($this->get("email"), $tpl->fetchfile("email_login_title.tpl"),

      $tpl->fetchfile("email_login_content.tpl", array(

        "EMAIL_USER_IP" => clientip(),

        "EMAIL_LINK"    => "?page=login&cmd=login&uid=".$this->id."&key=".$this->gen_login_lpkey()

       )), $tpl->fetchfile("email_login_xtra.tpl"));

     break;

   }

  }



  //setup for user to change email using auth system

  function email_auth($email){

   $this->set(array(

     "authemail"     => $email,

     "authemailkey"  => gen_rand_str(50),

     "authemailtime" => time(),

    ));



   $this->email("email");

  }



  //save new email and clear auth crap

  function save_email_auth(){

   $this->set(array(

     "email"         => $this->get("authemail"),

     "authemail"     => '',

     "authemailkey"  => '0',

     "authemailtime" => '0',

     "authemail"     => '',

    ));

  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

 }



?>