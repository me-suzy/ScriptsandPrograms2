<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/



 function write_verify(){global $tpl;

  $tpl->splice("SETUP", "verify.tpl");



  //check the dbs

  $db  = verify_db();



  //check db

  if($db["connect"]["valid"]){

   $tpl->parse("SETUP->DB_STATUS", "SETUP->DB_STATUS_GOOD");



   //are the tables present?

   if($db["tables"]["valid"])

    $tpl->parse("SETUP->DB_TABLE", "SETUP->DB_TABLE_GOOD");

   else //no tables

    $tpl->parse("SETUP->DB_TABLE", "SETUP->DB_TABLE_FAIL", array(

     "ERROR" => $db["connect"]["error"]

    ));

  }else{ //didnt work

   $tpl->parse("SETUP->DB_STATUS", "SETUP->DB_STATUS_FAIL", array(

     "ERROR" => $db["connect"]["error"]

    ));



   $tpl->parse("SETUP->DB_TABLE", "SETUP->DB_TABLE_FAIL", array(

     "ERROR" => ''

    ));

  }



  //check dbf

  $dbf = install_verify_dbf();



  //tell status of dbf

  $dbf_valid = $dbf["connect"]["valid"] && $dbf["tables"]["valid"];



  if($dbf["connect"]["valid"]){

   $tpl->parse("SETUP->DBF_STATUS", "SETUP->DBF_STATUS_GOOD");



   //are the tables present?

   if($dbf["tables"]["valid"])

    $tpl->parse("SETUP->DBF_TABLE", "SETUP->DBF_TABLE_GOOD");

   else //no tables

    $tpl->parse("SETUP->DBF_TABLE", "SETUP->DBF_TABLE_FAIL", array(

     "ERROR" => $dbf["connect"]["error"]

    ));



   if(is_array($dbf["forums"]["list"])) //valid array type

   if(!empty($dbf["forums"]["list"])) //list out options

    foreach($dbf["forums"]["list"] as $data)

     if($data["forum_name"] != '') //probably valid forum name

      $tpl->parse("SETUP->FORUM_LIST", "SETUP->FORUM_LIST", true, array(

        "NAME" => $data["forum_name"],

        "ID"   => $data["forum_id"]

       ));



   if($tpl->fetch("SETUP->FORUM_LIST") == '') //invalid list

    $tpl->parse("SETUP->FORUM_NEWS_LIST", "SETUP->FORUM_NEWS_ERROR", array(

      "ERROR" => $dbf["forums"]["error"]

     ));

   else //valid list

    $tpl->parse("SETUP->FORUM_NEWS_LIST", "SETUP->FORUM_SELECT");



   $tpl->parse("SETUP->DBF_NEWS", "SETUP->DBF_NEWS");

  }else{ //didnt work

   $tpl->parse("SETUP->DBF_STATUS", "SETUP->DBF_STATUS_FAIL", array(

     "ERROR" => $dbf["connect"]["error"]

    ));



   $tpl->parse("SETUP->DBF_TABLE", "SETUP->DBF_TABLE_FAIL", array(

     "ERROR" => ''

    ));



   $tpl->parse("SETUP->FORUM_NEWS_LIST", "SETUP->FORUM_NEWS_ERROR", array(

      "ERROR" => $dbf["forums"]["error"]

     ));

   $tpl->parse("SETUP->DBF_NEWS", "SETUP->DBF_NEWS");

  }



  $tpl->parse("SETUP->DBF", "SETUP->DBF");



  //Prepare their entrys for later

  $hidden = array(

    "DB_HOST"    => $_POST["db_host"],

    "DB_NAME"    => $_POST["db_name"],

    "DB_PASS"    => $_POST["db_pass"],

    "DB_USER"    => $_POST["db_user"],

    "DBF_NAME"   => $_POST["dbf_name"],

    "DBF_PASS"   => $_POST["dbf_pass"],

    "DBF_USER"   => $_POST["dbf_user"],

    "FORUM_PREFIX" => $_POST["dbf_prefix"],

    "FORUM_CLASS"  => $_POST["dbf_type"],

    "SITE_DNS"   => $_POST["site_dns"],

    "SITE_URL"   => $_POST["site_url"],

    "SITE_NAME"  => $_POST["site_name"],

    "SITE_EMAIL" => $_POST["site_email"],

    "SQL_DB"     => $_POST["sqlserver"],

   );



  $tpl->assign(array(

    "FIELD_SUBMIT"       => "submit",

    "FIELD_HIDDEN"       => "carryover",

    "FIELD_HIDDEN_VALUE" => htmlspecialchars(serialize($hidden)),

    "FIELD_NEWS_FORUM"   => "news_id"

   ));



  //check that everything is valid

  if($dbf_valid && $db["connect"]["valid"] && $db["tables"]["valid"])

   $tpl->parse("SETUP->STATUS", "SETUP->STATUS_GOOD");

  else //make them redo it

   $tpl->parse("SETUP->STATUS", "SETUP->STATUS_FAIL");



  $tpl->parse("CONTENT", "SETUP");

 }



 //load db api - Using their Sql Server Type

 require_once('./code/class/db/'.$_POST["sqlserver"].'.php');

 require_once('./code/class/dbase.inc.php');

 require_once('./code/class/forum/'.$_POST["dbf_type"].'.inc.php');



 switch($page){

  default:

   write_verify();

 }

?>