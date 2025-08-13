<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 function write_tourny_approve(){global $tpl, $apanel, $tournysauth, $users, $tournys, $users;

  $tourny = $tournysauth->first();



  //admin approves

  if($_POST["submit"] == "Approve"){

   $ntourny = &$tournys->tourny(0,1);

   $cuser   = &$users->user($tourny->get("creator"));



   $ntourny->set(array(

     "name"    => $tourny->get("name"),

     "type"    => $tourny->get("type"),

     "maxteamspermatch"  => $tourny->get("maxmatch"),

     "creator" => $cuser->id,

     "status"  => $GLOBALS["tourny_stage_setup"],

     "draft"   => $tourny->get("type") == $GLOBALS["tourny_type_team"] ? $tourny->get("draft") : false

    ));



   //make user founder

   $cuser->add_tourny_founder($ntourny->id);



   //email user of approval

   $tourny->email(TRUE, $_POST["reason"]);



   //delete tournament

   $tourny->delete();



   write_refresh("?page=admin&cmd=tourny&cmdd=approve");

  }



  //admin denys

  if($_POST["submit"] == "Deny"){

   //email user of approval

   $tourny->email(FALSE, $_POST["reason"]);



   //delete tournament

   $tourny->delete();



   write_refresh("?page=admin&cmd=tourny&cmdd=approve");

  }



  $tpl->splice("PAGE", "ap_tourny_approve.tpl");



  if($tourny->id > 0){

   $cuser = &$users->user($tourny->get("creator"));



   //parse out founder's tourmaents

   foreach($cuser->tournys_founder() as $id){

    $ftourny = &$tournys->tourny($id);



    if($ftourny->id > 0){

     $tpl->parse("PAGE->FTOURNY","PAGE->FTOURNY",1,array(

       "FOUNDER_TOURNY" => $ftourny->get("name")

      ));

   }}

   //parse if something there, otherwise hide

   if($tpl->fetch("PAGE->FTOURNY") == '') $tpl->assign("PAGE->OTOURNY", '');

   else $tpl->parse("PAGE->OTOURNY", "PAGE->OTOURNY");



   //get tournament type

   if($tourny->get("type") == 1)

    $tpl->parse("PAGE->TYPE", "PAGE->TYPE_SINGLE");



   if($tourny->get("type") == 2){

    if($tourny->get("draft")) //tell if its a draft

     $tpl->parse("PAGE->TYPE_DRAFT", "PAGE->TYPE_DRAFT");

    else $tpl->assign("PAGE->TYPE_DRAFT", '');



    $tpl->parse("PAGE->TYPE", "PAGE->TYPE_TEAM");

   }



   //parse out purpose

   if($tourny->get("purpose") != '')

    $tpl->parse("PAGE->DETAILS", "PAGE->DETAILS", 0, array(

      "TOURNY_DETAIL" => $tourny->get("purpose")

     ));

   else

    $tpl->assign("PAGE->DETAILS", '');





   //parse rest

   $tpl->parse("PAGE->TOURNY", "PAGE->TOURNY", array(

     "TOURNY_TIME"    => date("M d, Y, h:m A" , $tourny->get("time")),

     "TOURNY_NAME"    => $tourny->get("name"),

     "TOURNY_FOUNDER" => $cuser->tagname(),

     "SUBMIT_APPROVE" => "Approve",

     "SUBMIT_DENY"    => "Deny",

     "FIELD_TEXT"     => "reason"

    ));

  }



  //check if invalid

  if(!($tourny->id > 0)) $tpl->assign(array(

    "PAGE->TOURNY" => '',

    "PAGE->NONE"   => $tpl->parse("PAGE->NONE","PAGE->NONE")

   ));

  else $tpl->assign("PAGE->NONE","");



  $apanel->set_cnt("PAGE", 1);

 }



 function write_tourny_stage(){global $tourny, $tpl, $apanel;

  if($_POST["confirmed"]){//confirmed

   if($tourny->stage() == $GLOBALS["tourny_stage_end"]){

    $tourny->delete();



    write_refresh("?page=news");

   }



   if($tourny->stage() == $GLOBALS["tourny_stage_signup_close"]){

    $tourny->create_table_matchs();



    if($tourny->get("draft")) //draft out teams

     $tourny->draft();

   }



   $tourny->set("status", $tourny->stage()+1);



   if($tourny->id > 0) write_refresh("?page=admin&cmd=tourny&tournyid=".$tourny->id);

   else write_refresh("?page=news");

  }else{ //pre confirmation

   $tpl->assign(array(

     "FIELD_CONF"       => "confirmed",

     "FIELD_CONF_VALUE" => "1"

    ));



   if($tourny->stage() >= $GLOBALS["tourny_stage_setup"] && $tourny->stage() <= $GLOBALS["tourny_stage_end"]) $apanel->set_cnt("ap_tourny_stage_".$tourny->stage().".tpl", 1);

  }

 }



 //stageless commands

 switch($cmdd){

  case "approve":

   if($user->get("admin") >= $level_tourny) write_tourny_approve();

   break;

  case "stage":

   if($tourny->founder() || $user->get("admin") >= $level_tourny) write_tourny_stage($tourny->stage());

   break;

  case "select":

   $search = new form_search("?page=admin&cmd=tourny&tournyid=", 'tourny');

   $apanel->set_cnt($search->get_form_search());

   break;

 }

?>