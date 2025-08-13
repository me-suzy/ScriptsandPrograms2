<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

   General Setup

   Setup Banner Admins Servers Teams

 */



 function write_tourny_setup(){global $tourny, $tpl, $apanel, $games;

  if(isset($_POST["submit"])){

   $game = &$games->find_game($_POST["servergame"]);



   //convert date/time to timestamp

   $time = new entry_time($_POST["startdate"]);



   $tourny->set(array(

     "maxjoin"  => $_POST["maxteams"],

     "time"     => $time->get(),

     "details"  => $_POST["tournydescription"],

     "rules"    => $_POST["tournyrules"],

     "serverrequirments" => $_POST["tournyrequirments"],

     "maps"     => $_POST["tournymaps"],

     "schedule" => $_POST["tournysch"],

     "news"     => $_POST["tournynews"],

     "sponsers" => $_POST["tournysponser"],

     "prizes"   => $_POST["tournyprizes"],



     "game"     => $game->id,

     "gametype" => htmlchars($_POST["servertype"]),

     "mod"      => htmlchars($_POST["servermod"]),



     "playermin" => (INT) $_POST["playermin"],

     "playermax" => (INT) $_POST["playermax"]

    ));

  }



  //grab time obj

  $time = new time($tourny->get("time"));



  $tpl->assign(array(

   "FIELD_SUBMIT"          => "submit",

   "FIELD_GAME"            => "servergame",

   "FIELD_GAME_OPTIONS"    => write_game_optlist($tourny->get("game")),

   "FIELD_GAME_TYPE"       => "servertype",

   "FIELD_GAME_TYPE_VALUE" => $tourny->get("gametype"),

   "FIELD_GAME_TYPE_MAX"   => "255",

   "FIELD_GAME_MOD"        => "servermod",

   "FIELD_GAME_MOD_VALUE"  => $tourny->get("mod"),

   "FIELD_GAME_MOD_MAX"    => "255",

   "FIELD_TEAMS"           => "maxteams",

   "FIELD_TEAMS_MAX"       => "255",

   "FIELD_TEAMS_VALUE"     => $tourny->get("maxjoin"),

   "FIELD_PLAYERMIN"       => "playermin",

   "FIELD_PLAYERMIN_MAX"   => "2",

   "FIELD_PLAYERMIN_VALUE" => $tourny->get("playermin"),

   "FIELD_PLAYERMAX"       => "playermax",

   "FIELD_PLAYERMAX_MAX"   => "2",

   "FIELD_PLAYERMAX_VALUE" => $tourny->get("playermax"),

   "FIELD_DATE"            => "startdate",

   "FIELD_DATE_VALUE"      => $time->get_formated(),

   "FIELD_DATE_MAX"        => "255",

   "FIELD_NEWS"            => "tournynews",

   "FIELD_NEWS_VALUE"      => $tourny->get("news"),

   "FIELD_DESC"            => "tournydescription",

   "FIELD_DESC_VALUE"      => $tourny->get("details"),

   "FIELD_RULES"           => "tournyrules",

   "FIELD_RULES_VALUE"     => $tourny->get("rules"),

   "FIELD_SRVREQ"          => "tournyrequirments",

   "FIELD_SRVREQ_VALUE"    => $tourny->get("serverrequirments"),

   "FIELD_MAP"             => "tournymaps",

   "FIELD_MAP_VALUE"       => $tourny->get("maps"),

   "FIELD_SCH"             => "tournysch",

   "FIELD_SCH_VALUE"       => $tourny->get("schedule"),

   "FIELD_SPONS"           => "tournysponser",

   "FIELD_SPONS_VALUE"     => $tourny->get("sponsers"),

   "FIELD_PRIZE"           => "tournyprizes",

   "FIELD_PRIZE_VALUE"     => $tourny->get("prizes")

  ));



  $tpl->splice("SETUP", "ap_tourny_all_setup.tpl");



  //show/hide player min/max area

  if($tourny->type() == $GLOBALS["tourny_type_single"])

   $tpl->assign("SETUP->TEAM_REQ", '');

  elseif($tourny->type() == $GLOBALS["tourny_type_team"])

   $tpl->parse("SETUP->TEAM_REQ", "SETUP->TEAM_REQ");



  $apanel->set_cnt("SETUP", 1);

 }



 function write_tourny_banner(){global $tourny, $apanel, $tpl, $images;

  if($_POST["submit"]){

   $ufile = new file_uploaded("userfile");



   if(!$ufile->err){ //no errors

    if($tourny->get("banner") > 0) //load image if exists

     $image = &$images->image($tourny->get("banner"));

    else  //create image

     $image = &$images->image(0, 1);



    //save file

    $image->upload($ufile);



    //update tourny

    $tourny->set("banner", $image->id);



    unset($image);

  }}



  $image = &$images->image($tourny->get("banner"));



  $tpl->splice("SETUP", "ap_tourny_all_banner.tpl");



  //check to display errs

  if(is_object($ufile)){

   if($ufile->err) $tpl->parse("SETUP->ERROR", "SETUP->ERROR");

   else $tpl->assign("SETUP->ERROR", "");

  } else $tpl->assign("SETUP->ERROR", "");



  $tpl->assign(array(

   "FILE_NAME"         => $image->name(),

   "FILE_LOCATION"     => $image->image(),

   "enctype"           => "multipart/form-data",

   "FILE_MAX"          => "1000000",

   "FIELD_FILE_NAME"   => "userfile",

   "FIELD_FILE_TYPE"   => "file",

   "FIELD_SUBMIT_NAME" => "submit"

  ));



  $apanel->set_cnt("SETUP", 1);

 }



 function write_tourny_admins(){global $tourny, $tpl, $apanel, $users;

  if($_GET["addadmin"] > 0) $tourny->add_admin($_GET["addadmin"]);

  if($_GET["deladmin"] > 0) $tourny->del_admin($_GET["deladmin"]);



  if(isset($_GET["addadmin"]) || isset($_GET["deladmin"])){

   write_refresh("?page=admin&cmd=tourny&cmdd=admins&tournyid=".$tourny->id);

   return;

  }



  $tpl->splice("SETUP", "ap_tourny_all_admin.tpl");



  //grab admin array

  $admins = $tourny->admins();



  //clean array of blanks -- only need with the admin count

  foreach($admins as $key => $data)

   if($data == '') unset($admins[$key]);



  if(count($admins) > 0)//there are admins

   foreach($admins as $adminid)//list out admins

    if($adminid > 0){

     $admin = &$users->user($adminid);



     if($admin->id > 0)//only valid admins

      $tpl->parse("SETUP->ROW", "SETUP->ROW", 1, array(

        "ROW_CLASS"    => ($i++ % 2) ? "row" : "rowoff",

        "ADMIN_NAME"   => $admin->tagname(),

        "ADMIN_R_LINK" => "?page=admin&cmd=tourny&cmdd=admins&tournyid=".$tourny->id."&deladmin=".$admin->id,

       ));

    }



  //show/hide none text

  if($tpl->fetch("SETUP->ROW") == '')

   $tpl->parse("SETUP->NONE", "SETUP->NONE", array("SETUP->ROW" => ""));

  else $tpl->assign("SETUP->NONE", "");



  $tpl->assign(array(

    "TOURNY_ID"   => $tourny->id,

    "ADMIN_COUNT" => count($admins)

   ));



  $apanel->set_cnt("SETUP", 1);

 }



 function write_tourny_servers(){global $tourny, $apanel, $tpl, $servers;

  if($_GET["server"] > 0){ //selecting a server

   $server = &$servers->server($_GET["server"]);



   if($server->id > 0){ //valid server

    if($_GET["selectadmin"]){

     //list admins to assign to server

     write_tourny_servers_admin_list(&$server); return;

    } else {

     //list admins assigned to server

     write_tourny_servers_srv_admin_list(&$server); return;

  }}}



  //Front Page - Listing Servers

  write_tourny_servers_server_list();

 }



 //list admins to add to server

 function write_tourny_servers_admin_list(&$server){global $tourny, $apanel, $tpl, $users;

  $tpl->splice("SETUP", "ap_tourny_all_servers_admins_lst.tpl");



  $tpl->assign(array(

    "SERVER_ID"   => $server->id,

    "SERVER_NAME" => $server->get("name"),

    "SERVER_IP"   => $server->get("ip"),

    "SERVER_PORT" => $server->get("port"),

   ));



  //grab admin array

  $admins = $tourny->admins();

  foreach($admins as $adminid) if($adminid > 0){

   $admin = $users->user($adminid);



   if($admin->id > 0){//valid admin

    $tpl->parse("SETUP->COL", "SETUP->COL", 1, array(

      "LINK" => "?page=admin&cmd=tourny&cmdd=servers&tournyid=".$tourny->id."&server=".$server->id."&addadmin=".$admin->id,

      "TXT"  => $admin->tagname()

     ));



    //parse 3 admins per row

    if(++$i % 3 == 0){

     $tpl->parse("SETUP->ROW", "SETUP->ROW", 1);



     //clear cols

     $tpl->clear("SETUP->COL");

    }

  }}



  //last row not parsed

  if($i % 3 != 0 && $i > 0) $tpl->parse("SETUP->ROW", "SETUP->ROW", 1);



  //check for no admins

  if($tpl->fetch("SETUP->ROW") == '') //error out

   $tpl->parse("SETUP->ERROR", "SETUP->ERROR", array("SETUP->ROW" => ''));

  else $tpl->assign("SETUP->ERROR", ""); //clear error var



  $apanel->set_cnt("SETUP", 1);

 }



 //list admins assigned to a single server

 function write_tourny_servers_srv_admin_list(&$server){global $tourny, $apanel, $tpl, $users;

  $tpl->splice("SETUP", "ap_tourny_all_servers_admins.tpl");



  $tpl->assign(array(

    "SERVER_ID"   => $server->id,

    "SERVER_NAME" => $server->get("name"),

    "SERVER_IP"   => $server->get("ip"),

    "SERVER_PORT" => $server->get("port"),

   ));



  if($_GET["addadmin"] > 0) $server->add_admin($_GET["addadmin"]);

  if($_GET["deladmin"] > 0) $server->rem_admin($_GET["deladmin"]);



  //grap admin array

  $admins = $server->admins();



  if($admins > 0)//there are serveral possible valid admins

   foreach($admins as $adminid)

    if($adminid > 0){ //most likely valid admin

     $admin = &$users->user($adminid);



     if($admin->id > 0)//valid admin

      $tpl->parse("SETUP->ROW", "SETUP->ROW", 1, array(

        "ROW_CLASS" => ($i++ % 2) ? "row" : "rowoff",

        "ADMIN"     => $admin->tagname(),

        "LINK_REM_ADMIN" => "?page=admin&cmd=tourny&cmdd=servers&tournyid=".$tourny->id."&server=".$server->id."&deladmin=".$admin->id

       ));

   }



  //check if there are any admins

  if($tpl->fetch("SETUP->ROW") == '') //hide error

   $tpl->parse("SETUP->ERROR", "SETUP->ERROR", array("SETUP->ROW" => ''));

  else //print out error

   $tpl->assign("SETUP->ERROR", '');



  $apanel->set_cnt("SETUP", 1);

 }



 // Front Page - Listing Servers

 function write_tourny_servers_server_list(){global $tourny, $apanel, $tpl, $servers;

  if($_GET["delserver"] > 0){

   $tourny->del_server($_GET["delserver"]);

   return write_refresh("?page=admin&cmd=tourny&cmdd=servers&tournyid=".$tourny->id);

  }



  $tpl->splice("SETUP", "ap_tourny_all_servers.tpl");



  $tservers = $tourny->servers();



  if(count($tservers) > 0)

   foreach($tservers as $serverid) if($serverid > 0){

    $server = &$servers->server($serverid);



    if($server->id > 0){

     //count admins

     $acount = count($server->admins());

     if($acount > 0) //there are admins

      $tpl->parse("SETUP->ADMIN_GOOD", "SETUP->ADMIN_GOOD", array(

        "SERVER_ADMIN_CNT" => $acount,

        "SETUP->ADMIN_BAD" => '' //dont show bad msg var

       ));

     else //err no admin

      $tpl->parse("SETUP->ADMIN_BAD", "SETUP->ADMIN_BAD", array(

        "SETUP->ADMIN_GOOD" => '' //dont show good msg var

       ));



     $tpl->parse("SETUP->ROW", "SETUP->ROW", 1, array(

       "ROW_CLASS"   => ($i++ % 2) ? "row" : "rowoff",

       "SERVER_ID"   => $server->id,

       "SERVER_NAME" => $server->get("name"),

       "SERVER_IP"   => $server->get("ip"),

       "SERVER_PORT" => $server->get("port")

      ));

   }}



  //check for no servers

  if($tpl->fetch("SETUP->ROW") == '')

   $tpl->parse("SETUP->NEED_SRV", "SETUP->NEED_SRV", array("SETUP->ROW" => ''));

  else $tpl->assign("SETUP->NEED_SRV", '');



  $apanel->set_cnt("SETUP", 1);

 }



 function write_tourny_servers_modify(){global $apanel, $tpl, $servers, $regions, $tourny;

  $server =& $servers->server($_GET["serverid"]);



  if(isset($_POST["cmdtype"]))

  if(($errchk =

     ($server->get("name")  ==$_POST["servername"]        && $server->id > 0 ?'': $server->check("name", $_POST["servername"])) .

     ($server->get("ip")    ==$_POST["serverip"]          && $server->id > 0 ?'': $server->check("ip", $_POST["serverip"])) .

     ($server->get("port")  ==$_POST["port"]              && $server->id > 0 ?'': $server->check("port", $_POST["port"])) .

     ($server->get("sapass")==$_POST["sadminpass"]        && $server->id > 0 ?'': $server->check("sapass", $_POST["sadminpass"])) .

     ($server->get("apass") ==$_POST["adminpass"]         && $server->id > 0 ?'': $server->check("apass", $_POST["adminpass"])) .

     ($server->get("jpass") ==$_POST["joinpass"]          && $server->id > 0 ?'': $server->check("jpass", $_POST["joinpass"])) .

     ($server->get("amsg")  ==$_POST["adminmsg"]          && $server->id > 0 ?'': $server->check("amsg", $_POST["adminmsg"])) .

     ($server->get("cmsg")  ==$_POST["captainmsg"]        && $server->id > 0 ?'': $server->check("cmsg", $_POST["captainmsg"])) .

     ($server->get("pmsg")  ==$_POST["playermsg"]         && $server->id > 0 ?'': $server->check("pmsg", $_POST["playermsg"])) .

     ($server->get("srvmsg")==$_POST["serverdescription"] && $server->id > 0 ?'': $server->check("srvmsg", $_POST["serverdescription"]))

    ) == ""){

   if(!($_GET["serverid"] > 0) || $_POST["forcesavenew"] || $_POST["cmdtype"] == "Clone Server" ){

    $server =& $servers->server(0,1);

    $tourny->add_server($server->id);

   }



   $region = &$regions->find_region($serverlocation);



   $server->set(array(

     "tournyid" => $_GET["tournyid"],

     "ip"       => htmlchars($_POST["serverip"]),

     "name"     => htmlchars($_POST["servername"]),

     "srvmsg"   => htmlchars($_POST["serverdescription"]),

     "region"   => $region->id,

     "apass"    => htmlchars($_POST["adminpass"]),

     "sapass"   => htmlchars($_POST["sadminpass"]),

     "jpass"    => htmlchars($_POST["joinpass"]),

     "cmsg"     => $_POST["captainmsg"],

     "pmsg"     => $_POST["playermsg"],

     "amsg"     => $_POST["adminmsg"],

     "port"     => htmlchars($_POST["port"])

    ));



   if($_POST["cmdtype"] == "New Server"){echo write_refresh("?page=admin&cmd=tourny&cmdd=createserv&tournyid=".$_GET["tournyid"]."&serverid=-1"); return;}

   if($_POST["cmdtype"] == "Save Server Data"){echo write_refresh("?page=admin&cmd=tourny&cmdd=servers&tournyid=".$_GET["tournyid"]); return;}

  }



  $tpl->splice("SETUP", "ap_tourny_all_servers_modify.tpl");



  if($cmdtype == "Clone Server") //print extra cmd for clone server

   $tpl->parse("SETUP->CMDS_XTRA", "SETUP->CMDS_XTRA");

  else //clear var

   $tpl->assign("SETUP->CMDS_XTRA", "");



  $tpl->assign(array(

    "ERRORS"                    => $errchk == ''?'':write_error_common($errchk),

    "FIELD_CMDTYPE_NAME"        => "cmdtype",

    "FIELD_CMDTYPE_VALUE_SAVE"  => "Save Server Data",

    "FIELD_CMDTYPE_VALUE_NEW"   => "New Server",

    "FIELD_CMDTYPE_VALUE_CLONE" => "Clone Server",

    "FIELD_SRVNAME_MAX"         => "50",

    "FIELD_SRVNAME_NAME"        => "servername",

    "FIELD_SRVNAME_VALUE"       => isset($_POST["servername"]) ? $_POST["servername"] : $server->get("name"),

    "FIELD_SRVIP_MAX"           => "15",

    "FIELD_SRVIP_NAME"          => "serverip",

    "FIELD_SRVIP_VALUE"         => isset($_POST["serverip"]) ? $_POST["serverip"] : $server->get("ip"),

    "FIELD_SRVPORT_MAX"         => "4",

    "FIELD_SRVPORT_NAME"        => "port",

    "FIELD_SRVPORT_VALUE"       => isset($_POST["port"]) ? $_POST["port"] : $server->get("port"),

    "FIELD_SRVLOC_NAME"         => "serverlocation",

    "FIELD_SRVLOC_VALUE"        => write_srvlocation_optlist($server->get("region")),

    "FIELD_SAPASS_MAX"          => "50",

    "FIELD_SAPASS_NAME"         => "sadminpass",

    "FIELD_SAPASS_VALUE"        => isset($_POST["sadminpass"]) ? $_POST["sadminpass"] : $server->get("sapass"),

    "FIELD_APASS_MAX"           => "50",

    "FIELD_APASS_NAME"          => "adminpass",

    "FIELD_APASS_VALUE"         => isset($_POST["adminpass"]) ? $_POST["adminpass"] : $server->get("apass"),

    "FIELD_JPASS_MAX"           => "50",

    "FIELD_JPASS_NAME"          => "joinpass",

    "FIELD_JPASS_VALUE"         => isset($_POST["joinpass"]) ? $_POST["joinpass"] : $server->get("jpass"),

    "FIELD_AMSG_NAME"           => "adminmsg",

    "FIELD_AMSG_VALUE"          => isset($_POST["adminmsg"]) ? $_POST["adminmsg"] : $server->get("amsg"),

    "FIELD_CMSG_NAME"           => "captainmsg",

    "FIELD_CMSG_VALUE"          => isset($_POST["captainmsg"]) ? $_POST["captainmsg"] : $server->get("cmsg"),

    "FIELD_PMSG_NAME"           => "playermsg",

    "FIELD_PMSG_VALUE"          => isset($_POST["playermsg"]) ? $_POST["playermsg"] : $server->get("pmsg"),

    "FIELD_SRVDESC_NAME"        => "serverdescription",

    "FIELD_SRVDESC_VALUE"       => isset($_POST["serverdescription"]) ? $_POST["serverdescription"] : $server->get("srvmsg")

   ));



  $apanel->set_cnt("SETUP", 1);

 }



 function write_tourny_teams(){global $tourny, $apanel, $tpl, $teams, $users;

  //preload teams

  if($tourny->type() == $GLOBALS["tourny_type_single"])

   $users->user($tourny->teams());

  if($tourny->type() == $GLOBALS["tourny_type_team"])

   $teams->team($tourny->teams());



  //add team

  if(isset($_GET["addteam"])){

   $tourny->add_team((INT) $_GET["addteam"]);



   write_refresh("?page=admin&cmd=tourny&cmdd=teamsetup&tournyid=".$tourny->id);

   return;

  }

  //remove team

  if(isset($_GET["delteam"])){

   $tourny->del_team((INT) $_GET["delteam"]);



   write_refresh("?page=admin&cmd=tourny&cmdd=teamsetup&tournyid=".$tourny->id);

   return;

  }

  //add capt

  if(isset($_GET["addcapt"])){

   $tourny->add_draft_capt((INT) $_GET["addcapt"]);



   write_refresh("?page=admin&cmd=tourny&cmdd=teamsetup&tournyid=".$tourny->id);

   return;

  }

  //add draft player

  if(isset($_GET["adddraft"])){

   $tourny->add_draft_user((INT) $_GET["adddraft"]);



   write_refresh("?page=admin&cmd=tourny&cmdd=teamsetup&tournyid=".$tourny->id);

   return;

  }

  //remove draft player

  if(isset($_GET["deldraft"])){

   $tourny->del_draft_user((INT) $_GET["deldraft"]);

   $tourny->del_draft_capt((INT) $_GET["deldraft"]);



   write_refresh("?page=admin&cmd=tourny&cmdd=teamsetup&tournyid=".$tourny->id);

   return;

  }



  $tpl->splice("SETUP", "ap_tourny_all_teams.tpl");



  //draft tournaments

  if($tourny->get("draft")){

   //check to see if they can add draft players

   if($tourny->stage() < $GLOBALS["tourny_stage_active"])

    $tpl->parse("SETUP->DRAFT_EDIT_USER", "SETUP->DRAFT_EDIT_USER");

   else //cant change draft users after tourny begins

    $tpl->assign("SETUP->DRAFT_EDIT_USER", "");



   if(is_array($tourny->draft_capts())){

    //preload users

    $users->user($tourny->draft_capts());



   foreach($tourny->draft_capts() as $userid) if($userid > 0){ //possibly valid user

    $duser =& $users->user($userid);



    if($duser->id > 0){ //valid user

     $dcapt_count++;



     $team = $teams->team($duser->get_draft_team($tourny->id));

     if($team->id > 0) //valid team

      $tpl->assign("SETUP->TEAM_STATUS", $team->get("name"));

     else { //team not set

      //check if captain made profile

      if($tourny->get_draft_capt_profile($duser->id) === FALSE) //hide if they havent

       $tpl->parse("SETUP->TEAM_STATUS", "SETUP->TEAM_STATUS_NONE");

      else //show they have completed profile

       $tpl->parse("SETUP->TEAM_STATUS", "SETUP->TEAM_STATUS_DONE");

     }



     //show type as captain

     $tpl->parse("SETUP->DRAFT_TYPE", "SETUP->DRAFT_CAPT");



     $tpl->parse("SETUP->DRAFT_ROW", "SETUP->DRAFT_ROW", true, array(

       "CLASS"   => $cl++ % 2 ? "row" : "rowoff",

       "NAME"    => $duser->tagname(),

       "TEAM_ID" => $duser->id

      ));

   }}}



   if(is_array($tourny->draft_users())){

    //preload users

    $users->user($tourny->draft_users());



   foreach($tourny->draft_users() as $userid) if($userid > 0){ //possibly valid user

    $duser =& $users->user($userid);



    if($duser->id > 0){ //valid user

     $duser_count++;



     $team = $teams->team($duser->get_draft_team($tourny->id));

     if($team->id > 0) //valid team

      $tpl->assign("SETUP->TEAM_STATUS", $team->get("name"));

     else //team not set

      $tpl->assign("SETUP->TEAM_STATUS", "");



     //show they are user

     $tpl->parse("SETUP->DRAFT_TYPE", "SETUP->DRAFT_USER");



     $tpl->parse("SETUP->DRAFT_ROW", "SETUP->DRAFT_ROW", true, array(

       "CLASS"   => $cl++ % 2 ? "row" : "rowoff",

       "NAME"    => $duser->tagname(),

       "TEAM_ID" => $duser->id

      ));

   }}}



   //check for no teams

   if($tpl->fetch("SETUP->DRAFT_ROW") == '')

    $tpl->parse("SETUP->DRAFT_USER_LIST", "SETUP->DRAFT_ERROR", array(

     "DRAFT_COUNT" => 0,

     "CAPT_COUNT"  => 0

    ));

   else //valid teams

    $tpl->parse("SETUP->DRAFT_USER_LIST", "SETUP->DRAFT_TABLE", array(

     "DRAFT_COUNT" => (INT) $duser_count,

     "CAPT_COUNT"  => (INT) $dcapt_count

    ));



   $tpl->parse("SETUP->DRAFT_LIST", "SETUP->DRAFT_LIST");

  }else $tpl->assign("SETUP->DRAFT_LIST", '');



  if(is_array($tourny->teams())){

  foreach($tourny->teams() as $teamid) if($teamid > 0){

   if($tourny->type() == $GLOBALS["tourny_type_single"])

    $team = &$users->user($teamid);

   if($tourny->type() == $GLOBALS["tourny_type_team"])

    $team = &$teams->team($teamid);



   if($team->id > 0){ //valid team

    $tpl->parse("SETUP->ROW", "SETUP->ROW", 1, array(

      "CLASS"   => (++$i % 2) ? "row" : "rowoff",

      "NAME"    => $team->get("name"),

      "TEAM_ID" => $team->id

     ));



    $team_count++; //allows acurate list figure at top of page

  }}}



  //check for no teams

  if($tpl->fetch("SETUP->ROW") == '')

   $tpl->parse("SETUP->TEAM_LIST", "SETUP->ERROR");

  else //valid teams

   $tpl->parse("SETUP->TEAM_LIST", "SETUP->TEAM_TABLE");



  $tpl->assign(array(

    "TYPE"       => $tourny->get_type_name(),

    "TEAM_COUNT" => (INT) $team_count

   ));



  $apanel->set_cnt("SETUP", 1);

 }



 switch($cmdd){

  case "setup":

   write_tourny_setup();

   break;

  case "banner":

   write_tourny_banner();

   break;

  case "admins":

   write_tourny_admins();

   break;

  case "createserv":

   write_tourny_servers_modify();

   break;

  case "servers":

   write_tourny_servers();

   break;

  case "selectadmin":

   $search = new form_search("?page=admin&cmd=tourny&cmdd=admins&tournyid=".$tourny->id."&addadmin=", 'user');

   $apanel->set_cnt($search->get_form_search());

   break;

  case "adminjoin":

   $search = new form_search("?page=admin&cmd=tourny&cmdd=teamsetup&tournyid=".$tourny->id."&addteam=", ($tourny->type() == $GLOBALS["tourny_type_single"]) ? 'user' : 'team');

   $apanel->set_cnt($search->get_form_search());

   break;

  case "captjoin":

   $search = new form_search("?page=admin&cmd=tourny&cmdd=teamsetup&tournyid=".$tourny->id."&addcapt=", 'user');

   $apanel->set_cnt($search->get_form_search());

   break;

  case "draftjoin":

   $search = new form_search("?page=admin&cmd=tourny&cmdd=teamsetup&tournyid=".$tourny->id."&adddraft=", 'user');

   $apanel->set_cnt($search->get_form_search());

   break;

  case "teamsetup":

   write_tourny_teams();

   break;

 }

?>