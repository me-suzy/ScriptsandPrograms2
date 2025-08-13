<? /*************************************************************************************************
Copyright (c) 2003, 2004 Nathan 'Random' Rini
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*************************************************************************************************/ if(!defined('CONFIG')) die;

 function write_team_users(){global $apanel, $tpl, $team, $users, $tournys;
  //add player
  if($_GET["iadd"] > 0){
   $iuser = &$users->user($_GET["iadd"]);

   if($iuser->id > 0)
   if(!$team->is_user($iuser->id)){
    $team->add_user($iuser->id);
    $iuser->add_team($team->id);

    if($team->get("draft")){
     $teamlst = $team->tournys();

     //draft teams only have 1 tourny
     $itourny =& $tournys->tourny($teamlst[0]);

     //tell user they are in draft team
     $iuser->add_draft_tourny($itourny->id);

     switch($_GET["add"]){
      case "1": //draft player
       $itourny->add_draft_user($iuser->id);
       $team->set_rank($iuser->id, $GLOBALS["level_player"]);
       break;
      case "2": //draft capt
       $itourny->add_draft_capt($iuser->id);
       $team->set_rank($iuser->id, $GLOBALS["level_captain"]);
       break;
    }}
   }

   write_refresh("?page=admin&cmd=team&cmdd=users&team=".$team->id);
   return;
  }

  //search for player
  if($_GET["add"] > 0){
   $search = new form_search("?page=admin&cmd=team&cmdd=users&team=".$team->id."&add=".$_GET["add"]."&iadd=", 'user');
   $apanel->set_cnt($search->get_form_search());

   return;
  }

  //delete player
  if($_GET["del"] > 0){
   $iuser = &$users->user($_GET["del"]);

   if($iuser->id > 0)
   if($team->is_user($iuser->id)){
    if($team->get("draft")){
     $teamlst = $team->tournys();

     //draft teams only have 1 tourny
     $itourny =& $tournys->tourny($teamlst[0]);

     //remove player from tourny
     $itourny->del_draft_capt($iuser->id);
     $itourny->del_draft_user($iuser->id);

     //remove user from their draft team
     $iuser->del_draft_team($itourny->id);
    }

    $team->del_user($iuser->id);
    $iuser->del_team($team->id);
   }

   write_refresh("?page=admin&cmd=team&cmdd=users&team=".$team->id);
   return;
  }

  $tpl->splice("SETUP", "ap_team_users.tpl");

  if($team->get("draft"))
   $tpl->parse("SETUP->ADD", "SETUP->ADD_DRAFT");
  else //normal team
   $tpl->parse("SETUP->ADD", "SETUP->ADD_USER");

  if(is_array($team->users()))
  foreach($team->users() as $userid){
   $iuser = $users->user($userid);

   if($iuser->id > 0){
    $tpl->assign(array(
      "USER_NAME" => $iuser->tagname(),
      "USER_RANK" => $team->user_rank_text($iuser->id),
      "LINK_DEL"  => "?page=admin&cmd=team&cmdd=users&team=".$team->id."&del=".$iuser->id
     ));

    $tpl->parse("SETUP->TEAM","SETUP->TEAM", true);
  }}

  $tpl->parse("SETUP","SETUP", array(
    "LINK_ADD_USER" => "?page=admin&cmd=team&cmdd=users&team=".$team->id."&add=1",
    "LINK_ADD_CAPT" => "?page=admin&cmd=team&cmdd=users&team=".$team->id."&add=2"
   ));

  $apanel->set_cnt($tpl->fetch("SETUP"));
 }

 function write_team_invites(){global $apanel, $tpl, $team, $teaminvites, $users;
  //invite player
  if($_GET["iadd"] > 0){
   $iuser = &$users->user( $_GET["iadd"] );

   if($iuser->id > 0)
   if(!$teaminvites->user_team_invite($iuser->id, $team->id)){
    $invite = &$teaminvites->invite(0, 1);

    $invite->set(array(
      "userid" => $iuser->id,
      "team"   => $team->id,
      "time"   => time(),
     ));

    echo write_refresh("?page=admin&cmd=team&cmdd=invites&team=".$team->id);
    return;
  }}

  //search for player to invite
  if($_GET["add"] == 1){
   $search = new form_search("?page=admin&cmd=team&cmdd=invites&team=".$team->id."&iadd=", 'user');
   $apanel->set_cnt($search->get_form_search());

   return;
  }

  //delete invite
  if($_GET["del"] > 0){
   $invite = &$teaminvites->invite($_GET["del"]);

   if($invite->id > 0){
    $invite->delete();
   }

   echo write_refresh("?page=admin&cmd=team&cmdd=invites&team=".$team->id);
   return;
  }

  $tpl->define("NROW", "ap_team_invites_row.tpl");
  $tpl->clear("ROWS");

  foreach($teaminvites->team_invite($team->id) as $inviteid){
   $invite = &$teaminvites->invite($inviteid);
   $iuser  = &$users->user( $invite->get("userid") );

   if($invite->id > 0 && $iuser->id > 0){
    $tpl->assign(array(
      "USER_NAME" => $iuser->tagname(),
      "LINK_DEL"  => "?page=admin&cmd=team&cmdd=invites&team=".$team->id."&del=".$invite->id
     ));

    $tpl->parse("ROWS",".NROW");
   }

   unset($invite); unset($iuser);
  }

  $tpl->assign("LINK_CREATE_INVITE", "?page=admin&cmd=team&cmdd=invites&team=".$team->id."&add=1");

  $apanel->set_cnt("ap_team_invites.tpl", 1);
 }

 function write_team_edit(){global $apanel, $tpl, $team, $teams;
  if(isset($_POST["submit"]))
  if($errchk =
     ($team->get("name")==$_POST["name"]?'':$teams->check("name", $_POST["name"])) .
     ($team->get("password")==$_POST["pass"]?'':$teams->check("pass", $_POST["pass"])) .
     ($team->get("tag")==$_POST["tag"]?'':$teams->check("tag", $_POST["tag"])) .
     ($team->get("email")==$_POST["email"]?'':$teams->check("email", $_POST["email"])) .
     ($team->get("website")==$_POST["web"]?'':$teams->check("web", $_POST["web"])) .
     ($team->get("ircserv")==$_POST["ircs"]?'':$teams->check("ircs", $_POST["ircs"])) .
     ($team->get("irc")==$_POST["ircc"]?'':$teams->check("ircc", $_POST["ircc"])) .
     ($team->get("description")==$_POST["desc"]?'':$teams->check("desc", $_POST["desc"])
    ) == "")
  {
   while(@list ($line_num, $line) = @each($_POST["srvloc"]))
    $Prefered_Loc = addlistlocation($Prefered_Loc, $line);

   while(@list ($line_num, $line) = @each($_POST["gameplay"]))
    $Games_Play = addlistgame($Games_Play, $line);

   $team->set(array(
     "name"         => htmlchars($_POST["name"]),
     "tag"          => htmlchars($_POST["tag"]),
     "tagside"      => $_POST["tagside"],
     "email"        => $_POST["email"],
     "games"        => $Games_Play,
     "website"      => $_POST["web"],
     "ircserv"      => htmlchars($_POST["ircs"]),
     "irc"          => htmlchars($_POST["ircc"]),
     "servlocation" => $Prefered_Loc,
     "description"  => htmlchars($_POST["desc"])
    ));

   if($_POST["jpass"]) $team->set("password", ''); //disable
   else if($_POST["pass"] != '') $team->set("password", hash($_POST["pass"]));
  }

  $tpl->assign(array(
    "ERRORS"                       => $errchk == ''?'':write_error_common($errchk),
    "FIELD_NAME"                   => "name",
    "FIELD_NAME_VALUE"             => ($_POST["name"]=='')?$team->get("name"):htmlchars($_POST["name"]),
    "FIELD_TAG"                    => "tag",
    "FIELD_TAG_VALUE"              => ($_POST["tag"]=='')?$team->get("tag"):htmlchars($_POST["tag"]),
    "FIELD_TAGSIDE_NAME"           => "tagside",
    "FIELD_TAGSIDE_VALUE"          => ($_POST["tagside"]=='')?$team->get("tagside"):$_POST["tagside"],
    "FIELD_PASS"                   => "pass",
    "FIELD_PASS_VALUE"             => '', //pass is hashed
    "FIELD_JPASS_NAME"             => "jpass",
    "FIELD_JPASS_VALUE"            => "1",
    "FIELD_EMAIL"                  => "email",
    "FIELD_EMAIL_VALUE"            => ($_POST["email"]=='')?$team->get("email"):htmlchars($_POST["email"]),
    "FIELD_WEB"                    => "web",
    "FIELD_WEB_VALUE"              => ($_POST["web"]=='')?$team->get("website"):htmlchars($_POST["web"]),
    "FIELD_IRCC"                   => "ircc",
    "FIELD_IRCC_VALUE"             => ($_POST["ircc"]=='')?$team->get("irc"):htmlchars($_POST["ircc"]),
    "FIELD_IRCS"                   => "ircs",
    "FIELD_IRCS_VALUE"             => ($_POST["ircs"]=='')?$team->get("ircserv"):htmlchars($_POST["ircs"]),
    "FIELD_SRVLOC"                 => "srvloc",
    "FIELD_SRVLOC_VALUE"           => write_srvlocation_optlist($team->get("servlocation")),
    "FIELD_GAMEPLAY"               => "gameplay",
    "FIELD_GAMEPLAY_VALUE"         => write_game_optlist($team->get("games")),
    "FIELD_TEAM_DESCRIPTION"       => "desc",
    "FIELD_TEAM_DESCRIPTION_VALUE" => ($_POST["desc"]=='')?$team->get("description"):htmlchars($_POST["desc"]),
    "FIELD_SUBMIT"                 => "submit"
   ));

  $apanel->set_cnt("ap_team_edit.tpl", 1);
 }

 //find user if they exist, if not still call it
 $team = &$teams->team((INT) $_GET["team"]);

 //no team - select one
 if(!$team->id > 0) $_GET["cmdd"] = "select";

 switch($_GET["cmdd"]){
  case "select":
   $search = new form_search("?page=admin&cmd=team&team=", 'team');
   $apanel->set_cnt($search->get_form_search());
   break;
  case "edit":
   write_team_edit();
   break;
  case "invites":
   write_team_invites();
   break;
  case "users":
   write_team_users();
   break;
  default:
   $tpl->assign(array(
     "TEAM_NAME" => $team->get("name")
    ));
   $apanel->set_cnt("ap_team_default.tpl", 1);
 }
?>