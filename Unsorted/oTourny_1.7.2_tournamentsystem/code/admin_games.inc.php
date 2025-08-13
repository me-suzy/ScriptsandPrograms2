<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;



 function write_game_mod(){global $apanel, $tpl, $games;

  if(isset($_POST["name"])){

   if($_POST["id"] == -1) $game = &$games->game(0,1);

   else $game = &$games->game($_GET["id"]);



   $game->set(array(

     "name"  => $_POST["name"],

     "qstat" => $_POST["qstat"]

    ));



   unset($game);

  }



  $game = &$games->game($_GET["id"]);



  $tpl->assign(array(

    "FIELD_GAME_MAX"    => "30",

    "FIELD_GAME_NAME"   => "name",

    "FIELD_GAME_VALUE"  => htmlchars($game->get("name")),

    "FIELD_QSTAT_MAX"   => "3",

    "FIELD_QSTAT_NAME"  => "qstat",

    "FIELD_QSTAT_VALUE" => htmlchars($game->get("qstat"))

   ));



  $apanel->set_cnt("ap_games_mod.tpl", 1);

 }



 function write_game_del(){global $apanel, $tpl, $games;

  $game = &$games->game($_GET["id"]);



  if(isset($_POST["submit"])){

   $game->delete();



   echo write_refresh("?page=admin&cmd=games&cmdd=list",0);

   return;

  }



  $tpl->assign(array(

    "FIELD_SUBMIT_NAME" => "submit",

    "GAME" => htmlchars($game->get("name"))

   ));



  $apanel->set_cnt("ap_games_del.tpl", 1);

 }



 if(!($id > 0) || $id == -1) $cmdd = "sel";



 switch($cmdd){

  case "sel":

   $search = new form_search("?page=admin&cmd=games&cmdd=mod&id=", 'game');

   $apanel->set_cnt($search->get_form_search());

   break;

  case "mod":

   write_game_mod();

   break;

  case "del":

   write_game_del();

   break;

 }

?>