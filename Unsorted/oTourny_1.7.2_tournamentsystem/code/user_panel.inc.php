<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  User Control Panel System

 */



 function write_profile(){global $tpl, $user, $teams;

  $tpl->splice("PROFILE", "user_panel_profile.tpl");



  if($_POST["submit"]){

   $user_time = strtotime($_POST["time"]); //grab their time



   if(($errchk =

     ($user->get("name")      == $_POST["Name"]        ? '' : $user->check("name", $_POST["Name"])) .

     ($_POST["Password"]      == ''                    ? '' : $user->check("password", $_POST["Password"])) .

     ($user->get("email")     == $_POST["Email"]       ? '' : $user->check("email", $_POST["Email"])) .

     ($user->get("realname")  == $_POST["Real_Name"]   ? '' : $user->check("realname", $_POST["Real_Name"])) .

     ($user->get("location")  == $_POST["Location"]    ? '' : $user->check("Location", $_POST["Location"])) .

     ($user->get("affialtion")== $_POST["Affiliation"] ? '' : $user->check("affiliation", $_POST["Affiliation"])) .

     ($user->get("webpage")   == $_POST["Webpage"]     ? '' : $user->check("webpage", $_POST["Webpage"])) .

     ($user->get("icq")       == $_POST["ICQ"]         ? '' : $user->check("icq", $_POST["ICQ"])) .

     ($user->get("msn")       == $_POST["MSN"]         ? '' : $user->check("msn", $_POST["MSN"])) .

     ($user->get("aim")       == $_POST["AIM"]         ? '' : $user->check("aim", $_POST["AIM"])) .

     $user->check("time_offset", $user_time) .

     ($user->get("time_format") == $_POST["time_format"] ? '' : $user->check("time_format", $_POST["time_format"]))

    ) == "")

  {

   //save main team

   if($_POST["mainteam"] == $tpl->parse("PROFILE->OPTION_TEAM_NAME", "PROFILE->OPTION_TEAM_NAME")) //check for null save

    $user->set("primaryteam", 0);

   else{

    //load team and check if they are member

    $team = &$teams->find_team($_POST["mainteam"]);



    if($team->is_user($user->id))//they are member

     $user->set("primaryteam", $team->id);

    else //error-catch

     $user->set("primaryteam", 0);

   }



   //find time diff

   $srv_time = new time((INT) $_POST["srv_time"], false);



   //grab diff

   $user_offset = $srv_time->get_time_offset($user_time, 3);



   while(@list ($line_num, $line) = @each($_POST["Prefered_Location"]))

    $Prefered_Loc = addlistlocation($Prefered_Loc, $line);



   while(@list ($line_num, $line) = @each($_POST["Games_Played"]))

    $Games_Play = addlistgame($Games_Play, $line);



   //save password if set

   if($_POST["Password"] != '')

    $user->set("password", hash($_POST["Password"]));



   //make email auth email

   if($user->get("email") != $_POST["Email"])

    $user->email_auth($_POST["Email"]);



   $user->set(array(

     "name"        => htmlchars($_POST["Name"]),

     "showemail"   => strgetbool($_POST["emailvisible"]),

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

     "time_format" => htmlchars($_POST["time_format"]),

     "time_offset" => $user_offset,

     "srvlocation" => $Prefered_Loc,

     "gamesplayed" => $Games_Play

    ));



   //save to forum

   $GLOBALS["forum"]->add_user($user);



   //only refresh if email not changed - otherwise need to tell them to check email

   if($user->get("email") == $_POST["Email"]){

   write_refresh('?page=profile&type=1&id='.$user->id,0); return;

  }}



  //parse out main teams

  foreach($user->teams() as $teamid){

   $team = &$teams->team($teamid);



   if($team->id > 0){

    $tpl->parse("PROFILE->OPTION_TEAM", "PROFILE->OPTION_TEAM", 1, array(

      "PROFILE->OPTION_TEAM_SELECTED" =>

       (($user->get("primaryteam") == $team->id) ? $tpl->parse("PROFILE->OPTION_TEAM_SELECTED","PROFILE->OPTION_TEAM_SELECTED") : ''),

      "PROFILE->OPTION_TEAM_NAME" => $team->get("name")

     ));

  }}}



  //parse out main teams null

  $tpl->parse("PROFILE->OPTION_TEAM", "PROFILE->OPTION_TEAM", 1, array(

      "PROFILE->OPTION_TEAM_SELECTED" =>

       (($user->get("primaryteam") == 0) ? $tpl->parse("PROFILE->OPTION_TEAM_SELECTED","PROFILE->OPTION_TEAM_SELECTED") : ''),

      "PROFILE->OPTION_TEAM_NAME" => $tpl->parse("PROFILE->OPTION_TEAM_NAME", "PROFILE->OPTION_TEAM_NAME")

     ));



  //grab time obj

  $time = new time(false, true);



  $tpl->parse("CENTER", "PROFILE", array(

    "PROFILE->EMAIL_CHANGE"=> ($user->get("email") != $_POST["Email"] && $_POST["Email"] != '')? $tpl->parse("PROFILE->EMAIL_CHANGE","PROFILE->EMAIL_CHANGE") : '',

    "ERRORS"               => $errchk == ''?'':write_error_common($errchk),

    "FIELD_NAME"           => "Name",

    "FIELD_NAME_VALUE"     => ($_POST["Name"]=='')?$user->get("name"):htmlchars($_POST["Name"]),

    "FIELD_PASS"           => "Password",

    "FIELD_EMAIL"          => "Email",

    "FIELD_EMAIL_VALUE"    => ($_POST["Email"]=='')?$user->get("email"):htmlchars($_POST["Email"]),

    "FIELD_EMAIL_VIS"             => "emailvisible",

    "FIELD_EMAIL_VIS_VALUE"       => "1",

    "FIELD_EMAIL_VIS_VALUE_CHECK" => numtobool($user->get("showemail")),

    "FIELD_RNAME"          => "Real_Name",

    "FIELD_RNAME_VALUE"    => ($_POST["Real_Name"]=='')?$user->get("realname"):htmlchars($_POST["Real_Name"]),

    "FIELD_LOC"            => "Location",

    "FIELD_LOC_VALUE"      => ($_POST["Location"]=='')?$user->get("location"):htmlchars($_POST["Location"]),

    "FIELD_SRVLOC"         => "Prefered_Location[]",

    "FIELD_SRVLOC_VALUE"   => write_srvlocation_optlist($user->get("srvlocation")),

    "FIELD_GAMEPLAY"       => "Games_Played[]",

    "FIELD_GAMEPLAY_VALUE" => write_game_optlist($user->get("gamesplayed")),

    "FIELD_AFF"            => "Affiliation",

    "FIELD_AFF_VALUE"      => ($_POST["Affiliation"]=='')?$user->get("affialtion"):htmlchars($_POST["Affiliation"]),

    "FIELD_WEB"            => "Webpage",

    "FIELD_WEB_VALUE"      => ($_POST["Webpage"]=='')?$user->get("webpage"):htmlchars($_POST["Webpage"]),

    "FIELD_ICQ"            => "ICQ",

    "FIELD_ICQ_VALUE"      => ($_POST["ICQ"]=='')?$user->get("icq"):htmlchars($_POST["ICQ"]),

    "FIELD_ICQ_VIS"        => "ICQ_Visible",

    "FIELD_ICQ_VIS_VALUE"  => 1,

    "FIELD_ICQ_VIS_CHK"    => $user->get("showicq"),

    "FIELD_MSN"            => "MSN",

    "FIELD_MSN_VALUE"      => ($_POST["MSN"]=='')?$user->get("msn"):htmlchars($_POST["MSN"]),

    "FIELD_MSN_VIS"        => "MSN_Visible",

    "FIELD_MSN_VIS_VALUE"  => "1",

    "FIELD_MSN_VIS_CHK"    => $user->get("showmsn"),

    "FIELD_AIM"            => "AIM",

    "FIELD_AIM_VALUE"      => ($_POST["AIM"]=='')?$user->get("aim"):htmlchars($_POST["AIM"]),

    "FIELD_AIM_VIS"        => "AIM_Visible",

    "FIELD_AIM_VIS_VALUE"  => "1",

    "FIELD_AIM_VIS_CHK"    => $user->get("showaim"),

    "FIELD_TEAMS"          => "mainteam",

    "FIELD_HIDDEN_TIME"    => "srv_time",

    "FIELD_HIDDEN_TIME_VALUE" => time(),

    "FIELD_TIME"           => "time",

    "FIELD_TIME_VALUE"     => ($_POST["time"]=='')?$time->get_formated($time->get_format_default(false)):htmlchars($_POST["time"]),

    "FIELD_TIME_FORMAT"    => "time_format",

    "FIELD_TIME_FORMAT_VALUE" => ($_POST["time_format"]=='')?($user->get("time_format")==''?$time->get_format_default():$user->get("time_format")):htmlchars($_POST["time_format"]),

    "FIELD_SUBMIT"         => "submit"

   ));

 }



 function player_jointeam(){global $centercol, $tpl, $teams, $user;

  $centercol = "user_panel_jteam.tpl";



  $team = &$teams->team($_GET["teamid"]);



  $tpl->assign(array(

    ERRORS     => '',

    TEAM_NAME  => $team->get("name"),

    FIELD_PASS => "team_pass"

   ));



  //pass is good

  if(isset($_POST["team_pass"])){

   $_POST["team_pass"] = trim($_POST["team_pass"]);



   if($_POST["team_pass"] == $team->get("password") && $team->get("password") != ''){

    $team->add_user($user->id); //notify team

    $user->add_team($team->id); //notify user



    write_refresh("?page=profile&type=1&id=".$user->id);

   }else

    $tpl->parsefile("ERRORS","user_panel_jteam_err.tpl");

 }}



 function write_teaminvite(){global $centercol, $tpl, $teams, $teaminvites, $user;

  $centercol = "user_panel_invite.tpl";



  if(isset($_GET["joinid"]))

  if($_GET["joinid"] > 0)

  foreach($teaminvites->user_invite($user->id) as $inviteid){

   $invite = &$teaminvites->invite($inviteid);



   if($invite->get("team") == $_GET["joinid"]){

    $team = &$teams->team( $_GET["joinid"] );



    $tpl->assign("TEAM_NAME", $team->get("name"));



    if(isset($_GET["deny"]))

     $centercol = "user_panel_invite_conf_2.tpl";

    else

     if($user->id > 0 && $team->id > 0){ //last check

      $team->add_user($user->id);

      $user->add_team($team->id);



      $centercol = "user_panel_invite_conf_1.tpl";

     }



    $teaminvites->delete($team->id, $user->id);//clear out invites

    return;

   }



   unset($invite); unset($team);

  }



  $tpl->define("NROW", "user_panel_invite_row.tpl");

  $tpl->assign("ROWS", '');



  foreach($teaminvites->user_invite($user->id) as $inviteid){

   $invite = &$teaminvites->invite($inviteid);

   $team   = &$teams->team( $invite->get("team") );



   if($team->id > 0){

    $tpl->assign(array(

      "TEAM_NAME"   => $team->get("name"),

      "LINK_ACCEPT" => "?page=playercontrol&cmd=iteam&joinid=".$team->id,

      "LINK_REJECT" => "?page=playercontrol&cmd=iteam&deny=1&joinid=".$team->id

     ));



    $tpl->parse("ROWS",".NROW");

   }



   unset($invite); unset($team);

  }



 }



 function write_tourny(){global $centercol, $tpl, $user, $tournys, $images;

  $tpl->splice("TOURNYS", "user_panel_tourny.tpl");



  if(isset($_GET["draftask"])){

   if(isset($_POST["submit"])){

    //join tourny

    $tourny = &$tournys->tourny($_GET["jtournyid"]);



    if($tourny->id > 0){//valid tourny

     if($_POST["captain"]) $tourny->add_draft_capt(&$user);

     else $tourny->add_draft_user(&$user);

    }



    write_refresh("?page=playercontrol&cmd=tourny");

    return;

   }



   //show captain question

   $tpl->parse("CENTER", "TOURNYS->ASKCAPT", array(

     "FIELD_CAPT_NAME"  => "captain",

     "FIELD_CAPT_VALUE" => "1",

     "FIELD_SUBMIT"     => "submit"

    ));



   return;

  }else $tpl->assign("TOURNYS->ASKCAPT", ''); //null tpl



  if(isset($_GET["ltournyid"]))

  if($_GET["ltournyid"] > 0){//leave

   $tourny = &$tournys->tourny($_GET["ltournyid"]);



   if($tourny->id > 0)

   if($tourny->del_team_valid($user->id, $tourny->get("draft"))){

    if($tourny->get("type") == 1)   //single only

      $tourny->del_team($user->id);

    if($tourny->get("type") == 2 && $tourny->get("draft")){ //draft only

      $tourny->del_draft_capt(&$user);

      $tourny->del_draft_user(&$user);

     }

   }



   return write_refresh("?page=playercontrol&cmd=tourny");

  }



  if(isset($_GET["jtournyid"]))

  if($_GET["jtournyid"] > 0){//join

   $tourny = &$tournys->tourny($_GET["jtournyid"]);



   if($tourny->id > 0)

   if($tourny->add_team_valid($user->id, $tourny->get("draft"))){

    if($tourny->get("type") == 1) //single only

      $tourny->add_team($user->id);

    if($tourny->get("type") == 2 && $tourny->get("draft"))//draft only

      $tourny->add_draft_user($user->id);

   }



   write_refresh("?page=playercontrol&cmd=tourny");

   return;

  }



  //JOIN



  //select tournys that are open and single player

  $query = new db_cmd("SELECT", "tournaments", array("tournamentid"), "status=".$GLOBALS["tourny_stage_signup_open"]." AND (type = 1 OR draft = 1)");

  foreach($query->data as $tournydata)

   if($tournydata['tournamentid'] > 0){ //possible valid tourny

    $tourny =& $tournys->tourny($tournydata['tournamentid']);



    if($tourny->id > 0){ //valid tourny

     if($tourny->get('type') == 1){ //single player

      if($tourny->add_team_valid($user->id)){ //user not in tourny

       //image

       $image = $images->image($tourny->get('banner'));

       if($image->id > 0)

        $tpl->parse("TOURNYS->JT_BANNER", "TOURNYS->JT_BANNER", 0, array(

          "JT_LINK"   => "?page=playercontrol&cmd=tourny&jtournyid=".$tourny->id,

          "JT_BANNER" => $image->image()

         ));

       else $tpl->assign("TOURNYS->JT_BANNER", '');



       $tpl->parse("TOURNYS->JT_ROW", "TOURNYS->JT_ROW", 1, array(

         "JT_NAME"   => $tourny->get("name"),

         "JT_LINK"   => "?page=playercontrol&cmd=tourny&jtournyid=".$tourny->id,

        ));

      }

     }elseif($tourny->get("draft")){ //draft tourny

      if($tourny->add_team_valid($user->id, true)){ //user not in tourny

       //image

       $image = $images->image($tourny->get('banner'));

       if($image->id > 0)

        $tpl->parse("TOURNYS->JD_BANNER", "TOURNYS->JD_BANNER", 0, array(

          "JD_LINK"   => "?page=playercontrol&cmd=tourny&draftask=1&jtournyid=".$tourny->id,

          "JD_BANNER" => $image->image()

         ));

       else $tpl->assign("TOURNYS->JD_BANNER", '');



       $tpl->parse("TOURNYS->JD_ROW", "TOURNYS->JD_ROW", 1, array(

         "JD_NAME"   => $tourny->get("name"),

         "JD_LINK"   => "?page=playercontrol&cmd=tourny&draftask=1&jtournyid=".$tourny->id

        ));

     }}}

    }

  //Error Msg -- single

  if($tpl->fetch("TOURNYS->JT_ROW") != '')

   $tpl->assign("TOURNYS->JT_NONE", ''); //null out error msg

  else{//no tournys - error out

   $tpl->parse("TOURNYS->JT_NONE","TOURNYS->JT_NONE");

   $tpl->assign("TOURNYS->JT_ROW", '');

  }



  //Error Msg  -- draft

  if($tpl->fetch("TOURNYS->JD_ROW") != '')

   $tpl->assign("TOURNYS->JD_NONE", ''); //null out error msg

  else{//no tournys - error out

   $tpl->parse("TOURNYS->JD_NONE","TOURNYS->JD_NONE");

   $tpl->assign("TOURNYS->JD_ROW", '');

  }



  //LEAVE



  //current single player tournaments

  foreach($user->tournys() as $tournyid){

   $tourny = &$tournys->tourny($tournyid);



   if($tourny->id > 0)

   if($tourny->del_team_valid($user->id)){

    $image = $images->image($tourny->get("banner"));



    if($image->id > 0)

     $tpl->parse("TOURNYS->LT_BANNER", "TOURNYS->LT_BANNER", 0, array(

       "LT_LINK"   => "?page=playercontrol&cmd=tourny&ltournyid=".$tourny->id,

       "LT_BANNER" => $image->image()

      ));

    else $tpl->assign("TOURNYS->LT_BANNER", '');



    $tpl->parse("TOURNYS->LT_ROW", "TOURNYS->LT_ROW", 1, array(

      "LT_NAME" => $tourny->get("name"),

      "LT_LINK" => "?page=playercontrol&cmd=tourny&ltournyid=".$tourny->id

     ));

  }}



  //current single player tournaments

  foreach($user->draft_tournys() as $tournyid){

   $tourny = &$tournys->tourny($tournyid);



   if($tourny->id > 0)

   if($tourny->del_team_valid($user->id, true)){

    $image = $images->image($tourny->get("banner"));



    if($image->id > 0)

     $tpl->parse("TOURNYS->LT_BANNER", "TOURNYS->LT_BANNER", 0, array(

       "LT_LINK"   => "?page=playercontrol&cmd=tourny&ltournyid=".$tourny->id,

       "LT_BANNER" => $image->image()

      ));

    else $tpl->assign("TOURNYS->LT_BANNER", '');



    $tpl->parse("TOURNYS->LT_ROW", "TOURNYS->LT_ROW", 1, array(

      "LT_NAME" => $tourny->get("name"),

      "LT_LINK" => "?page=playercontrol&cmd=tourny&ltournyid=".$tourny->id

     ));

  }}



  //Error Msg

  if($tpl->fetch("TOURNYS->LT_ROW") != '')

   $tpl->assign("TOURNYS->LT_NONE", ''); //null out error msg

  else{//no tournys - error out

   $tpl->parse("TOURNYS->LT_NONE","TOURNYS->LT_NONE");

   $tpl->assign("TOURNYS->LT_ROW", '');

  }



  $tpl->parse("CENTER", "TOURNYS");

 }



 if($user->id > 0)

 switch($cmd){

  case "profile":

   write_profile();

   break;



  case "tourny":

   write_tourny();

   break;



  case "cteam":

   include 'team_create.inc.php';

   break;

  case "iteam":

   write_teaminvite();

   break;

  case "jteam":

   if($_GET["teamid"] > 0)

    player_jointeam();

   else{

    $search = new form_search("?page=playercontrol&cmd=jteam&teamid=", 'team');

    $search->set_search_center();

   }

   break;



 }

?>