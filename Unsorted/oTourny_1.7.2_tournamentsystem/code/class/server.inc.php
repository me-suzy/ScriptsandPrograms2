<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  Server Protocol

 */



 class db_servers extends db_table {

  var $servers; //user array - holds reference to user



  function db_servers(){

   //notify parent of db names and class

   parent::db_table("servers", "serverid", "db_server");



   //reference class list

   $this->servers =& $this->objs;

  }



  //retrieve a server

  function &server($id = 0, $create = 0){

   return parent::obj($id, $create);

  }



  //find server

  function &find_server($name){

   $query = new db_cmd("select", "servers", "serverid", "name LIKE '".$name."'", 1);



   return $this->server($query->data[0]["serverid"]);

  }



  function clear_admins($tourny){global $tournys;

   if(!is_object($tourny)) $tourny = &$tournys->tourny($tourny);



   if($tourny->id > 0)

    foreach($tourny->servers() as $serverid){

     $server = &$this->server($serverid);



     $server->clear_admins();

    }

  }

 }



 class db_server extends db_obj {

  var $admins;   //server admins



  function db_server($id, &$data, &$container){

   parent::db_obj($id, &$data, &$container);

  }



  //return array of server admins

  function admins(){

   if(!empty($this->admins)) return $this->admins;



   //check for blank

   if($this->get("admins") == '' ) return $this->admins = array();



   //explode !

   $this->admins = explode('!', $this->get("admins"));



   //check blanks

   foreach($this->admins as $key => $val)

    if($val == '') unset($this->admins[$key]);



   //grab all admins

   return $this->admins;

  }



  //is server admin

  function is_admin($id){

   return in_array($id, $this->admins());

  }



  //add server admin

  function add_admin($id){global $tournys;

   if($this->is_admin($id)) return;



   //check if they are an admin in the tourny

   $tourny = $tournys->tourny($this->get("tournyid"));

    if(!($tourny->id > 0)) return; //tourny not valid

    if(!$tourny->is_admin($id)) return; //player not tourny admin



   $this->admins[] = $id;



   $this->set("admins", implode('!', $this->admins));

  }



  //remove server admin

  function rem_admin($id){

   if(!$this->is_admin($id)) return;



   unset($this->admins[array_search($id, $this->admins)]);



   $this->set("admins", implode('!', $this->admins));

  }



  //clear server admin list

  function clear_admins(){

   unset($this->admins);



   $this->set("admins", '');

  }



  //checks a proposed property to server

  function check($type, $item=''){global $tpl;

   switch($type){

    case "name":

     //check for min lengths

     if(strlen($item) < 5 || strlen($item) > 50)  $errchk .= write_error_row($tpl->fetchfile("server_change_name_err_0.tpl"));

     break;

    case "ip":

     //check for format

     $ip = explode('.', $item);

     if(!(strlen($ip[0])>=1 && strlen($ip[0])<=3 && strlen($ip[1])>=1 && strlen($ip[1])<=3 && strlen($ip[2])>=1 && strlen($ip[2])<=3)) $errchk .= write_error_row($tpl->fetchfile("server_change_ip_err_0.tpl"));

     break;

    case "port":

     //check for min lengths

     if(strlen($item) < 1 || strlen($item) > 10)  $errchk .= write_error_row($tpl->fetchfile("server_change_port_err_0.tpl"));

     break;

    case "sapass":

     //check for min lengths

     if(strlen($item) < 5 || strlen($item) > 50)  $errchk .= write_error_row($tpl->fetchfile("server_change_sapass_err_0.tpl"));

     break;

    case "apass":

     //check for min lengths

     if(strlen($item) < 5 || strlen($item) > 50)  $errchk .= write_error_row($tpl->fetchfile("server_change_apass_err_0.tpl"));

     break;

    case "jpass":

     //check for min lengths

     if(strlen($item) < 5 || strlen($item) > 50)  $errchk .= write_error_row($tpl->fetchfile("server_change_jpass_err_0.tpl"));

     break;

    case "amsg":

     //check for min lengths

     if(strlen($item) > 1000)  $errchk .= write_error_row($tpl->fetchfile("server_change_amsg_err_0.tpl"));

     break;

    case "cmsg":

     //check for min lengths

     if(strlen($item) > 1000)  $errchk .= write_error_row($tpl->fetchfile("server_change_cmsg_err_0.tpl"));

     break;

    case "pmsg":

     //check for min lengths

     if(strlen($item) > 1000)  $errchk .= write_error_row($tpl->fetchfile("server_change_pmsg_err_0.tpl"));

     break;

    case "srvmsg":

     //check for min lengths

     if(strlen($item) > 1000)  $errchk .= write_error_row($tpl->fetchfile("server_change_srvmsg_err_0.tpl"));

     break;

   }



   return $errchk;

  }



  /*

   write server template

   type =

    0 = Anyone

    1 = player

    2 = capt

    3 = admin

  */

  function server_template($type){global $regions, $tpl;

   $region = &$regions->region( $this->get("region") );



   return $tpl->fetchfile("server_form.tpl", array(

     //Server Information:

     "SERVER_NAME"   => $this->get("name"),

     "SERVER_IP"     => $this->get("ip"),

     "SERVER_PORT"   => $this->get("port"),

     "SERVER_REGION" => $region->get("name"),

     //Server Description:

     "SERVER_DESC"   => $this->get("srvmsg"),

     //Sever Passwords

     "SERVER_PASS" => (($type >= 3 && $this->get("sapass") != '') || ($type >= 2 && $this->get("apass") != '') || ($type >= 1 && $this->get("jpass") != '')) ? $tpl->fetchfile("server_form_pass.tpl", array(

        "SERVER_SAPASS" => ($type >= 3 && $this->get("sapass") != '') ? $tpl->fetchfile("server_form_pass_sa.tpl", array("SERVER_SAPASS" => $this->get("sapass"))):'',

        "SERVER_APASS"  => ($type >= 2 && $this->get("apass") != '')  ? $tpl->fetchfile("server_form_pass_a.tpl", array("SERVER_APASS" => $this->get("apass"))):'',

        "SERVER_JPASS"  => ($type >= 1 && $this->get("jpass") != '')  ? $tpl->fetchfile("server_form_pass_j.tpl", array("SERVER_JPASS" => $this->get("jpass"))):''

       )) : '',

     //Server Messages

     "SERVER_MSG" => ( ($type >= 3 && $this->get("amsg") != '') || ($type >= 2 && $this->get("cmsg") != '') || ($type >= 1 && $this->get("pmsg") != '')) ? $tpl->fetchfile("server_form_msg.tpl", array(

        "SERVER_AMSG" => (($type >= 3 && $this->get("amsg") != '') ? $tpl->fetchfile("server_form_msg_a.tpl", array("SERVER_AMSG" => $this->get("amsg"))):''),

        "SERVER_CMSG" => (($type >= 2 && $this->get("cmsg") != '') ? $tpl->fetchfile("server_form_msg_c.tpl", array("SERVER_CMSG" => $this->get("cmsg"))):''),

        "SERVER_PMSG" => (($type >= 1 && $this->get("pmsg") != '') ? $tpl->fetchfile("server_form_msg_p.tpl", array("SERVER_PMSG" => $this->get("pmsg"))):''),

       )) : '',

     //Server Admins

     "SERVER_ADMINS" => ($this->server_template_admins() != '') ? $tpl->fetchfile("server_form_admin.tpl", array("SERVER_ADMIN" => $this->server_template_admins())) : ''

    ));

  }



  //private to clean up template function

  function server_template_admins(){global $tpl, $users;

   //define

   $tpl->define(array(

     "SA_NCOL" => "server_form_admin_col.tpl",

     "SA_NROW" => "server_form_admin_row.tpl"

    ));



   //preset vars

   $tpl->clear("COLS");

   $tpl->clear("SERVER_ADMIN");

   $tpl->clear("SERVER_ADMINS");

   $i = 0; //count of admins



   foreach($this->admins() as $adminid){

    $admin = &$users->user($adminid);



    if($admin->id > 0){

     $tpl->assign("SERVER_ADMIN", $admin->get_alink_profile());

     $tpl->parse("COLS",".SA_NCOL");



     if(++$i % 5 == 0){

      $tpl->parse("SERVER_ADMINS",".SA_NROW");

      $tpl->clear("COLS");

      $i = 0;

   }}}



   if($i % 5 != 0) $tpl->parse("SERVER_ADMINS",".SA_NROW");



   return $tpl->fetch("SERVER_ADMINS");

  }



 }



?>