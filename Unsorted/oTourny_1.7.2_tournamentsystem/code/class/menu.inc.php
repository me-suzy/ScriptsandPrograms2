<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;



 //tournament list

 function create_menu_tourny(){global $tpl;

  //call quick cmd - mysql should be cacheing this

  $query = new db_cmd("select", "tournaments", array("tournamentid", "name", "status", "draft"));



  if(is_array($query->data))

  foreach($query->data as $tourny)

   if($tourny["status"] >= $GLOBALS["tourny_stage_signup_open"]){ //tourny open to public

    //grab module names

    $modquery = new db_cmd("select", "tournaments_module", array("id", "name"), "tournyid = '".$tourny["tournamentid"]."'");



    //clear old modules

    $tpl->clear("MENU->TOURNYS_ROWS_MODULE");



    if(is_array($modquery->data)) //parse out all module links

     foreach($modquery->data as $module)

      if($module["id"] > 0 && $module["name"] != '')

       $tpl->parse("MENU->TOURNYS_ROWS_MODULE", "MENU->TOURNYS_ROWS_MODULE", true, array(

         "MENU_MODULE_NAME" => htmlchars($module["name"]),

         "MENU_MODULE_ID"   => $module["id"],

        ));



    if($tpl->fetch("MENU->TOURNYS_ROWS_MODULE") == '') //check for nulls

     $tpl->assign("MENU->TOURNYS_ROWS_MODULE", '');



    //player draft

    if($tourny["draft"])

     $tpl->parse("MENU->TOURNYS_ROWS_DRAFT", "MENU->TOURNYS_ROWS_DRAFT");

    else //hide draft

     $tpl->assign("MENU->TOURNYS_ROWS_DRAFT", '');



    $tpl->assign(array(

      "MENU_TOURNY_ID"   => $tourny["tournamentid"],

      "MENU_TOURNY_NAME" => htmlchars($tourny["name"]),

     ));



    $tpl->parse("MENU->TOURNYS_ROWS","MENU->TOURNYS_ROWS", true);

   }



   if($tpl->fetch("MENU->TOURNYS_ROWS") == '')

    $tpl->assign(array( //hide on null

      "MENU->TOURNYS_ROWS_MODULE" => '',

      "MENU->TOURNYS_ROWS"        => ''

     ));



  $tpl->assign("MENU->TOURNYS", $tpl->assignc(!empty($query->data), "", "MENU->TOURNYS") );

 }



 function create_menu_user_teams(){global $tpl, $user, $teams, $level_founder, $level_captain;

  if(count($user->teams()) > 0)

   foreach($user->teams() as $teamid) if($teamid > 0){

    $team = &$teams->team($teamid);



    if($team->get("name") != ''){

     $tpl->assign(array(

       "MENU_USER_TEAM_ID"   => $team->id,

       "MENU_USER_TEAM_NAME" => htmlchars($team->get("name"))

      ));



     $adminlvl = $team->user_rank($user->id);



     if($adminlvl >= $level_founder && !$team->get("draft")) $tpl->parse("MENU->USER_TEAM_CP_FOUNDER", "MENU->USER_TEAM_CP_FOUNDER");

     else $tpl->assign("MENU->USER_TEAM_CP_FOUNDER", '');



     if($adminlvl >= $level_captain && !$team->get("draft")) $tpl->parse("MENU->USER_TEAM_CP_CAPT", "MENU->USER_TEAM_CP_CAPT");

     else $tpl->assign("MENU->USER_TEAM_CP_CAPT", '');



     if(!$team->get("draft"))

      $tpl->parse("MENU->USER_TEAM", "MENU->USER_TEAM", 1);

   }}



  if($tpl->fetch("MENU->USER_TEAM") != "")

   $tpl->parse("MENU->USER_TEAMS", "MENU->USER_TEAMS");

  else

   $tpl->assign("MENU->USER_TEAMS", "");//no teams

 }



 //admin tournaments

 function create_menu_user_atourny(){global $tpl, $user, $tournys;

  if(count($user->tournys_admin()) > 0 ){

   foreach($user->tournys_admin() as $tournyid){

    $tourny = &$tournys->tourny($tournyid);



    if($tourny->id > 0)

    $tpl->parse("MENU->USER_ATOURNY_ROW", "MENU->USER_ATOURNY_ROW", 1, array(

      "MENU_USER_ATOURNY_ID"   => $tourny->id,

      "MENU_USER_ATOURNY_NAME" => htmlchars($tourny->get("name"))

     ));

   }



   $tpl->parse("MENU->USER_ATOURNY", "MENU->USER_ATOURNY");

  }



  if($tpl->fetch("MENU->USER_ATOURNY_ROW") == '')

   $tpl->assign("MENU->USER_ATOURNY");//no atournys

 }



 //single player tournaments

 function create_menu_user_stourny(){global $tpl, $user, $tournys;

  if(count($user->tournys()) > 0){

   foreach($user->tournys() as $tournyid){

    $tourny = &$tournys->tourny($tournyid);



    if($tourny->id > 0)

    $tpl->parse("MENU->USER_TOURNY_SINGLE_ROW", "MENU->USER_TOURNY_SINGLE_ROW", 1, array(

      "MENU_USER_STOURNY_ID"   => $tourny->id,

      "MENU_USER_STOURNY_NAME" => htmlchars($tourny->get("name"))

     ));

   }



   $tpl->parse("MENU->USER_TOURNY_SINGLE", "MENU->USER_TOURNY_SINGLE");

  }



  if($tpl->fetch("MENU->USER_TOURNY_SINGLE_ROW") == '')

   $tpl->assign("MENU->USER_TOURNY_SINGLE", '');

 }



 //team tournaments

 function create_menu_user_ttourny(){global $tpl, $user, $teams, $tournys;

  if(count($user->teams()) > 0){

   foreach($user->teams() as $teamid){

    $team = &$teams->team($teamid);



    if($team->id > 0)

    if(count($team->tournys()) > 0){

     $tpl->assign(array(

       "MENU_USER_TEAM_ID"   => $team->id,

       "MENU_USER_TEAM_NAME" => htmlchars($team->get("name"))

      ));



     $tpl->clear("MENU->USER_TOURNY_TEAM_ROW");



     foreach($team->tournys() as $tournyid){

      $tourny = &$tournys->tourny($tournyid);



      if($tourny->id > 0)

       $tpl->parse("MENU->USER_TOURNY_TEAM_ROW", "MENU->USER_TOURNY_TEAM_ROW", 1, array(

         "MENU_USER_TEAM_TOURNY_ID"   => $tourny->id,

         "MENU_USER_TEAM_TOURNY_NAME" => htmlchars($tourny->get("name"))

        ));

     }



     if($tpl->fetch("MENU->USER_TOURNY_TEAM_ROW") != '')

      $tpl->parse("MENU->USER_TOURNY_TEAM", "MENU->USER_TOURNY_TEAM", 1);

  }}}



  //catch blanks

  if($tpl->fetch("MENU->USER_TOURNY_TEAM") == '')

   $tpl->assign("MENU->USER_TOURNY_TEAM", '');

 }



 function create_menu_user(){global $tpl, $user, $level_console;

  if(!is_object($user))

   $tpl->parse("MENU->SIGNUP", "MENU->SIGNUP", 0, array("MENU->USER" => ""));

  else{

   $tpl->assign(array(

     "MENU->USER_ADMINCONSOLE" => ($user->get("admin") >= $level_console)?$tpl->parse("MENU->USER_ADMINCONSOLE", "MENU->USER_ADMINCONSOLE"):'',

     "MENU_USER_ID"     => $user->id,

     "MENU_USER_NAME"   => htmlchars($user->tagname())

    ));



   create_menu_user_teams();

   create_menu_user_atourny();

   create_menu_user_stourny();

   create_menu_user_ttourny();



   if($tpl->fetch("MENU->USER_TOURNY_SINGLE_ROW") != '' || $tpl->fetch("MENU->USER_TOURNY_TEAM_ROW") != '')

    $tpl->parse("MENU->USER_TOURNY","MENU->USER_TOURNY");

   else

    $tpl->assign("MENU->USER_TOURNY","");



   $tpl->parse("MENU->USER", "MENU->USER", 0, array("MENU->SIGNUP" => ""));

  }

 }



 function create_menu(){global $tpl;

  $tpl->splice("MENU", "menu.tpl");



  create_menu_tourny();

  create_menu_user();



  $tpl->parse("MENU", "MENU");

 }



?>