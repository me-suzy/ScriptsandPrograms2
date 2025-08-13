<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  Region Class

 */



 class db_regions extends db_table {

  var $regions; //region array - holds reference to regions



  function db_regions(){

   //notify parent of db names and class

   parent::db_table("regions", "id", "db_region");



   //reference class list

   $this->regions =& $this->objs;

  }



  //retrieve a region

  function &region($id = 0, $create = 0){

   return parent::obj($id, $create);

  }



  //find region

  function &find_region($name){

   $query = new db_cmd("select", "regions", "id", "name LIKE '".convertsqlquotes($name)."'", 1);



   return $this->region($query->data[0]["id"]);

  }

 }



 class db_region extends db_obj {

  function db_region($id, &$data, &$container){

   parent::db_obj($id, &$data, &$container);

  }

 }



 //write region option list

 function write_srvlocation_optlist($list){

  $query = new db_cmd("select", "regions", array("id", "name"), '', '', "name ASC");



  foreach($query->data as $region)

   $regionlist .= write_option($region["name"], findvalue($list, $region["id"]));



  return $regionlist;

 }



 //adds a location into a list by location name

 function addlistlocation($list, $location){global $regions;

  $region = &$regions->find_region($location);



  if($region->id > 0) return addvalue($list, $region->id);

  return $list;

 }



?>