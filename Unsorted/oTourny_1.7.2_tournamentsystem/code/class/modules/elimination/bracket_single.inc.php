<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  Tournament Module

   Single Elimation Class

 */



 require_once('./code/class/modules/elimination/bracket.inc.php');



 class module_elim_bracket_single extends elimination_bracket {

  function module_elim_bracket_single($id){

   parent::db_tourny_module($id);

  }



  function show(){global $tpl, $teams, $users, $tourny;

   //give link to rounds match list

   $rounds = $this->get("rounds");

   for($r=0;$r <= $rounds;$r++)

    $tpl->assign("A_ROUND_".$r."_HREF", "?page=tourny&tournyid=".$tourny->id."&cmd=matchs&show=module&module=".$this->id."&round=".$r);



   foreach($this->matchs() as $match)

    for($t=0;$t < $this->get("teamspermatch");$t++){



     if($match->get_team($t) > 0){ //dont waste time on invalid teams

      if($tourny->type() == $GLOBALS["tourny_type_team"])   $team =& $teams->team($match->get_team($t));

      if($tourny->type() == $GLOBALS["tourny_type_single"]) $team =& $users->user($match->get_team($t));

     }



     if($team->id > 0)

      $tpl->assign($match->get_team($t, "position"), $tpl->fetchfile("module_snlelim_setup_team.tpl", array(

        "A_TEAM_HREF"  => "?page=tourny&tournyid=".$tourny->id."&cmd=match&matchid=".$match->id,

        "A_TEAM_VALUE" => $team->get("name")

       )));

     else

      $tpl->assign($match->get_team($t, "position"), $tpl->fetchfile("module_snlelim_setup_team.tpl", array(

        "A_TEAM_HREF"  => "?page=tourny&tournyid=".$tourny->id."&cmd=match&matchid=".$match->id,

        "A_TEAM_VALUE" => ""

       )));



     unset($team);

    }



   if($this->get("generated")) $tpl->parsefile("CONTENT", $GLOBALS["loc_cache"]. $this->get("tpl"));

  }



  //setup page in admin console for bracket

  function write_setup(){global $tourny, $apanel, $tpl, $teams, $users;

   $tpl->assign("MODULE_ID", $this->id);



   //Set Team for Match Position

   if($_GET["setteam"] > 0){

    if(isset($_GET["newteam"])){ //set team

     $match =& $tourny->match($_GET["setteam"]);



     if($match->id > 0){

      if($_GET["newteam"] == 0) //save null teams

       $match->set_team($_GET["teampos"], "id", 0);

      else //make sure its a valid team

       if($tourny->is_team($_GET["newteam"]))

        $match->set_team($_GET["teampos"], "id", $_GET["newteam"]);



      unset($match);



      return write_refresh("?page=admin&cmd=tourny&cmdd=modules&tournyid=".$tourny->id."&module=".$this->id);

    }} else { //Select Team

     $tpl->splice("SETUP", "module_snlelim_setup_setteam.tpl");



     if(count($tourny->teams()) > 0){ //there are teams

      $tpl->assign(array(

        "SETUP->TEAMS"    => $tourny->get_list_teams("?page=admin&cmd=tourny&cmdd=modules&tournyid=".$tourny->id."&module=".$this->id."&setteam=".$_GET["setteam"]."&teampos=".$_GET["teampos"]."&newteam="),

        "SETUP->NO_TEAMS" => '',

        "A_NOTEAM_HREF"   => "?page=admin&cmd=tourny&cmdd=modules&tournyid=".$tourny->id."&module=".$this->id."&setteam=".$_GET["setteam"]."&teampos=".$_GET["teampos"]."&newteam=0"

       ));

     } else { //no teams

      $tpl->assign("SETUP->TEAMS", '');

      $tpl->parse("SETUP->NO_TEAMS", "SETUP->NO_TEAMS");

     }



     $tpl->parse("CONTENT", "SETUP");



     return;

    }}



   if($_GET["cmdtype"] > 0){

    switch($_GET["cmdtype"]){

     case "1": //create bracket

      if(!isset($_GET["destmod"])){

       $tourny->refresh_module_select_teams("?page=admin&cmd=tourny&cmdd=modules&tournyid=".$tourny->id."&module=".$this->id."&cmdtype=".$_GET["cmdtype"]."&destmod=1");



       return;

      }



      $this->gen_bracket(count($this->teams()));

      $this->seed($this->teams());

      break;

     case "2": //clear bracket

      $this->gen_bracket();

      break;

    }



    return write_refresh("?page=admin&cmd=tourny&cmdd=modules&tournyid=".$tourny->id."&module=".$this->id);

   }



   $tpl->parsefile("CONTENT", "module_snlelim_setup.tpl");



   //give link to rounds match list

   $rounds = $this->get("rounds");

   for($r=0;$r <= $rounds;$r++) //null out all round links

    $tpl->assign("A_ROUND_".$r."_HREF", "");



   foreach($this->matchs() as $match)

    for($t=0;$t < $this->get("teamspermatch");$t++){



     if($match->get_team($t) > 0){ //dont waste time on invalid teams

      if($tourny->type() == $GLOBALS["tourny_type_team"]) $team =& $teams->team($match->get_team($t));

      if($tourny->type() == $GLOBALS["tourny_type_single"])   $team =& $users->user($match->get_team($t));

     }



     if($team->id > 0)

      $tpl->assign($match->get_team($t, "position"), $tpl->fetchfile("module_snlelim_setup_team.tpl", array(

        "A_TEAM_HREF"  => "?page=admin&cmd=tourny&cmdd=modules&tournyid=".$tourny->id."&module=".$this->id."&setteam=".$match->id."&teampos=".$t,

        "A_TEAM_VALUE" => $team->get("name")

       )));

     else

      $tpl->assign($match->get_team($t, "position"), $tpl->fetchfile("module_snlelim_setup_team.tpl", array(

        "A_TEAM_HREF"  => "?page=admin&cmd=tourny&cmdd=modules&tournyid=".$tourny->id."&module=".$this->id."&setteam=".$match->id."&teampos=".$t,

        "A_TEAM_VALUE" => "Assign ".$tourny->get_type_name()

       )));



     unset($team);

    }



   if($this->get("generated")) $tpl->parsefile("BRACKETS", $GLOBALS["loc_cache"]. $this->get("tpl"));

  }



 }



 //presetup of bracket before generation

 function write_presetup_module_single_elim(){global $tourny, $apanel, $tpl;

  $tpl->splice("SETUP", "module_sglelim_presetup.tpl");



  if($_POST["submit"]){

   if( //check values

    ($_POST["tpm"] >= 2 && $_POST["tpm"] <= $tourny->get("maxteamspermatch"))

     &&

    ($_POST["mpm"] >= 1 && $_POST["mpm"] <= 999)

     &&

    (strlen($_POST["name"]) >= 5 && strlen($_POST["name"]) <= 125)

   ){

    $bracket = &$tourny->module(0, 1, $_GET["create"]);



    $bracket->set(array(

      "name"          => $_POST["name"],

      "teamspermatch" => $_POST["tpm"],

      "mapspermatch"  => $_POST["mpm"]

     ));



    //create blank bracket

    $bracket->gen_bracket();



    write_refresh("?page=admin&cmd=tourny&cmdd=modules&tournyid=".$tourny->id);

   }else //not valid - error out

    $tpl->parse("SETUP->ERROR", "SETUP->ERROR");

  }else $tpl->assign("SETUP->ERROR", ''); //hide error tpl



  $tpl->assign(array(

    "TPM_MAX"          => $tourny->get("maxteamspermatch"),

    "FIELD_NAME_NAME"  => "name",

    "FIELD_NAME_VALUE" => (strlen($_POST["name"]) >= 5 && strlen($_POST["name"]) <= 125) ? $_POST["name"] : ($_POST["name"] == '' ? "Single Elimination Bracket" : substr($_POST["name"], 0, 125) ),

    "FIELD_TPM_NAME"   => "tpm",

    "FIELD_TPM_VALUE"  => $_POST["tpm"] > 2 && $_POST["tpm"] <= $tourny->get("maxteamspermatch") ? $_POST["tpm"] : 2,

    "FIELD_MPM_NAME"   => "mpm",

    "FIELD_MPM_VALUE"  => $_POST["mpm"] > 1 && $_POST["mpm"] < 1000 ? $_POST["mpm"] : 1,

    "FIELD_SUBMIT_NAME" => "submit"

   ));



  $apanel->set_cnt("SETUP", 1);

 }



?>