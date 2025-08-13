<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/



 function write_config(){global $tpl;

  if(!isset($_POST["carryover"])) return; //bad user



  //assign vars from setup page

  $tpl->assign(unserialize(stripslashes($_POST["carryover"])));



  //save forum news id

  $tpl->assign("FORUM_NEWS", $_POST["news_id"]);



  //parse config file

  $tpl->parsefile("CONFIG", "config.tpl");

  //convert parse file

  $tpl->assign("CONFIG", htmlchars($tpl->fetch("CONFIG")));

  //parse page

  $tpl->parsefile("CONTENT", "show.tpl");

 }



 //Tell user what they need to do

 function write_presetup(){global $tpl;

  if(isset($_POST["submit"])) //they have read it or dont care

   return write_refresh("install.php?page=install&cmd=setup");



  $tpl->parsefile("CONTENT", "presetup.tpl", array(

    "FIELD_SUBMIT" => "submit"

   ));

 }



 //Grab all initial info we need

 function write_setup(){global $tpl, $forum_module;

  $tpl->splice("SETUP", "setup.tpl");



  foreach($forum_module as $module)

   $tpl->parse("SETUP->FORUMS", "SETUP->FORUMS", array(

     "NAME"  => $module["name"],

     "CLASS" => $module["class"]

    ));



  if($tpl->fetch("SETUP->FORUMS") == '') //hide blank list

   $tpl->assign("SETUP->FORUMS", '');



  $tpl->assign(array(

    "FIELD_SUBMIT"           => "submit",

    "FIELD_SITE_NAME"        => "site_name",

    "FIELD_SITE_NAME_VALUE"  => "",

    "FIELD_SITE_DNS"         => "site_dns",

    "FIELD_SITE_DNS_VALUE"   => "",

    "FIELD_SITE_URL"         => "site_url",

    "FIELD_SITE_URL_VALUE"   => "",

    "FIELD_SITE_EMAIL"       => "site_email",

    "FIELD_SITE_EMAIL_VALUE" => "",

    "FIELD_SQL_SRV"          => "sqlserver",

    "FIELD_DB_HOST"          => "db_host",

    "FIELD_DB_HOST_VALUE"    => "localhost",

    "FIELD_DB_NAME"          => "db_name",

    "FIELD_DB_NAME_VALUE"    => "",

    "FIELD_DB_USER"          => "db_user",

    "FIELD_DB_USER_VALUE"    => "",

    "FIELD_DB_PASS"          => "db_pass",

    "FIELD_DB_PASS_VALUE"    => "",

    "FIELD_DBF_NAME"         => "dbf_name",

    "FIELD_DBF_NAME_VALUE"   => "",

    "FIELD_DBF_USER"         => "dbf_user",

    "FIELD_DBF_USER_VALUE"   => "",

    "FIELD_DBF_PASS"         => "dbf_pass",

    "FIELD_DBF_PASS_VALUE"   => "",

    "FIELD_DBF_PRE"          => "dbf_prefix",

    "FIELD_DBF_PRE_VALUE"    => "phpbb_",

    "FIELD_FORUM_TYPE"       => "dbf_type"

   ));



  $tpl->parse("CONTENT", "SETUP");

 }



 switch($cmd){

  case "config":

   write_config();

   break;

  case "setup":

   write_setup();

   break;

  default:

   write_presetup();

 }

?>