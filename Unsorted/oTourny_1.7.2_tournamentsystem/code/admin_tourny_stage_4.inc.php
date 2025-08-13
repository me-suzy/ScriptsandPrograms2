<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  Stage 4

   Create/Play Modules

   End Tourny

 */



 function write_tourny_modules(){global $tourny, $apanel, $tpl;

  //ADMIN MODULE

  if($_GET["module"] > 0){

   $module =& $tourny->module($_GET["module"]);



   if($module->id > 0){ //valid module

    $module->write_setup();

    return;

  }}



  //CREATE MODULE

  if($_GET["create"] > 0){ //use the sub class's setup page

   $func = $GLOBALS["tourny_module"][$_GET["create"]]["func"]["presetup"];

   $func(); //call function

   return;

  }



  if($_GET["delete"] > 0){ //delete the module

   $module =& $tourny->module((INT) $_GET["delete"]);



   if($module->id > 0){ //valid module

    //delete all matchs

    $module->del_matchs();



    //delete module

    $tourny->del_module($module->id);



    //unreference module

    unset($module);

   }



   return write_refresh("?page=admin&cmd=tourny&cmdd=modules&tournyid=".$tourny->id);

  }



  $tpl->splice("SETUP", "ap_tourny_stage_4_modules.tpl");



  //run through each sub class and give link to create module

  foreach($GLOBALS["tourny_module"] as $id => $var)  if($var["name"] != ''){

   $tpl->parse("SETUP->CMODULES_COL", "SETUP->CMODULES_COL", 1, array(

     "NAME" => $var["name"],

     "LINK" => "?page=admin&cmd=tourny&cmdd=modules&tournyid=".$tourny->id."&create=".$id

    ));



   if(++$i % 3 == 0){

    $tpl->parse("SETUP->CMODULES_LIST", "SETUP->CMODULES_ROW", 1);

    $tpl->clear("SETUP->CMODULES_COL");

  }}



  if($i % 3 != 0)//check anything not parsed

   $tpl->parse("SETUP->CMODULES_LIST", "SETUP->CMODULES_ROW", 1);



  //check for nulls

  if($tpl->fetch("SETUP->CMODULES_LIST") == '')

   $tpl->parse("SETUP->CMODULES_LIST", "SETUP->CMODULES_NONE");



  //show modules

  foreach($tourny->modules() as $key => $data)

   if($key > 0){ //possibly valid module

    $module =& $tourny->module($key);



    if($module->id > 0 && $module->get("name") != ''){ //valid module

     $tpl->parse("SETUP->MODULES_COL", "SETUP->MODULES_COL", 1, array(

       "NAME" => $module->get("name"),

       "LINK" => "?page=admin&cmd=tourny&cmdd=modules&tournyid=".$tourny->id."&module=".$module->id

      ));



     if(++$ii % 3 == 0){

      $tpl->parse("SETUP->MODULES_LIST", "SETUP->MODULES_ROW", 1);

      $tpl->clear("SETUP->MODULES_COL");

     }

   }}



  if($ii % 3 != 0)//check anything not parsed

   $tpl->parse("SETUP->MODULES_LIST", "SETUP->MODULES_ROW", 1);



  //check for nulls

  if($tpl->fetch("SETUP->MODULES_LIST") == '')

   $tpl->parse("SETUP->MODULES_LIST", "SETUP->MODULES_NONE");



  //show modules to delete

  foreach($tourny->modules() as $key => $data)

   if($key > 0){ //possibly valid module

    $module =& $tourny->module($key);



    if($module->id > 0){ //valid module

     $tpl->parse("SETUP->DMODULES_COL", "SETUP->DMODULES_COL", 1, array(

       "NAME" => $module->get("name"),

       "LINK" => "?page=admin&cmd=tourny&cmdd=modules&tournyid=".$tourny->id."&delete=".$module->id

      ));



     if(++$iii % 3 == 0){

      $tpl->parse("SETUP->DMODULES_LIST", "SETUP->DMODULES_ROW", 1);

      $tpl->clear("SETUP->DMODULES_COL");

     }

   }}



  if($iii % 3 != 0)//check anything not parsed

   $tpl->parse("SETUP->DMODULES_LIST", "SETUP->DMODULES_ROW", 1);



  //check for nulls

  if($tpl->fetch("SETUP->DMODULES_LIST") == '')

   $tpl->parse("SETUP->DMODULES_LIST", "SETUP->DMODULES_NONE");







  $tpl->parse("CONTENT", "SETUP");

 }



 switch($cmdd){

  case "modules":

   write_tourny_modules();

   break;

 }

?>