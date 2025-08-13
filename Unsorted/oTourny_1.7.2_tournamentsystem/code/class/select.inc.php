<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  Select Functions

   Called by Index to Choose Correct Content Page to Load

 */



 //Grabs Correct Page

 function select_page($list = FALSE, $type = FALSE){global $pages;

  if($list === FALSE)//Determine Normal Page

   $list = $pages; //Use Page List



  if($type === FALSE) //Type not set

   $type = $_GET["page"]; //Page is default



  //Its a Listed Page Array

  if(isset($list["list"][$type]))

   return select_page($list[$list["list"][$type]], $_GET[$list["list"][$type]]);



  //Parsed Page

  if(isset($list["parse"][$type]))

   return select_load("parse", $list["parse"][$type]);



  //Static Page

  if(isset($list["static"][$type]))

   return select_load("static", $list["static"][$type]);

 }



 //Loads Correct Page Type

 function select_load($type, $page){global $load_page, $load_type;

  $load_page = $page;

  $load_type = $type;

 }



 //Select Page

 select_page();



 switch($load_type){

  case "static":

   $centercol = $load_page;

   break;

  case "parse":

   include($load_page);

   break;

 }



 //remove vars used

 unset($load_type); unset($load_page); unset($pages);



?>