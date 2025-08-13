<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;



 function write_request(){global $tpl, $tournysauth, $user;

  $tpl->splice("TOURNY", "tourny_request.tpl");



  if(isset($_POST["tourny_name"]) && $user->id > 0){

   $errchk = $tournysauth->check($_POST["tourny_name"], $_POST["tourny_type"], $_POST["tourny_text"]);



   if($errchk == ''){//no errors

    $tourny = &$tournysauth->tourny(0, 1);



    $tourny->set(array(

     "name"     => htmlchars($_POST["tourny_name"]),

     "type"     => (INT) $_POST["tourny_type"],

     "creator"  => $user->id,

     "purpose"  => htmlchars($_POST["tourny_text"]),

     "time"     => time(),

     "draft"    => (BOOL) $_POST["tourny_draft"],

     "maxmatch" => $_POST["tourny_max"] < 3 || $_POST["tourny_max"] > 999 ? 2 : (INT) $_POST["tourny_max"]

    ));



    $tpl->parse("CENTER", "TOURNY->FINISH");

    return;

  }}



  $tpl->parse("CENTER", "TOURNY", array(

    "TOURNY->FINISH"   => '', //hide finish text



    "TOURNY->ERRORS"   => ($errchk == '') ? '' : write_error_common($errchk),



    "FIELD_NAME_NAME"  => "tourny_name",

    "FIELD_NAME_VALUE" => htmlchars($_POST["tourny_name"]),

    "FIELD_MAX_NAME"   => "tourny_max",

    "FIELD_MAX_VALUE"  => $_POST["tourny_max"] < 3 || $_POST["tourny_max"] > 999 ? 2 : (INT) $_POST["tourny_max"],



    "FIELD_TYPE_0"       => "tourny_type",

    "FIELD_TYPE_0_VALUE" => "1",

    "FIELD_TYPE_1"       => "tourny_type",

    "FIELD_TYPE_1_VALUE" => "2",

    "FIELD_TYPE_0_CHECKED" => $_POST["tourny_type"] == 0 ? "CHECKED" : '',

    "FIELD_TYPE_1_CHECKED" => $_POST["tourny_type"] == 1 ? "CHECKED" : '',



    "FIELD_DRAFT_NAME"  => "tourny_draft",

    "FIELD_DRAFT_VALUE" => true,

    "FIELD_DRAFT_CHECK" => $_POST["tourny_draft"] ? "checked=\"true\"" : '',



    "FIELD_TEXT"       => "tourny_text",

    "FIELD_TEXT_VALUE" => htmlchars($_POST["tourny_text"]),

   ));

 }



 //must be valid user

 if($user->id > 0) write_request();

?>