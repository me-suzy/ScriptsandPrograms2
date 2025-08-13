<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  Team Status Protocol

 */



 class db_teams_status {

  var $status;



  function db_teams_status(){

   $this->status = array("Recruiting", "Active", "In-Active", "Retired");

  }



  //retrieve list of types

  function get($id = NULL){

   if($id != NULL) return array_search($id, $this->status);

   else return $this->status;

  }



  //check if status is valid

  function check($id){

   return in_array($id, $this->status);

  }



  function update_db(){

   //nuttin

  }



 }



?>