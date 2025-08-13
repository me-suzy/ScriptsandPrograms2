<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 function write_email_create(){global $tpl, $tournys, $emails;

  $tpl->splice("SETUP", "ap_email.tpl");



  //send out email

  if(isset($_POST["type"])){

   $title   = $_POST["title"];

   $content = $_POST["content"];

   $xtra    = $tpl->parse("SETUP->XTRA", "SETUP->XTRA");



   if(substr($_POST["type"], 0, strlen("tourny_")) == "tourny_"){ //check if its a tourny listing

    $parts = explode("_", $_POST["type"]);



    //check tourny

    $tourny = $tournys->tourny($parts[1]);



    if($tourny->id > 0) //valid tourny

     switch($parts[2]){ //type

      case "admins":

       $count = $emails->gen_list_tourny_admins($tourny->id, $title, $content, $xtra);

       break;

      case "players":

       $count = $emails->gen_list_tourny_players($tourny->id, $title, $content, $xtra);

       break;

      case "leaders":

       $count = $emails->gen_list_tourny_leaders($tourny->id, $title, $content, $xtra);

       break;

      case "team":

       $count = $emails->gen_list_tourny_team($tourny->id, $title, $content, $xtra);

       break;

      case "draft_users":

       if($tourny->get("draft")) $count = $emails->gen_list_tourny_team($tourny->id, $title, $content, $xtra);

       break;

      case "draft_capts":

       if($tourny->get("draft")) $count = $emails->gen_list_tourny_draft_capts($tourny->id, $title, $content, $xtra);

       break;

   }} else { //normal listing

    switch($_POST["type"]){

     case "all":

      $count = $emails->gen_list_users($title, $content, $xtra);

      break;

     case "teams":

      $count = $emails->gen_list_team($title, $content, $xtra);

      break;

     case "team_leaders":

      $count = $emails->gen_list_team_leaders($title, $content, $xtra);

      break;

     case "admins":

      $count = $emails->gen_list_admins($title, $content, $xtra);

      break;

   }}



   $tpl->parse("CONTENT", "SETUP->GENERATION", array(

     "COUNT" => $count

    ));

   return;

  }



  //query all tournaments to add them to listing

  $query = new db_cmd("select", "tournaments", "tournamentid");

  if(is_array($query->data))

  foreach($query->data as $data) if($data["tournamentid"] > 0){//possibly valid id

   $tourny =& $tournys->tourny($data["tournamentid"]);



   if($tourny->id > 0){//valid id

    $tpl->assign(array(

      "TOURNY_ID" => $tourny->id,

      "NAME"      => $tourny->get("name")

     ));



    if($tourny->get("draft")) //draft tourny

     $tpl->parse("SETUP->TOURNY_DRAFT", "SETUP->TOURNY_DRAFT");

    else //hide draft section

     $tpl->assign("SETUP->TOURNY_DRAFT", '');



    if($tourny->type() == $GLOBALS["tourny_type_team"]) //Team tourny

     $tpl->parse("SETUP->TOURNY_TEAM", "SETUP->TOURNY_TEAM");

    else //hide team section

     $tpl->assign("SETUP->TOURNY_TEAM", '');



    $tpl->parse("SETUP->TOURNY", "SETUP->TOURNY", true);

  }}



  //if there are no tournys, hide section

  if($tpl->fetch("SETUP->TOURNY") == '')

   $tpl->assign("SETUP->TOURNY", '');



  $tpl->parse("CONTENT", "SETUP->CREATION", array(

    "FIELD_TITLE_MAX"     => 800,

    "FIELD_TITLE_NAME"    => "title",

    "FIELD_EMAILCNT_NAME" => "content"

   ));

 }



 function write_email_send(){global $tpl, $emails;

  if($emails->send(50))

   write_refresh("?page=admin&cmd=email&cmdd=send", 1);

  else //done

   write_refresh("?page=admin&cmd=email&cmdd=finish", 0);

 }



 function write_email_finish(){global $tpl, $emails;

  $tpl->splice("SETUP", "ap_email.tpl");



  //tell them its done



  $tpl->parse("CONTENT", "SETUP->FINISH");

 }



 switch($cmdd){

  case "finish":

   write_email_finish();

   break;

  case "send":

   write_email_send();

   break;

  default:

   write_email_create();

 }



?>