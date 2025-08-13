<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  Game List Class

 */



 class db_games extends db_table {

  var $games; //game array - holds reference to game



  function db_games(){

   //notify parent of db names and class

   parent::db_table("games", "id", "db_game");



   //reference class list

   $this->games =& $this->objs;

  }



  //retrieve a email

  function &game($id = 0, $create = 0){

   return parent::obj($id, $create);

  }



  //find game

  function &find_game($name){

   $query = new db_cmd("select", "games", "id", "name LIKE '".convertsqlquotes($name)."'", 1);



   return $this->game($query->data[0]["id"]);

  }

 }



 class db_game extends db_obj {

  function db_game($id, &$data, &$container){

   parent::db_obj($id, &$data, &$container);

  }

 }



 //writes out option list of games

 function write_game_optlist($list){

  $query = new db_cmd("select", "games", array("id", "name"), '', '', "name ASC");



  foreach($query->data as $game)

   $gamelist .= write_option($game["name"], findvalue($list, $game["id"]));



  return $gamelist;

 }



 //retrive game by index

 function getgame($id){global $games;

  $game = &$games->game($id);



  if($game->id > 0) return $game->get("name");

 }



 //adds game by name to list

 function addlistgame($list, $name){global $games;

  $game = &$games->find_game($name);



  if($game->id > 0) return addvalue($list,$game->id);

  else return $list;

 }



?>