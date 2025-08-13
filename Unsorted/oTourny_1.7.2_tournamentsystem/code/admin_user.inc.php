<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 //change user admin level

 function write_user_admin(){global $apanel, $tpl, $iuser;

  if(isset($_GET["setlevel"]) && $iuser->id > 0){

   $iuser->set("admin", (($_GET["setlevel"] > -1 && $_GET["setlevel"] < 4)? $_GET["setlevel"] :"0") );



   echo write_refresh("?page=admin&cmd=user&cmdd=admin&iuser=".$iuser->id,0);

   return;

  }



  $tpl->assign(array(

    "PLAYER" => $iuser->tagname(),

    "PLAYER_LEVEL" => $iuser->level_name(),

    "LINK_ADMIN_3" => "?page=admin&cmd=user&cmdd=admin&iuser=".$iuser->id."&setlevel=3",

    "LINK_ADMIN_2" => "?page=admin&cmd=user&cmdd=admin&iuser=".$iuser->id."&setlevel=2",

    "LINK_ADMIN_1" => "?page=admin&cmd=user&cmdd=admin&iuser=".$iuser->id."&setlevel=1",

    "LINK_ADMIN_0" => "?page=admin&cmd=user&cmdd=admin&iuser=".$iuser->id."&setlevel=0"

   ));



  $apanel->set_cnt("ap_user_adminlvl.tpl", 1);

 }



 function write_user_edit(){global $apanel, $iuser, $tpl, $user;

  if(isset($_POST["submit"]))

  if(($errchk =

     ($iuser->get("name")      == $_POST["username"]    ? '' : $iuser->check("name", $_POST["username"])) .

     ($_POST["Password"]       == ''                    ? '' : $iuser->check("password", $_POST["userpass"])) .

     ($iuser->get("email")     == $_POST["useremail"]   ? '' : $iuser->check("email", $_POST["useremail"])) .

     ($iuser->get("realname")  == $_POST["Real_Name"]   ? '' : $iuser->check("realname", $_POST["Real_Name"])) .

     ($iuser->get("location")  == $_POST["Location"]    ? '' : $iuser->check("Location", $_POST["Location"])) .

     ($iuser->get("affialtion")== $_POST["Affiliation"] ? '' : $iuser->check("affiliation", $_POST["Affiliation"])) .

     ($iuser->get("webpage")   == $_POST["Webpage"]     ? '' : $iuser->check("webpage", $_POST["Webpage"])) .

     ($iuser->get("icq")       == $_POST["ICQ"]         ? '' : $iuser->check("icq", $_POST["ICQ"])) .

     ($iuser->get("msn")       == $_POST["MSN"]         ? '' : $iuser->check("msn", $_POST["MSN"])) .

     ($iuser->get("aim")       == $_POST["AIM"]         ? '' : $iuser->check("aim", $_POST["AIM"]))

    ) == "" || $_POST["override"])

  {

   while(@list ($line_num, $line) = @each($_POST["Prefered_Location"]))

    $Prefered_Loc = addlistlocation($Prefered_Loc, $line);



   while(@list ($line_num, $line) = @each($_POST["Games_Played"]))

    $Games_Play = addlistgame($Games_Play, $line);



   $iuser->set(array(

     "showaim"     => strgetbool($_POST["AIM_Visible"]),

     "aim"         => htmlchars($_POST["AIM"]),

     "showmsn"     => strgetbool($_POST["MSN_Visible"]),

     "msn"         => htmlchars($_POST["MSN"]),

     "icq"         => htmlchars($_POST["ICQ"]),

     "showicq"     => strgetbool($_POST["ICQ_Visible"]),

     "webpage"     => htmlchars($_POST["Webpage"]),

     "affialtion"  => htmlchars($_POST["Affiliation"]),

     "location"    => htmlchars($_POST["Location"]),

     "realname"    => htmlchars($_POST["Real_Name"]),

     "srvlocation" => $Prefered_Loc,

     "gamesplayed" => $Games_Play,

     "name"        => $_POST["username"],

     "email"       => $_POST["useremail"]

    ));



   if($_POST["userpass"] != '') $iuser->set("password", hash($_POST["userpass"]));



   //save to forum

   $GLOBALS["forum"]->add_user($user);



   write_refresh('?page=admin&cmd=user&cmdd=edit&iuser='.$iuser->id);

   return;

  }



  $tpl->assign(array(

    "ERRORS"               => $errchk == ''?'':write_error_common($errchk),

    "FIELD_NAME"           => "username",

    "FIELD_NAME_VALUE"     => ($_POST["username"]=='')?$iuser->get("name"):htmlchars($_POST["username"]),

    "FIELD_PASS"           => "userpass",

    "FIELD_PASS_VALUE"     => '',

    "FIELD_EMAIL"          => "useremail",

    "FIELD_EMAIL_VALUE"    => ($_POST["useremail"]=='')?$iuser->get("email"):htmlchars($_POST["useremail"]),

    "FIELD_RNAME"          => "Real_Name",

    "FIELD_RNAME_VALUE"    => ($_POST["Real_Name"]=='')?$iuser->get("realname"):htmlchars($_POST["Real_Name"]),

    "FIELD_LOC"            => "Location",

    "FIELD_LOC_VALUE"      => ($_POST["Location"]=='')?$iuser->get("location"):htmlchars($_POST["Location"]),

    "FIELD_SRVLOC"         => "Prefered_Location[]",

    "FIELD_SRVLOC_VALUE"   => write_srvlocation_optlist($iuser->get("srvlocation")),

    "FIELD_GAMEPLAY"       => "Games_Played[]",

    "FIELD_GAMEPLAY_VALUE" => write_game_optlist($iuser->get("gamesplayed")),

    "FIELD_AFF"            => "Affiliation",

    "FIELD_AFF_VALUE"      => ($_POST["Affiliation"]=='')?$iuser->get("affialtion"):htmlchars($_POST["Affiliation"]),

    "FIELD_WEB"            => "Webpage",

    "FIELD_WEB_VALUE"      => ($_POST["Webpage"]=='')?$iuser->get("webpage"):htmlchars($_POST["Webpage"]),

    "FIELD_ICQ"            => "ICQ",

    "FIELD_ICQ_VALUE"      => ($_POST["ICQ"]=='')?$iuser->get("icq"):htmlchars($_POST["ICQ"]),

    "FIELD_ICQ_VIS"        => "ICQ_Visible",

    "FIELD_ICQ_VIS_VALUE"  => 1,

    "FIELD_ICQ_VIS_CHK"    => $iuser->get("showicq"),

    "FIELD_MSN"            => "MSN",

    "FIELD_MSN_VALUE"      => ($_POST["MSN"]=='')?$iuser->get("msn"):htmlchars($_POST["MSN"]),

    "FIELD_MSN_VIS"        => "MSN_Visible",

    "FIELD_MSN_VIS_VALUE"  => "1",

    "FIELD_MSN_VIS_CHK"    => $iuser->get("showmsn"),

    "FIELD_AIM"            => "AIM",

    "FIELD_AIM_VALUE"      => ($_POST["AIM"]=='')?$iuser->get("aim"):htmlchars($_POST["AIM"]),

    "FIELD_AIM_VIS"        => "AIM_Visible",

    "FIELD_AIM_VIS_VALUE"  => "1",

    "FIELD_AIM_VIS_CHK"    => $iuser->get("showaim"),

    "FIELD_OVERRIDE_NAME"  => "override",

    "FIELD_OVERRIDE_VALUE" => "1",

    "FIELD_SUBMIT"         => "submit"

   ));



  $apanel->set_cnt("ap_user_edit.tpl", 1);

 }



 function write_user_disc(){global $apanel, $iuser, $tpl, $user;

  if(isset($_POST["submit"]))

   $iuser->set("loginlock", (($iuser->get("loginlock") == 1) ? '0' : '1' ));



  $tpl->assign(array(

    "FIELD_LOCK_NAME" => "submit",

    "FIELD_LOCK_VAL"  => ($iuser->get("loginlock") ? "UnLock User Account" : "Lock User Account")

   ));



  $apanel->set_cnt("ap_user_disc.tpl", 1);

 }



 function write_user_teams(){global $apanel, $iuser, $tpl, $user, $teams;

  //find team

  if(isset($_GET["sadd"])){

   $search = new form_search("?page=admin&cmd=user&cmdd=teams&iuser=".$iuser->id."&add=", 'team');

   $apanel->set_cnt($search->get_form_search());

   return;

  }



  //add team

  if(isset($_GET["add"]))

  if($_GET["add"] > 0)

  {

   $team = &$teams->team($_GET["add"]);



   if($team->id > 0) //valid team

   if(!$team->is_user($iuser->id)){//not on team

    $team->add_user($iuser->id);

    $iuser->add_team($team->id);

  }}



  //remove team

  if(isset($_GET["rem"]))

  if($_GET["rem"] > 0)

  {

   $team = &$teams->team($_GET["rem"]);



   if($team->id > 0) //valid team

   if($team->is_user($iuser->id)){//on team

    $team->del_user($iuser->id);

    $iuser->del_team($team->id);

  }}



  $tpl->define("NROW", "ap_user_teams_row.tpl");

  $tpl->clear("ROWS");



  foreach($iuser->teams() as $teamid){

   $team = &$teams->team($teamid);



   if($team->id > 0){ //valid team

    $tpl->assign(array(

      "CLASS"           => (++$i % 2)?"row":"rowoff",

      "TEAM_NAME"       => $team->get("name"),

      "LINK_RTEAM_HREF" => "?page=admin&cmd=user&cmdd=teams&iuser=".$iuser->id."&rem=".$team->id

     ));



    $tpl->parse("ROWS", ".NROW");



    unset($team);

  }}



  $tpl->assign("LINK_ADD_HREF", "?page=admin&cmd=user&cmdd=teams&iuser=".$iuser->id."&sadd=1");



  $apanel->set_cnt("ap_user_teams.tpl", 1);

 }



 //get selected user obj

 $iuser = &$users->user((INT) $_GET["iuser"]);



 //no user - select one

 if(!$iuser->id > 0) $_GET["cmdd"] = "select";



 //Permissions Check -- Thou shall not edit thy masters or thy peers

 if($iuser->get("admin") >= $user->get("admin")) $_GET["cmdd"] = 'select';



 switch($_GET["cmdd"]){

  case "select":

   $search = new form_search("?page=admin&cmd=user&iuser=", 'user');

   $apanel->set_cnt($search->get_form_search());

   break;

  case "admin":

   write_user_admin();

   break;

  case "teams":

   write_user_teams();

   break;

  case "disc":

   write_user_disc();

   break;

  case "edit":

   write_user_edit();

   break;

  default:

   $tpl->assign(array(

     "PLAYER"       => $iuser->tagname(),

     "PLAYER_LEVEL" => $iuser->level_name()

    ));

   $apanel->set_cnt("ap_user_default.tpl", 1);

 }

?>