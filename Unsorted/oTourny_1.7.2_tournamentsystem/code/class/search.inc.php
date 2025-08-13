<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  Search Tables

 */



 class form_search {

  var $searchtext; //text entered in by user

  var $page;       //current page number

  var $showrows;   //number of rows to show per page

  var $clickcmd;   //link appended to id

  var $type;       //type data



  function form_search($clickcmd, $type){

   //grab all info submitted by user

   $this->get_user_entry();



   //get the search data

   $this->get_search_type($type);



   //get links for each id

   $this->clickcmd = $clickcmd;

  }



  //retrieve the correlating DB data

  function get_search_type($type){

   switch($type){

    case "game":

     $this->type = array(

       "type"  => "game",

       "name"  => "Game",

       "table" => "games",

       "col"   => "name",

       "index" => "id"

      );

     break;

    case "user":

     $this->type = array(

       "type"  => "user",

       "name"  => "Player",

       "table" => "users",

       "col"   => "name",

       "index" => "userid"

      );

     break;

    case "team":

     $this->type = array(

       "type"  => "team",

       "name"  => "Team",

       "table" => "teams",

       "col"   => "name",

       "index" => "teamid"

      );

     break;

    case "tourny":

     $this->type = array(

       "type"  => "tourny",

       "name"  => "Tournament",

       "table" => "tournaments",

       "col"   => "name",

       "index" => "tournamentid"

      );

     break;

    case "server":

     $this->type = array(

       "type"  => "server",

       "name"  => "Server",

       "table" => "servers",

       "col"   => "name",

       "index" => "serverid"

      );

     break;

    case "image":

     $this->type = array(

       "type"  => "image",

       "name"  => "Picture",

       "table" => "images",

       "col"   => "name",

       "index" => "id"

      );

     break;

    case "region":

     $this->type = array(

       "type"  => "region",

       "name"  => "Location",

       "table" => "regions",

       "col"   => "name",

       "index" => "id"

      );

     break;

    default:

     die("invalid search type");

   }

  }



  //take and edit all user entrys to search

  function get_user_entry(){global $user;

   //appearance options

   $this->page       = ($GLOBALS["pagenum"] > 1) ? (INT) $GLOBALS["pagenum"]  :  1;

   $this->showrows   = ($GLOBALS["showrows"] > 5 && $GLOBALS["showrows"] < 100) ? (INT) $GLOBALS["showrows"] : 15;



   //grab their search text

   if($user->get("admin") >= $GLOBALS["level_search"]) //admin - search rules dont apply

    $this->searchtext = $GLOBALS["searchtxt"];

   else { //common players

    if(strlen($this->searchtext) > 2) //must have atleast 3 characters

     $this->searchtext = str_replace("%", "", $GLOBALS["searchtxt"]);

   }



   //make sure it doesnt error out the db query

   $this->searchtext = convertsqlquotes($this->searchtext);

  }



  //make db query and return all results

  function query_items(){

   $items = array($this->type["index"], $this->type["col"]);

   $where = $this->type["col"]." LIKE '%" . $this->searchtext . "%'";

   $limit = (($this->page - 1) * ($this->showrows * 3)) .", ". ($this->showrows * 3);



   //query items

   $query = new db_cmd("SELECT", $this->type["table"], $items, $where, $limit);



   //only need data

   return $query->data;

  }



  function parse_form_search(){global $user, $tpl;

   $tpl->splice("SEARCH", "search.tpl");



   //grap the click cmd so the search page knows what to show

   $tpl->parse("SEARCH_DATA", "SEARCH->SEARCH_DATA" ,array(

     "SEARCH->SEARCH_DATA" => '', //hide this subtpl



     "FIELD_HIDDEN_TYPE"            => "type",

     "FIELD_HIDDEN_TYPE_VALUE"      => htmlchars($this->type["type"]),

     "FIELD_HIDDEN_SEARCHTXT"       => "searchtxt",

     "FIELD_HIDDEN_SEARCHTXT_VALUE" => htmlchars($this->searchtext),

     "FIELD_HIDDEN_CLICKCMD"        => "clickcmd",

     "FIELD_HIDDEN_CLICKCMD_VALUE"  => htmlchars($this->clickcmd),



     "TYPE" => $this->type["name"]

    ));



   //Select Message Type

   if($user->get("admin") >= $GLOBALS["level_search"])

    $tpl->parse("SEARCH->MSGS", "SEARCH->MSG_ADMIN");

   else //show message for players

    $tpl->parse("SEARCH->MSGS", "SEARCH->MSG_PLAYER");



   //default found to 0

   $found = 0;



   //query the items

   $items = $this->query_items();

   if(is_array($items)) //valid db cmd

    foreach($items as $item) if($item[$this->type["index"]] > 0){ //possibly valid id

     $found++; //up found count



     $tpl->parse("SEARCH->COL", "SEARCH->COL", true, array(

       "A_LINK" => $this->clickcmd . $item[$this->type["index"]],

       "TEXT" => $item[$this->type["col"]]

      ));



     if($found % 3 == 0){ //new row

      $tpl->parse("SEARCH->LIST", "SEARCH->ROW", true);

      $tpl->clear("SEARCH->COL");

    }}



   if($found > 0){ //found cols

    if($found % 3 != 0) //catch any rows missed

     $tpl->parse("SEARCH->LIST", "SEARCH->ROW", true);

   } else //no cols - error out

    $tpl->parse("SEARCH->LIST", "SEARCH->ROW_NONE");



   //Back/Last Page CMD

   if($this->page > 1){

    $tpl->parse("SEARCH->PAGE_BACK", "SEARCH->PAGE_BACK", array(

      "FIELD_HIDDEN_PAGENUM"       => "pagenum",

      "FIELD_HIDDEN_PAGENUM_VALUE" => $this->page - 1

     ));

    $tpl->parse("SEARCH->PAGE_LAST", "SEARCH->PAGE_LAST", array(

      "FIELD_HIDDEN_PAGENUM"       => "pagenum",

      "FIELD_HIDDEN_PAGENUM_VALUE" => 1

     ));

   }else //cant go back

    $tpl->assign(array(

      "SEARCH->PAGE_BACK" => "",

      "SEARCH->PAGE_LAST" => ""

     ));



   //Next Page CMD

   if($found == $this->showrows * 3) //did we get enough items

    $tpl->parse("SEARCH->PAGE_NEXT", "SEARCH->PAGE_NEXT", array(

      "FIELD_HIDDEN_PAGENUM"       => "pagenum",

      "FIELD_HIDDEN_PAGENUM_VALUE" => $this->page + 1

     ));

   else //cant go back

    $tpl->assign("SEARCH->PAGE_NEXT", "");



   //return page

   return $tpl->parse("SEARCH", "SEARCH", array(

     "COUNT"    => $found,

     "TYPE"     => $this->type["name"],

     "PAGE_NUM" => $this->page,



     "FIELD_SEARCHTXT"       => "searchtxt",

     "FIELD_SEARCHTXT_VALUE" => $this->searchtext

    ));

  }



  //retrieves page command

  function get_search_page($pagenum){global $tpl;

   return $tpl->fetchfile("search_form_pagenum.tpl" ,array(

     "FIELD_HIDDEN_PAGENUM" => "pagenum",

     "FIELD_HIDDEN_PAGENUM_VALUE" => $pagenum

    ));

  }



  //retrieves search page

  function get_form_search(){global $tpl;

   return $this->parse_form_search();

  }



  //sets search page as center col

  function set_search_center(){global $tpl;

   $tpl->assign("CENTER", $this->parse_form_search());

  }

 }



?>