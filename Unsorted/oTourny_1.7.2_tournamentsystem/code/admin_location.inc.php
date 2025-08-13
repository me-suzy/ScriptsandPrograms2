<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;



 function write_location_mod(){global $tpl, $apanel, $regions, $region;

  if($_POST["submit"])

  if($_POST["lon"] > '' && $_POST["lat"] > '' &&  $_POST["name"] > ''){

   if($_GET["id"] == -1) $region = &$regions->region(0,1);



   $region->set(array(

     "name"      => htmlchars($_POST["name"]),

     "longitude" => $_POST["lon"],

     "latitude"  => $_POST["lat"]

    ));



   echo write_refresh("?page=admin&cmd=locations&cmdd=mod&id=".$region->id,0);

   return;

  }



  $tpl->assign(array(

    "FIELD_SUBMIT_NAME" => "submit",

    "FIELD_NAME_MAX"    => "70",

    "FIELD_NAME_NAME"   => "name",

    "FIELD_NAME_VALUE"  => $region->get("name"),

    "FIELD_LON_MAX"     => "4",

    "FIELD_LON_NAME"    => "lon",

    "FIELD_LON_VALUE"   => $region->get("longitude"),

    "FIELD_LAT_MAX"     => "4",

    "FIELD_LAT_NAME"    => "lat",

    "FIELD_LAT_VALUE"   => $region->get("latitude")

   ));



  $apanel->set_cnt("ap_loc_mod.tpl", 1);

 }



 function write_location_del(){global $tpl, $apanel, $regions, $region;

  if($_POST["submit"]){

   $region->delete();



   echo write_refresh("?page=admin&cmd=locations",0);

   return;

  }



  $tpl->assign(array(

    "LOCATION"          => $region->get("name"),

    "FIELD_SUBMIT_NAME" => "submit"

   ));



  $apanel->set_cnt("ap_loc_del.tpl", 1);

 }



 $region = &$regions->region($_GET["id"]);

 if(!$region->id > 0)  $cmdd = "sel";

 if($_GET["id"] == -1) $cmdd = "mod";



 switch($cmdd){

  case "sel":

   $search = new form_search("?page=admin&cmd=locations&cmdd=mod&id=", 'region');

   $apanel->set_cnt($search->get_form_search());

   break;

  case "mod":

   write_location_mod();

   break;

  case "del":

   write_location_del();

   break;

 }

?>