<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;



 //writes a list of servers to select from

 function write_tourny_srv_lst($return, $null, $link){global $apanel, $tpl ,$tourny;

  $tpl->assign(array(

    "LINK_RETURN" => $return,

    "LINK_NULL"   => $null,

    "SERVERS"     => $tourny->get_list_servers($link)

   ));



  $apanel->set_cnt("ap_tourny_srv_lst.tpl", 1);

 }



 //writes a list of teams to select from

 function write_tourny_team_lst($return, $null, $link){global $apanel, $tpl, $tourny;

  $tpl->assign(array(

    "TYPE"         => $tourny->get_type_name(),

    "LINK_RETURN"  => $return,

    "LINK_NULL"    => $null,

    "TOURNY_TEAMS" => $tourny->get_list_teams($link)

   ));



  $apanel->set_cnt("ap_tourny_team_lst.tpl", 1);

 }



 function write_tourny_tlb(){global $tourny, $tpl, $apanel, $user;

  if($tourny->id == 0 || $tourny->stage() == 0){//tourny not valid or not selected

   if($user->get("admin") >= $GLOBALS["level_tourny"])//user is admin

    $apanel->set_tlb($tpl->fetchfile("ap_tourny_tlb_stage_0.tpl"));

  }else{ //valid tourny

   $tpl->splice("TLB", "ap_tourny_tlb_stage_".$tourny->stage().".tpl");



   if($user->get("admin") >= $GLOBALS["level_tourny"])//user is admin

    $tpl->parse("TLB->ADMIN", "TLB->ADMIN");

   else //not admin - hide

    $tpl->assign("TLB->ADMIN", "");



   $apanel->set_tlb($tpl->parse("TLB", "TLB"));

  }



  $apanel->set_hdr($tourny->get("name")  . " Tournament Admin Console");

 }



 //load tourny object

 $tourny = &$tournys->tourny($_GET["tournyid"]);



 //just assign the common vars

 $tpl->assign(array(

   "TOURNY_ID" => $tourny->id,

   "TYPE"      => $tourny->get_type_name()

  ));



 if($tourny->founder() || $user->get("admin") >= $level_tourny){

  write_tourny_tlb();



  switch($_GET["cmdd"]){

   case "stage":

    if($tourny->stage() >= $GLOBALS["tourny_stage_setup"] && $tourny->stage() <= $GLOBALS["tourny_stage_end"])

     include './code/admin_tourny_stage_all.inc.php';

    break;

   case "approve":

    if($user->get("admin") >= $level_tourny)

     include './code/admin_tourny_stage_all.inc.php';

    break;

   case "select":

    if($user->get("admin") >= $level_tourny)

     include './code/admin_tourny_stage_all.inc.php';

    break;

   default://stage cmd

    if($tourny->stage() >= $GLOBALS["tourny_stage_setup"] && $tourny->stage() <= $GLOBALS["tourny_stage_end"])

     switch($_GET["cmdd"]){

      case "module_team": //Called by Modules When Selecting a Teamlist

       if($tourny->stage() == $GLOBALS["tourny_stage_active"])

        $tourny->write_module_select_teams();

       break;

      default:

       include './code/admin_tourny_stage_'.$tourny->stage().'.inc.php';

       include './code/admin_tourny_setup.inc.php';

       break;

     }

    break;

  }



  if($apanel->get_cnt() == '') $apanel->set_cnt("ap_tourny_default.tpl", 1);

 }else //not supposed to be here

  $apanel->hide();



?>